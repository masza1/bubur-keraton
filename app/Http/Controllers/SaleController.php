<?php

namespace App\Http\Controllers;

use App\Exports\IncomeReportExcel;
use App\Models\Income;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
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
        $incomes = Income::when($date == null, function ($q) {
            $q->whereDate('created_at', Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        }, function ($q) use ($date) {
            $q->whereDate('created_at', $date);
        })->get();
        if (request()->ajax()) {
            $table_income = view('components.incomes.table-incomes', compact('incomes', 'showButton'))->render();
            return [
                'table_income' => $table_income,
            ];
        }
        $items = Item::get();
        return view('income.index', [
            'incomes' => $incomes,
            'items' => $items,
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
            $showButton = true;
            $tab = $request->tab;
            if ($tab == 'item') {
                $validated = $request->validate([
                    'name' => ['required', 'array'],
                    'name.*' => ['required', 'string', 'max:255'],
                    'price' => ['required', 'array'],
                    'price.*' => ['required', 'numeric'],
                ]);
                $data = [];
                foreach ($validated['name'] as $key => $value) {
                    array_push($data, [
                        'name' => $validated['name'][$key],
                        'price' => $validated['price'][$key],
                        'created_at' => Carbon::now('Asia/Jakarta'),
                        'updated_at' => Carbon::now('Asia/Jakarta'),
                    ]);
                }
                if (Item::insert($data)) {
                    $items = Item::get();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menyimpan data',
                        'items' => $items,
                    ];
                }
                return abort(500, 'Query failed');
            } else if ($tab == 'income') {
                $validated = $request->validate([
                    'platform' => ['required', 'array'],
                    'platform.*' => ['required', 'numeric', 'in:1,2,3,4'],
                    'item_id' => ['required', 'array'],
                    'item_id.*' => ['required', 'numeric'],
                    'quantity' => ['required', 'array'],
                    'quantity.*' => ['required', 'numeric'],
                ]);
                $data = [];
                foreach ($validated['platform'] as $key => $value) {
                    array_push($data, [
                        'platform' => $validated['platform'][$key],
                        'item_id' => $validated['item_id'][$key],
                        'quantity' => $validated['quantity'][$key],
                        'date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                        'created_at' => Carbon::now('Asia/Jakarta'),
                        'updated_at' => Carbon::now('Asia/Jakarta'),
                    ]);
                }
                if (Income::insert($data)) {
                    $incomes = Income::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                    $view = view('components.incomes.table-incomes', compact('incomes', 'showButton'))->render();
                    return [
                        'status' => 200,
                        'message' => 'Berhasil menyimpan data',
                        'view' => $view,
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
        //
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
            $showButton = true;
            $validated = $request->validate([
                'platform' => ['required', 'numeric', 'in:1,2,3,4'],
                'item_id' => ['required', 'numeric'],
                'quantity' => ['required', 'numeric'],
            ]);
            if (Income::where('id', $id)->update($validated)) {
                $incomes = Income::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                $view = view('components.incomes.table-incomes', compact('incomes', 'showButton'))->render();
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
            if (Income::where('id', $id)->delete()) {
                $incomes = Income::whereDate('date',  Carbon::now('Asia/Jakarta')->format('Y-m-d'),)->get();
                $view = view('components.incomes.table-incomes', compact('incomes', 'showButton'))->render();
                return [
                    'status' => 200,
                    'message' => 'Berhasil menyimpan data',
                    'view' => $view,
                    'contentTable' => 'contentTableIncome',
                ];
            }
            return abort(500, 'Query failed');
        }
        return abort(404, 'Halaman tidak ditemukan');
    }

    public function reportIncomes($date)
    {
        $items = Item::with(['incomes' => function ($q) use ($date) {
            $q->without('item')
                ->select('id', 'item_id', 'date', 'platform', DB::raw('sum(quantity) as total'))
                ->whereDate('date', $date)
                ->groupBy(['item_id', 'platform']);
        }])->whereHas('incomes', function ($q) use ($date) {
            $q->whereDate('date', $date);
        })->get();
        foreach ($items as $key => $value) {
            if (count($value->incomes) > 0) {
                array_map(function ($item) use($items, $key) {
                    if($item['platform'] == 1){
                        $items[$key]->setAttribute('offline', $item['total']);
                    } else if ($item['platform'] == 2) {
                        $items[$key]->setAttribute('gojek', $item['total']);
                    } else if ($item['platform'] == 3) {
                        $items[$key]->setAttribute('grab', $item['total']);
                    } else if ($item['platform'] == 4) {
                        $items[$key]->setAttribute('shopeefood', $item['total']);
                    }
                }, $value->incomes->toArray());
            }
        }
        if(request()->type == 'view'){
            return view('income.report-incomes', [
                'items' => $items,
                'date' => $date,
            ]);
        }else{
            return (new IncomeReportExcel($items, $date))->download('laporan_pemasukan_' . date('d_M_Y', strtotime($date)) . '.xlsx');
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
            $items = Item::with(['incomes' => function ($q) use ($startDate, $endDate) {
                $q->without('item')
                    ->select('id', 'item_id', 'date', 'platform', DB::raw('sum(quantity) as total'))
                    ->whereBetween('date', [$startDate, $endDate])
                    ->groupBy(['item_id', 'platform']);
            }])/* ->whereHas('incomes', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            }) */->get();
            foreach ($items as $key => $value) {
                if (count($value->incomes) > 0) {
                    array_map(function ($item) use ($items, $key) {
                        if ($item['platform'] == 1) {
                            $items[$key]->setAttribute('offline', $item['total']);
                        } else if ($item['platform'] == 2) {
                            $items[$key]->setAttribute('gojek', $item['total']);
                        } else if ($item['platform'] == 3) {
                            $items[$key]->setAttribute('grab', $item['total']);
                        } else if ($item['platform'] == 4) {
                            $items[$key]->setAttribute('shopeefood', $item['total']);
                        }
                    }, $value->incomes->toArray());
                }
            }
            if (request()->type == 'view') {
                return view('income.report-incomes', [
                    'items' => $items,
                    'date' => $startDate,
                ]);
            } else {
                return (new IncomeReportExcel($items, $startDate))->download('laporan_pemasukan_' . date('d_M_Y', strtotime($startDate)) . '.xlsx');
            }
        }
    }
}
