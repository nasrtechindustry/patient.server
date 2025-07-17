<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DoctorExportController extends Controller
{
    public function export(Request $request)
    {
        $doctor = Auth::guard('doctor')->user();
        $type = $request->query('type'); // 'daily' or 'weekly'

        if (!in_array($type, ['daily', 'weekly'])) {
            return redirect()->back()->withErrors('Invalid export type.');
        }

        $today = now()->startOfDay();

        $appointments = Appointment::with(['patient', 'appointmentType'])
            ->where('doctor_id', $doctor->id)
            ->when($type === 'daily', fn($q) => $q->whereDate('date', $today))
            ->when($type === 'weekly', fn($q) => $q->whereBetween('date', [
                $today->copy()->startOfWeek(),
                $today->copy()->endOfWeek()
            ]))
            ->orderBy('date')
            ->get();

        $csvData = $this->generateCSV($appointments);

        $filePath = storage_path("app/exports/{$doctor->id}_schedule_{$type}.csv");

        // Make sure the directory exists
        File::ensureDirectoryExists(dirname($filePath));

        // Save CSV data directly
        File::put($filePath, $csvData);

        // Return as download
        return response()->download($filePath);
    }


    private function generateCSV($appointments): string
    {
        $rows = [];
        $header = ['Date', 'Start Time', 'End Time', 'Patient Name', 'Appointment Type', 'Status'];
        $rows[] = implode(',', $header);

        foreach ($appointments as $a) {
            $rows[] = implode(',', [
                $a->date->format('Y-m-d'),
                $a->start_time,
                $a->end_time,
                $a->patient->full_name ?? 'N/A',
                $a->appointmentType->name ?? 'N/A',
                ucfirst($a->status),
            ]);
        }

        return implode("\n", $rows);
    }
}
