<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Ringkasan Stok -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
                    <h3 class="text-lg font-semibold text-gray-700">Total Barang</h3>
                    <p class="text-3xl font-bold mt-2 text-indigo-600" id="total-barang">0</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <h3 class="text-lg font-semibold text-gray-700">Barang Stok Menipis</h3>
                    <div id="stok-menipis" class="mt-2 text-sm text-gray-600">Loading...</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-pink-500">
                    <h3 class="text-lg font-semibold text-gray-700">Barang Terpopuler</h3>
                    <div id="stok-terpopuler" class="mt-2 text-sm text-gray-600">Loading...</div>
                </div>
            </div>

            <!-- Statistik Transaksi -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">📊 Statistik Transaksi</h3>
                <canvas id="chartTransaksi" height="100"></canvas>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">🕒 Aktivitas Terbaru</h3>
                <div id="aktivitas" class="space-y-2 text-sm text-gray-600">Loading...</div>

            </div>
        </div>
</x-app-layout>

<!-- Script AJAX -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartTransaksi = null;

    function loadDashboard() {
        // Ringkasan stok
        fetch("{{ route('dashboard.stokRingkasan') }}")
            .then(res => res.json())
            .then(data => document.getElementById("total-barang").textContent = data.totalBarang);

        // Barang stok menipis
        fetch("{{ route('dashboard.stokMenipis') }}")
            .then(res => res.json())
            .then(data => {
                let html = data.length ? "<ul>" : "Tidak ada barang menipis";
                data.forEach(b => html += `<li>${b.barang} (stok: ${b.stok})</li>`);
                if (data.length) html += "</ul>";
                document.getElementById("stok-menipis").innerHTML = html;
            })
            .catch(err => {
                document.getElementById("stok-menipis").innerHTML = "Gagal memuat data";
                console.error(err);
            });


        // Barang terpopuler
        fetch("{{ route('dashboard.stokTerpopuler') }}")
            .then(res => res.json())
            .then(data => {
                let html = data.length ? "<ul>" : "Tidak ada data";
                data.forEach(b => html += `<li>${b.barang} (${b.total} transaksi)</li>`);
                if (data.length) html += "</ul>";
                document.getElementById("stok-terpopuler").innerHTML = html;
            });

        // Statistik transaksi
        fetch("{{ route('dashboard.transaksiStatistik') }}")
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('chartTransaksi').getContext('2d');
                const chartData = {
                    labels: data.map(d => d.tanggal),
                    datasets: [{
                            label: 'Masuk',
                            data: data.map(d => d.masuk),
                            borderColor: 'green',
                            fill: false
                        },
                        {
                            label: 'Keluar',
                            data: data.map(d => d.keluar),
                            borderColor: 'red',
                            fill: false
                        }
                    ]
                };

                if (!chartTransaksi) {
                    chartTransaksi = new Chart(ctx, {
                        type: 'line',
                        data: chartData
                    });
                } else {
                    chartTransaksi.data = chartData;
                    chartTransaksi.update();
                }
            });

        // Aktivitas terbaru
        fetch("{{ route('dashboard.aktivitasTerbaru') }}")
            .then(res => res.json())
            .then(data => {
                let html = data.length ? "<ul>" : "Belum ada aktivitas";
                data.forEach(log => html +=
                    `<li>${log.created_at} - ${log.pegawai} - ${log.transaksi} ${log.jumlah}</li>`);
                if (data.length) html += "</ul>";
                document.getElementById("aktivitas").innerHTML = html;
            });

    }

    // Load pertama kali
    loadDashboard();

    // Auto refresh tiap 30 detik
    setInterval(loadDashboard, 30000);
</script>
