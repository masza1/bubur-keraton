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
                <td>
                    Hari, Tanggal
                </td>
                <td>: {{ date('l, d F Y', strtotime($date)) }}</td>
            </tr>
            <tr>
                <td>
                    Shift
                </td>
                <td>: </td>
            </tr>
            <tr></tr>
        </thead>
        <tbody>
            <tr>
                <th class="text-center font-bold" colspan="6">Stock Barang</th>
            </tr>
            <tr>
                <th class="text-center font-bold">No</th>
                <th class="text-center font-bold">Nama Barang</th>
                <th class="text-center font-bold">Masuk</th>
                <th class="text-center font-bold">Keluar</th>
                <th class="text-center font-bold">Sisa</th>
                <th class="text-center font-bold">Keterangan</th>
            </tr>
            @if (array_key_exists('Barang', $item_stocks->toArray()))
                @forelse ($item_stocks['Barang'] as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->name != null ? $item->name : '-' }}</td>
                        <td>{{ count($item->stocks) > 0 ? $item->stocks[0]->total : '0' }}</td>
                        <td>{{ count($item->stock_reductions) > 0 ? $item->stock_reductions[0]->total : '0' }}</td>
                        <td>=(C{{ $key + 6 }}-D{{ $key + 6 }})</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak ada data</td>
                    </tr>
                @endforelse
            @endif
            <tr>
              <td>Keterangan Tambahan :</td>
            </tr>
            <tr></tr>
            <tr>
                <th class="text-center font-bold" colspan="6">Stock Masakan</th>
            </tr>
            <tr>
                <th class="text-center font-bold">No</th>
                <th class="text-center font-bold">Nama Barang</th>
                <th class="text-center font-bold">Masuk</th>
                <th class="text-center font-bold">Keluar</th>
                <th class="text-center font-bold">Sisa</th>
                <th class="text-center font-bold">Keterangan</th>
            </tr>
            @if (array_key_exists('Masakan', $item_stocks->toArray()))
                @forelse ($item_stocks['Masakan'] as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->name != null ? $item->name : '-' }}</td>
                        <td>{{ count($item->stocks) > 0 ? $item->stocks[0]->total : '0' }}</td>
                        <td>{{ count($item->stock_reductions) > 0 ? $item->stock_reductions[0]->total : '0' }}</td>
                        <td>=(C{{ $key + 10 + count($item_stocks['Barang']) }}-D{{ $key + 10 + count($item_stocks['Barang']) }})</td>
                        <td></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak ada data</td>
                    </tr>
                @endforelse
            @endif
            <tr>
              <td>Keterangan Tambahan :</td>
            </tr>
            <tr></tr>
            <tr>
              <td>OVO :</td>
            </tr>
            <tr>
              <td>Voucher:</td>
            </tr>
            <tr></tr>
            <tr></tr>
            <tr>
              <td>Uang Cash : (Uang Riil - Uang Belanja - modal Untuk Kembalian) + Uang Yang Dipinjam : Rp.</td>
            </tr>
            <tr>
              <td>*Uang dimasukkan ke dalam amplop:</td>
            </tr>
            <tr>
              <td>*Uang Modal Untuk kembalian masukkan lagi ke Shift Berikutnya !</td>
            </tr>
            <tr></tr>
            <tr></tr>
            <tr>
              <td>Uang Belanja :</td>
            </tr>
            <tr>
              <td>Modal untuk kembalian :</td>
            </tr>
            <tr>
              <td>Uang sisa belanja : </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
