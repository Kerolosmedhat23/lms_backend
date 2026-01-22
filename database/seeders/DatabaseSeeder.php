<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'student',
        ]);

        User::factory()->create([
            'name' => 'Instructor User',
            'email' => 'instructor@example.com',
            'role' => 'instructor',
        ]);

        // Call all seeders
        $this->call([
            CategorySeeder::class,
            CourseSeeder::class,
            SectionSeeder::class,
            LectureSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            EnrollmentSeeder::class,
        ]);
    }
}
