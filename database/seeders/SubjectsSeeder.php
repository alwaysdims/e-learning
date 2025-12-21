<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectsSeeder extends Seeder
{
    public function run(): void
    {
        Subject::insert([
            ['name' => 'Pemrograman Web'],
            ['name' => 'Basis Data'],
            ['name' => 'Matematika'],
        ]);
    }
}
