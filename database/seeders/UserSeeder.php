<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Doctor',
                'email' => 'doctor@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Nurse',
                'email' => 'nurse@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Specialist',
                'email' => 'specialist@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
} 