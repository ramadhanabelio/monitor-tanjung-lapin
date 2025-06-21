<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VisitorController extends Controller
{
    public function index()
    {
        $visitors = Visitor::orderBy('date', 'desc')->get();
        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        return view('visitors.create');
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

        return redirect()->route('visitor.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $visitor = Visitor::findOrFail($id);
        return view('visitors.edit', compact('visitor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'period' => 'required|in:weekly,monthly',
            'count' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'date' => $request->date,
            'period' => $request->period,
            'count' => $request->count,
            'notes' => $request->notes,
        ]);

        return redirect()->route('visitor.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete();

        return redirect()->route('visitor.index')->with('success', 'Data berhasil dihapus!');
    }
}
