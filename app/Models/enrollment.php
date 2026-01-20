<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class enrollment extends Model
{
    $table='enrollments';
    protected $fillable = [
        'user_id',
        'course_id',
        'order_item_id',
        'status',
    ];  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(course::class); 
    }
    public function orderItem()
    {
        return $this->belongsTo(order_item::class);
    }
}
