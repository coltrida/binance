<?php

use App\Http\Controllers\CoinController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\TradeController;
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

Route::get('/',  [FrontController::class, 'index'])->name('index');
Route::middleware(['auth'])->group(function (){
    Route::get('info', [FrontController::class, 'info'])->name('info');
    Route::resource('platform', PlatformController::class);
    Route::resource('trade', TradeController::class);
    Route::resource('coin', CoinController::class);
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
