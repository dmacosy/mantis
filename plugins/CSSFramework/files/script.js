jQuery(function($){
	$(document).ready(function(){

		$('table').css({
			'border-spacing' : '1px',
			'border-collapse' : 'inherit'
		});

		$('table.hide').removeClass('hide').css('width', '100%');
		$('.navbar-fixed-top').next().children('a').remove();
//		$('.navbar-fixed-top').next().css('margin-top', $('.navbar-fixed-top').height() + 10 + 'px');
		$('.announcement-header').css({
//			'position' : 'fixed',
//			'top' : '100px',
//		'display ' : 'block'
		});

		$('.menu').parent().parent().parent().remove();

		$('.login-info-left').html('');
		
		var width = '';
/*		$.each( $('textarea'), function(k, elm){
			width = $(elm).parent().width() + 'px';
			$(elm).css('width', width);
		});*/
		$('table textarea').css('width', '95%');
/*
		$.each( $('input[type="text"]'), function(k, elm){
			width = $(elm).parent().width() + 'px';
			$(elm).css('width', width);
		});*/
		
		$('input[type="text"]').css('width', '60%');

		$('.button').addClass('btn btn-inverse').removeClass('button').css('display', 'inline-block');
		$('.button-small').addClass('btn btn-mini btn-inverse').removeClass('button').css('display', 'inline-block');

		$('form').addClass('form form-horizontal');

		$('input[id="ufile[]"]').css('width', 'inherit').attr('size', '100');

		$('input.small, input[name="dest_bug_id"]').css('width', '50px');

		$('input[name="search"]').css('width', '150px');

		$('a#history').css('text-decoration', 'none');

		$('#history').find('table').css({
			'border-spacing' : '0px',
			'border-collapse' : 'inherit'
		});

		$("td:contains('New relationship')").next().attr('width', '85%');
		$("td.category:contains('Note')").attr('width', '15%').next().attr('width', '85%');

		$('#filter_open').find('a').css({
			'color' : 'blue'
//			'font-weight' : 'bold'
		});

		$('img[src*="images/down.gif"]').after('<i class="icon-chevron-down"></i>');
		$('img[src*="images/down.gif"]').remove();
		$('img[src*="images/up.gif"]').after('<i class="icon-chevron-up"></i>');
		$('img[src*="images/up.gif"]').remove();

		$('.arrow').css('padding', '0px');

		$('ul').css('zoom', '1');

		$('select.small').addClass('span2').removeClass('small');

		//	Replace all link in bracket by a button
		$('.bracket-link').addClass('btn btn-inverse btn-mini').find('a').css('color', 'white');

		//	Remove bracket of link in bracket
		var txt = '';
		$.each( $('.bracket-link'), function(k, elm){
			txt = $(elm).html().replace('[', '').replace(']', '').replace(']', '');
			
			$(elm).html( $.trim( txt ));
		});

		//	Resize the calendar button 
		$('input[src*="calendar-img.gif"]').css('width', '20px');
		
		//	Wrap the content with the class container-fluid
		$('body').wrapInner('<div class="container-fluid">');
		
	});
});