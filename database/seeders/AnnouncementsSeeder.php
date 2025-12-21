<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;

class AnnouncementsSeeder extends Seeder
{
    public function run(): void
    {
        Announcement::insert([
            [
                'user_id' => 1,
                'title' => 'Pengumuman Libur',
                'description' => 'Libur nasional minggu depan',
            ],
            [
                'user_id' => 1,
                'title' => 'Ujian Tengah Semester',
                'description' => 'UTS dimulai minggu depan',
            ],
            [
                'user_id' => 2,
                'title' => 'Tugas Baru',
                'description' => 'Tugas pemrograman sudah tersedia',
            ],
        ]);
    }
}
