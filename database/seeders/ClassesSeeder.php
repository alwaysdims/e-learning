<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassRoom;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        ClassRoom::insert([
            [
                'name' => 'XI RPL 1',
                'grade_level' => 11,
                'academic_year' => '2024/2025',
                'major_id' => 2,
                'home_room_teacher' => 1,
            ],
            [
                'name' => 'XI RPL 2',
                'grade_level' => 11,
                'academic_year' => '2024/2025',
                'major_id' => 2,
                'home_room_teacher' => 2,
            ],
        ]);
    }
}
