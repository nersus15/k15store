$(document).ready(function () {
    getOrder();
});

async function getOrder() {
    const data = await fetch(path + '/api/transaksi/order/null/null').then(res => res.json()).then(res => res);
    let rows = '';
    data.forEach((item, index) => {
        rows += `
            <tr>
                <td>${item.tanggal.substr(0, 10)}</td>
                <td data-index="${index}" class="barang" style="cursor: pointer" data-idp="${item.id}">${item.nama_product}</td>
                <td>${item.jumlah}</td>
                <td class="owner" style="cursor: pointer" data-owner="${item.owner}">${item.owner}</td>
                <td class="username" style="cursor: pointer" data-username="${item.pembeli}">${item.pembeli}</td>
                <td>${item.destinasi}</td>
                <td>${item.detail_alamat}</td>
            </tr>
            `;
    });
    $('tbody').html(rows);
    $(".barang").on('click', { "transaksi": data, }, bukaModal);
}

function bukaModal(event) {
    const data = event.data.transaksi;
    let { modalId, wrapper, opt } = modalConf.keranjang;
    const barang = data[$(this).data('index')];
    let thumbnail = barang.gambar.split(';');
    opt.saatBuka = saatBuka;
    opt.modalTitle = "Detail transaksi menunggu konfirmasi pembayaran";
    opt.modalSubtitle = barang.tanggal_update.substr(0, 10);
    modalId = "modal-dt";
    opt.modalBody = {
        customBody: `
                <div class="row">
                    <div class="col">
                        <img src="${storage_path + '/images/product/' + thumbnail[0]}" alt="thumbnail"/>
                        <p class="overflow-auto"><b>Deskripsi</b>: ${barang.deskripsi} </P>
                    </div>
                    <div class="col ml-2 mt-2">
                        <p style="overflow: auto" class="text-muted row" id="nama_product">Barang: <strong> ${barang.nama_product}</strong></p>
                        <hr>
                        <p class="text-muted row" id="harga">Harga: <strong>Rp. ${barang.harga.toString().rupiahFormat()} </strong></p><hr>
                        <p class="text-muted row" id="berat">Berat(gram): <strong>${barang.berat} </strong> gr</p><hr>
                        <p class="text-muted row" id="owner">Penjual: <strong>${barang.owner} </strong></p><hr>
                        <p class="text-muted row" id="jumlah">Jumlah: <strong>${barang.jumlah} </strong></p><hr>
                        <p class="text-muted row" id="destinasi">Destinasi: <strong>${barang.destinasi} </strong></p><hr>
                        <div>
                            <p style="overflow: auto; cursor: pointer" class="text-muted row" id="detail_alamat">Detail alamat pengiriman: <strong>${barang.detail_alamat} </strong></p>
                        </div>
                        <hr>
                        <p class="text-muted row" id="kurir">Kurir:<strong>${barang.kurir.toUpperCase() + " (" + barang.estimasi + " Hari dengan " + barang.service + ")"} </strong></p><hr>                   
                        <p class="text-muted row" id="ongkir">Ongkir:<strong>Rp. ${barang.ongkir.toString().rupiahFormat()} </strong> </p><hr>                   
                        <p class="text-muted row" id="total">Total:<strong> Rp. ${barang.total.toString().rupiahFormat()} </strong> </p><hr>                   
                    </div>
                </div>
                `,
    };
    opt.modalFooter = [
        { type: 'reset', data: 'data-dismiss="modal"', text: 'Tutup', id: "batal", class: "btn btn-empty" },
        { type: 'button', data: `data-id="${barang.idtr}" data-barang="${barang.id}" data-sisastok= "${parseInt(barang.stok) - parseInt(barang.jumlah)}" data-terjual= "${parseInt(barang.terjual) + parseInt(barang.jumlah)}" data-pembeli="${barang.pembeli}" data-penjual="${barang.owner}"`, text: 'Konfirmasi', id: "konfirmasi", class: "btn btn btn-primary" },
    ];
    UiHelper.generateModal(modalId, wrapper, opt);
}

function saatBuka() {
    $('#konfirmasi').click(async function () {
        const idtr = $(this).data('id');
        await fetch(path + '/api/transaksi/' + idtr,
            {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    _token: $('meta[name="_token"]').attr('content'),
                    konfirmasi: true,
                    pembeli:  $(this).data('pembeli'),
                    penjual: $(this).data('penjual'),
                    barang: $(this).data('barang'),
                    sisastok: $(this).data('sisastok'),
                    terjual:$(this).data('terjual'),
                })
            }
        ).then(res => res.json()).then(res => {
            if(res.err)
                UiHelper.makeNotify({type: 'danger', title: 'Error', message: res.err});
            else
                UiHelper.makeNotify({type: 'success', title: 'Berhasil menambahkan', message: res.message});
        });
        location.reload();
    })
}