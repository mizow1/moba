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

		// ハンバーガーメニュー
		var $hamburger = $('.hamburger');
		function isSP() {
			return window.matchMedia('(max-width: 640px)').matches;
		}
		$hamburger.on('click', function(){
			if(!isSP()) return;
			$(this).toggleClass('is-active');
			$nav.toggleClass('is-open');
			// アクセシビリティ対応
			var expanded = $(this).attr('aria-expanded') === 'true';
			$(this).attr('aria-expanded', !expanded);
		});

		// ウィンドウリサイズ時、SP以外なら強制的に閉じる
		$(window).on('resize', function(){
			if(!isSP()){
				$nav.removeClass('is-open');
				$hamburger.removeClass('is-active');
				$hamburger.attr('aria-expanded', 'false');
			}
		});
	});
})(jQuery);
