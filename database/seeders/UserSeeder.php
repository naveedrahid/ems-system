<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'job_type' => 'onsite',
                'work_type' => 'fulltime',
                'city' => 1,
                'country' => 1,
                'password' => Hash::make('admin12345'),
                'status' => 'active',
                'role_id' => 0
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
