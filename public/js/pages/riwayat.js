
$(document).ready(getRiwayat);
async function getRiwayat() {
    const mapskey = {
        'bayar': 'red',
        'kirim': '#0b5927',
        'selesai': '#2fba13',
        'konformasi': 'orange'
    }
    const data = await fetch(path + '/api/riwayat/riwayat/' + session.username + '/null').then(res => res.json()).then(res => res);
    if (data.length > 0) {
        let rows = '';
        data.forEach((item, index) => {
            let thumbnail = item.gambar.split(';');
            let color = mapskey[item.status];
            rows += `
                <tr style = "color: ${color}">
                    <td data-total=${item.total} class="bayar" data-thumbnail = ${thumbnail[0]} style="cursor: pointer" data-barang="${index}">${item.nama_product}</td>
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
    $('.bayar').click(bayar)
}

function bayar(){
    const thumbnail = $(this).data('thumbnail');
    const total = 'Rp. ' + $(this).data('total').toString().rupiahFormat();
    const { modalId, wrapper, opt } = modalConf.keranjang;
    opt.modalId = "riwayat";
    opt.modalTitle = 'Informasi pembayaran';
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
    UiHelper.generateModal(modalId, wrapper, opt);
};