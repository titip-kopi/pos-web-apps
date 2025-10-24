<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expenditure;
use Illuminate\Support\Facades\Auth;

class ExpenditureController extends Controller
{
    public function index(Request $request)
    {
        $query = Expenditure::query();
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('from')) {
            $query->whereDate('spent_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('spent_at', '<=', $request->to);
        }

        // Only show user's own records by default
        $query->where('user_id', Auth::id());

        $expenditures = $query->orderByDesc('spent_at')->paginate(15);
        return view('pages.expenditure.index', compact('expenditures'));
    }

    public function create()
    {
        return view('pages.expenditure.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'spent_at' => 'nullable|date',
        ]);
        $data['user_id'] = Auth::id();
        Expenditure::create($data);
        return redirect()->route('expenditure.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit(Expenditure $expenditure)
    {
        $this->authorize('update', $expenditure);
        return view('pages.expenditure.edit', compact('expenditure'));
    }

    public function update(Request $request, Expenditure $expenditure)
    {
        $this->authorize('update', $expenditure);
        $data = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'spent_at' => 'nullable|date',
        ]);
        $expenditure->update($data);
        return redirect()->route('expenditure.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expenditure $expenditure)
    {
        $this->authorize('delete', $expenditure);
        $expenditure->delete();
        return redirect()->route('expenditure.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
