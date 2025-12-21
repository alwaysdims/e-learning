<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'learning_material_id', 'description', 'published_at'];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function learningMaterial()
    {
        return $this->belongsTo(LearningMaterial::class);
    }
}
