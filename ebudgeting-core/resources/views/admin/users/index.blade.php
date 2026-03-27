@extends('layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Sistem /</span> Manajemen Pengguna</h4>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
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

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h5 class="mb-3 mb-md-0">Daftar Pengguna</h5>
            <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                <form action="{{ route('admin.users.index') }}" method="GET" class="input-group input-group-merge"
                    style="width: 250px;">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari pengguna...">
                </form>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bx bx-import me-1"></i> Import CSV
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bx bx-plus me-1"></i> Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="usersTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Divisi Utama</th>
                        <th>Divisi Diawasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-label-primary">{{ $user->roles->first()->name ?? '-' }}</span></td>
                            <td>{{ $user->division->name ?? '-' }}</td>
                            <td>
                                @if ($user->hasRole('manager'))
                                    <div class="d-flex flex-wrap gap-1">
                                        @php
                                            $managed = $user->managedDivisions;
                                            $totalManaged = $managed->count();
                                            $limit = 2;
                                        @endphp

                                        @foreach ($managed->take($limit) as $div)
                                            <span class="badge bg-label-info">{{ $div->name }}</span>
                                        @endforeach

                                        @if ($totalManaged > $limit)
                                            @php
                                                $remainingNames = $managed->skip($limit)->pluck('name')->implode(', ');
                                            @endphp
                                            <span class="badge bg-label-secondary" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="{{ $remainingNames }}"
                                                style="cursor: pointer;">
                                                +{{ $totalManaged - $limit }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger"
                                        onclick="confirmDelete('{{ $user->id }}')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $user->id }}"
                                    action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="card-footer d-flex justify-content-center pb-0">
                {{ $users->appends(['search' => request('search')])->links() }}
            </div>
        @endif
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-start mb-3" role="alert">
                        <span class="alert-icon text-info me-2 mt-1">
                            <i class="bx bx-info-circle"></i>
                        </span>
                        <small>
                            Sistem mengabaikan kolom password di CSV demi keamanan. Pengguna baru akan otomatis diberikan
                            password default: <br>
                            <code class="fs-6 text-dark fw-bold mt-1 d-block">12345678</code>
                        </small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih File CSV/Excel<span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".csv, .xlsx, .xls" required />
                        <div class="form-text mt-2">
                            Belum punya template? <a href="{{ route('admin.users.template') }}">Download Template CSV</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.users.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password<span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="8" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Divisi Utama<span class="text-danger">*</span></label>
                        <select name="division_id" class="form-select" required>
                            <option value="">-- Pilih Divisi --</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role<span class="text-danger">*</span></label>
                        <select name="role" class="form-select role-select" data-target="managed-divisions-add"
                            required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="managed-divisions-add">
                        <label class="form-label">Pilih Divisi yang Diawasi (Khusus Manajer)</label>
                        <select name="managed_divisions[]" class="form-select select2" multiple style="width: 100%;">
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($users as $user)
        <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" minlength="8" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Divisi Utama</label>
                            <select name="division_id" class="form-select" required>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}"
                                        {{ $user->division_id == $division->id ? 'selected' : '' }}>{{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select role-select"
                                data-target="managed-divisions-edit-{{ $user->id }}" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 {{ $user->hasRole('manager') ? '' : 'd-none' }}"
                            id="managed-divisions-edit-{{ $user->id }}">
                            <label class="form-label">Pilih Divisi yang Diawasi (Khusus Manajer)</label>
                            <select name="managed_divisions[]" class="form-select select2" multiple style="width: 100%;">
                                @php $managedIds = $user->managedDivisions->pluck('id')->toArray(); @endphp
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}"
                                        {{ in_array($division->id, $managedIds) ? 'selected' : '' }}>{{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            let checkJquery = setInterval(function() {
                if (window.jQuery) {
                    clearInterval(checkJquery);
                    $.getScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                        function() {
                            $('.select2').select2({
                                placeholder: "-- Pilih Divisi --",
                                allowClear: true
                            });
                            $('.modal').on('shown.bs.modal', function() {
                                $(this).find('.select2').select2({
                                    dropdownParent: $(this),
                                    placeholder: "-- Pilih Divisi --",
                                    allowClear: true
                                });
                            });
                        });
                }
            }, 100);

            const roleSelects = document.querySelectorAll('.role-select');
            roleSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetDiv = document.getElementById(targetId);
                    if (this.value === 'manager') {
                        targetDiv.classList.remove('d-none');
                    } else {
                        targetDiv.classList.add('d-none');
                        if (window.jQuery && $.fn.select2) {
                            $('#' + targetId + ' select').val(null).trigger('change');
                        }
                    }
                });
            });

            let typingTimer;
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => {
                        this.form.submit();
                    }, 500);
                });
                if (searchInput.value) {
                    searchInput.focus();
                    let valLen = searchInput.value.length;
                    searchInput.setSelectionRange(valLen, valLen);
                }
            }
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengguna ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3e1d',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
