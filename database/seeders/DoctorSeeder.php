<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Department;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        $departments = Department::all();

        Doctor::factory(5)->create()->each(function ($doctor) use ($departments) {
            $doctor->departments()->attach($departments->random(rand(1, 2))->pluck('id')->toArray());
        });
    }
}
