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


    $.Cabinet.registerPage('tenders', {
        $root: null,
        $searchButton: null,
        $searchInput: null,
        $priceSelect: null,
        uploadedFiles: [],
        currentDocumentFiles: [],

        init: function (root) {
            this.$root = $(root);
            this.$searchButton = this.$root.find('.tenders-search .button.search');
            this.$searchInput = this.$root.find('#search-tenders');

            this.initSelects();
            this.initDatepicker();
            this.initDatepickerPeriod();
            this.initTimePicker();
            this.initPriceFilter();
            this.initApplicationDeadline();
            this.bindSupplierApprovalToggle();
            this.bindAutoRenewalToggle();

            this.bindEvents();
            this.bindNDSToggle();
            this.toggleSearchButton();
            this.bindTenderTypeToggle();
            this.bindPrequalificationToggle();
            this.bindHidePricesToggle();
            this.bindCategoryTabs();
            this.bindMinIncrementToggle();
            this.bindPriceLimitToggle();
            this.initDocumentDeadline();
            this.initEndDeadline(); 
            this.bindDocumentDeadlineToggle();
            this.initAdditionalInfoFields();
            this.bindAdditionalInfoToggle();
            this.initApproverAppointment();
            this.initSingleSelect();
            this.initMultiSelect();

            this.initAddressFields();
            this.initPaymentConditionsFields();
            this.initContactsFields();
            
            this.initLotsManagement();

            this.initFileUpload();
            this.initLotsToggler();

            this.initSupplierTabs();
            this.initSupplierSelection();
            this.initEmailInvites();
            this.initCharCounters();
        },

        // -----------------------------
        // SELECT2
        // -----------------------------
        initSelects: function() {
            const self = this;

            self.$root.find('.tender-filters .filter-select')
            .not('#selectDeadline')
            .each(function () {

                const $select = $(this);
                const placeholder = $select.data('placeholder') || 'Выберите...';

                $select.select2({
                    multiple: true,
                    placeholder: placeholder,
                    closeOnSelect: false,
                    matcher: function(params, data) {
                        const selected = $select.val() || [];
                        if (data.id && selected.includes(data.id)) return null;
                        return $.fn.select2.defaults.defaults.matcher(params, data);
                    },
                });

                $select.on('select2:select select2:unselect', function () {
                    if ($(this).data('select2').isOpen()) {
                        $(this).select2('close').select2('open');
                    }
                });

                const $select2 = $select.next('.select2');
                const $selection = $select2.find('.select2-selection');
                $select.data('select2').dropdown.$dropdown.appendTo($selection);
            });
            
            self.$root.find('.company-select').each(function () {
                const $select = $(this);
                const placeholder = $select.data('placeholder') || 'Введите название компании';

                $select.select2({
                    multiple: true,
                    placeholder: placeholder,
                    closeOnSelect: false,
                    matcher: function(params, data) {
                        const selected = $select.val() || [];
                        if (data.id && selected.includes(data.id)) return null;
                        return $.fn.select2.defaults.defaults.matcher(params, data);
                    },
                });

                $select.on('select2:select select2:unselect', function () {
                    if ($(this).data('select2').isOpen()) {
                        $(this).select2('close').select2('open');
                    }
                });

                const $select2 = $select.next('.select2');
                const $selection = $select2.find('.select2-selection');
                $select.data('select2').dropdown.$dropdown.appendTo($selection);
            });

            self.$root.find('.prequalification-select').each(function () {
                const $select = $(this);
                const placeholder = $select.data('placeholder') || 'Введите название компании';

                $select.select2({
                    multiple: true,
                    placeholder: placeholder,
                    closeOnSelect: false,
                    matcher: function(params, data) {
                        const selected = $select.val() || [];
                        if (data.id && selected.includes(data.id)) return null;
                        return $.fn.select2.defaults.defaults.matcher(params, data);
                    },
                });

                $select.on('select2:select select2:unselect', function () {
                    if ($(this).data('select2').isOpen()) {
                        $(this).select2('close').select2('open');
                    }
                });

                const $select2 = $select.next('.select2');
                const $selection = $select2.find('.select2-selection');
                $select.data('select2').dropdown.$dropdown.appendTo($selection);
            });

            self.$root.find('.relevant-tags').each(function () {
                const $select = $(this);
                const placeholder = $select.data('placeholder') || 'Введите название компании';

                $select.select2({
                    multiple: true,
                    placeholder: placeholder,
                    closeOnSelect: false,
                    matcher: function(params, data) {
                        const selected = $select.val() || [];
                        if (data.id && selected.includes(data.id)) return null;
                        return $.fn.select2.defaults.defaults.matcher(params, data);
                    },
                });

                $select.on('select2:select select2:unselect', function () {
                    if ($(this).data('select2').isOpen()) {
                        $(this).select2('close').select2('open');
                    }
                });

                const $select2 = $select.next('.select2');
                const $selection = $select2.find('.select2-selection');
                $select.data('select2').dropdown.$dropdown.appendTo($selection);
            });
        },


        // -----------------------------
        // DATEPICKER
        // -----------------------------
        initDatepicker: function() {
            const self = this;
            const $deadline = self.$root.find('#selectDeadline .date-select');

            if (!$deadline.length) return;

            flatpickr($deadline[0], {
                mode: "range",
                dateFormat: "Y/m/d",
                showMonths: 2,
                locale: 'ru',

                onChange: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        const format = d => d.toISOString().split('T')[0];

                        $('#deadline_from').val(format(selectedDates[0]));
                        $('#deadline_to').val(format(selectedDates[1]));
                    }
                }
            });

            self.$root.find('#selectDeadline').on('click', function() {
                const input = $(this).find('.date-select')[0];
                if (input && input._flatpickr) {
                    input._flatpickr.open();
                }
            });
        },

        initDatepickerPeriod: function() {
            const self = this;
            const $deadline = self.$root.find('#selectPeriod .date-select');

            if (!$deadline.length) return;

            flatpickr($deadline[0], {
                mode: "single",
                dateFormat: "Y/m/d",
                showMonths: 1,
                locale: 'ru',

                onChange: function(selectedDates) {
                    if (selectedDates.length === 1) {
                        const format = d => d.toISOString().split('T')[0];
                        $('#deadline_period').val(format(selectedDates[0]));
                    }
                }
            });

            self.$root.find('#selectPeriod').on('click', function() {
                const input = $(this).find('.date-select')[0];
                if (input && input._flatpickr) {
                    input._flatpickr.open();
                }
            });
        },

        initTimePicker: function() {
            const self = this;
            const $timePickers = self.$root.find('.date-picker.time');
            
            $timePickers.each(function() {
                const $container = $(this);
                const $timeInput = $container.find('.time');
                
                if (!$timeInput.length) return;
                
                flatpickr($timeInput[0], {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    minuteIncrement: 15,
                    
                    onChange: function(selectedDates) {
                        if (!selectedDates.length) return;
                        
                        const d = selectedDates[0];
                        const hours = String(d.getHours()).padStart(2, '0');
                        const minutes = String(d.getMinutes()).padStart(2, '0');
                        const timeValue = `${hours}:${minutes}`;
                        
                        if ($container.attr('id') === 'selectTime') {
                            $('#deadline_time').val(timeValue);
                        } else if ($container.attr('id') === 'applicationDeadlineTime') {
                            $('#applicationDeadlineTime').val(timeValue);
                        }
                    }
                });
                
                $container.on('click', function(e) {
                    e.stopPropagation();
                    const input = $(this).find('.time')[0];
                    if (input && input._flatpickr) {
                        input._flatpickr.open();
                    }
                });
            });
        },

        initApplicationDeadline: function() {
            const self = this;
            const $deadline = self.$root.find('#applicationDeadlineContainer  .date-select');
            
            if (!$deadline.length) return;
            
            flatpickr($deadline[0], {
                mode: "single",
                dateFormat: "Y/m/d",
                showMonths: 1,
                locale: 'ru',
                
                onChange: function(selectedDates) {
                    if (selectedDates.length === 1) {
                        const format = d => d.toISOString().split('T')[0];
                        $('#applicationDeadline').val(format(selectedDates[0]));
                    }
                }
            });
            
            self.$root.find('#applicationDeadlineContainer').on('click', function() {
                const input = $(this).find('.date-select')[0];
                if (input && input._flatpickr) {
                    input._flatpickr.open();
                }
            });
        },

        bindSupplierApprovalToggle: function () {
            const self = this;

            const $supplierApproval = self.$root.find('input[name="supplierApproval"]');
            const $fixPeriod = self.$root.find('input[name="fixPeriod"]');

            const $innerBlock = self.$root.find('.tender-creation-checkbox-wrap.inner');
            const $periodBlock = self.$root.find('.tender-create-period');

            function update() {
                const supplierChecked = $supplierApproval.is(':checked');
                const fixChecked = $fixPeriod.is(':checked');

                $innerBlock.toggleClass('visible', supplierChecked);

                if (!supplierChecked) {
                    $fixPeriod.prop('checked', false);
                }

                $periodBlock.toggleClass('visible', supplierChecked && fixChecked);
            }

            $supplierApproval.on('change', update);
            $fixPeriod.on('change', update);

            update();
        },

        bindAutoRenewalToggle: function() {
            const self = this;
            
            const $checkbox = self.$root.find('input[name="procedureRenewalPrices"]');
            const $innerBlock = self.$root.find('.tender-creation-renewal-inner');
            const $renewalSelect = self.$root.find('.renewal-period');
            
            if ($renewalSelect.length) {
                $renewalSelect.select2({
                    multiple: false,
                    placeholder: $renewalSelect.data('placeholder') || 'Укажите период',
                    allowClear: false,
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
                
                const $select2 = $renewalSelect.next('.select2');
                const $selection = $select2.find('.select2-selection');
            }
            
            function update() {
                const isChecked = $checkbox.is(':checked');
                
                if (isChecked) {
                    $innerBlock.addClass('visible');
                } else {
                    $innerBlock.removeClass('visible');
                    if ($renewalSelect.val()) {
                        $renewalSelect.val(null).trigger('change');
                    }
                    $innerBlock.find('input[type="checkbox"]').prop('checked', false);
                }
            }
            
            $checkbox.on('change', update);
            
            update();
        },

        bindMinIncrementToggle: function() {
            const self = this;
            
            const $minIncrementCheckbox = self.$root.find('input[name="minIncrement"]');
            const $minIncrementRadios = self.$root.find('.tender-creation-radios.min-increment');
            const $selfPriceRadio = self.$root.find('input[name="increment"][value="selfPrice"]');
            const $bestPriceRadio = self.$root.find('input[name="increment"][value="bestPrice"]');
            const $incrementBlock = self.$root.find('.tender-creation-increment');
            const $incrementButtons = self.$root.find('.increment-button');
            const $incrementSum = self.$root.find('input[name="minIncrementSum"]').closest('.input-box');
            const $incrementPercent = self.$root.find('input[name="minIncrementPercent"]').closest('.input-box');
            
            function updateMainVisibility() {
                const isChecked = $minIncrementCheckbox.is(':checked');
                $minIncrementRadios.toggleClass('visible', isChecked);
                
                if (!isChecked) {
                    $selfPriceRadio.prop('checked', false);
                    $bestPriceRadio.prop('checked', false);
                    $incrementBlock.removeClass('visible');
                } else {
                    updateIncrementVisibility();
                }
            }
            
            function updateIncrementVisibility() {
                const isSelfPriceSelected = $selfPriceRadio.is(':checked');
                $incrementBlock.toggleClass('visible', isSelfPriceSelected);
                
                if (!isSelfPriceSelected) {
                    $incrementSum.find('input').val('');
                    $incrementPercent.find('input').val('');
                }
            }
            
            function updateIncrementType() {
                const activeButton = $incrementButtons.filter('.active');
                const isSumActive = activeButton.text().trim() === 'Сумма';
                
                if (isSumActive) {
                    $incrementSum.show();
                    $incrementPercent.hide();
                } else {
                    $incrementSum.hide();
                    $incrementPercent.show();
                }
            }
            
            $minIncrementCheckbox.on('change', updateMainVisibility);
            $selfPriceRadio.on('change', updateIncrementVisibility);
            $bestPriceRadio.on('change', updateIncrementVisibility);
            
            $incrementButtons.on('click', function() {
                $incrementButtons.removeClass('active');
                $(this).addClass('active');
                updateIncrementType();
            });
            
            updateMainVisibility();
            updateIncrementType();
        },

        bindNDSToggle: function() {
            const self = this;
            
            const $ndsRadios = self.$root.find('input[name="NDS"]');
            const $ndsSelect = self.$root.find('.NDS-choice');
            const $ndsSelectContainer = $ndsSelect.closest('.creation-radio-wrap');
            const $ndsSelectBlock = $ndsSelect.closest('.tender-creation-nds-select');
            

            if ($ndsSelect.length) {
                $ndsSelect.select2({
                    multiple: false,
                    placeholder: $ndsSelect.data('placeholder') || 'Выберите ставку НДС',
                    allowClear: false,
                    width: '100%',
                    minimumResultsForSearch: Infinity
                });
            }
            
            function update() {
                const isNdsYes = $ndsRadios.filter(':checked').val() === 'yes';
                
                if (isNdsYes) {
                    $ndsSelectBlock.addClass('visible');
                } else {
                    $ndsSelectBlock.removeClass('visible');
                    if ($ndsSelect.val()) {
                        $ndsSelect.val(null).trigger('change');
                    }
                }
            }
            
            $ndsRadios.on('change', update);
            
            update();
        },

        initPriceFilter: function() {
            const self = this;

            self.$priceSelect = self.$root.find('.price-select');
            if (!self.$priceSelect.length) return;

            self.$priceSelect.each(function() {
                const $root = $(this);

                const $trigger = $root.find('.price-select-trigger');
                const $dropdown = $root.find('.price-select-dropdown');
                const $label = $root.find('.price-select-label');

                const $min = $root.find('.price-min');
                const $max = $root.find('.price-max');

                const $hiddenMin = $root.find('input[name="price_min"]');
                const $hiddenMax = $root.find('input[name="price_max"]');

                $trigger.on('click', function(e) {
                    e.stopPropagation();
                    $('.price-select').not($root).removeClass('open');
                    $root.toggleClass('open');
                });

                $dropdown.on('click', function(e) {
                    e.stopPropagation();
                });

                $(document).on('click', function() {
                    $root.removeClass('open');
                });

                const sanitize = (v) => (v || '').replace(/\D/g, '');

                const format = (v) => {
                    if (!v) return '';
                    return new Intl.NumberFormat('ru-RU').format(parseInt(v));
                };

                const update = () => {
                    let min = sanitize($min.val());
                    let max = sanitize($max.val());

                    $min.val(format(min));
                    $max.val(format(max));

                    $hiddenMin.val(min);
                    $hiddenMax.val(max);

                    let text = 'Начальная цена';

                    if (min && max) text = `${format(min)} - ${format(max)}`;
                    else if (min) text = `от ${format(min)}`;
                    else if (max) text = `до ${format(max)}`;

                    $label.text(text);

                    $label.toggleClass('has-value');
                };

                $min.on('input', update);
                $max.on('input', update);
            });
        },

        initFileUpload: function() {
            const self = this;

            this.$root.find('.file-upload-card').each(function() {
                const $fileCard = $(this);
                const $fileArea = $fileCard.find('.creation-card-file-area');
                
                if (!$fileArea.length) return;
                
                const uploadedFiles = [];
                
                const $dropZone = $fileArea.find('.file-drop-zone');
                const $fileInput = $fileArea.find('.file-input');
                const $fileList = $fileCard.find('.file-list');

                $dropZone.off('click dragenter dragstart dragover dragleave dragend drop');
                $fileInput.off('change');

                const MAX_FILE_SIZE = 10 * 1024 * 1024;
                const ALLOWED_EXTENSIONS = ['.pdf', '.doc', '.docx'];

                function addFileToList(file, $fileList) {
                    const fileSize = self.formatFileSize(file.size);
                    const extension = '.' + file.name.split('.').pop().toLowerCase();
                    
                    const $fileItem = $(`
                        <div class="file-item" data-file-name="${file.name}">
                            <div class="file-info">
                                <div class="file-icon">
                                    <span class="file-ext">${extension.replace('.', '').toUpperCase()}</span>
                                </div>
                                <div class="file-text">
                                    <div class="file-name">${self.escapeHtml(file.name)}</div>
                                    <div class="file-size">Файл загружен • (${fileSize})</div>
                                </div>
                            </div>
                            <button class="file-remove" type="button" title="Удалить">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.183058 0.183058C0.427136 -0.0610194 0.822864 -0.0610194 1.06694 0.183058L5.625 4.74112L10.1831 0.183059C10.4271 -0.0610188 10.8229 -0.0610188 11.0669 0.183059C11.311 0.427137 11.311 0.822865 11.0669 1.06694L6.50888 5.625L11.0669 10.1831C11.311 10.4271 11.311 10.8229 11.0669 11.0669C10.8229 11.311 10.4271 11.311 10.1831 11.0669L5.625 6.50888L1.06694 11.0669C0.822864 11.311 0.427136 11.311 0.183058 11.0669C-0.0610194 10.8229 -0.0610194 10.4271 0.183058 10.1831L4.74112 5.625L0.183058 1.06694C-0.0610194 0.822864 -0.0610194 0.427136 0.183058 0.183058Z" fill="#2B2B2B" />
                                </svg>
                            </button>
                        </div>
                    `);
                    
                    $fileItem.find('.file-remove').on('click', function() {
                        const index = uploadedFiles.findIndex(f => f.name === file.name && f.size === file.size);
                        if (index !== -1) {
                            uploadedFiles.splice(index, 1);
                        }
                        $fileItem.remove();
                    });
                    
                    $fileList.append($fileItem);
                }

                function showErrors(errors, $dropZone) {
                    const $errorDiv = $dropZone.closest('.creation-card-file-area').find('.file-errors');
                    if ($errorDiv.length) {
                        $errorDiv.remove();
                    }
                    
                    const $errorsHtml = $(`
                        <div class="file-errors">
                            ${errors.map(err => `<div class="file-error-message">⚠️ ${self.escapeHtml(err)}</div>`).join('')}
                        </div>
                    `);
                    
                    $dropZone.after($errorsHtml);
                    
                    setTimeout(() => {
                        $errorsHtml.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }, 3000);
                }

                function handleFiles(fileList, $fileList, $dropZone) {
                    const newFiles = Array.from(fileList);
                    const errors = [];
                    
                    newFiles.forEach(file => {
                        if (file.size > MAX_FILE_SIZE) {
                            errors.push(`${file.name}: превышает 10 МБ`);
                            return;
                        }
                        
                        const extension = '.' + file.name.split('.').pop().toLowerCase();
                        const isValidExtension = ALLOWED_EXTENSIONS.includes(extension);
                        
                        if (!isValidExtension) {
                            errors.push(`${file.name}: неподдерживаемый формат`);
                            return;
                        }
                        
                        const isDuplicate = uploadedFiles.some(f => f.name === file.name && f.size === file.size);
                        if (isDuplicate) {
                            errors.push(`${file.name}: файл уже загружен в этот блок`);
                            return;
                        }
                        
                        uploadedFiles.push(file);
                        addFileToList(file, $fileList);
                    });
                    
                    if (errors.length > 0) {
                        showErrors(errors, $dropZone);
                    }
                }

                $dropZone.on('click', function(e) {
                    if (e.target === $fileInput[0] || $(e.target).hasClass('file-browse-btn')) {
                        return;
                    }
                    $fileInput.trigger('click');
                });

                $fileInput.on('change', function(e) {
                    handleFiles(e.target.files, $fileList, $dropZone);
                    $fileInput.val('');
                });

                $dropZone.on('dragenter dragstart dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $dropZone.addClass('drag-over');
                });

                $dropZone.on('dragleave dragend drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $dropZone.removeClass('drag-over');
                });

                $dropZone.on('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $dropZone.removeClass('drag-over');
                    handleFiles(e.originalEvent.dataTransfer.files, $fileList, $dropZone);
                });
            });
        },

        addFileToList: function(file, $fileList) {
            const self = this;
            const fileSize = this.formatFileSize(file.size);
            const extension = '.' + file.name.split('.').pop().toLowerCase();
            
            const $fileItem = $(`
                <div class="file-item" data-file-name="${file.name}">
                    
                    <div class="file-info">
                        <div class="file-icon">
                            <span class="file-ext">${extension.replace('.', '').toUpperCase()}</span>
                        </div>
                        <div class="file-text">
                            <div class="file-name">${this.escapeHtml(file.name)}</div>
                            <div class="file-size">Файл загружен • (${fileSize})</div>
                        </div>
                    </div>
                    <button class="file-remove" type="button" title="Удалить">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.183058 0.183058C0.427136 -0.0610194 0.822864 -0.0610194 1.06694 0.183058L5.625 4.74112L10.1831 0.183059C10.4271 -0.0610188 10.8229 -0.0610188 11.0669 0.183059C11.311 0.427137 11.311 0.822865 11.0669 1.06694L6.50888 5.625L11.0669 10.1831C11.311 10.4271 11.311 10.8229 11.0669 11.0669C10.8229 11.311 10.4271 11.311 10.1831 11.0669L5.625 6.50888L1.06694 11.0669C0.822864 11.311 0.427136 11.311 0.183058 11.0669C-0.0610194 10.8229 -0.0610194 10.4271 0.183058 10.1831L4.74112 5.625L0.183058 1.06694C-0.0610194 0.822864 -0.0610194 0.427136 0.183058 0.183058Z" fill="#2B2B2B" />
                        </svg>
                    </button>
                </div>
            `);
            
            $fileItem.find('.file-remove').on('click', function() {
                const index = self.uploadedFiles.findIndex(f => f.name === file.name && f.size === file.size);
                if (index !== -1) {
                    self.uploadedFiles.splice(index, 1);
                }
                $fileItem.remove();
                console.log('Удален файл, осталось:', self.uploadedFiles.length);
            });
            
            $fileList.append($fileItem);
        },

        showErrors: function(errors, $dropZone) {
            const $errorDiv = $dropZone.closest('.creation-card-file-area').find('.file-errors');
            if ($errorDiv.length) {
                $errorDiv.remove();
            }
            
            const $errorsHtml = $(`
                <div class="file-errors">
                    ${errors.map(err => `<div class="file-error-message">⚠️ ${this.escapeHtml(err)}</div>`).join('')}
                </div>
            `);
            
            $dropZone.after($errorsHtml);
            
            setTimeout(() => {
                $errorsHtml.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        },

        formatFileSize: function(bytes) {
            if (bytes === 0) return '0 Б';
            const k = 1024;
            const sizes = ['Б', 'КБ', 'МБ', 'ГБ'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        escapeHtml: function(str) {
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        },

        bindEvents: function (){
            const self = this;

            self.$searchInput.on('input', function() {
                self.toggleSearchButton();
            });

            self.$searchButton.on('click', function(e) {
                e.preventDefault();

                const searchTerm = self.$searchInput.val();
                const filters = {};

                // select2
                self.$root.find('.tender-filters .filter-select').each(function() {
                    const $select = $(this);
                    const name = $select.attr('name');
                    const values = $select.val();

                    if (values && values.length) {
                        filters[name] = values;
                    }
                });

                const priceMin = self.$root.find('input[name="price_min"]').val();
                const priceMax = self.$root.find('input[name="price_max"]').val();

                if (priceMin) filters.price_min = parseInt(priceMin);
                if (priceMax) filters.price_max = parseInt(priceMax);

                console.log('Поиск:', searchTerm, filters);
            });
        },

        bindTenderTypeToggle: function() {
            const self = this;

            const $radios = self.$root.find('input[name="tenderType"]');
            const $block = self.$root.find('.tender-creation-closed-procedure');

            function update() {
                const value = $radios.filter(':checked').val();

                $block.toggleClass('visible', value === 'closed');
            }

            $radios.on('change', update);

            update();
        },

        bindPrequalificationToggle: function() {
            const self = this;

            const $checkbox = self.$root.find('input[name="prequalification"]');
            const $block = self.$root.find('.tender-creation-prequalification');

            function update() {
                const isChecked = $checkbox.is(':checked');
                $block.toggleClass('visible', isChecked);
            }

            $checkbox.on('change', update);

            update();
        },

        toggleSearchButton: function (){
            const self = this;
            const hasSearchText = self.$searchInput.val().trim().length > 0;

            self.$searchButton.toggleClass('visible', hasSearchText);
        },

        bindHidePricesToggle: function () {
            const self = this;

            const $hidePrices = self.$root.find('input[name="hidePrices"]');
            const $radiosBlock = self.$root.find('.tender-creation-radios.inner');

            const $rankRadios = self.$root.find('input[name="rankPrices"]');
            const $orgNameBlock = self.$root
                .find('input[name="orgNameVisible"]')
                .closest('.tender-creation-checkbox-wrap');

            function update() {
                const hidePricesChecked = $hidePrices.is(':checked');
                const rankValue = $rankRadios.filter(':checked').val();

                $radiosBlock.toggleClass('visible', hidePricesChecked);

                if (!hidePricesChecked) {
                    $rankRadios.prop('checked', false);
                    $orgNameBlock.removeClass('visible');
                    return;
                }

                $orgNameBlock.toggleClass('visible', rankValue === 'yes');
            }

            $hidePrices.on('change', update);
            $rankRadios.on('change', update);

            update();
        },

        bindCategoryTabs: function() {
            const $buttons = $('.creation-card-categories-button');
            const $commonClassifier = $('.creation-card-categories-results.common-classifier');
            const $esklp = $('.creation-card-categories-results.esklp');

            $buttons.on('click', function() {
                const tabValue = $(this).data('tab');
                
                $buttons.removeClass('active');
                $(this).addClass('active');
                
                if (tabValue === 'common') {
                    $commonClassifier.addClass('active');
                    $esklp.removeClass('active');
                } else if (tabValue === 'esklp') {
                    $esklp.addClass('active');
                    $commonClassifier.removeClass('active');
                }
            });

            $buttons.first().trigger('click');
            
            this.initCheckboxTracking();
        },

        initCheckboxTracking: function() {
            const self = this;
            
            $(document).on('change', '.creation-card-categories-results input[type="checkbox"]', function() {
                const $checkbox = $(this);
                const $tagsContainer = $checkbox.closest('.creation-card-categories-results').find('.creation-card-results-tags');
                const checkboxValue = $checkbox.val();
                const checkboxText = $checkbox.closest('.tender-creation-checkbox').find('.checkbox-text').text();
                
                if ($checkbox.is(':checked')) {
                    self.addTag($tagsContainer, checkboxValue, checkboxText);
                } else {
                    self.removeTag($tagsContainer, checkboxValue);
                }
            });
        },

        bindPriceLimitToggle: function() {
            const self = this;

            const $checkbox = self.$root.find('input[name="priceLimit"]');
            const $block = self.$root.find('.tender-creation-checkbox-wrap.price-limit .tender-creation-input-field');

            function update() {
                const isChecked = $checkbox.is(':checked');
                $block.toggleClass('inner-visible', isChecked);
            }

            $checkbox.on('change', update);

            update();
        },

        addTag: function($container, value, text) {
            if ($container.find(`.tag[data-value="${value}"]`).length === 0) {
                const tagHtml = `
                    <div class="tag" data-value="${value}">
                        <span class="tag-text">${text}</span>
                        <button class="tag-remove" type="button">×</button>
                    </div>
                `;
                $container.append(tagHtml);
                
                $container.find(`.tag[data-value="${value}"] .tag-remove`).on('click', () => {
                    this.removeTagByValue($container, value);
                    const $checkbox = $(`input[type="checkbox"][value="${value}"]`);
                    $checkbox.prop('checked', false);
                });
            }
        },

        bindDocumentDeadlineToggle: function() {
            const self = this;
            
            const $radios = self.$root.find('input[name="documentDeadlineRadio"]');
            const $manualBlock = self.$root.find('.creation-radio-wrap:has(input[value="manual"]) .tender-create-period');
            
            function update() {
                const isManual = $radios.filter(':checked').val() === 'manual';
                
                if (isManual) {
                    $manualBlock.addClass('visible');
                } else {
                    $manualBlock.removeClass('visible');
                }
            }
            
            $radios.on('change', update);
            update();
        },

        initDocumentDeadline: function() {
            const self = this;
            const $deadline = self.$root.find('#documentDeadlineContainer .date-select');
            
            if (!$deadline.length) return;
            
            flatpickr($deadline[0], {
                mode: "single",
                dateFormat: "Y/m/d",
                showMonths: 1,
                locale: 'ru',
                
                onChange: function(selectedDates) {
                    if (selectedDates.length === 1) {
                        const format = d => d.toISOString().split('T')[0];
                        $('#documentDeadline').val(format(selectedDates[0]));
                    }
                }
            });
            
            self.$root.find('#documentDeadlineContainer').on('click', function() {
                const input = $(this).find('.date-select')[0];
                if (input && input._flatpickr) {
                    input._flatpickr.open();
                }
            });
        },

        initEndDeadline: function() {
            const self = this;
            const $deadline = self.$root.find('#endDeadlineContainer .date-select');
            
            if (!$deadline.length) return;
            
            flatpickr($deadline[0], {
                mode: "single",
                dateFormat: "Y/m/d",
                showMonths: 1,
                locale: 'ru',
                
                onChange: function(selectedDates) {
                    if (selectedDates.length === 1) {
                        const format = d => d.toISOString().split('T')[0];
                        $('#endDeadline').val(format(selectedDates[0]));
                    }
                }
            });
            
            self.$root.find('#endDeadlineContainer').on('click', function() {
                const input = $(this).find('.date-select')[0];
                if (input && input._flatpickr) {
                    input._flatpickr.open();
                }
            });
        },

        removeTag: function($container, value) {
            $container.find(`.tag[data-value="${value}"]`).remove();
        },

        removeTagByValue: function($container, value) {
            $container.find(`.tag[data-value="${value}"]`).remove();
        },

        bindAdditionalInfoToggle: function() {
            const self = this;
            
            const $checkbox = self.$root.find('input[name="additionalInfo"]');
            const $infoBlock = self.$root.find('.additional-info-text');
            
            function update() {
                const isChecked = $checkbox.is(':checked');
                $infoBlock.toggleClass('visible', isChecked);
            }
            
            $checkbox.on('change', update);
            update();
        },

        initAdditionalInfoFields: function() {
            const self = this;
            const $container = self.$root.find('.additional-info-text');
            const $addButton = $container.find('.button-add-more');
            
            let fieldCounter = 0;
            
            function addField() {
                fieldCounter++;
                const $fieldWrapper = $(`
                    <div class="additional-field-wrapper" data-field-id="${fieldCounter}">
                        <div class="tender-creation-hint mg-bt-8">Дополнительная информация ${fieldCounter + 1}</div>
                        <div class="input-box-wrapper">
                            <div class="input-box">
                                <input type="text" name="additionalInfoText_${fieldCounter}" placeholder="Введите информацию для поставщиков">
                            </div>
                            <button class="button-remove-field" type="button" title="Удалить поле">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `);
                
                // Вставляем перед кнопкой "Добавить"
                $fieldWrapper.insertBefore($addButton);
                
                // Обработчик удаления поля
                $fieldWrapper.find('.button-remove-field').on('click', function() {
                    $fieldWrapper.remove();
                });
            }
            
            $addButton.on('click', addField);
        },

        initApproverAppointment: function() {
            const self = this;
            const $container = self.$root.find('.approver-appointment');
            const $addButton = $container.find('.button-add-more');
            
            let approverCounter = 0;
            
            const $mainSelect = $container.find('#approverAppointment');
            if ($mainSelect.length) {
                $mainSelect.select2({
                    multiple: false,
                    placeholder: $mainSelect.data('placeholder') || 'Добавить утверждающего',
                    allowClear: true,
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
            }
            
            function addApproverField() {
                approverCounter++;
                const $fieldWrapper = $(`
                    <div class="tender-creation-closed-procedure approver-field-wrapper" data-approver-id="${approverCounter}" style="margin-top: 12px;">
                        <div class="tender-creation-hint mg-bt-8">Утверждающий ${approverCounter + 1}</div>
                        <div class="input-box-wrapper">
                            <select class="select2 company-select approver-select-dynamic" name="approverAppointment_${approverCounter}" data-placeholder="Добавить утверждающего">
                                <option value=""></option>
                                <option value="approver1">утверждающий1</option>
                                <option value="approver2">утверждающий2</option>
                                <option value="approver3">утверждающий3</option>
                                <option value="approver4">утверждающий4</option>
                                <option value="approver5">утверждающий5</option>
                            </select>
                            <button class="button-remove-field" type="button" title="Удалить утверждающего">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `);
                
                $fieldWrapper.insertBefore($addButton);
                
                const $newSelect = $fieldWrapper.find('.approver-select-dynamic');
                $newSelect.select2({
                    multiple: false,
                    placeholder: $newSelect.data('placeholder') || 'Добавить утверждающего',
                    allowClear: true,
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
                
                $fieldWrapper.find('.button-remove-field').on('click', function() {
                    if ($newSelect.hasClass('select2-hidden-accessible')) {
                        $newSelect.select2('destroy');
                    }
                    $fieldWrapper.remove();
                });
            }
    
            $addButton.off('click').on('click', addApproverField);
        },

        initAddressFields: function() {
            const self = this;
            const $container = self.$root.find('.creation-card.delivery-info');
            const $addButton = $container.find('.button-add-more').first();
            
            let addressCounter = 0;
            
            function addAddressField() {
                addressCounter++;
                const uniqueId = `address_${Date.now()}_${addressCounter}`;
                
                const $fieldWrapper = $(`
                    <div class="tender-creation-single-search width-440 search-icon address-field-wrapper" data-address-id="${addressCounter}" style="margin-top: 15px;">
                        <div class="creation-card-hint mg-bt-8">Адрес ${addressCounter + 1}</div>
                        <div class="input-box-wrapper">
                            <select class="select2 filter-select address-select-dynamic" name="addressChoice_${addressCounter}" data-placeholder="Индекс, Страна, Область, населенный пункт, Улица, дом, Строение/корпус, квартира/офис" id="${uniqueId}">
                                <option value="address1">адрес1</option>
                                <option value="address2">адрес2</option>
                                <option value="address3">адрес3</option>
                                <option value="address4">адрес4</option>
                                <option value="address5">адрес5</option>
                            </select>
                            <button class="button-remove-field" type="button" title="Удалить адрес">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `);
                
                $fieldWrapper.insertBefore($addButton);
                
                const $newSelect = $fieldWrapper.find('.address-select-dynamic');
                $newSelect.select2({
                    multiple: false,
                    placeholder: $newSelect.data('placeholder') || 'Выберите адрес',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: Infinity,
                    dropdownParent: $fieldWrapper
                });
                
                $fieldWrapper.find('.button-remove-field').on('click', function() {
                    if ($newSelect.hasClass('select2-hidden-accessible')) {
                        $newSelect.select2('destroy');
                    }
                    $fieldWrapper.remove();
                });
            }
            
            $addButton.off('click').on('click', addAddressField);
        },

        initPaymentConditionsFields: function() {
            const self = this;
            const $container = self.$root.find('.creation-card.delivery-info');
            const $addButton = $container.find('.tender-creation-single-search.list-icon .button-add-more');
            
            let paymentCounter = 0;
            
            function addPaymentField() {
                paymentCounter++;
                const uniqueId = `payment_${Date.now()}_${paymentCounter}`;
                
                const $fieldWrapper = $(`
                    <div class="tender-creation-single-search width-440 list-icon payment-field-wrapper" data-payment-id="${paymentCounter}" style="margin-top: 15px;">
                        <div class="creation-card-hint mg-bt-8">Условия оплаты ${paymentCounter + 1}</div>
                        <div class="input-box-wrapper">
                            <select class="select2 filter-select payment-conditions-dynamic" name="paymentConditions_${paymentCounter}" data-placeholder="Выберите условия оплаты из ваших шаблонов" id="${uniqueId}">
                                <option value="payment1">условие1</option>
                                <option value="payment2">условие2</option>
                                <option value="payment3">условие3</option>
                                <option value="payment4">условие4</option>
                                <option value="payment5">условие5</option>
                            </select>
                            <button class="button-remove-field" type="button" title="Удалить условие">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `);
                
                $fieldWrapper.insertBefore($addButton);
                
                const $newSelect = $fieldWrapper.find('.payment-conditions-dynamic');
                $newSelect.select2({
                    multiple: false,
                    placeholder: $newSelect.data('placeholder') || 'Выберите условия оплаты',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: Infinity,
                    dropdownParent: $fieldWrapper
                });
                
                $fieldWrapper.find('.button-remove-field').on('click', function() {
                    if ($newSelect.hasClass('select2-hidden-accessible')) {
                        $newSelect.select2('destroy');
                    }
                    $fieldWrapper.remove();
                });
            }
            
            $addButton.off('click').on('click', addPaymentField);
        },

        initContactsFields: function() {
            const self = this;
            const $container = self.$root.find('.creation-card.contacts-info');
            const $addButton = $container.find('.buttons-more-wrap .button-add-more').first();
            const $createNewButton = $container.find('.buttons-more-wrap .button-add-more').last();
            
            let contactCounter = 0;
            
            function addContactField() {
                contactCounter++;
                const uniqueId = `contact_${Date.now()}_${contactCounter}`;
                
                const $fieldWrapper = $(`
                    <div class="tender-creation-single-search width-440 no-icon contact-field-wrapper" data-contact-id="${contactCounter}" style="margin-top: 15px;">
                        <div class="creation-card-hint mg-bt-8">Контакт ${contactCounter + 1}</div>
                        <div class="input-box-wrapper">
                            <select class="select2 filter-select contact-select-dynamic" name="contactsInfo_${contactCounter}" data-placeholder="Выберите контакт из списка организации" id="${uniqueId}">
                                <option value="contact1">контакт1</option>
                                <option value="contact2">контакт2</option>
                                <option value="contact3">контакт3</option>
                                <option value="contact4">контакт4</option>
                                <option value="contact5">контакт5</option>
                            </select>
                            <button class="button-remove-field" type="button" title="Удалить контакт">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `);
                
                $fieldWrapper.insertBefore($container.find('.buttons-more-wrap'));
                
                const $newSelect = $fieldWrapper.find('.contact-select-dynamic');
                $newSelect.select2({
                    multiple: false,
                    placeholder: $newSelect.data('placeholder') || 'Выберите контакт',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: Infinity,
                    dropdownParent: $fieldWrapper
                });
                
                $fieldWrapper.find('.button-remove-field').on('click', function() {
                    if ($newSelect.hasClass('select2-hidden-accessible')) {
                        $newSelect.select2('destroy');
                    }
                    $fieldWrapper.remove();
                });
            }
            
            function createNewContact() {
                contactCounter++;
                
                const $fieldWrapper = $(`
                    <div class="contact-field-wrapper new-contact" data-contact-id="${contactCounter}" style="margin-top: 15px;">
                        <div class="creation-card-hint mg-bt-8">Новый контакт ${contactCounter + 1}</div>
                        <div class="input-box-wrapper">
                            <div class="input-box width-440">
                                <input type="text" name="newContact_${contactCounter}" placeholder="ФИО, должность, телефон, email">
                            </div>
                            <button class="button-remove-field" type="button" title="Удалить контакт">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                    <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `);
                
                $fieldWrapper.insertBefore($container.find('.buttons-more-wrap'));
                
                $fieldWrapper.find('.button-remove-field').on('click', function() {
                    $fieldWrapper.remove();
                });
            }
            
            $addButton.off('click').on('click', addContactField);
            $createNewButton.off('click').on('click', createNewContact);
        },

        initLotsManagement: function() {
            const self = this;
            
            const $addLotButton = this.$root.find('.creation-card-lot.left-side .button-add-more.lot');
            const $leftSideContainer = this.$root.find('.creation-card-lot.left-side');
            const $centerSideContainer = this.$root.find('.creation-card-lot.center-side');
            const $rightSideContainer = this.$root.find('.creation-card-lot.right-side');
            
            let lotCounter = $leftSideContainer.find('.creation-card-lot-item').length;
            
            function clearSelected() {
                $leftSideContainer.find('.creation-card-lot-item').removeClass('selected');
            }
            
            function updateBudgetControl() {
                let totalSum = 0;
                let ndsRate = 20;
                
                $leftSideContainer.find('.creation-card-lot-item').each(function() {
                    const infoText = $(this).find('.lot-info').text();
                    const priceMatch = infoText.match(/(\d+[\s\d]*)\s*₽/);
                    if (priceMatch) {
                        const price = parseFloat(priceMatch[1].replace(/\s/g, ''));
                        totalSum += price;
                    }
                    const ndsMatch = infoText.match(/НДС\s*(\d+)%/);
                    if (ndsMatch) {
                        ndsRate = parseFloat(ndsMatch[1]);
                    }
                });
                
                const nmc = 900000;
                const ndsAmount = totalSum * ndsRate / 100;
                const totalWithNds = totalSum + ndsAmount;
                const diff = nmc - totalWithNds;
                const ratio = totalWithNds > 0 ? (totalWithNds / nmc * 100) : 0;
                
                $rightSideContainer.find('.creation-lot-result:eq(0) .creation-lot-result-number').text(totalSum.toLocaleString('ru-RU') + ' ₽');
                $rightSideContainer.find('.creation-lot-result:eq(1) .creation-lot-result-number').text(ndsRate + '%');
                $rightSideContainer.find('.creation-lot-result:eq(2) .creation-lot-result-number').text(nmc.toLocaleString('ru-RU') + ' ₽');
                $rightSideContainer.find('.creation-lot-result:eq(3) .creation-lot-result-number').text(ndsRate + '%');
                $rightSideContainer.find('.creation-lot-result-bottom .creation-lot-result:eq(0) .creation-lot-result-number').text(diff.toLocaleString('ru-RU') + ' ₽');
                $rightSideContainer.find('.creation-lot-result-bottom .creation-lot-result:eq(1) .creation-lot-result-number').text(ratio.toFixed(1) + '%');
            }
            
            function updateLotInfo(quantity, price, ndsValue, unitText) {
                const $selectedItem = $leftSideContainer.find('.creation-card-lot-item.selected');
                if ($selectedItem.length) {
                    const quantityText = quantity || '0';
                    const priceText = price ? parseInt(price).toLocaleString('ru-RU') : '0';
                    let ndsText = '0';
                    if (ndsValue) {
                        const ndsMatch = ndsValue.match(/\d+/);
                        ndsText = ndsMatch ? ndsMatch[0] : '0';
                    }
                    const unitTextValue = unitText || 'шт';
                    $selectedItem.find('.lot-info').html(`${quantityText}${unitTextValue} · ${priceText} ₽ · НДС ${ndsText}%`);
                }
            }
            
            function updateCenterSide(lotNumber, title, quantity, price, ndsValue, unitValue) {
                const $lotTitle = $centerSideContainer.find('.creation-card-title');
                if ($lotTitle.length) {
                    $lotTitle.text(`Позиция №${lotNumber}`);
                }
                
                $centerSideContainer.find('input[name="positionName"]').val(title || '');
                $centerSideContainer.find('input[name="positionQuantity"]').val(quantity || '0');
                $centerSideContainer.find('input[name="positionMaxPrice"]').val(price || '0');
                
                const $ndsSelect = $centerSideContainer.find('select[name="positionNDS"]');
                if ($ndsSelect.length && $ndsSelect.data('select2')) {
                    let ndsOptionValue = 'nds1';
                    if (ndsValue) {
                        const ndsNumber = ndsValue.match(/\d+/);
                        if (ndsNumber) {
                            if (ndsNumber[0] === '20') ndsOptionValue = 'nds1';
                            else if (ndsNumber[0] === '10') ndsOptionValue = 'nds2';
                            else if (ndsNumber[0] === '0') ndsOptionValue = 'nds3';
                        }
                    }
                    $ndsSelect.val(ndsOptionValue).trigger('change');
                }
                
                const $unitSelect = $centerSideContainer.find('select[name="positionUnit"]');
                if ($unitSelect.length && $unitSelect.data('select2')) {
                    let unitOptionValue = unitValue || 'unit1';
                    $unitSelect.val(unitOptionValue).trigger('change');
                }
            }
            
            function getCurrentCenterData() {
                const lotNumber = $centerSideContainer.find('.creation-card-title').text().match(/\d+/)?.[0] || '1';
                const title = $centerSideContainer.find('input[name="positionName"]').val();
                const quantity = $centerSideContainer.find('input[name="positionQuantity"]').val();
                const price = $centerSideContainer.find('input[name="positionMaxPrice"]').val();
                const nds = $centerSideContainer.find('select[name="positionNDS"]').val();
                return { lotNumber, title, quantity, price, nds };
            }
            
            function addLotItem() {
                lotCounter++;
                
                const $newLotItem = $(`
                    <div class="creation-card-lot-item" data-lot-id="${lotCounter}">
                        <div class="lot-number">${lotCounter}</div>
                        <div class="lot-text">
                            <div class="lot-title">Позиция ${lotCounter}</div>
                            <div class="lot-info">0шт · 0 ₽ · НДС 0%</div>
                        </div>
                    </div>
                `);
                
                $newLotItem.insertAfter($addLotButton);
                
                $newLotItem.on('click', function() {
                    clearSelected();
                    $(this).addClass('selected');
                    const lotId = $(this).data('lot-id');
                    const lotTitle = $(this).find('.lot-title').text();
                    const lotInfo = $(this).find('.lot-info').text();
                    
                    const quantityMatch = lotInfo.match(/(\d+)шт/);
                    const priceMatch = lotInfo.match(/(\d+[\s\d]*)\s*₽/);
                    const ndsMatch = lotInfo.match(/НДС\s*(\d+)%/);
                    
                    updateCenterSide(
                        lotId,
                        lotTitle,
                        quantityMatch ? quantityMatch[1] : '0',
                        priceMatch ? priceMatch[1].replace(/\s/g, '') : '0',
                        ndsMatch ? ndsMatch[1] : '20'
                    );
                });
            }
            
            if ($addLotButton.length) {
                $addLotButton.off('click').on('click', addLotItem);
            }
            
            $leftSideContainer.find('.creation-card-lot-item').on('click', function() {
                clearSelected();
                $(this).addClass('selected');
                const lotNumber = $(this).find('.lot-number').text();
                const lotTitle = $(this).find('.lot-title').text();
                const lotInfo = $(this).find('.lot-info').text();
                
                const quantityMatch = lotInfo.match(/(\d+)шт/);
                const priceMatch = lotInfo.match(/(\d+[\s\d]*)\s*₽/);
                const ndsMatch = lotInfo.match(/НДС\s*(\d+)%/);
                
                let unitValue = 'unit1';
                if (lotInfo.includes('кг')) unitValue = 'unit2';
                else if (lotInfo.includes('л')) unitValue = 'unit3';
                else if (lotInfo.includes('мг')) unitValue = 'unit4';
                else if (lotInfo.includes('комплект')) unitValue = 'unit5';
                
                updateCenterSide(
                    lotNumber,
                    lotTitle,
                    quantityMatch ? quantityMatch[1] : '0',
                    priceMatch ? priceMatch[1].replace(/\s/g, '') : '0',
                    ndsMatch ? ndsMatch[1] : '20',
                    unitValue
                );
            });
            
            function bindCenterInputs() {
                $centerSideContainer.find('input[name="positionQuantity"], input[name="positionMaxPrice"]').off('input').on('input', function() {
                    const $selectedItem = $leftSideContainer.find('.creation-card-lot-item.selected');
                    if ($selectedItem.length) {
                        const quantity = $centerSideContainer.find('input[name="positionQuantity"]').val();
                        const price = $centerSideContainer.find('input[name="positionMaxPrice"]').val();
                        const ndsValue = $centerSideContainer.find('select[name="positionNDS"]').val();
                        const unitSelect = $centerSideContainer.find('select[name="positionUnit"]');
                        const unitText = unitSelect.find('option:selected').text();
                        updateLotInfo(quantity, price, ndsValue, unitText);
                        updateBudgetControl();
                    }
                });
                
                $centerSideContainer.find('select[name="positionNDS"]').off('change').on('change', function() {
                    const $selectedItem = $leftSideContainer.find('.creation-card-lot-item.selected');
                    if ($selectedItem.length) {
                        const quantity = $centerSideContainer.find('input[name="positionQuantity"]').val();
                        const price = $centerSideContainer.find('input[name="positionMaxPrice"]').val();
                        const ndsValue = $(this).val();
                        const unitSelect = $centerSideContainer.find('select[name="positionUnit"]');
                        const unitText = unitSelect.find('option:selected').text();
                        updateLotInfo(quantity, price, ndsValue, unitText);
                        updateBudgetControl();
                    }
                });
                
                $centerSideContainer.find('select[name="positionUnit"]').off('change').on('change', function() {
                    const $selectedItem = $leftSideContainer.find('.creation-card-lot-item.selected');
                    if ($selectedItem.length) {
                        const quantity = $centerSideContainer.find('input[name="positionQuantity"]').val();
                        const price = $centerSideContainer.find('input[name="positionMaxPrice"]').val();
                        const ndsValue = $centerSideContainer.find('select[name="positionNDS"]').val();
                        const unitText = $(this).find('option:selected').text();
                        updateLotInfo(quantity, price, ndsValue, unitText);
                    }
                });
                
                $centerSideContainer.find('input[name="positionName"]').off('input').on('input', function() {
                    const $selectedItem = $leftSideContainer.find('.creation-card-lot-item.selected');
                    if ($selectedItem.length) {
                        const newTitle = $(this).val();
                        $selectedItem.find('.lot-title').text(newTitle || 'Без названия');
                    }
                });
            }
            
            bindCenterInputs();
            
                const $addFieldButton = $centerSideContainer.find('.button-add-more.small');
                let fieldCounter = 0;
                
                if ($addFieldButton.length) {
                $addFieldButton.off('click').on('click', function() {
                    fieldCounter++;
                    const $newField = $(`
                        <div class="tender-creation-single-search delivery-place search-icon search-visible mg-top-16">
                            <div class="input-hint small mg-bt-6">Место поставки ${fieldCounter + 1}</div>
                            <select class="select2 filter-select single-select" name="deliveryPlace_${fieldCounter}" id="deliveryPlace_${fieldCounter}" data-placeholder="Введите или выберите из списка">
                                <option value="place1">Место 1</option>
                                <option value="place2">Место 2</option>
                                <option value="place3">Место 3</option>
                            </select>
                        </div>
                    `);
                    
                    $newField.insertBefore($addFieldButton);
                    
                    const $newSelect = $newField.find('select');
                    $newSelect.select2({
                        multiple: false,
                        placeholder: $newSelect.data('placeholder') || 'Выберите место поставки',
                        allowClear: false,
                        width: '100%',
                        minimumResultsForSearch: Infinity
                    });
                    
                    $newField.find('.button-remove-field').on('click', function() {
                        if ($newSelect.hasClass('select2-hidden-accessible')) {
                            $newSelect.select2('destroy');
                        }
                        $newField.remove();
                    });
                });
            }
            
            const $copyButton = $centerSideContainer.find('.tender-creation-upper-buttons .button.position:first-child');
            if ($copyButton.length) {
                $copyButton.off('click').on('click', function() {
                    const { title, quantity, price, nds } = getCurrentCenterData();
                    
                    lotCounter++;
                    
                    const quantityText = quantity || '0';
                    const priceText = price ? parseInt(price).toLocaleString('ru-RU') : '0';
                    const ndsText = nds ? nds.replace('%', '') : '0';
                    
                    const $newLotItem = $(`
                        <div class="creation-card-lot-item" data-lot-id="${lotCounter}">
                            <div class="lot-number">${lotCounter}</div>
                            <div class="lot-text">
                                <div class="lot-title">${title || 'Позиция ' + lotCounter}</div>
                                <div class="lot-info">${quantityText}шт · ${priceText} ₽ · НДС ${ndsText}%</div>
                            </div>
                        </div>
                    `);
                    
                    $newLotItem.insertAfter($addLotButton);
                    
                    $newLotItem.on('click', function() {
                        clearSelected();
                        $(this).addClass('selected');
                        updateCenterSide(lotCounter, title, quantity, price, ndsText);
                    });
                    
                    updateBudgetControl();
                });
            }
            
            const $deleteButton = $centerSideContainer.find('.tender-creation-upper-buttons .button.position:last-child');
            if ($deleteButton.length) {
                $deleteButton.off('click').on('click', function() {
                    const $selectedItem = $leftSideContainer.find('.creation-card-lot-item.selected');
                    if ($selectedItem.length && $leftSideContainer.find('.creation-card-lot-item').length > 1) {
                        $selectedItem.remove();
                        
                        const $firstItem = $leftSideContainer.find('.creation-card-lot-item').first();
                        if ($firstItem.length) {
                            $firstItem.addClass('selected');
                            const lotNumber = $firstItem.find('.lot-number').text();
                            const lotTitle = $firstItem.find('.lot-title').text();
                            const lotInfo = $firstItem.find('.lot-info').text();
                            
                            const quantityMatch = lotInfo.match(/(\d+)шт/);
                            const priceMatch = lotInfo.match(/(\d+[\s\d]*)\s*₽/);
                            const ndsMatch = lotInfo.match(/НДС\s*(\d+)%/);
                            
                            updateCenterSide(
                                lotNumber,
                                lotTitle,
                                quantityMatch ? quantityMatch[1] : '0',
                                priceMatch ? priceMatch[1].replace(/\s/g, '') : '0',
                                ndsMatch ? ndsMatch[1] : '0'
                            );
                        }
                        updateBudgetControl();
                    }
                });
            }
            
            updateBudgetControl();
        },

        initSingleSelect: function() {
            const self = this;
            
            self.$root.find('.tender-creation-single-search .select2').each(function() {
                const $select = $(this);
                
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                
                $select.select2({
                    multiple: false,
                    placeholder: $select.data('placeholder') || 'Выберите...',
                    closeOnSelect: true,
                    minimumResultsForSearch: Infinity,
                });
                
            });
        },

        initMultiSelect: function() {
            const self = this;
            
            self.$root.find('.tender-creation-multi-search .select2').each(function() {
                const $select = $(this);
                
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                
                $select.select2({
                    multiple: true,
                    placeholder: $select.data('placeholder') || 'Выберите...',
                    closeOnSelect: true,
                    minimumResultsForSearch: Infinity,
                });
                
            });
        },

        initLotsToggler: function() {
            const self = this;
            const $toggleItems = self.$root.find('.creation-card-lot-toggle-item');
            const $lotsWrap = self.$root.find('.creation-card-lots-wrap');
            const $documentsWrap = self.$root.find('.creation-card-documents-wrap');
            
            function switchTab(tabText) {
                if (tabText === 'Позиции') {
                    $lotsWrap.addClass('active');
                    $documentsWrap.removeClass('active');
                } else if (tabText === 'Запрашиваемые документы') {
                    $lotsWrap.removeClass('active');
                    $documentsWrap.addClass('active');

                    setTimeout(function() {
                        self.initDocumentCounter();
                        self.initDocumentFileUpload();
                    }, 50);
                }
            }
            
            $toggleItems.on('click', function() {
                const tabText = $(this).data('text');
                
                $toggleItems.removeClass('active');
                $(this).addClass('active');
                
                switchTab(tabText);
            });
            
            switchTab('Позиции');
        },

        initDocumentItems: function() {
            const self = this;
            const $documentsWrap = self.$root.find('.creation-card-documents-wrap');
            if (!$documentsWrap.length) return;
            
            if ($documentsWrap.data('initialized')) return;
            $documentsWrap.data('initialized', true);
            
            const $addDocButton = $documentsWrap.find('.button-add-more.document');
            const $leftSide = $documentsWrap.find('.creation-card-documents.left-side');
            const $centerSide = $documentsWrap.find('.creation-card-documents.center-side');
            
            let docCounter = $leftSide.find('.creation-card-document-item').length;
            
            function clearSelected() {
                $leftSide.find('.creation-card-document-item').removeClass('selected');
            }
            
            function updateCenterSide(docNumber, title) {
                $centerSide.find('.creation-card-title').text('Документ №' + docNumber);
                $centerSide.find('input[name="documentName"]').val(title || '');
                
                const $textarea = $centerSide.find('#documentDesc');
                if ($textarea.length) {
                    $textarea.val('');
                    self.updateCounter($textarea, $centerSide.find('.char-counter'));
                }
                
                const $checkbox = $centerSide.find('input[name="documentRequired"]');
                if ($checkbox.length) {
                    $checkbox.prop('checked', false);
                }
                
                const $fileList = $centerSide.find('.file-list');
                if ($fileList.length) {
                    $fileList.empty();
                }
                
                self.currentDocumentFiles = [];
            }
            
            function addDocumentItem() {
                docCounter++;
                
                const $newDocItem = $(`
                    <div class="creation-card-document-item" data-doc-id="${docCounter}">
                        <div class="lot-number">${docCounter}</div>
                        <div class="lot-text">
                            <div class="lot-title">Документ ${docCounter}</div>
                        </div>
                    </div>
                `);
                
                $newDocItem.insertAfter($addDocButton);
                
                $newDocItem.on('click', function() {
                    clearSelected();
                    $(this).addClass('selected');
                    const docNumber = $(this).find('.lot-number').text();
                    const docTitle = $(this).find('.lot-title').text();
                    updateCenterSide(docNumber, docTitle);
                });
            }
            
            if ($addDocButton.length) {
                $addDocButton.off('click').on('click', addDocumentItem);
            }
            
            $leftSide.find('.creation-card-document-item').off('click').on('click', function() {
                clearSelected();
                $(this).addClass('selected');
                const docNumber = $(this).find('.lot-number').text();
                const docTitle = $(this).find('.lot-title').text();
                updateCenterSide(docNumber, docTitle);
            });
            
            self.initDocumentCounter();
            self.initDocumentFileUpload();
        },

        initDocumentCounter: function() {
            const self = this;
            function setupCounter($textarea, $counter) {
                if (!$textarea.length || !$counter.length) return;
                
                function updateCounter() {
                    const length = $textarea.val().length;
                    const max = 200;
                    
                    $counter.text(length + '/' + max);
                    
                    $counter.removeClass('warning danger');
                    if (length >= max) {
                        $counter.addClass('danger');
                    } else if (length >= max - 20) {
                        $counter.addClass('warning');
                    }
                }
                
                $textarea.off('input').on('input', updateCounter);
                updateCounter();
            }
            
            // Ищем во всём $root, а не только в документах
            const $textarea = self.$root.find('#documentDesc');
            const $counter = self.$root.find('.char-counter');
            
            if ($textarea.length && $counter.length) {
                setupCounter($textarea, $counter);
            }
        },

        initCharCounters: function() {
            const self = this;
            
            $('.char-counter-textarea').each(function() {
                const $textarea = $(this);
                const $counter = $textarea.closest('.textarea-wrapper').find('.char-counter');
                
                if ($counter.length) {
                    function updateCounter() {
                        const length = $textarea.val().length;
                        const max = parseInt($textarea.attr('maxlength')) || 200;
                        
                        $counter.text(length + '/' + max);
                        
                        $counter.removeClass('warning danger');
                        if (length >= max) {
                            $counter.addClass('danger');
                        } else if (length >= max - 20) {
                            $counter.addClass('warning');
                        }
                    }
                    
                    $textarea.off('input').on('input', updateCounter);
                    updateCounter();
                }
            });
        },

        initDocumentFileUpload: function() {
            const self = this;
            const $documentsWrap = self.$root.find('.creation-card-documents-wrap');
            if (!$documentsWrap.length) return;
            
            $documentsWrap.find('.file-upload-card').each(function() {
                const $fileCard = $(this);
                const $fileArea = $fileCard.find('.creation-card-file-area');
                
                if (!$fileArea.length || $fileCard.data('initialized')) return;
                $fileCard.data('initialized', true);
                
                const uploadedFiles = [];
                
                const $dropZone = $fileArea.find('.file-drop-zone');
                const $fileInput = $fileArea.find('.file-input');
                const $fileList = $fileCard.find('.file-list');
                
                const MAX_FILE_SIZE = 10 * 1024 * 1024;
                const ALLOWED_EXTENSIONS = ['.pdf', '.doc', '.docx'];
                
                function addFileToList(file, $fileList) {
                    const fileSize = self.formatFileSize(file.size);
                    const extension = '.' + file.name.split('.').pop().toLowerCase();
                    
                    const $fileItem = $(`
                        <div class="file-item" data-file-name="${file.name}">
                            <div class="file-info">
                                <div class="file-icon">
                                    <span class="file-ext">${extension.replace('.', '').toUpperCase()}</span>
                                </div>
                                <div class="file-text">
                                    <div class="file-name">${self.escapeHtml(file.name)}</div>
                                    <div class="file-size">Файл загружен • (${fileSize})</div>
                                </div>
                            </div>
                            <button class="file-remove" type="button">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M0.183058 0.183058C0.427136 -0.0610194 0.822864 -0.0610194 1.06694 0.183058L5.625 4.74112L10.1831 0.183059C10.4271 -0.0610188 10.8229 -0.0610188 11.0669 0.183059C11.311 0.427137 11.311 0.822865 11.0669 1.06694L6.50888 5.625L11.0669 10.1831C11.311 10.4271 11.311 10.8229 11.0669 11.0669C10.8229 11.311 10.4271 11.311 10.1831 11.0669L5.625 6.50888L1.06694 11.0669C0.822864 11.311 0.427136 11.311 0.183058 11.0669C-0.0610194 10.8229 -0.0610194 10.4271 0.183058 10.1831L4.74112 5.625L0.183058 1.06694C-0.0610194 0.822864 -0.0610194 0.427136 0.183058 0.183058Z" fill="#2B2B2B"/>
                                </svg>
                            </button>
                        </div>
                    `);
                    
                    $fileItem.find('.file-remove').on('click', function() {
                        const index = uploadedFiles.findIndex(f => f.name === file.name && f.size === file.size);
                        if (index !== -1) uploadedFiles.splice(index, 1);
                        $fileItem.remove();
                    });
                    
                    $fileList.append($fileItem);
                }
                
                function handleFiles(fileList, $fileList, $dropZone) {
                    const newFiles = Array.from(fileList);
                    const errors = [];
                    
                    newFiles.forEach(file => {
                        if (file.size > MAX_FILE_SIZE) {
                            errors.push(`${file.name}: превышает 10 МБ`);
                            return;
                        }
                        
                        const extension = '.' + file.name.split('.').pop().toLowerCase();
                        if (!ALLOWED_EXTENSIONS.includes(extension)) {
                            errors.push(`${file.name}: неподдерживаемый формат`);
                            return;
                        }
                        
                        if (uploadedFiles.some(f => f.name === file.name && f.size === file.size)) {
                            errors.push(`${file.name}: файл уже загружен`);
                            return;
                        }
                        
                        uploadedFiles.push(file);
                        addFileToList(file, $fileList);
                    });
                    
                    if (errors.length) {
                        self.showErrors(errors, $dropZone);
                    }
                }
                
                $dropZone.off('click').on('click', function(e) {
                    if (e.target === $fileInput[0] || $(e.target).hasClass('file-browse-btn')) return;
                    $fileInput.trigger('click');
                });
                
                $fileInput.off('change').on('change', function(e) {
                    handleFiles(e.target.files, $fileList, $dropZone);
                    $fileInput.val('');
                });
                
                $dropZone.off('dragenter dragstart dragover').on('dragenter dragstart dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $dropZone.addClass('drag-over');
                });
                
                $dropZone.off('dragleave dragend drop').on('dragleave dragend drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $dropZone.removeClass('drag-over');
                    if (e.type === 'drop') {
                        handleFiles(e.originalEvent.dataTransfer.files, $fileList, $dropZone);
                    }
                });
            });
        },

        updateCounter: function($textarea, $counter) {
            const length = $textarea.val().length;
            const max = 200;
            
            $counter.text(length + '/' + max);
            
            $counter.removeClass('warning danger');
            if (length >= max) {
                $counter.addClass('danger');
            } else if (length >= max - 20) {
                $counter.addClass('warning');
            }
        },

        initSupplierTabs: function() {
        const self = this;
        const $toggleItems = this.$root.find('.creation-card-lot-toggle-item');
        const $allSuppliers = this.$root.find('.input-wrap.tenders-search.all-suppliers');
        const $mySuppliers = this.$root.find('.input-wrap.tenders-search.my-suppliers');
        const $groupSuppliers = this.$root.find('.input-wrap.tenders-search.group-suppliers');
        
        function switchTab(tabText) {
            $allSuppliers.removeClass('active');
            $mySuppliers.removeClass('active');
            $groupSuppliers.removeClass('active');
            
            if (tabText === 'Все поставщики') {
                $allSuppliers.addClass('active');
            } else if (tabText === 'Мои поставщики') {
                $mySuppliers.addClass('active');
            } else if (tabText === 'Группы') {
                $groupSuppliers.addClass('active');
            }
        }
        
        $toggleItems.on('click', function() {
            const tabText = $(this).data('text');
            
            $toggleItems.removeClass('active');
            $(this).addClass('active');
            
            switchTab(tabText);
        });
        
        switchTab('Все поставщики');
    },

    initSupplierSelection: function() {
        const self = this;
        const $supplierCheckboxes = this.$root.find('.supplier-search-results .tender-creation-checkbox-wrap');
        const $chosenSupplierBlock = this.$root.find('.creation-card.chosen-supplier');
        const $chosenSupplierNumber = $chosenSupplierBlock.find('.chosen-supplier-value');
        const $chosenSupplierList = $chosenSupplierBlock.find('.chosen-supplier-list');
        const $chosenSupplierNoText = $chosenSupplierBlock.find('.chosen-supplier-no-text');
        
        let selectedSuppliers = [];
        
        function updateChosenSuppliers() {
            $chosenSupplierNumber.text(selectedSuppliers.length);
            
            if (selectedSuppliers.length === 0) {
                $chosenSupplierNoText.show();
                $chosenSupplierList.hide();
            } else {
                $chosenSupplierNoText.hide();
                $chosenSupplierList.show();
            }
            
            $chosenSupplierList.empty();
            
            selectedSuppliers.forEach(supplier => {
                const $supplierItem = $(`
                    <div class="chosen-supplier-item" data-supplier-id="${supplier.id}">
                        <span class="chosen-supplier-name">${self.escapeHtml(supplier.name)}</span>
                        <button class="chosen-supplier-remove" type="button" title="Удалить">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M0.183058 0.183058C0.427136 -0.0610194 0.822864 -0.0610194 1.06694 0.183058L5.625 4.74112L10.1831 0.183059C10.4271 -0.0610188 10.8229 -0.0610188 11.0669 0.183059C11.311 0.427137 11.311 0.822865 11.0669 1.06694L6.50888 5.625L11.0669 10.1831C11.311 10.4271 11.311 10.8229 11.0669 11.0669C10.8229 11.311 10.4271 11.311 10.1831 11.0669L5.625 6.50888L1.06694 11.0669C0.822864 11.311 0.427136 11.311 0.183058 11.0669C-0.0610194 10.8229 -0.0610194 10.4271 0.183058 10.1831L4.74112 5.625L0.183058 1.06694C-0.0610194 0.822864 -0.0610194 0.427136 0.183058 0.183058Z" fill="currentColor"/>
                            </svg>
                        </button>
                    </div>
                `);
                
                $supplierItem.find('.chosen-supplier-remove').on('click', function() {
                    self.removeSupplier(supplier.id);
                });
                
                $chosenSupplierList.append($supplierItem);
            });
            
            if (selectedSuppliers.length > 0) {
                const $clearAllBtn = $(`
                    <button class="chosen-supplier-clear-all" type="button">Очистить всё</button>
                `);
                $clearAllBtn.on('click', function() {
                    self.clearAllSuppliers();
                });
                $chosenSupplierList.append($clearAllBtn);
            }
        }
        
        this.addSupplier = function(supplierId, supplierName) {
            if (!selectedSuppliers.find(s => s.id === supplierId)) {
                selectedSuppliers.push({ id: supplierId, name: supplierName });
                updateChosenSuppliers();
                
                const $checkbox = $(`input[type="checkbox"][value="${supplierId}"]`);
                if ($checkbox.length) {
                    $checkbox.prop('checked', true);
                }
            }
        };
        
        this.removeSupplier = function(supplierId) {
            selectedSuppliers = selectedSuppliers.filter(s => s.id !== supplierId);
            updateChosenSuppliers();
            
            const $checkbox = $(`input[type="checkbox"][value="${supplierId}"]`);
            if ($checkbox.length) {
                $checkbox.prop('checked', false);
            }
        };
        
        this.clearAllSuppliers = function() {
            selectedSuppliers = [];
            updateChosenSuppliers();
            
            $supplierCheckboxes.find('input[type="checkbox"]').prop('checked', false);
        };
        
        $supplierCheckboxes.find('input[type="checkbox"]').on('change', function() {
            const $wrap = $(this).closest('.tender-creation-checkbox-wrap');
            const supplierName = $wrap.find('.checkbox-text').text().trim();
            const supplierId = $(this).val();
            
            if ($(this).is(':checked')) {
                self.addSupplier(supplierId, supplierName);
            } else {
                self.removeSupplier(supplierId);
            }
        });
        
        updateChosenSuppliers();
    },

    initEmailInvites: function() {
        const self = this;
        const $inviteWrap = this.$root.find('.supplier-invite-wrap');
        const $emailInput = $inviteWrap.find('#invite-supplier');
        const $addButton = $inviteWrap.find('.button.secondary.add');
        const $invitesContainer = this.$root.find('.chosen-supplier-invites');
        
        let invitedEmails = [];
        
        function updateInvitedEmails() {
            if (!$invitesContainer.length) return;
            
            $invitesContainer.empty();
            
            invitedEmails.forEach(email => {
                const $emailItem = $(`
                    <div style="margin-top: 16px;" class="tag" data-email="${email}">
                        <span class="tag-text">${self.escapeHtml(email)}</span>
                        <button class="tag-remove invited-email-remove" type="button">×</button>
                    </div>
                `);
                
                $emailItem.find('.invited-email-remove').on('click', function() {
                    self.removeEmailInvite(email);
                });
                
                $invitesContainer.append($emailItem);
            });
        }
        
        this.addEmailInvite = function(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                self.showError($emailInput, 'Введите корректный email');
                return false;
            }
            
            if (!invitedEmails.includes(email)) {
                invitedEmails.push(email);
                updateInvitedEmails();
                $emailInput.val('');
                self.hideError($emailInput);
                return true;
            } else {
                self.showError($emailInput, 'Этот email уже добавлен');
                return false;
            }
        };
        
        this.removeEmailInvite = function(email) {
            invitedEmails = invitedEmails.filter(e => e !== email);
            updateInvitedEmails();
        };
        
        if ($addButton.length) {
            $addButton.off('click').on('click', function() {
                const email = $emailInput.val().trim();
                if (email) {
                    self.addEmailInvite(email);
                }
            });
        }
        
        $emailInput.off('keypress').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                const email = $(this).val().trim();
                if (email) {
                    self.addEmailInvite(email);
                }
            }
        });
        
        updateInvitedEmails();
    },


    showError: function($input, message) {
        const $error = $('<div class="error-message">' + this.escapeHtml(message) + '</div>');
        $input.addClass('error');
        $input.parent().append($error);
        setTimeout(() => {
            $error.remove();
            $input.removeClass('error');
        }, 3000);
    },

    hideError: function($input) {
        $input.removeClass('error');
        $input.parent().find('.error-message').remove();
    },

        
});







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