@foreach ($pemasok as $p)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">{{ $p->id_pemasok }}</td>
        <td class="px-6 py-4 whitespace-nowrap">{{ $p->pemasok }}</td>
        <td class="px-6 py-4 whitespace-nowrap">{{ $p->kontak }}</td>
        <td class="px-6 py-4 whitespace-nowrap space-x-4">
            <button class="text-indigo-600 hover:text-indigo-900 btnEditPemasok" data-id="{{ $p->id_pemasok }}"
                data-pemasok="{{ $p->pemasok }}" data-kontak="{{ $p->kontak }}">
                Edit
            </button>

            <button class="text-red-600 hover:text-red-900 btnDeletePemasok" data-id="{{ $p->id_pemasok }}">
                Hapus
            </button>
        </td>
    </tr>
@endforeach
