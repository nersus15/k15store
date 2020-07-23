<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class Pembeli extends Controller
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
    function index()
    {
        $this->resource['js'][] =  ['pos' => 'body:end', 'js' => 'js/pages/product.js'];

        $data = array(
            'pageTitle' => 'K15 Store | Situs Jual Beli Online',
            'navbar' => 'comp/navbarPengunjung',
            'footer' => 'comp/footerPengunjung',
            'css' => $this->resource['css'],
            'js' => $this->resource['js'],
            'storage_path' => asset('storage', true),
            'resources_path' => asset('', true),
            'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
            'content' => [
                'public/product'
            ]
        );
        return view('template/main-template', $data);
    }


    function detailProduct($id)
    {

        $this->resource['js'][] = ['pos' => 'body:end', 'js' => 'js/vendor/slick.min.js'];
        $this->resource['js'][] = ['pos' => 'body:end', 'js' => 'js/vendor/select2.full.js'];
        $this->resource['js'][] = ['pos' => 'body:end', 'js' => 'js/pages/detail.product.js'];
        $this->resource['css'][] = ['pos' => 'head', 'css' => 'css/vendor/slick.css'];
        $this->resource['css'][] = ['pos' => 'head', 'css' => 'css/vendor/select2.min.css'];
        // $this->resource['css'][] = ['pos' => 'head', 'css' => 'css/vendor/select2-bootstrap.min.css'];
        $data = array(
            'pageTitle' => 'K15 Store | Situs Jual Beli Online',
            'navbar' => 'comp/navbarPengunjung',
            'footer' => 'comp/footerPengunjung',
            'css' => $this->resource['css'],
            'js' => $this->resource['js'],
            'idProduct' => $id,
            'storage_path' => asset('storage', true),
            'resources_path' => asset('', true),
            'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
            'content' => [
                'public/detailProduct'
            ]
        );
        return view('template/main-template', $data);
    }

    function keranjang()
    {
        $this->resource['css'][] = ['pos' => 'head', 'css' => 'css/vendor/select2.min.css'];
        $this->resource['js'][] = ['pos' => 'body:end', 'js' => 'js/vendor/select2.full.js'];
        $this->resource['js'][] = ['pos' => 'body:end', 'js' => 'js/pages/keranjang.js'];
        $data = array(
            'pageTitle' => 'K15 Store | Situs Jual Beli Online',
            'navbar' => 'comp/navbarPengunjung',
            'footer' => 'comp/footerPengunjung',
            'css' => $this->resource['css'],
            'js' => $this->resource['js'],
            'storage_path' => asset('storage', true),
            'resources_path' => asset('', true),
            'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
            'content' => [
                'public/keranjang'
            ]
        );
        return view('template/main-template', $data);
    }
    function riwayat()
    {
        $this->resource['js'][] = ['pos' => 'body:end', 'js' => 'js/pages/riwayat.js'];
        $data = array(
            'pageTitle' => 'K15 Store | Situs Jual Beli Online',
            'navbar' => 'comp/navbarPengunjung',
            'footer' => 'comp/footerPengunjung',
            'css' => $this->resource['css'],
            'js' => $this->resource['js'],
            'storage_path' => asset('storage', true),
            'resources_path' => asset('', true),
            'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
            'content' => [
                'public/riwayat'
            ]
        );
        return view('template/main-template', $data);
    }
}
