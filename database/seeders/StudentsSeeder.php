<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentsSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'user_id' => 4, // user student
            'nis' => '1732',
            'address' => 'Jl. Siswa No. 5',
            'academic_year' => '2024/2025',
            'no_telp' => '089876543210',
            'class_id' => 1,
            'major_id' => 2,
        ]);
    }
}
