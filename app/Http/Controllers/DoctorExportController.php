<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorExportController extends Controller
{
    public function export(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();

        $type = $request->query('type'); // daily or weekly

        // Validate input
        if (!in_array($type, ['daily', 'weekly'])) {
            return redirect()->back()->withErrors('Invalid export type.');
        }

        // Fetch the schedule data for export according to $type

        // Generate CSV, Excel, PDF or any export format (implement as per your requirement)
        // For now, just return a placeholder response

        return response()->download(storage_path("exports/{$doctor->id}_schedule_{$type}.csv"));
    }
}
