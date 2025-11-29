@forelse ($transaksi as $t)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $t->created_at->format('d-m-Y H:i') }}</td>
        <td class="px-6 py-4 whitespace-nowrap">{{ $t->barang->barang ?? 'N/A' }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if ($t->transaksi == 'masuk')
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    MASUK
                </span>
            @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    KELUAR
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap font-bold">{{ $t->jumlah }}</td>
        <td class="px-6 py-4 whitespace-nowrap">{{ $t->pegawai->pegawai ?? 'N/A' }}</td>
        <td class="px-6 py-4">{{ $t->keterangan }}</td>
        <td class="px-6 py-4 whitespace-nowrap">{{ $t->pemasok->pemasok ?? '-' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
            Data tidak ditemukan.
        </td>
    </tr>
@endforelse