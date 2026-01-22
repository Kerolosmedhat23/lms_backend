<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Course;
use App\Models\enrollment ;
use App\Models\Order_item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderItems = Order_item::all();
        $statuses = ['enrolled', 'completed', 'cancelled'];

        foreach ($orderItems as $orderItem) {
        enrollment::create([
                'id' => Str::uuid(),
                'user_id' => $orderItem->order->user_id,
                'course_id' => $orderItem->course_id,
                'order_item_id' => $orderItem->id,
                'status' => $statuses[array_rand($statuses)],
            ]);
        
    }
    }
}
