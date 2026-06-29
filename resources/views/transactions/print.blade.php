<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .receipt-container {
            max-width: 350px;
            margin: 0 auto;
            border: 1px dashed #ccc;
            padding: 15px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        .mt-4 { margin-top: 20px; }
        .w-100 { width: 100%; }
        hr { border-top: 1px dashed #000; border-bottom: none; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 4px 0; }
        @media print {
            body { padding: 0; }
            .receipt-container { border: none; max-width: 100%; width: 100%; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="receipt-container">
        <div class="text-center mb-4">
            <h2 class="mb-1 mt-0">JEEVES LAUNDRY</h2>
            <div>Jl. Bersih Indah No. 123</div>
            <div>Telp: 0812-3456-7890</div>
        </div>

        <hr>
        
        <table class="mb-2">
            <tr>
                <td><strong>Order Code</strong></td>
                <td class="text-right">{{ $order->order_code }}</td>
            </tr>
            <tr>
                <td><strong>Date</strong></td>
                <td class="text-right">{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Estimasi Selesai</strong></td>
                <td class="text-right">{{ \Carbon\Carbon::parse($order->order_date)->addDays(2)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td><strong>Customer</strong></td>
                <td class="text-right">{{ $order->customer->customer_name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td class="text-right">
                    {{ $order->order_status == 0 ? 'Baru' : 'Sudah Diambil' }}
                    @if($order->order_status == 1 && $order->transLaundryPickup && $order->transLaundryPickup->notes)
                        <br><small style="font-style: italic;">({{ $order->transLaundryPickup->notes }})</small>
                    @endif
                </td>
            </tr>
        </table>

        <hr>

        <table class="mb-2">
            <thead>
                <tr>
                    <th style="text-align: left;">Service</th>
                    <th style="text-align: center;">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->transOrderDetails as $detail)
                <tr>
                    <td>{{ $detail->typeOfService->service_name ?? '-' }}</td>
                    <td class="text-center">{{ $detail->qty }} kg</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <table>
            <tr>
                <td class="fw-bold fs-5">TOTAL</td>
                <td class="text-right fw-bold fs-5">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            @if($order->order_pay > 0)
            <tr>
                <td>Bayar</td>
                <td class="text-right">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-right">Rp {{ number_format($order->order_change, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>

        <hr class="mt-4">
        
        <div class="text-center mt-4">
            <div>Terima kasih telah menggunakan</div>
            <div class="fw-bold">Jeeves Laundry</div>
            <div>Layanan Laundry Terbaik</div>
        </div>
    </div>

</body>
</html>
