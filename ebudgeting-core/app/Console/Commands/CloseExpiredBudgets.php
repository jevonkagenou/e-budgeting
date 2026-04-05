<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reimbursement;
use App\Models\FiscalYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CloseExpiredBudgets extends Command
{
    protected $signature = 'budget:close-expired';

    protected $description = 'Otomatis menolak pengajuan pending pada anggaran yang kedaluwarsa dan menutup tahun fiskal.';

    public function handle()
    {
        $this->info('Memulai proses Year-End Closing...');
        $today = now()->format('Y-m-d');

        DB::beginTransaction();
        try {
            $expiredQuery = Reimbursement::where('status', 'pending')
                ->whereHas('budget', function ($q) use ($today) {
                    $q->whereDate('end_date', '<', $today)
                      ->orWhereHas('fiscalYear', function ($f) use ($today) {
                          $f->whereDate('end_date', '<', $today)
                            ->orWhere('is_active', false);
                      });
                });

            $rejectedCount = $expiredQuery->count();
            
            $expiredQuery->update([
                'status' => 'rejected',
                'rejection_reason' => 'Ditolak otomatis oleh sistem: Masa berlaku anggaran atau Tahun Buku telah ditutup.',
            ]);

            $expiredFiscalYearsCount = FiscalYear::where('is_active', true)
                ->whereDate('end_date', '<', $today)
                ->update(['is_active' => false]);

            DB::commit();

            $pesan = "Closing selesai! $rejectedCount pengajuan ditolak otomatis. $expiredFiscalYearsCount tahun fiskal ditutup.";
            $this->info($pesan);
            Log::info('Budget Command Run: ' . $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            Log::error('Budget Command Failed: ' . $e->getMessage());
        }
    }
}
