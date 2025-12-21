<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 'question', 'picture', 'answer_a', 'answer_b',
        'answer_c', 'answer_d', 'answer_e', 'correct_answer'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
