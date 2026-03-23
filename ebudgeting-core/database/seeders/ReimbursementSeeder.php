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
        $staff = User::where('email', 'staff@syncbudget.com')->first();
        $manager = User::where('email', 'manager@syncbudget.com')->first();

        if (!$staff || !$manager) {
            return;
        }

        $budget = Budget::where('division_id', $staff->division_id)->first();

        if (!$budget) {
            return;
        }

        $reimbursements = [
            [
                'user_id' => $staff->id,
                'budget_id' => $budget->id,
                'title' => 'Pembelian Kertas HVS & Tinta Printer',
                'description' => 'Untuk keperluan cetak laporan bulanan operasional cabang.',
                'amount' => 1500000,
                'status' => 'approved',
                'action_by' => $manager->id,
                'rejection_reason' => null,
            ],
            [
                'user_id' => $staff->id,
                'budget_id' => $budget->id,
                'title' => 'Sewa Mobil Dinas Luar Kota',
                'description' => 'Kunjungan survei lapangan ke klien di Surabaya.',
                'amount' => 3500000,
                'status' => 'rejected',
                'action_by' => $manager->id,
                'rejection_reason' => 'Gunakan kendaraan operasional kantor yang sudah tersedia, tidak perlu sewa.',
            ],
            [
                'user_id' => $staff->id,
                'budget_id' => $budget->id,
                'title' => 'Restock Kopi & Snack Pantri',
                'description' => 'Kebutuhan konsumsi bulanan karyawan operasional.',
                'amount' => 800000,
                'status' => 'pending',
                'action_by' => null,
                'rejection_reason' => null,
            ]
        ];

        DB::transaction(function () use ($reimbursements, $budget) {
            foreach ($reimbursements as $data) {
                $reimbursement = Reimbursement::firstOrCreate(
                    ['title' => $data['title']],
                    $data
                );

                if ($reimbursement->wasRecentlyCreated && $reimbursement->status === 'approved') {
                    $budget->lockForUpdate()->increment('used_amount', $reimbursement->amount);
                }
            }
        });
    }
}
