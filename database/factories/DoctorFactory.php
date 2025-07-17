<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class DoctorFactory extends Factory
{
    public function definition()
    {
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];

        $workingHours = [];
        foreach ($days as $day) {
            $start = $this->faker->optional()->time('H:i');
            $end = $start ? \Carbon\Carbon::createFromFormat('H:i', $start)->addMinutes(60)->format('H:i') : null;

            $workingHours[$day] = [
                'start' => $start,
                'end' => $end,
                'types' => $this->faker->randomElements([1, 2, 3], rand(1, 2)),
            ];
        }

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'specialization' => $this->faker->randomElement(['Cardiologist', 'Pediatrician', 'Surgeon']),
            'working_hours' => json_encode($workingHours),
        ];
    }
}

