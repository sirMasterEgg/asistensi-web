<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use Symfony\Component\Console\Input\Input;

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

/**
 * nyalain xampp
 * php artisan serve
 * lt --port 8000 --subdomain asistensi
 */

Route::get('/', [SiteController::class, 'index']);
Route::post('/', [SiteController::class, 'post'])->name('post');

Route::get('/req', [SiteController::class, 'requestPage']);
Route::post('/req', [SiteController::class, 'requestToken'])->name('requestToken');

Route::get('/fetch-absen', [SiteController::class, 'fetchAbsen']);

Route::get('/hashed', function (Request $request) {
    return Hash::make($request->keyword);
});
