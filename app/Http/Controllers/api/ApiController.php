<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockReduction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function get_stock(){
        // return abort(500, "abc");
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

        return response()->json(['stocks' => $stocks, 'reductions' => $reductions, 'date' => $date, 'showButton' => $showButton], 200);
    }
}
