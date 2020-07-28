<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

if (!session_id()) session_start();
Route::post('/login', ['uses' => 'Auth@login']);

Route::post('/register', ['uses' => 'Auth@register']);

Route::get('/logout', function () {
    if (!isset($_SESSION['userdata']))
        return response("Anda tidak memiliki akses!", 401);
    unset($_SESSION['userdata']);
    return response(['message' => 'loging out']);
});

Route::get('/kota', function () {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/city",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            'key: 0c1d1e3a19e74a81cb7894ab582885e6',
            'Access-Control-Allow-Origin:http://localhost:8000'
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    if (empty($err))
        return response($response);
});

Route::get('/cost/{origin}/{dest}/{weight}/{courier}', function ($origin, $dest, $weight, $courier) {
    $origin = intval($origin);
    $dest = intval($dest);
    $weight = intval($weight);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "origin=$origin&destination=$dest&weight=$weight&courier=$courier",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            'key: 0c1d1e3a19e74a81cb7894ab582885e6',
            'Access-Control-Allow-Origin:http://localhost:8000'
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if (empty($err))
        return response($response);
});

Route::resources(['product' => 'Product', 'ulasan' => 'Ulasan', 'transaksi' => 'Transaksi']);
Route::get('/product/ulasan/{product}', ['uses' => 'Ulasan@index']);
Route::get('/transaksi/{status}/{pembeli}/{opsi}', ['uses' => 'Transaksi@transaksi']);
Route::get('/notif/count/{user}', function ($user) {
    return DB::table('notif')->where('pembaca', $user)->orderBy('tanggal', 'desc')->get();
});
Route::put('/notif/baca/{id}', function ($id) {
    return DB::table('notif')->where('id', $id)->update(['tanggal_baca' => date('Y-m-d H:i:s')]);
});

Route::get('/product/list/{username}', function ($username) {
    if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'pedagang')
        return response("Anda tidak memiliki akses!", 401);
    return DB::table('product')->where('owner', $username)->get();
});

Route::put('/akun/update/', function () {
    if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'pembeli')
        return response('Anda tidak memiliki akses!', 401);
    try{
        DB::table('users')->where('username', $_SESSION['userdata']['username'])->update(['role' => 'pedagang']);
        return response(['message' => 'Berhasil, sekarang anda adalah pedagang']);
    }catch(Throwable $err){
        return response(['message' => 'Terjadi kesalahan', 'err' => $err]);
    }
});
