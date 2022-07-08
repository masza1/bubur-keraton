<x-datatables :header="$showButton">
    @if ($showButton)
        <x-slot name="buttonHeader">
            <div class="flex justify-end gap-2">
                <x-button id="addItem" type="button" @click="openModal($event,'modalAddItem', '#modalAddItem')"
                    class="flex items-center justify-between text-white px-2 py-1 my-1 text-sm font-medium leading-5 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray">
                    Tambah Daftar Belanja
                </x-button>
            </div>
        </x-slot>
    @endif

    <x-slot name="tableHeader">
        Table Daftar Belanja
    </x-slot>

    <x-slot name="thead">
        <th class="px-4 py-3 w-3/12">Nama Barang</th>
        <th class="px-4 py-3 w-2/12">Jumlah</th>
        <th class="px-4 py-3 w-3/12">Total Harga</th>
        <th class="px-4 py-3 w-3/12">Tanggal/Jam</th>
        <th class="px-4 py-3 w-1/12">Actions</th>
    </x-slot>

    @forelse ($buyItems as $item)
        <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">{{ $item->item == null ? '-' : $item->item }}</td>
            <td class="px-4 py-3">{{ $item->quantity == null ? '-' : $item->quantity }}</td>
            <td class="px-4 py-3">{{ $item->price == null ? '-' : $item->price }}</td>
            <td class="px-4 py-3">{{ $item->created_at == null ? '-' : date('Y-m-d H:i:s', strtotime($item->created_at)) }}</td>
            <td class="px-4 py-3">
                <div class="flex items-center space-x-4 text-sm">
                    @if ($showButton)
                        <button type="button" @click="openModal($event,'modalEditItem', '#modalEditItem')" data-id="{{ $item->id }}"
                            data-price="{{ $item->price }}" data-quantity="{{ $item->quantity }}" data-item="{{ $item->item }}"
                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                            aria-label="Edit">
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                </path>
                            </svg>
                        </button>
                        <button type="button" @click="openModal($event,'modalDelete', '#modalDelete')" data-id={{ $item->id }} data-tab="1"
                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                            aria-label="Delete">
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    @else
                        -
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center">Tidak ada data</td>
        </tr>
    @endforelse
</x-datatables>
