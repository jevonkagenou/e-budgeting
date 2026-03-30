<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Keuangan {{ $fiscalYear->year }}</title>
    <style>
        @page {
            margin: 35px 45px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 10pt;
            line-height: 1.4;
        }

        /* Kop Surat ala LPJ */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #3498db; /* Garis biru khas LPJ */
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .company-title {
            font-family: 'Georgia', 'Times New Roman', serif; /* Serif seperti di LPJ */
            font-size: 18pt;
            color: #2c3e50;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }

        .company-sub {
            font-size: 9pt;
            color: #7f8c8d;
            margin: 4px 0 0 0;
        }

        .doc-info {
            font-size: 8pt;
            color: #7f8c8d;
            text-align: right;
            vertical-align: bottom;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin-bottom: 25px;
        }

        .report-title h2 {
            font-size: 13pt;
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .report-title p {
            font-size: 9pt;
            color: #7f8c8d;
            margin: 5px 0 0 0;
        }

        /* Ringkasan ala Kotak Modern */
        .summary-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }

        .summary-table td {
            background-color: #f8fafc;
            padding: 15px;
            text-align: center;
            border: 1px solid #e2e8f0;
            width: 33.33%;
        }

        .summary-label {
            font-size: 8pt;
            color: #7f8c8d;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 14pt;
            color: #2c3e50;
            font-weight: bold;
        }

        /* Tabel Data ala LPJ */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .data-table th {
            background-color: #3498db; /* Biru terang LPJ */
            color: #ffffff;
            font-size: 8pt;
            padding: 10px;
            text-transform: uppercase;
            border: 1px solid #2980b9;
            text-align: center;
        }

        .data-table td {
            padding: 9px;
            font-size: 9pt;
            color: #444;
            border: 1px solid #e2e8f0; /* Garis abu-abu lembut, bukan hitam legam */
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc; /* Efek zebra tabel yang sangat tipis */
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        /* Blok Tanda Tangan */
        .signature-table {
            width: 100%;
            margin-top: 40px;
            text-align: center;
            font-size: 9pt;
            color: #2c3e50;
            page-break-inside: avoid; /* Mencegah ttd terpotong ke halaman 2 */
        }

        .signature-table td { width: 33.33%; }
        .sign-space { height: 70px; }
        .sign-name { font-weight: bold; text-decoration: underline; }
        .sign-title { font-size: 8pt; color: #7f8c8d; margin-top: 2px; }

    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="65%">
                <h1 class="company-title">SYNCBUDGET - PT. INDONESIA</h1>
                <p class="company-sub">Sistem Manajemen Pengajuan Dana Operasional (E-Budgeting)</p>
                <p class="company-sub">Jln. Raya Krajan RT 18 RW 05, Jambuwer, Kromengan, Malang</p>
            </td>
            <td width="35%" class="doc-info">
                Dokumen ID: YEC-{{ $fiscalYear->year }}{{ time() }}<br>
                Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y - H:i') }} WIB
            </td>
        </tr>
    </table>

    <div class="report-title">
        <h2>Laporan Eksekutif Kinerja & Efisiensi Anggaran</h2>
        <p>Tahun Buku Terpusat: Seluruh Data Historis ({{ $fiscalYear->year }})</p>
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-label">Total Pagu Anggaran</div>
                <div class="summary-value">Rp {{ number_format($report->total_budget, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Total Realisasi Pengeluaran</div>
                <div class="summary-value">Rp {{ number_format($report->total_used, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Total Efisiensi (Sisa Kas)</div>
                <div class="summary-value" style="color: #27ae60;">Rp {{ number_format($report->total_remaining, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Divisi</th>
                <th width="22%">Dompet Anggaran</th>
                <th width="16%">Total Pagu</th>
                <th width="15%">Realisasi</th>
                <th width="16%">Sisa/Efisiensi</th>
                <th width="8%">Serapan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($budgets as $index => $budget)
                @php
                    $sisa = $budget->total_amount - $budget->used_amount;
                    $persentase = $budget->total_amount > 0 ? ($budget->used_amount / $budget->total_amount) * 100 : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $budget->division->name ?? 'Divisi Terhapus' }}</td>
                    <td>{{ $budget->name }}</td>
                    <td class="text-right">Rp {{ number_format($budget->total_amount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($budget->used_amount, 0, ',', '.') }}</td>
                    <td class="text-right fw-bold">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($persentase, 1) }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada data anggaran pada tahun buku ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                Dibuat Oleh,<br>
                <div class="sign-space"></div>
                <div class="sign-name">Sistem Cron (Auto-Report)</div>
                <div class="sign-title">Administrator Sistem</div>
            </td>
            <td>
                Diperiksa Oleh,<br>
                <div class="sign-space"></div>
                <div class="sign-name">_______________________</div>
                <div class="sign-title">Finance Auditor</div>
            </td>
            <td>
                Mengetahui,<br>
                <div class="sign-space"></div>
                <div class="sign-name">_______________________</div>
                <div class="sign-title">Manajer Keuangan / Direktur</div>
            </td>
        </tr>
    </table>

</body>
</html>
