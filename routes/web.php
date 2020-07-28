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

Route::get('/profile', function(){
    if(!isset($_SESSION['userdata']))
        return redirect(url('/'));
    $resource = [
        'css' => array(
            // ['pos' => 'head', 'css' => 'css/app.css'],
            ['pos' => 'head', 'css' => 'font/iconsmind/style.css'],
            ['pos' => 'head', 'css' => 'font/simple-line-icons/css/simple-line-icons.css'],
            ['pos' => 'head', 'css' => 'css/vendor/bootstrap.min.css'],
            ['pos' => 'head', 'css' => 'css/vendor/perfect-scrollbar.css'],
            ['pos' => 'head', 'css' => 'css/vendor/dataTables.bootstrap4.min.css'],
            ['pos' => 'head', 'css' => 'css/vendor/datatables.responsive.bootstrap4.min.css'],
            ['pos' => 'head', 'css' => 'css/vendor/component-custom-switch.min.css'],
            ['pos' => 'head', 'css' => 'css/main.css']
        ),
        'js' => array(
            ['pos' => 'head', 'js' => 'js/vendor/jquery-3.3.1.min.js'],
            ['pos' => 'head', 'js' => 'js/vendor/jquery.form.js'],
            ['pos' => 'head', 'js' => 'js/vendor/bootstrap.bundle.min.js'],
            ['pos' => 'body:end', 'js' => 'js/vendor/perfect-scrollbar.min.js'],
            ['pos' => 'body:end', 'js' => 'js/vendor/mousetrap.min.js'],
            ['pos' => 'body:end', 'js' => 'js/vendor/moment.min.js'],
            ['pos' => 'body:end', 'js' => 'js/vendor/datatables.min.js'],
            ['pos' => 'body:end', 'js' => 'js/vendor/datatables.bt.min.js'],
            ['pos' => 'body:end', 'js' => 'js/vendor/bootstrap-notify.min.js'],
            ['pos' => 'body:end', 'js' => 'js/dore.script.js'],
            ['pos' => 'head', 'js' => 'js/vendor/jquery-validation/jquery.validate.js'],
            ['pos' => 'body:end', 'js' => 'js/scripts.js'],
            ['pos' => 'body:end', 'js' => 'js/uihelper.js'],
            ['pos' => 'body:end', 'js' => 'js/modal.config.js'],
            ['pos' => 'body:end', 'js' => 'js/pembeli.main.js'],

        )
    ];

    $data = array(
        'pageTitle' => 'K15 Store | Situs Jual Beli Online',
        'navbar' => 'comp/navbarPengunjung',
        'footer' => 'comp/footerPengunjung',
        'css' => $resource['css'],
        'js' => $resource['js'],
        'storage_path' => asset('storage'),
        'resources_path' => asset(''),
        'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
        'content' => [
            'public/profile'
        ]
    );
    return view('template/main-template', $data);
    
});