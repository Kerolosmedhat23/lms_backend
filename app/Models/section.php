<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class section extends Model
{
    protected $table='sections';
    protected $fillable = [
        'course_id',
        'title',
        'position',
    ];  
    public function course()
    {
        return $this->belongsTo(course::class); 
    }
    public function lectures()
    {
        return $this->hasMany(lecture::class);   
    }
}
