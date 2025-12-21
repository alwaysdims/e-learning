<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['forum_id', 'user_id', 'comment', 'picture'];

    public function forum()
    {
        return $this->belongsTo(DiscussionForum::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
