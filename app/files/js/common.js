$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

$(document).on('click', "a[data-bs-original-title='Удалить']", function (e) {
    if (!confirm('Удалить запись?')) {
        e.preventDefault();
    }
});

$(document).on('click', "#bcl", function (e) {
    if ($('#dn').css('opacity') == '0') {
        e.preventDefault();
        $('#dn').css({'opacity': 1, 'transform': 'none'});
    }
});