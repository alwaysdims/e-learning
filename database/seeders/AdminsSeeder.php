<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminsSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'user_id' => 1,
            'address' => 'Jl. Kantor Sekolah',
            'no_telp' => '081111111111',
        ]);
    }
}
