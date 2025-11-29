<!DOCTYPE html>
<html>

<head>
    <title>Laporan Bulanan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }

        h1 {
            text-align: center;
            font-size: 14pt;
        }

        h2 {
            font-size: 12pt;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        h3 {
            text-align: center;
            font-size: 12pt;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-left: auto;
            margin-right: auto;
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

        .footer-info {
            margin-top: 50px;
            font-size: 9pt;
            text-align: right;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div style="text-align: center; margin-bottom: 25px;">
        <h1 style="margin: 0; font-size: 20pt; font-weight: 700; letter-spacing: 1px;">
            GOODANG
        </h1>

        <div style="font-size: 11pt; margin-top: 3px; font-weight: 500;">
            Jalan Awan, Malang
        </div>

        <div style="font-size: 10pt; margin-top: 2px;">
            Telp: +62 82233445566 &nbsp; | &nbsp; Email: goodang@gmail.com
        </div>

        <div style="border-bottom: 3px solid #000; width: 95%; margin: 12px auto 0 auto;"></div>
    </div>



    <h2 style="text-align: center; font-size: 14pt; margin-bottom: 20px;">LAPORAN STOK BULANAN</h2>

    <div style="margin-bottom: 20px; font-size: 10pt;">
        <strong>Periode:</strong> {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
        {{ $tahun }}
    </div>

    <h2>1. Mutasi Stok</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Nama Barang</th>
                <th style="width: 20%;">Total Masuk</th>
                <th style="width: 20%;">Total Keluar</th>
                <th style="width: 20%;">Perubahan Bersih</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rangkuman as $item)
                <tr>
                    <td>{{ $item->nama_barang }}</td>
                    <td style="color: green;">+{{ $item->total_masuk }}</td>
                    <td style="color: red;">-{{ $item->total_keluar }}</td>
                    <td>
                        @php $perubahan = $item->total_masuk - $item->total_keluar; @endphp
                        {{ $perubahan > 0 ? '+' : '' }}{{ $perubahan }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; font-style: italic;">
                        Tidak ada pergerakan barang di bulan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="page-break-after: always"></div>
    <!-- Bagian Detail Transaksi -->
    <h2>2. Detail Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Barang</th>
                <th style="width: 10%;">Tipe</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 30%;">Keterangan</th>
                <th style="width: 10%;">Pegawai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($detailTransaksi as $detail)
                <!-- ✅ gunakan camelCase -->
                <tr>
                    <td>{{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('d M Y H:i') }}</td>
                    <td>{{ $detail->nama_barang }}</td>
                    <td class="text-center">
                        <span style="color: {{ $detail->transaksi == 'masuk' ? 'green' : 'red' }}; font-weight: bold;">
                            {{ strtoupper($detail->transaksi) }}
                        </span>
                    </td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td>{{ $detail->keterangan }}</td>
                    <td>{{ $detail->nama_pegawai }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="font-style: italic;">
                        Tidak ada catatan transaksi detail di bulan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Bagian Stok Saat Ini -->
    <h2>3. Stok Saat Ini</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 70%;">Nama Barang</th>
                <th style="width: 30%;">Total Stok Tersisa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stokSaatIni as $stok)
                <!-- ✅ gunakan camelCase -->
                @if ($stok->stok_saat_ini > 0)
                    <tr>
                        <td>{{ $stok->nama_barang }}</td>
                        <td class="text-center">{{ $stok->stok_saat_ini }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="2" class="text-center" style="font-style: italic;">
                        Tidak ada data stok barang yang tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-info">
        Dicetak oleh: {{ $cetakOleh }}<br>
        Tanggal Cetak: {{ $cetakWaktu->translatedFormat('d F Y H:i:s') }}
    </div>

</body>

</html>
