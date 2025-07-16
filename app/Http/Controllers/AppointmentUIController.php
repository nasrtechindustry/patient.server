<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\AppointmentType;
use Illuminate\Http\Request;
use App\Services\BookingService;

class AppointmentUIController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }


    public function create()
    {
        return view('appointments.create', [
            'doctors' => Doctor::all(),
            'types' => AppointmentType::all()
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_type_id' => 'required|exists:appointment_types,id',
            'date' => 'required|date',
            'start_time' => 'required'
        ]);

        $this->bookingService->bookAppointment(
            auth()->guard('patient')->id(),
            $data['doctor_id'],
            $data['appointment_type_id'],
            $data['date'],
            $data['start_time']
        );

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment booked successfully.');
    }

    public function index()
    {
        $appointments = Appointment::with('doctor', 'appointmentType')
            ->where('patient_id', auth()->guard('patient')->id())
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get();

        return view('appointments.index', compact('appointments'));
    }

    public function cancel($id)
    {
        $this->bookingService->cancel($id);
        return redirect()->back()->with('status', 'Appointment cancelled.');
    }

    public function reschedule(Request $request, $id)
    {
        $data = $request->validate([
            'new_date' => 'required|date',
            'new_start_time' => 'required'
        ]);

        $this->bookingService->reschedule($id, $data['new_date'], $data['new_start_time']);

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment rescheduled.');
    }


    public function availableSlots(Request $request)
    {
        $doctor = Doctor::findOrFail($request->doctor_id);

        $slots = $this->bookingService->getAvailableSlots(
            $doctor,
            $request->date,
            $request->appointment_type_id
        );

        return response()->json($slots);
    }

    public function book(Request $request)
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_type_id' => 'required|exists:appointment_types,id',
            'date' => 'required|date',
            'start_time' => 'required'
        ]);

        $appointment = $this->bookingService->bookAppointment(
            auth()->guard('patient')->id(),
            $data['doctor_id'],
            $data['appointment_type_id'],
            $data['date'],
            $data['start_time']
        );

        return response()->json($appointment);
    }
}
