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
        Schema::create('lectures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('section_id')->constrained('sections')->onDelete('restrict');
            $table->string('title');
            $table->text('content')->nullable();    
            $table->integer('duration')->default(0); // duration in minutes
            $table->string('video_url')->nullable();
            // is preview lecture or not
            $table->boolean('is_preview')->default(false);
            //  position of the lecture in the section
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
