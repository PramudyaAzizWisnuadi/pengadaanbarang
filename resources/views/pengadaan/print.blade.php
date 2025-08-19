<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Form Pengadaan Barang - {{ $pengadaan->kode_pengadaan }}</title>
        <style>
            @page {
                size: A4;
                margin: 1.5cm;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 10px;
                line-height: 1.3;
                color: #000;
                margin: 0;
                padding: 0;
            }

            .header {
                text-align: center;
                margin-bottom: 15px;
                padding-bottom: 10px;
                padding: 10px;
                border-radius: 0;
            }

            .header h1 {
                font-size: 16px;
                margin: 0;
                text-transform: uppercase;
                font-weight: bold;
                color: #000;
                letter-spacing: 1px;
            }

            .header h2 {
                font-size: 12px;
                margin: 4px 0;
                font-weight: 500;
                color: #000;
            }

            .header .kode {
                background: transparent;
                color: #000;
                padding: 6px 12px;
                border-radius: 0;
                font-weight: bold;
                font-size: 11px;
                display: inline-block;
                margin-top: 8px;
                border: 1px solid #000;
            }

            .info-section {
                background: #ffffff;
                border: 1px solid #000;
                border-radius: 0;
                padding: 12px;
                margin-bottom: 15px;
            }

            .info-table {
                width: 100%;
                margin-bottom: 0;
            }

            .info-table td {
                padding: 4px 8px;
                vertical-align: top;
                border-bottom: 1px solid #dee2e6;
                font-size: 9px;
            }

            .info-table .label {
                width: 120px;
                font-weight: 700;
                color: #000;
                text-transform: uppercase;
                font-size: 9px;
            }

            .info-table .value {
                color: #212529;
                font-weight: 500;
            }

            .section-title {
                font-size: 12px;
                font-weight: bold;
                color: #000;
                margin: 15px 0 10px 0;
                padding-bottom: 5px;
                border-bottom: 1px solid #000;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                box-shadow: none;
                border-radius: 0;
                overflow: hidden;
                font-size: 12px;
            }

            .items-table th,
            .items-table td {
                border: 1px solid #000;
                padding: 12px 8px;
                text-align: left;
                vertical-align: top;
            }

            .items-table th {
                background: #ffffff;
                color: #000;
                font-weight: 600;
                text-align: center;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border: 1px solid #000;
            }

            .items-table tbody tr:nth-child(even) {
                background-color: #ffffff;
            }

            .items-table tbody tr:hover {
                background-color: #ffffff;
            }

            .items-table .number {
                text-align: center;
                width: 40px;
                font-weight: 600;
            }

            .items-table .price {
                text-align: right;
                font-family: 'Courier New', monospace;
                font-weight: 500;
            }

            .total-row {
                background: #ffffff !important;
                color: #000 !important;
                font-weight: bold;
                border: 2px solid #000 !important;
            }

            .total-row td {
                border-color: #000 !important;
                padding: 15px 8px;
            }

            /* Styling untuk tabel barang */
            .barang-table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                font-size: 12px;
                border: none;
            }

            .barang-table th,
            .barang-table td {
                border: none;
                padding: 10px;
                text-align: left;
                vertical-align: top;
                border-bottom: 1px solid #dee2e6;
            }

            .barang-table th {
                background: #f8f9fa;
                color: #000;
                font-weight: bold;
                text-align: center;
                border-bottom: 2px solid #dee2e6;
                font-size: 13px;
            }

            .barang-table td {
                background: #ffffff;
            }

            .barang-table tr:nth-child(even) td {
                background: #f8f9fa;
            }

            .barang-table tr:hover td {
                background: #e9ecef;
            }

            .barang-table tr:last-child th,
            .barang-table tr:last-child td {
                border-bottom: none;
            }

            .barang-table td:nth-child(1) {
                /* No */
                text-align: center;
                width: 4%;
            }

            .barang-table td:nth-child(2) {
                /* Kategori */
                width: 12%;
            }

            .barang-table td:nth-child(3) {
                /* Nama Barang */
                width: 25%;
            }

            .barang-table td:nth-child(4) {
                /* Spesifikasi */
                width: 20%;
            }

            .barang-table td:nth-child(5) {
                /* Jumlah */
                text-align: center;
                width: 8%;
            }

            .barang-table td:nth-child(6) {
                /* Harga Estimasi */
                text-align: right;
                width: 12%;
            }

            .barang-table td:nth-child(7) {
                /* Total Harga */
                text-align: right;
                width: 12%;
                font-weight: bold;
            }

            .barang-table td:nth-child(8) {
                /* Prioritas */
                text-align: center;
                width: 7%;
            }

            .priority-1 {
                background: #ffffff;
                border: none;
            }

            .priority-2 {
                background: #ffffff;
                border: none;
            }

            .priority-3 {
                background: #ffffff;
                border: none;
            }

            .total-summary {
                background: #ffffff;
                color: #000;
                padding: 8px 12px;
                border-radius: 0;
                margin: 8px 0;
                box-shadow: none;
                border: 1px solid #000;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .total-summary-title {
                font-size: 10px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                flex-shrink: 0;
                color: #000;
            }

            .total-summary-amount {
                font-size: 12px;
                font-weight: bold;
                font-family: 'Courier New', monospace;
            }

            .alasan-section {
                background: #ffffff;
                border: 1px solid #000;
                border-radius: 0;
                padding: 12px;
                margin: 15px 0;
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 11px;
            }

            .alasan-section .title {
                font-weight: bold;
                color: #000;
                text-transform: uppercase;
                font-size: 10px;
                letter-spacing: 0.5px;
                flex-shrink: 0;
            }

            .approval-note {
                margin: 15px 0;
                padding: 10px;
                border: 1px solid #000;
                background: #ffffff;
                border-radius: 0;
                font-size: 9px;
            }

            .approval-note strong {
                color: #000;
            }

            .signature-section {
                margin-top: 20px;
                page-break-inside: avoid;
            }

            .signature-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 10px 0;
            }

            .signature-box {
                height: 80px;
                text-align: center;
                vertical-align: bottom;
                padding: 8px;
                position: relative;
                border-radius: 0;
                background: #ffffff;
            }

            .signature-title {
                text-align: center;
                font-weight: bold;
                margin-bottom: 8px;
                color: #000;
                font-size: 10px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .signature-line {
                border-bottom: 1px solid #000;
                margin-top: 40px;
                margin-bottom: 4px;
                width: 50%;
                margin-left: auto;
                margin-right: auto;
            }

            .signature-box strong {
                font-size: 9px;
                font-weight: 600;
            }

            .signature-box small {
                font-size: 8px;
            }

            .status-approved {
                color: #000;
                font-weight: bold;
                background: #ffffff;
                padding: 2px 8px;
                border: none;
                border-radius: 0;
            }

            .status-rejected {
                color: #000;
                font-weight: bold;
                background: #ffffff;
                padding: 2px 8px;
                border: none;
                border-radius: 0;
            }

            .status-submitted {
                color: #000;
                font-weight: bold;
                background: #ffffff;
                padding: 2px 8px;
                border: none;
                border-radius: 0;
            }

            .status-draft {
                color: #000;
                font-weight: bold;
                background: #ffffff;
                padding: 2px 8px;
                border: none;
                border-radius: 0;
            }

            .status-completed {
                color: #000;
                font-weight: bold;
                background: #ffffff;
                padding: 2px 8px;
                border: none;
                border-radius: 0;
            }

            .footer {
                margin-top: 15px;
                font-size: 8px;
                text-align: center;
                color: #000;
                border-top: 1px solid #000;
                padding-top: 8px;
            }

            .priority-badge {
                font-size: 10px;
                padding: 3px 8px;
                border: none;
                border-radius: 0;
                font-weight: bold;
                text-transform: uppercase;
                background: #ffffff;
                color: #000;
            }

            .priority-1 {
                background: #ffffff;
                color: #000;
            }

            .priority-2 {
                background: #ffffff;
                color: #000;
            }

            .priority-3 {
                background: #ffffff;
                color: #000;
            }

            @media print {
                .no-print {
                    display: none;
                }

                body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                    font-size: 10px;
                    line-height: 1.2;
                    color: #000;
                }

                .barang-table {
                    font-size: 11px;
                    page-break-inside: avoid;
                    border: none;
                }

                .barang-table th,
                .barang-table td {
                    padding: 6px;
                    border: none;
                    border-bottom: 1px solid #ccc;
                }

                .barang-table th {
                    border-bottom: 2px solid #999;
                }

                .barang-table tr:nth-child(even) td {
                    background: #f0f0f0 !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .items-table {
                    font-size: 11px;
                }

                .header {
                    margin-bottom: 10px;
                    padding: 8px;
                }

                .info-section {
                    margin-bottom: 10px;
                    padding: 8px;
                }

                .signature-box {
                    height: 60px;
                    padding: 6px;
                }

                .signature-line {
                    margin-top: 30px;
                }

                .total-summary {
                    margin: 10px 0;
                    padding: 8px;
                }

                .section-title {
                    margin: 10px 0 6px 0;
                    font-size: 11px;
                }

                .alasan-section {
                    margin: 10px 0;
                    padding: 8px;
                }

                .approval-note {
                    margin: 10px 0;
                    padding: 8px;
                }
            }

            .print-button {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
            }
        </style>
    </head>

    <body>
        <!-- Print Button -->
        <div class="print-button no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Cetak Dokumen
            </button>
            <a href="{{ route('pengadaan.show', $pengadaan) }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="header">
            <h1>Form Pengadaan Barang</h1>
            <h2>MD Mall</h2>
        </div>

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="label">Kode Pengadaan</td>
                    <td class="value">: {{ $pengadaan->kode_pengadaan }}</td>
                    <td class="label">Tanggal Pengajuan</td>
                    <td class="value">: {{ $pengadaan->tanggal_pengajuan->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Pemohon</td>
                    <td class="value">: {{ $pengadaan->nama_pemohon }}</td>
                    <td class="label">Tanggal Dibutuhkan</td>
                    <td class="value">: {{ $pengadaan->tanggal_dibutuhkan->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td class="value">: {{ $pengadaan->jabatan }}</td>
                    <td class="label">Status</td>
                    <td class="value">: <span
                            class="status-{{ $pengadaan->status }}">{{ strtoupper($pengadaan->status) }}</span></td>
                </tr>
                <tr>
                    <td class="label">Departemen</td>
                    <td class="value">: {{ $pengadaan->departemen }}</td>
                    <td class="label"></td>
                    <td class="value"></td>
                </tr>
            </table>
        </div>

        <div class="alasan-section">
            <span class="title">Keterangan:</span>
            <span>{{ $pengadaan->keterangan }}</span>
        </div>

        <h3 class="section-title">Daftar Barang yang Diminta</h3>

        <table class="barang-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Barang</th>
                    <th>Spesifikasi</th>
                    <th>Jumlah</th>
                    <th>Harga Est.</th>
                    <th>Total</th>
                    <th>Prioritas</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                @foreach ($pengadaan->barangPengadaan as $index => $barang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barang->kategoriBarang->nama_kategori ?? '-' }}</td>
                        <td>
                            <strong>{{ $barang->nama_barang }}</strong>
                            {{-- @if ($barang->merk)
                                <br><small>Merk: {{ $barang->merk }}</small>
                            @endif --}}
                        </td>
                        <td>{{ $barang->spesifikasi ?? '-' }}</td>
                        <td>{{ $barang->jumlah }} {{ $barang->satuan }}</td>
                        <td>Rp {{ number_format($barang->harga_estimasi, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($barang->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="priority-badge priority-{{ $barang->prioritas }}">
                                @if ($barang->prioritas == 1)
                                    Tinggi
                                @elseif($barang->prioritas == 2)
                                    Sedang
                                @elseif($barang->prioritas == 3)
                                    Rendah
                                @else
                                    P{{ $barang->prioritas }}
                                @endif
                            </span>
                        </td>
                    </tr>
                    @if ($barang->keterangan)
                        <tr>
                            <td></td>
                            <td colspan="7" style="font-size: 10px; color: #666;">
                                <strong>Keterangan:</strong> {{ $barang->keterangan }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
            </tbody>
        </table>

        <div class="total-summary">
            <span class="total-summary-title">TOTAL :</span>
            <span class="total-summary-amount">Rp {{ number_format($pengadaan->total_estimasi, 0, ',', '.') }}</span>
        </div>

        @if ($pengadaan->catatan_approval)
            <div class="approval-note">
                <strong>Catatan Approval:</strong><br>
                {{ $pengadaan->catatan_approval }}
                @if ($pengadaan->approvedBy)
                    <br><small><em>Oleh: {{ $pengadaan->approvedBy->name }} -
                            {{ $pengadaan->tanggal_approval->format('d/m/Y H:i') }}</em></small>
                @endif
            </div>
        @endif

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td width="33%">
                        <div class="signature-title">Pemohon</div>
                        <div class="signature-box">
                            <div class="signature-line"></div>
                            <strong>{{ $pengadaan->nama_pemohon }}</strong><br>
                            <small>Tanggal: {{ $pengadaan->tanggal_pengajuan->format('d/m/Y') }}</small>
                        </div>
                    </td>
                    <td width="33%">
                        <div class="signature-title">Manager Operasional</div>
                        <div class="signature-box">
                            <div class="signature-line"></div>
                            <strong>(...........................)</strong><br>
                            <small>Tanggal: ..................</small>
                        </div>
                    </td>
                    <td width="33%">
                        <div class="signature-title">Direktur</div>
                        <div class="signature-box">
                            <div class="signature-line"></div>
                            <strong>(...........................)</strong><br>
                            <small>Tanggal: ..................</small>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <!-- Bootstrap CSS for button styling -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

        <script>
            // Auto focus for print
            window.addEventListener('load', function() {
                setTimeout(function() {
                    if (window.location.search.includes('auto-print=1')) {
                        window.print();
                    }
                }, 500);
            });

            // Print function
            function printDocument() {
                window.print();
            }

            // Back function
            function goBack() {
                window.history.back();
            }
        </script>
    </body>

</html>
