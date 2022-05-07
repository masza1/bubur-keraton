<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <title>Sheet 1</title>
</head>

<body>
    <table>
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
                $index = $key + 4
            @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->name != null ? $item->name : '-' }}</td>
                    <td>{{ $item->price != null ? $item->price : '0' }}</td>
                    <td>{{ $item->offline != null ? $item->offline : '0' }}</td>
                    <td>{{ $item->gojek != null ? $item->gojek : '0' }}</td>
                    <td>{{ $item->grab != null ? $item->grab : '0' }}</td>
                    <td>{{ $item->shopeefood != null ? $item->shopeefood : '0' }}</td>
                    <td>=D{{ $index }}+E{{ $index }}+F{{ $index }}+G{{ $index }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data</td>
                </tr>
            @endforelse
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ '#=IFERROR(SUM(INDEX(C4:C'.$index . '*D4:D' . $index . ';;));0)'}}</td>
            </tr>
            <tr>
                <td>Keterangan :</td>
            </tr>
            <tr></tr>
            <tr>
                <td>Jumlah Porsi :</td>
            </tr>
            <tr>
                <td>Tagihan :</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
