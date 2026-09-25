(function ($) {
    $.Cabinet = {
        popoverAction: null,
        popoverMenu: null,
        logoutAction: null,
        navSidebar: null,
        pages: {},
        changeRoleAction: null,
        roleItem: null,
        htmxContent: null,
        $htmxContent: null,
        $htmxLoader: null,

        init: function() {
            Dropzone.autoDiscover = false;

            this.popoverAction = '.js-popover';
            this.popoverMenu = '.popover-menu';
            this.logoutAction = '.js-logout';
            this.changeRoleAction = '.js-change-role';
            this.roleItem = '.user-role-item';
            this.htmxContent = '.js-main-content';
            this.$htmxContent = $(this.htmxContent);
            this.$htmxLoader = $('.htmx-loader');
            this.navSidebar = initNavSidebar({
                $wrapper: $('#js-app-sidebar'),
                sidebar_menu_state: false,
                tooltips: [],
                locales: { pin_menu: "", unpin_menu: "" },
                urls: { sidebar_menu_state: 'local' }
            });

            this.initDatatable();
            this.initPage();
            this.bindEvents();
        },


        utils: {
            // Универсальная инициализация Select2
            utils: {
                // Универсальная инициализация Select2
                initSelect2: function($select, options = {}) {
                    if (!$select.length) return null;

                    // Уничтожаем предыдущую инициализацию, если есть
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.select2('destroy');
                    }

                    // Определяем тип Select2 на основе атрибутов
                    const isMultiple = $select.is('[multiple]') || $select.data('multiple') === true;
                    const placeholder = $select.data('placeholder') || 'Выберите...';

                    // Базовые настройки
                    const defaults = {
                        multiple: isMultiple,
                        placeholder: placeholder,
                        closeOnSelect: isMultiple ? false : true,
                        allowClear: $select.data('allow-clear') === true,
                        width: '100%',
                    };

                    // Для select с поиском (если есть data-search или больше определенного количества опций)
                    const optionsCount = $select.find('option').length;
                    if ($select.data('search') === true || optionsCount > 10) {
                        defaults.minimumResultsForSearch = 1;
                    } else {
                        defaults.minimumResultsForSearch = Infinity;
                    }

                    // Для AJAX подгрузки
                    if ($select.data('ajax-url')) {
                        defaults.ajax = {
                            url: $select.data('ajax-url'),
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term,
                                    page: params.page || 1
                                };
                            },
                            processResults: function(data, params) {
                                return {
                                    results: data.results || data.items || [],
                                    pagination: {
                                        more: data.has_more || false
                                    }
                                };
                            },
                            cache: true
                        };
                        defaults.minimumInputLength = $select.data('min-length') || 1;
                    }

                    const config = { ...defaults, ...options };

                    // Инициализируем Select2
                    $select.select2(config);

                    // Для множественного выбора с автоматическим переоткрытием
                    if (isMultiple && config.closeOnSelect === false) {
                        $select.on('select2:select select2:unselect', function() {
                            const instance = $(this).data('select2');
                            if (instance && instance.isOpen()) {
                                $(this).select2('close').select2('open');
                            }
                        });
                    }

                    // Обработка пустого состояния
                    if (isMultiple && $select.val() === null) {
                        $select.val([]).trigger('change');
                    }

                    return $select;
                },

                // Универсальная инициализация всех Select2 в контейнере с проверкой на уже инициализированные
                initAllSelects: function($container, selectors = ['.filter-select', '.select2']) {
                    const self = this;
                    selectors.forEach(selector => {
                        $container.find(selector).each(function() {
                            const $select = $(this);
                            // Проверяем, не инициализирован ли уже Select2
                            const isInitialized = $select.hasClass('select2-hidden-accessible') ||
                                                 $select.data('select2') !== undefined;

                            if (!isInitialized) {
                                self.initSelect2($select);
                            }
                        });
                    });
                },

                // Обновление Select2 после динамического добавления опций
                updateSelect2: function($select) {
                    if (!$select.length) return;

                    const isInitialized = $select.hasClass('select2-hidden-accessible') ||
                                         $select.data('select2') !== undefined;

                    if (isInitialized) {
                        $select.trigger('change.select2');
                    } else {
                        this.initSelect2($select);
                    }
                },
            },

            // Универсальная инициализация DatePicker
            initDatePicker: function($input, options = {}) {
                if (!$input.length) return null;

                const defaults = {
                    mode: "single",
                    dateFormat: "Y/m/d",
                    showMonths: 1,
                    locale: 'ru',
                };

                const config = { ...defaults, ...options };
                const picker = flatpickr($input[0], config);

                // Добавляем обработчик клика для открытия по клику на контейнер
                const $container = $input.closest('.date-picker');
                if ($container.length && !$container.data('flatpickr-bound')) {
                    $container.on('click', function(e) {
                        if ($input[0] && $input[0]._flatpickr) {
                            $input[0]._flatpickr.open();
                        }
                    });
                    $container.data('flatpickr-bound', true);
                }

                return picker;
            },

            // Универсальный TimePicker
            initTimePicker: function($input, options = {}) {
                if (!$input.length) return null;

                const defaults = {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    minuteIncrement: 15,
                };

                const config = { ...defaults, ...options };
                const picker = flatpickr($input[0], config);

                const $container = $input.closest('.date-picker.time');
                if ($container.length && !$container.data('flatpickr-bound')) {
                    $container.on('click', function(e) {
                        if ($input[0] && $input[0]._flatpickr) {
                            $input[0]._flatpickr.open();
                        }
                    });
                    $container.data('flatpickr-bound', true);
                }

                return picker;
            },

            // Универсальный Range DatePicker
            initDateRangePicker: function($input, options = {}) {
                if (!$input.length) return null;

                const defaults = {
                    mode: "range",
                    dateFormat: "Y/m/d",
                    showMonths: 2,
                    locale: 'ru',
                };

                const config = { ...defaults, ...options };
                return flatpickr($input[0], config);
            },

            // Универсальный toggle для блоков
            bindToggle: function($trigger, $target, options = {}) {
                const config = {
                    event: 'change',
                    triggerValue: null,
                    invert: false,
                    ...options
                };

                const update = function() {
                    let show = false;

                    if (config.triggerValue !== null) {
                        show = $trigger.val() === config.triggerValue;
                    } else if ($trigger.is(':checkbox')) {
                        show = $trigger.is(':checked');
                    } else if ($trigger.is(':radio')) {
                        show = $trigger.filter(':checked').val() === config.radioValue;
                    } else {
                        show = !!$trigger.val();
                    }

                    if (config.invert) show = !show;

                    $target.toggleClass('visible', show);

                    if (config.onToggle) {
                        config.onToggle(show);
                    }
                };

                $trigger.on(config.event, update);
                update();

                return { update };
            },

            // Форматирование чисел
            formatNumber: function(value) {
                if (!value) return '';
                return new Intl.NumberFormat('ru-RU').format(parseInt(value.toString().replace(/\D/g, '')));
            },

            sanitizeNumber: function(value) {
                return (value || '').toString().replace(/\D/g, '');
            },

            // Форматирование размера файла
            formatFileSize: function(bytes) {
                if (bytes === 0) return '0 Б';
                const k = 1024;
                const sizes = ['Б', 'КБ', 'МБ', 'ГБ'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            // Escape HTML
            escapeHtml: function(str) {
                if (!str) return '';
                return str.replace(/[&<>]/g, function(m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    return m;
                });
            },

            // Универсальная инициализация всех Select2 в контейнере
            initAllSelects: function($container, selectors = ['.filter-select', '.select2']) {
                const self = this;
                selectors.forEach(selector => {
                    $container.find(selector).each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            self.initSelect2($(this));
                        }
                    });
                });
            },
        },

        getActionsColumnDefs: function() {
            return [
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="table-actions">
                                <button class="button outline icon-only small edit-btn" data-id="${row.item_id}"><svg><use href="#icon-edit"></use></svg></button>
                                <button class="button outline icon-only small delete-btn" data-id="${row.item_id}"><svg><use href="#icon-trash"></use></svg></button>
                            </div>
                        `;
                    }
                }
            ];
        },

        getBaseColumnDefs: function () {
            return [
                {
                    targets: '_all',
                    createdCell: function (td, cellData) {
                        if (cellData === null || cellData === '') {
                            $(td).html('-');
                        }
                    }
                }
            ];
        },

        initDatatable: function (){
            $.extend(true, $.fn.dataTable.defaults, {
                searching: false,
                paging: false,
                info: false,
                ordering: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/ru.json'
                },
                initComplete: function(settings, json) {
                    const table = this.api();
                    const actions = settings.oInit.actions || {};

                    table.on('click', '.table-actions .edit-btn', function () {
                        const rowData = table.row($(this).closest('tr')).data();
                        if (actions.onEdit) actions.onEdit(rowData);
                    });

                    table.on('click', '.table-actions .delete-btn', function () {
                        const rowData = table.row($(this).closest('tr')).data();
                        if (actions.onDelete) actions.onDelete(rowData);
                    });
                }
            });

            $(document).on('draw.dt', function (e) {
                const $table = $(e.target);
                if ($table.find('[hx-get], [hx-post], [hx-trigger]').length) {
                    htmx.process(e.target);
                }
            });
        },

        initPage: function() {
            var pageEl = $('.js-cabinet-page')[0];
            if (!pageEl) return;

            var name = pageEl.dataset.page;
            var page = this.pages[name];

            if (page && page.init) {
                page.init(pageEl);
            }
        },

        registerPage: function(name, module) {
            this.pages[name] = module;
        },

        bindEvents: function() {
            this.bindPopover();
            this.bindHtmx();
            this.bindRoleSwitcher();
        },

        bindPopover: function() {
            var self = this;

            $(document).on('click', self.popoverAction, function (e) {
                e.stopPropagation();
                $(self.popoverAction).removeClass('active');
                $(this).toggleClass('active');

                const $menu = $(this).nextAll(self.popoverMenu).first();
                $(self.popoverMenu).not($menu).removeClass('open');
                $menu.toggleClass('open');
            });

            $(document).on('click', function (e) {
                e.stopPropagation();
                $(self.popoverAction).removeClass('active');
                $(self.popoverMenu).removeClass('open');
            });
        },

        bindHtmx: function () {
            const self = this;
            let hideTimer = null;

            $(document).on('htmx:beforeRequest', function () {
                self.$htmxLoader.addClass('is-active');
                const el = self.$htmxContent;
                const duration = parseFloat(getComputedStyle(el[0]).transitionDuration) * 1000;

                if (hideTimer) {
                    clearTimeout(hideTimer);
                    hideTimer = null;
                }

                el.removeClass('hidden');
                requestAnimationFrame(() => {
                    el.addClass('fade-out');
                });

                hideTimer = setTimeout(() => {
                    el.addClass('hidden');
                    hideTimer = null;
                }, duration);
            });


            $(document).on('htmx:afterSettle', function () {
                self.$htmxLoader.removeClass('is-active');
                const el = self.$htmxContent;
                self.initPage();

                if (hideTimer) {
                    clearTimeout(hideTimer);
                    hideTimer = null;
                }

                el.removeClass('hidden');
                el.addClass('fade-out');
                requestAnimationFrame(() => {
                    el.removeClass('fade-out');
                });
            });




        },

        bindRoleSwitcher: function() {
            const self = this;

            $(self.changeRoleAction).click(function (e) {
                e.stopPropagation();
                $(this).toggleClass('open');
            });

            $(document).click(function (e) {
                e.stopPropagation();
                $(self.changeRoleAction).removeClass('open');
            });

            $(self.roleItem).click(function (e) {
                e.stopPropagation();
                if ($(this).hasClass('active'))
                    return;

                $.fRequest({
                    url: '/cabinet/switch-role/',
                    data: { role: $(this).data('role') },
                    onSuccess: (reply) => {
                        setTimeout(() => {window.location.href=`/cabinet/${reply.role}/`}, 250);
                    }
                });
            });
        },

        htmxReload: function() {
            htmx.trigger(this.htmxContent, "refresh")
        },
    };

    $.Cabinet.registerPage('data', {
        root: null,
        tabs: null,

        init: function(root) {
            this.root = root;
            this.tabs = new TabManager({
                container: this.root,
            });

            this.bindEvents();
        },

        bindEvents: function (){

        }
    })

    $.bindToggleToDataTable = function({toggle, table, paramName = 'type',}) {
        let currentValue;
        let isInit = true;

        const settings = table.settings()[0];
        if (!settings)
            return;

        const original = settings.ajax.data;

        settings.ajax.data = function(d) {
            if (typeof original === 'function') {
                original(d);
            }
            d[paramName] = currentValue;
        };

        toggle.waToggle({
            change: function(event, target) {
                currentValue = $(target).closest('[data-type]').data('type');

                if (!isInit) {
                    table.ajax.reload();
                }
            }
        });

        let $active = toggle.find('.active');

        if ($active.length) {
            currentValue = $active.data('type');
        } else {
            const $first = toggle.find('[data-type]').first();
            $first.trigger('click');
        }

        isInit = false;
    };
    
    
})(jQuery);