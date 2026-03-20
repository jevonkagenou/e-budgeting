<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Division;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);

        $divIT = Division::where('name', 'IT & Development')->first();
        $divKeuangan = Division::where('name', 'Keuangan')->first();
        $divOperasional = Division::where('name', 'Operasional')->first();

        $admin = User::firstOrCreate(
            ['email' => 'admin@syncbudget.com'],
            [
                'name' => 'Felix',
                'password' => Hash::make('password123'),
                'division_id' => $divIT ? $divIT->id : null,
            ]
        );
        $admin->assignRole($adminRole);

        $manager = User::firstOrCreate(
            ['email' => 'manager@syncbudget.com'],
            [
                'name' => 'Manajer Keuangan',
                'password' => Hash::make('password123'),
                'division_id' => $divKeuangan ? $divKeuangan->id : null,
            ]
        );
        $manager->assignRole($managerRole);

        $staff = User::firstOrCreate(
            ['email' => 'staff@syncbudget.com'],
            [
                'name' => 'Staf Operasional',
                'password' => Hash::make('password123'),
                'division_id' => $divOperasional ? $divOperasional->id : null,
            ]
        );
        $staff->assignRole($staffRole);
    }
}
