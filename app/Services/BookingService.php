<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\DoctorAvailabilityException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function getAvailableSlots($doctor, $date, $appointmentTypeId)
    {
        $weekday = Carbon::parse($date)->dayOfWeek;

        $schedule = $doctor->schedules()->where('weekday', $weekday)->first();
        if (!$schedule) return [];

        $duration = AppointmentType::find($appointmentTypeId)?->duration_minutes ?? 0;
        if ($duration === 0) return [];

        $exceptions = DoctorAvailabilityException::where('doctor_id', $doctor->id)
            ->where('date', $date)->first();
        if ($exceptions && !$exceptions->is_available) return [];

        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);
        $slots = [];

        while ($start->copy()->addMinutes($duration)->lte($end)) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addMinutes($duration);

            $exists = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $date)
                ->where(function ($q) use ($slotStart, $slotEnd) {
                    $q->whereBetween('start_time', [$slotStart, $slotEnd->subMinute()])
                      ->orWhereBetween('end_time', [$slotStart->addMinute(), $slotEnd]);
                })->exists();

            if (!$exists && now()->addHours(4)->lte($slotStart)) {
                $slots[] = [
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];
            }

            $start->addMinutes($duration);
        }

        return $slots;
    }

    public function bookAppointment($patientId, $doctorId, $appointmentTypeId, $date, $startTime)
    {
        $duration = AppointmentType::find($appointmentTypeId)?->duration_minutes ?? 0;
        $endTime = Carbon::parse($startTime)->addMinutes($duration);

        $conflict = Appointment::where('doctor_id', $doctorId)
            ->where('date', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime]);
            })->exists();

        if ($conflict || Carbon::parse($startTime)->lt(now()->addHours(4))) {
            throw ValidationException::withMessages(['slot' => 'Slot unavailable']);
        }

        return Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'appointment_type_id' => $appointmentTypeId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'booked',
        ]);
    }

    public function reschedule($appointmentId, $newDate, $newStartTime)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        if (
            $appointment->reschedule_count >= 1 ||
            now()->diffInHours(Carbon::parse($appointment->start_time), false) < 12
        ) {
            throw ValidationException::withMessages(['reschedule' => 'Not allowed']);
        }

        $new = $this->bookAppointment(
            $appointment->patient_id,
            $appointment->doctor_id,
            $appointment->appointment_type_id,
            $newDate,
            $newStartTime
        );

        $appointment->update(['status' => 'rescheduled', 'reschedule_count' => 1]);

        return $new;
    }

    public function cancel($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->update(['status' => 'cancelled']);
    }
}
