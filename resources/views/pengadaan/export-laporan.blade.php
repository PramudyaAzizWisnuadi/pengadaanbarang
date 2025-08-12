<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Pengadaan Barang</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                line-height: 1.4;
                color: #333;
            }

            .header {
                text-align: center;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 2px solid #333;
            }

            .header h1 {
                font-size: 20px;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .header h2 {
                font-size: 16px;
                font-weight: normal;
                color: #666;
            }

            .info-section {
                margin-bottom: 20px;
            }

            .info-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 5px;
            }

            .statistics {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
                margin-bottom: 30px;
            }

            .stat-item {
                text-align: center;
                padding: 15px;
                border: 1px solid #ddd;
                background-color: #f8f9fa;
            }

            .stat-item h3 {
                font-size: 14px;
                margin-bottom: 5px;
                color: #666;
            }

            .stat-item .number {
                font-size: 18px;
                font-weight: bold;
                color: #333;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
                vertical-align: top;
            }

            th {
                background-color: #f5f5f5;
                font-weight: bold;
                text-align: center;
            }

            .text-center {
                text-align: center;
            }

            .text-right {
                text-align: right;
            }

            .status-badge {
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .status-draft {
                background-color: #6c757d;
                color: white;
            }

            .status-submitted {
                background-color: #ffc107;
                color: black;
            }

            .status-approved {
                background-color: #28a745;
                color: white;
            }

            .status-rejected {
                background-color: #dc3545;
                color: white;
            }

            .status-completed {
                background-color: #007bff;
                color: white;
            }

            .footer {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
            }

            .signature {
                display: flex;
                justify-content: space-between;
                margin-top: 40px;
            }

            .signature-box {
                text-align: center;
                width: 200px;
            }

            .signature-line {
                margin-top: 60px;
                border-top: 1px solid #333;
                padding-top: 5px;
            }

            @media print {
                body {
                    margin: 0;
                    font-size: 10px;
                }

                .statistics {
                    grid-template-columns: repeat(6, 1fr);
                    gap: 5px;
                }

                .stat-item {
                    padding: 5px;
                }

                th,
                td {
                    padding: 4px;
                    font-size: 9px;
                }

                .signature {
                    page-break-inside: avoid;
                }
            }

            /* PDF specific styles */
            @page {
                margin: 1cm;
                size: A4;
            }

            .page-break {
                page-break-before: always;
            }

            .no-break {
                page-break-inside: avoid;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h1>LAPORAN PENGADAAN BARANG</h1>
            <h2>Sistem Informasi Pengadaan Barang</h2>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</span>
                <span><strong>Total Data:</strong> {{ $statistics['total_pengadaan'] }} pengadaan</span>
            </div>
            @if (isset($request) && ($request->filled('start_date') || $request->filled('end_date')))
                <div class="info-row">
                    <span><strong>Periode:</strong>
                        @if ($request->filled('start_date'))
                            {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                        s/d
                        @if ($request->filled('end_date'))
                            {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}
                        @else
                            {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                        @endif
                    </span>
                </div>
            @endif
            @if (isset($request) && $request->filled('status') && $request->status !== 'all')
                <div class="info-row">
                    <span><strong>Filter Status:</strong> {{ ucfirst($request->status) }}</span>
                </div>
            @endif
            @if (isset($request) && $request->filled('departemen') && $request->departemen !== 'all')
                <div class="info-row">
                    <span><strong>Filter Departemen:</strong> {{ $request->departemen }}</span>
                </div>
            @endif
        </div>

        <div class="statistics">
            <div class="stat-item">
                <h3>Total Pengadaan</h3>
                <div class="number">{{ $statistics['total_pengadaan'] }}</div>
            </div>
            <div class="stat-item">
                <h3>Draft</h3>
                <div class="number">{{ $statistics['draft'] }}</div>
            </div>
            <div class="stat-item">
                <h3>Submitted</h3>
                <div class="number">{{ $statistics['submitted'] }}</div>
            </div>
            <div class="stat-item">
                <h3>Approved</h3>
                <div class="number">{{ $statistics['approved'] }}</div>
            </div>
            <div class="stat-item">
                <h3>Rejected</h3>
                <div class="number">{{ $statistics['rejected'] }}</div>
            </div>
            <div class="stat-item">
                <h3>Completed</h3>
                <div class="number">{{ $statistics['completed'] }}</div>
            </div>
        </div>

        <table class="no-break">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Kode Pengadaan</th>
                    <th width="10%">Tgl Pengajuan</th>
                    <th width="15%">Pemohon</th>
                    <th width="12%">Departemen</th>
                    <th width="15%">Total Estimasi</th>
                    <th width="10%">Status</th>
                    <th width="10%">Tgl Approval</th>
                    <th width="11%">Approved By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengadaans as $index => $pengadaan)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $pengadaan->kode_pengadaan }}</td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($pengadaan->tanggal_pengajuan)->format('d/m/Y') }}</td>
                        <td>{{ $pengadaan->nama_pemohon }}</td>
                        <td>{{ $pengadaan->departemen }}</td>
                        <td class="text-right">Rp {{ number_format($pengadaan->total_estimasi, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="status-badge status-{{ $pengadaan->status }}">
                                {{ ucfirst($pengadaan->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if ($pengadaan->tanggal_approval)
                                {{ \Carbon\Carbon::parse($pengadaan->tanggal_approval)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($pengadaan->approvedBy)
                                {{ $pengadaan->approvedBy->name }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data pengadaan ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="5" class="text-right">TOTAL ESTIMASI KESELURUHAN:</td>
                    <td class="text-right">Rp {{ number_format($statistics['total_estimasi'], 0, ',', '.') }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <div class="signature">
                <div class="signature-box">
                    <div>Disiapkan oleh,</div>
                    <div class="signature-line">
                        <strong>{{ Auth::user()->name }}</strong><br>
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </div>
                </div>
                <div class="signature-box">
                    <div>Mengetahui,</div>
                    <div class="signature-line">
                        <strong>Manager</strong><br>
                        _________________
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Check if this is accessed directly for print or via PDF export
            const urlParams = new URLSearchParams(window.location.search);
            const format = urlParams.get('format');

            // Only auto-print if format is not 'pdf' (i.e., direct print access)
            if (format !== 'pdf') {
                window.onload = function() {
                    window.print();

                    // Close window after printing (optional)
                    window.onafterprint = function() {
                        window.close();
                    };
                };
            }
        </script>
    </body>

</html>
