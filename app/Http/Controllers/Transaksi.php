<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class Transaksi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
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
                    "id" => Str::random(5),
                    "tanggal" => date('Y-m-d H:i:s'),
                    "barang" => $request->barang,
                    "pembeli" => $_SESSION['userdata']['username'],
                    "jumlah" => $request->jumlah,
                    "status" => 'keranjang',
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
    function transaksi($status, $user = null, $opsi = null)
    {
        if (!isset($_SESSION['userdata']))
            return response("Error Processing Request, Login dulu !", 401);

        $transaksi = DB::table('transaksi')->select('transaksi.*', 'product.*', 'transaksi.id as idtr')->join('product', 'product.id', '=', 'transaksi.barang', 'inner');
        // $transaksi = DB::table('transaksi')->join('product', 'product.id', '=', 'transaksi.barang', 'inner')->where('pembeli', $pembeli)->where('status', $status);

        if ($user != 'null' && $status != 'toko')
            $transaksi->where('pembeli', $user);
        switch ($status) {
            case 'riwayat':
                $transaksi->where('transaksi.status', '!=', 'keranjang');
                break;
            case 'order':
                if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'admin')
                    return response("Anda tidak memiliki akses!", 401);
                $transaksi->where('transaksi.status', 'bayar');
                break;
            case 'keranjang':
                if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] == 'admin')
                    return response("Anda tidak memiliki akses!", 401);
                $transaksi->where('transaksi.status', 'keranjang');
                break;
            case 'toko':
                if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'pedagang')
                    return response("Anda tidak memiliki akses!", 401);
                $transaksi->where('transaksi.status', '!=', 'keranjang');
                $transaksi->where('product.owner', $user);
                break;
        }

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

        $notif = [
            'id' => Str::random(5),
            'tanggal' => date('Y-m-d H:i:s')
        ];
        if ($request->checkout == 'on') {
            if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] == 'admin')
                return response("Anda tidak memiliki akses!", 401);
            if (!isset($request->edit)) {
                $post = [
                    'jumlah' => $request->jumlah,
                    'destinasi' => $request->destinasi,
                    'total' => $request->total,
                    'ongkir' => $request->ongkir,
                    'estimasi' => $request->estimasi,
                    'kurir' => $request->kurir,
                    'detail_alamat' => $request->detail_alamat,
                    'tanggal_update' => date('Y-m-d H:i:s'),
                    'status' => 'bayar'
                ];
            } else {
                $post = [
                    'status' => 'bayar',
                    'detail_alamat' => $request->detail_alamat,
                    'tanggal_update' => date('Y-m-d H:i:s'),
                ];
            }
            $notif['pesan'] = 'Berhasil melakukan checkout, Selanjutnya silahkan melakukan pembayaran secepatnya, sebelum <strong>' . date('Y-m-d H:i:s', time() + 24 * 3600) . '</strong> klik <a href="#">disini</a> untuk melihat detail pembayaran';
            $notif['judul'] = 'Pembayaran untuk transaksi <b>' . $id . '</b>';
            $notif['pembaca'] = $request->pembeli;
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
            $notif['judul'] = "Memperbaruai transaksi dengan id <b>" . $id . '</b>';
            $notif['pembaca'] = $request->pembeli;
        }
        if (isset($request->kirim) && $request->kirim == 'yes') {
            if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'pedagang')
                return response("Anda tidak memiliki akses!", 401);

            $post = [
                'tanggal_update' => date('Y-m-d H:i:s'),
                'status' => 'kirim',
            ];
            $notif = [
                [
                    'id' => Str::random(5),
                    'tanggal' => date('Y-m-d H:i:s'),
                    'judul' => 'Pemberitahuan',
                    'pesan' => 'Anda telah mengirim barang dengan id ' . $request->idbrng . '(' . $request->namabrng . ')',
                    'pembaca' => $request->penjual
                ],
                [
                    'id' => Str::random(5),
                    'tanggal' => date('Y-m-d H:i:s'),
                    'judul' => 'Pemberitahuan Pengiriman',
                    'pesan' => 'Barang yang anda pesan dengan id ' . $request->idbrng . '(' . $request->namabrng . ')',
                    'pembaca' => $request->pembeli
                ],
            ];
        }
        if (isset($request->selesai) && $request->selesai == 'yes') {
            if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] == 'pembeli')
                return response("Anda tidak memiliki akses!", 401);
            $post = [
                'tanggal_update' => date('Y-m-d H:i:s'),
                'status' => 'selesai',
            ];
            $notif = [
                [
                    'id' => Str::random(5),
                    'tanggal' => date('Y-m-d H:i:s'),
                    'judul' => 'Pemberitahuan',
                    'pesan' => 'Barang dengan id ' . $request->idbrng . '(' . $request->namabrng . ') sudah diterima oleh pembeli',
                    'pembaca' => $request->penjual
                ],
                [
                    'id' => Str::random(5),
                    'tanggal' => date('Y-m-d H:i:s'),
                    'judul' => 'Pemberitahuan Pesanan',
                    'pesan' => 'Barang yang anda pesan dengan id ' . $request->idbrng . '(' . $request->namabrng . ') telah diterima',
                    'pembaca' => $request->pembeli
                ],
            ];
        }
        if (isset($request->konfirmasi)) {
            if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] != 'admin')
                return response("Anda tidak memiliki akses!", 401);
            DB::table('product')->where('id', $request->barang)->update(['stok' => $request->sisastok, 'terjual' => $request->terjual]);
            $post = [
                'tanggal_update' => date('Y-m-d H:i:s'),
                'status' => 'konfirmasi',
            ];
            $notif = [
                [
                    'id' => Str::random(5),
                    'tanggal' => date('Y-m-d H:i:s'),
                    'judul' => 'Pemberitahuan',
                    'pesan' => 'Barang anda dengan id <b>' . $request->barang_id . ' (' . $request->barang . ')</barang> telah dipesan dan dibayar, silahkan segera melakukan pengiriman',
                    'pembaca' => $request->penjual
                ],
                [
                    'id' => Str::random(5),
                    'tanggal' => date('Y-m-d H:i:s'),
                    'judul' => 'Pemberitahuan Pembayaran',
                    'pesan' => 'Pembayaran untuk barang pesanan dengan id <b>' . $request->barang_id . ' (' . $request->barang . ')</barang> Telah dikonfirmasi, penjual sedang memprosesnya',
                    'pembaca' => $request->pembeli
                ],
            ];
        }
        try {
            DB::table('transaksi')->where('id', $id)->update($post);
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
        if (!isset($_SESSION['userdata']) || $_SESSION['userdata']['role'] == 'admin')
            return response("Anda tidak memiliki akses!", 401);
        try {

            $tr = DB::table('transaksi')->select('product.nama_product', 'transaksi.pembeli', 'transaksi.pembeli')->join('product', 'product.id', '=', 'transaksi.barang')->where('transaksi.id', $id)->first();
            DB::table('transaksi')->where('id', $id)->delete();
            $res = ["message" => 'Berhasil menghapus data'];
            $notif = [
                [
                    'judul' => 'Anda membatalkan pesanan',
                    'id' => Str::random(5),
                    'pembaca' => $tr->pembeli,
                    'pesan' => 'Anda membatalkan pesanan anda untuk barang <b>' . $tr->nama_product . '</b>',
                    'tanggal' => date('Y-m-d H:i:s')
                ],
                [
                    'judul' => 'Pembatalan',
                    'pesan' => $tr->pembeli . ' Membatalkan pesanannya untuk barang <b>' . $tr->nama_product . '</b>',
                    'id' => Str::random(5),
                    'pembaca' => 'admin-k15store',
                    'tanggal' => date('Y-m-d H:i:s')
                ],
            ];
            DB::table('notif')->insert($notif);
        } catch (\Throwable $err) {
            $res = ['message' => 'Terjadi kesalahan', 'err' => $err];
        }
        return $res;
    }
}
