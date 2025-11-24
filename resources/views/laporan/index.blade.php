<x-app-layout>

    {{-- Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Stok Gudang') }}
        </h2>
    </x-slot>

    <div class="py-10" x-data="reportsApp()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- FILTER CARD --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border dark:border-gray-700">
                <form @submit.prevent="applyFilter()" class="grid grid-cols-1 md:grid-cols-12 gap-4">

                    {{-- Start Date --}}
                    <div class="md:col-span-3">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                            Tanggal Mulai
                        </label>
                        <input type="date" x-model="filters.startDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white" />
                    </div>

                    {{-- End Date --}}
                    <div class="md:col-span-3">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                            Tanggal Akhir
                        </label>
                        <input type="date" x-model="filters.endDate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white" />
                    </div>

                    {{-- Transaction Type --}}
                    <div class="md:col-span-3">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                            Tipe Transaksi
                        </label>
                        <select x-model="filters.type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white">
                            <option value="all">Semua</option>
                            <option value="in">Stok Masuk</option>
                            <option value="out">Stok Keluar</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="md:col-span-3 flex items-end space-x-2">
                        <button type="submit"
                            class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold">
                            Filter
                        </button>

                        <button @click="exportPDF()" type="button"
                            class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-500">
                            PDF
                        </button>
                    </div>

                </form>
            </div>

            {{-- Loading --}}
            <div x-show="loading" class="flex justify-center py-10">
                <div class="flex items-center space-x-3 text-gray-600 dark:text-gray-300">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-800"></div>
                    <span>Memuat data...</span>
                </div>
            </div>

            {{-- STATISTICS --}}
            <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-4 gap-6">

                {{-- Stok Masuk --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border dark:border-gray-700">
                    <h4 class="text-sm text-gray-500 mb-1">Total Stok Masuk</h4>
                    <p class="text-3xl font-bold text-green-600" x-text="statistics.stockIn"></p>
                </div>

                {{-- Stok Keluar --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border dark:border-gray-700">
                    <h4 class="text-sm text-gray-500 mb-1">Total Stok Keluar</h4>
                    <p class="text-3xl font-bold text-red-600" x-text="statistics.stockOut"></p>
                </div>

                {{-- Selisih --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border dark:border-gray-700">
                    <h4 class="text-sm text-gray-500 mb-1">Selisih Bersih</h4>
                    <p class="text-3xl font-bold" x-text="formatNumber(statistics.netChange)"></p>
                </div>

                {{-- Total Transaksi --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border dark:border-gray-700">
                    <h4 class="text-sm text-gray-500 mb-1">Total Transaksi</h4>
                    <p class="text-3xl font-bold text-purple-600" x-text="statistics.totalTransactions"></p>
                </div>
            </div>

            {{-- TRANSACTIONS TABLE --}}
            <div x-show="!loading"
                class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border dark:border-gray-700">

                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                    Riwayat Transaksi
                </h3>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600">User</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                        <template x-for="t in transactions" :key="t.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-3 text-sm" x-text="formatDateTime(t.created_at)"></td>
                                <td class="px-6 py-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                        :class="t.type === 'in' ? 'bg-green-100 text-green-700' :
                                            'bg-red-100 text-red-700'">
                                        <span x-text="t.type === 'in' ? 'Masuk' : 'Keluar'"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm font-medium" x-text="t.item_name"></td>
                                <td class="px-6 py-3 text-sm font-bold"
                                    :class="t.type === 'in' ? 'text-green-600' : 'text-red-600'"
                                    x-text="(t.type === 'in' ? '+' : '-') + t.quantity"></td>
                                <td class="px-6 py-3 text-sm" x-text="t.user_name"></td>
                            </tr>
                        </template>

                        {{-- Empty state --}}
                        <tr x-show="transactions.length === 0">
                            <td colspan="5" class="py-10 text-center text-gray-500">
                                Tidak ada transaksi pada periode ini
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-app-layout>


{{-- SCRIPT ALPINE --}}
@push('scripts')
    <script>
        {!! file_get_contents(resource_path('js/reports-app.js')) !!}
    </script>
@endpush
