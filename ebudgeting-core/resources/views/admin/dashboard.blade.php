@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-4 col-md-12 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-time-five"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Menunggu Persetujuan</span>
                <h3 class="card-title mb-2">{{ $pendingCount }} <small class="text-muted fs-6">Pengajuan</small></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-double"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Dana Keluar (Bulan Ini)</span>
                <h3 class="card-title mb-2">Rp {{ number_format($approvedThisMonth, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info"><i class="bx bx-wallet"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Sisa Pagu (Global)</span>
                <h3 class="card-title mb-2">Rp {{ number_format($totalBudgetRemaining, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Tren Realisasi Pengeluaran ({{ date('Y') }})</h5>
            </div>
            <div class="card-body">
                <div id="expenseChart" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-lg-8 order-1 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">5 Pengajuan Dana Terbaru</h5>
                <a href="{{ route('reimbursements.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pengaju & Divisi</th>
                            <th>Nominal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($recentReimbursements as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->user->name }}</strong><br>
                                <small class="text-muted">{{ $item->user->division->name ?? '-' }}</small>
                            </td>
                            <td class="fw-semibold">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td>
                                @if($item->status === 'pending')
                                    <span class="badge bg-label-warning">Pending</span>
                                @elseif($item->status === 'approved')
                                    <span class="badge bg-label-success">Approved</span>
                                @else
                                    <span class="badge bg-label-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4">Belum ada pengajuan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-4 order-2 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Log Aktivitas</h5>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    @forelse($recentLogs as $log)
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-history"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0">{{ $log->causer->name ?? 'Sistem' }}</h6>
                                <small class="text-muted">{{ $log->description }}</small>
                            </div>
                            <div class="user-progress">
                                <small class="fw-semibold">{{ $log->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-muted py-4">Belum ada log terekam.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartData = {!! $chartDataJson !!};

        const options = {
            series: [{
                name: 'Total Pencairan (Rp)',
                data: chartData
            }],
            chart: {
                height: 300,
                type: 'area',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3, colors: ['#696cff'] },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                },
                colors: ['#696cff']
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#a1acb8', fontSize: '13px' } }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return "Rp " + (val / 1000000).toFixed(1) + " Jt";
                    },
                    style: { colors: '#a1acb8', fontSize: '13px' }
                }
            },
            grid: {
                borderColor: '#f0f2f4',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#expenseChart"), options);
        chart.render();
    });
</script>
@endsection
