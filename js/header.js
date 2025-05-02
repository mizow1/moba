(function($){
	$(function(){
		var $nav = $('#site-navigation');
		if(!$nav.length) return;
		var navOffset = $nav.offset().top;
		$(window).on('scroll', function(){
			if($(window).scrollTop() >= navOffset){
				$nav.addClass('fixed-site-navigation');
			} else {
				$nav.removeClass('fixed-site-navigation');
			}
		});
	});
})(jQuery);
