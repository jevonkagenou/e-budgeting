@extends('layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Sistem /</span> Manajemen Divisi</h4>

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
        <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <h5 class="mb-0 text-nowrap">Daftar Divisi</h5>
            <form action="{{ route('admin.divisions.index') }}" method="GET" class="d-grid gap-2 d-lg-flex">
                <div class="input-group input-group-merge" style="min-width: 250px;">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari divisi...">
                </div>
                <button type="button" class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bx bx-plus me-1"></i> Tambah Divisi
                </button>
            </form>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="divisionsTable">
                <thead>
                    <tr>
                        <th style="width: 10%">No</th>
                        <th>Nama Divisi</th>
                        <th style="width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($divisions as $index => $division)
                        <tr>
                            <td>{{ $divisions->firstItem() + $index }}</td>
                            <td><strong>{{ $division->name }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $division->id }}">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Hapus"
                                        onclick="confirmDelete('{{ $division->id }}')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $division->id }}"
                                    action="{{ route('admin.divisions.destroy', $division->id) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Belum ada data divisi yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($divisions->hasPages())
            <div class="card-footer pb-0">
                {{ $divisions->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    @foreach ($divisions as $division)
        <div class="modal fade" id="editModal{{ $division->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('admin.divisions.update', $division->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Divisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Divisi <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $division->name }}" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-grid w-100 d-sm-flex justify-content-sm-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.divisions.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Divisi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Divisi <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: HRD, Keuangan, IT" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-grid w-100 d-sm-flex justify-content-sm-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Jika divisi ini dihapus, staf yang berada di divisi ini akan kehilangan data divisinya!",
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
