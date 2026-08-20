(function( $ ) {
	$.fn.fSend = function(callback) {
		$(this).submit(function() {
			var form = $(this);
			var remote_location = form.attr('action');
			var message_field = form.find('.form-message');
			var submit_button = form.find('input[type="submit"]');
			var preloader = form.find('.form-preloader');
			submit_button.removeClass('green').removeClass('yellow').addClass('gray').attr('disabled', true);
			preloader.show();
			message_field.hide();
			$.post(remote_location, form.serialize(), function(jData) {
				preloader.hide();
				message_field.show();
				form.find('.field-error').hide();
				submit_button.removeClass('gray').removeAttr('disabled');
				if(jData.data.message) {
					message_field.html(jData.data.message);
				}
				if(jData.data.result == '1') {
					message_field.css('color', 'green');
					submit_button.addClass('green');
				}
				else {
					message_field.css('color', 'red');
					submit_button.addClass('yellow');
					if(jData.data.fields) {
						$.each(jData.data.fields, function(index, value) {
							if(value.length) {
								form.find('.field-error[data-field="'+index+'"]').html(value);
							}
							form.find('.field-error[data-field="'+index+'"]').show();
						});
					}
				}
				if(callback) {
					callback(jData, form);
				}
			}, 'json');
			return false;
		});
	};
})(jQuery);