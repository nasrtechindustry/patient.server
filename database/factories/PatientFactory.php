<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PatientFactory extends Factory
{
    public function definition()
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => $this->faker->phoneNumber(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'dob' => $this->faker->date('Y-m-d', '-18 years'),
            'national_id' => $this->faker->uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
