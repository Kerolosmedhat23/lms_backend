<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Programming',
                'description' => 'Learn programming languages and development',
            ],
            [
                'name' => 'Web Development',
                'description' => 'Master web development with modern frameworks',
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Build mobile apps for iOS and Android',
            ],
            [
                'name' => 'Design',
                'description' => 'Learn UI/UX and graphic design',
            ],
            [
                'name' => 'Business',
                'description' => 'Develop business and entrepreneurship skills',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Master data analysis and machine learning',
            ],
            [
                'name' => 'DevOps',
                'description' => 'Learn deployment and infrastructure',
            ],
            [
                'name' => 'Cybersecurity',
                'description' => 'Secure your applications and infrastructure',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                ...$category,
                'id' => Str::uuid(),
                'slug' => Str::slug($category['name']),
            ]);
        }
    }
}
