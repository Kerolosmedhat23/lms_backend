<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class order extends Model
{
    use HasUuids;

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
