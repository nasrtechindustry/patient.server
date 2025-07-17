<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\DoctorAvailabilityException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Doctor;


class BookingService
{


    public function availableSlots(Request $request)
    {


        $doctorId = $request->doctor_id;
        $appointmentTypeId = $request->appointment_type_id;
        $date = $request->date;

        if (!$doctorId || !$appointmentTypeId || !$date) {
            return response()->json(["success" => false, "message" => "Do not receiving any of the three data"]);
        }

        $doctor = Doctor::find($doctorId);
        $appointmentType = AppointmentType::find($appointmentTypeId);

        if (!$doctor || !$appointmentType) {
            return response()->json(["success" => false, "message" => "No doctor or apointment with tha id"]);
        }


        // Parse working_hours from JSON
        $workingHours = json_decode((string) $doctor->working_hours, true);
        $weekday = strtolower(Carbon::parse($date)->format('l'));


        if (
            !isset($workingHours[$weekday]) ||
            !in_array((string)$appointmentTypeId, $workingHours[$weekday]['types'] ?? [])
        ) {
            return response()->json([
                "success" => false,
                "message" => "Selected appointment type is not available for this doctor on the chosen day."
            ]);
        }

        $start = Carbon::parse($date . ' ' . $workingHours[$weekday]['start']);
        $end = Carbon::parse($date . ' ' . $workingHours[$weekday]['end']);
        $duration = $appointmentType->duration_minutes;


        $slots = [];

        while ($start->copy()->addMinutes($duration)->lte($end)) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addMinutes($duration);


            $exists = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $date)
                ->where(function ($q) use ($slotStart, $slotEnd) {
                    $q->whereBetween('start_time', [$slotStart, $slotEnd->subMinute()])
                        ->orWhereBetween('end_time', [$slotStart->addMinute(), $slotEnd]);
                })
                ->exists();


            if (!$exists) {
                $slots[] = [
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                ];
            }



            $start->addMinutes($duration);
        }

        if (empty($slots)) {
            return response()->json([
                'success' => false,
                'message' => 'No available slots for the selected date and type.'
            ]);
        }

        return response()->json($slots);
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

        if ($conflict) {
            throw ValidationException::withMessages(['slot' => 'This time slot is already booked.']);
        }

        if (Carbon::parse($startTime)->lt(now()->addHours(4))) {
            throw ValidationException::withMessages(['slot' => 'Appointments must be booked at least 4 hours in advance.']);
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
        $originalStart = Carbon::parse($appointment->date . ' ' . $appointment->start_time);

        if (
            $appointment->reschedule_count >= 1 ||
            now()->diffInHours($originalStart, false) < 12
        ) {
            throw ValidationException::withMessages([
                'reschedule' => 'Reschedule not allowed. Only one reschedule allowed and it must be done at least 12 hours before the appointment.'
            ]);
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
