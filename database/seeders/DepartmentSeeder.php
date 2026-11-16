<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Cardiology', 'code' => 'CARD', 'description' => 'Heart and vascular care'],
            ['name' => 'Neurology', 'code' => 'NEUR', 'description' => 'Brain and nervous system'],
            ['name' => 'Orthopedics', 'code' => 'ORTH', 'description' => 'Bones and joints'],
            ['name' => 'Pediatrics', 'code' => 'PED', 'description' => 'Child healthcare'],
            ['name' => 'General Medicine', 'code' => 'GEN', 'description' => 'Primary care'],
            ['name' => 'Pharmacy', 'code' => 'PHARM', 'description' => 'Medication dispensing'],
        ];
        foreach ($departments as $d) {
            Department::firstOrCreate(['code' => $d['code']], $d);
        }
    }
}
