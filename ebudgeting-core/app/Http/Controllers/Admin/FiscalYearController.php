<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    public function index()
    {
        $fiscalYears = FiscalYear::orderBy('year', 'desc')->paginate(10);
        return view('admin.fiscal_years.index', compact('fiscalYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|digits:4|unique:fiscal_years,year',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startYear = date('Y', strtotime($request->start_date));
        $endYear = date('Y', strtotime($request->end_date));

        if ($startYear != $request->year && $endYear != $request->year) {
            return back()
                ->withErrors(['start_date' => 'Tanggal mulai atau berakhir harus berada di dalam tahun anggaran ' . $request->year])
                ->withInput();
        }

        FiscalYear::create([
            'year' => $request->year,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Tahun Anggaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $fiscalYear = FiscalYear::findOrFail($id);

        $request->validate([
            'year' => 'required|digits:4|unique:fiscal_years,year,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startYear = date('Y', strtotime($request->start_date));
        $endYear = date('Y', strtotime($request->end_date));

        if ($startYear != $request->year && $endYear != $request->year) {
            return back()
                ->withErrors(['start_date' => 'Tanggal mulai atau berakhir harus berada di dalam tahun anggaran ' . $request->year])
                ->withInput();
        }

        $fiscalYear->update([
            'year' => $request->year,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Tahun Anggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        FiscalYear::findOrFail($id)->delete();
        return back()->with('success', 'Tahun Anggaran berhasil dihapus.');
    }
}
