<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorScheduleSeeder extends Seeder
{
    public function run()
    {
        Doctor::all()->each(function ($doctor) {
            foreach (range(1, 5) as $weekday) {
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'weekday' => $weekday,
                    'start_time' => '09:00',
                    'end_time' => '16:00',
                ]);
            }
        });
    }
}
