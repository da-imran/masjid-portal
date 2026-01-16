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
        Schema::create('takwim', function (Blueprint $table) {
            $table->id();
            $table->string('title_ms');
            $table->string('title_en')->nullable();
            $table->text('description_ms');
            $table->text('description_en')->nullable();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('location_ms')->nullable();
            $table->string('location_en')->nullable();
            $table->string('image_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['event_date', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('takwim');
    }
};
