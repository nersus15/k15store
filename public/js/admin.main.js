$(document).ready(async function () {
    // Inisiasi UiHelper
    UiHelper.init();

    $("#profile-btn").click(function () {
        location.href(path + '/profile');
    });

    $("#logout").click(function () {
        $.get(path + '/api/logout', function () {
            window.location.reload();
        })
    });

    if (!$('.count').text())
        $('.count').hide();
    if (session) {
        notifCount();
        setInterval(notifCount, 60000);
    }
});

async function notifCount() {
    fetch(path + '/api/notif/count/' + session.username).then(res => res.json()).then(res => {
        UiHelper.addNotifItem('#notificationDropdown', res);
        res.forEach((item, index) => {
            $("#" + item.id).off('click');
            $("#" + item.id).on('click', item, showNotif);
        });
    });
}

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