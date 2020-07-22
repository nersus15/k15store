let notifyConf = {
    title: 'Error',
    type: 'danger',
};
$(document).ready(async function () {
    dataKeranjang();
});

async function dataKeranjang() {
    const data = await fetch(path + '/api/transaksi/keranjang/' + session.username + "/null").then(res => res.json()).then(res => res);
    if (data.length > 0) {
        let rows = '';
        data.forEach((item, index) => {
            let thumbnail = item.gambar.split(';');
            rows += `
                <tr>
                    <td class="detail" style="cursor: pointer" data-barang="${index}">${item.nama_product}</td>
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
    $(".detail").click(function () {
        let barang = data[$(this).data('barang')];
        let thumbnail = barang.gambar.split(';');
        const { modalId, wrapper, opt } = modalConf.keranjang;
        barang.detail_alamat = !barang.detail_alamat ? `` : barang.detail_alamat;
        barang.kurir = barang.kurir.toUpperCase();
        opt.saatBuka = () => {
            saatDetailBuka(barang)
        };
        opt.modalBody = {
            customBody: `
                <div class="row">
                    <div class="">
                        <img src="${storage_path + '/images/product/' + thumbnail[0]}" alt="thumbnail"/>
                    </div>
                    <div class="col ml-2 mt-2">
                        <p style="overflow: auto" class="text-muted row" id="nama_product">Barang: </p>
                        <hr>
                        <p class="text-muted row" id="harga">harga: </p><hr>
                        <p class="text-muted row" id="berat">Berat(gram): </p><hr>
                        <p class="text-muted row" id="owner">Penjual: </p><hr>
                        <p class="text-muted row" id="jumlah">Jumlah: </p><hr>
                        <p class="text-muted row" id="destinasi">Destinasi: </p><hr>
                        <div>
                            <p style="overflow: auto; cursor: pointer" class="text-muted row" id="detail_alamat">Detail alamat pengiriman: </p>
                            <input id="d_alamat" type="text" placeholder="Detail Alamat" name="alamat" class="form-control" style="display: none" />
                        </div>
                        <hr>
                        <p class="text-muted row" id="kurir">Kurir: </p><hr>                   
                        <p class="text-muted row" id="ongkir">Ongkir: </p><hr>                   
                        <p class="text-muted row" id="total">Total: </p><hr>                   
                    </div>
                </div>
                `,
        };
        opt.modalFooter = [
            { type: 'reset', data: 'data-dismiss="modal"', text: 'Tutup', id: "batal", class: "btn btn-empty" },
            { type: 'button', data: `data-idtr="${barang.idtr}"`, text: 'Hapus', id: "hapus", class: "btn btn-danger" },
            { type: 'button', data: `data-id="${barang.idtr}"`, text: 'Edit', id: "edit", class: "btn btn btn-warning" },
            { type: 'button', data: `data-id="${barang.idtr}"`, text: 'checkout', id: "ck", class: "btn btn btn-primary" },
        ];
        UiHelper.generateModal(modalId, wrapper, opt);
    })

}
async function caribiaya(ini, origin, berat, ongkir) {
    $('#alert_danger').hide();
    let id = ini.attr('id');
    let kurir = id == 'kurir-opt' ? ini.val() : $("#kurir-opt").val();
    let kab_kota = $("#kab_kota option:selected").data('item');
    let opsi = '';
    let url = path + '/api/cost/' + origin + '/' + kab_kota.split(', ')[0] + '/' + berat + '/' + kurir;
    if (!kab_kota || !kurir) {
        $('#alert_danger').show().text('Kurir atau Kab/kota belum dipilih');
        return;
    }
    $("#ongkir").prop('disabled', true);
    let data = await fetch(url).then(res => res.json()).then(res => res.rajaongkir)
    let costs = data.results[0].costs;
    let destination_d = data.destination_details;
    if (costs.length <= 0) {
        $('#alert_danger').show().text('Service dengan kurir ' + kurir + ' tidak tersedia');
        return;
    }
    costs.forEach(cost => {
        if (ongkir == cost.cost[0].value)
            opsi += `<option data-serv="${cost.service}" data-etd="${cost.cost[0].etd.replace('HARI', '')}" data-des="${destination_d.province}: ${destination_d.postal_code}" selected value="${cost.cost[0].value}"> Rp. ${cost.cost[0].value.toString().rupiahFormat()} (${cost.cost[0].etd.replace('HARI', '')} Hari ${cost.service})</option>`;
        else
            opsi += `<option data-serv="${cost.service}" data-etd="${cost.cost[0].etd.replace('HARI', '')}" data-des="${destination_d.province}: ${destination_d.postal_code}" value="${cost.cost[0].value}"> Rp. ${cost.cost[0].value.toString().rupiahFormat()} (${cost.cost[0].etd.replace('HARI', '')} Hari ${cost.service})</option>`;
    });
    $("#ongkir-opt").prop('disabled', false).html(opsi).trigger('change');
}

function checkout(event) {
    const barang = event.data;
    if (!$("#d_alamat").val())
        return;

    fetch(path + '/api/transaksi/' + barang.idtr, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            _token: $('meta[name="_token"]').attr('content'),
            detail_alamat: $("#d_alamat").val(),
            checkout: 'on',
            edit: 'no',
            pembeli: session.username,
        })
    }).then(res => {
        try {
            return res.json();
        }
        catch (err) {
            notifyConf.message = err,
                setTimeout(function () {
                    $('body').removeClass('show-spinner');
                    $('body').removeClass('modal-open');
                }, 3000);
            UiHelper.makeNotify(notifyConf);
        }
    }).then(res => {
        if (!res.err) {
            notifyConf.type = 'success';
            notifyConf.title = 'Berhasil';
            notifyConf.message = res;
        }
        notifyConf.message = res.message;
        setTimeout(function () {
            $('body').removeClass('show-spinner');
            $('body').removeClass('modal-open');
            $('.c-overlay').hide();
        }, 3000);
        UiHelper.makeNotify(notifyConf);
        $("#" + modalConf.keranjang.modalId).modal('hide');
        window.location.reload();
    });
}

function hapus() {
    const idtr = $(this).data('idtr');
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

async function editdata(event) {
    $("#edit, #ck, #batal").prop('disabled', true);
    const barang = event.data;
    kab = await fetch(path + '/api/kota').then(res => res.json()).then(res => res.rajaongkir.results);
    const { modalId, wrapper, opt } = modalConf.editkeranjang;
    const opsi = {};
    const idbr =
        opt.formOpt.formAct += '/' + barang.idtr;
    const origin = kab.filter(k => k.city_name == barang.origin.split(',')[0]);
    kab.forEach(item => {
        opsi[item.city_name] = {
            text: item.city_name,
            data: {
                item: item.city_id + ', ' + item.province
            }
        };
    });
    const customInput =
        `   
        <div class="custom-switch custom-switch-primary-inverse mb-2">
            <label class ="control-label"> Langsung checkout </label><br>
            <input class="custom-switch-input" name="checkout" id="langsung-bayar" type="checkbox" checked="">
            <label class="custom-switch-btn" for="langsung-bayar"></label>
        </div>
        `;
    const inputs = [
        {
            label: `Jumlah <small class="text-danger">Min:1, Maks: ${barang.stok} </small>`, placeholder: '',
            type: 'number', name: 'jumlah', id: 'jumlah', attr: 'required', attr: `min="1" max="${barang.stok}"`, value: barang.jumlah
        },
        { id: 'harga', type: 'hidden', value: barang.harga, name: "harga" },
        { id: 'status', type: 'hidden', value: 'bayar', name: "status" },
        { id: 'pembeli', type: 'hidden', value: session.username, name: "pembeli" },
        { id: 'service', type: 'hidden', value: barang.service, name: "service" },
        { id: 'total', type: 'hidden', value: barang.total, name: "total" },
        { id: 'destinasi', type: 'hidden', value: barang.destinasi, name: "destinasi" },
        { id: 'method', type: 'hidden', value: 'PUT', name: "_method" },
        { id: 'estimasi', type: 'hidden', value: '', name: "estimasi" },
        {
            label: 'Destinasi (Kabupaten/ Kota)', placeholder: '', options: opsi,
            type: 'select', name: 'kab_kota', id: 'kab_kota', class: 'select2', default: barang.destinasi.split(',')[0]
        },
        {
            label: 'Kurir', placeholder: '', options: { 'jne': { text: 'JNE' }, 'pos': { text: 'POS' }, 'tiki': { text: 'TIKI' } },
            type: 'select', name: 'kurir', id: 'kurir-opt', default: barang.kurir.toLowerCase()
        },
        {
            label: 'Service', placeholder: '', nullOpt: 'Pilih service',
            type: 'select', name: 'ongkir', id: 'ongkir-opt'
        },
        {
            value: barang.da, attr: 'required', type: 'textarea', id: 'detail_alamat', name: 'detail_alamat', label: 'Alamat pengiriman'
        },
        { type: 'custom', text: `<p id="total-txt" >Rp. ${barang.total.toString().rupiahFormat()}` },
        { type: 'custom', text: customInput },

    ]

    opt.saatBuka = () => { saatEditBuka(barang, origin) };
    opt.modalTitle = barang.nama_product + ' (Harga: ' + barang.harga + ')';
    opt.modalBody.input = inputs;
    opt.modalSubtitle = barang.tanggal.substr(0, 10);
    UiHelper.generateModal(modalId, wrapper, opt);
}

function saatEditBuka(barang, origin) {
    $('#' + modalConf.keranjang.modalId).modal('hide');
    setTimeout(function () {
        $('body').addClass('modal-open');
    }, 1000);
    $("#kurir-opt, #kab_kota").change(function (evt) {
        evt.preventDefault();
        caribiaya($(this), origin[0].city_id, barang.berat, barang.ongkir);
    });
    $("#ongkir-opt, #jumlah").change(function () {
        let jumlah = parseInt($('#jumlah').val());
        let harga = parseInt($("#harga").val());
        let ongkir = parseInt($(this).val());
        let total = (jumlah * harga) + ongkir;
        $('#total-txt').text("Rp. " + total.toString().rupiahFormat());
        $("#destinasi").val($('#kab_kota').val() + ', ' + $('#ongkir-opt option:selected').data('des'));
        $("#total").val(total);
        $('#service').val($('#ongkir-opt option:selected').data('serv'))
        $('#estimasi').val($('#ongkir-opt option:selected').data('etd'));
    });
    $(".select2").select2();
    $("#kurir-opt").trigger('change');

    $("#langsung-bayar").change(function () {
        let val = $(this).is(':checked') ? 'on' : 'off';
        $(this).val(val);
    })
}

function saatDetailBuka(barang) {
    barang.da = !barang.detail_alamat ? '' : barang.detail_alamat;
    const items = ['nama_product', 'harga', 'berat', 'kurir', 'detail_alamat', 'total', 'jumlah', 'owner', 'destinasi', 'ongkir'];
    items.forEach(item => {
        let barang2 = item == 'total' || item == 'harga' || item == 'ongkir' ? 'Rp. ' + barang[item].toString().rupiahFormat() : barang[item];
        $("#" + item).append(barang2);
    });
    if (!barang.detail_alamat)
        $("p#detail_alamat").append('<span id="no-da" class="text-danger">Lengkapi alamat pengiriman</span>');

    $("#d_alamat").val(barang.detail_alamat);
    $('#kurir').append(` (${barang.service})`);
    $('#detail_alamat').click(function () {
        $("#d_alamat").show().blur(function () {
            $(this).hide();
            if ($(this).val())
                $("#detail_alamat span").hide().parent().html(`Detail alamat pengiriman: <span id="da"> ${$(this).val()}</span>`);
            else
                $("#detail_alamat").html('Detail alamat pengiriman: <span id="no-da" class="text-danger">Lengkapi alamat pengiriman</span>');
            barang.da = $(this).val();

        });
    });
    $("#hapus").click(hapus);
    $('#ck').on('click', barang, checkout);
    $('#edit').on('click', barang, editdata);
}