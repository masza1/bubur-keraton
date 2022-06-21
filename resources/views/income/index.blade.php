<x-app-layout>
    <x-slot name="header">
        Table Pemasukan
    </x-slot>
    <div class="flex-none mb-2">
        <x-button type="button" data-type="download" @click="openModal($event,'downloadSaleMonth', '#downloadSaleMonth')">
            Downloadn Laporan Bulanan
        </x-button>
        <x-button type="button" data-type="view" @click="openModal($event,'downloadSaleMonth', '#downloadSaleMonth')">
            Lihat Laporan Bulanan
        </x-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="flex">
            <div class="flex-auto">
                <x-input @change.debounce.500ms="changeDate($event)" type="date" id="date" name="date" class="block w-full" site="incomes"
                    value="{{ $date ?? date('Y-m-d') }}" />
            </div>
            <div class="flex-none ml-2 mt-1">
                <x-button type="button" id="downloadIncome" @click="downloadIncome('download')">
                    <i class="fa fa-download"></i>
                </x-button>
                <x-button type="button" id="downloadIncome" @click="downloadIncome('view')">
                    <i class="fa fa-eye"></i>
                </x-button>
            </div>
        </div>
    </div>

    <div id="contentTableIncome">
        <x-incomes.table-incomes :incomes="$incomes" :showButton="$showButton" />
    </div>

    <x-slot name="modals">
        <x-base-modal id="modalAddItem" :modalWidth="__('w-8/12')" :withForm="true" :formId="__('formAddItem')">
            <x-slot name="modalTitle">Tambah Menu</x-slot>
            <div class="grid grid-cols-3 sm:grid-cols-10 gap-3">
                <div class="col-span-3 sm:col-span-6">
                    <x-label>Nama Menu</x-label>
                    <x-input type="text" id="name[]" name="name[]" class="block w-full" autofocus />
                </div>
                <div class="col-span-2 sm:col-span-3">
                    <x-label>Harga</x-label>
                    <x-input type="text" id="price[]" name="price[]" class="block w-full" autofocus />
                </div>
                <div class="flex mt-4 mb-3 justify-end text-sm sm:col-span-1">
                    <button type="button" @click="btnAddRow"
                        class="btnAddRow flex items-center justify-between px-2 py-2 text-4xl font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                        aria-label="Tambah">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button type="button" @click="btnDeleteRow"
                        class="btnDeleteRow hidden items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                        aria-label="Delete">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </x-base-modal>
        <x-base-modal id="modalAddIncome" :modalWidth="__('w-8/12')" :withForm="true" :formId="__('formAddIncome')">
            <x-slot name="modalTitle">Tambah Pemasukan</x-slot>
            <div class="grid grid-cols-9 gap-3">
                <div class="col-span-9 sm:col-span-3 mt-1">
                    <x-label>Platform</x-label>
                    <x-input :inputType="__('select')" id="platform[]" name="platform[]" class="block w-full" autofocus>
                        <option value="" selected disabled>Pilih Platform</option>
                        <option value="1">Offline</option>
                        <option value="2">Gojek</option>
                        <option value="3">Grab</option>
                        <option value="4">Shopeefood</option>
                    </x-input>
                </div>
                <div class="col-span-9 sm:col-span-3 mt-1">
                    <x-label>Menu</x-label>
                    <x-input :inputType="__('select')" id="item_id[]" name="item_id[]" class="block w-full item_id" autofocus>
                        <option value="" selected disabled>Pilih Menu</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </x-input>
                </div>
                <div class="col-span-6 sm:col-span-2">
                    <x-label>Jumlah</x-label>
                    <x-input type="number" id="quantity[]" min="1" name="quantity[]" class="block w-full" autofocus />
                </div>
                <div class="flex mt-4 mb-3 justify-end text-sm col-span-3 sm:col-span-1">
                    <button type="button" @click="btnAddRow"
                        class="btnAddRow flex items-center justify-between px-2 py-2 text-4xl font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                        aria-label="Tambah">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button type="button" @click="btnDeleteRow"
                        class="btnDeleteRow hidden items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                        aria-label="Delete">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </x-base-modal>
        <x-base-modal id="modalEditIncome" :modalWidth="__('w-8/12')" :withForm="true" :formId="__('formEditIncome')">
            <x-slot name="modalTitle">Ubah Pemasukan</x-slot>
            <x-slot name="formMethod">PUT</x-slot>
            <x-slot name="textSubmit">Ubah</x-slot>
            <div class="grid grid-cols-9 gap-3">
                <div class="col-span-9 sm:col-span-3 mt-1">
                    <x-label>Platform</x-label>
                    <x-input :inputType="__('select')" id="platform" name="platform" class="block w-full" autofocus>
                        <option value="" selected disabled>Pilih Platform</option>
                        <option value="1">Offline</option>
                        <option value="2">Gojek</option>
                        <option value="3">Grab</option>
                        <option value="4">Shopeefood</option>
                    </x-input>
                </div>
                <div class="col-span-6 sm:col-span-5 mt-1">
                    <x-label>Menu</x-label>
                    <x-input :inputType="__('select')" id="item_id" name="item_id" class="block w-full item_id" autofocus>
                        <option value="" selected disabled>Pilih Menu</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </x-input>
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <x-label>Jumlah</x-label>
                    <x-input type="number" id="quantity" min="1" name="quantity" class="block w-full" autofocus />
                </div>
            </div>
        </x-base-modal>
        <x-base-modal id="downloadSaleMonth" :modalWidth="__('w-3/12')" :withForm="true" :formId="__('formDownloadSaleMonth')">
            <x-slot name="modalTitle">Pilih Bulan dan Tahun</x-slot>
            <x-label>Bulan</x-label>
            <x-input :inputType="__('select')" name="month" class="block w-full" autofocus>
                <option value="" selected disabled>Pilih Bulan</option>
                @for ($i = 1; $i < 13; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </x-input>
            <x-label>Tahun</x-label>
            <x-input :inputType="__('select')" name="year" class="block w-full" autofocus>
                <option value="" selected disabled>Pilih Tahun</option>
                @for ($i = 2019; $i <= date('Y'); $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </x-input>
        </x-base-modal>
    </x-slot>

    <x-slot name="js">
        <script src="{{ asset('js/stock.js') }}"></script>
    </x-slot>
</x-app-layout>
