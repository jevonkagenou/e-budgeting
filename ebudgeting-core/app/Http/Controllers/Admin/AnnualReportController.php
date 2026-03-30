<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnnualReport;

class AnnualReportController extends Controller
{
    public function index()
    {
        $reports = AnnualReport::with('fiscalYear')->latest()->paginate(10);
        return view('admin.annual_reports.index', compact('reports'));
    }

    public function download($id)
    {
        $report = AnnualReport::findOrFail($id);

        $relativePath = str_replace('public/', '', $report->file_path);
        $relativePath = ltrim($relativePath, '/');

        $absolutePath = storage_path('app/public/' . $relativePath);

        if (!file_exists($absolutePath)) {
            return back()->with('error', 'Gagal! Sistem mencari file di: ' . $absolutePath);
        }

        return response()->download($absolutePath);
    }
}
