<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PrometheusController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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
})->name('welcome');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/logout', function() {
        Session::flush();
        Auth::logout();
        return redirect()->route('welcome');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/shorturl/qrcode', [ShortUrlController::class, 'getQRCode']);
    Route::resource('shorturl', ShortUrlController::class);
});

Route::get('/shorturl/qrcode', [ShortUrlController::class, 'getQRCode'])->middleware(['auth']);

Route::resource('shorturl', ShortUrlController::class)->middleware(['auth']);

Route::get('/s/{token}', [ShortUrlController::class, 'redirect'])->where('name', '[A-Za-z0-9]+');
Route::get('/metrics', [PrometheusController::class, 'metrics']);

# Google Auth routes
Route::get('/google-auth/redirect', [GoogleAuthController::class, 'redirect'])->name("google.redirect");
 
Route::get('/google-auth/callback', [GoogleAuthController::class, 'callback'])->name("google.callback");

require __DIR__ . '/auth.php';
