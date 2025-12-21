<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeachersSeeder extends Seeder
{
    public function run(): void
    {
        Teacher::insert([
            [
            'nip' => '1987654321',
            'user_id' => 2, // user teacher
            'address' => 'Jl. Pendidikan No. 1',
            'no_telp' => '081234567890',
            'subject_id' => 1,
            ],
            [
                'nip' => '64773254912',
                'user_id' => 3, // user teacher
                'address' => 'Jl. Suruh No. 5',
                'no_telp' => '086473571313',
                'subject_id' => 1,
            ],
        ]);
    }
}
