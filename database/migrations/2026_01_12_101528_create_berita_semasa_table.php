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
        Schema::create('berita_semasa', function (Blueprint $table) {
            $table->id();
            $table->string('title_ms');
            $table->string('title_en');
            $table->string('image_name')->nullable();
            $table->text('description_ms');
            $table->text('description_en');
            $table->text('content_ms')->nullable();
            $table->text('content_en')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            // Indexes for performance
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index('view_count');

            // Full-text search index (MySQL 5.6+)
            $table->fullText(['title_ms', 'title_en', 'description_ms', 'description_en'], 'berita_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_semasa');
    }
};
