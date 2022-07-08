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
        table.border td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }

        td.center,
        th.center {
            text-align: center;
        }
    </style>
</head>

<body>
    <table class="border">
        <thead>
            <tr>
                <th class="text-center font-bold" colspan="7">Pemasukan</th>
            </tr>
            <tr>
                <th rowspan="2" class="text-center font-bold">No</th>
                <th rowspan="2" class="text-center font-bold">Nama Produk</th>
                <th rowspan="2" class="text-center font-bold">Price</th>
                <th rowspan="1" colspan="4" class="text-center font-bold">Jumlah</th>
                <th rowspan="2" class="text-center font-bold">Total</th>
            </tr>
            <tr>
                <th class="text-center font-bold">Offline</th>
                <th class="text-center font-bold">Gojek</th>
                <th class="text-center font-bold">Grab</th>
                <th class="text-center font-bold">Shopeefood</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $key => $item)
                @php
                    $index = $key + 4;
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->name != null ? $item->name : '-' }}</td>
                    <td>{{ $item->price != null ? $item->price : '0' }}</td>
                    <td>{{ $item->offline != null ? $item->offline : '0' }}</td>
                    <td>{{ $item->gojek != null ? $item->gojek : '0' }}</td>
                    <td>{{ $item->grab != null ? $item->grab : '0' }}</td>
                    <td>{{ $item->shopeefood != null ? $item->shopeefood : '0' }}</td>
                    <td>{{ ($item->offline != null ? $item->offline : 0) + ($item->gojek != null ? $item->gojek : 0) + ($item->grab != null ? $item->grab : 0) + ($item->shopeefood != null ? $item->shopeefood : 0) }}</td>
                    {{-- <td>=D{{ $index }}+E{{ $index }}+F{{ $index }}+G{{ $index }}</td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data</td>
                </tr>
            @endforelse
            <tr>
                <td></td>
                <td></td>
                <td colspan="2">
                    @php
                        $totals = 0;
                    @endphp
                    @foreach ($items as $val)
                        @if (($offline = $val->offline != null ? $val->offline : 0) > 0)
                            @php
                                $totals = $totals + $val->price * $offline;
                            @endphp
                        @endif
                    @endforeach
                    {{ $totals }}
                </td>
            </tr>
            @php
                $grand_total = 0;
            @endphp
            @foreach ($buy_items as $key => $item)
                @php
                    $grand_total += $item->price;
                @endphp
            @endforeach
            <tr>
                <td colspan="2">Total Belanja : </td>
                <td class="text-left">{{ $grand_total }}</td>
                
            </tr>
            <tr></tr>
            <tr>
                <td colspan="2">Pemasukan Bersih :</td>
                <td class="text-left">{{ $totals - $grand_total }}</td>
                
            </tr>
        </tbody>
    </table>
    <table></table>
</body>

</html>
