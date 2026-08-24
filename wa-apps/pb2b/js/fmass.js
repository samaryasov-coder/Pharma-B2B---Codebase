(function($) {
	$.fn.fMass = function(options) {
		$.fMass = {
			target: null,
			countUrl: null,
			itemHash: '',
			fullHash: '',
			allCheckboxClass: null,
			itemCheckboxClass: null,
			counter: null,
			onOperationStart: null,
			count: 0,
			exLock: 0,
			ajaxRequest: null,
			init: function(target, options) {
				this.target = target;
				this.countUrl = this.target.attr('data-count-url');
				if(options.itemHash) {this.itemHash = options.itemHash;}
				if(options.fullHash) {this.fullHash = options.fullHash;}
				if(options.allCheckboxClass) {this.allCheckboxClass = options.allCheckboxClass;}
				if(options.itemCheckboxClass) {this.itemCheckboxClass = options.itemCheckboxClass;}
				if(options.counter) {this.counter = options.counter;}
				else {this.counter = this.target.find('.mass-operations-count');}
				if(options.onOperationStart) {this.onOperationStart = options.onOperationStart;}
				this.initDropdown();
				this.initTable();
				return this;
			},
			initDropdown: function() {
				var self = this;
				this.target.waDropdown({
					hover: true
				});
				this.target.find('.dropdown-body .menu a').click(function() {
					var t = $(this);
					var id = t.attr('data-id');
					if(typeof(self.onOperationStart) == 'function' && self.count > 0 && self.exLock == 0) {
						self.lock();
						self.onOperationStart(id, self.getHash(), function() {
							self.unlock();
						});
					}
					return false;
				});
			},
			initTable: function() {
				var self = this;
				$('body').off('change', self.itemCheckboxClass);
				$('body').on('change', self.itemCheckboxClass, function() {
					var allChecked = 1;
					$(self.itemCheckboxClass).each(function() {
						if(!$(this).prop('checked')) {
							allChecked = 0;
						}
					});
					if(allChecked) {
						$(self.allCheckboxClass).prop('checked', true);
					}
					else {
						$(self.allCheckboxClass).prop('checked', false);
					}
					self.applySelection();
				});
				$('body').off('change', self.allCheckboxClass);
				$('body').on('change', self.allCheckboxClass, function() {
					var t = $(this);
					var checked = t.prop('checked');
					if(checked) {
						$(self.itemCheckboxClass).prop('checked', true);
					}
					else {
						$(self.itemCheckboxClass).prop('checked', false);
					}
					self.applySelection();
				});
			},
			applySelection: function() {
				var self = this;
				self.count = null;
				self.setDropdownState(0, null);
				if(self.ajaxRequest !== null) {self.ajaxRequest.abort(); self.ajaxRequest = null;}
				self.ajaxRequest = $.ajax({
					method: 'post',
					url: self.countUrl,
					data: 'hash='+self.getHash(),
					cache: false,
					success: function(jData) {
						var c = parseInt(jData.data.count);
						self.count = c;
						if(c > 0) {
							self.setDropdownState(1, self.count);
						}
						else {
							self.setDropdownState(0, self.count);
						}
					},
					error: function(jqXHR, exception) {
						self.count = 0;
						self.setDropdownState(0, self.count);
					}
				});
			},
			getHash: function() {
				var self = this;
				var allChecked = $(self.allCheckboxClass).prop('checked');
				if(allChecked) {
					return self.fullHash;
				}
				else {
					var ids = new Array();
					$(self.itemCheckboxClass).each(function() {
						var t = $(this);
						if(t.prop('checked')) {
							ids.push(t.val());
						}
					});
					if(ids.join(',').length == 0) {
						ids.push(0);
					}
					return self.itemHash+ids.join(',');
				}
			},
			setDropdownState: function(state, count) {
				if(this.exLock == 0) {
					if(state) {
						this.target.find('.dropdown-toggle').addClass('green').removeClass('gray');
					}
					else {
						this.target.find('.dropdown-toggle').removeClass('green').addClass('gray');
					}
				}
				else {
					this.target.find('.dropdown-toggle').removeClass('green').addClass('gray');
				}
				if(count !== null) {
					this.counter.html(this.count);
				}
				else {
					this.counter.html('<i class="fas fa-spinner wa-animation-spin"></i>');
				}
			},
			lock: function() {
				this.exLock = 1;
				this.target.find('.dropdown-toggle').removeClass('green').addClass('gray');
			},
			unlock: function() {
				this.exLock = 0;
				this.target.find('.dropdown-toggle').addClass('green').removeClass('gray');
			}
		}
		return $.fMass.init($(this), options);
	};
})(jQuery);