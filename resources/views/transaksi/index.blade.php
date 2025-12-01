<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Transaksi Stok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- TOMBOL BUKA MODAL --}}
                    <button id="btnAddTransaksi"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                        Catat Transaksi Baru
                    </button>

                    {{-- Filter & Search --}}
                    <div class="mb-4 flex flex-wrap items-end gap-4">

                        <div class="flex items-end gap-2">
                            <div>
                                <label for="tanggal" class="block font-medium text-sm text-gray-700">Tanggal:</label>
                                <input type="date" name="tanggal" id="tanggal"
                                    class="block mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value="{{ $tanggal ?? '' }}">
                            </div>

                            <button type="button" id="btnFilterTanggal"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 h-10">
                                Filter
                            </button>
                        </div>

                        <div class="flex-grow relative">
                            <label for="search" class="block font-medium text-sm text-gray-700">Cari Barang:</label>
                            <input type="text" id="search" placeholder="Ketik nama barang..."
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ $search ?? '' }}">

                            <div id="loading-indicator" class="absolute right-3 top-9 hidden">
                                <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <a href="{{ route('transaksi.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 h-10">
                            Reset
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Barang</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pegawai</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keterangan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pemasok</th>
                                </tr>
                            </thead>
                            <tbody id="table-body" class="bg-white divide-y divide-gray-200">
                                @include('transaksi.table_body')
                            </tbody>
                        </table>
                    </div>

                    {{-- MODAL TRANSAKSI BARU --}}
                    <div id="modalTransaksi"
                        class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded shadow-md w-full max-w-xl">
                            <h2 class="text-lg font-bold mb-4">Catat Transaksi Stok Baru</h2>

                            <form id="form-transaksi">
                                @csrf

                                <div class="mt-2">
                                    <label for="transaksi" class="block font-medium text-sm text-gray-700">Tipe
                                        Transaksi</label>
                                    <select name="transaksi" id="transaksi"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">None</option>
                                        <option value="masuk">Barang Masuk</option>
                                        <option value="keluar">Barang Keluar</option>
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <label for="id_barang"
                                        class="block font-medium text-sm text-gray-700">Barang</label>
                                    <select name="id_barang" id="id_barang"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">None</option>
                                        @foreach ($barang as $b)
                                            @php
                                                $stok = ($b->masuk_sum_jumlah ?? 0) - ($b->keluar_sum_jumlah ?? 0);
                                            @endphp
                                            <option value="{{ $b->id_barang }}">
                                                {{ $b->barang }} (Stok: {{ $stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <label for="jumlah" class="block font-medium text-sm text-gray-700">Jumlah</label>
                                    <input type="number" name="jumlah" id="jumlah"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div class="mt-4">
                                    <label for="keterangan" class="block font-medium text-sm text-gray-700">Keterangan
                                        (Wajib diisi)</label>
                                    <textarea name="keterangan" id="keterangan"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>

                                <div id="pemasok_div" class="mt-4" style="display:none;">
                                    <label for="id_pemasok" class="block font-medium text-sm text-gray-700">Pemasok
                                        (Wajib untuk Barang Masuk)</label>
                                    <select name="id_pemasok" id="id_pemasok"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">None</option>
                                        @foreach ($pemasok as $pem)
                                            <option value="{{ $pem->id_pemasok }}">{{ $pem->pemasok }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-center justify-end mt-6 space-x-2">
                                    <button type="button" id="btnCancelTransaksi"
                                        class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gray-800 text-white rounded-md font-semibold text-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                        Simpan Transaksi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('table-body');
            const loadingIndicator = document.getElementById('loading-indicator');
            const tanggalInput = document.getElementById('tanggal');
            const btnFilterTanggal = document.getElementById('btnFilterTanggal');

            const btnAddTransaksi = document.getElementById('btnAddTransaksi');
            const modalTransaksi = document.getElementById('modalTransaksi');
            const formTransaksi = document.getElementById('form-transaksi');
            const btnCancel = document.getElementById('btnCancelTransaksi');
            const selectTransaksi = document.getElementById('transaksi');
            const pemasokDiv = document.getElementById('pemasok_div');

            let timeout = null;

            function togglePemasok(tipe) {
                if (tipe === 'masuk') {
                    pemasokDiv.style.display = 'block';
                } else {
                    pemasokDiv.style.display = 'none';
                }
            }

            function closeModalTransaksi() {
                modalTransaksi.classList.add('hidden');
                formTransaksi.reset();
                pemasokDiv.style.display = 'none';
            }

            // === AJAX search/filter log ===
            function performSearch() {
                loadingIndicator.classList.remove('hidden');

                const query = searchInput.value;
                const tanggal = tanggalInput.value;

                const url = "{{ route('transaksi.index') }}?search=" + encodeURIComponent(query) +
                    "&tanggal=" + encodeURIComponent(tanggal);

                fetch(url, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "text/html"
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.text();
                    })
                    .then(html => {
                        tableBody.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error AJAX:', error);
                        tableBody.innerHTML =
                            '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Gagal memuat data. Silakan coba lagi.</td></tr>';
                    })
                    .finally(() => {
                        loadingIndicator.classList.add('hidden');
                    });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(performSearch, 300);
            });

            tanggalInput.addEventListener('change', performSearch);
            btnFilterTanggal.addEventListener('click', performSearch);

            // === Modal Transaksi Baru ===
            btnAddTransaksi.addEventListener('click', () => {
                modalTransaksi.classList.remove('hidden');
            });

            btnCancel.addEventListener('click', () => {
                closeModalTransaksi();
            });

            selectTransaksi.addEventListener('change', function() {
                togglePemasok(this.value);
            });

            formTransaksi.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(formTransaksi);

                loadingIndicator.classList.remove('hidden');

                fetch("{{ route('transaksi.store.ajax') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content,
                            "Accept": "application/json"
                        },
                        body: formData
                    })
                    .then(async response => {
                        loadingIndicator.classList.add('hidden');

                        if (response.ok) {
                            const res = await response.json();

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: res.message,
                                showConfirmButton: false,
                                timer: 2000
                            });

                            closeModalTransaksi();
                            performSearch(); // reload tabel dengan filter yang sekarang
                        } else if (response.status === 422) {
                            const res = await response.json();
                            let message = '';

                            if (res.errors) {
                                Object.values(res.errors).forEach(arr => {
                                    message += arr[0] + '<br>';
                                });
                            } else {
                                message = 'Validasi gagal.';
                            }

                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Gagal!',
                                html: message,
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Terjadi kesalahan server',
                                showConfirmButton: true
                            });
                        }
                    })
                    .catch(error => {
                        loadingIndicator.classList.add('hidden');
                        console.error(error);
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Gagal menyimpan transaksi!',
                            showConfirmButton: true
                        });
                    });
            });
        });
    </script>
</x-app-layout>
