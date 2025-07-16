<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppointmentType;

class AppointmentTypeSeeder extends Seeder
{
    public function run()
    {
        AppointmentType::insert([
            ['name' => 'Physical Visit', 'duration_minutes' => 30],
            ['name' => 'Diagnostic', 'duration_minutes' => 20],
            ['name' => 'Follow-up', 'duration_minutes' => 15],
        ]);
    }
}

