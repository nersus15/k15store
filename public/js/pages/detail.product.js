let notifyConf = {
    title: 'Error',
    type: 'danger',
};
$(document).ready(function () {
    fetchProduct(idproduct).then(function (data) {
        let stok = data.stok.split(' ');
        stok = stok[stok.length - 1];
        let batas = data.batas_beli

        // Init slick carousel
        $('.carousel').slick({ arrows: false });
        $('.slick-nav').click(function () {
            $('.carousel').slick('slickGoTo', parseInt($(this).data('index')))
        });
        // End


        // Add event listener
        $('#jumlah').blur(function () {
            var value = $(this).val();
            if (value <= 0) {
                notifyConf.message = 'Jumlah pembelian minimal 1';
                UiHelper.makeNotify(notifyConf);
                $("#jumlah").val(1)

            }

            if (value > parseInt(stok)) {
                notifyConf.message = 'Jumlah pembelian tidak boleh melebihi batas (' + batas + ')';
                UiHelper.makeNotify(notifyConf);
                $("#jumlah").val(1)

            }
            hitungTotal();
        });

        $('#plus, #minus').click(function () {
            var id = $(this).attr('id');
            var value = parseInt($('#jumlah').val());


            if (!value && id == 'plus')
                value = 0;

            if ((value <= 1 || !value) && id == 'minus') {
                notifyConf.message = 'Jumlah pembelian minimal 1';
                UiHelper.makeNotify(notifyConf);
                return;
            }
            if (value >= parseInt(batas)) {
                notifyConf.message = 'Jumlah pembelian tidak boleh melebihi batas (' + batas + ')';
                UiHelper.makeNotify(notifyConf);
                return;
            }
            var newvalue = id == 'plus' ? value + 1 : value - 1;
            $("#jumlah").val(newvalue)
            hitungTotal();

        });

        $('#keranjang').click(function () {
            let barang = hitungTotal(true);
            barang.origin = $('#origin_detail').val();
            barang.destination = $('#destination_detail').val();
            let body = {
                id: getRandomId(),
                tanggal: waktu(),
                barang: data.id,
                jumlah: barang.jumlah,
                status: 'keranjang',
                estimasi: $('#etd').data('etdterpilih'),
                origin: barang.origin,
                destinasi: barang.destination,
                ongkir: barang.ongkir,
                kurir: $('#kurir').val(),
                service: $("#service").val(),
                total: barang.total,
                _token: $('meta[name="_token"]').attr('content'),
                pembeli: session.username ? session.username : null,
            }
            if (Object.keys(session).length == 0) {
                const { modalId, wrapper, opt } = modalConf.formlogin;
                opt.saatBuka = () => {
                    $('#' + opt.formOpt.formId + ' #alert_danger').html('Silahkan login dulu sebelum melanjutkan transaksi').show();
                };
                opt.submitSuccess = (res) => {
                    if (!res.data)
                        $('#' + opt.formOpt.formId + ' #alert_danger').html(res.massage).show();
                    else {
                        $("#" + modalId).modal('hide');
                        session = res.data;
                        body.pembeli = session.username;
                        keranjang(body);
                    }

                    keranjangCount();
                }
                UiHelper.generateModal(modalId, wrapper, opt);
            } else {
                keranjang(body);
            }

        })
        // End


    });

    // Event listener untuk tab card
    $("#ulasan-tab").click(fetchUlasan);

});
async function keranjang(body) {
    $('body').addClass('show-spinner');
    $('body').addClass('modal-open');
    $('.c-overlay').show();

    fetch(path + '/api/transaksi', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(body)
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
            notifyConf.message = 'Berhasil menambahkan ke keranjang'
        }
        notifyConf.message = res.message;
        setTimeout(function () {
            $('body').removeClass('show-spinner');
            $('body').removeClass('modal-open');
            $('.c-overlay').hide();
        }, 3000);
        UiHelper.makeNotify(notifyConf);
    });
}
async function fetchProduct(id) {
    let data = await fetch(path + '/api/product/' + id).then(res => res.json());
    let cards = '';
    let images = data.gambar.split(';').filter(el => {
        return el;
    });

    let card = {
        id: data.id,
        class: 'h-100 product',
        type: 'carousel',
        width: '100%',
        footer: [
            { type: 'button', button: '<button disabled id="keranjang" type="button" style="text-align: center" class="btn btn-outline-primary btn-sm"> Tambah ke keranjang </button>' },
            { type: 'text', tag: 'p', text: 'Total Bayar', id: 'total', class: 'text-primary', extra: "data-total='' style='text-align: end; float: right'" }
        ],
        images: [],
        badge: [
            { 'text': data.kondisi.capitalize(), class: 'badge-theme-1' }
        ],
        links: [],
    }
    images.forEach((image, index) => {
        var link = {
            href: '#',
            class: 'slick-nav',
            extra: 'data-gambar="' + image + '" data-index="' + index + '"',
            text: `<img style="width: 5rem;" src="${storage_path + '/images/product/' + image}" />`
        }
        card.images.push({ type: 'carousel', src: storage_path + '/images/product/' + image, class: 'thumbnail', alt: 'thumbnail product', position: 'top', styles: 'margin: 0 0 15px 0' })
        card.links.push(link);
    });
    cards += `<div class="col mb-4 col-sm-5 col-lg-5 col-12">
                ${UiHelper.generateCard(card)}
            </div>`;


    $('#cards').prepend(cards);

    addInfoProduct(data);
    return data;
}

async function addInfoProduct(product) {
    let maps = ['owner', 'nama_product', 'terjual', 'harga', 'stok', 'berat', 'kondisi', 'kategori'];


    fetch(path + '/api/kota').then(res => res.json()).then(res => res.rajaongkir.results).then(function (kab) {
        let options = `<option value="">Pilih kabupaten/kota</option>`;
        if (!session) {
            session = {}
        }
        kab.forEach(item => {
            if (session.kab_kota && item.city_id == session.kab_kota)
                options += `<option selected value="${item.city_id}" data-prov = "${item.province_id}">${item.city_name}, ${item.province}</option>`;
            else
                options += `<option value="${item.city_id}" data-prov = "${item.province_id}">${item.city_name}, ${item.province}</option>`;

        });
        $("#kab_kota").append(options).trigger('change');
        $(".select2").select2();
    });
    if (!product.berat)
        product.berat = 0;

    $("#desc").html(product.deskripsi);

    $("#origin").val(product.kab_kota);
    $("#harga").data('harga', product.harga);
    maps.forEach(item => {
        if (item == 'terjual')
            product.terjual = product.terjual + ' Terjual';
        if (item == 'harga')
            product.harga = 'Rp. ' + product.harga.toString().rupiahFormat();

        if (item == 'stok')
            product.stok = 'Stok tinggal ' + product.stok;

        if (item == 'berat')
            product.berat += ' gr';

        if (item == 'kondisi' || item == 'kategori')
            product[item] = product[item].capitalize('all');

        $("#" + item).text(product[item]);
    });


}
$("#kab_kota, #kurir").change(async function () {
    let ini = $(this);
    let id = ini.attr('id');
    let kurir = id == 'kurir' ? ini.val() : $("#kurir").val();
    let kab_kota = id == 'kab_kota' ? ini.val() : $("#kab_kota").val();
    let berat = $('#berat').text().replace(' gr', '');
    let origin = $("#origin").val();

    let url = path + '/api/cost/' + origin + '/' + kab_kota + '/' + berat + '/' + kurir;
    $("#cost, #etd").text('');
    $('#cost').data('costterpilih', '');
    $('#etd').data('etdterpilih', '');
    $('#costitem p').off('click')
    $("#origin_detail, #destination_detail").val('')
    if (!kab_kota || !kurir) {
        notifyConf.message = 'Kota atau kurir belum dipilih';
        UiHelper.makeNotify(notifyConf);
        hitungTotal();
        return;
    }

    let data = await fetch(url).then(res => res.json()).then(res => res.rajaongkir)
    let costs = data.results[0].costs;
    let origin_d = data.origin_details;
    let destination_d = data.destination_details;
    let harga = [];
    let estimasi = [];
    let service = [];
    let item = ``;
    let index;
    let sortedharga = [];
    if (costs.length <= 0) {
        $('#cost').text('Tidak tersedia');
        return;
    }
    costs.forEach(cost => {
        harga.push(parseInt(cost.cost[0].value));
        service.push(cost.service);
        estimasi.push(cost.cost[0].etd.replace('HARI', ''));
        item += `<p style="cursor: pointer" data-service="${cost.service}" class="dropdown-item" data-etd="${cost.cost[0].etd.replace('HARI', '')}" data-cost="${cost.cost[0].value}">Rp. ${cost.cost[0].value.toString().rupiahFormat()} (${cost.cost[0].etd.replace('HARI', '')} Hari ${cost.service})</p>`;
    });
    sortedharga = harga.sort(function (a, b) { return a - b })
    harga.forEach((h, i) => {
        if (h == sortedharga[0])
            index = i;
    });

    $('#cost').text('Mulai dari Rp. ' + sortedharga[0].toString().rupiahFormat()).data('costterpilih', sortedharga[0]);
    $("#etd").text('').data('etdterpilih', estimasi[index]);
    $("#costitem").html(item);
    $('#service').val(service[index]);
    $("#dikirim-dari").text(origin_d.city_name + ', ' + origin_d.province);
    $("#origin_detail").val(origin_d.city_name + ', ' + origin_d.province + ': ' + origin_d.postal_code);
    $("#destination_detail").val(destination_d.city_name + ', ' + destination_d.province + ': ' + destination_d.postal_code);

    // Add event listener
    $('#costitem p').click(function () {
        let ini = $(this);
        let service = ini.data('service');
        let cost = ini.data('cost');
        let etd = ini.data('etd');
        $('#service').val(service);
        $('#cost').text('Ongkos kirim: Rp. ' + cost.toString().rupiahFormat()).data('costterpilih', cost);
        $("#etd").text('Estimasi waktu: ' + etd + ' hari').data('etdterpilih', etd);
        hitungTotal();
    })
    hitungTotal();

});

async function fetchUlasan(product) {

}
function hitungTotal(isReturn = false) {
    let ongkir = $('#cost').data('costterpilih');
    let harga = $('#harga').data('harga');
    let Jumlah = $("#jumlah").val();
    let total = 0;
    $('#total').text('Total bayar').data('total', '');

    if (!ongkir || !harga || !Jumlah) {
        $('#keranjang').prop('disabled', true);
    } else {
        total = parseInt(harga) * parseInt(Jumlah) + parseInt(ongkir);
        $('#total').text('Total bayar: Rp. ' + total.toString().rupiahFormat()).data('total', total);
        $('#keranjang').prop('disabled', false);
    }

    if (isReturn)
        return { 'ongkir': ongkir, 'harga': harga, 'jumlah': Jumlah, 'total': total }
}