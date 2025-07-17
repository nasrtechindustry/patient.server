<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\BookingService;
use Illuminate\Http\Request;

class DoctorAppointmentController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function cancel($appointmentId)
    {
        try {
            $this->bookingService->cancel($appointmentId);
            return redirect()->back()->with('success', 'Appointment cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to cancel appointment.');
        }
    }

    public function showRescheduleForm($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        $bookingService = app(BookingService::class);

        $availableSlotsResponse = $bookingService->availableSlots(new Request([
            'doctor_id' => $appointment->doctor_id,
            'appointment_type_id' => $appointment->appointment_type_id,
            'date' => $appointment->date,
        ]));

        $availableSlotsData = json_decode($availableSlotsResponse->getContent(), true);

        $availableSlots = is_array($availableSlotsData['original'] ?? null) ? $availableSlotsData['original'] : [];

        return view('dashboard.doctor.reschedule-form', compact('appointment', 'availableSlots'));
    }



    public function reschedule(Request $request, $appointmentId)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
        ]);

        try {
            $newAppointment = $this->bookingService->reschedule(
                $appointmentId,
                $request->input('date'),
                $request->input('start_time')
            );

            return redirect()->route('doctor.dashboard.appointments')->with('success', 'Appointment rescheduled successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Failed to reschedule appointment.');
        }
    }
}
