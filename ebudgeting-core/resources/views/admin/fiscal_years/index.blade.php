@extends('layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kelola Data /</span> Tahun Anggaran</h4>

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
            <h5 class="mb-0">Daftar Tahun Anggaran</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bx bx-plus me-1"></i> Tambah
            </button>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($fiscalYears as $item)
                        <tr>
                            <td><strong>{{ $item->year }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</td>
                            <td>
                                @if ($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </td>
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
                                    action="{{ route('admin.fiscal_years.destroy', $item->id) }}" method="POST"
                                    class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data tahun anggaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($fiscalYears->hasPages())
            <div class="card-footer d-flex justify-content-center pb-0">{{ $fiscalYears->links() }}</div>
        @endif
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.fiscal_years.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Anggaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun<span class="text-danger">*</span></label>
                        <input type="number" name="year" class="form-control" placeholder="2026" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col">
                            <label class="form-label">Tanggal Mulai<span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Tanggal Berakhir<span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActiveAdd">
                        <label class="form-check-label" for="isActiveAdd">Set sebagai Tahun Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($fiscalYears as $item)
        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.fiscal_years.update', $item->id) }}" method="POST" class="modal-content">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Tahun Anggaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="year" class="form-control" value="{{ $item->year }}"
                                required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ $item->start_date }}" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Tanggal Berakhir</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ $item->end_date }}" required>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-2">
                            @php
                                $isExpired = \Carbon\Carbon::parse($item->end_date)->startOfDay()->isPast();
                            @endphp
                            <input class="form-check-input toggle-active-fy" type="checkbox" name="is_active"
                                id="isActiveEdit{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}
                                {{ $isExpired && !$item->is_active ? 'disabled' : '' }}>
                            <label class="form-check-label" for="isActiveEdit{{ $item->id }}">Set sebagai Tahun
                                Aktif</label>
                            @if ($isExpired && !$item->is_active)
                                <small class="text-danger d-block mt-1" id="warningEdit{{ $item->id }}">
                                    <i class="bx bx-error-circle"></i> Ubah Tanggal Berakhir ke masa depan untuk
                                    mengaktifkan.
                                </small>
                            @endif
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
                title: 'Hapus Tahun?',
                text: "Tahun anggaran ini akan dihapus dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3e1d',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const editModals = document.querySelectorAll('[id^="editModal"]');

            editModals.forEach(modal => {
                const endDateInput = modal.querySelector('input[name="end_date"]');
                const activeSwitch = modal.querySelector('.toggle-active-fy');
                const warningText = modal.querySelector('[id^="warningEdit"]');

                if (endDateInput && activeSwitch) {
                    endDateInput.addEventListener('change', function() {
                        const selectedDate = new Date(this.value);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (selectedDate >= today) {
                            activeSwitch.removeAttribute('disabled');
                            if (warningText) warningText.style.display = 'none';
                        } else {
                            activeSwitch.setAttribute('disabled', 'disabled');
                            activeSwitch.checked = false;
                            if (warningText) warningText.style.display = 'block';
                        }
                    });
                }
            });
        });
    </script>
@endsection
