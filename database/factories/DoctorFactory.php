<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;


class DoctorFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'password' => Hash::make('password'),
            'email' => $this->faker->email,
            'specialization' => $this->faker->randomElement(['Cardiologist', 'Pediatrician', 'Surgeon']),
        ];
    }
}

