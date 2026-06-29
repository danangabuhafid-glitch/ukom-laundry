<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Laundry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .filter-info {
            margin-bottom: 20px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row th {
            background-color: #e9ecef;
            font-size: 16px;
        }
        ul {
            margin: 0;
            padding-left: 20px;
        }
        .signature {
            margin-top: 50px;
            float: right;
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 80px;
            border-top: 1px solid #333;
        }
        @media print {
            body { padding: 0; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>JEEVES LAUNDRY</h2>
        <p>Jl. Bersih Indah No. 123 | Telp: 0812-3456-7890</p>
        <h3 style="margin-top: 15px; margin-bottom: 0;">LAPORAN TRANSAKSI</h3>
    </div>

    <div class="filter-info">
        Periode: 
        @if($startDate && $endDate)
            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        @else
            Semua Waktu (All Time)
        @endif
        <br>
        Tanggal Cetak: {{ now()->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode TRX</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Pelanggan</th>
                <th width="30%">Layanan (Qty)</th>
                <th width="15%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                <td>{{ $order->customer->customer_name ?? '-' }}</td>
                <td>
                    <ul>
                    @foreach($order->transOrderDetails as $detail)
                        <li>{{ $detail->typeOfService->service_name }} ({{ $detail->qty }} kg)</li>
                    @endforeach
                    </ul>
                </td>
                <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="5" class="text-right">TOTAL PENDAPATAN</th>
                <th class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        Mengetahui,<br>
        <strong>Pimpinan</strong>
        <div class="signature-line">
            ( ..................................... )
        </div>
    </div>

</body>
</html>
