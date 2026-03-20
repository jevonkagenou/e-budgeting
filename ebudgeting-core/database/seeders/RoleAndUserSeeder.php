<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Roles
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $staffRole = Role::create(['name' => 'staff']);

        // Admin
        $admin = User::create([
            'name' => 'Felix',
            'email' => 'admin@syncbudget.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole($adminRole);

        // Akun Manajer (Untuk Approval)
        $manager = User::create([
            'name' => 'Manajer Keuangan',
            'email' => 'manager@syncbudget.com',
            'password' => Hash::make('password123'),
        ]);
        $manager->assignRole($managerRole);

        // Akun Staf (Untuk Pengajuan)
        $staff = User::create([
            'name' => 'Staf Operasional',
            'email' => 'staff@syncbudget.com',
            'password' => Hash::make('password123'),
        ]);
        $staff->assignRole($staffRole);
    }
}
