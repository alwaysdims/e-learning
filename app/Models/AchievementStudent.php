<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AchievementStudent extends Model
{
    use HasFactory;

    protected $table = 'achievement_students';

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
