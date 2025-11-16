<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catat Transaksi Stok Baru') }}
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

                    <form action="{{ route('transaksi.store') }}" method="POST">
                        @csrf

                        <div class="mt-4">
                            <label for="transaksi" class="block font-medium text-sm text-gray-700">Tipe
                                Transaksi</label>
                            <select name="transaksi" id="transaksi" class="block mt-1 w-full rounded-md shadow-sm"
                                onchange="togglePemasok(this.value)">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="masuk" {{ old('transaksi') == 'masuk' ? 'selected' : '' }}>Barang Masuk
                                </option>
                                <option value="keluar" {{ old('transaksi') == 'keluar' ? 'selected' : '' }}>Barang Keluar
                                </option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="id_barang" class="block font-medium text-sm text-gray-700">Barang</label>
                            <select name="id_barang" id="id_barang" class="block mt-1 w-full rounded-md shadow-sm">
                                <option value="">-- Pilih Barang --</option>

                                @foreach ($barang as $b)
                                    @php
                                        // Hitung stok di sini
                                        $stok = ($b->masuk_sum_jumlah ?? 0) - ($b->keluar_sum_jumlah ?? 0);
                                    @endphp
                                    <option value="{{ $b->id_barang }}" {{ old('id_barang') == $b->id_barang ? 'selected' : '' }}>
                                        {{ $b->barang }} (Stok: {{ $stok }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="jumlah" class="block font-medium text-sm text-gray-700">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah"
                                class="block mt-1 w-full rounded-md shadow-sm" value="{{ old('jumlah') }}">
                        </div>

                        <div class="mt-4">
                            <label for="keterangan" class="block font-medium text-sm text-gray-700">Keterangan (Wajib
                                diisi)</label>
                            <textarea name="keterangan" id="keterangan"
                                class="block mt-1 w-full rounded-md shadow-sm">{{ old('keterangan') }}</textarea>
                        </div>

                        <div id="pemasok_div" class="mt-4"
                            style="display:{{ old('transaksi') == 'masuk' ? 'block' : 'none' }};">
                            <label for="id_pemasok" class="block font-medium text-sm text-gray-700">Pemasok (Wajib untuk
                                Barang Masuk)</label>
                            <select name="id_pemasok" id="id_pemasok" class="block mt-1 w-full rounded-md shadow-sm">
                                <option value="">-- Pilih Pemasok --</option>
                                @foreach ($pemasok as $pem)
                                    <option value="{{ $pem->id_pemasok }}" {{ old('id_pemasok') == $pem->id_pemasok ? 'selected' : '' }}>
                                        {{ $pem->pemasok }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('transaksi.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                Simpan Transaksi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript simpel untuk show/hide dropdown Pemasok
        function togglePemasok(tipe) {
            var div = document.getElementById('pemasok_div');
            if (tipe === 'masuk') {
                div.style.display = 'block';
            } else {
                div.style.display = 'none';
            }
        }
    </script>
</x-app-layout>