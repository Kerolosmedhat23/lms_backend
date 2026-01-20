<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lecture extends Model
{

    protected $table='lectures';
    protected $fillable = [
        'section_id',
        'title',
        'content',
        'duration',
        'video_url',
        'is_preview',
        'position',
    ];  
    public function section()
    {
        return $this->belongsTo(section::class);
    }
}