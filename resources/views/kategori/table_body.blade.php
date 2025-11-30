@foreach ($kategori as $kat)
<tr>
    <td class="px-6 py-4 whitespace-nowrap">{{ $kat->id_kategori }}</td>
    <td class="px-6 py-4 whitespace-nowrap">{{ $kat->kategori }}</td>
    <td class="px-6 py-4 whitespace-nowrap">
        <button 
            class="text-indigo-600 hover:text-indigo-900 btnEditKategori" 
            data-id="{{ $kat->id_kategori }}"
            data-kategori="{{ $kat->kategori }}">
            Edit
        </button>

        <button 
            class="text-red-600 hover:text-red-900 ml-4 btnDeleteKategori"
            data-id="{{ $kat->id_kategori }}">
            Hapus
        </button>
    </td>
</tr>
@endforeach

@if ($kategori->count() === 0)
<tr>
    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
        Tidak ada data kategori.
    </td>
</tr>
@endif