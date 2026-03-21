<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Budget::with(['division', 'creator']);

        if ($search) {
            $query->where('name', 'ilike', "%{$search}%")
            ->orWhereHas('division', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%");
                });
        }

        $budgets = $query->latest()->paginate(10);

        $divisions = Division::all();

        return view('budgets.index', compact('budgets', 'divisions', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Budget::create([
            'division_id' => $request->division_id,
            'name' => $request->name,
            'total_amount' => $request->total_amount,
            'used_amount' => 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Pagu anggaran berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $budget = Budget::findOrFail($id);

        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:' . $budget->used_amount,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $budget->update([
            'division_id' => $request->division_id,
            'name' => $request->name,
            'total_amount' => $request->total_amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return back()->with('success', 'Data anggaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $budget = Budget::findOrFail($id);
        $budget->delete();

        return back()->with('success', 'Anggaran berhasil dihapus dari sistem!');
    }
}
