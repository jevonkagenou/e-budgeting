<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class AuthController extends Controller
{
    public function index()
    {
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255'
            ],
            'password' => [
                'required',
                'string'
            ],
        ], [
            'email.required' => 'Email tidak boleh kosong!',
            'email.email'    => 'Format email tidak valid (contoh: nama@email.com).',
            'email.max'      => 'Email terlalu panjang (maksimal 255 karakter).',
            'password.required' => 'Kata sandi tidak boleh kosong!',
        ]);

        $remember = $request->has('remember_token') ? true : false;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang kembali, Super Admin!');
            } elseif ($user->hasRole('manager')) {
                return redirect()->intended('/manager/dashboard')->with('success', 'Selamat datang kembali, Manajer Keuangan!');
            } elseif ($user->hasRole('staff')) {
                return redirect()->intended('/staff/dashboard')->with('success', 'Selamat datang kembali, Staf Operasional!');
            }

            return redirect()->intended('/')->with('success', 'Berhasil masuk!');
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak ditemukan. Periksa kembali email dan kata sandi Anda.',
        ])->onlyInput('email');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // (Redirect) sesuai Role Spatie
            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->hasRole('manager')) {
                return redirect()->intended('/manager/dashboard');
            } elseif ($user->hasRole('staff')) {
                return redirect()->intended('/staff/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
