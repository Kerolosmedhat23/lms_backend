<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class course extends Model
{
           use HasFactory , HasUuids ,SoftDeletes;
    public $table='courses';
    protected $fillable = [
        'instructor_id',
        'category_id',
        'slug',
        'title',
        'description',
        'price',
        'thumbnail',
        'status',
        'level',
        'language',
        'duration',
    ];
        protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function category()
    {
        return $this->belongsTo(category::class);
    }
    public function enrollments()
    {
        return $this->hasMany(enrollment::class);
    }
}
