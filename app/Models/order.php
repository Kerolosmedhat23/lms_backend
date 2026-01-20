<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderItems()
    {
        return $this->hasMany(order_item::class);
    }
}
