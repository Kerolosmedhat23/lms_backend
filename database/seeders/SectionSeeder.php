<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            $sections = [
                [
                    'title' => 'Introduction and Setup',
                    'position' => 1,
                ],
                [
                    'title' => 'Core Concepts',
                    'position' => 2,
                ],
                [
                    'title' => 'Advanced Topics',
                    'position' => 3,
                ],
                [
                    'title' => 'Projects and Practice',
                    'position' => 4,
                ],
                [
                    'title' => 'Final Project',
                    'position' => 5,
                ],
            ];

            foreach ($sections as $section) {
                Section::create([
                    ...$section,
                    'id' => Str::uuid(),
                    'course_id' => $course->id,
                ]);
            }
        }
    }
}
