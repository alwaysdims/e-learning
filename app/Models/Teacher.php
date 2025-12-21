<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['nip', 'user_id', 'address', 'no_telp', 'subject_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'teacher_classes');
    }

    public function homeRoomClasses()
    {
        return $this->hasMany(ClassRoom::class, 'home_room_teacher');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function learningMaterials()
    {
        return $this->hasMany(LearningMaterial::class);
    }

    public function discussionForums()
    {
        return $this->hasMany(DiscussionForum::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
