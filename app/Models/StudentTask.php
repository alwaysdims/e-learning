<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 'class_id', 'student_id', 'started_at', 'due_at',
        'submitted_at', 'total_score', 'status'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
