<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <a href="{{ route('barang.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                        Tambah Barang
                    </a>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Barang</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pemasok</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Satuan</th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stok</th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-barang" class="bg-white divide-y divide-gray-200">
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>

     <script>
    $(document).ready(function () {
        loadBarang();

        function loadBarang() {
            $.ajax({
                url: "{{ route('barang.data') }}",
                method: "GET",
                success: function (data) {
                    let rows = "";

                    data.forEach((item) => {
                        let stok = (item.masuk_sum_jumlah ?? 0) - (item.keluar_sum_jumlah ?? 0);

                        rows += `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">${item.id_barang}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.barang}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.kategori?.kategori ?? 'N/A'}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.pemasok?.pemasok ?? 'N/A'}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.satuan}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-lg">${stok}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="/barang/${item.id_barang}/edit"
                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                    <button class="text-red-600 hover:text-red-900 ml-4 btn-delete"
                                            data-id="${item.id_barang}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    $("#tbody-barang").html(rows);
                }
            });
        }
    });

    $(document).on("click", ".btn-delete", function () {
        let id = $(this).data("id");

        if (!confirm("Yakin mau hapus?")) return;

        $.ajax({
            url: "/barang/" + id,
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                alert("Barang berhasil dihapus!");
                loadBarang(); 
            },
            error: function () {
                alert("Barang tidak bisa dihapus, mungkin sudah dipakai di transaksi!");
            }
        });
    });

    </script>
</x-app-layout>