<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            // foringid to categories table
            $table->foreignUuid('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignUuid('instructor_id')->constrained('users')->onDelete('restrict');

            $table->decimal('price', 8, 2)->default(0.00);
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('duration')->default(0); // duration in minutes 
            $table->string('language')->default('English'); 
            $table->enum('level', [1, 2, 3])->default(1); // 1: Beginner, 2: Intermediate, 3: Advanced
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('courses');
    }
};
