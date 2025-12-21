<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'subject_id', 'title', 'content', 'is_published'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classMaterials()
    {
        return $this->hasMany(ClassMaterial::class);
    }
}
