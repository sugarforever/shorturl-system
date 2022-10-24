<?php

use App\Http\Controllers\PrometheusController;
use App\Http\Controllers\ShortUrlController;
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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/shorturl/qrcode', [ShortUrlController::class, 'getQRCode'])->middleware(['auth']);

Route::resource('shorturl', ShortUrlController::class)->middleware(['auth']);

Route::get('/s/{token}', [ShortUrlController::class, 'redirect'])
    ->where('name', '[A-Za-z0-9]+');

Route::get('/metrics', [PrometheusController::class, 'metrics']);
require __DIR__ . '/auth.php';
