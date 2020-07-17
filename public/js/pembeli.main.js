// Init Modal untuk sekali saja
UiHelper.init();
$(".rahasia").empty();
$('#masuk').click(function(){
    const {modalId, wrapper, opt} = modalConf.formlogin;
    UiHelper.generateModal(modalId, wrapper, opt);
});

$("#logout").click(function(){
    $.get(path + '/api/logout', function(){
        window.location.reload();
    })
});

$('#keranjang-btn').click(function(){
    if(window.location.pathname == '/keranjang' || !session){
        if(!session)
            UiHelper.makeNotify({message: 'Silahkan login dulu', type: 'danger', title: 'error'})
        return;
    }
    window.location.href = path + '/keranjang';
});
$('#riwayat-btn').click(function(){
    window.location.href = path + '/riwayat';
});
$(document).ready(async function(){
    if(!$('.count').text())
        $('.count').hide();
    if(session){
        keranjangCount();
        notifCount();
        setInterval(notifCount, 60000);
        if(window.location.pathname != '/keranjang')
            setInterval(keranjangCount, 30000)
    }
})

async function keranjangCount(){
    // const fetchParams = { method: 'GET', headers: (headers) };
     fetch(path + '/api/riwayat/keranjang/' + session.username + '/count').then(res => res.json()).then(res => {
        if(res > 0)
            $('#keranjang-count').text(res).show();
     });
}
async function notifCount(){
    fetch(path + '/api/notif/count/' + session.username).then(res => res.json()).then(res => {
        UiHelper.addNotifItem('#notificationDropdown', res);
    });
}