<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumMember extends Model
{
    use HasFactory;
    protected $table = 'forum_members';
    protected $fillable = ['forum_id', 'user_id', 'role', 'joined_at'];

    public function forum()
    {
        return $this->belongsTo(DiscussionForum::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
