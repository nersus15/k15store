
$(document).ready(getRiwayat);
async function getRiwayat() {
    const mapskey = {
        'bayar': 'red',
        'kirim': '#0b5927',
        'selesai': '#2fba13',
        'konfirmasi': 'orange'
    }
    const data = await fetch(path + '/api/transaksi/riwayat/' + session.username + '/null').then(res => res.json()).then(res => res);
    if (data.length > 0) {
        let rows = '';
        data.forEach((item, index) => {
            let thumbnail = item.gambar.split(';');
            let color = mapskey[item.status];
            rows += `
                <tr style = "color: ${color}">
                    <td data-index="${index}" class="bayar" data-thumbnail = ${thumbnail[0]} style="cursor: pointer" data-barang="${index}">${item.nama_product}</td>
                    <td>${item.jumlah}</td>
                    <td class="username" style="cursor: pointer" data-username="${item.owner}">${item.owner}</td>
                    <td>Rp. ${item.harga.toString().rupiahFormat()}</td>
                    <td>${item.destinasi}</td>
                    <td><img class="img-thumbnail img-fluid" src="${storage_path + '/images/product/' + thumbnail[0]}" alt="thumbnail"/></td>
                    <td>${item.tanggal.substr(0, 10)}</td>
                </tr>
            `;
        });
        $('tbody').html(rows);

    }
    initDatatable('.data-table', { search: true, info: true, change: true, order: true });
    $('.bayar').on('click', data, bayar);
}

function bayar(event) {
    const thumbnail = $(this).data('thumbnail');
    const { modalId, wrapper, opt } = modalConf.keranjang;
    const barang = event.data[$(this).data('barang')];
    const status = barang.status;
    const total = 'Rp. ' + barang.total.toString().rupiahFormat();
    if (status == 'bayar' || status == 'selesai' || status == 'keranjang')
        return;

    opt.modalId = "riwayat";
    opt.modalTitle = 'Informasi pembayaran';
    opt.modalSubtitle = barang.nama_product;
    opt.saatBuka = () => {
        $('#batalkan').click(saatBuka);
        $('#selesai').on('click', barang, selesai);
    }
    opt.modalBody = {
        customBody: `
            <div class="row">
                <div class="">
                    <img src="${storage_path + '/images/product/' + thumbnail}" alt="thumbnail"/>
                </div>
                <div class="col ml-2 mt-2">
                    <p style="overflow: auto" class="text-muted row" id="nama_product">
                        Silahkan melakukan pembayaran dengan melakukan transfer sebesar <b><i>${total}</i></b> ke nomer rekening berikut:<br>
                        <p class="row"> BRI: 20840798242724 a.n K15 </p>
                        <p class="row"> BNI: 252626222642724 a.n K15 </p>
                        <p class="row"> Mandiri: 123115151 a.n K15 </p>
                    </p>
                    <hr>             
                </div>
            </div>
            `,
    };
    opt.modalFooter = [
        { type: 'reset', data: 'data-dismiss="modal"', text: 'Tutup', id: "batal", class: "btn btn-empty" },
    ];
    if (status == 'bayar') {
        opt.modalFooter.push(
            { type: 'button', data: `data-id="${barang.idtr}"`, text: 'Batalkan', id: "batalkan", class: "btn btn btn-outline-danger" },
        );
    } else if (status == 'kirim') {
        opt.modalBody.customBody =
            `<div class="row">
                <div class="">
                    <img src="${storage_path + '/images/product/' + thumbnail}" alt="thumbnail"/>
                </div>
                <div class="col ml-2 mt-2">
                    <p style="overflow: auto" class="text-muted row" id="nama_product">
                       Barang anda sudah sampai
                    </p>
                    <hr>             
                </div>
            </div>
            `,

            opt.modalFooter.push(
                { type: 'button', data: `data-id="${barang.idtr}"`, text: 'Selesai', id: "selesai", class: "btn btn btn-outline-primary" },
            );
    }
    UiHelper.generateModal(modalId, wrapper, opt);
};

function saatBuka() {
    const idtr = $(this).data('id');
    $(this).prop('disabled', true);
    fetch(path + '/api/transaksi/' + idtr, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
    }).then(res => res.json()).then(res => {
        $(this).prop('disabled', false);
        if (res.err)
            UiHelper.makeNotify({ type: 'danger', title: 'Error', message: res.message });
        else
            UiHelper.makeNotify({ type: 'success', title: 'Berhasil', message: res.message });
    });
    setTimeout(() => { location.reload() }, 500);

}
function selesai(ev) {
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
            selesai: 'yes',
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
    // setTimeout(() => { location.reload() }, 500);

}