var MODAL =
{
	show: function( selector )
	{
		MODAL.hide();

		$( selector ).show();
		$( selector ).attr('data-offset', $(window).scrollTop() );
		$('.container').addClass('opened-modal');
		$('body').addClass('no-footer');

		MODAL.toMiddle( selector );

		$( window ).resize( function()
		{
			MODAL.toMiddle( selector );
			$( selector ).width( $('.container').width() );
		});
	},
	hide: function()
	{
		$('body').removeClass('no-footer');
		$('.modal' ).hide();
		$('.container').removeClass('opened-modal');
	},
	toMiddle: function( selector )
	{
		var marginTop = ( $( selector ).height() - $( selector + ' > div' ).not('.close').outerHeight() ) / 2;
		var marginLeft = ( $( selector ).width() - $( selector + ' > div' ).not('.close').outerWidth() ) / 2;

		var overflow_y = 'hidden';

		if ( $( selector + ' > div' ).not('.close').height() > $( selector ).height() )
			overflow_y = 'scroll';

		$( selector ).css( 'overflow-y', overflow_y );

		if ( marginTop < 0 )
			marginTop = 0;

		$( selector + ' > div' ).not('.close')
			.css( 'margin-top', marginTop )
			.css( 'margin-left', marginLeft );
	},

	showMobileElements : function() {
		width = $(window).width();
		if (device.mobile()) {
			$('.mobile-hide').hide();
			$('.modal.modal-request').appendTo('.request > div'); //feedback form
			$('.modal.modal-vacancy').appendTo('.vacancies > div'); //vacancy form
			$('.mobile .company-index-container .list-vacancies a.openmodal').removeClass('openmodal');

			//menu
			$('.menu-wrapper').addClass('mobile-menu').appendTo('.container');
			$('.contacts-index-container').siblings('.footer').removeClass('dark').addClass('yellow');
		}
		else {
            $('.menu-wrapper.mobile-menu').removeClass('mobile-menu').appendTo('.header');
        }
	}

};

$(function()
{
    $( document ).on('click', '.modal > div', function(e)
    {
        e.stopPropagation();
    });
    
    $( document ).on('click', '.openmodal', function(e)
	{
		e.preventDefault();
		var modal = $(this).attr('data-modal');
		MODAL.show('.modal.' + modal);
		return false;
	});
    
	$( document ).on('click', '.modal .close, .btn-close-modal', function(e)
	{
		e.preventDefault();
		MODAL.hide();
		return false;
	});

	// request form reason captions
	if (device.mobile()) {
		$('.request.wrapper h1').text($('.reason-block[data-value="' + $('#RequestForm_reason option:first').text() + '"] .caption').text());
		$('.request.wrapper h3').text($('.reason-block[data-value="' + $('#RequestForm_reason option:first').text() + '"] .description').text());
		$('#RequestForm_reason').on('change', function (e) {
			$('.request.wrapper h1').text($('.reason-block[data-value="' + $(this).val() + '"] .caption').text());
			$('.request.wrapper h3').text($('.reason-block[data-value="' + $(this).val() + '"] .description').text());
		});

	} else {
		$('.reason-block[data-value="' + $('#RequestForm_reason option:first').text() + '"]').show();
		$('#RequestForm_reason').on('change', function (e) {
			$('.reason-block').hide();
			$('.reason-block[data-value="' + $(this).val() + '"]').show();
		});
	}

	//mobile menu
    $('.menu-button').on('click', function() {
        $(this).toggleClass('menu-closed menu-opened');
        $('.mobile-menu').toggle('fade', 500);
        $('.header').toggleClass('mobile-header');

        if ($('.header').hasClass('mobile-header')) {
            $('.header').animate({
                backgroundColor: 'transparent',
                borderColor: 'transparent',
            }, 500);
        }
        else {
            $('.header').animate({
                backgroundColor: 'rgba(255,255,255, 0.2)',
                borderColor: 'rgba(255,255,255, 0.2)',
            }, 500);
        }

    });


});
