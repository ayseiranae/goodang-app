<!DOCTYPE html>
<html>

<head>
    <title>Laporan Stok Bulanan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 15px;
            border-bottom: 1px solid #000;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 18pt;
            font-weight: bold;
        }

        .header h2 {
            margin: 0 0 15px 0;
            font-size: 16pt;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0;
            font-size: 12pt;
            font-weight: normal;
        }

        .periode {
            margin: 15px 0 20px 0;
            font-size: 12pt;
            font-weight: bold;
        }

        h2 {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11pt;
        }

        th,
        td {
            border: 0.5px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            font-size: 10pt;
        }

        td.center {
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11pt;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <h1>Laporan Stok Bulanan</h1>
        <h2>{{ $profil->nama_perusahaan ?? 'PT Indo Abadi Merdeka' }}</h2>
        <p>Alamat: {{ $profil->alamat ?? 'Jl. Jalan No. 123, Malang' }}</p>
        <p>Nomor Telepon: {{ $profil->telepon ?? '081234567890' }}</p>
        <p>Email: {{ $profil->email ?? 'info@abadimerdeka.com' }}, website:
            {{ $profil->website ?? 'www.abadi-merdeka.com' }}</p>
        <p class="periode">Periode: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
        </p>
    </div>

    <!-- 1. Mutasi Stok -->
    <h2>1. Mutasi Stok</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kode Barang</th>
                <th>Jumlah Awal</th>
                <th>Total Masuk</th>
                <th>Total Keluar</th>
                <th>Perubahan Bersih</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rangkuman as $item)
                @php
                    $perubahan = $item->total_masuk - $item->total_keluar;
                    $jumlah_awal = $item->stok_saat_ini - $perubahan;
                @endphp
                <tr>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="center">{{ $item->id_barang }}</td>
                    <td class="center">{{ $jumlah_awal }}</td>
                    <td class="center">{{ $item->total_masuk }}</td>
                    <td class="center">{{ $item->total_keluar }}</td>
                    <td class="center">{{ $perubahan > 0 ? '+' : '' }}{{ $perubahan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; font-style: italic;">
                        Contoh
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- 2. Detail Transaksi -->
    <h2>2. Detail Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Transaksi</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Pegawai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($detail_transaksi as $detail)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($detail->tanggal)->translatedFormat('d/m/Y H:i') }}</td>
                    <td>{{ $detail->nama_barang }}</td>
                    <td class="center">{{ strtoupper($detail->transaksi) }}</td>
                    <td class="center">{{ $detail->jumlah }}</td>
                    <td>{{ $detail->keterangan }}</td>
                    <td>{{ $detail->nama_pegawai }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; font-style: italic;">
                        Contoh
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- 3. Stok Saat Ini -->
    <h2>3. Stok Saat Ini</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Total Stok Tersisa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stok_saat_ini as $stok)
                @if ($stok->stok_saat_ini > 0)
                    <tr>
                        <td>{{ $stok->nama_barang }}</td>
                        <td class="center">{{ $stok->stok_saat_ini }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="2" style="text-align: center; font-style: italic;">
                        Contoh
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Dicetak oleh: {{ $cetakOleh ?? 'pegawai' }}<br>
        Tanggal/Waktu Cetak:
        {{ isset($cetakWaktu) ? $cetakWaktu->format('d/m/Y H:i:s') : now()->format('d/m/Y H:i:s') }}
    </div>

</body>

</html>