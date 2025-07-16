<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\AppointmentType;

class DoctorAppointmentTypeSeeder extends Seeder
{
    public function run()
    {
        $types = AppointmentType::all();
        Doctor::all()->each(function ($doctor) use ($types) {
            foreach (range(1, 5) as $weekday) {
                $doctor->appointmentTypes()->attach(
                    $types->random(2)->pluck('id'),
                    ['weekday' => $weekday]
                );
            }
        });
    }
}

