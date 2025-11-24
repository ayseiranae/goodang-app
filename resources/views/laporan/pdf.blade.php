<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan GOODANG</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .info-box {
            background: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 5px;
        }

        .statistics {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .stat-card {
            display: table-cell;
            width: 33%;
            padding: 15px;
            text-align: center;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .stat-card p {
            margin: 10px 0 0;
            font-size: 24px;
            font-weight: bold;
        }

        .stat-card.green p {
            color: #16a34a;
        }

        .stat-card.orange p {
            color: #ea580c;
        }

        .stat-card.blue p {
            color: #2563eb;
        }

        table.transactions {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.transactions th {
            background: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }

        table.transactions td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }

        table.transactions tr:nth-child(even) {
            background: #f9fafb;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge.in {
            background: #dcfce7;
            color: #16a34a;
        }

        .badge.out {
            background: #fed7aa;
            color: #ea580c;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h1>GOODANG</h1>
        <p>Laporan Stok Gudang</p>
        <p><strong>Periode:</strong> {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <table>
            <tr>
                <td><strong>Tanggal Cetak:</strong></td>
                <td>{{ $generatedAt }}</td>
            </tr>
            <tr>
                <td><strong>Dicetak Oleh:</strong></td>
                <td>{{ auth()->user()->name }}</td>
            </tr>
            <tr>
                <td><strong>Total Transaksi:</strong></td>
                <td>{{ count($transactions) }} transaksi</td>
            </tr>
        </table>
    </div>

    <!-- Statistics -->
    <div class="statistics">
        <div class="stat-card green">
            <h3>Total Stok Masuk</h3>
            <p>{{ $totalStockIn }}</p>
        </div>

        <div class="stat-card orange">
            <h3>Total Stok Keluar</h3>
            <p>{{ $totalStockOut }}</p>
        </div>

        <div class="stat-card blue">
            <h3>Total Barang Aktif</h3>
            <p>{{ $totalItems }}</p>
        </div>
    </div>

    <!-- Table -->
    <table class="transactions">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $t->item->nama_barang }}</td>
                    <td>
                        @if ($t->type == 'in')
                            <span class="badge in">Masuk</span>
                        @else
                            <span class="badge out">Keluar</span>
                        @endif
                    </td>
                    <td>{{ $t->qty }}</td>
                    <td>{{ $t->created_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        GOODANG Inventory System — Generated on {{ $generatedAt }}
    </div>

</body>

</html>
