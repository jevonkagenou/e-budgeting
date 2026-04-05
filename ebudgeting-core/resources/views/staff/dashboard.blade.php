@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-time"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Pengajuanku (Menunggu)</span>
                    <h3 class="card-title mb-2">{{ $myPending }} <small class="text-muted fs-6">Dokumen</small></h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-shield"></i></span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Total Dana Disetujui (Personal)</span>
                    <h3 class="card-title mb-2">Rp {{ number_format($myApprovedTotal, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2">
                    <h5 class="card-title m-0 me-2">Riwayat Pengajuanku</h5>
                    <a href="{{ route('reimbursements.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Keperluan</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($recentReimbursements as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->title }}</strong><br>
                                        <small class="text-muted">{{ $item->budget->name }}</small>
                                    </td>
                                    <td>Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
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
                                    <td colspan="3" class="text-center py-3">Kamu belum membuat pengajuan apapun.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title m-0">Ketersediaan Anggaran Divisi</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @forelse($myBudgets as $budget)
                            @php
                                $percentage =
                                    $budget->total_amount > 0
                                        ? ($budget->used_amount / $budget->total_amount) * 100
                                        : 0;
                                $progressColor =
                                    $percentage < 50 ? 'bg-success' : ($percentage < 80 ? 'bg-warning' : 'bg-danger');
                            @endphp
                            <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">{{ $budget->name }}</h6>
                                        <small class="text-muted">Sisa: Rp
                                            {{ number_format($budget->total_amount - $budget->used_amount, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="user-progress w-25">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar {{ $progressColor }}" role="progressbar"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-center text-muted">Belum ada anggaran aktif untuk divisimu saat ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
