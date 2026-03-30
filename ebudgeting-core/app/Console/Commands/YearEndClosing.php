<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FiscalYear;
use App\Models\Budget;
use App\Models\Reimbursement;
use App\Models\AnnualReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class YearEndClosing extends Command
{
    protected $signature = 'budget:year-end-closing';

    protected $description = 'Menjalankan proses tutup buku, menghitung efisiensi, dan mencetak laporan tahunan otomatis.';

    public function handle()
    {
        $this->info('Memulai proses Year-End Closing...');

        $activeYear = FiscalYear::where('is_active', true)->first();

        if (!$activeYear) {
            $this->warn('Tidak ada Tahun Anggaran aktif yang perlu ditutup.');
            return;
        }

        DB::beginTransaction();
        try {
            $pendingReimbursements = Reimbursement::where('status', 'pending')
                ->whereHas('budget', function ($q) use ($activeYear) {
                    $q->where('fiscal_year_id', $activeYear->id);
                })->get();

            foreach ($pendingReimbursements as $reimbursement) {
                $reimbursement->update([
                    'status' => 'rejected',
                    'rejection_reason' => 'Ditolak otomatis oleh sistem: Proses Tutup Buku Akhir Tahun (Year-End Closing).',
                ]);
            }
            $this->info('Data Freeze: ' . $pendingReimbursements->count() . ' pengajuan pending telah ditolak.');

            $budgets = Budget::with('division')->where('fiscal_year_id', $activeYear->id)->get();

            $totalBudget = $budgets->sum('total_amount');
            $totalUsed = $budgets->sum('used_amount');
            $totalRemaining = $totalBudget - $totalUsed;

            $reportData = (object) [
                'total_budget' => $totalBudget,
                'total_used' => $totalUsed,
                'total_remaining' => $totalRemaining,
            ];

            $this->info('Kalkulasi selesai. Total Efisiensi: Rp ' . number_format($totalRemaining, 0, ',', '.'));

            $fileName = 'Laporan_Kinerja_Keuangan_' . $activeYear->year . '_' . time() . '.pdf';
            $filePath = 'annual_reports/' . $fileName;

            $pdf = Pdf::loadView('reports.annual_executive_summary', [
                'fiscalYear' => $activeYear,
                'budgets' => $budgets,
                'report' => $reportData
            ])->setPaper('a4', 'portrait');

            Storage::disk('public')->put($filePath, $pdf->output());

            AnnualReport::create([
                'fiscal_year_id' => $activeYear->id,
                'file_name' => 'Laporan Eksekutif ' . $activeYear->year,
                'file_path' => $filePath,
                'total_budget' => $totalBudget,
                'total_used' => $totalUsed,
                'total_remaining' => $totalRemaining,
            ]);

            $activeYear->update(['is_active' => false]);

            DB::commit();

            $pesan = "Year-End Closing Sukses! Laporan tahun {$activeYear->year} berhasil dibuat dan tahun anggaran telah ditutup.";
            $this->info($pesan);
            Log::info($pesan);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            Log::error('Year-End Closing Failed: ' . $e->getMessage());
        }
    }
}
