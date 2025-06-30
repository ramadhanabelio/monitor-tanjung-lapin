<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VisitorController extends Controller
{
    public function index()
    {
        $weeklyVisitors = Visitor::where('period', 'weekly')->orderBy('start_date')->get();
        $monthlyVisitors = Visitor::where('period', 'monthly')->orderBy('start_date')->get();

        return view('visitors.index', compact('weeklyVisitors', 'monthlyVisitors'));
    }

    public function create()
    {
        return view('visitors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'period' => 'required|in:weekly,monthly',
            'count' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        Visitor::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'period' => $request->period,
            'count' => $request->count,
            'notes' => $request->notes,
        ]);

        return redirect()->route('visitors.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $visitor = Visitor::findOrFail($id);
        return view('visitors.edit', compact('visitor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'period' => 'required|in:weekly,monthly',
            'count' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'period' => $request->period,
            'count' => $request->count,
            'notes' => $request->notes,
        ]);

        return redirect()->route('visitors.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete();

        return redirect()->route('visitors.index')->with('success', 'Data berhasil dihapus!');
    }
}
