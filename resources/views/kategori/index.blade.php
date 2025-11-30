<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- BUTTON TAMBAH -->
                    <button id="btnAddKategori"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                               rounded-md font-semibold text-xs text-white uppercase tracking-widest
                               hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900
                               focus:ring ring-gray-300 transition ease-in-out duration-150 mb-4">
                        + Tambah Kategori
                    </button>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- TABLE -->
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="kategori-body" class="bg-white divide-y divide-gray-200">
                            @include('kategori.table_body')
                        </tbody>
                    </table>

                    <div id="modalTambahKategori"
                        class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center">
                        <div class="bg-white p-6 rounded shadow-md w-1/3">

                            <h2 class="text-lg font-bold mb-4">Tambah Kategori</h2>

                            <form id="form-tambah-kategori">
                                @csrf

                                <label class="block mb-2">Nama Kategori:</label>
                                <input type="text" name="kategori"
                                       class="w-full border p-2 rounded mb-4 focus:ring-gray-300 focus:border-gray-400"
                                       required>

                                <div class="flex justify-end space-x-2 mt-4">

                                    <button type="button" onclick="closeModal()"
                                        class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700
                                               hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        Batal
                                    </button>

                                    <button type="submit"
                                        class="px-4 py-2 bg-gray-800 text-white rounded-md font-semibold text-sm
                                               hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                        Simpan
                                    </button>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("btnAddKategori").addEventListener("click", () => {
            document.getElementById("modalTambahKategori").classList.remove("hidden");
        });

        function closeModal() {
            document.getElementById("modalTambahKategori").classList.add("hidden");
        }

        document.getElementById("form-tambah-kategori").addEventListener("submit", function(e){
            e.preventDefault();

            fetch("{{ route('kategori.store.ajax') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ kategori: this.kategori.value })
            })
            .then(res => res.json())
            .then(res => {
                alert(res.message);

                document.getElementById("kategori-body").insertAdjacentHTML("beforeend", `
                    <tr id="row-${res.data.id_kategori}">
                        <td class="px-6 py-4">${res.data.id_kategori}</td>
                        <td class="px-6 py-4">${res.data.kategori}</td>
                        <td class="px-6 py-4">
                            <a href="/kategori/${res.data.id_kategori}/edit" class="text-indigo-600">Edit</a>
                        </td>
                    </tr>
                `);

                closeModal();
                this.reset();
            })
            .catch(err => {
                alert("Gagal menambahkan kategori!");
                console.error(err);
            });
        });
    </script>

</x-app-layout>
