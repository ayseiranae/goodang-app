<!DOCTYPE html>
<html>

<head>
    <title>Laporan Bulanan</title>
    <!-- Ini styling standar biar PDF-nya rapi -->
    <style>
        body {
            font-family: sans-serif;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        thead th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h1>Laporan Rangkuman Stok</h1>
    <h3>Bulan: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</h3>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Total Masuk</th>
                <th>Total Keluar</th>
                <th>Perubahan Bersih</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $item)
                <tr>
                    <td>{{ $item->nama_barang }}</td>
                    <td>+{{ $item->total_masuk }}</td>
                    <td>-{{ $item->total_keluar }}</td>
                    <td>
                        @php $perubahan = $item->total_masuk - $item->total_keluar; @endphp
                        {{ $perubahan > 0 ? '+' : '' }}{{ $perubahan }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">
                        Tidak ada pergerakan barang di bulan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>