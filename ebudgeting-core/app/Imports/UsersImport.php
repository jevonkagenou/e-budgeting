<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $email = $this->sanitize(trim($row['email'] ?? ''));
        if (empty($email)) {
            return null;
        }

        $divisionName = $this->sanitize(trim($row['divisi'] ?? ''));
        $division = Division::where('name', 'ilike', $divisionName)->first();

        if (!$division && !empty($divisionName)) {
            throw new \Exception("Gagal: Divisi '{$divisionName}' tidak ditemukan di sistem. Pastikan penulisan sesuai.");
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'email'       => $email,
                'name'        => $this->sanitize(trim($row['nama'] ?? 'User Baru')),
                'password'    => Hash::make('12345678'),
                'division_id' => $division ? $division->id : null,
            ]);
        } else {
            $user->update([
                'name'        => $this->sanitize(trim($row['nama'] ?? $user->name)),
                'division_id' => $division ? $division->id : $user->division_id,
            ]);
        }

        $roleName = strtolower($this->sanitize(trim($row['role'] ?? 'staff')));

        if ($roleName === 'admin' || !Role::where('name', $roleName)->exists()) {
            $roleName = 'staff';
        }

        $user->syncRoles([$roleName]);

        return $user;
    }

    private function sanitize($value)
    {
        if (is_string($value) && preg_match('/^[=\+\-@]/', $value)) {
            return "'" . $value;
        }
        return $value;
    }
}
