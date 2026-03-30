@extends('layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kelola Data /</span> Manajemen Anggaran</h4>

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

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h5 class="mb-3 mb-md-0">Daftar Pagu Anggaran Divisi</h5>
            <div class="d-flex flex-column flex-md-row align-items-center gap-3">

                <div class="input-group input-group-merge" style="width: 250px;">
                    <form action="{{ route('budgets.index') }}" method="GET" class="input-group input-group-merge"
                        style="width: 250px;">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari anggaran/divisi...">
                    </form>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bx bx-plus me-1"></i> Buat Anggaran
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="budgetsTable">
                <thead>
                    <tr>
                        <th>Nama Anggaran</th>
                        <th>Detail & Periode</th>
                        <th>Total Pagu (Rp)</th>
                        <th>Status Pemakaian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($budgets as $budget)
                        @php
                            $totalAmount = (float) ($budget->total_amount ?? 0);
                            $usedAmount = (float) ($budget->used_amount ?? 0);

                            $percentage = $totalAmount > 0 ? ($usedAmount / $totalAmount) * 100 : 0;
                            $progressColor =
                                $percentage < 50 ? 'bg-success' : ($percentage < 80 ? 'bg-warning' : 'bg-danger');
                        @endphp
                        <tr>
                            <td style="min-width: 200px;">
                                <strong>{{ $budget->name }}</strong><br>
                                <small class="text-muted">Oleh: {{ $budget->creator?->name ?? 'Sistem' }}</small>
                            </td>
                            <td style="min-width: 280px;">
                                <div class="d-flex flex-column gap-1 mb-2">
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted me-2" style="width: 55px;">Divisi</small>
                                        <span
                                            class="badge bg-label-info">{{ $budget->division?->name ?? 'Tanpa Divisi' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted me-2" style="width: 55px;">Kategori</small>
                                        <span class="badge bg-label-primary">{{ $budget->budgetCategory?->name ?? '-' }}
                                            (TA: {{ $budget->fiscalYear?->year ?? '-' }})
                                        </span>
                                    </div>
                                </div>
                                <small class="text-muted d-flex align-items-center">
                                    <i class="bx bx-calendar me-1"></i>
                                    {{ $budget->start_date ? \Carbon\Carbon::parse($budget->start_date)->format('d M Y') : '-' }}
                                    s/d
                                    {{ $budget->end_date ? \Carbon\Carbon::parse($budget->end_date)->format('d M Y') : '-' }}
                                </small>
                            </td>
                            <td><strong>{{ number_format($totalAmount, 0, ',', '.') }}</strong></td>
                            <td style="min-width: 200px;">
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div class="progress w-100" style="height: 8px;">
                                        <div class="progress-bar {{ $progressColor }}" role="progressbar"
                                            style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="fw-semibold">{{ number_format($percentage, 1) }}%</small>
                                </div>
                                <small class="text-muted mt-1 d-block">Terpakai: Rp
                                    {{ number_format($usedAmount, 0, ',', '.') }}</small>
                            </td>
                            <td>
                                @if ($budget->fiscalYear && $budget->fiscalYear->is_active)
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-warning"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $budget->id }}"
                                            title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger"
                                            onclick="confirmDelete('{{ $budget->id }}')" title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $budget->id }}"
                                        action="{{ route('budgets.destroy', $budget->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @else
                                    <span class="badge bg-secondary"><i class="bx bx-lock-alt me-1"></i> Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data pagu anggaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($budgets->hasPages())
            <div class="card-footer d-flex justify-content-center pb-0">
                {{ $budgets->appends(['search' => request('search')])->links() }}
            </div>
        @endif
    </div>

    @foreach ($budgets as $budget)
        <div class="modal fade" id="editModal{{ $budget->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('budgets.update', $budget->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Anggaran: {{ $budget->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Nama Anggaran</label>
                                <input type="text" name="name" class="form-control" value="{{ $budget->name }}"
                                    required />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label class="form-label">Tahun Anggaran</label>
                                <select name="fiscal_year_id" class="form-select" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach ($fiscalYears as $year)
                                        <option value="{{ $year->id }}"
                                            {{ $budget->fiscal_year_id == $year->id ? 'selected' : '' }}>
                                            {{ $year->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="budget_category_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $budget->budget_category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Divisi</label>
                                <select name="division_id" class="form-select" required>
                                    @foreach ($divisions as $divisi)
                                        <option value="{{ $divisi->id }}"
                                            {{ $budget->division_id == $divisi->id ? 'selected' : '' }}>
                                            {{ $divisi->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Total Pagu (Rp)</label>
                                <input type="number" name="total_amount" class="form-control"
                                    value="{{ intval($budget->total_amount) }}" min="{{ intval($budget->used_amount) }}"
                                    required />
                                <small class="text-danger">*Minimal angka:
                                    {{ number_format((float) $budget->used_amount, 0, ',', '.') }} (Sesuai dana yang sudah
                                    terpakai)</small>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ $budget->start_date ? \Carbon\Carbon::parse($budget->start_date)->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col mb-0">
                                <label class="form-label">Tanggal Berakhir</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ $budget->end_date ? \Carbon\Carbon::parse($budget->end_date)->format('Y-m-d') : '' }}"
                                    required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('budgets.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Buat Pagu Anggaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Nama Anggaran<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Contoh: Anggaran Q1 IT" required />
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-3">
                            <label class="form-label">Tahun Anggaran<span class="text-danger">*</span></label>
                            <select name="fiscal_year_id" class="form-select" required>
                                <option value="">-- Pilih Tahun --</option>
                                @foreach ($fiscalYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Kategori<span class="text-danger">*</span></label>
                            <select name="budget_category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Divisi<span class="text-danger">*</span></label>
                            <select name="division_id" class="form-select" required>
                                <option value="">-- Pilih Divisi --</option>
                                @foreach ($divisions as $divisi)
                                    <option value="{{ $divisi->id }}">{{ $divisi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Total Pagu (Rp)<span class="text-danger">*</span></label>
                            <input type="number" name="total_amount" class="form-control" placeholder="10000000"
                                min="0" required />
                            <small class="text-muted">Masukkan angka saja tanpa titik/koma.</small>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col mb-0">
                            <label class="form-label">Tanggal Mulai<span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required />
                        </div>
                        <div class="col mb-0">
                            <label class="form-label">Tanggal Berakhir<span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Anggaran</button>
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

        function confirmDelete(budgetId) {
            Swal.fire({
                title: 'Hapus Anggaran?',
                text: "Anggaran akan dimasukkan ke riwayat Soft Deletes dan tidak ditampilkan lagi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff3e1d',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + budgetId).submit();
                }
            });
        }
    </script>
@endsection
