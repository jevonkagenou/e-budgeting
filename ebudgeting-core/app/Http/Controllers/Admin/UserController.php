<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->with('managedDivisions');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::where('name', '!=', 'admin')->get();
        $divisions = Division::all();

        return view('admin.users.index', compact('users', 'roles', 'search', 'divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'division_id' => 'required|exists:divisions,id',
            'managed_divisions' => 'nullable|array',
            'managed_divisions.*' => 'exists:divisions,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'division_id' => $request->division_id
        ]);

        $user->assignRole($request->role);

        if ($request->role === 'manager' && $request->has('managed_divisions')) {
            $user->managedDivisions()->sync($request->managed_divisions);
        }

        return back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return back()->with('success', 'Data pengguna berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal impor! ' . $e->getMessage());
        }
    }

    public function template()
    {
        $path = public_path('templates/template_pengguna.csv');

        if (!file_exists($path)) {
            return back()->with('error', 'File template belum tersedia di server.');
        }

        return response()->download($path, 'Template_Import_Pengguna.csv');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id)->whereNull('deleted_at')
            ],
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
            'division_id' => 'required|exists:divisions,id',
            'managed_divisions' => 'nullable|array',
            'managed_divisions.*' => 'exists:divisions,id'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'division_id' => $request->division_id
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        $user->syncRoles([$request->role]);

        if ($request->role === 'manager') {
            $user->managedDivisions()->sync($request->managed_divisions ?? []);
        } else {
            $user->managedDivisions()->detach();
        }

        return back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() == $user->id) {
            return back()->with('error', 'Akses Ditolak! Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Data pengguna berhasil dihapus secara permanen!');
    }
}
