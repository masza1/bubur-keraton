<?php

namespace App\Http\Controllers;

use App\Exports\BuyItemExport;
use App\Models\BuyItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuyItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = request()->date == null ? null : date('Y-m-d', strtotime(request()->date));
        $showButton = false;
        if ($date == null) {
            $showButton = true;
        } else {
            if ($date == Carbon::now('Asia/Jakarta')->format('Y-m-d')) {
                $showButton = true;
            } else {
                $showButton = false;
            }
        }
        $buy_items = BuyItem::when($date == null, function ($q) {
            $q->whereDate('created_at', Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        }, function ($q) use ($date) {
            $q->whereDate('created_at', $date);
        })->get();
        if (request()->ajax()) {
            $table_item = view('components.item.table-item', ['buyItems' => $buy_items, 'showButton' => $showButton])->render();
            return [
                'table_item' => $table_item,
            ];
        }
        return view('item.index', [
            'buy_items' => $buy_items,
            'showButton' => $showButton,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $showButton = true;
            $validated = $request->validate([
                'item' => ['required', 'array'],
                'item.*' => ['required', 'string', 'max:255'],
                'quantity' => ['required', 'array'],
                'quantity.*' => ['required', 'string'],
                'price' => ['required', 'array'],
                'price.*' => ['required', 'numeric'],
            ]);
            $data = [];
            foreach ($validated['item'] as $key => $value) {
                array_push($data, [
                    'item' => $validated['item'][$key],
                    'quantity' => $validated['quantity'][$key],
                    'price' => $validated['price'][$key],
                    'date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                    'created_at' => Carbon::now('Asia/Jakarta'),
                    'updated_at' => Carbon::now('Asia/Jakarta'),
                ]);
            }
            if (BuyItem::insert($data)) {
                $buyItems = BuyItem::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                $view = view('components.item.table-item', compact('buyItems', 'showButton'))->render();
                return [
                    'status' => 200,
                    'message' => 'Berhasil menyimpan data',
                    'table_item' => $view,
                ];
            }
            return abort(500, 'Query failed');
        }
        return abort(404, 'Halaman tidak ditemukan');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $showButton = true;
            $validated = $request->validate([
                'item' => ['required', 'string', 'max:255'],
                'quantity' => ['required', 'string'],
                'price' => ['required', 'numeric'],
            ]);
            if (BuyItem::where('id', $id)->update($validated)) {
                $buyItems = BuyItem::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                $view = view('components.item.table-item', compact('buyItems', 'showButton'))->render();
                return [
                    'status' => 200,
                    'message' => 'Berhasil menyimpan data',
                    'view' => $view,
                ];
            }
            return abort(500, 'Query failed');
        }
        return abort(404, 'Halaman tidak ditemukan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            $showButton = true;
            if (BuyItem::where('id', $id)->delete()) {
                $buyItems = BuyItem::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                $view = view('components.item.table-item', compact('buyItems', 'showButton'))->render();
                return [
                    'status' => 200,
                    'message' => 'Berhasil menyimpan data',
                    'view' => $view,
                    'contentTable' => 'contentTableItem',
                ];
            }
            return abort(500, 'Query failed');
        }
        return abort(404, 'Halaman tidak ditemukan');
    }

    public function reportItems($date)
    {
        $data = BuyItem::whereDate('date', date('Y-m-d', strtotime($date)))
            ->get();
        // return $items;
        if (request()->type == 'view') {
            return view('item.report-item', [
                'data' => $data,
                'date' => $date,
            ]);
        } else {
            return (new BuyItemExport($data, $date))->download('laporan_daftar_belanja_' . date('d_M_Y', strtotime($date)) . '.xlsx');
        }
    }

    public function printPerMonth()
    {
        $month = request()->month;
        $year = request()->year;

        if ($month == null || $year == null) {
            return abort(501, 'Bulan atau Tahun tidak boleh kosong');
        } else {
            $startDate = Carbon::parse($year . '-' . $month . '-01', 'Asia/Jakarta')->format('Y-m-d');
            $endDate = Carbon::parse($year . '-' . ($month + 1) . '-01', 'Asia/Jakarta')->subDays()->format('Y-m-d');
            $data = BuyItem::whereBetween('date', [$startDate, $endDate])->get();
            
            if (request()->type == 'view') {
                return view('item.report-item', [
                    'data' => $data,
                    'date' => $startDate,
                ]);
            } else {
                return (new BuyItemExport($data, $startDate))->download('laporan_daftar_belanja_' . date('d_M_Y', strtotime($startDate)) . '.xlsx');
            }
        }
    }
}
