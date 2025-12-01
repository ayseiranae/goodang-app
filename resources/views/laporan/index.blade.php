<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Rangkuman Bulanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="GET" action="{{ route('laporan.index') }}" class="mb-6">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label for="bulan" class="block font-medium text-sm text-gray-700">Bulan:</label>
                                <select name="bulan" id="bulan"
                                    class="block mt-1 rounded-md shadow-sm border-gray-300">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="tahun" class="block font-medium text-sm text-gray-700">Tahun:</label>
                                <select name="tahun" id="tahun"
                                    class="block mt-1 rounded-md shadow-sm border-gray-300">
                                    @foreach ($daftarTahun as $th)
                                        <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>
                                            {{ $th }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pt-6">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                    Tampilkan Laporan
                                </button>
                            </div>

                            <div class="pt-6">
                                <a href="{{ route('laporan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                    Download PDF
                                </a>
                            </div>

                        </div>
                    </form>
                    <h3 class="text-lg font-semibold mb-2">
                        Laporan untuk: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
                        {{ $tahun }}
                    </h3>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Barang</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-green-600">
                                    Total Masuk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-red-600">
                                    Total Keluar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Perubahan Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($laporan as $item)
                                <tr class="cursor-pointer hover:bg-gray-50 detail-row"
                                    data-url="{{ route('laporan.detail', [
                                        'id_barang' => $item->id_barang,
                                        'bulan' => $bulan,
                                        'tahun' => $tahun,
                                    ]) }}"
                                    data-nama="{{ $item->nama_barang }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-600 font-medium">
                                        +{{ $item->total_masuk }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-red-600 font-medium">
                                        -{{ $item->total_keluar }}</td>

                                    @php $perubahan = $item->total_masuk - $item->total_keluar; @endphp
                                    <td
                                        class="px-6 py-4 whitespace-nowrap font-bold {{ $perubahan >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $perubahan > 0 ? '+' : '' }}{{ $perubahan }}
                                    </td>
                                </tr>
                            @empty


                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada pergerakan barang di bulan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        <!-- MODAL DETAIL TRANSAKSI PER BARANG -->
        <div id="modalDetailLaporan"
            class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-md w-full max-w-3xl">
                <h3 id="modal-detail-title" class="text-lg font-semibold mb-4">
                    Detail Transaksi
                </h3>

                <div class="overflow-x-auto max-h-96">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Transaksi
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keterangan
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pegawai</th>
                            </tr>
                        </thead>
                        <tbody id="modal-detail-body" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    Memuat data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeDetailLaporan()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openDetailLaporan(url, namaBarang) {
            const modal = document.getElementById('modalDetailLaporan');
            const title = document.getElementById('modal-detail-title');
            const tbody = document.getElementById('modal-detail-body');

            title.textContent = 'Detail Transaksi: ' + namaBarang;
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                        Memuat data...
                    </td>
                </tr>
            `;

            modal.classList.remove('hidden');

            fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.length) {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                Tidak ada transaksi pada periode ini.
                            </td>
                        </tr>
                    `;
                        return;
                    }

                    tbody.innerHTML = '';
                    data.forEach(row => {
                        const tr = document.createElement('tr');

                        const tgl = row.tanggal ? new Date(row.tanggal) : null;
                        const tglText = tgl ? tgl.toLocaleString('id-ID') : '-';

                        tr.innerHTML = `
                        <td class="px-4 py-2 whitespace-nowrap">${tglText}</td>
                        <td class="px-4 py-2 whitespace-nowrap">${row.transaksi.toUpperCase()}</td>
                        <td class="px-4 py-2 whitespace-nowrap font-semibold">${row.jumlah}</td>
                        <td class="px-4 py-2">${row.keterangan ?? ''}</td>
                        <td class="px-4 py-2 whitespace-nowrap">${row.nama_pegawai ?? '-'}</td>
                    `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => {
                    console.error(err);
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-red-500">
                            Gagal memuat data.
                        </td>
                    </tr>
                `;
                });
        }

        function closeDetailLaporan() {
            document.getElementById('modalDetailLaporan').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.detail-row').forEach(row => {
                row.addEventListener('click', () => {
                    const url = row.dataset.url;
                    const nama = row.dataset.nama;
                    openDetailLaporan(url, nama);
                });
            });
        });
    </script>

</x-app-layout>
