<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            DepartmentSeeder::class,
            AppointmentTypeSeeder::class,
            DoctorSeeder::class,
            PatientSeeder::class,
            DoctorScheduleSeeder::class,
            DoctorAppointmentTypeSeeder::class,
            DoctorAvailabilityExceptionSeeder::class,
        ]);
    }
}
