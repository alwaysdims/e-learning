<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscussionForum extends Model
{
    use HasFactory;

    protected $table = 'discussion_forums';

    protected $fillable = ['title', 'teacher_id', 'subject_id', 'class_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function members()
    {
        return $this->hasMany(ForumMember::class, 'forum_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'forum_id');
    }
}
