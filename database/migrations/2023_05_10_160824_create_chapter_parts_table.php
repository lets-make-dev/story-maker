<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chapter_parts', function (Blueprint $table) {
            $table->id();
            $table->text('story');
            $table->text('image_description')->nullable();
            $table->text('image_url')->nullable();
            $table->integer('order')->default(0);
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_parts');
    }
};
