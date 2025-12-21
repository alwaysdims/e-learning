<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'subject_id', 'title', 'description',
        'total_questions', 'type', 'max_score'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function questions()
    {
        return $this->hasMany(AssignmentQuestion::class);
    }

    public function taskClasses()
    {
        return $this->hasMany(TaskClass::class);
    }

    public function studentTasks()
    {
        return $this->hasMany(StudentTask::class);
    }
}
