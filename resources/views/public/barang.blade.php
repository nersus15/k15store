<main class="default-transition">
    <h2>Barang</h2>
    <div class="container-fluid">
            <button class="mb-3 btn btn-outline-primary btn-sm" id="btn-add">Tambah Barang</button>
            <p>Silahkan klik nama produk untuk melihat detail</p>
        </div>
        <table id="barang-table" class="table data-table" >
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Kondisi</th>
                    <th>Berat</th>
                    <th>gambar</th>
                </tr>
            </thead>
            <tbody>                
            </tbody>
        </table>
        
        {{-- card hasil generate --}}
        <div id="cards" class="row row-cols-1 row-cols-md-2"></div>

    </div>
</main>