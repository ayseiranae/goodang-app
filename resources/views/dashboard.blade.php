<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Row 1: Cari Barang + Stok Menipis --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Cari Barang --}}
                <div class="col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Cari Barang</h3>
                    <input id="search-barang" type="text" placeholder="Ketik nama barang..."
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <div id="search-results" class="mt-4 text-sm">
                    </div>
                </div>

                {{-- Stok Menipis --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Stok Menipis (&lt; 10)</h3>
                    <ul id="low-stock-list" class="text-sm space-y-2">
                        <li class="text-gray-500">Memuat data...</li>
                    </ul>
                </div>
            </div>

            {{-- Row 2: Grafik Transaksi --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Grafik Transaksi Stok (7 hari terakhir)</h3>
                <canvas id="chart-transaksi" height="120"></canvas>
            </div>

            {{-- Row 3: Aktivitas Terakhir --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Aktivitas Terakhir</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="px-3 py-2 text-left">Barang</th>
                                <th class="px-3 py-2 text-left">Jumlah</th>
                                <th class="px-3 py-2 text-left">Keterangan</th>
                                <th class="px-3 py-2 text-left">Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="recent-transactions-body">
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-gray-500 text-center">
                                    Memuat data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Script AJAX & Chart --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadDashboardData();
            setupSearchBarang();
        });

        function loadDashboardData() {
            fetch('{{ route('dashboard.data') }}')
                .then(resp => resp.json())
                .then(data => {
                    renderLowStock(data.low_stock);
                    renderRecentTransactions(data.recent_transactions);
                    renderChart(data.chart);
                })
                .catch(err => console.error(err));
        }

        function renderLowStock(items) {
            const ul = document.getElementById('low-stock-list');
            ul.innerHTML = '';

            if (!items.length) {
                ul.innerHTML = '<li class="text-gray-500">Tidak ada barang dengan stok menipis.</li>';
                return;
            }

            items.forEach(item => {
                const li = document.createElement('li');
                li.className = 'flex justify-between';
                li.innerHTML =
                    `<span>${item.barang}</span>
                     <span class="font-semibold">${item.stok} ${item.satuan}</span>`;
                ul.appendChild(li);
            });
        }

        function renderRecentTransactions(rows) {
            const tbody = document.getElementById('recent-transactions-body');
            tbody.innerHTML = '';

            if (!rows.length) {
                tbody.innerHTML =
                    '<tr><td colspan="4" class="px-3 py-4 text-gray-500 text-center">Belum ada transaksi.</td></tr>';
                return;
            }

            rows.forEach(row => {
                const tr = document.createElement('tr');
                tr.className = 'border-b';
                const tgl = row.created_at ? new Date(row.created_at) : null;

                tr.innerHTML = `
                    <td class="px-3 py-2">${row.barang}</td>
                    <td class="px-3 py-2">${row.transaksi === 'keluar' ? '-' : ''}${row.jumlah}</td>
                    <td class="px-3 py-2">${row.keterangan ?? ''}</td>
                    <td class="px-3 py-2">${tgl ? tgl.toLocaleString('id-ID') : '-'}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        let chartInstance = null;

        function renderChart(chart) {
            const ctx = document.getElementById('chart-transaksi').getContext('2d');

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chart.labels,
                    datasets: [{
                        label: 'Net Stok',
                        data: chart.data,
                        borderWidth: 2,
                        tension: 0.2,
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function setupSearchBarang() {
            const input = document.getElementById('search-barang');
            const results = document.getElementById('search-results');
            let timer = null;

            input.addEventListener('keyup', function () {
                clearTimeout(timer);
                const keyword = this.value.trim();

                if (keyword.length === 0) {
                    results.innerHTML = '';
                    return;
                }

                timer = setTimeout(() => {
                    fetch('{{ route('dashboard.search-barang') }}?q=' + encodeURIComponent(keyword))
                        .then(resp => resp.json())
                        .then(data => {
                            if (!data.length) {
                                results.innerHTML =
                                    '<p class="text-gray-500 text-sm mt-2">Tidak ada barang ditemukan.</p>';
                                return;
                            }

                            let html = '<ul class="divide-y mt-3 border rounded-md">';
                            data.forEach(item => {
                                html += `
                                    <li class="px-3 py-2 flex justify-between">
                                        <span>${item.barang}</span>
                                        <span class="font-semibold">${item.stok} ${item.satuan}</span>
                                    </li>`;
                            });
                            html += '</ul>';
                            results.innerHTML = html;
                        })
                        .catch(err => console.error(err));
                }, 300);
            });
        }
    </script>
</x-app-layout>