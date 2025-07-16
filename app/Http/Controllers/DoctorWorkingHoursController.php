<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorWorkingHoursController extends Controller
{
    public function edit()
    {
        $doctor = Auth::guard('doctor')->user();

        $workingHours = $doctor->working_hours ? json_decode($doctor->working_hours, true) : [];

        dd($workingHours);

        return view('doctor.working_hours.edit', compact('workingHours'));
    }

    public function update(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        $validated = $request->validate([
            'working_hours' => 'required|array',
            'working_hours.*.start' => 'nullable|date_format:H:i',
            'working_hours.*.end' => 'nullable|date_format:H:i|after:working_hours.*.start',
            'working_hours.*.types' => 'nullable|array',
            'working_hours.*.types.*' => 'exists:appointment_types,id',
        ]);

        // Save the working hours data as needed, e.g., JSON or related model

        $doctor->working_hours = $validated['working_hours'];
        $doctor->save();

        return redirect()->back()->with('success', 'Working hours updated successfully.');
    }
}
