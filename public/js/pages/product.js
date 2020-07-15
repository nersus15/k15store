
$(document).ready(async function () {
    let data = await fetchProduct();
    let page = data.page;
    $('#load-more').children().click(async function () {
        let data = await fetchProduct(page + 1)
        page = data.page;
    });

    $(window).scroll(async function () {
        if ($(window).scrollTop() == $(document).height() - $(window).height() && page < data.maxPage) {
            if (page < 3) {
                let data = await fetchProduct(page + 1);
                page = data.page;
            } else {
                $('#load-more').show();
            }
        }
    });

})
async function fetchProduct(page = 1) {
    let products = await fetch(path + '/api/product?page=' + page).then(res => res.json());
    let cards = '';
    $('#load-more').hide();
    products.data.forEach(data => {
        let images = data.gambar.split(';');
        let card = {
            id: data.id,
            title: data.nama_product,
            class: 'h-100 product',
            subtitle: 'Rp. ' + data.harga.toString().rupiahFormat(),
            subtitleClass: 'text-muted',
            images: [
                { src: storage_path + '/images/product/' + images[0], alt: 'thumbnail product', position: 'top', styles: 'margin: 0 0 15px 0' },
            ],
            badge: [
                { 'text': data.kondisi.capitalize(), class: 'badge-theme-1' }
            ]
        }
        cards += `<div class="col mb-4">
                    ${UiHelper.generateCard(card)}
                </div>`;

    });
    $('#cards').append(cards);
    $(".product .card-body").css('cursor', 'pointer').click(function () {
        const idProduct = $(this).parent().attr('id');
        window.location.href = path + '/product/' + idProduct;
    });

    return { 'page': page, 'maxPage': products.last_page };
}
