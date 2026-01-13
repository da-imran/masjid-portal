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
        Schema::create('kutipan_masjid', function (Blueprint $table) {
            $table->id();
            $table->integer('day'); // 1-7 (Monday to Sunday)
            $table->integer('week'); // Week of month
            $table->integer('month'); // 1-12
            $table->integer('year');
            $table->decimal('day_total', 10, 2)->default(0);
            $table->decimal('week_total', 10, 2)->default(0);
            $table->decimal('month_total', 10, 2)->default(0);
            $table->decimal('year_total', 10, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Unique constraint to prevent duplicate daily records
            $table->unique(['day', 'week', 'month', 'year'], 'unique_daily_record');

            // Indexes for performance
            $table->index(['month', 'year']);
            $table->index('week');
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kutipan_masjid');
    }
};
