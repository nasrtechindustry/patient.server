<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorAvailabilityException;
use App\Models\Doctor;

class DoctorAvailabilityExceptionSeeder extends Seeder
{
    public function run()
    {
        Doctor::all()->each(function ($doctor) {
            DoctorAvailabilityException::create([
                'doctor_id' => $doctor->id,
                'date' => now()->addDays(3)->format('Y-m-d'),
                'is_available' => false,
                'reason' => 'Personal Leave',
            ]);
        });
    }
}
