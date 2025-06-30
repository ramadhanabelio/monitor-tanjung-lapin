<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $weekly = Visitor::where('period', 'weekly')->orderBy('start_date')->get();
        $monthly = Visitor::where('period', 'monthly')->orderBy('start_date')->get();

        $weeklyChartData = $weekly->pluck('count');
        $weeklyLabels = $weekly->pluck('start_date')->map(function ($date) {
            return Carbon::parse($date)->translatedFormat('d M');
        });

        $monthlyChartData = $monthly->pluck('count');
        $monthlyLabels = $monthly->pluck('start_date')->map(function ($date) {
            return Carbon::parse($date)->translatedFormat('M Y');
        });

        $pieData = [
            'weekly' => $weekly->sum('count'),
            'monthly' => $monthly->sum('count')
        ];

        $topMonths = $monthly->sortByDesc('count')->take(5)->values()->map(function ($item) {
            return (object)[
                'visit_date' => $item->start_date,
                'count' => $item->count
            ];
        });

        $noteSummary = Visitor::select('notes', DB::raw('COUNT(*) as total'))
            ->whereNotNull('notes')
            ->groupBy('notes')
            ->get();

        return view('dashboard', compact(
            'weeklyChartData',
            'weeklyLabels',
            'monthlyChartData',
            'monthlyLabels',
            'pieData',
            'topMonths',
            'noteSummary'
        ));
    }
}
