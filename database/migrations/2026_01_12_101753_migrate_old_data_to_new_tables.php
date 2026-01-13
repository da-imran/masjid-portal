<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate mm_berita_semasa -> berita_semasa
        if (Schema::hasTable('mm_berita_semasa')) {
            DB::statement("
                INSERT INTO berita_semasa
                    (id, title_ms, title_en, image_name, description_ms, description_en,
                     view_count, is_active, created_at, updated_at)
                SELECT
                    beritaID,
                    berita_titleMS,
                    berita_titleEN,
                    berita_fileName,
                    berita_descriptionMS,
                    berita_descriptionEN,
                    COALESCE(berita_visitCount, 0),
                    berita_status = 0,
                    berita_createdAt,
                    berita_updatedAt
                FROM mm_berita_semasa
            ");
        }

        // Migrate mm_pengumuman -> pengumuman
        if (Schema::hasTable('mm_pengumuman')) {
            DB::statement("
                INSERT INTO pengumuman
                    (id, title_ms, title_en, image_name, description_ms, description_en,
                     priority, is_active, created_at, updated_at)
                SELECT
                    pengumumanID,
                    pengumuman_titleMS,
                    pengumuman_titleEN,
                    pengumuman_fileName,
                    pengumuman_descriptionMS,
                    pengumuman_descriptionEN,
                    'medium',
                    pengumuman_status = 0,
                    pengumuman_createdAt,
                    pengumuman_updatedAt
                FROM mm_pengumuman
            ");
        }

        // Migrate mm_kutipan -> kutipan_masjid
        if (Schema::hasTable('mm_kutipan')) {
            DB::statement("
                INSERT INTO kutipan_masjid
                    (id, day, week, month, year, day_total, week_total, month_total,
                     year_total, created_at, updated_at)
                SELECT
                    kutipanID,
                    kutipanDay,
                    kutipanWeek,
                    kutipanMonth,
                    kutipanYear,
                    COALESCE(kutipanDayTotal, 0),
                    COALESCE(kutipanWeekTotal, 0),
                    COALESCE(kutipanMonthTotal, 0),
                    COALESCE(kutipanTotal, 0),
                    createdAt,
                    updatedAt
                FROM mm_kutipan
            ");
        }

        // Migrate mm_visitor -> visitors
        if (Schema::hasTable('mm_visitor')) {
            DB::statement("
                INSERT INTO visitors
                    (id, ip_address, visit_count, created_at, updated_at)
                SELECT
                    id,
                    visitorIP,
                    COALESCE(visitorCount, 1),
                    created_at,
                    updated_at
                FROM mm_visitor
            ");
        }

        // Insert default corporate info pages
        DB::table('corporate_info')->insert([
            [
                'slug' => 'sejarah-masjid',
                'title_ms' => 'Sejarah Masjid',
                'title_en' => 'Mosque History',
                'content_ms' => 'Sejarah Masjid Al-Mustaghfirin',
                'content_en' => 'Al-Mustaghfirin Mosque History',
                'image_name' => null,
                'order_column' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'profil-korporat',
                'title_ms' => 'Profil Korporat',
                'title_en' => 'Corporate Profile',
                'content_ms' => 'Profil Korporat Masjid Al-Mustaghfirin',
                'content_en' => 'Al-Mustaghfirin Mosque Corporate Profile',
                'image_name' => null,
                'order_column' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'carta-organisasi',
                'title_ms' => 'Carta Organisasi',
                'title_en' => 'Organization Chart',
                'content_ms' => 'Carta Organisasi Masjid Al-Mustaghfirin',
                'content_en' => 'Al-Mustaghfirin Mosque Organization Chart',
                'image_name' => 'masjid-organisasi.png',
                'order_column' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'direktori-kakitangan',
                'title_ms' => 'Direktori Kakitangan',
                'title_en' => 'Staff Directory',
                'content_ms' => 'Direktori Kakitangan Masjid Al-Mustaghfirin',
                'content_en' => 'Al-Mustaghfirin Mosque Staff Directory',
                'image_name' => null,
                'order_column' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'perutusan-imam-besar',
                'title_ms' => 'Perutusan Imam Besar',
                'title_en' => "Grand Imam's Message",
                'content_ms' => 'Perutusan Imam Besar Masjid Al-Mustaghfirin',
                'content_en' => "Grand Imam's Message from Al-Mustaghfirin Mosque",
                'image_name' => null,
                'order_column' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'logo',
                'title_ms' => 'Logo Masjid',
                'title_en' => 'Mosque Logo',
                'content_ms' => 'Logo Masjid Al-Mustaghfirin',
                'content_en' => 'Al-Mustaghfirin Mosque Logo',
                'image_name' => null,
                'order_column' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback by truncating new tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('berita_semasa')->truncate();
        DB::table('pengumuman')->truncate();
        DB::table('kutipan_masjid')->truncate();
        DB::table('visitors')->truncate();
        DB::table('corporate_info')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
