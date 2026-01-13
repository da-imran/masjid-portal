<?php

namespace Database\Seeders;

use App\Models\KutipanMasjid;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KutipanMasjidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        KutipanMasjid::query()->delete();

        // Generate data for each month in 2025
        $year = 2025;
        $yearTotal = 0; // Move outside the loop to accumulate across all months

        foreach (range(1, 12) as $month) {
            $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
            $monthTotal = 0;
            $weekTotals = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                $dayOfWeek = $date->dayOfWeekIso; // 1 (Monday) to 7 (Sunday)
                $weekOfMonth = ceil($day / 7);

                // Generate realistic daily collection amount (RM)
                // Friday (Jumaat) tends to have higher collections
                $baseAmount = $dayOfWeek === 5 ? rand(800, 1500) : rand(200, 600);

                // Special days might have higher collections
                if ($month === 1 && $day === 1) { // Awal Muharram
                    $baseAmount += rand(500, 1000);
                }
                if ($month === 5 && $day >= 1 && $day <= 5) { // Hari Raya period
                    $baseAmount += rand(1000, 2000);
                }
                if ($month === 6 && $day >= 16 && $day <= 20) { // Hari Raya Haji period
                    $baseAmount += rand(800, 1500);
                }
                if ($month === 10 && $day >= 15 && $day <= 17) { // Maulidur Rasul
                    $baseAmount += rand(300, 600);
                }
                if ($month === 12 && $day >= 20 && $day <= 31) { // End of year
                    $baseAmount += rand(400, 800);
                }

                $dayTotal = $baseAmount;
                $monthTotal += $dayTotal;
                $yearTotal += $dayTotal;

                // Week total (cumulative for the week)
                $weekKey = "{$month}-{$weekOfMonth}";
                if (!isset($weekTotals[$weekKey])) {
                    $weekTotals[$weekKey] = 0;
                }
                $weekTotals[$weekKey] += $dayTotal;

                KutipanMasjid::create([
                    'day' => $dayOfWeek,
                    'week' => $weekOfMonth,
                    'month' => $month,
                    'year' => $year,
                    'day_total' => $dayTotal,
                    'week_total' => $weekTotals[$weekKey],
                    'month_total' => $monthTotal,
                    'year_total' => $yearTotal,
                ]);
            }
        }

        $this->command->info('Kutipan Masjid data for 2025 has been seeded successfully!');
        $this->command->info('Total collections for 2025: RM ' . number_format($yearTotal, 2));
    }
}
