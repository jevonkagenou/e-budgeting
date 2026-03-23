<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pertanggungjawaban (LPJ)</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 9pt; color: #2c3e50; line-height: 1.4; }

        .header-container { width: 100%; border-bottom: 2px solid #2980b9; padding-bottom: 12px; margin-bottom: 25px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; border: none; padding: 0; }

        .brand-title { font-size: 20pt; font-weight: 800; color: #2c3e50; margin: 0; letter-spacing: 0.5px; text-transform: uppercase; }
        .brand-subtitle { font-size: 9.5pt; color: #7f8c8d; margin: 4px 0 0 0; font-weight: 500; }

        .doc-meta { text-align: right; font-size: 8.5pt; color: #555; }
        .doc-meta span { display: block; margin-bottom: 3px; }

        .report-header { text-align: center; margin-bottom: 25px; }
        .report-title { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; color: #2c3e50; letter-spacing: 1px; }
        .report-period { font-size: 9.5pt; color: #7f8c8d; margin-top: 6px; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 35px; }
        table.data-table thead th { background-color: #2980b9; color: #ffffff; text-align: left; padding: 12px 10px; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #2980b9; }
        table.data-table tbody td { padding: 10px; border-bottom: 1px solid #ecf0f1; border-left: 1px solid #ecf0f1; border-right: 1px solid #ecf0f1; vertical-align: top; }
        table.data-table tbody tr:nth-child(even) { background-color: #f8fbfd; }
        table.data-table tfoot th { background-color: #ecf0f1; color: #2c3e50; padding: 12px 10px; text-align: right; font-size: 9pt; border: 1px solid #ecf0f1; }

        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .fw-bold { font-weight: bold; }

        .badge-divisi { display: block; color: #7f8c8d; font-size: 7.5pt; margin-top: 2px; }
        .badge-ta { display: inline-block; padding: 3px 6px; background-color: #e8f4f8; color: #2980b9; border-radius: 3px; font-size: 7.5pt; margin-top: 3px; border: 1px solid #d6eaf8; }

        .signature-section { width: 100%; margin-top: 40px; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { width: 33.33%; text-align: center; vertical-align: bottom; border: none; padding: 0; }

        .sign-space { height: 80px; }
        .sign-name { font-weight: bold; font-size: 10pt; text-decoration: underline; margin-bottom: 3px; color: #2c3e50; }
        .sign-title { font-size: 8.5pt; color: #7f8c8d; margin: 0; }
    </style>
</head>
<body>

    <div class="header-container">
        <table class="header-table">
            <tr>
                <td style="width: 65%;">
                    <h1 class="brand-title">SyncBudget - Wardiere Inc.</h1>
                    <p class="brand-subtitle">Sistem Manajemen Pengajuan Dana Operasional (E-Budgeting)</p>
                    <p class="brand-subtitle" style="font-size: 8pt; color: #95a5a6;">Jl. Raya Krajan RT 18 RW 05, Jambuwer, Kromengan, Malang, Jawa Timur</p>
                </td>
                <td style="width: 35%;" class="doc-meta">
                    <span>Dokumen ID: <strong>LPJ-{{ date('YmdHis') }}</strong></span>
                    <span>Dicetak pada: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y - H:i') }} WIB</strong></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="report-header">
        <h2 class="report-title">Laporan Realisasi Dana Operasional</h2>
        <div class="report-period">
            @if(request('start_date') && request('end_date'))
                Periode Persetujuan: {{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d F Y') }}
            @elseif(request('start_date'))
                Periode Persetujuan: Mulai {{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d F Y') }}
            @elseif(request('end_date'))
                Periode Persetujuan: Hingga {{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d F Y') }}
            @else
                Periode Persetujuan: Seluruh Data Historis
            @endif
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 12%; text-align: center;">Tanggal</th>
                <th style="width: 20%; text-align: center;">Pengaju / Divisi</th>
                <th style="width: 18%; text-align: center;">Sumber Dana</th>
                <th style="width: 23%; text-align: center;">Rincian Keperluan</th>
                <th style="width: 13%; text-align: center;">Otorisasi</th>
                <th style="width: 10%; text-align: right;">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reimbursements as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y') }}</td>
                <td>
                    <span class="fw-bold">{{ $item->user->name }}</span>
                    <span class="badge-divisi">{{ $item->user->division->name ?? '-' }}</span>
                </td>
                <td>
                    <span class="fw-bold">{{ $item->budget->name }}</span><br>
                    <span class="badge-ta">TA: {{ $item->budget->fiscalYear->year ?? '-' }}</span>
                </td>
                <td>{{ $item->title }}</td>
                <td>{{ $item->actionBy->name ?? 'Sistem' }}</td>
                <td class="text-center fw-bold">{{ number_format($item->amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4">Tidak ada data transaksi pengajuan dana pada rentang periode yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6">TOTAL REALISASI PENGELUARAN :</th>
                <th class="text-center" style="font-size: 10pt;">{{ number_format($reimbursements->sum('amount'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    <p class="sign-title" style="margin-bottom: 5px; color: #2c3e50;">Dibuat Oleh,</p>
                    <div class="sign-space"></div>
                    <p class="sign-name">{{ Auth::user()->name }}</p>
                    <p class="sign-title">Administrator Sistem</p>
                </td>
                <td>
                    <p class="sign-title" style="margin-bottom: 5px; color: #2c3e50;">Diperiksa Oleh,</p>
                    <div class="sign-space"></div>
                    <p class="sign-name">_______________________</p>
                    <p class="sign-title">Finance Auditor</p>
                </td>
                <td>
                    <p class="sign-title" style="margin-bottom: 5px; color: #2c3e50;">Mengetahui,</p>
                    <div class="sign-space"></div>
                    <p class="sign-name">_______________________</p>
                    <p class="sign-title">Manajer Keuangan</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
