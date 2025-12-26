<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class,'user_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function forumMembers()
    {
        return $this->hasMany(ForumMember::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function isAdmin() { return $this->role === 'admin'; }
    public function isTeacher() { return $this->role === 'teacher'; }
    public function isStudent() { return $this->role === 'student'; }
}
