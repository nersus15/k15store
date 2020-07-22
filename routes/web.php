<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isPedagang;
use App\Http\Middleware\notAdmin;
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

if (!session_id()) session_start();
// URL::forceSchema('https');
Route::get('/', ['uses' => 'Pembeli@index']);
Route::get('/product/{id}', ['uses' => 'Pembeli@detailProduct']);
Route::middleware([notAdmin::class])->group(function(){
    Route::get('/keranjang', ['uses' => 'Pembeli@keranjang']);
    Route::get('/riwayat', ['uses' => 'Pembeli@riwayat']);
});

Route::middleware([isAdmin::class])->group(function () {
    Route::get('/admin', ['uses' => 'Admin@index']);
    Route::get('/admin/order', ['uses' => 'Admin@order']);
    Route::get('/admin/transaksi', ['uses' => 'Admin@transaksi']);
});

Route::middleware([isPedagang::class])->group(function(){
    Route::get('/toko', ['uses' => 'Pedagang@index']);
});