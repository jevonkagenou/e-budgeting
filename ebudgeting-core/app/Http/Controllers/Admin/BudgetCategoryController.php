<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;

class BudgetCategoryController extends Controller
{
    public function index()
    {
        $categories = BudgetCategory::orderBy('name', 'asc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:budget_categories,code',
        ]);

        BudgetCategory::create($request->only(['name', 'code']));
        return back()->with('success', 'Kategori Anggaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = BudgetCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:budget_categories,code,' . $id,
        ]);

        $category->update($request->only(['name', 'code']));
        return back()->with('success', 'Kategori Anggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        BudgetCategory::findOrFail($id)->delete();
        return back()->with('success', 'Kategori Anggaran berhasil dihapus.');
    }
}
