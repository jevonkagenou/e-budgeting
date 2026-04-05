@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-task"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Tugas Persetujuan (Tim)</span>
                    <h3 class="card-title mb-2">{{ $pendingCount }} <small class="text-muted fs-6">Menunggu</small></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-trending-up"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Realisasi Divisi (Bulan Ini)</span>
                    <h3 class="card-title mb-2">Rp {{ number_format($approvedThisMonth, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-wallet"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Sisa Anggaran Divisi</span>
                    <h3 class="card-title mb-2">Rp {{ number_format($totalBudgetRemaining, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
                    <h5 class="card-title m-0 me-2">Pengajuan Terbaru dari Tim</h5>
                    <a href="{{ route('reimbursements.index') }}" class="btn btn-sm btn-outline-primary">Kelola
                        Pengajuan</a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Staff</th>
                                <th>Keperluan</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($recentReimbursements as $item)
                                <tr>
                                    <td><strong>{{ $item->user->name }}</strong></td>
                                    <td>{{ $item->title }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($item->status === 'pending')
                                            <span class="badge bg-label-warning">Pending</span>
                                        @elseif($item->status === 'approved')
                                            <span class="badge bg-label-success">Approved</span>
                                        @else
                                            <span class="badge bg-label-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Belum ada pengajuan dari tim.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
