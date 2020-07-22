// Init Modal untuk sekali saja
UiHelper.init();
$(".rahasia").empty();
$('#masuk').click(function () {
    const { modalId, wrapper, opt } = modalConf.formlogin;
    UiHelper.generateModal(modalId, wrapper, opt);
});

$("#logout").click(function () {
    $.get(path + '/api/logout', function () {
        window.location.reload();
    })
});

$('#keranjang-btn').click(function () {
    if (window.location.pathname == '/keranjang' || !session) {
        if (!session)
            UiHelper.makeNotify({ message: 'Silahkan login dulu', type: 'danger', title: 'error' })
        return;
    }
    window.location.href = path + '/keranjang';
});
$('#riwayat-btn').click(function () {
    window.location.href = path + '/riwayat';
});
$(document).ready(async function () {
    if (!$('.count').text())
        $('.count').hide();
    if (session) {
        keranjangCount();
        notifCount();
        setInterval(notifCount, 60000);
        if (window.location.pathname != '/keranjang')
            setInterval(keranjangCount, 30000)
    }
});

function showNotif(event) {
    const notif = event.data;
    const { modalId, wrapper, opt } = modalConf.keranjang;
    opt.modalId = "modal-notif";
    opt.modalTitle = 'Notifikasi';
    opt.modalSubtitle = notif.tanggal;
    opt.modalBody = {
        customBody: `
            <div class="row">
                <h3>${notif.judul}</h3>
            </div>
            <div class="row overflow-auto">
                <p>${notif.pesan}</p>
            </div>
            `,
    };
    opt.saatBuka = () => {
        fetch(path + '/api/notif/baca/' + notif.id, { method: 'PUT' });
    };
    opt.modalFooter = [
        { type: 'reset', data: 'data-dismiss="modal"', text: 'Tutup', id: "batal", class: "btn btn-empty" },
    ];
    UiHelper.generateModal(modalId, wrapper, opt);
}

async function keranjangCount() {
    // const fetchParams = { method: 'GET', headers: (headers) };
    fetch(path + '/api/transaksi/keranjang/' + session.username + '/count').then(res => res.json()).then(res => {
        if (res > 0)
            $('#keranjang-count').text(res).show();
    });
}
async function notifCount() {
    fetch(path + '/api/notif/count/' + session.username).then(res => res.json()).then(res => {
        UiHelper.addNotifItem('#notificationDropdown', res);
        res.forEach((item, index) => {
            $("#" + item.id).off('click');
            $("#" + item.id).on('click', item, showNotif);
        });
    });
}