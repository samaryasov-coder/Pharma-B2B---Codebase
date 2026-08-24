(function($) {
	$.fn.fHierarchical = function(options) {
		$.fHierarchical = {
			tree: null,
			options: null,
			itemClassSelector: null,
			init: function(tree, options) {
				this.tree = tree;
				this.options = options;
				if(typeof(this.options['draggableSelector']) == 'undefined') {
					this.options['draggableSelector'] = 'li.dr';
				}
				if(typeof(this.options['treeClass']) == 'undefined') {
					this.options['treeClass'] = 'fhierarchical-tree';
				}
				if(typeof(this.options['itemClass']) == 'undefined') {
					this.options['itemClass'] = 'fhierarchical-item';
				}
				if(typeof(this.options['hoverClass']) == 'undefined') {
					this.options['hoverClass'] = 'fhierarchical-highlighted';
				}
				if(typeof(this.options['activeClass']) == 'undefined') {
					this.options['activeClass'] = 'fhierarchical-ready';
				}
				if(typeof(this.options['newpositionClass']) == 'undefined') {
					this.options['newpositionClass'] = 'fhierarchical-newposition-placeholder';
				}
				if(typeof(this.options['itemType']) == 'undefined') {
					this.options['itemType'] = 'fhierarchical-item';
				}
				if(typeof(this.options['itemUrl']) == 'undefined') {
					this.options['itemUrl'] = '#';
				}
				if(typeof(this.options['iconClass']) == 'undefined') {
					this.options['iconClass'] = 'fa-plus-circle fhierarchical-inline-item-icon';
				}
				this.itemClassSelector = '.'+this.options['itemClass'];
				this.itemClassSelector = this.itemClassSelector.replace(' ', '.');
				this.initTree();
				return this;
			},
			initTree: function() {
				var self = this;
				if(!self.tree.hasClass('draggable-initialized')) {
					self.tree.find(this.options['draggableSelector']).draggable('destroy');
				}
				self.tree.find(this.options['draggableSelector']).draggable({
					cursorAt: { left: 5 },
					opacity: 0.75,
					refreshPositions: true,
					distance: 10,
					helper: function() {
						var t = $(this);
						if (!t.hasClass('dr')) {
							t = t.closest('li');
						}
						return t.clone().addClass('ui-draggable dr-helper').css({
							position: 'absolute'
						}).prependTo(self.tree);
					},
				});
				if(!self.tree.hasClass('draggable-initialized')) {
					self.tree.find(self.itemClassSelector).droppable('destroy');
				}
				self.tree.find(self.itemClassSelector).droppable({
					greedy: true,
					hoverClass: self.options['hoverClass'],
					tolerance: 'pointer',
					drop: function(event, ui) {
						var draggable = $(ui.draggable[0]);
						var type = draggable.attr('data-type');
						if (!draggable.hasClass('dr')) {
							draggable = draggable.closest('li');
						}
						if(type == self.options['itemType']) {
							var destination = $(this).closest('li');
							if(!destination.children('ul.menu').length) {
								destination.append('<ul class="menu"><li class="fhierarchical-newposition '+self.options['newpositionClass']+' ui-droppable" data-type="'+self.options['itemType']+'"></li></ul>');
								destination.find(self.itemClassSelector).prepend('<i class="w-icon fas fa-caret-down overhanging collapse-handler darr"></i>');
								destination.find(self.itemClassSelector+' .fa-empty').remove();
							}
							var target = destination.children('ul.menu');
							var oldpos = draggable.closest('ul.menu');
							var handler = draggable.prev('.'+self.options['newpositionClass']);
							draggable.detach().appendTo(target);
							handler.detach().appendTo(target);
							if(oldpos.find('li').length < 3) {
								oldpos.closest('li').find(self.itemClassSelector).children('.collapse-handler').remove();
								oldpos.closest('li').find(self.itemClassSelector).prepend('<i class="w-icon fas fa-empty"></i>');
								oldpos.remove();
							}

							var dragged_id = draggable.attr('data-id');
							var parent_id = $(this).attr('data-id');
							if(typeof(self.options['onItemMove']) !== 'undefined') {
								self.options['onItemMove'](dragged_id, parent_id, null);
							}
						}
					}
				});
				if(!self.tree.hasClass('draggable-initialized')) {
					self.tree.find('.'+self.options['newpositionClass']).droppable('destroy');
				}
				let accept = '.dr[data-type="'+self.options['itemType']+'"]';
				if (this.options['draggableSelector'] !== 'li.dr') {
					accept = this.options['draggableSelector']+'[data-type="'+self.options['itemType']+'"]';
				}
				self.tree.find('.'+self.options['newpositionClass']).droppable({
					greedy: true,
					accept: accept,
					activeClass: self.options['activeClass'],
					hoverClass: self.options['hoverClass'],
					tolerance: 'pointer',
					drop: function(event, ui) {
						var draggable = $(ui.draggable[0]);
						var oldpos = draggable.closest('ul.menu');
						var handler = draggable.prev('.'+self.options['newpositionClass']);
						draggable.detach().insertBefore($(this));
						handler.detach().insertBefore(draggable);
						if(oldpos.find('li').length < 3) {
							oldpos.closest('li').find(self.itemClassSelector).children('.collapse-handler').remove();
							oldpos.closest('li').find(self.itemClassSelector).prepend('<i class="w-icon fas fa-empty"></i>');
							oldpos.remove();
						}
						var dragged_id = draggable.attr('data-id');
						var parent_id = 0;
						if($(this).parent().parent().hasClass('dr')) {
							parent_id = $(this).parent().closest('li').children(self.itemClassSelector).attr('data-id');
						}
						var before_id = null;
						if($(this).next('.dr').length) {
							before_id = $(this).next('.dr').attr('data-id');
							if(before_id == dragged_id) {
								before_id = null;
							}
						}
						if(typeof(self.options['onItemMove']) !== 'undefined') {
							self.options['onItemMove'](dragged_id, parent_id, before_id);
						}
					}
				});
				$('body').off('click', self.options['treeClass']+' .collapse-handler');
				$('body').on('click', self.options['treeClass']+' .collapse-handler', function(e) {
					var state = 0;
					if($(this).hasClass('rarr')) {
						$(this).closest('li').children('ul.menu').show();
						$(this).removeClass('rarr').addClass('darr');
						state = 1;
					}
					else {
						$(this).closest('li').children('ul.menu').hide();
						$(this).removeClass('darr').addClass('rarr');
					}
					var item_id = $(this).closest('li').attr('data-id');
					if(typeof(self.options['onItemToggle']) !== 'undefined') {
						e.preventDefault();
						return self.options['onItemToggle'](item_id, state);
					}
					e.preventDefault();
					return false;
				});
				self.tree.addClass('draggable-initialized');
			},
			addItem: function(item) {
				var self = this;
				var target = self.tree.children('ul.menu');
				var toggler = null;
				var draftClass = '';
				if(parseInt(item.is_draft) > 0) {
					draftClass = 'gray';
				}
				if(self.tree.find('.dr[data-id="'+item.parent_id+'"]').length) {
					target = self.tree.find('.dr[data-id="'+item.parent_id+'"]');
					toggler = target.children(self.itemClassSelector).children('.collapse-handler');
					if(!target.children('ul.menu').length) {
						target.append('<ul class="menu"><li class="fhierarchical-newposition '+self.options['newpositionClass']+' ui-droppable" data-type="'+self.options['itemType']+'"></li></ul>');
						target.find(self.itemClassSelector).prepend('<i class="w-icon fas fa-caret-down overhanging collapse-handler darr"></i>');
						target.find(self.itemClassSelector+' .fa-empty').remove();
					}
					target = target.children('ul.menu');
				}
				var data_action = '';
				if (item.action) {
					data_action = ' data-action="'+item.action+'"';
				}
				target.prepend('\
					<li class="dr ui-draggable" data-type="'+self.options['itemType']+'" data-id="'+item.id+'">\
						<a href="'+self.options['itemUrl']+item.id+'" class="fhierarchical-item '+self.options['itemClass']+' ui-droppable" data-id="'+item.id+'" data-params="id='+item.id+'"'+data_action+'>\
							<i class="w-icon fas fa-empty"></i>\
							<span class="name '+draftClass+'">'+item.name+'</span><strong class="small highlighted count-new"></strong>\
							<span class="count"><i class="w-icon fas '+self.options['iconClass']+' fhierarchical-'+self.options['itemType']+'-create mr5" data-parent="'+item.id+'"></i></span>\
						</a>\
					</li>');
				target.prepend('<li class="fhierarchical-newposition '+self.options['newpositionClass']+' ui-droppable" data-type="'+self.options['itemType']+'"></li>');
				if(toggler != null) {
					if(toggler.length) {
						if(toggler.hasClass('rarr')) {
							toggler.trigger('click');
						}
					}
				}
				self.initTree();
			},
			renameItem: function(item) {
				var self = this;
				var c = self.options['itemClass'];
				c = c.replace(' ', '.');
				self.tree.find('.'+c+'[data-id="'+item.id+'"]').find('.name').text(item.name);
				if(item.is_draft == '1') {
					self.tree.find('.'+c+'[data-id="'+item.id+'"]').find('.name').addClass('gray');
				}
				else {
					self.tree.find('.'+c+'[data-id="'+item.id+'"]').find('.name').removeClass('gray');
				}
			}
		}
		return $.fHierarchical.init($(this), options);
	};
})(jQuery);