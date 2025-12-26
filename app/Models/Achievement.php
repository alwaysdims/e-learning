<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'icon', 'target_value','type'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'achievement_students');
    }
}
