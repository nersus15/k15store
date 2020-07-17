<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Transaksi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        date_default_timezone_set('Asia/Jakarta');
    }
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

        $transaksi = DB::table('transaksi')->select('transaksi.*', 'product.*', 'transaksi.id as idtr')->join('product', 'product.id', '=', 'transaksi.barang', 'inner')->where('pembeli', $pembeli);
        // $transaksi = DB::table('transaksi')->join('product', 'product.id', '=', 'transaksi.barang', 'inner')->where('pembeli', $pembeli)->where('status', $status);

        if ($status == 'riwayat')
            $transaksi->where('transaksi.status', '!=', 'keranjang');
        if ($status != 'all' && $status != 'riwayat')
            $transaksi->where('transaksi.status', $status);
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

        if ($request->checkout == 'on') {
            if (!isset($request->edit)) {
                $post = [
                    'jumlah' => $request->jumlah,
                    'destinasi' => $request->destinasi,
                    'jumlah' => $request->jumlah,
                    'total' => $request->total,
                    'ongkir' => $request->ongkir,
                    'estimasi' => $request->estimasi,
                    'kurir' => $request->kurir,
                    'detail_alamat' => $request->detail_alamat,
                    'tanggal_update' => date('Y-m-d H:i:s'),
                    'status' => 'bayar'
                ];
                $post = [
                    'status' => 'bayar',
                    'detail_alamat' => $request->detail_alamat,
                    'tanggal_update' => date('Y-m-d H:i:s'),
                ];
            }
        } else {
            $post = [
                'jumlah' => $request->jumlah,
                'destinasi' => $request->destinasi,
                'jumlah' => $request->jumlah,
                'total' => $request->total,
                'ongkir' => $request->ongkir,
                'estimasi' => $request->estimasi,
                'kurir' => $request->kurir,
                'detail_alamat' => $request->detail_alamat,
            ];
        }
        try {
            DB::table('transaksi')->where('id', $id)->update($post);
            $notif = [
                'id' => Str::random(5),
                'tanggal' => date('Y-m-d H:i:s'),
                'pesan' => 'Berhasil melakukan checkout, Selanjutnya silahkan melakukan pembayaran secepatnya, sebelum <strong>' . date('Y-m-d H:i:s', time() + 24 * 3600) . '</strong> klik <a href="#">disini</a> untuk melihat detail pembayaran',
                'judul' => 'Pembayaran untuk transaksi <b>' . $id . '</b>',
                'pembaca' => $request->pembeli
            ];

            if (empty($request->checkout)) {
                $notif['judul'] = "Memperbaruai transaksi dengan id <b>" . $id . '</b>';
                $notif['pesan'] = "";
            }
            DB::table('notif')->insert($notif);
            return ['data' => $notif, 'message' => isset($request->checkout) && $request->checkout == 'on' ? isset($request->edit) ? 'Berhasil edit dan checkout' : 'Checkout berhasil' : 'Berhasil Memperbarui'];
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
