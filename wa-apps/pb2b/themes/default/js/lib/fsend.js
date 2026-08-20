(function( $ ) {
	$.fn.fSend = function(options = {}) {
		$(this).submit(function(e) {
			e.preventDefault();
			const showInputErrors = (errors = {}) => {
				$('.input-wrap.error').each(function() {
					const name = $(this).find('[name]').attr('name');
					if (!(name in errors)) {
						$(this).removeClass('error');
						$(this).find('.input-hint').text('');
					}
				});
				$.each(errors, function(field, message) {
					const $input = $('[name="' + field + '"]');
					const $wrap= $input.closest('.input-wrap');
					const $hint = $wrap.find('.input-hint');
					$wrap.addClass('error');
					$hint.text(message);
				});
			}

			const viewMessage = (status, message) => {
				if (message === undefined || message === null || message === false || (Array.isArray(message) && message.length === 0))
					return;

				switch (status){
					case 'success': $.AlertManager.showSuccess(message); break;
					case 'warning': $.AlertManager.showWarning(message); break;
					case 'info': $.AlertManager.showInfo(message); break;
					case 'error': $.AlertManager.showError(message); break;
				}
			}

			const config = {
				prepareForm: null,
				onSuccess: null,
				onWarning: null,
				onInfo: null,
				onError: null,
				action: null,
				...options,
			};
			const formData = new FormData($(this)[0]);
			const $form = $(this);
			const action_url = config.action ?? $form.attr('action');
			let $submit_button = $form.find('[type="submit"], button[type="submit"]');
			if (!$submit_button.length)
				$submit_button = $(`[form="${$form.attr('id')}"]`);

			$submit_button.addClass('loading').attr('disabled', true);
			showInputErrors();
			if (typeof config.prepareForm === 'function'){
				const prepared = config.prepareForm(formData, $form);
				if (prepared === false) {
					$submit_button.removeClass('loading').prop('disabled', false);
					return false;
				}
			}

			$.ajax({
				url: action_url,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'json',
				error: function (reply) {
					viewMessage(result, (callbackMessage === true || callbackMessage === undefined) ? reply_data.message : callbackMessage);
				},
				complete: function (){
					$submit_button.removeClass('loading').prop('disabled', false);
				},
				success: function(reply) {
					let replyStatus = reply.status;
					let replyPayload = reply.payload;
					let callbackMessage = undefined;

					switch (replyStatus){
						case 'success': {
							if (typeof config.onSuccess === 'function')
								callbackMessage = config.onSuccess(replyPayload, $form);
							break;
						}
						case 'warning': {
							if (typeof config.onWarning === 'function')
								callbackMessage = config.onWarning(replyPayload, $form);
							break;
						}
						case 'info': {
							if (typeof config.onInfo === 'function')
								callbackMessage = config.onInfo(replyPayload, $form);
							break;
						}
						case 'error': {
							if (typeof config.onError === 'function')
								callbackMessage = config.onError(replyPayload, $form);
							break;
						}
					}

					showInputErrors(replyPayload.fields ?? {});
					viewMessage(replyStatus, (callbackMessage === true || callbackMessage === undefined) ? reply.message : callbackMessage);
				}
			});
			return false;
		});
	};
})(jQuery);