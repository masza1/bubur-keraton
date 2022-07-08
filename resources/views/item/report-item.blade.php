<style>
        table.border,
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
<table class="border">
    <thead>
        <tr>
            <td colspan="4">Laporan Tanggal : {{ $date }}</td>
        </tr>
        <tr>
            <th class="text-center font-bold center" style="width: 50px">No</th>
            <th class="text-center font-bold center" style="width: 250px">Nama Barang</th>
            <th class="text-center font-bold center" style="width: 80px">Kuantiti</th>
            <th class="text-center font-bold center" style="width: 100px">Total harga</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grand_total = 0;
        @endphp
        @foreach ($data as $key =>$item)
            <tr>
                <td class="center">{{ $key+1 }}</td>
                <td class="center">{{ $item->item }}</td>
                <td class="center">{{ $item->quantity }}</td>
                <td class="center">{{ $item->price }}</td>
                @php
                    $grand_total += $item->price
                @endphp
            </tr>
        @endforeach
        <tr>
            <td colspan="3">Grand Total</td>
            <td class="center">{{ $grand_total }}</td>
        </tr>
    </tbody>
</table>