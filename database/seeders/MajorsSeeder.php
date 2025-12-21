<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorsSeeder extends Seeder
{
    public function run(): void
    {
        Major::insert([
            ['name' => 'Ototronik', 'code_major' => 'OTO'],
            ['name' => 'Rekayasa Perangkat Lunak', 'code_major' => 'RPL'],
            ['name' => 'Teknik Pembuatan Kain', 'code_major' => 'TPK'],
            ['name' => 'Teknik Mesin', 'code_major' => 'TM'],
        ]);
    }
}
