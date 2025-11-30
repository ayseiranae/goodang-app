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

                    <button id="btnAddBarang"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                        Tambah Barang
                    </button>

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

                    <!-- MODAL TAMBAH -->
                    <div id="modalTambahBarang"
                        class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded shadow-md w-1/2 max-h-[90vh] overflow-y-auto">
                            <h2 class="text-lg font-bold mb-4">Tambah Barang Baru</h2>

                            <form id="form-tambah-barang">
                                @csrf
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Nama Barang</label>
                                    <input type="text" name="barang" id="barang"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                </div>

                                <div class="mt-4">
                                    <label class="block font-medium text-sm text-gray-700">Deskripsi</label>
                                    <textarea name="deskripsi" id="deskripsi"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300"></textarea>
                                </div>

                                <div class="mt-4">
                                    <label class="block font-medium text-sm text-gray-700">Satuan</label>
                                    <input type="text" name="satuan" id="satuan"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                </div>

                                <div class="mt-4">
                                    <label class="block font-medium text-sm text-gray-700">Kategori</label>
                                    <select name="id_kategori" id="id_kategori"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <label class="block font-medium text-sm text-gray-700">Pemasok</label>
                                    <select name="id_pemasok" id="id_pemasok"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    </select>
                                </div>

                                <div class="flex justify-end space-x-2 mt-6">
                                    <button type="button" onclick="closeModalTambah()"
                                        class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gray-800 text-white rounded-md font-semibold text-sm hover:bg-gray-700">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- MODAL EDIT -->
                    <div id="modalEditBarang"
                        class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded shadow-md w-1/2 max-h-[90vh] overflow-y-auto">
                            <h2 class="text-lg font-bold mb-4">Edit Barang</h2>

                            <form id="form-edit-barang">
                                @csrf
                                <input type="hidden" id="edit-id" name="id">

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Nama Barang</label>
                                    <input type="text" name="barang" id="edit-barang"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                </div>

                                <div class="mt-4">
                                    <label class="block font-medium text-sm text-gray-700">Deskripsi</label>
                                    <textarea name="deskripsi" id="edit-deskripsi"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300"></textarea>
                                </div>

                                <div class="mt-4">
                                    <label class="block font-medium text-sm text-gray-700">Satuan</label>
                                    <input type="text" name="satuan" id="edit-satuan"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                </div>

                                <div class="mt-4">
                                    <label class="block font-medium text-sm text-gray-700">Kategori</label>
                                    <select name="id_kategori" id="edit-id_kategori"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <label class="block font-medium text-sm text-gray-700">Pemasok</label>
                                    <select name="id_pemasok" id="edit-id_pemasok"
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    </select>
                                </div>

                                <div class="flex justify-end space-x-2 mt-6">
                                    <button type="button" onclick="closeModalEdit()"
                                        class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gray-800 text-white rounded-md font-semibold text-sm hover:bg-gray-700">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            loadBarang();
            loadKategori();
            loadPemasok();
        });

        // LOAD DATA BARANG
        function loadBarang() {
            $.ajax({
                url: "{{ route('barang.data') }}",
                method: "GET",
                success: function (data) {
                    let rows = "";

                    if (data.length === 0) {
                        rows = `<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data barang.</td></tr>`;
                    } else {
                        data.forEach((item) => {
                            let stok = (item.masuk_sum_jumlah ?? 0) - (item.keluar_sum_jumlah ?? 0);

                            rows += `
                                <tr id="row-${item.id_barang}">
                                    <td class="px-6 py-4 whitespace-nowrap">${item.id_barang}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${item.barang}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${item.kategori?.kategori ?? 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${item.pemasok?.pemasok ?? 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${item.satuan}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-lg">${stok}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button class="text-indigo-600 hover:text-indigo-900 btnEditBarang"
                                                data-id="${item.id_barang}"
                                                data-barang="${item.barang}"
                                                data-deskripsi="${item.deskripsi ?? ''}"
                                                data-satuan="${item.satuan}"
                                                data-kategori="${item.id_kategori ?? ''}"
                                                data-pemasok="${item.id_pemasok ?? ''}">
                                            Edit
                                        </button>

                                        <button class="text-red-600 hover:text-red-900 ml-4 btn-delete"
                                                data-id="${item.id_barang}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    $("#tbody-barang").html(rows);
                }
            });
        }

        // LOAD KATEGORI
        function loadKategori() {
            $.ajax({
                url: "{{ route('kategori.data') }}",
                method: "GET",
                success: function (data) {
                    let options = '<option value="">None</option>';
                    data.forEach(item => {
                        options += `<option value="${item.id_kategori}">${item.kategori}</option>`;
                    });
                    $('#id_kategori, #edit-id_kategori').html(options);
                }
            });
        }

        // LOAD PEMASOK
        function loadPemasok() {
            $.ajax({
                url: "{{ route('pemasok.data') }}",
                method: "GET",
                success: function (data) {
                    let options = '<option value="">None</option>';
                    data.forEach(item => {
                        options += `<option value="${item.id_pemasok}">${item.pemasok}</option>`;
                    });
                    $('#id_pemasok, #edit-id_pemasok').html(options);
                }
            });
        }

        // MODAL TAMBAH
        $('#btnAddBarang').on('click', function () {
            $('#modalTambahBarang').removeClass('hidden');
        });

        function closeModalTambah() {
            $('#modalTambahBarang').addClass('hidden');
            $('#form-tambah-barang')[0].reset();
        }

        // TAMBAH BARANG
        $('#form-tambah-barang').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('barang.store.ajax') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: {
                            title: 'swal-title-small'
                        }
                    });

                    loadBarang();
                    closeModalTambah();
                },
                error: function (err) {
                    let errors = err.responseJSON.errors;
                    let message = "";

                    $.each(errors, function (key, val) {
                        message += val[0] + "<br>";
                    });

                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal!',
                        html: message,
                        showConfirmButton: true
                    });
                }
            });
        });

        // MODAL EDIT
        function closeModalEdit() {
            $('#modalEditBarang').addClass('hidden');
            $('#form-edit-barang')[0].reset();
        }

        $(document).on('click', '.btnEditBarang', function () {
            const id = $(this).data('id');
            const barang = $(this).data('barang');
            const deskripsi = $(this).data('deskripsi');
            const satuan = $(this).data('satuan');
            const kategori = $(this).data('kategori');
            const pemasok = $(this).data('pemasok');

            $('#edit-id').val(id);
            $('#edit-barang').val(barang);
            $('#edit-deskripsi').val(deskripsi);
            $('#edit-satuan').val(satuan);
            $('#edit-id_kategori').val(kategori);
            $('#edit-id_pemasok').val(pemasok);

            $('#modalEditBarang').removeClass('hidden');
        });

        // UPDATE BARANG
        $('#form-edit-barang').on('submit', function (e) {
            e.preventDefault();

            const id = $('#edit-id').val();

            $.ajax({
                url: `/barang/${id}/update-ajax`,
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: {
                            title: 'swal-title-small'
                        }
                    });

                    loadBarang();
                    closeModalEdit();
                },
                error: function (err) {
                    let errors = err.responseJSON?.errors;
                    let message = "";

                    if (errors) {
                        $.each(errors, function (key, val) {
                            message += val[0] + "<br>";
                        });
                    } else {
                        message = "Terjadi kesalahan!";
                    }

                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal!',
                        html: message,
                        showConfirmButton: true
                    });
                }
            });
        });

        // DELETE BARANG
        $(document).on("click", ".btn-delete", function () {
            let id = $(this).data("id");

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data barang akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/barang/${id}/delete-ajax`,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: res.message,
                                showConfirmButton: false,
                                timer: 2000,
                                customClass: {
                                    title: 'swal-title-small'
                                }
                            });

                            loadBarang();
                        },
                        error: function () {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Gagal menghapus!',
                                text: 'Barang mungkin sudah dipakai di transaksi',
                                showConfirmButton: true
                            });
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>