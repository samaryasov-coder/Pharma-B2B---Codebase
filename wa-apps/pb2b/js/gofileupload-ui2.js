(function( $ ) {
	$.fn.goFileUpload = function(callback) {
		$.goFileUpload = {
			form: null,
			uploadCounter: null,
			jqXHR: null,
			content: null,
			callback: null,
			cancel: null,
			init: function(form, callback) {
				this.form = form;
				this.uploadCounter = 0;
				this.jqXHR = new Array();
				this.callback = callback;
				this.cancel = form.find('.l-cancel');
				this.initUpload();
			},
			initUpload: function() {
				var self = this;
				this.form.fileupload({
					dropZone: self.form,
					pasteZone: self.form,
					add: function(e, data) {
						self.uploadAdd(e, data);
					},
					done: function(e, data) {
						self.uploadDone(e, data);
					},
					progress: function (e, data) {
						self.uploadProgress(e, data);
					},
					fail: function (e, data) {
					}
				});
			},
			uploadAdd: function(e, data) {
				if(this.content === null) {
					this.content = this.form.find('.l-upload-notification').clone();
					this.cancel = this.content.find('.l-cancel');
					this.initCancelButton();
				}
				var self = this;
				$.waDialog({
					html: self.content,
					onClose: function(dialog) {
						dialog.hide();
						return false;
					}
				});
				$.each(data.files, function(index, value) {
					data.context = $('<div class="l-upload-status fresh">\
						<div class="l-upload-bar" style="width: 0%;"></div>\
						<div class="l-upload-progress">0%</div>\
						<div class="l-upload-name"><span>'+value.name+'</span></div>\
					</div>').appendTo(self.content.find('.l-upload-list'));
				});
				data.submit();
				this.uploadCounter += 1;
				this.jqXHR.push(data.jqXHR);
				this.cancel.text('Прервать').addClass('l-cancel-upload');
			},
			uploadDone: function(e, data) {
				var self = this;
				if(data.context) {
					data.context.each(function(index) {
						if(typeof(data.result.data[index]) == 'undefined') {
							$(this).addClass('l-progress-error');
							$(this).addClass('l-fresh-error');
							$(this).find('.l-upload-progress').remove();
							$(this).find('.l-upload-name span').css('font-weight', 'bold');
							$(this).find('.l-upload-name span').css('margin-right', '5px');
							$(this).find('.l-upload-name').append('Ошибка загрузки на стороне сервера');
						}
						else {
							var fData = data.result.data[index];
							if(fData.result == '1') {
								self.callback(fData);
							}
							else {
								$(this).addClass('l-progress-error');
								$(this).addClass('l-fresh-error');
								$(this).find('.l-upload-progress').remove();
								$(this).find('.l-upload-name span').css('font-weight', 'bold');
								$(this).find('.l-upload-name span').css('margin-right', '5px');
								$(this).find('.l-upload-name').append(fData.message);
							}
						}
					});
				}
				self.uploadCounter -= 1;
				if(self.uploadCounter == 0) {
					self.content.find('.l-cancel').text('Закрыть').removeClass('l-cancel-upload');
					if(self.content.find('.l-upload-list').find('.l-fresh-error').length) {
						self.content.find('.l-upload-errors').show();
					}
					else {
						self.content.find('.js-close-dialog').trigger('click');
					}
					self.content.find('.l-upload-status').removeClass('fresh');
					self.jqXHR = new Array();
					self.content = null;
				}
			},
			uploadProgress: function(e, data) {
				var p = parseInt(data.loaded / data.total * 100, 10);
				data.context.find('.l-upload-bar').css('width', p+'%');
				data.context.find('.l-upload-progress').text(p+'%');
			},
			initCancelButton: function() {
				var self = this;
				this.cancel.click(function() {
					if($(this).hasClass('l-cancel-upload')) {
						$.each(self.jqXHR, function(index, value) {
							value.abort();
						});
						self.jqXHR = new Array();
						self.content.find('.l-upload-status.fresh').remove();
						$(this).text('Закрыть').removeClass('l-cancel-upload');
						self.uploadCounter = 0;
						return false;
					}
				});
			}
		}
		$.goFileUpload.init($(this), callback);
	};
})(jQuery);