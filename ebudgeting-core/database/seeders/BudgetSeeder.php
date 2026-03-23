<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Division;
use App\Models\User;
use App\Models\FiscalYear;
use App\Models\BudgetCategory;
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

        $fiscalYear = FiscalYear::firstOrCreate(
            ['year' => '2026'],
            [
                'is_active' => true,
                'start_date' => Carbon::create(2026, 1, 1)->format('Y-m-d'),
                'end_date' => Carbon::create(2026, 12, 31)->format('Y-m-d'),
            ]
        );

        $catIT = BudgetCategory::firstOrCreate(['code' => 'IT-01'], ['name' => 'Infrastruktur & IT']);
        $catTaktis = BudgetCategory::firstOrCreate(['code' => 'FIN-01'], ['name' => 'Dana Taktis']);
        $catOps = BudgetCategory::firstOrCreate(['code' => 'OPS-01'], ['name' => 'Operasional Cabang']);

        if ($admin && $manager && $divIT && $divKeuangan && $divOperasional) {
            $budgets = [
                [
                    'fiscal_year_id' => $fiscalYear->id,
                    'budget_category_id' => $catIT->id,
                    'division_id' => $divIT->id,
                    'name' => 'Anggaran IT & Infrastruktur Q1 2026',
                    'total_amount' => 50000000,
                    'used_amount' => 12500000,
                ],
                [
                    'fiscal_year_id' => $fiscalYear->id,
                    'budget_category_id' => $catTaktis->id,
                    'division_id' => $divKeuangan->id,
                    'name' => 'Dana Taktis Keuangan 2026',
                    'total_amount' => 100000000,
                    'used_amount' => 85000000,
                ],
                [
                    'fiscal_year_id' => $fiscalYear->id,
                    'budget_category_id' => $catOps->id,
                    'division_id' => $divOperasional->id,
                    'name' => 'Anggaran Operasional Cabang',
                    'total_amount' => 200000000,
                    'used_amount' => 120000000,
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
