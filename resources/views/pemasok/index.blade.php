<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pemasok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- BUTTON TAMBAH (sekarang buka modal, bukan route create) --}}
                    <button id="btnAddPemasok"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                                   rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                   hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900
                                   focus:ring ring-gray-300 transition ease-in-out duration-150 mb-4">
                        Tambah Pemasok
                    </button>

                    {{-- Notif success via session masih bisa dipakai kalau mau --}}
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

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Pemasok</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kontak</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pemasok-body" class="bg-white divide-y divide-gray-200">
                            @include('pemasok.table_body')
                        </tbody>
                    </table>

                    {{-- MODAL TAMBAH PEMASOK --}}
                    <div id="modalTambahPemasok"
                        class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded shadow-md w-1/3">
                            <h2 class="text-lg font-bold mb-4">Tambah Pemasok</h2>

                            <form id="form-tambah-pemasok">
                                @csrf
                                <div class="mb-3">
                                    <label class="block mb-1 text-sm">Nama Pemasok</label>
                                    <input type="text" name="pemasok" id="input-pemasok"
                                        class="w-full border p-2 rounded focus:ring-gray-300 focus:border-gray-400"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="block mb-1 text-sm">Kontak</label>
                                    <input type="text" name="kontak" id="input-kontak"
                                        class="w-full border p-2 rounded focus:ring-gray-300 focus:border-gray-400"
                                        required>
                                </div>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" onclick="closeModalTambahPemasok()"
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

                    {{-- MODAL EDIT PEMASOK --}}
                    <div id="modalEditPemasok"
                        class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded shadow-md w-1/3">
                            <h2 class="text-lg font-bold mb-4">Edit Pemasok</h2>

                            <form id="form-edit-pemasok">
                                @csrf
                                <input type="hidden" id="edit-id">

                                <div class="mb-3">
                                    <label class="block mb-1 text-sm">Nama Pemasok</label>
                                    <input type="text" id="edit-pemasok"
                                        class="w-full border p-2 rounded focus:ring-gray-300 focus:border-gray-400"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="block mb-1 text-sm">Kontak</label>
                                    <input type="text" id="edit-kontak"
                                        class="w-full border p-2 rounded focus:ring-gray-300 focus:border-gray-400"
                                        required>
                                </div>

                                <div class="flex justify-end space-x-2 mt-4">
                                    <button type="button" onclick="closeModalEditPemasok()"
                                        class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700
                                                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-gray-800 text-white rounded-md font-semibold text-sm
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

    {{-- jQuery & SweetAlert --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ==== MODAL TAMBAH ====
        document.getElementById('btnAddPemasok').addEventListener('click', () => {
            document.getElementById('modalTambahPemasok').classList.remove('hidden');
        });

        function closeModalTambahPemasok() {
            document.getElementById('modalTambahPemasok').classList.add('hidden');
            document.getElementById('form-tambah-pemasok').reset();
        }

        // SUBMIT TAMBAH (AJAX)
        $('#form-tambah-pemasok').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('pemasok.store.ajax') }}",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    pemasok: $('#input-pemasok').val(),
                    kontak: $('#input-kontak').val(),
                },
                success: function(res) {
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

                    loadTablePemasok();
                    closeModalTambahPemasok();
                },
                error: function(err) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal menambahkan pemasok!',
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

        // ==== MODAL EDIT ====
        function closeModalEditPemasok() {
            $('#modalEditPemasok').addClass('hidden');
            $('#form-edit-pemasok')[0].reset();
        }

        // BUKA MODAL EDIT
        $(document).on('click', '.btnEditPemasok', function() {
            const id = $(this).data('id');
            const pemasok = $(this).data('pemasok');
            const kontak = $(this).data('kontak');

            $('#edit-id').val(id);
            $('#edit-pemasok').val(pemasok);
            $('#edit-kontak').val(kontak);

            $('#modalEditPemasok').removeClass('hidden');
        });

        // SUBMIT EDIT (AJAX)
        $('#form-edit-pemasok').on('submit', function(e) {
            e.preventDefault();

            const id = $('#edit-id').val();
            const pemasok = $('#edit-pemasok').val();
            const kontak = $('#edit-kontak').val();

            $.ajax({
                url: `/pemasok/${id}/update-ajax`,
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    pemasok: pemasok,
                    kontak: kontak
                },
                success: function(res) {
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

                    loadTablePemasok();
                    closeModalEditPemasok();
                },
                error: function(err) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal mengupdate pemasok!',
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

        // ==== DELETE (AJAX) ====
        $(document).on('click', '.btnDeletePemasok', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pemasok akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1f2937',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/pemasok/${id}/delete-ajax`,
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
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

                            loadTablePemasok();
                        },
                        error: function(err) {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Gagal menghapus pemasok!',
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

        // ==== RELOAD TABLE BODY ====
        function loadTablePemasok() {
            $.ajax({
                url: "{{ route('pemasok.index') }}",
                method: "GET",
                success: function(response) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(response, 'text/html');
                    const newBody = doc.querySelector('#pemasok-body');

                    if (newBody) {
                        $('#pemasok-body').html(newBody.innerHTML);
                    }
                }
            });
        }
    </script>
</x-app-layout>
