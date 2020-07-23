<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Admin extends Controller
{
    protected $resource = [
        'css' => array(
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
            ['pos' => 'body:end', 'js' => 'js/admin.main.js'],

        )
    ];
    function index()
    {
        $data = array(
            'pageTitle' => 'K15 Store | Situs Jual Beli Online',
            'navbar' => 'comp/navbarAdmin',
            'sidebar' => 'comp/sidebarAdmin',
            'footer' => 'comp/footerPengunjung',
            'css' => $this->resource['css'],
            'js' => $this->resource['js'],
            'storage_path' => asset('storage'),
            'linkActive' => 'admin',
            'resources_path' => asset('', true),
            'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
        );
        return view('template/main-template', $data);
    }
    function order()
    {        
        $this->resource['js'][] = ['pos' => 'body:end', 'js' => 'js/pages/admin.order.js' ];
        $data = array(
            'pageTitle' => 'K15 Store | Situs Jual Beli Online',
            'navbar' => 'comp/navbarAdmin',
            'sidebar' => 'comp/sidebarAdmin',
            'footer' => 'comp/footerPengunjung',
            'css' => $this->resource['css'],
            'js' => $this->resource['js'],
            'storage_path' => asset('storage'),
            'linkActive' => 'order',
            'resources_path' => asset('', true),
            'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
            'content' => [
                'admin/order'
            ]
        );
        return view('template/main-template', $data);
    }
    function transaksi()
    {
        $data = array(
            'pageTitle' => 'K15 Store | Situs Jual Beli Online',
            'navbar' => 'comp/navbarAdmin',
            'sidebar' => 'comp/sidebarAdmin',
            'footer' => 'comp/footerPengunjung',
            'css' => $this->resource['css'],
            'js' => $this->resource['js'],
            'storage_path' => asset('storage'),
            'linkActive' => 'transaksi',
            'resources_path' => asset('', true),
            'user' => !empty($_SESSION['userdata']) ? $_SESSION['userdata'] : null,
        );
        return view('template/main-template', $data);
    }
    
}
