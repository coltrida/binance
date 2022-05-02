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
Route::get('/controllo',  [FrontController::class, 'controllo']);
Route::get('/info',  [FrontController::class, 'info']);

Route::middleware(['auth'])->group(function (){
    Route::get('info', [FrontController::class, 'info'])->name('info');
    Route::resource('platform', PlatformController::class);
    Route::resource('trade', TradeController::class);
    Route::get('aperture', [TradeController::class, 'aperture'])->name('aperture.trade');
    Route::post('aperture', [TradeController::class, 'apertureStore'])->name('aperture.store');
    Route::resource('coin', CoinController::class);
    Route::get('valutazioni', [CoinController::class, 'valutazioni'])->name('coin.valutazioni');
    Route::post('valutazioni', [CoinController::class, 'eseguiValutazioni'])->name('coin.esegui.valutazioni');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
