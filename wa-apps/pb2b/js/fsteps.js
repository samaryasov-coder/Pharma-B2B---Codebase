(function($) {
	$.fn.fSteps = function(options) {
		$.fSteps = {
			target: null,
			dataForm: null,
			progressbar: null,
			deployButton: null,
			url: null,
			payload: null,
			start: 0,
			limit: 50,
			total: 0,
			onFinish: null,
			onError: null,
			ajaxRequest: null,
			init: function(target, options) {
				this.target = target;
				this.url = this.target.attr('action');
				if(options.dataForm) {this.dataForm = options.dataForm;}
				if(options.progressbar) {this.progressbar = options.progressbar;}
				if(options.deployButton) {this.deployButton = options.deployButton;}
				if(options.onFinish) {this.onFinish = options.onFinish;}
				if(options.onError) {this.onError = options.onError;}
				if(options.limit) {this.limit = options.limit;}
				this.initForm();
				return this;
			},
			initForm: function() {
				var self = this;
				this.progressbar.waProgressbar({percentage: 0});
				this.target.submit(function() {
					self.payload = self.target.serialize()
					self.deployButton.find('.icon').html('<i class="fas fa-spinner wa-animation-spin"></i>');
					self.deployButton.attr('disabled', true);
					self.step();
					return false;
				});
				this.deployButton.click(function() {
					self.target.trigger('submit');
				});
			},
			step: function() {
				var self = this;
				var data = self.payload+'&start='+this.start+'&limit='+this.limit
				if(self.ajaxRequest !== null) {self.ajaxRequest.abort(); self.ajaxRequest = null;}
				self.ajaxRequest = $.ajax({
					method: 'post',
					url: self.url,
					data: data,
					cache: false,
					success: function(jData) {
						if(jData.data.result == '1') {
							self.progressbar.show();
							self.start = parseInt(jData.data.start);
							self.total = parseInt(jData.data.total);
							if(self.start >= self.total) {
								self.progressbar.find('.progressbar-inner').css({'width': '100%'});
								self.progressbar.find('.progressbar-text').text('100%');
								if(typeof(self.onFinish) == 'function') {
									self.onFinish(jData);
								}
							}
							else {
								self.progressbar.find('.progressbar-inner').css({'width': parseFloat(self.start/self.total*100)+'%'});
								self.progressbar.find('.progressbar-text').text(Math.round(parseFloat(self.start/self.total*100)*100)/100+'%');
								self.step();
							}
						}
						else {
							self.abort();
							if(typeof(self.onError) == 'function') {
								self.onError(jData);
							}
						}
					},
					error: function(jqXHR, exception) {
						self.abort();
						if(typeof(self.onError) == 'function') {
							self.onError({
								result: 0,
								message: 'Ошибка связи'
							});
						}
					}
				});
			},
			deploy: function() {
				this.target.trigger('submit');
			},
			abort: function() {
				var self = this;
				if(self.ajaxRequest !== null) {self.ajaxRequest.abort(); self.ajaxRequest = null;}
				self.deployButton.find('.icon').html('<i class="fas fa-arrow-right"></i></span>');
				self.deployButton.removeAttr('disabled');
				self.progressbar.hide();
			}
		}
		return $.fSteps.init($(this), options);
	};
})(jQuery);