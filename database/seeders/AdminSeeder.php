<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'username' => 'admins',
            'email'    => 'admin@edugrade.com',
            'password' => Hash::make('admin1234'),
            'role'     => 'admin',
        ]);
    }
}