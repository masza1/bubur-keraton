<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <title>Sheet 1</title>
    <style>
        table.border tr,
        table.border th,
        table.border td{
            border: 1px solid black; 
            border-collapse: collapse;
            padding: 5px;
        }
        td.center, th.center {
            text-align: center;
        }
    </style>
</head>

<body>
    <table>
        <tbody>
            <tr>
                <td width="100">
                    Hari, Tanggal
                </td>
                <td colspan="5">: {{ date('l, d F Y', strtotime($date)) }}</td>
            </tr>
            <tr>
                <td>
                    Shift
                </td>
                <td colspan="5">: </td>
            </tr>
        </tbody>
    </table>
    <table class="border">
        {{-- <thead>
            <tr>
                <td width="20px">
                    Hari, Tanggal
                </td>
                <td colspan="5">: {{ date('l, d F Y', strtotime($date)) }}</td>
            </tr>
            <tr>
                <td>
                    Shift
                </td>
                <td colspan="5">: </td>
            </tr>
            <tr></tr>
        </thead> --}}
        <tbody>
            <tr>
                <th class="text-center font-bold" colspan="6">Stock Barang</th>
            </tr>
            <tr>
                <th class="text-center font-bold center" style="width: 50px">No</th>
                <th class="text-center font-bold center" style="width: 250px">Nama Barang</th>
                <th class="text-center font-bold center" style="width: 80px">Masuk</th>
                <th class="text-center font-bold center" style="width: 80px">Keluar</th>
                <th class="text-center font-bold center" style="width: 80px">Sisa</th>
                <th class="text-center font-bold center" style="width: 300px">Keterangan</th>
            </tr>
            @if (array_key_exists('Barang', $item_stocks->toArray()))
                @forelse ($item_stocks['Barang'] as $key => $item)
                    <tr>
                        <td class="center">{{ $key + 1 }}</td>
                        <td class="">{{ $item->name != null ? $item->name : '-' }}</td>
                        <td class="center">{{ count($item->stocks) > 0 ? $item->stocks[0]->total : 0 }}</td>
                        <td class="center">{{ count($item->stock_reductions) > 0 ? $item->stock_reductions[0]->total : 0 }}</td>
                        <td class="center">{{ (count($item->stocks) > 0 ? $item->stocks[0]->total : 0) - (count($item->stock_reductions) > 0 ? $item->stock_reductions[0]->total : 0) }}</td>
                        {{-- <td>=(C{{ $key + 6 }}-D{{ $key + 6 }})</td> --}}
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center">Tidak ada data</td>
                    </tr>
                @endforelse
            @endif
            <tr>
                <td colspan="6">Keterangan Tambahan :</td>
            </tr>
            <tr></tr>
            @if (array_key_exists('Masakan', $item_stocks->toArray()))
            <tr>
                <th class="text-center font-bold" colspan="6">Stock Masakan</th>
            </tr>
            <tr>
                <th class="text-center font-bold center">No</th>
                <th class="text-center font-bold center">Nama Barang</th>
                <th class="text-center font-bold center">Masuk</th>
                <th class="text-center font-bold center">Keluar</th>
                <th class="text-center font-bold center">Sisa</th>
                <th class="text-center font-bold center">Keterangan</th>
            </tr>
                @forelse ($item_stocks['Masakan'] as $key => $item)
                    <tr>
                        <td class="center">{{ $key + 1 }}</td>
                        <td class="">{{ $item->name != null ? $item->name : '-' }}</td>
                        <td class="center">{{ count($item->stocks) > 0 ? $item->stocks[0]->total : 0 }}</td>
                        <td class="center">{{ count($item->stock_reductions) > 0 ? $item->stock_reductions[0]->total : 0 }}</td>
                        <td class="center">{{ (count($item->stocks) > 0 ? $item->stocks[0]->total : 0) - (count($item->stock_reductions) > 0 ? $item->stock_reductions[0]->total : 0) }}</td>
                        {{-- <td>=(C{{ $key + 10 + count($item_stocks['Barang']) }}-D{{ $key + 10 + count($item_stocks['Barang']) }})</td> --}}
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center">Tidak ada data</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="6">Keterangan Tambahan :</td>
                </tr>
                @endif
        </tbody>
    </table>
</body>

</html>
