<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class order_item extends Model
{
    use HasUuids;

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
