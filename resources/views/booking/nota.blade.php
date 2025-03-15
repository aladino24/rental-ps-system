<!DOCTYPE html>
<html>
<head>
    <title>Nota Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; padding: 20px; }
        .header { text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table, .table th, .table td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nota Pembayaran</h2>
        </div>
        <p>ID Transaksi: {{ 'PSN-' . $booking->id . '-' . \Carbon\Carbon::parse($booking->date)->format('Ymd') . '-' . '0' . Auth::id() }}</p>
        <p>Nama: {{ $booking->user->name }}</p>
        <p>Total: Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
        <p>Status: {{ ucfirst($booking->status) }}</p>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Jumlah Sesi</th>
                    <th>Harga</th>
                    <th>Tambahan</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td>{{ $booking->service }}</td>
                        <td>{{ $booking->session_duration }}</td>
                        <td>Rp {{ number_format($booking->base_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($booking->surcharge, 0, ',', '.') }}</td>
                    </tr>
            </tbody>
        </table>

        <h3>Total Bayar: Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h3>
    </div>
</body>
</html>
