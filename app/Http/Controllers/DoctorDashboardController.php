<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\AppointmentType;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $doctor = auth()->guard('doctor')->user();

        $workingHours = $doctor->working_hours
            ? json_decode($doctor->working_hours, true)
            : [];


        $recentAppointments = $doctor->appointments()
            ->with('patient', 'appointmentType')
            ->latest('date')
            ->limit(5)
            ->get();

        $today = now()->toDateString();

        $todayAppointmentsCount = $doctor->appointments()
            ->where('date', $today)
            ->count();
        $totalPatients = $doctor->appointments()
            ->distinct('patient_id')
            ->count('patient_id');

        $upcomingAppointmentsCount = $doctor->appointments()
            ->where('date', '>', $today)
            ->where('status', '!=', 'cancelled')
            ->count();

        $cancelledAppointmentsCount = $doctor->appointments()
            ->where('status', 'cancelled')
            ->count();

        return view('dashboard.doctor', compact(
            'workingHours',
            'recentAppointments',
            'todayAppointmentsCount',
            'totalPatients',
            'upcomingAppointmentsCount',
            'cancelledAppointmentsCount'
        ));
    }



    public function profile()
    {
        return view('dashboard.doctor.profile');
    }

    public function workingHours()
    {
        $doctor = auth()->guard('doctor')->user();
        $workingHours = $doctor->working_hours ?? [];
        $appointmentTypes = AppointmentType::all();

        return view('dashboard.doctor.working-hours', compact('workingHours', 'appointmentTypes'));
    }

    public function exceptions()
    {
        $doctor = auth()->guard('doctor')->user();
        $exceptions = $doctor->exceptions()->orderBy('date')->get();

        return view('dashboard.doctor.exceptions', compact('exceptions'));
    }

    public function appointments(Request $request)
    {
        $doctor = auth()->guard('doctor')->user();
        $filterDate = $request->get('filter_date');
        $filterDepartment = $request->get('filter_department');

        $appointmentsQuery = Appointment::with(['patient', 'appointmentType'])
            ->where('doctor_id', $doctor->id);

        if ($filterDate) {
            $appointmentsQuery->where('date', $filterDate);
        }
        if ($filterDepartment) {
            $appointmentsQuery->whereHas('doctor.departments', function ($q) use ($filterDepartment) {
                $q->where('id', $filterDepartment);
            });
        }

        $appointments = $appointmentsQuery->orderBy('date', 'desc')->paginate(10);
        $departments = $doctor->departments;

        return view('dashboard.doctor.appointments', compact('appointments', 'departments'));
    }

    public function export()
    {
        return view('dashboard.doctor.export');
    }
}
