<?php

namespace App\Http\Controllers;

use App\Exports\StockReportExcel;
use App\Models\ItemStock;
use App\Models\Stock;
use App\Models\StockReduction;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
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
                        'remaining_stock' => $validated['stock'][$key],
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
                DB::beginTransaction();
                try {
                    $data = [];
                    $err = [];
                    $updateStocks = [];
                    foreach ($validated['item_stock_id'] as $key => $value) {
                        $stock = Stock::with('item_stock:id,name')
                            ->where('item_stock_id', $validated['item_stock_id'][$key])
                            ->whereDate('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'))
                            ->where('remaining_stock', '>=', $validated['expense'][$key])
                            ->first();
                        if ($stock) {
                            array_push($updateStocks, [
                                'id' => $stock->id,
                                'remaining_stock' => $stock->remaining_stock - $validated['expense'][$key],
                            ]);
                            array_push($data, [
                                'item_stock_id' => $validated['item_stock_id'][$key],
                                'stock_id' => $stock->id,
                                'expense' => $validated['expense'][$key],
                                'description' => $validated['description'][$key],
                                'date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                                'created_at' => Carbon::now('Asia/Jakarta'),
                                'updated_at' => Carbon::now('Asia/Jakarta'),
                            ]);
                        } else {
                            array_push($err, [
                                'field' => 'barang ' . ($key + 1),
                                'message' => 'Sisa stock tidak mencukupi'
                            ]);
                        }
                        if (count($updateStocks) < 1) {
                            return abort(501, 'Harap pastikan stock tersedia!');
                        }
                    }
                    Stock::upsert($updateStocks, ['id'], ['remaining_stock']);
                    StockReduction::insert($data);
                    DB::commit();
                    $reductions = StockReduction::whereDate('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->get();
                    $table_reduction = view('components.stock.table-reduction', compact('reductions', 'showButton'))->render();
                    $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                    $table_stock = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menyimpan data',
                        'table_reduction' => $table_reduction,
                        'table_stock' => $table_stock,
                        'errors' => $err
                    ];
                } catch (QueryException $th) {
                    DB::rollBack();
                    return abort(400, $th->getMessage());
                }
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
                if ($stock = Stock::where('id', $id)->where('stock', '!=', $validated['stock'])->first()) {
                    if ($stock->stock < $validated['stock']) {
                        $stock->update([
                            'stock' => $validated['stock'],
                            'remaining_stock' => ($validated['stock'] - $stock->stock + $stock->remaining_stock)
                        ]);
                        $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                        $table_stock = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
                        return [
                            'status' => 200,
                            'message' => 'Berhasil mengubah data',
                            'table_stock' => $table_stock,
                        ];
                    } else {
                        if (($stock->remaining_stock - ($stock->stock - $validated['stock'])) > 0) {
                            $stock->update([
                                'stock' => $validated['stock'],
                                'remaining_stock' => ($stock->remaining_stock - ($stock->stock - $validated['stock']))
                            ]);
                            $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                            $table_stock = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
                            return [
                                'status' => 200,
                                'message' => 'Berhasil mengubah data',
                                'table_stock' => $table_stock,
                            ];
                        }
                        return abort(501, 'Tidak bisa mengubah data karena sisa stock menjadi ' . ($stock->remaining_stock - ($stock->stock - $validated['stock'])));
                    }
                }
                return abort(501, 'jumlah stock harus berbeda dari sebelumnya');
            } else if ($tab == 'reduction') {
                $validated = $request->validate([
                    'item_stock_id' => ['required', 'numeric'],
                    'expense' => ['required', 'numeric'],
                    'description' => ['nullable', 'string'],
                ]);
                if ($reduction = StockReduction::where('id', $id)->first()) {
                    DB::beginTransaction();
                    try {
                        $stock = $reduction->stock;
                        if ($reduction->expense == $validated['expense']) {
                            return abort(501, 'jumlah harus berbeda dari sebelumnya');
                        }
                        if ($reduction->expense < $validated['expense']) {
                            if ((($stock->remaining_stock) - ($validated['expense'] - $reduction->expense)) >= 0) {
                                $stock->update(['remaining_stock' => ($stock->remaining_stock) - ($validated['expense'] - $reduction->expense)]);
                            } else {
                                return abort(501, 'jumlah pengeluaran melebihi stock yang tersedia');
                            }
                        } else {
                            $stock->update(['remaining_stock' => ($stock->remaining_stock) + ($reduction->expense - $validated['expense'])]);
                        }
                        DB::commit();
                        $reduction->update($validated);
                        $reductions = StockReduction::whereDate('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->get();
                        $table_reduction = view('components.stock.table-reduction', compact('reductions', 'showButton'))->render();
                        $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                        $table_stock = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
                        return [
                            'status' => 200,
                            'message' => 'Berhasil menyimpan data',
                            'table_reduction' => $table_reduction,
                            'table_stock' => $table_stock,
                        ];
                    } catch (QueryException $th) {
                        DB::rollBack();
                        return abort(501, $th->getMessage());
                    }
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
                if ($stock = Stock::where('id', $id)->first()) {
                    if ($stock->stock != $stock->remaining_stock) {
                        return abort(501, 'Jumlah stock awal dan sisa stock berbeda, anda tidak bisa menghapus data ini');
                    }
                    $stock->delete();
                    $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'))->get();
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
                DB::beginTransaction();
                try {
                    if ($reduction = StockReduction::where('id', $id)->first()) {
                        $stock = $reduction->stock;
                        $stock->update(['remaining_stock' => ($stock->remaining_stock + $reduction->expense)]);
                        $reduction->delete();
                        DB::commit();
                        $reductions = StockReduction::whereDate('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->get();
                        $table_reduction = view('components.stock.table-reduction', compact('reductions', 'showButton'))->render();
                        $stocks = Stock::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'))->get();
                        $table_stock = view('components.stock.table-stock', compact('stocks', 'showButton'))->render();
                        return [
                            'status' => 200,
                            'message' => 'Berhasil menghapus data',
                            'table_reduction' => $table_reduction,
                            'table_stock' => $table_stock,
                            'type' => 'reduction',
                            'contentTable' => 'contentTableReduction',
                        ];
                    }
                    return abort(501, 'Data tidak ada');
                } catch (QueryException $th) {
                    DB::rollBack();
                    return abort(501, $th->getMessage());
                }
            }
        }
        return abort(404, 'Halaman tidak ditemukan');
    }

    public function reportStock($date)
    {
        $item_stocks = ItemStock::with(['stocks' => function ($q) use ($date) {
            $q->select('id', 'item_stock_id', 'date', DB::raw('sum(stock) as total'))
                ->whereDate('date', $date)
                ->where('stock', '>', 0)
                ->groupby('item_stock_id');
        }, 'stock_reductions' => function ($q) use ($date) {
            $q->select('id', 'item_stock_id', 'date', DB::raw('sum(expense) as total'))
                ->whereDate('date', $date)
                ->groupby('item_stock_id');
        }])->whereHas('stocks', function ($q) use ($date) {
            $q->whereDate('date', $date)
                ->where('stock', '>', 0);
        })->get()->groupBy(function ($q) {
            if ($q->type == 1) {
                return 'Barang';
            } else {
                return 'Masakan';
            }
        });
        if (request()->type == 'view') {
            return view('stock.report-stock', [
                'date' => $date,
                'item_stocks' => $item_stocks,
            ]);
        } else {
            return (new StockReportExcel($item_stocks, $date))->download('laporan_stock_' . date('d_M_Y', strtotime($date)) . '.xlsx');
        }
    }

    public function getPrevStock()
    {
        if (request()->ajax()) {
            $date = request()->date;
            if ($date != null) {
                if (Stock::whereDate('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->first()) {
                    return abort(422, 'Tidak bisa tarik data karena terdapat data tanggal saat ini.');
                } else {
                    ///$date = Carbon::parse($date, 'Asia/Jakarta')/* ->subDays(1) */;
                    /* $item_stocks = ItemStock::with(['stocks' => function ($q) use ($date) {
                        $q->select('id', 'item_stock_id', 'date', DB::raw('sum(stock) as total'))
                            ->whereDate('date', $date)
                            ->groupby('item_stock_id');
                    }, 'stock_reductions' => function ($q) use ($date) {
                        $q->select('id', 'item_stock_id', 'date', DB::raw('sum(expense) as total'))
                            ->whereDate('date', $date)
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
                        if($stock != 0 && ($reduction != 0 || $reduction == 0)){
                            array_push($data, [
                                'item_stock_id' => $item->id,
                                'stock' => $stock - $reduction,
                                'date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                                'created_at' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                            ]);
                        }
                    } */
                    $stocks = Stock::whereDate('date', $date)->get();
                    if (count($stocks) > 0) {
                        $data = [];
                        foreach ($stocks as $key => $value) {
                            array_push($data, [
                                'item_stock_id' => $value->item_stock_id,
                                'stock' => $value->remaining_stock,
                                'remaining_stock' => $value->remaining_stock,
                                'date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                                'created_at' => Carbon::now('Asia/Jakarta'),
                                'updated_at' => Carbon::now('Asia/Jakarta')
                            ]);
                        }
                        if (Stock::insert($data)) {
                            return [
                                'status' => 200,
                                'message' => 'Berhasil menyimpan data',
                                'date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                            ];
                        }
                        return abort(500, 'Query failed');
                    } else {
                        return abort(501, 'Tidak ada data ditanggal ini');
                    }
                }
            }
            return abort(500, 'Error');
        }
        return abort(404, 'Halaman tidak ditemukan');
    }

    public function printPerMonth()
    {
        $month = request()->month;
        $year = request()->year;

        if ($month == null || $year == null) {
            return abort(501, 'Bulan atau Tahun tidak boleh kosong');
        } else {
            $startDate = Carbon::parse($year . '-' . $month . '-01', 'Asia/Jakarta')->format('Y-m-d');
            $endDate = Carbon::parse($year . '-' . ($month+1) . '-01', 'Asia/Jakarta')->subDays()->format('Y-m-d');
            $item_stocks = ItemStock::with(['stocks' => function ($q) use ($startDate, $endDate) {
                $q->select('id', 'item_stock_id', 'date', DB::raw('sum(stock) as total'))
                ->whereBetween('date', [$startDate, $endDate])
                ->where('stock', '>', 0)
                ->groupby('item_stock_id');
            }, 'stock_reductions' => function ($q) use ($startDate, $endDate) {
                $q->select('id', 'item_stock_id', 'date', DB::raw('sum(expense) as total'))
                ->whereBetween('date', [$startDate, $endDate])
                ->groupby('item_stock_id');
            }])/* ->whereHas('stocks', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate])
                ->where('stock', '>', 0);
            }) */->get()->groupBy(function ($q) {
                if ($q->type == 1) {
                    return 'Barang';
                } else {
                    return 'Masakan';
                }
            });
            if (request()->type == 'view') {
                return view('stock.report-stock', [
                    'date' => $startDate,
                    'item_stocks' => $item_stocks,
                ]);
            } else {
                return (new StockReportExcel($item_stocks, $startDate))->download('laporan_stock_' . date('d_M_Y', strtotime($startDate)) . '.xlsx');
            }
        }
    }
}
