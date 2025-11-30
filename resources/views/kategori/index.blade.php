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
                    <button id="btnAddKategori" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                               rounded-md font-semibold text-xs text-white uppercase tracking-widest
                               hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900
                               focus:ring ring-gray-300 transition ease-in-out duration-150 mb-4">
                        Tambah Kategori
                    </button>

                    @if (session('success'))
                        <script>
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: '{{ session('success') }}',
                                showConfirmButton: false,
                                timer: 2000,
                                customClass: {
                                    title: 'swal-title-small'
                                }
                            });
                        </script>
                    @endif

                    <!-- TABLE -->
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Kategori</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="kategori-body" class="bg-white divide-y divide-gray-200">
                            @include('kategori.table_body')
                        </tbody>
                    </table>

                    <!-- MODAL TAMBAH -->
                    <div id="modalTambahKategori"
                        class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded shadow-md w-1/3">
                            <h2 class="text-lg font-bold mb-4">Tambah Kategori</h2>

                            <form id="form-tambah-kategori">
                                @csrf
                                <label class="block mb-2">Nama Kategori:</label>
                                <input type="text" name="kategori" id="kategori-input"
                                    class="w-full border p-2 rounded mb-4 focus:ring-gray-300 focus:border-gray-400"
                                    required>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" onclick="closeModalTambah()" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700
                                               hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md font-semibold text-sm
                                               hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- MODAL EDIT -->
                    <div id="modalEditKategori"
                        class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded shadow-md w-1/3">
                            <h2 class="text-lg font-bold mb-4">Edit Kategori</h2>

                            <form id="form-edit-kategori">
                                @csrf
                                <input type="hidden" id="edit-id" name="id">
                                <label class="block mb-2">Nama Kategori:</label>
                                <input type="text" name="kategori" id="edit-kategori"
                                    class="w-full border p-2 rounded mb-4 focus:ring-gray-300 focus:border-gray-400"
                                    required>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" onclick="closeModalEdit()" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700
                                               hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md font-semibold text-sm
                                               hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
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
        // MODAL TAMBAH
        document.getElementById("btnAddKategori").addEventListener("click", () => {
            document.getElementById("modalTambahKategori").classList.remove("hidden");
        });

        function closeModalTambah() {
            document.getElementById("modalTambahKategori").classList.add("hidden");
            document.getElementById("form-tambah-kategori").reset();
        }

        // TAMBAH KATEGORI
        document.getElementById("form-tambah-kategori").addEventListener("submit", function (e) {
            e.preventDefault();

            fetch("{{ route('kategori.store.ajax') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ kategori: document.getElementById('kategori-input').value })
            })
                .then(res => res.json())
                .then(res => {
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

                    // Reload table
                    loadTable();
                    closeModalTambah();
                })
                .catch(err => {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal menambahkan kategori!',
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: {
                            title: 'swal-title-small'
                        }
                    });
                    console.error(err);
                });
        });

        // MODAL EDIT
        function closeModalEdit() {
            document.getElementById("modalEditKategori").classList.add("hidden");
            document.getElementById("form-edit-kategori").reset();
        }

        // OPEN EDIT MODAL
        $(document).on('click', '.btnEditKategori', function () {
            const id = $(this).data('id');
            const kategori = $(this).data('kategori');

            $('#edit-id').val(id);
            $('#edit-kategori').val(kategori);
            $('#modalEditKategori').removeClass('hidden');
        });

        // UPDATE KATEGORI
        $('#form-edit-kategori').on('submit', function (e) {
            e.preventDefault();

            const id = $('#edit-id').val();
            const kategori = $('#edit-kategori').val();

            $.ajax({
                url: `/kategori/${id}/update-ajax`,
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    kategori: kategori
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

                    loadTable();
                    closeModalEdit();
                },
                error: function (err) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal mengupdate kategori!',
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: {
                            title: 'swal-title-small'
                        }
                    });
                    console.error(err);
                }
            });
        });

        // DELETE KATEGORI
        $(document).on('click', '.btnDeleteKategori', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data kategori akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/kategori/${id}/delete-ajax`,
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}'
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

                            loadTable();
                        },
                        error: function (err) {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Gagal menghapus kategori!',
                                showConfirmButton: false,
                                timer: 2000,
                                customClass: {
                                    title: 'swal-title-small'
                                }
                            });
                            console.error(err);
                        }
                    });
                }
            });
        });

        // RELOAD TABLE
        function loadTable() {
            $.ajax({
                url: "{{ route('kategori.index') }}",
                method: "GET",
                success: function (response) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(response, 'text/html');
                    const newTableBody = doc.querySelector('#kategori-body');

                    if (newTableBody) {
                        $('#kategori-body').html(newTableBody.innerHTML);
                    }
                }
            });
        }
    </script>
</x-app-layout>