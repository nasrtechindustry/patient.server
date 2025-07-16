<?php

// database/seeders/DepartmentSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = ['Pediatrics', 'Cardiology', 'General Surgery', 'Dermatology'];
        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}

