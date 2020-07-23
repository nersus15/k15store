<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Pedagang extends Controller
{
    //
    protected $resource = [
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

    // function index()
    // {
    
    //     $this->resource['js'][] = ['pos' => 'body:end', 'js' => 'js/pages/transaksi.js'];
    //     $data = array(
    //         'pageTitle' => 'K15 Store | Situs Jual Beli Online',
    //         'navbar' => 'comp/navbarPengunjung',
    //         'footer' => 'comp/footerPengunjung',
    //         'css' => $this->resource['css'],
    //         'js' => $this->resource['js'],
    //         'storage_path' => asset('storage'),
    //         'resources_path' => asset(''),
    //         'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
    //         'content' => [
    //             'public/transaksi'
    //         ]
    //     );
    //     return view('template/main-template', $data);
    // }
    function index(){
        $this->resource['js'][] = ['pos' => 'body:end', 'js' => 'js/pages/toko.js'];
        $data = array(
            'pageTitle' => 'K15 Store | Situs Jual Beli Online',
            'navbar' => 'comp/navbarPengunjung',
            'footer' => 'comp/footerPengunjung',
            'css' => $this->resource['css'],
            'js' => $this->resource['js'],
            'storage_path' => asset('storage'),
            'resources_path' => asset(''),
            'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
            'content' => [
                'public/barang',
                'public/transaksi'
            ]
        );
        return view('template/main-template', $data);
    }
}
