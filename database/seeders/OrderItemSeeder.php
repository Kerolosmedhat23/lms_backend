<?php

namespace Database\Seeders;

use App\Models\Order_item;
use App\Models\Order;
use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $courses = Course::all();

        if ($courses->isEmpty()) {
            return;
        }

        foreach ($orders as $order) {
            $randomCourses = $courses->random(rand(1, 3));

            foreach ($randomCourses as $course) {
                Order_item::create([
                    'id' => Str::uuid(),
                    'order_id' => $order->id,
                    'course_id' => $course->id,
                    'price' => $course->price,
                ]);
            }
        }
    }
}
