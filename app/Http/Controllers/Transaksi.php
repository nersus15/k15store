<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Transaksi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] == 'admin')
            return response("Anda tidak memiliki akses!", 401);


        try {
            DB::table('transaksi')->insert(
                [
                    "id" => $request->id,
                    "tanggal" => $request->tanggal,
                    "barang" => $request->barang,
                    "pembeli" => $request->pembeli,
                    "jumlah" => $request->jumlah,
                    "status" => $request->status,
                    "origin" => $request->origin,
                    "destinasi" => $request->destinasi,
                    "ongkir" => $request->ongkir,
                    "service" => $request->service,
                    "total" => $request->total,
                    "kurir" => $request->kurir,
                    'estimasi' => $request->estimasi,
                ]
            );
            $res = ['message' => 'Berhasil ditambahkan'];
        } catch (Exception $err) {
            $res = ['message' => 'Terjadi Kesalahan saat menambahkan', 'err' => $err];
        }

        return $res;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    function transaksi($status, $pembeli, $opsi = null)
    {
        if (!isset($_SESSION['userdata']))
            return response("Error Processing Request, Login dulu !", 401);

        $transaksi = DB::table('transaksi')->select('transaksi.*', 'product.*', 'transaksi.id as idtr')->join('product', 'product.id', '=', 'transaksi.barang', 'inner')->where('pembeli', $pembeli)->where('status', $status);
        // $transaksi = DB::table('transaksi')->join('product', 'product.id', '=', 'transaksi.barang', 'inner')->where('pembeli', $pembeli)->where('status', $status);

        if ($opsi == 'count')
            return $transaksi->count();

        return $transaksi->get();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        if (!isset($request->status)) {
            $post = [
                'detail_alamat' => $request->detail_alamat
            ];
        } else {
            $post = [
                'jumlah' => $request->jumlah,
                'destinasi' => $request->destinasi,
                'jumlah' => $request->jumlah,
                'total' => $request->total,
                'ongkir' => $request->ongkir,
                'estimasi' => $request->estimasi,
                'kurir' => $request->kurir,
                'detail_alamat' => $request->detail_alamat
            ];
        }

        if (isset($request->checkout) && $request->checkout == 'on')
            $post['status'] = 'bayar';



        try {
            DB::table('transaksi')->where('id', $id)->update($post);
            return ['message' => isset($request->checkout) && $request->checkout == 'on' ? 'Checkout berhasil' : 'Berhasil Memperbarui'];
        } catch (\Throwable $th) {
            return ['message' => 'Terjadi kesalahan', 'err' => $th];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
