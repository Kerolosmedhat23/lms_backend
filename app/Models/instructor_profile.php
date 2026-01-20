<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class instructor_profile extends Model
{
    public $table = 'instructor_profiles';
    protected $fillable = [
        'user_id',
        'bio',
        'headline',
        'course_count',
        'student_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
