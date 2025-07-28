<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            line-height: 1.6;
            font-size: 12px;
            color: #333;
            margin: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #666;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 2px 0;
        }
        .order-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .order-details th, .order-details td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            text-align: left;
        }
        .order-details th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 13px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>INVOICE</h2>
        <p>#{{ $order->id }}</p>
    </div>

    <div class="info">
        <p><strong>Nama Pelanggan:</strong> {{ $order->user->name }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
        <p><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y') }}</p>
        <p><strong>Status:</strong> Pembayaran Berhasil</p>
    </div>

    <h4>Rincian Pesanan:</h4>
    <table class="order-details">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Warna</th>
                <th>Ukuran</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->product->name }}</td>
                <td>{{ $order->warna ?? '-' }}</td>
                <td>{{ $order->ukuran ?? '-' }}</td>
                <td>{{ $order->jumlah }}</td>
                <td>
                    Rp {{ number_format(($order->jumlah > 0) ? $order->total_harga / $order->jumlah : 0, 0, ',', '.') }}
                </td>
                <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p class="total">Total: Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>

    <div class="footer">
        Terima kasih telah berbelanja di Dinara Konveksi.
    </div>

</body>
</html>
