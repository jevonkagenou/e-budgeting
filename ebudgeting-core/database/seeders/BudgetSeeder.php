<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $divIT = Division::where('name', 'IT & Development')->first();
        $divKeuangan = Division::where('name', 'Keuangan')->first();
        $divOperasional = Division::where('name', 'Operasional')->first();

        $admin = User::where('email', 'admin@syncbudget.com')->first();
        $manager = User::where('email', 'manager@syncbudget.com')->first();

        if ($admin && $manager && $divIT && $divKeuangan && $divOperasional) {
            $budgets = [
                [
                    'division_id' => $divIT->id,
                    'name' => 'Anggaran IT & Infrastruktur Q1 2026',
                    'total_amount' => 50000000,
                    'used_amount' => 12500000,
                    'start_date' => Carbon::now()->startOfYear()->format('Y-m-d'),
                    'end_date' => Carbon::now()->endOfYear()->format('Y-m-d'),
                    'created_by' => $admin->id,
                ],
                [
                    'division_id' => $divKeuangan->id,
                    'name' => 'Dana Taktis Keuangan 2026',
                    'total_amount' => 100000000,
                    'used_amount' => 85000000,
                    'start_date' => Carbon::now()->startOfYear()->format('Y-m-d'),
                    'end_date' => Carbon::now()->endOfYear()->format('Y-m-d'),
                    'created_by' => $manager->id,
                ],
                [
                    'division_id' => $divOperasional->id,
                    'name' => 'Anggaran Operasional Cabang',
                    'total_amount' => 200000000,
                    'used_amount' => 120000000,
                    'start_date' => Carbon::now()->startOfYear()->format('Y-m-d'),
                    'end_date' => Carbon::now()->endOfYear()->format('Y-m-d'),
                    'created_by' => $manager->id,
                ],
            ];

            foreach ($budgets as $budgetData) {
                Budget::firstOrCreate(
                    ['name' => $budgetData['name']],
                    $budgetData
                );
            }
        }
    }
}
