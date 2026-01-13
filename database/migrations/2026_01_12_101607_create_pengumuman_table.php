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
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('title_ms');
            $table->string('title_en');
            $table->string('image_name')->nullable();
            $table->text('description_ms');
            $table->text('description_en');
            $table->text('content_ms')->nullable();
            $table->text('content_en')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->date('expiry_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            // Indexes for performance
            $table->index(['is_active', 'priority']);
            $table->index('expiry_date');

            // Full-text search index
            $table->fullText(['title_ms', 'title_en', 'description_ms', 'description_en'], 'pengumuman_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
