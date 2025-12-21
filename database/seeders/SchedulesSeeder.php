<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;

class SchedulesSeeder extends Seeder
{
    public function run(): void
    {
        $baseDate = Carbon::create(2025, 1, 6); // Monday

        $schedules = [
            // ===== MONDAY =====
            ['day'=>'Monday','class'=>1,'subject'=>1,'teacher'=>1,'start'=>'07:15','end'=>'08:00'],
            ['day'=>'Monday','class'=>1,'subject'=>2,'teacher'=>1,'start'=>'08:00','end'=>'08:45'],
            ['day'=>'Monday','class'=>2,'subject'=>1,'teacher'=>2,'start'=>'09:45','end'=>'10:30'],
            ['day'=>'Monday','class'=>2,'subject'=>2,'teacher'=>2,'start'=>'10:30','end'=>'11:15'],

            // ===== TUESDAY =====
            ['day'=>'Tuesday','class'=>1,'subject'=>3,'teacher'=>2,'start'=>'07:15','end'=>'08:00'],
            ['day'=>'Tuesday','class'=>1,'subject'=>1,'teacher'=>1,'start'=>'08:00','end'=>'08:45'],
            ['day'=>'Tuesday','class'=>2,'subject'=>3,'teacher'=>2,'start'=>'09:45','end'=>'10:30'],
            ['day'=>'Tuesday','class'=>2,'subject'=>1,'teacher'=>1,'start'=>'10:30','end'=>'11:15'],

            // ===== WEDNESDAY =====
            ['day'=>'Wednesday','class'=>1,'subject'=>2,'teacher'=>1,'start'=>'07:15','end'=>'08:00'],
            ['day'=>'Wednesday','class'=>1,'subject'=>3,'teacher'=>2,'start'=>'08:00','end'=>'08:45'],
            ['day'=>'Wednesday','class'=>2,'subject'=>2,'teacher'=>1,'start'=>'09:45','end'=>'10:30'],
            ['day'=>'Wednesday','class'=>2,'subject'=>3,'teacher'=>2,'start'=>'10:30','end'=>'11:15'],

            // ===== THURSDAY =====
            ['day'=>'Thursday','class'=>1,'subject'=>1,'teacher'=>1,'start'=>'07:15','end'=>'08:00'],
            ['day'=>'Thursday','class'=>1,'subject'=>2,'teacher'=>2,'start'=>'08:00','end'=>'08:45'],
            ['day'=>'Thursday','class'=>2,'subject'=>1,'teacher'=>1,'start'=>'09:45','end'=>'10:30'],
            ['day'=>'Thursday','class'=>2,'subject'=>2,'teacher'=>2,'start'=>'10:30','end'=>'11:15'],

            // ===== FRIDAY (SHORT DAY) =====
            ['day'=>'Friday','class'=>1,'subject'=>3,'teacher'=>2,'start'=>'07:15','end'=>'08:00'],
            ['day'=>'Friday','class'=>1,'subject'=>1,'teacher'=>1,'start'=>'08:00','end'=>'08:45'],
            ['day'=>'Friday','class'=>2,'subject'=>3,'teacher'=>2,'start'=>'09:00','end'=>'09:45'],
            ['day'=>'Friday','class'=>2,'subject'=>1,'teacher'=>1,'start'=>'10:00','end'=>'10:45'],
        ];

        foreach ($schedules as $i => $item) {
            Schedule::create([
                'subject_id' => $item['subject'],
                'class_id' => $item['class'],
                'teacher_id' => $item['teacher'],
                'day' => $item['day'],
                'start_date' => $baseDate->copy()->addDays($i)->setTimeFromTimeString($item['start']),
                'end_date' => $baseDate->copy()->addDays($i)->setTimeFromTimeString($item['end']),
            ]);
        }
    }
}
