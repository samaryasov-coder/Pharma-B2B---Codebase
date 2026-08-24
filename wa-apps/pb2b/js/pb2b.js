(function($) {
	$.pb2b = {
		siteId: null,
		ajaxContent: null,
		article: null,
		prevHash: null,
		pageTree: null,
		pageTreeClass: null,
		pageAddIconClass: null,
		pages: null,
		blogTree: null,
		albumTree: null,
		calendarTree: null,
		navigation: null,
		navigationBlockClass: null,
		ajaxRequest: null,
		progressbar: null,
		siteSelector: null,
		sidebar: null,
		sidebarToggler: null,
		init: function() {
			this.siteId = parseInt($('.active-site-id').val());
			this.ajaxContent = $('.pb2b-ajax-content');
			this.article = $('.pb2b-base-article');
			this.pageTree = $('.pb2b-sidebar-pages');
			this.pageTreeClass = '.pb2b-sidebar-pages';
			this.pageAddIconClass = '.fhierarchical-page-create';
			this.blogTree = $('.pb2b-sidebar-blogs');
			this.albumTree = $('.pb2b-sidebar-albums');
			this.calendarTree = $('.pb2b-sidebar-calendars');
			this.navigation = $('.pb2b-sidebar-bricks');
			this.navigationBlockClass = '.pb2b-sidebar-content-block';
			this.progressbar = $.waLoading();
			this.siteSelector = $('.pb2b-site-selector');
			this.sidebar = $('.pb2b-main-sidebar');
			this.sidebarToggler = $('.pb2b-main-sidebar-toggle');
			this.initNavigation();
			this.initPageTree();
			this.initBlogTree();
			this.initAlbumTree();
			this.initCalendarTree();
			this.initErrorHandler();
			this.prevHash = ['#', 'dashboard', 'stats'];
			var self = this;
			$(window).on('hashchange', function() {
				self.dispatch();
			});
			self.dispatch();
		},
		initNavigation: function() {
			var self = this;
			this.navigation.find('.brick').click(function() {
				var t = $(this);
				var block = t.attr('data-block');
				if(t.hasClass('accented')) {
					return false;
				}
				self.navigation.find('.brick').removeClass('accented');
				self.navigation.find('.brick').removeClass('selected');
				t.addClass('accented');
				t.addClass('selected');
				$(self.navigationBlockClass).addClass('hidden');
				$(self.navigationBlockClass+'[data-block="'+block+'"]').removeClass('hidden');
				$.post('?module=backend&action=setSidebarMode', 'mode='+block, function(jData) {
					return;
				}, 'json');
				return false;
			});
			if(!this.navigation.find('.brick.selected').length && this.navigation.find('.brick').length) {
				this.navigation.find('.brick').first().trigger('click');
			}
			this.siteSelector.waDropdown({
				hover: false
			});
			this.siteSelector.find('a').click(function() {
				var t = $(this);
				var id = $(t).attr('data-id');
				if(id == 'new') {
					return;
				}
				var url = self.siteSelector.attr('data-toggle-url');
				$.post(url, 'id='+id, function(jData) {
					if(jData.data.result == '1') {
						window.location.href = wa_app_url;
					}
					else {
						alert(jData.data.message);
					}
				}, 'json');
				return false;
			});
			this.sidebarToggler.click(function() {
				if(!self.sidebar.hasClass('opened')) {
					self.sidebar.addClass('opened');
				}
				else {
					self.sidebar.removeClass('opened');
				}
			});
			this.sidebar.find('.sidebar-footer a').click(function() {
				self.sidebar.removeClass('opened');
			});
		},
		initPageTree: function() {
			var self = this;
			if(!self.pageTree.length) {return;}
			$('body').off('click', self.pageAddIconClass);
			$('body').on('click', self.pageAddIconClass, function() {
				window.location.hash = '#/page/create/parent_id='+$(this).attr('data-parent');
				self.dispatch();
				return false;
			});
			this.pages = self.pageTree.fHierarchical({
				'treeClass': self.pageTreeClass,
				'itemType': 'page',
				'itemUrl': '#/page/edit/id=',
				'iconClass': 'fa-plus-circle fhierarchical-inline-item-icon',
				'newpositionClass': 'fhierarchical-newposition-page',
				'itemClass': 'fhierarchical-item-page',
				'onItemMove': function(draggedId, parentId, beforeId) {
					var before_condition = '';
					if(beforeId !== null) {
						before_condition = '&before_id='+beforeId;
					}
					$.post('?module=page&action=move', 'id='+draggedId+'&parent_id='+parentId+before_condition, function(jData) {
						if(jData.data.result != '1') {
							alert(jData.data.message);
						}
					}, 'json');
				},
				'onItemToggle': function(itemId, state) {
					$.post('?module=page&action=setState', 'id='+itemId+'&state='+state, function(jData) {
						return;
					}, 'json');
				}
			});
		},
		initErrorHandler: function() {
			var self = this;
			$.wa.errorHandler = function () {};
			$(document).ajaxError(function(e, xhr, settings, exception) {
                if(xhr.status === 502 && exception == 'abort' || (settings.url && settings.url.indexOf('background_process') >= 0) || (settings.data && settings.data.indexOf('background_process') >= 0)) {
                    console && console.log && console.log('Notice: XHR failed on load: '+ settings.url);
                    return;
                }
                if(xhr.responseText) {
                    var iframe = $('<iframe src="about:blank" style="width: 100%; height: auto; min-height: 500px; border: 1px solid lightgray;"></iframe>');
                    self.toggleContentPadding(0);
					self.ajaxContent.empty().append(iframe);
					var ifrm = (iframe[0].contentWindow) ? iframe[0].contentWindow : (iframe[0].contentDocument.document) ? iframe[0].contentDocument.document : iframe[0].contentDocument;
                    ifrm.document.open();
                    ifrm.document.write(xhr.responseText);
                    ifrm.document.close();
                    $('.dialog:visible').trigger('close').remove();
                }
            });
		},
		addPage: function(page) {
			this.pages.addItem(page);
		},
		updatePage: function(page) {
			this.pages.renameItem(page);
		},
		initBlogTree: function() {
			var self = this;
			this.blogTree.sortable( { 
				items: "> li",
				handle: ".fa-sort",
				onUpdate: function(event, ui) {
					var itemsArray = new Array();
					self.blogTree.find('li').each(function(index) {
						itemsArray.push($(this).attr('data-item'));
					});
					$.post('?module=site&action=sortBlogs', 'site_id='+self.siteId+'&blogs='+itemsArray.join(','));
				}
			});
		},
		addBlog: function(item) {
			var self = this;
			this.blogTree.append('\
				<li data-item="'+item.id+'">\
					<a href="#/blog/edit/id='+item.id+'">\
						<i class="fas fa-sort"></i>\
						<span>'+self.escapeHtml(item.name)+'</span>\
					</a>\
				</li>\
			')
		},
		updateBlog: function(item) {
			this.blogTree.find('li[data-item="'+item.id+'"]').find('span').text(item.name);
		},
		initAlbumTree: function() {
			var self = this;
			this.albumTree.sortable( { 
				items: "> li",
				handle: ".fa-sort",
				onUpdate: function(event, ui) {
					var itemsArray = new Array();
					self.albumTree.find('li').each(function(index) {
						itemsArray.push($(this).attr('data-item'));
					});
					$.post('?module=site&action=sortAlbums', 'site_id='+self.siteId+'&albums='+itemsArray.join(','));
				}
			});
		},
		addAlbum: function(item) {
			var self = this;
			this.albumTree.append('\
				<li data-item="'+item.id+'">\
					<a href="#/album/edit/id='+item.id+'">\
						<i class="fas fa-sort"></i>\
						<span class="gray">'+self.escapeHtml(item.name)+'</span>\
					</a>\
				</li>\
			')
		},
		updateAlbum: function(item) {
			this.albumTree.find('li[data-item="'+item.id+'"]').find('span').text(item.name);
			if(parseInt(item.is_draft) > 0) {
				this.albumTree.find('li[data-item="'+item.id+'"]').find('span').addClass('gray');
			}
			else {
				this.albumTree.find('li[data-item="'+item.id+'"]').find('span').removeClass('gray');
			}
		},
		removeAlbum: function(item) {
			this.albumTree.find('li[data-item="'+item.id+'"]').remove();
		},
		initCalendarTree: function() {
			var self = this;
			this.calendarTree.sortable( { 
				items: "> li",
				handle: ".fa-sort",
				onUpdate: function(event, ui) {
					var itemsArray = new Array();
					self.calendarTree.find('li').each(function(index) {
						itemsArray.push($(this).attr('data-item'));
					});
					$.post('?module=site&action=sortCalendars', 'site_id='+self.siteId+'&calendars='+itemsArray.join(','));
				}
			});
		},
		addCalendar: function(item) {
			var self = this;
			this.calendarTree.append('\
				<li data-item="'+item.id+'">\
					<a href="#/calendar/edit/id='+item.id+'">\
						<i class="fas fa-sort"></i>\
						<span>'+self.escapeHtml(item.name)+'</span>\
					</a>\
				</li>\
			')
		},
		updateCalendar: function(item) {
			this.calendarTree.find('li[data-item="'+item.id+'"]').find('span').text(item.name);
		},
		escapeHtml: function(string) {
            return $("<div />").text(string).html();
        },
		dispatch: function(force) {
			if(typeof(force) == 'undefined') force = 0;
			var self = this;
			var hash = window.location.hash.replace('#', '').split('/');
			if(hash.length >= 3) {
				if(!hash[3]) {hash[3] = '';}

				var clean_data = self.removeHashParam(hash[3], 'tab');
				var prev = this.prevHash;
				this.prevHash = [hash[0], hash[1], hash[2], clean_data];

				if(hash[0] == prev[0] && hash[1] == prev[1] && hash[2] == prev[2] && clean_data == prev[3] && !force) {return;}
				if(self.ajaxRequest !== null) {self.progressbar.abort(); self.ajaxRequest.abort();}
				var url = '?module='+encodeURIComponent(hash[1]);
				if(hash[2].length && !this.isSelfDispatchingModule(hash[1])) url = url + '&action='+encodeURIComponent(hash[2]);

				if(!force && (prev[1] == hash[1] && this.isSelfDispatchingModule(hash[1]))) {
					self.sidebar.removeClass('opened');
					self.postDispatch(hash);
					return true;
				}
				var padding = this.isSelfPaddedModule(hash[1]);

				self.progressbar.show(10);
				self.ajaxRequest = $.ajax({
					method: 'post',
					url: url,
					data: hash[3],
					cache: false,
					xhr: function() {
						var xhr = new window.XMLHttpRequest();
						xhr.addEventListener("progress", function(e) {
							if(e.lengthComputable) {
								var p = e.loaded / e.total / 100 * 90;
								self.progressbar.show(10+p);
							}
						}, false);
						return xhr;
					},
					success: function(jData) {
						self.toggleContentPadding(padding);
						self.ajaxContent.html(jData);
						if(!(hash[0] == prev[0] && hash[1] == prev[1] && hash[2] == prev[2] && hash[3] == prev[3])) {
							$(window).scrollTop(0);
						}
						self.progressbar.done();
						self.onModuleLoad(hash[1]);
						self.sidebar.removeClass('opened');
						self.postDispatch(hash);
					},
					error: function(jqXHR, exception) {
						self.progressbar.abort();
						self.sidebar.removeClass('opened');
					}
				});
			}
		},
		postDispatch: function(hash) {
			// Webasyst design template bug fix
			init();
			if(hash[1] == 'design') {
				if(waDesignLoad) {
					waDesignLoad();
				}
			}
		},
		toggleContentPadding: function(is_not_padded) {
			if(is_not_padded) {
				this.ajaxContent.removeClass('article-body');
				this.article.removeClass('article');
			}
			else {
				this.ajaxContent.addClass('article-body');
				this.article.addClass('article');
			}
		},
		isSelfDispatchingModule: function(module) {
			var m = new Array('plugins', 'design', 'settings', 'flow', 'helpdesk');
			if(m.includes(module)) {return 1;}
			return 0;
		},
		isSelfPaddedModule: function(module) {
			var m = new Array('plugins', 'design', 'site', 'settings', 'flow', 'helpdesk');
			if(m.includes(module)) {return 1;}
			return 0;
		},
		onModuleLoad: function(module) {
			// Webasyst design template bug fix
			if(module == 'design') {
				if(waDesignLoad) {
					waDesignLoad();
				}
			}
		},
		reloadContent: function() {
			this.dispatch(true);
		},
		//tabs
		parseQueryString: function(str) {
			var result = {};
			if(!str) { return result; }
		
			var items = str.split('&');
			for(var i = 0; i < items.length; i++) {
				if(!items[i]) { continue; }
				var p = items[i].split('=');
				var key = decodeURIComponent(p[0] || '');
				if(!key.length) { continue; }
				var val = decodeURIComponent(p[1] || '');
				result[key] = val;
			}
			return result;
		},
		buildQueryString: function(params) {
			var parts = [];
			$.each(params, function(k, v) {
				if(typeof(v) == 'undefined' || v === null) { v = ''; }
				parts.push(encodeURIComponent(k) + '=' + encodeURIComponent(v));
			});
			return parts.join('&');
		},
		getHashData: function() {
			var hash = window.location.hash.replace('#', '').split('/');
			return (hash.length >= 4) ? (hash[3] || '') : '';
		},
		setHashData: function(data) {
			var hash = window.location.hash.replace('#', '').split('/');
			while(hash.length < 4) { hash.push(''); }
			hash[3] = data || '';
			window.location.hash = '#' + hash.join('/');
		},
		getHashParam: function(name) {
			var data = this.getHashData();
			var params = this.parseQueryString(data);
			return (typeof(params[name]) != 'undefined') ? params[name] : null;
		},
		setHashParam: function(name, value) {
			var data = this.getHashData();
			var params = this.parseQueryString(data);
			params[name] = value;
			this.setHashData(this.buildQueryString(params));
		},
		removeHashParam: function(data, name) {
			var params = this.parseQueryString(data || '');
			if(typeof(params[name]) != 'undefined') { delete params[name]; }
			return this.buildQueryString(params);
		},		
	}
})(jQuery);
let wa_pro_body = $('body');
let wa_pro_form = $('.js-form');
let wa_pro_data_tables = [];
let wa_pro_app = location.pathname.split('/')[2];
let wa_pro_dialog = false;
let wa_pro_drawer = false;
$.wa_pro = $[wa_pro_app];
(function( $ ) {
	$.fn.waproSortable = function(callback) {
		let table = $(this);
		let items = '> tr';
		let cont = table.find('tbody');
		if (table.find('tr').length === 0) {
			items = '.js-row';
			cont = table;
		}
		cont.sortable( {
			items: items,
			handle: '.js-sort',
			onUpdate: function(event, ui) {
				let itemsArray = new Array();
				table.find('.js-sort').each(function(index) {
					itemsArray.push($(this).attr('data-item'));
				});
				callback(itemsArray);
			},
			update: function(event, ui) {
				let itemsArray = new Array();
				table.find('.js-sort').each(function(index) {
					itemsArray.push($(this).attr('data-item'));
				});
				callback(itemsArray);
			}
		});
	};
	$.extend($[wa_pro_app], {
		action: {
			loaded: false,
			prevHash: null,
			action: null,
			params: null,
			item_id: null,
			submit: function (Data, Form) {
				this.beforeSubmit(Data, Form);
				if (Data.error) {
					if (Form.find('.js-form-message').length) {
						Form.find('.js-form-message').find('svg').hide();
						Form.find('.js-form-message').find('.fa-skull').show();
						Form.find('.js-form-message').find('.js-form-message-text').text(Data.message);
						Form.find('.js-form-message').removeClass('success').addClass('danger').show();
					} else {
						$.waDialog.alert({
							title: 'Возникла ошибка',
							text: Data.message,
							button_title: 'Понятно',
							button_class: 'warning',
						});
					}
				} else {
					this.afterSubmit(Data, Form);
				}
				Form.find('input[type="submit"]').prop('disabled', false);
			},
			beforeSubmit(Data, Form) {
			},
			afterSubmit(Data, Form) {
				if (Data.dispatch_url) {
					window.location.hash = Data.dispatch_url;
					if (wa_pro_drawer) {
						wa_pro_drawer.close();
					}
					if (wa_pro_dialog) {
						wa_pro_dialog.close();
					}
				} else {
					if (Form.find('.js-form-message').length) {
						Form.find('.js-form-message').find('svg').hide();
						Form.find('.js-form-message').find('.fa-check-circle').show();
						Form.find('.js-form-message').find('.js-form-message-text').text(Data.message ?? 'Данные сохранены');
						Form.find('.js-form-message').removeClass('danger').addClass('success').show();
					} else {
						$.waDialog.alert({
							title: 'Успех',
							text: 'Данные сохранены',
							button_title: 'Понятно',
						});
					}
				}
			}
		}
	});
})(jQuery);

function dataTablesReload() {
	for (let i = 0; i < wa_pro_data_tables.length; i++) {
		if (wa_pro_data_tables[i].data('action')) {
			wa_pro_data_tables[i].fnReloadAjax();
		}
	}
}

function sortableInit() {
	$('.js-sortable').each(function () {
		let table = $(this);
		table.waproSortable(function(items) {
			if (table.data('sort-action')) {
				$.post(table.data('sort-action'), {items: items});
			}
		});
	});
}

function dataTablesInit() {
	$('.js-data-table:not(.dataTable)').each(function () {
		let action = $(this).data('action') ? $(this).data('action') : false;
		if (action && $(this).data('hash')) {
			action += '&hash=' + encodeURIComponent($(this).data('hash'));
		}
		let min_length = $(this).data('min-length') ? $(this).data('min-length') : 20;
		let max_length = $(this).data('max-length') ? $(this).data('max-length') : 50;
		let lengthMenu = [[min_length, max_length], [min_length, max_length]];
		let order_col = 0;
		let order_dir = 'desc';
		let ordering = $(this).data('ordering') ? $(this).data('ordering') : '';
		let columns = [];
		if (ordering.length > 0) {
			ordering = ordering.split(',');
			for (let i = 0 ;i < $(this).find('th').length; i++) {
				if (!ordering.includes(i+'')) {
					columns[i] = i;
					if (order_col === i) {
						order_col++;
					}
				}
			}
		}
		let params = {
			processing: true,
			responsive: true,
			stateSave: true,
			columnDefs: [{ orderable: false, targets: columns}],
			order: [ order_col, order_dir ],
			serverSide:  action ? action : false,
			lengthMenu: lengthMenu,
			ajax: action,
			language:{
				sLengthMenu: 'Показывать _MENU_ записей',
				sZeroRecords: 'Нет записей, удовлетворяющих условиям поиска',
				sInfo: 'Отображаются записи с _START_ до _END_ из _TOTAL_',
				sInfoEmpty: 'Отображаются записи с 0 до 0 из 0',
				sInfoFiltered: '(отфильтровано из _MAX_ записей)',
				sSearch: 'Поиск: ',
				processing: 'Обработка...',
				oPaginate: { sNext: '>>', sPrevious: '<<' }
			}
		}
		$(this).attr('data-index', wa_pro_data_tables.length);
		wa_pro_data_tables[wa_pro_data_tables.length] = $(this).dataTable(params);
	});
}

function selectTwoInit() {
	$('.js-select-two:not(.select2-hidden-accessible)').each(function () {
		let that = $(this);
		let data = {};
		if (that.data('action') !== undefined) {
			data.ajax = {
				url: that.data('action'),
				data: function (params) {
					let val = '';
					let current_id = that.data('id') ? that.data('id') : that.val();
					let dependent_id = that.data('dependent') ? that.data('dependent') : null;
					if (dependent_id) {
						dependent_id = $('#' + dependent_id).val();
					}
					$('[data-group="'+that.data('group')+'"]').each(function () {
						if ($(this).val() !== 0 && $(this).val() !== null) {
							if (val.length > 0) {
								val = val+'+';
							}
							val = val+$(this).val();
						}
					});
					return {
						search: params.term,
						hash: that.data('hash'),
						group_id: val,
						g_id: that.data('group-id'),
						current_id: current_id,
						dependent_id: dependent_id,
						type: 'public'
					}
				},
				processResults: function (data) {
					return {
						results: data.data
					};
				},
				dataType: 'json'
			}
		}
		data.placeholder = that.data('placeholder');
		data.allowClear = that.data('allow-clear');
		$(this).select2(data);
	});
}

function switchInit() {
	$('.js-wa-switch').each(function () {
		let that = $(this);
		
		if (that.data('wa-switch-inited')) return;
		that.data('wa-switch-inited', 1);
		
		that.waSwitch({
			ready: function (wa_switch) {
				let $label = that.closest('.js-wa-switch').find('label').first();

				wa_switch.$label = $label;
				wa_switch.active_text = $label.data('active-text') || $label.text();
				wa_switch.inactive_text = $label.data('inactive-text') || $label.text();

				// let is_active = wa_switch.$input.prop('checked');
				// $label.text(is_active ? wa_switch.active_text : wa_switch.inactive_text);
			},
			change: function(active, wa_switch) {
				if (wa_switch.$label && wa_switch.$label.length) {
					wa_switch.$label.text(active ? wa_switch.active_text : wa_switch.inactive_text);
				}
			}
		});
	});
}

function tabsInit() {
	$('.js-tabs').each(function() {
		var $wrap = $(this);
		if($wrap.data('tabs-inited')) { return; }
		$wrap.data('tabs-inited', 1);

		var $controls = $wrap.find('.js-tabs-controls');
		var $panels = $wrap.find('.js-tabs-panels');

		var default_tab = $wrap.data('default-tab') || 'common';
		var param = $wrap.data('param') || 'tab';

		var showTab = function(tab) {
			if(!tab) { tab = default_tab; }
			if(!$panels.children('[data-tab="'+tab+'"]').length) { tab = default_tab; }

			$panels.children('[data-tab]').hide();
			$panels.children('[data-tab="'+tab+'"]').show();

			$controls.find('li[data-tab]').removeClass('selected');
			$controls.find('li[data-tab="'+tab+'"]').addClass('selected');
		};
		
		showTab($.pb2b.getHashParam(param));
		$controls.on('click', 'li[data-tab] a, li[data-tab]', function(e) {
			e.preventDefault();
			var tab = $(this).closest('li[data-tab]').attr('data-tab');
			$.pb2b.setHashParam(param, tab);
			showTab(tab);
			return false;
		});

		$(window).on('hashchange.pb2b_tabs', function() {
			showTab($.pb2b.getHashParam(param));
		});
	});
}

function init() {
	dataTablesInit();
	selectTwoInit();
	sortableInit();
	// dataTablesReload();
	switchInit();
	tabsInit();
}

$(document).ready(function () {
	wa_pro_form =  $('.js-form');
	wa_pro_body =  $('body');
	wa_pro_body.off('click', '.js-popup-drawer').on('click', '.js-popup-drawer', function (e) {
		e.preventDefault();
		$.get($(this).data('action'), $(this).data('params'), function (Template) {
			wa_pro_drawer = $.waDrawer({
				esc: false,
				html: Template,
				direction: 'right'
			});
			init();
		});
	});
	wa_pro_body.off('click', '.js-popup').on('click', '.js-popup', function (e) {
		e.preventDefault();
		$.get($(this).data('action'), $(this).data('params'), function (Template) {
			wa_pro_dialog = $.waDialog({
				esc: false,
				html: Template
			});
			init();
		});
	});
	wa_pro_body.off('click', '.js-popup-confirm').on('click', '.js-popup-confirm', function (e) {
		e.preventDefault();
		let self = $(this);
		let form = self.closest('form.js-form');
		if (form.length) {
			wa_pro_form = form;
		}
		$.waDialog.confirm({
			title: '<i class="fas fa-exclamation-triangle smaller state-error"></i> ' + (self.data('title') ?? 'Подтвердите действие'),
			text: self.data('message'),
			success_button_title: self.data('success'),
			cancel_button_title: self.data('cancel') ?? 'Отмена',
			onSuccess: function () {
				if (typeof $.wa_pro.action !== 'undefined' && self.data('action') in $.wa_pro.action) {
					$.wa_pro.action[self.data('action')](self.data('params'));
				}
			},
			onCancel: function () {
				if (typeof $.wa_pro.action !== 'undefined' && self.data('cancel-action') in $.wa_pro.action) {
					$.wa_pro.action[self.data('cancel-action')](self.data('params'));
				}
			}
		});
	});
	wa_pro_body.off('submit', '.js-form').on('submit', '.js-form', function (e) {
		e.preventDefault();
		let form = $(this);
		let url = form.attr('action');
		let useFormData = form.find('input[type="file"]').length > 0
			|| (form.prop('enctype') || '').toLowerCase().indexOf('multipart') !== -1;
		let ajaxOptions = {
			type: 'POST',
			url: url,
			dataType: 'JSON',
			success: function(Json) {
				if (typeof $.wa_pro.action !== 'undefined' && 'submit' in $.wa_pro.action) {
					$.wa_pro.action.submit(Json.data, form);
				} else {
					if (Json.data.error) {
						$.waDialog.alert({
							title: 'Возникла ошибка',
							text: Json.data.message,
							button_title: 'Понятно',
							button_class: 'warning',
						});
						form.find('input[type="submit"]').prop('disabled', false);
					} else {
						$.waDialog.alert({
							title: 'Успех',
							text: 'Данные успешно сохранены',
							button_title: 'Понятно',
							button_class: 'warning',
						});
					}
				}
			},
			error: function () {
				form.find('input[type="submit"]').prop('disabled', false);
				$.waDialog.alert({
					title: 'Ошибка',
					text: 'Не удалось сохранить данные. Проверьте форму или обратитесь к администратору.',
					button_title: 'Понятно',
					button_class: 'warning',
				});
			}
		};
		if (useFormData) {
			ajaxOptions.data = new FormData(form[0]);
			ajaxOptions.contentType = false;
			ajaxOptions.processData = false;
		} else {
			ajaxOptions.data = form.serialize();
		}
		form.find('input[type="submit"]').prop('disabled', true);
		$.ajax(ajaxOptions);
	});
});