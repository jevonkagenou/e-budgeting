<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Division::query();

        if ($search) {
            $query->where('name', 'ilike', "%{$search}%");
        }

        $divisions = $query->latest()->paginate(10);

        return view('admin.divisions.index', compact('divisions', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name'
        ]);

        Division::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Divisi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id
        ]);

        $division->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Divisi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);

        if ($division->users()->count() > 0) {
            return back()->with('error', 'Gagal dihapus! Masih ada pengguna di dalam divisi ini.');
        }

        $division->delete();

        return back()->with('success', 'Divisi berhasil dihapus!');
    }
}
