@extends('layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Sistem /</span> Log Aktivitas</h4>

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h5 class="mb-3 mb-md-0">Riwayat Sistem (Audit Trail)</h5>
            <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                <form action="{{ route('admin.logs.index') }}" method="GET" class="input-group input-group-merge"
                    style="width: 250px;">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari aktivitas...">
                </form>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pelaku</th>
                        <th>Aktivitas</th>
                        <th>Modul Target</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($log->causer)
                                    <strong>{{ $log->causer->name }}</strong>
                                @else
                                    <span class="text-muted">Sistem Otomatis</span>
                                @endif
                            </td>
                            <td>{{ $log->description }}</td>
                            <td><span class="badge bg-label-primary">{{ class_basename($log->subject_type) }}</span></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="modal"
                                    data-bs-target="#logModal{{ $log->id }}" title="Lihat Detail">
                                    <i class="bx bx-show"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada riwayat aktivitas yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="card-footer d-flex justify-content-center pb-0">
                {{ $logs->appends(['search' => request('search')])->links() }}
            </div>
        @endif
    </div>

    @foreach ($logs as $log)
        <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Perubahan Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6>Data Lama (Sebelum Perubahan):</h6>
                                <pre class="bg-dark text-white p-3 rounded" style="font-size: 13px;"><code>{{ json_encode($log->properties['old'] ?? [], JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>Data Baru (Setelah Perubahan):</h6>
                                <pre class="bg-dark text-success p-3 rounded" style="font-size: 13px;"><code>{{ json_encode($log->properties['attributes'] ?? [], JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
