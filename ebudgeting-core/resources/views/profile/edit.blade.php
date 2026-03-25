@extends('layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pengaturan /</span> Profil Saya
    </h4>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Detail Profil</h5>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" required autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" type="email" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required />
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Divisi & Role</label>
                                <input class="form-control" type="text"
                                    value="{{ $user->division->name ?? 'Admin Sistem' }} ({{ ucfirst($user->roles->first()->name ?? 'Tanpa Role') }})"
                                    disabled />
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="card-header px-0 pb-3">Ubah Password</h5>
                        <div class="alert alert-info">
                            Biarkan kosong jika Anda tidak ingin mengubah password saat ini. Jika ingin mengubah, Anda wajib
                            memasukkan password lama.
                        </div>

                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input class="form-control" type="password" id="current_password" name="current_password" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="password" class="form-label">Password Baru</label>
                                <input class="form-control" type="password" id="password" name="password" />
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input class="form-control" type="password" id="password_confirmation"
                                    name="password_confirmation" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
