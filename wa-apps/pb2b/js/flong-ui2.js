(function($) {
	$.fn.fLong = function(options) {
		var t = $(this);
		var requests = new Array();
		var cleanup = 0;
		var btn = t.find('.long-action-start');
		var controls = t.find('.long-action-controls');
		var progress = t.find('.long-action-progress');
		var results = t.find('.long-action-results');
		var bar = progress.find('.progressbar-inner');
		var progressValue = progress.find('.progressbar-text');
		var progressDescription = t.find('.progress-description');
		var total = results.find('.long-action-total');
		var duration = results.find('.long-action-duration');
		btn.click(function() {
			var url = btn.attr('data-url');
			var p = btn.attr('data-post');
			cleanup = 0;
			controls.hide();
			results.hide();
			progress.show();
			bar.css( { 'width': '0%' } );
			progressValue.text('0%');
			if(options['onStart']) {
				options['onStart']();
			}
			$.post(url, p, function(jData) {
				if(jData.error) {
					progress.hide();
					controls.show();
					alert(jData.error);
					if(options['onError']) {
						options['onError'](jData);
					}
				}
				else {
					var process_id = jData.processId;
					requests.push(setTimeout(function () {
						processLong(url, process_id, jData);
					}, 1000));
					requests.push(setTimeout(function () {
						processLong(url, process_id, jData);
					}, 3000));
				}
			}, 'json');
		});
		function processLong(url, process_id, data) {
			if(data && data.ready) {
				var timer;
				while(timer = requests.pop()) {
					if(timer) { clearTimeout(timer); }
				}
				bar.css( { 'width': '100%' } );
				progressValue.text('100%');
				if(data.summary) {
					progressDescription.text(data.summary);
				}
				controls.show();
				results.show();
				progress.hide();
				if(data.real_count) {
					total.text(data.real_count);
				}
				else {
					total.text(data.count);
				}
				duration.text(data.time);
				if(!cleanup) {
					cleanup = 1;
					$.post(url, 'processId='+process_id+'&cleanup = 1', function(jData) {
						return;
					}, 'json');
				}
				if(options['onSuccess']) {
					options['onSuccess'](data);
				}
			}
			else if(data && data.error) {
				var timer;
				while(timer = requests.pop()) {
					if(timer) { clearTimeout(timer); }
				}
				alert(data.error);
				if(options['onError']) {
					options['onError'](jData);
				}
			}
			else {
				if(data) {
					bar.animate( { 'width': data.progress+'%' } );
					progressValue.text(data.progress+'%');
					if(data.summary) {
						progressDescription.text(data.summary);
					}
				}
				requests.push(setTimeout(function () {
					$.ajax({
						url: url,
						data: { 'processId': process_id, },
						dataType: 'json',
						type: 'post',
						success: function(response) {
							processLong(url, process_id, response);
						},
						error: function(jqXHR, textStatus, errorThrown) {
							$.shop.trace('report error', [textStatus, errorThrown, jqXHR.response]);
						}
					});
				}, 3000));
			}
		}
	};
})(jQuery);