window.addEventListener('oc_plugins_loaded', function() {
	$(window).resize(function() {
		var window_width = $(window).width();
		var width = $('#wcms_block_{$block_id|escape} .wcms-block-videosrutube-video-responsive').width();
		var height = width/16*9;
		$('#wcms_block_{$block_id|escape} .wcms-block-videosrutube-video-responsive').css('height', height+'px');
		$('#wcms_block_{$block_id|escape} .wcms-block-videosrutube-video-responsive iframe').css('height', height+'px');
	});
	$(window).scroll(function() {
		var t = $('#wcms_block_{$block_id|escape} .wcms-block-videosrutube');
		if(t.attr('data-loaded') == '1') {
			return;
		}
		var dtop = $(window).scrollTop();
		var dbottom = dtop + $(window).height();
		var etop = t.offset().top;
		var ebottom = etop + t.height();
		
		if(etop < dbottom && ebottom > dtop) {
			t.attr('data-loaded', 1);
			$('#wcms_block_{$block_id|escape} .wcms-block-videosrutube-video-responsive').each(function() {
				var v = $(this;)
				var provider = v.attr('data-video-provider');
				if(provider == 'rutube') {
					v.html('<iframe width="560" height="315" src="https://rutube.ru/play/embed/'+e.attr('data-video-code')+'" frameBorder="0" allow="clipboard-write;" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
				}
				if(provider == 'youtube') {
					v.html('<iframe width="560" height="315" src="https://www.youtube.com/embed/'+e.attr('data-video-code')+'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>');
				}
			});
			$(window).resize();
		}
	});
	$(window).resize();
	$(window).scroll();
});