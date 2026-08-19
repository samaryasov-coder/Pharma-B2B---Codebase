(function($) {
	$.fTags = {
		target: null,
		namespace: 'tags',
		tags: {},
		init: function(target, options) {
			this.target = target;
			if(options.namespace) {this.namespace = options.namespace;}
			if(options.tags) {this.tags = options.tags;}
			this.target.append('<input type="text" class="f-tags-input">');
			this.input = this.target.find('.f-tags-input');
			this.initControls();
			this.initField();
			return this;
		},
		initControls: function() {
			var self = this;
			this.target.click(function() {
				self.input.focus();
			});
			this.input.keyup(function(e) {
				var t = $(this);
				if(e.keyCode == 13) {
					var v = t.val();
					self.appendTag(v);
					t.val('');
					e.preventDefault();
				}
			});
			this.input.keydown(function(e) {
				if(e.keyCode == 13) {
					e.preventDefault();
				}
			});
		},
		initField: function() {
			var self = this;
			var src = [];
			$.each(self.tags, function(index, tag) {
				src.push({label: tag.name, value: tag.name});
				if(!tag.is_active) {return;}
				self.appendTag(tag.name);
			});
			this.input.autocomplete({
				source: src,
				select: function(event, ui) {
					self.appendTag(ui.item.value);
					self.input.val('');
				},
				appendTo: self.input
			});
		},
		initElements: function() {
			this.target.find('div.new').off('click');
			this.target.find('div.new').click(function(e) {
				e.preventDefault();
				e.stopPropagation();
			});
			this.target.find('div.new .f-tag-remove').off('click');
			this.target.find('div.new .f-tag-remove').click(function(e) {
				$(this).closest('div').remove();
				e.preventDefault();
				e.stopPropagation();
			});
			this.target.find('div.new').removeClass('new');
		},
		appendTag: function(value) {
			var exists = 0;
			this.target.find('.f-tag-name').each(function() {
				if($(this).text() == value) {
					exists = 1;
					return;
				}
			});
			if(exists) {
				return;
			}
			this.input.before('<div class="new"><span class="f-tag-name">'+this.escapeHtml(value)+'</span><span class="f-tag-remove"><i class="fas fa-times"></i></span><input type="hidden" name="'+this.namespace+'[]" value="'+this.escapeHtml(value)+'"></div>');
			this.initElements();
		},
		escapeHtml: function(string) {
            return $("<div />").text(string).html();
        }
	}
})(jQuery);
(function($) {
	$.fn.fTags = function(options) {
		return $.fTags.init($(this), options);
	};
})(jQuery);