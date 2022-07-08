<?php

use App\Http\Controllers\BuyItemController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('report-stock/{date}', [StockController::class, 'reportStock']);
    Route::get('report-incomes/{date}', [SaleController::class, 'reportIncomes']);
    Route::get('report-buy-item/{date}', [BuyItemController::class, 'reportItems']);
    Route::get('report-stocks-month', [StockController::class, 'printPerMonth']);
    Route::get('report-incomes-month', [SaleController::class, 'printPerMonth']);
    Route::get('report-buy-item-month', [BuyItemController::class, 'printPerMonth']);
    Route::get('stocks/get-prev-stock', [StockController::class, 'getPrevStock']);

    Route::resource('stocks', StockController::class);
    Route::resource('incomes', SaleController::class);
    Route::resource('buy_items', BuyItemController::class);

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
