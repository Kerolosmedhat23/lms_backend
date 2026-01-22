<?php

namespace Database\Seeders;

use App\Models\Lecture;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = Section::all();

        foreach ($sections as $section) {
            $lectures = [
                [
                    'title' => 'Course Overview',
                    'content' => 'Welcome to the course! In this lecture, we will cover what you will learn.',
                    'duration' => 5,
                    'video_url' => 'https://example.com/videos/overview.mp4',
                    'is_preview' => true,
                    'position' => 1,
                ],
                [
                    'title' => 'Getting Started',
                    'content' => 'Learn how to set up your development environment.',
                    'duration' => 12,
                    'video_url' => 'https://example.com/videos/getting-started.mp4',
                    'is_preview' => false,
                    'position' => 2,
                ],
                [
                    'title' => 'Core Concepts Explained',
                    'content' => 'Deep dive into the fundamental concepts of this course.',
                    'duration' => 25,
                    'video_url' => 'https://example.com/videos/concepts.mp4',
                    'is_preview' => false,
                    'position' => 3,
                ],
                [
                    'title' => 'Practical Example',
                    'content' => 'Build a real-world project to solidify your understanding.',
                    'duration' => 30,
                    'video_url' => 'https://example.com/videos/example.mp4',
                    'is_preview' => false,
                    'position' => 4,
                ],
                [
                    'title' => 'Q&A and Troubleshooting',
                    'content' => 'Common questions and how to solve typical issues.',
                    'duration' => 15,
                    'video_url' => 'https://example.com/videos/qa.mp4',
                    'is_preview' => false,
                    'position' => 5,
                ],
            ];

            foreach ($lectures as $lecture) {
                Lecture::create([
                    ...$lecture,
                    'id' => Str::uuid(),
                    'section_id' => $section->id,
                ]);
            }
        }
    }
}
