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
    if(window.location.pathname == 'keranjang/' || !session)
        return;
    window.location.href = path + '/keranjang';
})

$(document).ready(async function(){
    if(!$('.count').text())
        $('.count').hide();
    if(session){
        keranjangCount();
        
        if(window.location.pathname != '/keranjang')
            setInterval(keranjangCount, 60000)
    }
})

async function keranjangCount(){
    // const fetchParams = { method: 'GET', headers: (headers) };
     fetch(path + '/api/keranjang/keranjang/' + session.username + '/count').then(res => res.json()).then(res => {
        if(res > 0)
            $('#keranjang-count').text(res).show();
     });
}