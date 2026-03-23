<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Reimbursement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReimbursementSeeder extends Seeder
{
    public function run(): void
    {
        $staffOps = User::where('email', 'staff@syncbudget.com')->first();
        $staffHrd = User::where('email', 'staffhrd@syncbudget.com')->first();
        $manager = User::where('email', 'manager@syncbudget.com')->first();

        if (!$staffOps || !$staffHrd || !$manager) {
            return;
        }

        $budgetOps = Budget::where('division_id', $staffOps->division_id)->first();
        $budgetHrd = Budget::where('division_id', $staffHrd->division_id)->first();

        if (!$budgetOps || !$budgetHrd) {
            return;
        }

        $reimbursements = [
            [
                'user_id' => $staffOps->id,
                'budget_id' => $budgetOps->id,
                'title' => 'Pembelian Kertas HVS & Tinta Printer',
                'description' => 'Untuk keperluan cetak laporan bulanan operasional cabang.',
                'amount' => 1500000,
                'status' => 'approved',
                'action_by' => $manager->id,
                'rejection_reason' => null,
            ],
            [
                'user_id' => $staffOps->id,
                'budget_id' => $budgetOps->id,
                'title' => 'Sewa Mobil Dinas Luar Kota',
                'description' => 'Kunjungan survei lapangan ke klien di Surabaya.',
                'amount' => 3500000,
                'status' => 'rejected',
                'action_by' => $manager->id,
                'rejection_reason' => 'Gunakan kendaraan operasional kantor yang sudah tersedia, tidak perlu sewa.',
            ],
            [
                'user_id' => $staffOps->id,
                'budget_id' => $budgetOps->id,
                'title' => 'Restock Kopi & Snack Pantri',
                'description' => 'Kebutuhan konsumsi bulanan karyawan operasional.',
                'amount' => 800000,
                'status' => 'pending',
                'action_by' => null,
                'rejection_reason' => null,
            ],
            [
                'user_id' => $staffHrd->id,
                'budget_id' => $budgetHrd->id,
                'title' => 'Biaya Iklan Lowongan Kerja Premium',
                'description' => 'Pemasangan iklan loker di portal Jobstreet untuk 3 posisi IT.',
                'amount' => 2500000,
                'status' => 'approved',
                'action_by' => $manager->id,
                'rejection_reason' => null,
            ],
            [
                'user_id' => $staffHrd->id,
                'budget_id' => $budgetHrd->id,
                'title' => 'Konsumsi Training Karyawan Baru',
                'description' => 'Makan siang untuk 15 peserta onboarding batch maret.',
                'amount' => 1200000,
                'status' => 'pending',
                'action_by' => null,
                'rejection_reason' => null,
            ]
        ];

        DB::transaction(function () use ($reimbursements, $budgetOps, $budgetHrd) {
            foreach ($reimbursements as $data) {
                $reimbursement = Reimbursement::firstOrCreate(
                    ['title' => $data['title']],
                    $data
                );

                if ($reimbursement->wasRecentlyCreated && $reimbursement->status === 'approved') {
                    $budgetToUpdate = $reimbursement->budget_id === $budgetOps->id ? $budgetOps : $budgetHrd;
                    $budgetToUpdate->lockForUpdate()->increment('used_amount', $reimbursement->amount);
                }
            }
        });
    }
}
