<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure categories exist
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            // If no categories, create them
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $instructor = User::where('role', 'instructor')->first();

        if (!$instructor) {
            $instructor = User::first();
        }

        $categoryMap = [
            'Web Development' => 'Web Development',
            'Data Science' => 'Data Science',
            'Mobile Development' => 'Mobile Development',
            'Design' => 'Design',
            'DevOps' => 'DevOps',
            'Cybersecurity' => 'Cybersecurity',
        ];

        $courses = [
            [
                'title' => 'Complete Web Development Bootcamp',
                'description' => 'Learn HTML, CSS, JavaScript, React, Node.js, and MongoDB to become a full-stack web developer.',
                'price' => 99.99,
                'duration' => 40,
                'level' => 'beginner',
                'language' => 'English',
                'status' => 'published',
                'category_name' => 'Web Development',
            ],
            [
                'title' => 'Python for Data Science',
                'description' => 'Master Python programming and data science libraries like Pandas, NumPy, and Scikit-learn.',
                'price' => 89.99,
                'duration' => 35,
                'level' => 'intermediate',
                'language' => 'English',
                'status' => 'published',
                'category_name' => 'Data Science',
            ],
            [
                'title' => 'React Advanced Patterns',
                'description' => 'Deep dive into advanced React patterns, hooks, context API, and performance optimization.',
                'price' => 79.99,
                'duration' => 30,
                'level' => 'advanced',
                'language' => 'English',
                'status' => 'published',
                'category_name' => 'Web Development',
            ],
            [
                'title' => 'Mobile App Development with React Native',
                'description' => 'Build cross-platform mobile apps using React Native for iOS and Android.',
                'price' => 94.99,
                'duration' => 38,
                'level' => 'intermediate',
                'language' => 'English',
                'status' => 'published',
                'category_name' => 'Mobile Development',
            ],
            [
                'title' => 'UI/UX Design Principles',
                'description' => 'Learn design thinking, wireframing, prototyping, and user research.',
                'price' => 69.99,
                'duration' => 25,
                'level' => 'beginner',
                'language' => 'English',
                'status' => 'published',
                'category_name' => 'Design',
            ],
            [
                'title' => 'AWS Solutions Architect Associate',
                'description' => 'Prepare for AWS certification and learn cloud architecture design.',
                'price' => 109.99,
                'duration' => 45,
                'level' => 'advanced',
                'language' => 'English',
                'status' => 'published',
                'category_name' => 'DevOps',
            ],
            [
                'title' => 'Cybersecurity Fundamentals',
                'description' => 'Learn network security, encryption, ethical hacking, and security best practices.',
                'price' => 84.99,
                'duration' => 32,
                'level' => 'intermediate',
                'language' => 'English',
                'status' => 'published',
                'category_name' => 'Cybersecurity',
            ],
        ];

        foreach ($courses as $course) {
            $categoryName = $course['category_name'];
            unset($course['category_name']);

            $category = $categories->where('name', $categoryName)->first();
            
            Course::create([
                ...$course,
                'instructor_id' => $instructor->id,
                'slug' => Str::slug($course['title']),
                'category_id' => $category ? $category->id : $categories->first()->id,
            ]);
        }
    }
}
