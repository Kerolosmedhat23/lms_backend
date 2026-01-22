<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class lecture extends Model
{
    use HasUuids;

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