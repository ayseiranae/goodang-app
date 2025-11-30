<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form id="form-edit-kategori">
                        @csrf

                        <div>
                            <label for="kategori" class="block font-medium text-sm text-gray-700">Kategori</label>
                            <input type="text" name="kategori" id="kategori"
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                value="{{ $kategori->kategori }}">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('kategori.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#form-edit-kategori').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('kategori.update.ajax', $kategori->id_kategori) }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "{{ route('kategori.index') }}";
                    });
                },
                error: function (err) {
                    let errors = err.responseJSON.errors;
                    let msg = "";

                    $.each(errors, function (key, val) {
                        msg += val[0] + "<br>";
                    });

                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Gagal!',
                        html: msg,
                        showConfirmButton: true
                    });
                }
            });
        });
    </script>

</x-app-layout>