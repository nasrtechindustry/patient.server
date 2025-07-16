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

        $workingHours = $doctor->working_hours ?? []; 
        $appointmentTypes = AppointmentType::all();
        $exceptions = $doctor->exceptions()->orderBy('date')->get();

        // Filters
        $filterDate = $request->get('filter_date');
        $filterDepartment = $request->get('filter_department');

        $appointmentsQuery = Appointment::with(['patient', 'appointmentType'])
            ->where('doctor_id', $doctor->id);

        if ($filterDate) {
            $appointmentsQuery->where('date', $filterDate);
        }
        if ($filterDepartment) {
            // Assuming doctor's departments relationship & filter by dept id if needed
            $appointmentsQuery->whereHas('doctor.departments', function ($q) use ($filterDepartment) {
                $q->where('id', $filterDepartment);
            });
        }

        $appointments = $appointmentsQuery->orderBy('date', 'desc')->paginate(10);

        $departments = $doctor->departments;

        return view('dashboard.doctor', compact(
            'workingHours',
            'appointmentTypes',
            'exceptions',
            'appointments',
            'departments'
        ));
    }
}
