<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pelanggan - Hotel Management</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 20mm 15mm 20mm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }

        .report-container {
            max-width: 210mm;
            margin: 0 auto;
        }

        /* Header */
        .report-header {
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .company-details h1 {
            color: #2c5aa0;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .company-details p {
            font-size: 10px;
            color: #666;
            margin: 2px 0;
        }

        .report-info {
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        /* Title */
        .report-title {
            text-align: center;
            margin: 15px 0 20px 0;
        }

        .report-title h2 {
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2c5aa0;
            margin-bottom: 4px;
        }

        .report-subtitle {
            font-size: 11px;
            color: #777;
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 10px;
        }

        .data-table thead {
            background: #2c5aa0;
            color: #fff;
        }

        .data-table th, 
        .data-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        .data-table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .data-table tbody td:first-child {
            text-align: center;
            color: #2c5aa0;
            font-weight: 600;
            width: 60px;
        }

        /* Summary */
        .report-summary {
            margin-top: 25px;
            padding: 12px 15px;
            border-left: 4px solid #2c5aa0;
            background: #f9fbfd;
            font-size: 11px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .summary-row:last-child {
            font-weight: bold;
            color: #2c5aa0;
            border-top: 1px solid #ddd;
            padding-top: 6px;
        }

        /* Signature */
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .signature-box {
            width: 200px;
        }

        .signature-line {
            margin: 50px auto 10px auto;
            width: 150px;
            border-bottom: 1px solid #444;
        }

        .signature-title {
            font-size: 10px;
            color: #666;
        }

        /* Footer */
        .report-footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
            font-size: 9px;
            text-align: center;
            color: #888;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Header -->
        <header class="report-header">
            <div class="company-details">
                <h1>Manajemen  Mei Room</h1>
                <p>Jl. Sudirman No. 123, Palembang</p>
                <p>Telp: (021) 123-4567 | Email: info@MeiRoom.com</p>
            <div class="report-info">
                <p><strong>Tanggal Cetak:</strong> {{ date('d F Y') }}</p>
                <p><strong>Waktu:</strong> {{ date('H:i:s') }}</p>
            </div>
        </header>

        <!-- Title -->
        <div class="report-title">
            <h2>Laporan Data Pelanggan</h2>
            <p class="report-subtitle">Periode: {{ date('F Y') }}</p>
        </div>

        <!-- Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pelanggan</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tamu as $p)
                <tr>
                    <td>{{ str_pad($p->idpelanggan, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $p->namapelanggan }}</td>
                    <td>{{ $p->nohp }}</td>
                    <td>{{ $p->alamat }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="report-summary">
            <div class="summary-row">
                <span>Total Pelanggan:</span>
                <span>{{ count($tamu) }} orang</span>
            </div>
            <div class="summary-row">
                <span>Laporan Generated:</span>
                <span>{{ date('d/m/Y H:i:s') }}</span>
            </div>
            <div class="summary-row">
                <span>Status Laporan:</span>
                <span>VALID</span>
            </div>
        </div>

        <!-- Footer -->
        <footer class="report-footer">
            © 2024 Manajemen  Mei Room System
        </footer>
    </div>
</body>
</html>
