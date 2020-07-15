<main class="default-transition">
    <div class="container-fluid">
        <h1>Hello Guys</h1>    
        <div id="cards" class="row">
            {{-- card hasil generate disin --}}

            <div class="col col-12 col-sm-7 col-lg-7 mb-4 h-100" id="info">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2" id="owner"></h6>
                        <h3 class="font-weight-bold" id="nama_product"></h3>
                        <h6 class="card-subtitle mb-2 text-muted" id="terjual"></h6>
                        <hr>
                        <div class="info">
                            <div class="row">
                                <p style="font-size: 23px" class="col col-sm-4 col-lg-2 text-muted">Harga</p>
                                <p style="font-size: 1.5rem" id="harga" data-harga="" class="col mt-2 text-primary"></p>
                            </div>
                            <hr>

                            <div class="row">
                                <p style="font-size: 23px" class="col col-sm-4 col-lg-2 text-muted">Jumlah</p>
                                <div class="col col-sm-7">
                                    <p id="stok" class="text-primary"></p>
                                    <div class="custom-input d-flex p-0">
                                        <span id="minus" class="btn-lg p-0" style="cursor: pointer; align-self: flex-end"><i class="simple-icon-minus"></i></span>
                                        <input type="text" style="border-top: none; border-left: none; border-right: none; width: 50%" name="jumlah" id="jumlah" value="1" class="form-control">
                                        <span id="plus" class="btn-lg p-0" style="cursor: pointer; align-self: flex-end"><i class="simple-icon-plus"></i></span>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <p style="font-size: 20px" class=" col-sm-4 col-lg-2 text-muted">Info Produk</p>
                                <div class="group col-sm-4 col-lg-7 row">
                                    <input type="hidden" name="origin" id="origin">
                                    <input type="hidden" name="origin_detail" id="origin_detail">
                                    <input type="hidden" name="destination_detail" id="destination_detail">
                                    <div class="col-sm-4 col-lg-4">
                                        <p class="text-muted">Berat</p>
                                        <p id="berat" class="text-primary"></p>
                                    </div>
                                    <div class="col-sm-4 col-lg-4">
                                        <p class="text-muted">Kondisi</p>
                                        <p id="kondisi" class="text-primary"></p>
                                    </div>
                                    <div class="col-sm-4 col-lg-4">
                                        <p class="text-muted">Kategori</p>
                                        <p id="kategori" class="text-primary"></p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row" id="ongkir">
                                <p style="font-size: 20px" class=" col-sm-2 col-lg-2 text-muted">Ongkos Kirim</p>
                                <p class="text-muted">Ke</p>
                                <div class="group col col-lg-3 col-sm-3">
                                    <input type="hidden" id="service" name="service">
                                    <select class="form-control select2" name="kab_kota" id="kab_kota"></select>
                                </div>
                                <div class="group col-lg-2 col-sm-2">
                                    <select class="form-control" name="kurir" id="kurir">
                                        <option value="">Kurir</option>
                                        <option value="jne">JNE</option>
                                        <option value="tiki">TIKI</option>
                                        <option value="pos">POS</option>
                                    </select>
                                </div>
                                <button style="text-align: end;" class=" btn btn-empty text-primary col-sm-4 col-lg-4 p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <span data-costTerpilih="" id="cost" class="name"></span><br>
                                    <span data-etdTerpilih="" id="etd" class="name"></span>
                                </button>
                                <div id="costitem" class="dropdown-menu dropdown-menu-right mt-3">
                                </div>
                            </div>
                            <hr>   
                            <div class="row">
                                <p style="font-size: 20px" class=" col-sm-4 col-lg-4 text-muted">Dikirim dari</p>
                                <p id="dikirim-dari" style="font-size: 20px" class=" col-sm-8 col-lg-8 text-primary"></p>
                            </div>                                                     
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="descard" class="row mb-4">
            <div class="col-md-12 col-lg-12 col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs " role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="desc-tab" data-toggle="tab" href="#desc"
                                    role="tab" aria-controls="desc" aria-selected="true">Deskripsi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ulasan-tab" data-toggle="tab" href="#ulasan" role="tab"
                                    aria-controls="ulasan" aria-selected="false">Ulasan</a>
                            </li>
                        </ul>
                        
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="desc" role="tabpanel" aria-labelledby="desc-tab">
                            </div>
                            <div class="tab-pane fade " id="ulasan" role="tabpanel" aria-labelledby="ulasan-tab">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script class="rahasia">
    const idproduct = "<?php echo $idProduct?>"
</script>