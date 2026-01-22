<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', '!=', 'instructor')->limit(5)->get();

        if ($students->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'completed', 'cancelled'];

        foreach ($students as $student) {
            Order::create([
                'id' => Str::uuid(),
                'user_id' => $student->id,
                'total_amount' => rand(50, 500),
                'status' => $statuses[array_rand($statuses)],
            ]);

            Order::create([
                'id' => Str::uuid(),
                'user_id' => $student->id,
                'total_amount' => rand(50, 500),
                'status' => 'completed',
            ]);
        }
    }
}
