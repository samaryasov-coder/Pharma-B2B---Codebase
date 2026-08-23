(function($) {
	$.fn.fSend = function(options) {
		$.fSend = {
			target: null,
			callback: null,
			url: null,
			deployButton: null,
			deployButtonSuccessClass: null,
			deployButtonErrorClass: null,
			message: null,
			messageSuccessClass: null,
			messageErrorClass: null,
			preloader: null,
			preloaderHtml: null,
			basePreloaderHtml: null,
			ajaxRequest: null,
			ajaxMode: null,
			fieldErrorClass: null,
			init: function(target, options) {
				this.target = target;
				if(typeof(options) == 'function') {
					this.callback = options;
					options = new Object();
				}
				if(typeof(options) == 'undefined') {
					options = new Object();
				}
				if(options.onComplete) {this.callback = options.onComplete;}
				if(options.url) {this.url = options.url;}
				else {this.url = this.target.attr('action');}
				if(options.deployButton) {
					this.deployButton = options.deployButton;
					var self = this;
					this.deployButton.click(function() {
						self.target.trigger('submit');
						return false;
					});
				}
				else {this.deployButton = this.target.find('input[type="submit"]');}
				
				if(options.message) {this.message = options.message;}
				else {this.message = this.target.find('.form-message');}
				if(options.preloader) {this.preloader = options.preloader;}
				else {this.preloader = this.target.find('.form-preloader');}
				
				if(options.deployButtonSuccessClass) {this.deployButtonSuccessClass = options.deployButtonSuccessClass;}
				else {this.deployButtonSuccessClass = 'green';}
				if(options.deployButtonErrorClass) {this.deployButtonErrorClass = options.deployButtonErrorClass;}
				else {this.deployButtonErrorClass = 'yellow';}
				
				if(options.messageSuccessClass) {this.messageSuccessClass = options.messageSuccessClass;}
				if(options.messageErrorClass) {this.messageErrorClass = options.messageErrorClass;}
				if(options.preloaderHtml) {
					this.basePreloaderHtml = this.preloader.html();
					this.preloaderHtml = options.preloaderHtml;
				}
				
				if(options.ajaxMode) {this.ajaxMode = options.ajaxMode;}
				else {this.ajaxMode = 'post';}
				if(options.fieldErrorClass) {this.fieldErrorClass = options.fieldErrorClass;}
				else {this.fieldErrorClass = 'field-error';}
				
				this.initForm();
				return this;
			},
			initForm: function() {
				var self = this;
				this.target.submit(function() {
					self.deployButton.removeClass(self.deployButtonSuccessClass).removeClass(self.deployButtonErrorClass).attr('disabled', true);
					if(self.preloaderHtml) {
						self.preloader.html(self.preloaderHtml);
					}
					self.preloader.show();
					self.message.hide();
					if(self.ajaxRequest !== null) {self.ajaxRequest.abort(); self.ajaxRequest = null;}
					
					if(self.ajaxMode != 'multipart') {
						self.ajaxRequest = $.ajax({
							method: 'post',
							url: self.url,
							data: self.target.serialize(),
							cache: false,
							success: function(jData) {
								self.parseResponse(jData);
							},
							error: function(jqXHR, exception) {
								if(typeof(self.callback) == 'function') {
									self.parseResponse({
										result: 0,
										message: 'Network Error'
									});
								}
							}
						});
					}
					else {
						var formData = new FormData(self.target[0]);
						$.ajax({
							type: 'post',
							url: self.url,
							data: formData,
							cache: false,
							contentType: false,
							processData: false,
							success: function(jData) {
								self.parseResponse(jData);
							},
							error: function(jData) {
								if(typeof(self.callback) == 'function') {
									self.parseResponse({
										result: 0,
										message: 'Network Error'
									});
								}
							}
						});
					}
					return false;
				});
			},
			parseResponse: function(jData) {
				var self = this;
				this.deployButton.removeAttr('disabled');
				this.target.find('.'+this.fieldErrorClass).hide();
				if(jData.data.result == '1') {
					if(this.messageSuccessClass && this.messageErrorClass) {
						this.message.removeClass(this.messageErrorClass).addClass(this.messageSuccessClass);
					}
					else {
						this.message.css('color', 'green');
					}
					this.deployButton.removeClass(this.deployButtonErrorClass).addClass(this.deployButtonSuccessClass);
				}
				else {
					if(this.messageSuccessClass && this.messageErrorClass) {
						this.message.removeClass(this.messageSuccessClass).addClass(this.messageErrorClass);
					}
					else {
						this.message.css('color', 'red');
					}
					this.deployButton.removeClass(this.deployButtonSuccessClass).addClass(this.deployButtonErrorClass);
					if(jData.data.fields) {
						$.each(jData.data.fields, function(index, value) {
							if(value.length) {
								self.target.find('.'+self.fieldErrorClass+'[data-field="'+index+'"]').html(value);
							}
							form.find('.'+self.fieldErrorClass+'[data-field="'+index+'"]').show();
						});
					}
				}
				setTimeout(function() {
					self.deployButton.removeClass(self.deployButtonSuccessClass).removeClass(self.deployButtonErrorClass);
				}, 700);
				if(jData.data.message) {
					this.message.html(jData.data.message).show();
				}
				if(this.preloaderHtml) {
					this.preloader.html(this.basePreloaderHtml);
				}
				else {
					this.preloader.hide();
				}
				if(typeof(this.callback) == 'function') {
					this.callback(jData, this.target);
				}
			}
		}
		var r = new Array();
		$(this).each(function(index, e) {
			var f = $.extend(true, {}, $.fSend);
			r.push(f.init($(this), options));
		});
		return r;
	};
})(jQuery);