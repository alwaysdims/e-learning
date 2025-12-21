<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['assignment_question_id', 'student_task_id', 'answer', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(AssignmentQuestion::class, 'assignment_question_id');
    }

    public function studentTask()
    {
        return $this->belongsTo(StudentTask::class);
    }
}
