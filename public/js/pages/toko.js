
$(document).ready(function () {
    getTransaksi();
    getBarang();
});
async function getBarang() {
    const data = await fetch(path + '/api/product/list/' + session.username).then(res => res.json()).then(res => res);
    if (data.length > 0) {
        let rows = '';
        data.forEach((item, index) => {
            let thumbnail = item.gambar.split(';');
            rows += `
                <tr>
                    <td data-index="${index}" style="cursor: pointer" class="detail">${item.id}</td>
                    <td>${item.nama_product}</td>
                    <td>${item.stok}</td>
                    <td>Rp. ${item.harga.toString().rupiahFormat()}</td>
                    <td>${item.kondisi}</td>
                    <td>${item.berat}</td>
                    <td><img class="img-thumbnail img-fluid" src="${storage_path + '/images/product/' + thumbnail[0]}" alt="thumbnail"/></td>
                </tr>
            `;
        });
        $('#barang-table tbody').html(rows);
    }
    initDatatable('#barang-table', { search: true, info: true, change: true, order: true });
    $("#btn-add").click(tambahBarang);
    $(".detail").on('click', data, lihatDetailBarang);
}
async function hapusBarang(ev) {
    const barang = ev.data;
    await fetch(path + '/api/product/' + barang.id, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
    }).then(res => res.json()).then(res => {
        if (res.err)
            UiHelper.makeNotify({ type: 'danger', title: 'Error', message: res.message });
        else
            UiHelper.makeNotify({ type: 'success', title: 'Berhasil', message: res.message });
    });
    location.reload();   
}
function editBarang(ev) {
    const barang = ev.data;
    let { modalId, wrapper, opt } = modalConf.form_tambah_barang;
    // opt.ajaxMethod = 'PUT';
    opt.formOpt.formId = 'form-edit-barang';
    opt.formOpt.formAct = path + '/api/product/' + barang.id;
    modalId = 'modal-form-edit-barang';
    opt.modalTitle = 'Edit Barang';
    opt.modalSubtitle = barang.nama_product;
    opt.saatBuka = () => {
        $("#modal-detail-barang").modal('hide');
        setTimeout(function () {
            $('body').addClass('modal-open');
        }, 1000);
        var token = $('meta[name="_token"]').attr('content');
        $("#form-edit-barang #token").val(token);

        $("#stok").change(function () {
            if (!$('#batas_beli').val())
                $('#batas_beli').val($(this).val());
        });
    }
    opt.modalBody = {
        input: [
            {
                label: 'Nama Barang', placeholder: 'Masukkan Nama Barang',
                type: 'text', name: 'nama_barang', id: 'nama_barang', attr: 'required',
                value: barang.nama_product
            },
            {
                label: 'Deskripsi', placeholder: 'Masukkan Deskripsi',
                type: 'textarea', name: 'deskripsi', id: 'deskripsi', value: barang.deskripsi
            },
            {
                label: 'Harga', placeholder: 'Masukkan harga',
                type: 'number', name: 'harga', id: 'harga', attr: 'required', value: barang.harga
            },
            {
                label: 'Stok', placeholder: 'Masukkan stok',
                type: 'number', name: 'stok', id: 'stok', attr: 'required', value: barang.stok
            },
            {
                label: 'Berat', placeholder: 'Masukkan berat dalam gram',
                type: 'number', name: 'berat', id: 'berat', value: barang.berat
            },
            {
                label: 'Kategori', placeholder: 'Masukkan Kategori',
                type: 'text', name: 'kategori', id: 'kategori', value: barang.kategori
            },
            {
                label: 'Kondisi', type: 'select', name: 'kondisi', id: 'kondisi',
                options: {
                    'baru': { text: 'Baru' },
                    'bekas': { text: 'Bekas' },
                },
                default: barang.kondisi
            },
            {
                label: 'Batas Pembelian', placeholder: 'Masukkan Batas pembelian',
                type: 'number', name: 'batas_beli', id: 'batas_beli', value: barang.batas_beli
            },
            {
                type: 'hidden', name: '_token', id: 'token'
            },
            {
                type: 'hidden', name: 'owner', value: barang.owner
            },
            {
                type: 'hidden', name: '_method', value: 'PUT', id: 'method'
            },
            {
                type: 'hidden', name: 'id', id: 'idbr', value: barang.id
            },

        ],
        buttons: [
            { type: 'reset', data: 'data-dismiss="modal"', text: 'Batal', id: "batal", class: "btn btn btn-warning" },
            { type: 'submit', text: 'Edit', id: "add", class: "btn btn btn-primary" }
        ]
    }
    UiHelper.generateModal(modalId, wrapper, opt);
}
function lihatDetailBarang(ev) {
    const barang = ev.data[$(this).data('index')];
    let { modalId, wrapper, opt } = modalConf.keranjang;
    let thumbnail = barang.gambar.split(';');
    barang.deskripsi = (barang.deskripsi) ? barang.deskripsi : '';
    opt.saatBuka = () => {
        $('#hapus').on('click', barang, hapusBarang);
        $('#edit').on('click', barang, editBarang);
    };
    opt.modalSubtitle = barang.id;
    opt.modalTitle = barang.nama_product;
    modalId = "modal-detail-barang";
    opt.modalBody = {
        customBody: `
                <div class="row">
                    <div class="">
                        <img src="${storage_path + '/images/product/' + thumbnail[0]}" alt="thumbnail"/>
                    </div>
                    <div class="col ml-2 mt-2">
                        <p style="overflow: auto" class="text-muted row" id="nama_product">Barang: </p>
                        <hr>
                        <p class="text-muted row" id="harga">harga: Rp. ${barang.harga.toString().rupiahFormat()} </p><hr>
                        <p class="text-muted row" id="berat">Berat(gram): ${barang.berat} gr </p><hr>
                        <p class="text-muted row" id="owner">Penjual: ${barang.owner}</p><hr>
                        <p class="text-muted row" readonly id="deskripsi">Deskripsi: <textarea readonly class="form-control">${barang.deskripsi}</textarea></p><hr>
                        <p class="text-muted row" id="stok" >Stok: ${barang.stok} </p><hr>
                        <p class="text-muted row" id="kategori">Kategori: ${barang.kategori} </p><hr>
                        <p class="text-muted row" id="kondisi">Kondisi: ${barang.kondisi}</p><hr>                        
                        <p class="text-muted row" id="terjual">Terjual: ${barang.terjual}</p><hr>                        
                        <p class="text-muted row" id="batas_beli">Batas pembelian: ${barang.batas_beli}</p><hr>                        
                    </div>
                </div>
                `,
    };
    opt.modalFooter = [
        { type: 'reset', data: 'data-dismiss="modal"', text: 'Tutup', id: "batal", class: "btn btn-empty" },
        { type: 'button', data: `data-idtr="${barang.idtr}"`, text: 'Hapus', id: "hapus", class: "btn btn-danger" },
        { type: 'button', data: `data-id="${barang.idtr}"`, text: 'Edit', id: "edit", class: "btn btn btn-warning" },
    ];

    UiHelper.generateModal(modalId, wrapper, opt);
}
function tambahBarang() {
    let { modalId, wrapper, opt } = modalConf.form_tambah_barang;
    modalId = "modal-form-add-barang",
        wrapper = ".generated-modals",
        opt.formOpt = {
            enctype: 'multipart/form-data',
            formId: "form-add-barang",
            formAct: "/api/product",
            formMethod: 'POST',
            formAttr: ''
        };
    opt.modalTitle = "Tambah Barang";
    opt.modalSubtitle = '';
    opt.saatBuka = () => {
        var token = $('meta[name="_token"]').attr('content');
        $("#" + modalConf.form_tambah_barang.opt.formOpt.formId + " #token").val(token);
        $("#stok").change(function () {
            if (!$('#batas_beli').val())
                $('#batas_beli').val($(this).val());
        });
    },
        opt.modalBody = {
            input: [
                {
                    label: 'Nama Barang', placeholder: 'Masukkan Nama Barang',
                    type: 'text', name: 'nama_barang', id: 'nama_barang', attr: 'required'
                },
                {
                    label: 'Deskripsi', placeholder: 'Masukkan Deskripsi',
                    type: 'textarea', name: 'deskripsi', id: 'deskripsi'
                },
                {
                    label: 'Harga', placeholder: 'Masukkan harga',
                    type: 'number', name: 'harga', id: 'harga', attr: 'required'
                },
                {
                    label: 'Stok', placeholder: 'Masukkan stok',
                    type: 'number', name: 'stok', id: 'stok', attr: 'required'
                },
                {
                    label: 'Berat', placeholder: 'Masukkan berat dalam gram',
                    type: 'number', name: 'berat', id: 'berat'
                },
                {
                    label: 'Kategori', placeholder: 'Masukkan Kategori',
                    type: 'text', name: 'kategori', id: 'kategori',
                },
                {
                    label: 'Kondisi', type: 'select', name: 'kondisi', id: 'kondisi',
                    options: {
                        'baru': { text: 'Baru' },
                        'bekas': { text: 'Bekas' },
                    }
                },
                {
                    label: 'Batas Pembelian', placeholder: 'Masukkan Batas pembelian',
                    type: 'number', name: 'batas_beli', id: 'batas_beli'
                },
                {
                    type: 'hidden', name: '_token', id: 'token'
                },
                {
                    type: 'hidden', name: 'owner', id: 'owner', value: session.username
                }
            ],
            buttons: [
                { type: 'reset', data: 'data-dismiss="modal"', text: 'Batal', id: "batal", class: "btn btn btn-warning" },
                { type: 'submit', text: 'Tambah', id: "add", class: "btn btn btn-primary" }
            ]
        },
        UiHelper.generateModal(modalId, wrapper, opt);
}
async function getTransaksi() {
    const mapskey = {
        'bayar': 'red',
        'kirim': '#0b5927',
        'selesai': '#2fba13',
        'konfirmasi': 'orange'
    }
    const data = await fetch(path + '/api/transaksi/toko/' + session.username + '/null').then(res => res.json()).then(res => res);
    if (data.length > 0) {
        let rows = '';
        data.forEach((item, index) => {
            let thumbnail = item.gambar.split(';');
            let color = mapskey[item.status];
            rows += `
                <tr style = "color: ${color}">
                    <td data-index="${index}" class="konfirmasi" data-thumbnail = ${thumbnail[0]} style="cursor: pointer" data-barang="${index}">${item.nama_product}</td>
                    <td>${item.jumlah}</td>
                    <td class="username" style="cursor: pointer" data-username="${item.owner}">${item.owner}</td>
                    <td>Rp. ${item.harga.toString().rupiahFormat()}</td>
                    <td>${item.destinasi}</td>
                    <td><img class="img-thumbnail img-fluid" src="${storage_path + '/images/product/' + thumbnail[0]}" alt="thumbnail"/></td>
                    <td>${item.tanggal.substr(0, 10)}</td>
                </tr>
            `;
        });
        $('#transaksi-table tbody').html(rows);

    }
    initDatatable('#transaksi-table', { search: true, info: true, change: true, order: true });
    $('.konfirmasi').on('click', data, konfirmasi);
}

function konfirmasi(event) {
    const thumbnail = $(this).data('thumbnail');
    const { modalId, wrapper, opt } = modalConf.keranjang;
    const barang = event.data[$(this).data('barang')];
    const status = barang.status;

    if (status == 'kirim' || status== 'bayar' || status == 'selesai')
        return;

    opt.modalId = "konfirmasi";
    opt.modalTitle = 'Kirim Barang';
    opt.modalSubtitle = barang.nama_product;
    opt.saatBuka = () => {
        $('#konfir').on('click', barang, kirim);
    }
    opt.modalBody = {
        customBody: `
            <div class="row">
                <div class="">
                    <img src="${storage_path + '/images/product/' + thumbnail}" alt="thumbnail"/>
                </div>
                <div class="col ml-2 mt-2">
                    <p style="overflow: auto" class="text-muted row" id="nama_product">Product ini telah dibayar dan dikonfirmasi oleh admin, silahkan segera di kirim
                    </p>
                    <hr>             
                </div>
            </div>
            `,
    };
    opt.modalFooter = [
        { type: 'reset', data: 'data-dismiss="modal"', text: 'Tutup', id: "batal", class: "btn btn-empty" },
        { type: 'button', data: `data-id="${barang.idtr}"`, text: 'Kirim', id: "konfir", class: "btn btn btn-outline-primary" },
    ];
    UiHelper.generateModal(modalId, wrapper, opt);
};

function kirim(ev) {
    const idtr = $(this).data('id');
    $(this).prop('disabled', true);
    fetch(path + '/api/transaksi/' + idtr, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            _token: $('meta[name="_token"]').attr('content'),
            edit: 'no',
            kirim: 'yes',
            idbrng: ev.data.id,
            namabrng: ev.data.nama_product,
            penjual: ev.data.owner,
            pembeli: session.username
        })
    }).then(res => res.json()).then(res => {
        $(this).prop('disabled', false);
        if (res.err)
            UiHelper.makeNotify({ type: 'danger', title: 'Error', message: res.message });
        else
            UiHelper.makeNotify({ type: 'success', title: 'Berhasil', message: res.message });
    });
    setTimeout(() => { location.reload() }, 500);

}