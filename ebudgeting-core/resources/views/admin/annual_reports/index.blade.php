@extends('layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kelola Data /</span> Arsip Laporan Tahunan</h4>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="mb-0 text-nowrap">Daftar Laporan Kinerja Keuangan</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tahun Buku</th>
                        <th>Nama Laporan</th>
                        <th>Total Pagu</th>
                        <th>Total Realisasi</th>
                        <th>Efisiensi (Sisa)</th>
                        <th>Tanggal Cetak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($reports as $report)
                        <tr>
                            <td><strong>{{ $report->fiscalYear->year ?? '-' }}</strong></td>
                            <td>
                                <i class="bx bxs-file-pdf text-danger me-2"></i>
                                {{ $report->file_name }}
                            </td>
                            <td>Rp {{ number_format($report->total_budget, 0, ',', '.') }}</td>
                            <td class="text-warning">Rp {{ number_format($report->total_used, 0, ',', '.') }}</td>
                            <td class="text-success fw-bold">Rp {{ number_format($report->total_remaining, 0, ',', '.') }}
                            </td>
                            <td>{{ $report->created_at->translatedFormat('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('management.annual_reports.download', $report->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="bx bx-download me-1"></i> Unduh
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Belum ada arsip laporan tahunan yang dicetak.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($reports->hasPages())
            <div class="card-footer d-flex flex-wrap justify-content-center pb-0">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
@endsection
