<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kategori Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="form-kategori" action="{{ route('kategori.store.ajax') }}" method="POST">
                     @csrf
                        <div>
                            <label for="kategori" class="block font-medium text-sm text-gray-700">Kategori</label>
                            <input type="text" name="kategori" id="kategori"
                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                value="{{ old('kategori') }}">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('kategori.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $('#form-kategori').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('kategori.store.ajax') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    window.location.href = "{{ route('kategori.index') }}";
                }
            },
            error: function(err) {
                let errors = err.responseJSON.errors;
                let message = "";

                $.each(errors, function(key, val) {
                    message += val[0] + "\n";
                });

                alert(message);
            }
        });
    });
    </script>
</x-app-layout>