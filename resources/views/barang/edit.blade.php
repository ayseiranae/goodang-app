<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Barang') }}
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

                    <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Barang -->
                        <div>
                            <label for="barang" class="block font-medium text-sm text-gray-700">Nama Barang</label>
                            <input type="text" name="barang" id="barang" class="block mt-1 w-full rounded-md shadow-sm" value="{{ old('barang', $barang->barang) }}">
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-4">
                            <label for="deskripsi" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="block mt-1 w-full rounded-md shadow-sm">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                        </div>

                        <!-- Satuan -->
                        <div class="mt-4">
                            <label for="satuan" class="block font-medium text-sm text-gray-700">Satuan (Pcs/Kg/Box)</label>
                            <input type="text" name="satuan" id="satuan" class="block mt-1 w-full rounded-md shadow-sm" value="{{ old('satuan', $barang->satuan) }}">
                        </div>

                        <!-- Dropdown Kategori -->
                        <div class="mt-4">
                            <label for="id_kategori" class="block font-medium text-sm text-gray-700">Kategori</Lanjutan></label>
                            <select name="id_kategori" id="id_kategori" class="block mt-1 w-full rounded-md shadow-sm">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id_kategori }}" 
                                        {{ (old('id_kategori', $barang->id_kategori) == $kat->id_kategori) ? 'selected' : '' }}>
                                        {{ $kat->kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dropdown Pemasok -->
                        <div class="mt-4">
                            <label for="id_pemasok" class="block font-medium text-sm text-gray-700">Pemasok</label>
                            <select name="id_pemasok" id="id_pemasok" class="block mt-1 w-full rounded-md shadow-sm">
                                <option value="">-- Pilih Pemasok --</option>
                                @foreach ($pemasok as $pem)
                                    <option value="{{ $pem->id_pemasok }}" 
                                        {{ (old('id_pemasok', $barang->id_pemasok) == $pem->id_pemasok) ? 'selected' : '' }}>
                                        {{ $pem->pemasok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('barang.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>