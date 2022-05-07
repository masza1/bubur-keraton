<?php

namespace App\Http\Controllers;

use App\Exports\StockReportExcel;
use App\Models\ItemStock;
use App\Models\Stock;
use App\Models\StockReduction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
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
        $stocks = Stock::when($date == null, function ($q) {
            $q->whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        }, function ($q) use ($date) {
            $q->whereDate('date',  $date);
        })->get();
        $reductions = StockReduction::when($date == null, function ($q) {
            $q->whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        }, function ($q) use ($date) {
            $q->whereDate('date',  $date);
        })->get();

        if (request()->ajax()) {
            $table_stock = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
            $table_reduction = view('components.stock.table-reduction', compact('reductions', 'showButton'))->render();
            return [
                'table_stock' => $table_stock,
                'table_reduction' => $table_reduction,
            ];
        }
        $item_stocks = ItemStock::get();
        return view('stock.index', [
            'stocks' => $stocks,
            'reductions' => $reductions,
            'item_stocks' => $item_stocks,
            'showButton' => $showButton,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $tab = $request->tab;
            if ($tab == 'add-stock') {
                $showButton = true;
                $validated = $request->validate([
                    'item_stock_id' => ['required', 'array'],
                    'item_stock_id.*' => ['required', 'numeric'],
                    'stock' => ['required', 'array'],
                    'stock.*' => ['required', 'numeric'],
                ]);
                $data = [];
                foreach ($validated['item_stock_id'] as $key => $value) {
                    array_push($data, [
                        'item_stock_id' => $validated['item_stock_id'][$key],
                        'stock' => $validated['stock'][$key],
                        'date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                        'created_at' => Carbon::now('Asia/Jakarta'),
                        'updated_at' => Carbon::now('Asia/Jakarta'),
                    ]);
                }

                if (Stock::insert($data)) {
                    $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                    $table_stock = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menyimpan data',
                        'table_stock' => $table_stock,
                    ];
                }
                return abort(500, 'Query failed');
            } else if ($tab == 'add-item-stock') {
                $date = $request->date;
                $validated = $request->validate([
                    'name' => ['required', 'array'],
                    'name.*' => ['required', 'string', 'max:255'],
                    'type' => ['required', 'array'],
                    'type.*' => ['required', 'numeric', 'in:1,2']
                ]);
                $data = [];
                foreach ($validated['name'] as $key => $value) {
                    array_push($data, [
                        'name' => $validated['name'][$key],
                        'type' => $validated['type'][$key],
                        'created_at' => Carbon::now('Asia/Jakarta'),
                        'updated_at' => Carbon::now('Asia/Jakarta'),
                    ]);
                }
                if (ItemStock::insert($data)) {
                    $item_stocks = ItemStock::get();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menyimpan data',
                        'item_stocks' => $item_stocks,
                    ];
                }
                return abort(500, 'Query failed');
            } else if ($tab == 'reduction-stock') {
                $showButton = true;
                $validated = $request->validate([
                    'item_stock_id' => ['required', 'array'],
                    'item_stock_id.*' => ['required', 'numeric'],
                    'expense' => ['required', 'array'],
                    'expense.*' => ['required', 'numeric'],
                    'description' => ['nullable', 'array'],
                    'description.*' => ['nullable', 'string', 'max:255'],
                ]);
                // $stocks = Stock::without('item_stock')
                //     ->select('id', 'item_stock_id', 'date', DB::raw('sum(stock) as total'))
                //     ->whereIn('item_stock_id', $validated['item_stock_id'])
                //     ->whereDate('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'))
                //     ->groupby('item_stock_id')
                //     ->get();
                $data = [];
                foreach ($validated['item_stock_id'] as $key => $value) {
                    array_push($data, [
                        'item_stock_id' => $validated['item_stock_id'][$key],
                        'expense' => $validated['expense'][$key],
                        'description' => $validated['description'][$key],
                        'date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                        'created_at' => Carbon::now('Asia/Jakarta'),
                        'updated_at' => Carbon::now('Asia/Jakarta'),
                    ]);
                }
                if (StockReduction::insert($data)) {
                    $reductions = StockReduction::whereDate('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->get();
                    $table_reduction = view('components.stock.table-reduction', compact('reductions', 'showButton'))->render();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menyimpan data',
                        'table_reduction' => $table_reduction,
                    ];
                }
                return abort(500, 'Query failed');
            }
        }
        return abort(404, 'Halaman tidak ditemukan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            $tab = $request->tab;
            $showButton = true;
            if ($tab == 'stock') {
                $validated = $request->validate([
                    'item_stock_id' => ['required', 'numeric'],
                    'stock' => ['required', 'numeric'],
                ]);

                if (Stock::where('id', $id)->update($validated)) {
                    $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                    $table_stock = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil mengubah data',
                        'table_stock' => $table_stock,
                    ];
                }
                return abort(500, 'Query failed');
            } else if ($tab == 'reduction') {
                $validated = $request->validate([
                    'item_stock_id' => ['required', 'numeric'],
                    'expense' => ['required', 'numeric'],
                    'description' => ['nullable', 'string'],
                ]);
                if (StockReduction::where('id', $id)->update($validated)) {
                    $reductions = StockReduction::whereDate('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->get();
                    $table_reduction = view('components.stock.table-reduction', compact('reductions', 'showButton'))->render();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menyimpan data',
                        'table_reduction' => $table_reduction,
                    ];
                }
                return abort(500, 'Query failed');
            }
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
            $tab = request()->tab;
            $showButton = true;
            if ($tab == 'stock') {
                if (Stock::where('id', $id)->delete()) {
                    $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                    $view = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menghapus data',
                        'view' => $view,
                        'contentTable' => 'contentTableStock',
                    ];
                }
                return abort(500, 'Query failed');
            } else if ($tab == 'reduction') {
                if (StockReduction::where('id', $id)->delete()) {
                    $reductions = StockReduction::whereDate('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->get();
                    $view = view('components.stock.table-reduction', compact('reductions', 'showButton'))->render();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menghapus data',
                        'view' => $view,
                        'contentTable' => 'contentTableReduction',
                    ];
                }
                return abort(500, 'Query failed');
            }
        }
        return abort(404, 'Halaman tidak ditemukan');
    }

    public function reportStock($date)
    {
        $item_stocks = ItemStock::with(['stocks' => function ($q) use ($date) {
            $q->select('id', 'item_stock_id', 'date', DB::raw('sum(stock) as total'))
                ->whereDate('date', $date)
                ->groupby('item_stock_id');
        }, 'stock_reductions' => function ($q) use ($date) {
            $q->select('id', 'item_stock_id', 'date', DB::raw('sum(expense) as total'))
                ->whereDate('date', $date)
                ->groupby('item_stock_id');
        }])->get()->groupBy(function ($q) {
            if ($q->type == 1) {
                return 'Barang';
            } else {
                return 'Masakan';
            }
        });
        // return $item_stocks;
        // return view('stock.report-stock', [
        //     'date' => $date,
        //     'item_stocks' => $item_stocks,
        // ]);
        return (new StockReportExcel($item_stocks, $date))->download('laporan_stock_' . date('d_M_Y', strtotime($date)) . '.xlsx');
    }

    public function getPrevStock()
    {
        if (request()->ajax()) {
            $date = request()->date;
            if ($date != null) {
                $subdate = Carbon::parse($date, 'Asia/Jakarta')->subDays(1);
                $item_stocks = ItemStock::with(['stocks' => function ($q) use ($subdate) {
                    $q->select('id', 'item_stock_id', 'date', DB::raw('sum(stock) as total'))
                        ->whereDate('date', $subdate)
                        ->groupby('item_stock_id');
                }, 'stock_reductions' => function ($q) use ($subdate) {
                    $q->select('id', 'item_stock_id', 'date', DB::raw('sum(expense) as total'))
                        ->whereDate('date', $subdate)
                        ->groupby('item_stock_id');
                }])->get();
                $data = [];
                foreach ($item_stocks as $key => $item) {
                    $stock = 0;
                    $reduction = 0;
                    if (count($item->stocks) > 0) {
                        $stock = $item->stocks[0]['total'];
                    }
                    if (count($item->stock_reductions) > 0) {
                        $reduction = $item->stock_reductions[0]['total'];
                    }
                    if($stock != 0 && $reduction != 0){
                        array_push($data, [
                            'item_stock_id' => $item->id,
                            'stock' => $stock - $reduction,
                            'date' => $date,
                            'created_at' => Carbon::parse($date,'Asia/Jakarta')->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::parse($date,'Asia/Jakarta')->format('Y-m-d H:i:s'),
                        ]);
                    }
                }
                if (Stock::insert($data)) {
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
                    $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                    $table_stock = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menyimpan data',
                        'table_stock' => $table_stock,
                    ];
                }
                return abort(500, 'Query failed');
            }
            return abort(500, 'Error');
        }
        return abort(404, 'Halaman tidak ditemukan');
    }
}
