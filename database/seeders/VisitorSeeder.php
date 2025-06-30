<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $weeklyData = [];
        $monthlyData = [];

        $startWeek = Carbon::create(2024, 11, 1)->startOfWeek();
        $endDate = Carbon::now()->startOfWeek();

        $current = $startWeek->copy();
        while ($current < $endDate) {
            $startDate = $current->copy();
            $endDateWeek = $current->copy()->endOfWeek();

            $weeklyData[] = [
                'start_date' => $startDate,
                'end_date' => $endDateWeek,
                'period' => 'weekly',
                'count' => rand(50, 300),
                'notes' => rand(0, 1) ? 'Cuaca cerah' : 'Berawan',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $current->addWeek();
        }

        $groupedByMonth = collect($weeklyData)->groupBy(function ($item) {
            return Carbon::parse($item['start_date'])->format('Y-m');
        });

        foreach ($groupedByMonth as $month => $items) {
            $items = collect($items);
            if ($items->isEmpty()) {
                continue;
            }

            $startOfMonth = Carbon::parse($items->first()['start_date'])->startOfMonth();
            $endOfMonth = Carbon::parse($items->last()['end_date'])->endOfMonth();

            $monthlyData[] = [
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
                'period' => 'monthly',
                'count' => $items->sum('count'),
                'notes' => rand(0, 1) ? 'Libur nasional' : 'Hari biasa',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('visitors')->insert(array_merge($weeklyData, $monthlyData));
    }
}
