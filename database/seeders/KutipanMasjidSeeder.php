<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class KutipanMasjidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for($i = 0; $i < 20; $i++){
            $day = Carbon::now()->addDays($i)->dayOfWeekIso;
            $week = Carbon::now()->addDays($i)->weekOfMonth;
            $month = $faker->month();
            $year = 2024;
            $totalDay = $faker->randomNumber(3, true);
            $totalWeek = $faker->randomNumber(4, true);
            $totalMonth = $faker->randomNumber(4, true);
            $total = $faker->randomNumber(6, true);
            $created_at = Carbon::now();
            $updated_at = Carbon::now();
            $created_by = 1;
            $updated_by = 1;
    
            DB::table('mm_kutipan')->insert([
                'kutipanDay' => $day,
                'kutipanWeek' => $week,
                'kutipanMonth' => $month,
                'kutipanYear' => $year,
                'kutipanDayTotal' => $totalDay,
                'kutipanWeekTotal' => $totalWeek,
                'kutipanMonthTotal' => $totalMonth,
                'kutipanTotal' => $total,
                'createdAt' => $created_at,
                'updatedAt' => $updated_at,
                'createdBy' => $created_by,
                'updatedBy' => $updated_by
            ]);
        }
    }
}
