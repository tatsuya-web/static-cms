$('#sphd_menu').on('click', function () {
    $(this).toggleClass('-open');
    $('#side').toggleClass('-open');
});

$('.side_nav_list > li > a:has(+ .side_nav_slist)').on('click', function () {
    $(this).toggleClass('-open').next().slideToggle(150);
    return false;
});