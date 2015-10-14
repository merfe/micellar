$(function() {
    if (device.mobile()) {

        // disable fullscreen
        $('.full-screen').each( function() {
           $(this).removeClass('full-screen');
        });
        $('.subheader').addClass('full-screen');

        // steps layout
        $('table td').attr('width', '100%');
        $('table td img').css('height', '');
        $('.step-2 td').first().insertAfter($('.step-2 td').last());
        $('.step-4 td').first().insertAfter($('.step-4 td').last());

        // lt case
        $('.step-3 .right-img').insertAfter($('.step-3 > div .lines-3'));

        //landrover
        replaceImage($('.landrover-case .block-3 img'));
        $('.wrapper.slider h3').css('padding','');
        $('.slider-title').insertAfter($('.jcarousel-wrapper'));

        //sportlife
        replaceImage($('.sportlife-case .block-2 img'));
        $('.sportlife-case table td').css('padding','');
        replaceImage($('.sportlife-case .block-3 img'));
        $('.sportlife-case .block-3 img').css('width','');
        $('.sportlife-case .block-3 .block-title').insertAfter($('.sportlife-case .block-3 img'));

        // zakazaka
        $('.zakazaka-case table img').css('width', '');
        replaceImage($('.zakazaka-case .step-1 table table td:first-child img'), '/phones-1.', '/phones-4.');
        replaceImage($('.zakazaka-case .results img'), '/screen', '/m-screen');
    }
});


var replaceImage = function(selector, path_old, path_new) {
    if (selector.length) {
        if (!!path_old) { path_old = '/img-'; }
        if (!!path_new) { path_new = '/m-img-'; }
        selector.attr('src', selector.attr('src').replace(path_old, path_new));
    }
}