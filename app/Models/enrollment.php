<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class enrollment extends Model
{
    use HasUuids;

    protected $table='enrollments';
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
