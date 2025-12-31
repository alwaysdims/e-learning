<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskClass extends Model
{
    use HasFactory;

    protected $table = 'task_classes';

    protected $fillable = [
        'task_id', 'class_id', 'published_at', 'start_time', 'deadline', 'duration'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}
