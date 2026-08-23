(function($) {
	$.fTransliterate = {
		target: null,
		lock: null,
		init: function(target, options) {
			this.target = target;
			this.options = options;
			if(typeof(this.options.input) == 'undefined') {return this;}
			if(typeof(this.options.url) == 'undefined') {return this;}
			if(this.target.val().length) {this.lock = 1;}
			else {this.lock = 0;}
			
			this.initFields();
			return this;
		},
		initFields: function() {
			var self = this;
			this.options.input.keyup(function() {
				self.deployTransliteration();
			});
			this.options.input.change(function() {
				self.deployTransliteration();
			});
		},
		deployTransliteration: function() {
			var self = this;
			if(self.lock == 1) {return;}
			$.post(self.options.url, 't='+encodeURIComponent(self.options.input.val()), function(jData) {
				if(jData.data.result == '1') {
					self.target.val(jData.data.transliterated);
				}
			}, 'json');
		},
		release: function() {
			this.lock = 0;
		}
	}
})(jQuery);
(function($) {
	$.fn.fTransliterate = function(options) {
		return $.fTransliterate.init($(this), options);
	};
})(jQuery);