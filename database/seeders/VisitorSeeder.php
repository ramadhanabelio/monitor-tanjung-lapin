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
        $data = [];

        $startWeek = Carbon::now()->subWeeks(52);
        for ($i = 0; $i < 52; $i++) {
            $data[] = [
                'date' => $startWeek->copy()->addWeeks($i),
                'period' => 'weekly',
                'count' => rand(50, 300),
                'notes' => rand(0, 1) ? 'Cuaca cerah' : 'Berawan',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $startMonth = Carbon::now()->subMonths(24);
        for ($i = 0; $i < 24; $i++) {
            $data[] = [
                'date' => $startMonth->copy()->addMonths($i),
                'period' => 'monthly',
                'count' => rand(500, 1500),
                'notes' => rand(0, 1) ? 'Libur nasional' : 'Hari biasa',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('visitors')->insert($data);
    }
}
