(function( $ ) {
	$.fn.fSend = function(options = {}) {
		$(this).submit(function(e) {
			e.preventDefault();

			const config = {
				prepareForm: null,
				onSuccess: null,
				onError: null,
				action: null,
				...options,
			};

			const $form = $(this);
			const formData = new FormData($form[0]);
			const action_url = config.action ?? $form.attr('action');

			let $submit_button = $form.find('[type="submit"], button[type="submit"]');

			if (!$submit_button.length)
				$submit_button = $(`[form="${$form.attr('id')}"]`);

			const showDetails = (details = {}) => {
				$form.find('.input-wrap').each(function() {
					$(this).removeClass('error warning info success').find('.input-hint').text('');
				});

				$.each(details, function(target, detail) {
					const $input = $form.find(`[name="${target}"]`);
					const $wrap = $input.closest('.input-wrap');
					if (!$wrap.length)
						return;
					$wrap.addClass(detail.type);
					if (detail.message)
						$wrap.find('.input-hint').text(detail.message);
				});
			};

			const showMessage = (text, type) => {
				if (!text || !type)
					return;

				switch (type) {
					case 'success':
						$.AlertManager.showSuccess(text);
						break;

					case 'warning':
						$.AlertManager.showWarning(text);
						break;

					case 'info':
						$.AlertManager.showInfo(text);
						break;

					case 'error':
						$.AlertManager.showError(text);
						break;
				}
			};

			const handleMessage = (textMessage, typeMessage, callback, data) => {
				if (typeof callback !== 'function') {
					showMessage(textMessage, typeMessage);
					return;
				}

				const callbackMessage = callback(data, $form);
				if (callbackMessage === false)
					return;

				if (callbackMessage === undefined || callbackMessage === true)
					showMessage(textMessage, typeMessage);
			};

			$submit_button.addClass('loading').prop('disabled', true);

			showDetails();

			if (typeof config.prepareForm === 'function') {
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

				success: function(reply) {
					showDetails(reply.details);
					if (reply.status === 'ok')
						handleMessage(reply.message?.text, reply.message?.type, config.onSuccess, reply);
					else if (reply.status === 'fail')
						handleMessage(reply.message, 'error', config.onError, reply);
				},

				error: function(xhr) {
					const reply = xhr.responseJSON;
					console.log('XHR:', xhr);
					console.log('Response JSON:', reply);
					console.log('Response text:', xhr.responseText);
					if (!reply)
						return;
				},

				complete: function() {
					$submit_button.removeClass('loading').prop('disabled', false);
				}
			});

			return false;
		});
	};
})(jQuery);