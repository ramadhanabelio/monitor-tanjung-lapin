<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $weekly = Visitor::where('period', 'weekly')->orderBy('date')->get();
        $monthly = Visitor::where('period', 'monthly')->orderBy('date')->get();

        $weeklyChartData = $weekly->pluck('count');
        $weeklyLabels = $weekly->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'));

        $monthlyChartData = $monthly->pluck('count');
        $monthlyLabels = $monthly->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M Y'));

        $pieData = [
            'weekly' => $weekly->count(),
            'monthly' => $monthly->count()
        ];

        $topMonths = $monthly->sortByDesc('count')->take(5);

        $noteSummary = Visitor::select('notes', DB::raw('COUNT(*) as total'))
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
