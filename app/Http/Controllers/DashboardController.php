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

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'period' => 'required|in:weekly,monthly',
            'count' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        Visitor::create([
            'date' => $request->date,
            'period' => $request->period,
            'count' => $request->count,
            'notes' => $request->notes,
        ]);

        return redirect('/')->with('success', 'Data berhasil ditambahkan!');
    }
}
