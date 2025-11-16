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

                    <!-- Pesan Sukses -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    <!-- Pesan Error -->
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
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($barang as $b)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $b->id_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $b->barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $b->kategori->kategori ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $b->pemasok->pemasok ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $b->satuan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('barang.edit', $b->id_barang) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                        <form action="{{ route('barang.destroy', $b->id_barang) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Yakin mau hapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>