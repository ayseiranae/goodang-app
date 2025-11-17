<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Profil Perusahaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT') <!-- using PUT for updates -->

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="nama_perusahaan" class="block font-medium text-sm text-gray-700">Nama
                                    Perusahaan</label>
                                <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                                    value="{{ old('nama_perusahaan', $profil->nama_perusahaan ?? '') }}"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            <div>
                                <label for="alamat" class="block font-medium text-sm text-gray-700">Alamat
                                    Lengkap</label>
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('alamat', $profil->alamat ?? '') }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="telepon" class="block font-medium text-sm text-gray-700">Nomor
                                        Telepon</label>
                                    <input type="text" name="telepon" id="telepon"
                                        value="{{ old('telepon', $profil->telepon ?? '') }}"
                                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $profil->email ?? '') }}"
                                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="website" class="block font-medium text-sm text-gray-700">Website</label>
                                <input type="text" name="website" id="website"
                                    value="{{ old('website', $profil->website ?? '') }}"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Simpan
                                Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>