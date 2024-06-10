<?php

namespace Database\Seeders;

use App\Models\EmployeeType;
use Illuminate\Database\Seeder;

class EmployeeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeTypes = [
            'internee',
            'probationary',
            'contractual',
            'permanent'
        ];

        foreach ($employeeTypes as $type) {
            EmployeeType::create(['type' => $type]);
        }
    }
}
