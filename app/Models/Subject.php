<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function learningMaterials()
    {
        return $this->hasMany(LearningMaterial::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
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
