<main class="default-transition">
    <div class="container-fluid">
        <div class="row">
            <div class="keterangan col-md-12 col-sm-12">                                    
                <ul style="display: flex; flex-direction: row; padding:0 5px;">
                    <li style="margin:0 5px; align-items: center; list-style: none; display: flex;"><div style="background: red; width: 13px; height: 13px; margin-right: 5px;"></div>Menunggu Pembayaran</li>
                    <li style="margin:0 5px; align-items: center; list-style: none; display: flex;"><div style="background: orange; width: 13px; height: 13px; margin-right: 5px;"></div>Menunggu Konfirmasi</li>
                    <li style="margin:0 5px; align-items: center; list-style: none; display: flex;"><div style="background: #0b5927; width: 13px; height: 13px; margin-right: 5px;"></div>Sedang di kirim</li>
                    <li style="margin:0 5px; align-items: center; list-style: none; display: flex;"><div style="background: #2fba13; width: 13px; height: 13px; margin-right: 5px;"></div>Selesai</li>
                </ul>                                   
            </div>
            <p>Silahkan klik nama produk untuk melihat detail</p>
        </div>
            <table id="riwayat-table" class="table data-table" >
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Penjual</th>
                        <th>Harga</th>
                        <th>Tujuan</th>
                        <th>gambar</th>
                        <th>Tgl</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        {{-- card hasil generate --}}
        <div id="cards" class="row row-cols-1 row-cols-md-2"></div>

    </div>
</main>