<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignUuid('course_id')->constrained('courses')->onDelete('restrict');
            // Add the column now; attach FK in a later migration after order_items exists
               $table->foreignUuid('order_item_id')->constrained('order_items')->onDelete('restrict');

            $table->enum('status', ['enrolled', 'completed', 'cancelled'])->default('enrolled');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};