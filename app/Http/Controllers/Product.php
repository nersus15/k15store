<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Util\Json;
use Illuminate\Support\Str;

class Product extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (!empty($_SESSION['userdata']))
            return DB::table('product')->where('owner', '!=', $_SESSION['userdata']['username'])->paginate(8);
        else
            return DB::table('product')->paginate(8);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'pedagang')
            return response("Anda tidak memiliki akses!", 401);
        if($request->batas_beli > $request->stok)
            return response('Batas beli tidak boleh lebih dari stok barang', 500);
        $post = [
            'id' => Str::random(5),
            'nama_product' => $request->nama_barang,
            'owner' => $_SESSION['userdata']['username'],
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori' => $request->kategori,
            'berat' => $request->berat,
            'kondisi' => $request->kondisi,
            'batas_beli' => $request->batas_beli,
            'deskripsi' => $request->deskripsi
        ];

        try {
            DB::table('product')->insert($post);
            $res = [
                'message' => 'Berhasil menambahkan barang baru'
            ];
            $notif = [
                'id' => Str::random(5),
                'tanggal' => date('Y-m-d H:i:s'),
                'judul' => 'Berhasil Menambah Barang',
                'pesan' => 'Anda telah Menambahkan barang dengan nama ' . $request->nama_barang,
                'pembaca' => $request->owner
            ];
            DB::table('notif')->insert($notif);
        } catch (\Throwable $th) {
            $res = [
                'message' => 'Terjadi kesalahan',
                'err' => $th
            ];
        }

        return $res;
    }


    public function show($id)
    {
        $product = DB::table('product')
            ->where('product.id', $id)
            ->join('users', 'users.username', '=', 'product.owner', 'inner')
            ->get();
        return Json::encode($product[0]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'pedagang')
            return response("Anda tidak memiliki akses!", 401);
        $post = [
            'nama_product' => $request->nama_barang,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori' => $request->kategori,
            'berat' => $request->berat,
            'kondisi' => $request->kondisi,
            'batas_beli' => $request->batas_beli,
            'deskripsi' => $request->deskripsi
        ];

        try {
            DB::table('product')->where('id', $id)->where('owner', $_SESSION['userdata']['username'])->update($post);
            $res = [
                'message' => 'Berhasil Update barang dengan id' . $id . '(' . $request->nama_barang . ')',
            ];
            $notif = [
                'id' => Str::random(5),
                'tanggal' => date('Y-m-d H:i:s'),
                'judul' => 'Berhasil Update Barang',
                'pesan' => 'Anda telah Memperbarui barang dengan nama' . $request->nama_barang,
                'pembaca' => $request->owner
            ];
            DB::table('notif')->insert($notif);
        } catch (\Throwable $th) {
            $res = [
                'message' => 'Terjadi kesalahan',
                'err' => $th
            ];
        }

        return $res;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'pedagang')
            return response("Anda tidak memiliki akses!", 401);
        try {
            DB::table('product')->where('id', $id)->where('owner', $_SESSION['userdata']['username'])->delete();
            $res = [
                'message' => 'Berhasil Hapus barang dengan id' . $id,
            ];
            $notif = [
                'id' => Str::random(5),
                'tanggal' => date('Y-m-d H:i:s'),
                'judul' => 'Berhasil Hapus Barang',
                'pesan' => 'Anda telah Menghapus barang dengan id' . $id,
                'pembaca' => $_SESSION['userdata']['username'],
            ];
            DB::table('notif')->insert($notif);
        } catch (\Throwable $th) {
            $res = [
                'message' => 'Terjadi kesalahan',
                'err' => $th
            ];
        }

        return $res;
    }
}
