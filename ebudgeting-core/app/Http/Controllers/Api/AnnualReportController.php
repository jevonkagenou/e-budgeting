<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnnualReport;
use Illuminate\Http\Request;

class AnnualReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('manager')) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk melihat laporan tahunan.'
            ], 403);
        }

        $search = $request->input('search');
        $fiscalYearId = $request->input('fiscal_year_id');

        $query = AnnualReport::with('fiscalYear')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('fiscalYear', function ($qFy) use ($search) {
                      $qFy->where('year', 'like', "%{$search}%");
                  });
            });
        }

        if ($fiscalYearId) {
            $query->where('fiscal_year_id', $fiscalYearId);
        }

        $reports = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Daftar laporan tahunan berhasil dimuat',
            'data'    => $reports,
        ], 200);
    }

    public function download(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->hasRole('manager')) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        $report = AnnualReport::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan.'
            ], 404);
        }

        $relativePath = str_replace('public/', '', $report->file_path);
        $relativePath = ltrim($relativePath, '/');

        $absolutePath = storage_path('app/public/' . $relativePath);

        if (!file_exists($absolutePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan di server.'
            ], 404);
        }

        return response()->download($absolutePath);
    }
}
