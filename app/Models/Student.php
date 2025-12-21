<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'address', 'academic_year', 'no_telp', 'class_id', 'major_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'achievement_students');
    }

    public function studentTasks()
    {
        return $this->hasMany(StudentTask::class);
    }
}
