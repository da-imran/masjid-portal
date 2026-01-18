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
        Schema::table('berita_semasa', function (Blueprint $table) {
            $table->string('title_en')->nullable()->change();
            $table->text('description_ms')->nullable()->change();
            $table->text('description_en')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita_semasa', function (Blueprint $table) {
            $table->string('title_en')->nullable(false)->change();
            $table->text('description_ms')->nullable(false)->change();
            $table->text('description_en')->nullable(false)->change();
        });
    }
};
