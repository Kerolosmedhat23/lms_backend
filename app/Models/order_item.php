<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order_item extends Model
{
    protected $table='order_items';
    protected $fillable = [
        'order_id',
        'course_id',
        'price',
    ];  
    public function order()
    {
        return $this->belongsTo(order::class);
    }
    public function course()
    {
        return $this->belongsTo(course::class); 
    }
}
