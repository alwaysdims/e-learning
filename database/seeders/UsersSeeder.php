<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Sukidi',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Agung Wiratmo',
                'username' => 'teacher',
                'email' => 'teacher@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Afif Nuruddin Maisaroh',
                'username' => 'pipapip',
                'email' => 'pipapip@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Orlando Dimas Saputra',
                'username' => 'student',
                'email' => 'student@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
