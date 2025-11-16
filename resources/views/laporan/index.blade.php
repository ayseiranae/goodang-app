<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Rangkuman Bulanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="GET" action="{{ route('laporan.index') }}" class="mb-6">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label for="bulan" class="block font-medium text-sm text-gray-700">Bulan:</label>
                                <select name="bulan" id="bulan" class="block mt-1 rounded-md shadow-sm border-gray-300">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="tahun" class="block font-medium text-sm text-gray-700">Tahun:</label>
                                <select name="tahun" id="tahun" class="block mt-1 rounded-md shadow-sm border-gray-300">
                                    @foreach ($daftarTahun as $th)
                                        <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>
                                            {{ $th }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                                    Tampilkan Laporan
                                </button>
                            </div>

                            <div class="pt-6">
                                <a href="{{ route('laporan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                                   target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                    Download PDF
                                </a>
                            </div>

                        </div>
                    </form>
                    <h3 class="text-lg font-semibold mb-2">
                        Laporan untuk: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                    </h3>
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-green-600">Total Masuk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-red-600">Total Keluar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perubahan Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($laporan as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-600 font-medium">+{{ $item->total_masuk }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-red-600 font-medium">-{{ $item->total_keluar }}</td>
                                    
                                    @php $perubahan = $item->total_masuk - $item->total_keluar; @endphp
                                    <td class="px-6 py-4 whitespace-nowrap font-bold {{ $perubahan >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $perubahan > 0 ? '+' : '' }}{{ $perubahan }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada pergerakan barang di bulan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>