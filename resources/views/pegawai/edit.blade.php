<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Pegawai') }}
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

                    <form action="{{ route('pegawai.update', $pegawai->id_pegawai) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Pegawai -->
                        <div>
                            <label for="pegawai" class="block font-medium text-sm text-gray-700">Nama Pegawai</label>
                            <input type="text" name="pegawai" id="pegawai"
                                class="block mt-1 w-full rounded-md shadow-sm"
                                value="{{ old('pegawai', $pegawai->pegawai) }}" required>
                        </div>

                        <!-- Username -->
                        <div class="mt-4">
                            <label for="username" class="block font-medium text-sm text-gray-700">Username (untuk
                                login)</label>
                            <input type="text" name="username" id="username"
                                class="block mt-1 w-full rounded-md shadow-sm"
                                value="{{ old('username', $pegawai->username) }}" required>
                        </div>

                        <!-- Dropdown Jabatan -->
                        <div class="mt-4">
                            <label for="id_jabatan" class="block font-medium text-sm text-gray-700">Jabatan</label>
                            <select name="id_jabatan" id="id_jabatan" class="block mt-1 w-full rounded-md shadow-sm"
                                required>
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach ($jabatan as $j)
                                    <option value="{{ $j->id_jabatan }}" {{ old('id_jabatan', $pegawai->id_jabatan) == $j->id_jabatan ? 'selected' : '' }}>
                                        {{ $j->jabatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <label for="password" class="block font-medium text-sm text-gray-700">Password Baru (Biarkan
                                kosong jika tidak ganti)</label>
                            <input type="password" name="password" id="password"
                                class="block mt-1 w-full rounded-md shadow-sm">
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mt-4">
                            <label for="password_confirmation"
                                class="block font-medium text-sm text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block mt-1 w-full rounded-md shadow-sm">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('pegawai.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>