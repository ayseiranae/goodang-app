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

                    <a href="{{ route('transaksi.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                        Catat Transaksi Baru
                    </a>

                    <!-- Filter & Search Container -->
                    <div class="mb-4 flex flex-wrap items-end gap-4">

                        <!-- Filter Tanggal -->
                        <form method="GET" action="{{ route('transaksi.index') }}" class="flex items-end gap-2">
                            <div>
                                <label for="tanggal" class="block font-medium text-sm text-gray-700">Tanggal:</label>
                                <input type="date" name="tanggal" id="tanggal"
                                    class="block mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    value="{{ $tanggal ?? '' }}">
                            </div>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 h-10">
                                Filter
                            </button>
                        </form>

                        <!-- Live Search -->
                        <div class="flex-grow relative">
                            <label for="search" class="block font-medium text-sm text-gray-700">Cari Barang:</label>
                            <input type="text" id="search" placeholder="Ketik nama barang..."
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ $search ?? '' }}">

                            <!-- Loading Indicator -->
                            <div id="loading-indicator" class="absolute right-3 top-9 hidden">
                                <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
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
                            <!-- ID table-body  -->
                            <tbody id="table-body" class="bg-white divide-y divide-gray-200">
                                @include('transaksi.table_body')
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const tableBody = document.getElementById('table-body');
            const loadingIndicator = document.getElementById('loading-indicator');
            const tanggalInput = document.getElementById('tanggal');
            let timeout = null;

            // Fungsi utama pencarian
            function performSearch() {
                loadingIndicator.classList.remove('hidden');

                const query = searchInput.value;
                const tanggal = tanggalInput.value;

                // URL target
                const url = "{{ route('transaksi.index') }}?search=" + encodeURIComponent(query) + "&tanggal=" + encodeURIComponent(tanggal);

                console.log("Searching: " + url);

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
                        tableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Gagal memuat data. Silakan coba lagi.</td></tr>';
                    })
                    .finally(() => {
                        loadingIndicator.classList.add('hidden');
                    });
            }

            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(performSearch, 300);
            });

            tanggalInput.addEventListener('change', function () {
                performSearch();
            });
        });
    </script>
</x-app-layout>