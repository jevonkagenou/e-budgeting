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
        $divisionName = $this->sanitize(trim($row['divisi']));

        $division = Division::firstOrCreate([
            'name' => $divisionName
        ]);

        $user = User::updateOrCreate(
            ['email' => $this->sanitize(trim($row['email']))],
            [
                'name'        => $this->sanitize(trim($row['nama'])),
                'password'    => Hash::make($row['password']),
                'division_id' => $division->id,
            ]
        );

        $roleName = isset($row['role']) ? $this->sanitize(trim($row['role'])) : 'staff';

        if (Role::where('name', $roleName)->exists()) {
            $user->syncRoles([$roleName]);
        } else {
            $user->syncRoles(['staff']);
        }

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
