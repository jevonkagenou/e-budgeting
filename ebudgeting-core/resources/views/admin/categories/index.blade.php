@extends('layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kelola Data /</span> Kategori Anggaran</h4>

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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Kategori</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bx bx-plus me-1"></i> Tambah
            </button>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($categories as $item)
                        <tr>
                            <td><span class="badge bg-label-primary">{{ $item->code }}</span></td>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Hapus"
                                        onclick="confirmDelete('{{ $item->id }}')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('admin.categories.destroy', $item->id) }}" method="POST"
                                    class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())
            <div class="card-footer d-flex justify-content-center pb-0">{{ $categories->links() }}</div>
        @endif
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.categories.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Kategori<span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="Contoh: IT-01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Infrastruktur & IT"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($categories as $item)
        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.categories.update', $item->id) }}" method="POST" class="modal-content">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Kategori</label>
                            <input type="text" name="code" class="form-control" value="{{ $item->code }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="name" class="form-control" value="{{ $item->name }}"
                                required>
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

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Kategori ini akan dihapus dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3e1d',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        }
    </script>
@endsection
