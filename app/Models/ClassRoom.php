<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use HasFactory;
    protected $table = 'classes';

    protected $fillable = [
        'name', 'grade_level', 'academic_year', 'major_id', 'home_room_teacher'
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function homeRoomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'home_room_teacher');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_classes');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function classMaterials()
    {
        return $this->hasMany(ClassMaterial::class);
    }

    public function discussionForums()
    {
        return $this->hasMany(DiscussionForum::class);
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
