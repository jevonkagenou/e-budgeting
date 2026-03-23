@extends('layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Transaksi /</span> Pengajuan Dana (Reimbursement)
    </h4>

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
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="mb-0">Daftar Pengajuan Dana</h5>

            <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                @hasanyrole('manager|admin')
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exportPdfModal">
                        <i class="bx bxs-file-pdf me-1"></i> Export LPJ
                    </button>
                @endhasanyrole
                <form action="{{ route('reimbursements.index') }}" method="GET" class="d-flex gap-2">
                    <select name="status" class="form-select" style="width: 160px;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <div class="input-group input-group-merge" style="width: 250px;">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari pengajuan...">
                    </div>
                </form>

                @hasanyrole('staff|admin')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bx bx-plus me-1"></i> Buat Pengajuan
                    </button>
                @endhasanyrole
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pengaju & Divisi</th>
                        <th>Tujuan Anggaran</th>
                        <th>Keterangan</th>
                        <th>Nominal (Rp)</th>
                        <th>Status</th>
                        @hasanyrole('manager|admin')
                            <th>Aksi</th>
                        @endhasanyrole
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($reimbursements as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                            <td>
                                <strong>{{ $item->user->name }}</strong><br>
                                <small class="text-muted">{{ $item->user->division->name ?? '-' }}</small>
                            </td>
                            <td><span class="badge bg-label-info">{{ $item->budget->name }}</span></td>
                            <td style="white-space: normal; min-width: 250px;">
                                {{ $item->title }}
                                @if ($item->receipt_path)
                                    <br>
                                    <a href="{{ asset('storage/' . $item->receipt_path) }}" target="_blank"
                                        class="text-primary fs-7 d-inline-flex align-items-center mt-1">
                                        <i class="bx bx-image me-1"></i> Lihat Struk
                                    </a>
                                @endif
                                @if ($item->status === 'rejected' && $item->rejection_reason)
                                    <br>
                                    <small class="text-danger mt-1 d-block"><i class="bx bx-error-circle"></i>
                                        {{ $item->rejection_reason }}</small>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td>
                                @if ($item->status === 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($item->status === 'approved')
                                    <span class="badge bg-success">Disetujui</span><br>
                                    <small class="text-muted">Oleh: {{ $item->actionBy->name ?? 'Sistem' }}</small>
                                @else
                                    <span class="badge bg-danger">Ditolak</span><br>
                                    <small class="text-muted">Oleh: {{ $item->actionBy->name ?? 'Sistem' }}</small>
                                @endif
                            </td>
                            @hasanyrole('manager|admin')
                                <td>
                                    @if ($item->status === 'pending')
                                        <div class="d-flex align-items-center gap-2">
                                            <form id="approve-form-{{ $item->id }}"
                                                action="{{ route('reimbursements.approve', $item->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="button" class="btn btn-sm btn-icon btn-success"
                                                    onclick="confirmApprove('{{ $item->id }}')">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-icon btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $item->id }}">
                                                <i class="bx bx-x"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted"><i class="bx bx-lock-alt"></i> Selesai</span>
                                    @endif
                                </td>
                            @endhasanyrole
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Belum ada data pengajuan dana.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reimbursements->hasPages())
            <div class="card-footer d-flex justify-content-center pb-0">
                {{ $reimbursements->appends(['search' => request('search'), 'status' => request('status')])->links() }}
            </div>
        @endif
    </div>

    @foreach ($reimbursements as $item)
        @if ($item->status === 'pending')
            <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('reimbursements.reject', $item->id) }}" method="POST" class="modal-content">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title text-danger">Tolak Pengajuan Dana</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda akan menolak pengajuan <strong>{{ $item->title }}</strong> senilai Rp
                                {{ number_format($item->amount, 0, ',', '.') }}.</p>
                            <div class="col mb-3">
                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('reimbursements.store') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Form Pengajuan Dana Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Ambil Dari Anggaran <span class="text-danger">*</span></label>
                            <select name="budget_id" class="form-select" required>
                                <option value="">-- Pilih Dompet Anggaran --</option>
                                @foreach ($budgets as $budget)
                                    <option value="{{ $budget->id }}">
                                        {{ $budget->name }} (Sisa: Rp
                                        {{ number_format($budget->total_amount - $budget->used_amount, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Judul Pengajuan <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" min="1000" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Bukti Struk/Nota (Opsional)</label>
                            <input type="file" name="receipt" class="form-control"
                                accept="image/jpeg, image/png, image/jpg" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Keterangan Tambahan</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="exportPdfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('reimbursements.export.pdf') }}" method="GET" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Laporan PDF (LPJ)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        Biarkan tanggal kosong jika ingin mencetak seluruh data historis yang telah disetujui.
                    </div>
                    <div class="row g-2">
                        <div class="col mb-0">
                            <label class="form-label">Mulai Tanggal (Disetujui)</label>
                            <input type="date" name="start_date" class="form-control" />
                        </div>
                        <div class="col mb-0">
                            <label class="form-label">Sampai Tanggal (Disetujui)</label>
                            <input type="date" name="end_date" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="bx bxs-file-pdf me-1"></i> Download
                        PDF</button>
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

        function confirmApprove(id) {
            Swal.fire({
                title: 'Setujui Pengajuan?',
                text: "Saldo anggaran akan otomatis terpotong.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#71dd37',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approve-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
