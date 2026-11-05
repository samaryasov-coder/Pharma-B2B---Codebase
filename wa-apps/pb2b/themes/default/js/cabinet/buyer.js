(function ($) {
    $.Cabinet.registerPage('accreditation', {
        tabs: null,
        idDocumentTable: null,
        idRequestTable: null,
        requestTable: null,
        templateTable: null,
        $templateToggle: null,
        $requestToggle: null,

        init: function(root) {
            this.root = root;
            this.idDocumentTable = '#templateTable';
            this.idRequestTable = '#requestTable';
            this.$templateToggle = $('#templateToggle');
            this.$requestToggle = $('#requestToggle');
            this.tabs = new TabManager({
                container: this.root,
                onChange({ tab, prevTab, button, content }) {
                    if (tab === prevTab) return;
                    const tables = content.find('table').filter(function () {
                        return $.fn.dataTable.isDataTable(this);
                    });
                    tables.each(function () {
                        const dt = $(this).DataTable();
                        dt.ajax.reload();
                    });
                }
            });

            this.bindEvents();
            this.initTables();
        },

        bindEvents: function (){
            const self = this;

            $('.create-request').click(function () {
                var dialog = new $.DialogManager({
                    url: `form/request/create/`,
                    width: '520px',

                    onOpen: function($container) {
                        const $form = $container.find('form');
                        const $selectCompanies = $form.find('#select_companies');
                        const $selectTemplates = $form.find('#select_templates');
                        let company_type = null;

                        $form.fSend({
                            action: '/api/buyer/docflow/request/create/',
                            onSuccess: function() {
                                dialog.close();
                                return true;
                            }
                        });

                        $selectCompanies.select2({
                            language: 'ru',
                            placeholder: 'Выбрать',
                            allowClear: false,
                            dropdownParent: $container,

                            ajax: {
                                url: '/api/buyer/company/select/',
                                dataType: 'json',
                                delay: 250,

                                data: function(params) {
                                    return {
                                        search: params.term,
                                        supplier: 1,
                                        page: params.page || 1
                                    };
                                },

                                processResults: function(data) {
                                    return data.data;
                                },

                                cache: true
                            }
                        });

                        $selectTemplates.select2({
                            language: 'ru',
                            placeholder: 'Выбрать',
                            allowClear: false,
                            dropdownParent: $container,
                            multiple: true,
                            closeOnSelect: false,
                            minimumResultsForSearch: Infinity,

                            templateResult: function(data) {
                                if (!data.id) return data.text;
                                const selected = $selectTemplates.val() || [];
                                return selected.includes(data.id) ? null : data.text;
                            },

                            ajax: {
                                url: '/api/buyer/docflow/template/select/',
                                dataType: 'json',
                                delay: 250,

                                data: function(params) {
                                    return {
                                        type: company_type,
                                        search: params.term,
                                        page: params.page || 1
                                    };
                                },

                                processResults: function(data) {
                                    return {
                                        results: [
                                            {
                                                text: "Наименования созданных документов",
                                                children: data.data.results
                                            }
                                        ]
                                    };
                                },

                                cache: false
                            }
                        });

                        $selectCompanies.on('select2:select', function(e) {
                            const new_company_type = e.params.data.company_type;
                            if (company_type !== null && company_type !== new_company_type)
                                $selectTemplates.val(null).trigger('change');
                            company_type = new_company_type;
                            toggleSelect();
                        });

                        function toggleSelect() {
                            const mode = $('input[name="mode"]:checked').val();
                            if (mode === 'selected' && company_type)
                                $selectTemplates.prop('disabled', false);
                            else
                                $selectTemplates.prop('disabled', true).val(null).trigger('change');
                        }
                        $('input[name="mode"]').on('change', toggleSelect);
                        toggleSelect();
                    }
                });
            });

            $('.upload-template').click(function () {
                var dialog = new $.DialogManager({
                    url: `form/template/create/`,
                    width: '500px',
                    onOpen: function($container) {
                        const $form = $container.find('form');
                        const fuploader= new $.FileUploader($form.find('.js-file-upload'));
                        $form.fSend({
                            action: '/api/buyer/docflow/template/create/',
                            prepareForm: function(formData) {
                                fuploader.appendToForm(formData)
                            },
                            onSuccess: function() {
                                self.templateTable.ajax.reload();
                                dialog.close();
                            },
                        });
                    }
                });
            });




            const $tab = $('[data-tab="templates"]');
            const $empty = $tab.find('.empty-container');

            // Событие после загрузки данных
            // table.on('xhr.dt', function () {
            //     const count = table.data().count();
            //
            //     if (count === 0) {
            //         $empty.show();
            //         $('#myTable').hide();
            //     } else {
            //         $empty.hide();
            //         $('#myTable').show();
            //     }
            // });
        },

        initTables: function (){
            const self = this;
            this.templateTable = $(this.idDocumentTable).DataTable({
                deferLoading: 0,
                ajax: {
                    url: '/api/buyer/docflow/template/list/',
                    type: 'GET'
                },
                columnDefs: [
                    ...$.Cabinet.getBaseColumnDefs(),
                    ...$.Cabinet.getActionsColumnDefs(),
                    {
                        target: 1,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return `<a href="/api/buyer/docflow/template/download?id=${row.id}" class="button link large">${data}</a>`;
                        }
                    },
                ],
                columns: [
                    { data: 'name' },
                    { data: 'filename' },
                    { data: 'comment' },
                    { data: null }
                ],
                actions: {
                    onEdit: (row) => {
                        var dialog = new $.DialogManager({
                            url: `form/template/edit?id=${row.id}`,
                            width: '500px',
                            onOpen: function($container) {
                                const $form = $container.find('form');
                                const fuploader= new $.FileUploader($form.find('.js-file-upload'));
                                $form.fSend({
                                    prepareForm: function(formData) {
                                        fuploader.appendToForm(formData)
                                    },
                                    onSuccess: function() {
                                        self.templateTable.ajax.reload();
                                        dialog.close();
                                    },
                                });
                            }
                        });
                    },
                    onDelete: (row) => {
                        $.DialogManager.confirm({
                            type: 'destruct',
                            title: 'Вы уверены, что хотите удалить данный документ?',
                            message: `"${row.name}"`,
                            ajaxSubmitUrl: '/api/buyer/docflow/template/delete/',
                            ajaxSubmitData: {id: row.id},
                            onConfirm: () => {
                                self.templateTable.ajax.reload();
                            }
                        });
                    }
                }
            });

            this.requestTable = $(this.idRequestTable).DataTable({
                order: [[0, 'desc']],
                deferLoading: 0,
                ajax: {
                    url: '/api/buyer/docflow/request/list/',
                    type: 'GET'
                },
                columnDefs: [
                    ...$.Cabinet.getBaseColumnDefs(),
                    {
                        target: 0,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return `<a class="button link large" hx-get="request/${row.id}/">${data}</a>`;
                        }
                    },
                    {
                        target: 1,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return data.fullname;
                        }
                    },
                    {
                        target: 2,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return `<span class="badge ${data.type}">${data.name}</span>`;
                        }
                    },
                    {
                        target: 3,
                        type: 'datetime',
                        render: function(data, type, row) {
                            if (!data) return '';
                            return luxon.DateTime.fromFormat(data, 'yyyy-MM-dd HH:mm:ss').toFormat('dd.MM.yyyy');
                        },
                    }
                ],
                columns: [
                    { data: 'procedure_code' },
                    { data: 'company_provider' },
                    { data: 'status' },
                    { data: 'create_datetime' },
                ],
            });

            $.bindToggleToDataTable({
                toggle: this.$templateToggle,
                table: this.templateTable,
                paramName: 'type',
            });

            // $.bindToggleToDataTable({
            //     toggle: this.$requestToggle,
            //     table: this.requestTable,
            //     paramName: 'type',
            // });
        }
    })

    $.Cabinet.registerPage('request', {
        $root: null,
        id: null,
        tabs: null,

        idTemplateTable: null,
        idDocumentTable: null,
        templateTable: null,
        documentTable: null,
        $downloadAllFiles: null,
        $approveRequest: null,
        $cancelRequest: null,
        $rejectRequest: null,
        $deleteRequest: null,

        init: function(root) {
            this.$root = $(root);
            this.id = this.$root.data('id');
            this.idTemplateTable = '#reqTemplateTable';
            this.idDocumentTable = '#reqDocumentTable';
            this.$downloadAllFiles = $('.download-all-files');
            this.$approveRequest = $('.approve-request');
            this.$cancelRequest = $('.cancel-request');
            this.$rejectRequest = $('.reject-request');
            this.$deleteRequest = $('.delete-request');

            this.bindEvents();
            this.initTable();
        },

        bindEvents: function (){
            const self = this;
            self.$downloadAllFiles.click(function (e){
                e.preventDefault();
                window.location = `/api/buyer/docflow/request/${self.id}/files/download/`;
            })

            self.$cancelRequest.click(function () {
                $.DialogManager.confirm({
                    type: 'destruct',
                    title: 'Вы уверены, что хотите отозвать запрос?',
                    message: 'Запрос будет отменён без возможности восстановления.',
                    confirmText: 'Отозвать',
                    width: 560,
                    ajaxSubmitUrl: '/api/buyer/docflow/request/cancel/',
                    ajaxSubmitData: {id: self.id},
                    onConfirm: () => {
                        $.Cabinet.htmxReload();
                    }
                });
            });

            self.$approveRequest.click(function () {
                $.DialogManager.confirm({
                    type: 'success',
                    title: 'Утвердить заявку?',
                    message: 'Компания получит статус одобренного поставщика.',
                    confirmText: 'Утвердить',
                    width: 560,
                    ajaxSubmitUrl: '/api/buyer/docflow/request/approve/',
                    ajaxSubmitData: {id: self.id},
                    onConfirm: () => {
                        $.Cabinet.htmxReload();
                    }
                });
            });

            self.$deleteRequest.click(function () {
                $.DialogManager.confirm({
                    type: 'destruct',
                    title: 'Удалить запрос?',
                    message: 'Запрос будет удален без возможности восстановления.',
                    confirmText: 'Удалить',
                    width: 560,
                    ajaxSubmitUrl: '/api/buyer/docflow/request/delete/',
                    ajaxSubmitData: {id: self.id},
                    onConfirm: () => {
                        $.Cabinet.htmxReload();
                    }
                });
            });

            self.$rejectRequest.click(this.rejectRequestAction.bind(this));
        },

        rejectRequestAction: function (){
            const self = this;
            var dialog = new $.DialogManager({
                url: `/cabinet/buyer/request/form/reject/`,
                width: '570px',
                method: 'POST',
                data: {
                    request_id: this.id
                },
                onOpen: function($container) {
                    const $form = $container.find('form');
                    const $button = $container.find('[type="submit"]')
                    $form.on('change', '.file-checkbox', function () {
                        const $file = $(this).closest('.file-item');
                        const $comment = $file.find('.file-comment');

                        if (this.checked) {
                            $comment.stop().slideDown(200);
                        } else {
                            $comment.stop().slideUp(200);
                            $comment.find('textarea').val('');
                        }
                    });

                    $form.on('submit', function (e) {
                        e.preventDefault();

                        const files = [];
                        $form.find('.file-item').each(function () {
                            const $item = $(this);
                            const $checkbox = $item.find('.file-checkbox');
                            const checked = $checkbox.prop('checked');

                            if (!checked) return;

                            const comment = $item.find('textarea').val().trim();
                            files.push({
                                id: $item.data('id'),
                                comment: comment
                            });
                        });

                        $.fRequest({
                            url: '/api/buyer/docflow/request/reject/',
                            method: 'POST',
                            data: {
                                id: self.id,
                                files: files
                            },
                            button: $button
                        });
                    });
                }
            });

        },

        initTable: function (){
            const self = this;
            this.templateTable = $(this.idTemplateTable).DataTable({
                deferLoading: 0,
                ajax: {
                    url: `/api/buyer/docflow/request/${self.id}/template/list/`,
                    type: 'GET'
                },
                columnDefs: [
                    ...$.Cabinet.getBaseColumnDefs(),
                    {
                        target: 1,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return `<a href="/api/buyer/docflow/request/template/download/?id=${row.id}" class="button link large">${data.name}</a>`;
                        }
                    },
                ],
                columns: [
                    { data: 'template_name' },
                    { data: 'file' },
                    { data: 'comment' },
                ],
            });

            this.documentTable = $(this.idDocumentTable).DataTable({
                deferLoading: 0,
                ajax: {
                    url: `/api/buyer/docflow/request/${self.id}/document/list/`,
                    type: 'GET'
                },
                columnDefs: [
                    ...$.Cabinet.getBaseColumnDefs(),
                    {
                        target: 1,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return `<a href="${data.link}" class="button link large">${data.name}</a>`;
                        }
                    },
                    {
                        target: 3,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return `<span class="badge ${data.type}">${data.name}</span>`;
                        }
                    },
                ],
                columns: [
                    { data: 'template_name' },
                    { data: 'file' },
                    { data: 'comment' },
                    { data: 'status' },
                ],
            });
        }
    })

    $.Cabinet.registerPage('tenders', {
        root: null,
        items: [],
        bucket: 'all',
        search: '',
        filterType: '',
        filterStatus: '',

        init: function (root) {
            this.root = root;
            this.items = [];
            this.bucket = 'all';
            this.search = '';
            this.filterType = '';
            this.filterStatus = '';
            this.bindEvents();
            this.loadList();
        },

        navigateCabinet: function (url) {
            if (typeof htmx !== 'undefined' && htmx.ajax) {
                htmx.ajax('GET', url, {
                    target: '.js-main-content',
                    swap: 'innerHTML',
                    push: url
                });
                return;
            }
            window.location.href = url;
        },

        bindEvents: function () {
            const self = this;
            const $root = $(this.root);

            this.tabs = new TabsManager({
                container: $root,
                defaultTab: 'all',
                onChange: function ({ tab }) {
                    self.bucket = String(tab || 'all');
                    self.applyFilters();
                }
            });

            $root.find('.js-tenders-reload').click(function () {
                self.loadList();
            });

            $root.find('.js-tenders-list').on('click', '.buyer-tender-card', function (e) {
                if ($(e.target).closest('.buyer-tender-card__more').length) {
                    return;
                }
                const id = parseInt($(this).data('id'), 10) || 0;
                if (id <= 0) return;
                const item = self.items.find(function (row) {
                    return parseInt(row.id, 10) === id;
                });
                if (item && String(item.status_code || '') === 'draft') {
                    self.navigateCabinet('/cabinet/buyer/tender/' + id + '/edit/');
                    return;
                }
                window.location.href = '/cabinet/buyer/tender/' + id + '/';
            });

            let searchTimer = null;
            $root.find('.js-tenders-search').on('input', function () {
                const value = String($(this).val() || '');
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    self.search = value;
                    self.applyFilters();
                }, 200);
            });

            const closeFilters = function () {
                $root.find('.js-tenders-filters-popover').removeClass('is-open').prop('hidden', true);
                $root.find('.js-tenders-filters-toggle').removeClass('is-open').attr('aria-expanded', 'false');
            };

            $root.find('.js-tenders-filters-toggle').click(function (e) {
                e.stopPropagation();
                const $popover = $root.find('.js-tenders-filters-popover');
                const willOpen = !$popover.hasClass('is-open');
                if (willOpen) {
                    $popover.addClass('is-open').prop('hidden', false);
                    $(this).addClass('is-open').attr('aria-expanded', 'true');
                } else {
                    closeFilters();
                }
            });

            $root.find('.js-tenders-filters-close').click(function (e) {
                e.stopPropagation();
                closeFilters();
            });

            $root.find('.js-tenders-filters-popover').click(function (e) {
                e.stopPropagation();
            });

            $(document).off('click.tendersFilters').on('click.tendersFilters', function () {
                closeFilters();
            });

            $root.find('.js-tenders-filter-type, .js-tenders-filter-status').on('change', function () {
                self.filterType = String($root.find('.js-tenders-filter-type').val() || '');
                self.filterStatus = String($root.find('.js-tenders-filter-status').val() || '');
                $root.find('.js-tenders-filters-reset').prop('disabled', !(self.filterType || self.filterStatus));
                self.applyFilters();
            });

            $root.find('.js-tenders-filters-reset, .js-tenders-empty-clear').click(function () {
                self.filterType = '';
                self.filterStatus = '';
                self.search = '';
                $root.find('.js-tenders-filter-type, .js-tenders-filter-status').val('');
                $root.find('.js-tenders-search').val('');
                $root.find('.js-tenders-filters-reset').prop('disabled', true);
                self.applyFilters();
            });

            $root.find('.js-tender-create-open').click(function () {
                var dialog = new $.DialogManager({
                    url: 'form/method/',
                    width: '569px',
                    onOpen: function ($container) {
                        const $continue = $container.find('.js-tender-method-continue');
                        const $radios = $container.find('input[name="procurement_method"]');

                        $radios.on('change', function () {
                            $continue.prop('disabled', !$radios.filter(':checked:not(:disabled)').length);
                        });

                        $continue.on('click', function () {
                            const $selected = $radios.filter(':checked');
                            if (!$selected.length) return;
                            const code = String($selected.data('code') || '');
                            dialog.close();
                            self.navigateCabinet(
                                code === 'prequalification'
                                    ? '/cabinet/buyer/prequal/create/'
                                    : '/cabinet/buyer/tenders/create/?type=' + encodeURIComponent(code)
                            );
                        });
                    }
                });
            });
        },

        loadList: function () {
            const self = this;
            const $root = $(this.root);
            $root.find('.js-tenders-error').hide();

            return $.fRequest({
                url: '/api/buyer/tender/list/',
                method: 'GET',
                showMessages: false,
                onSuccess: function (reply) {
                    self.items = reply.items || [];
                    self.fillStatusFilter();
                    self.applyFilters();
                },
                onError: function (reply) {
                    $root.find('.js-tenders-list').hide().empty();
                    $root.find('.js-tenders-empty').hide();
                    $root.find('.js-tenders-error').show();
                    $root.find('.js-tenders-error-text').text(reply.message || 'Ошибка загрузки');
                }
            });
        },

        fillStatusFilter: function () {
            const $select = $(this.root).find('.js-tenders-filter-status');
            const current = this.filterStatus;
            const names = {};
            this.items.forEach(function (item) {
                const name = String(item.status_name || '').trim();
                if (name) names[name] = true;
            });
            $select.find('option:not(:first)').remove();
            Object.keys(names).sort(function (a, b) {
                return a.localeCompare(b, 'ru');
            }).forEach(function (name) {
                $select.append($('<option>').val(name).text(name));
            });
            $select.val(current);
        },

        resolveBucket: function (statusCode) {
            if (statusCode === 'draft') return 'draft';
            if (statusCode === 'suspended') return 'on_hold';
            if (statusCode === 'archived' || statusCode === 'cancelled' || statusCode === 'unsuccessful') {
                return 'archived';
            }
            return 'active';
        },

        formatDate: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) return '';
            const d = new Date(raw.replace(' ', 'T'));
            if (isNaN(d.getTime())) return '';
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const yyyy = d.getFullYear();
            return dd + '.' + mm + '.' + yyyy;
        },

        formatDeadline: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) return '—';
            const d = new Date(raw.replace(' ', 'T'));
            if (isNaN(d.getTime())) return '—';
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const yyyy = d.getFullYear();
            const hh = String(d.getHours()).padStart(2, '0');
            const mi = String(d.getMinutes()).padStart(2, '0');
            return dd + '.' + mm + '.' + yyyy + ' в ' + hh + ':' + mi;
        },

        calcDaysLeft: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) return null;
            const d = new Date(raw.replace(' ', 'T'));
            if (isNaN(d.getTime())) return null;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return Math.floor((d.getTime() - today.getTime()) / 86400000);
        },

        formatDaysLeft: function (daysLeft) {
            if (daysLeft === null || daysLeft === undefined) return '';
            if (daysLeft < 0) return 'Приём завершён';
            const n = daysLeft;
            const mod10 = n % 10;
            const mod100 = n % 100;
            let word = 'дней';
            if (mod100 < 11 || mod100 > 14) {
                if (mod10 === 1) word = 'день';
                else if (mod10 >= 2 && mod10 <= 4) word = 'дня';
            }
            return 'Осталось ' + n + ' ' + word;
        },

        applyFilters: function () {
            const self = this;
            const query = String(this.search || '').trim().toLowerCase();
            const filtered = this.items.filter(function (item) {
                const bucket = self.resolveBucket(item.status_code);
                if (self.bucket !== 'all' && bucket !== self.bucket) return false;
                if (self.filterType && String(item.type_code || '') !== self.filterType) return false;
                if (self.filterStatus && String(item.status_name || '') !== self.filterStatus) return false;
                if (!query) return true;
                const hay = [
                    item.title,
                    item.number,
                    item.type_name,
                    item.status_name
                ].join(' ').toLowerCase();
                return hay.indexOf(query) !== -1;
            });
            this.renderList(filtered);
        },

        renderList: function (items) {
            const $root = $(this.root);
            const $list = $root.find('.js-tenders-list');
            const $empty = $root.find('.js-tenders-empty');
            const hasFilters = !!(this.search || this.filterType || this.filterStatus || this.bucket !== 'all');
            $list.empty();

            if (!items.length) {
                $list.hide();
                $empty.show();
                $root.find('.js-tenders-empty-title').text(hasFilters ? 'Ничего не найдено' : 'Нет тендеров');
                $root.find('.js-tenders-empty-des').text(
                    hasFilters
                        ? 'Измените поиск или сбросьте фильтры.'
                        : 'Здесь отображаются все процедуры вашей компании. Создайте тендер, чтобы начать закупку.'
                );
                $root.find('.js-tenders-empty-create').toggle(!hasFilters);
                $root.find('.js-tenders-empty-clear').toggle(!!hasFilters);
                return;
            }

            $empty.hide();
            $list.show();

            const self = this;
            items.forEach(function (item) {
                const created = self.formatDate(item.create_datetime) || '—';
                const deadline = self.formatDeadline(item.end_at);
                const daysLeft = self.calcDaysLeft(item.end_at);
                const daysLabel = self.formatDaysLeft(daysLeft);
                const expired = daysLeft !== null && daysLeft < 0;
                const access = item.is_private == 1 || item.is_private === true ? 'Закрытый' : 'Открытый';
                const tone = item.status_tone || 'info';
                const methodLine = (item.type_name || 'Тендер') + (item.number ? ' №' + item.number : '');

                const $card = $(`
                    <article class="buyer-tender-card" data-id="${item.id}">
                        <div class="buyer-tender-card__main">
                            <div class="buyer-tender-card__title-row">
                                <h3 class="buyer-tender-card__title"></h3>
                                <span class="badge badge--shared badge--medium ${tone} js-buyer-tender-status"></span>
                            </div>
                            <div class="buyer-tender-card__meta">
                                <span>Дата создания: ${created}</span>
                                <span class="buyer-tender-card__dot" aria-hidden="true"></span>
                                <span class="buyer-tender-card__method"></span>
                                <span class="buyer-tender-card__dot" aria-hidden="true"></span>
                                <span>${access}</span>
                            </div>
                        </div>
                        <div class="buyer-tender-card__metric">
                            <span class="buyer-tender-card__metric-label">Приглашено</span>
                            <span class="buyer-tender-card__metric-value">${parseInt(item.invited_count, 10) || 0}</span>
                        </div>
                        <div class="buyer-tender-card__metric">
                            <span class="buyer-tender-card__metric-label">Участники</span>
                            <span class="buyer-tender-card__metric-value">${parseInt(item.participants_count, 10) || 0}</span>
                        </div>
                        <div class="buyer-tender-card__metric">
                            <span class="buyer-tender-card__metric-label">Предложения</span>
                            <span class="buyer-tender-card__metric-value">${parseInt(item.proposals_count, 10) || 0}</span>
                        </div>
                        <div class="buyer-tender-card__deadline">
                            <span class="buyer-tender-card__metric-label">Окончание приёма заявок</span>
                            <div class="buyer-tender-card__deadline-row">
                                <span class="buyer-tender-card__metric-value${expired ? ' is-expired' : ''}">${deadline}</span>
                                ${daysLabel ? `<span class="badge badge--shared badge--small badge--tag badge--outline ${expired ? 'disabled' : 'neutral'}">${daysLabel}</span>` : ''}
                            </div>
                        </div>
                        <button type="button" class="buyer-tender-card__more" aria-label="Действия" disabled>⋮</button>
                    </article>`);
                $card.find('.buyer-tender-card__title').text(item.title || 'Без названия');
                $card.find('.js-buyer-tender-status').text(item.status_name || '—');
                $card.find('.buyer-tender-card__method').text(methodLine);
                $list.append($card);
            });
        },

    });

    $.Cabinet.registerPage('tender-form', {
        root: null,
        tenderId: 0,
        stepIndex: 0,
        highestStepReached: 0,
        selectedTypeId: 3,
        selectedTypeCode: 'price_request',
        editingPublishedTender: false,
        steps: ['privacy', 'basic', 'purchase_params', 'payment_delivery', 'lots', 'invitation'],
        /** @type {Object.<string, string>} step -> message после неудачного publish */
        publishIssuesByStep: null,
        items: [],
        techSpecFiles: [],
        criteria: [],
        extraInfoFields: [],
        extraApprovers: [],
        criteriaDrag: null,
        _criteriaDnDBound: false,
        bucket: 'all',
        search: '',
        filterType: '',
        filterStatus: '',
        categoryTreeRows: null,
        categoryClassifierType: 4,
        categoryTreePromise: null,
        categoryExpandedIds: null,
        classifierTabs: null,
        _categorySyncing: false,

        init: function (root) {
            this.root = root;
            const $root = $(root);
            const el = root;

            this.formMode = String(el.dataset.mode || 'create');
            this.selectedTypeCode = String(el.dataset.type || 'price_request');
            this.selectedTypeId = parseInt(el.dataset.typeId || '3', 10) || 3;
            this.tenderId = parseInt(el.dataset.tenderId || '0', 10) || 0;
            this.editingPublishedTender = this.formMode === 'edit'
                && String(el.dataset.statusCode || '') !== 'draft'
                && String(el.dataset.statusCode || '') !== '';

            let steps = [];
            try {
                steps = JSON.parse(el.dataset.steps || '[]');
            } catch (e) {
                steps = [];
            }
            if (!Array.isArray(steps) || !steps.length) {
                steps = ['privacy', 'basic', 'purchase_params', 'payment_delivery', 'lots', 'invitation'];
            }
            this.steps = steps;
            this.stepIndex = 0;
            this.highestStepReached = 0;
            this.publishIssuesByStep = null;

            this.items = [];
            this.techSpecFiles = [];
            this.criteria = [];
            this.extraInfoFields = [];
            this.extraApprovers = [];
            this.criteriaDrag = null;
            this._criteriaDnDBound = false;
            this.categoryTreeRows = null;
            this.categoryClassifierType = 4;
            this.categoryTreePromise = null;
            this.categoryExpandedIds = null;
            this.classifierTabs = new TabsManager({
                container: $root.find('.js-classifier-tabs'),
                defaultTab: 'common',
                hashSync: false,
                onChange: function ({ tab }) {
                    $root.find('.creation-card-categories-results').removeClass('active');
                    $root.find('.creation-card-categories-results[data-tab="' + tab + '"]').addClass('active');
                }
            });
            this._categorySyncing = false;
            this.lotPositions = [];
            this.lotDocuments = [];
            this.selectedLotId = '';
            this.selectedDocId = '';
            this.lotsActiveTab = 'lots';
            this.lotsPreviewMode = false;
            this.lotNmc = '0';
            this.lotVatRate = '20%';
            this.lotIdSeq = 0;
            this.budgetActionsHidden = false;
            this.invitationSelected = [];

            $root.find('.js-tender-id').val(String(this.tenderId));
            $root.find('.js-tender-type').val(String(this.selectedTypeId));
            $root.find('.js-price-request-only').toggle(this.selectedTypeCode === 'price_request');
            $root.find('.js-prequal-only').toggle(this.selectedTypeCode === 'prequalification');

            this.bindFormEvents();
            const self = this;
            $root.off('input.creationProgress change.creationProgress')
                .on('input.creationProgress change.creationProgress', 'input, select, textarea', function () {
                    Promise.resolve().then(function () { self.syncCreationSteps(); });
                });
            this.initInviteSelects();
            this.initPurchaseParamsUi();
            this.initPaymentDeliveryUi();
            this.initLotsUi();
            this.initInvitationUi();
            this.loadCategoryTree();

            let detail = null;
            const $detail = $root.find('.js-tender-form-detail');
            if ($detail.length) {
                try {
                    detail = JSON.parse($detail.text() || 'null');
                } catch (e) {
                    detail = null;
                }
            }

            if (detail && detail.tender) {
                this.applyDraftDetail(detail);
                const self = this;
                this.loadCategoryTree().then(function () {
                    if (detail.classifiers && detail.classifiers.length) {
                        const type = parseInt(self.categoryClassifierType, 10) || 4;
                        const ids = detail.classifiers.map(function (row) {
                            if (parseInt(row.classifier_type, 10) !== type) return 0;
                            return parseInt(row.classifier_id, 10) || 0;
                        }).filter(Boolean);
                        self.setSelectedCategoryIds(ids);
                    } else {
                        self.setSelectedCategoryIds([]);
                    }
                });
            } else {
                const self = this;
                this.resetPaymentDeliveryUi();
                this.resetLotsUi();
                this.renderCriteria();
                this.syncMinStepUi();
                this.syncBasicContinue();
                this.loadCategoryTree().then(function () {
                    self.setSelectedCategoryIds([]);
                });
            }

            $root.addClass('tender-create');
            this.goStep(0);
        },

        navigateCabinet: function (url) {
            if (typeof htmx !== 'undefined' && htmx.ajax) {
                htmx.ajax('GET', url, {
                    target: '.js-main-content',
                    swap: 'innerHTML',
                    push: url
                });
                return;
            }
            window.location.href = url;
        },

        /** После первого create: URL → /edit/, title «Редактирование…». */
        syncEditUrlAfterCreate: function () {
            if (this.formMode !== 'create' || this.tenderId <= 0) return;
            const url = '/cabinet/buyer/tender/' + this.tenderId + '/edit/';
            try {
                if (window.history && window.history.replaceState) {
                    window.history.replaceState({}, '', url);
                }
            } catch (e) { /* ignore */ }
            this.formMode = 'edit';
            const el = this.root;
            if (el && el.dataset) {
                el.dataset.mode = 'edit';
                el.dataset.tenderId = String(this.tenderId);
            }
            const $title = $(this.root).find('.js-creation-title');
            const current = $.trim($title.text() || '');
            if (current.indexOf('Создание') === 0) {
                $title.text('Редактирование' + current.slice('Создание'.length));
            } else if (current && current.indexOf('Редактирование') !== 0) {
                $title.text('Редактирование: ' + current);
            }
        },

        bindFormEvents: function () {
            const self = this;
            const $root = $(this.root);

            $root.find('.js-tender-create-back, .js-tender-create-cancel').on('click', function (e) {
                e.preventDefault();
                self.leaveForm();
            });

            $root.find('.js-tender-apply-template').click(function () {
                $.AlertManager.showError('Применение шаблона будет доступно позже');
            });

            $root.find('input[name="is_private"]').change(function () {
                const isPrivate = $(this).val() === '1';
                $root.find('.js-private-invites').toggleClass('visible', isPrivate);
                $root.find('.js-past-prequal-wrap').toggle(isPrivate && self.selectedTypeCode === 'price_request');
                if (!isPrivate) {
                    $root.find('.js-field-past-prequal').prop('checked', false);
                    $root.find('.js-past-prequal-block').removeClass('visible');
                }
            });

            $root.find('.js-field-past-prequal').change(function () {
                $root.find('.js-past-prequal-block').toggleClass('visible', $(this).is(':checked'));
            });

            $root.find('.js-field-approval-required').change(function () {
                $root.find('.js-approval-period-wrap').toggleClass('visible', $(this).is(':checked'));
                if (!$(this).is(':checked')) {
                    $root.find('.js-field-approval-period').prop('checked', false);
                    $root.find('.js-approval-period-fields').removeClass('visible');
                }
            });

            $root.find('.js-field-approval-period').change(function () {
                $root.find('.js-approval-period-fields').toggleClass('visible', $(this).is(':checked'));
            });

            $root.find('.js-field-hide-prices').change(function () {
                $root.find('.js-hide-prices-options').toggleClass('visible', $(this).is(':checked'));
            });

            $root.off('click.stepValidationDismiss', '.js-step-validation-dismiss')
                .on('click.stepValidationDismiss', '.js-step-validation-dismiss', function () {
                    $(this).closest('.js-step-validation-banner').hide();
                });

            $root.find('.js-field-title').on('input', function () {
                if (self.publishIssuesByStep && self.publishIssuesByStep.basic) {
                    self.refreshPublishIssueForStep('basic');
                }
            });
            $root.find('.js-field-end-date, .js-field-end-time').on('change input', function () {
                if (self.publishIssuesByStep && self.publishIssuesByStep.purchase_params) {
                    self.refreshPublishIssueForStep('purchase_params');
                }
            });
            $root.find('input[name="is_private"], .js-invite-select').on('change', function () {
                if (self.publishIssuesByStep && self.publishIssuesByStep.privacy) {
                    self.refreshPublishIssueForStep('privacy');
                }
            });

            $root.off('change.tenderClassifier').on('change.tenderClassifier', '.js-classifier-check', function () {
                self.syncCategoryCheckboxBranch($(this));
                self.renderClassifierSelected($(this).closest('.creation-card-categories-results'));
            });

            $root.off('click.clfToggle').on('click.clfToggle', '.js-clf-toggle', function (e) {
                e.preventDefault();
                e.stopPropagation();
                self.toggleCategoryExpand(parseInt($(this).closest('.clf-node').data('id'), 10) || 0);
            });

            $root.find('.js-classifier-selected').on('click', '.js-classifier-tag-remove', function () {
                const value = String($(this).closest('.tag').data('value') || '');
                const $panel = $(this).closest('.creation-card-categories-results');
                const $cb = $panel.find('.js-classifier-check').filter(function () {
                    return String($(this).val()) === value;
                });
                $cb.prop('checked', false);
                self.syncCategoryCheckboxBranch($cb.first());
                self.renderClassifierSelected($panel);
            });

            $root.find('.js-classifier-search').on('input', function () {
                if (String($(this).data('tab') || 'common') === 'common') {
                    self.filterCategoryTree(String($(this).val() || ''));
                }
            });

            $root.find('.js-tech-spec-dropzone').on('click', function (e) {
                if ($(e.target).closest('.js-tech-spec-file-input').length) return;
                $root.find('.js-tech-spec-file-input').trigger('click');
            });

            $root.find('.js-tech-spec-dropzone').on('dragover dragenter', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('is-dragover');
            });

            $root.find('.js-tech-spec-dropzone').on('dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('is-dragover');
            });

            $root.find('.js-tech-spec-dropzone').on('drop', function (e) {
                const files = e.originalEvent && e.originalEvent.dataTransfer
                    ? e.originalEvent.dataTransfer.files
                    : null;
                self.addTechSpecFiles(files);
            });

            $root.find('.js-tech-spec-file-input').on('change', function () {
                self.addTechSpecFiles(this.files);
                $(this).val('');
            });

            $root.find('.js-tech-spec-file-list').on('click', '.js-tech-spec-file-remove', function () {
                const id = String($(this).closest('.file-item').data('id') || '');
                self.techSpecFiles = self.techSpecFiles.filter(function (file) {
                    return String(file.id) !== id;
                });
                self.renderTechSpecFiles();
            });

            $root.find('.js-field-auto-extend-change').change(function () {
                const on = $(this).is(':checked');
                $root.find('.js-renewal-inner').toggleClass('visible', on);
                if (!on) {
                    $root.find('.js-field-min-step, .js-field-only-reduction').prop('checked', false);
                    $root.find('.js-min-step-radios').removeClass('visible');
                } else {
                    self.syncMinStepUi();
                }
            });

            $root.find('.js-field-min-step').change(function () {
                self.syncMinStepUi();
            });

            $root.find('.js-min-step-type').click(function () {
                $root.find('.js-min-step-type').removeClass('active');
                $(this).addClass('active');
                const isAmount = String($(this).data('type')) === 'amount';
                $root.find('.js-min-step-amount').toggle(isAmount);
                $root.find('.js-min-step-percent').toggle(!isAmount);
            });

            $root.find('.js-add-info-field').click(function () {
                self.extraInfoFields.push({
                    id: 'info_' + Date.now() + '_' + Math.random().toString(36).slice(2, 6),
                    value: ''
                });
                self.renderExtraInfoFields();
            });

            $root.find('.js-add-approver').click(function () {
                self.extraApprovers.push({
                    id: 'appr_' + Date.now() + '_' + Math.random().toString(36).slice(2, 6),
                    value: ''
                });
                self.renderExtraApprovers();
            });

            $root.on('click', '.js-extra-info-remove', function () {
                const id = String($(this).closest('.add-info-extra-row').data('id') || '');
                self.extraInfoFields = self.extraInfoFields.filter(function (row) {
                    return String(row.id) !== id;
                });
                self.renderExtraInfoFields();
            });

            $root.on('click', '.js-extra-approver-remove', function () {
                const id = String($(this).closest('.add-info-extra-row').data('id') || '');
                self.extraApprovers = self.extraApprovers.filter(function (row) {
                    return String(row.id) !== id;
                });
                self.renderExtraApprovers();
            });

            $root.on('input', '.js-extra-info-input', function () {
                const id = String($(this).closest('.add-info-extra-row').data('id') || '');
                const value = $.trim($(this).val() || '');
                self.extraInfoFields = self.extraInfoFields.map(function (row) {
                    return String(row.id) === id ? $.extend({}, row, { value: value }) : row;
                });
            });

            $root.on('input', '.js-extra-approver-input', function () {
                const id = String($(this).closest('.add-info-extra-row').data('id') || '');
                const value = $.trim($(this).val() || '');
                self.extraApprovers = self.extraApprovers.map(function (row) {
                    return String(row.id) === id ? $.extend({}, row, { value: value }) : row;
                });
            });

            $root.find('.js-criterion-add').click(function () {
                self.openCriterionDialog(null);
            });

            $root.find('.js-criteria-list').on('click', '.js-criterion-edit', function () {
                const id = String($(this).closest('.js-criteria-item').data('id') || '');
                const item = self.criteria.find(function (c) { return String(c.id) === id; });
                if (item) self.openCriterionDialog(item);
            });

            $root.find('.js-criteria-list').on('click', '.js-criterion-remove', function () {
                const id = String($(this).closest('.js-criteria-item').data('id') || '');
                self.criteria = self.criteria.filter(function (c) { return String(c.id) !== id; });
                self.renderCriteria();
            });

            $root.find('.js-tender-step-next').click(function () {
                self.goStep(self.stepIndex + 1);
            });

            $root.find('.js-tender-step-prev').click(function () {
                self.goStep(self.stepIndex - 1);
            });

            $root.find('.js-tender-steps').on('click', '.creation-step', function () {
                const index = self.steps.indexOf(String($(this).data('step') || ''));
                if (!self.canJumpToStep(index)) return;
                self.goStep(index);
            });

            $root.find('.js-tender-save-draft').click(function () {
                self.saveDraft();
            });
        },

        initPurchaseParamsUi: function () {
            const $root = $(this.root);
            const utils = $.Cabinet && $.Cabinet.utils ? $.Cabinet.utils : null;

            const $renewal = $root.find('.js-field-renewal-period');
            if ($renewal.length && !$renewal.hasClass('select2-hidden-accessible')) {
                $renewal.select2({
                    language: 'ru',
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
            }

            if (utils && typeof utils.initDatePicker === 'function') {
                $root.find('.js-field-end-date, .js-field-docs-date, .js-field-result-date, .js-field-approval-date').each(function () {
                    utils.initDatePicker($(this), { dateFormat: 'd.m.Y', allowInput: false });
                });
            }
            if (utils && typeof utils.initTimePicker === 'function') {
                $root.find('.js-field-end-time, .js-field-docs-time, .js-field-approval-time').each(function () {
                    utils.initTimePicker($(this), {});
                });
            }

            this.syncMinStepUi();
            this.renderCriteria();
        },

        initPaymentDeliveryUi: function () {
            const self = this;
            const $root = $(this.root);

            const syncCharCounter = function ($textarea) {
                const max = parseInt($textarea.attr('maxlength'), 10) || 0;
                const len = String($textarea.val() || '').length;
                $textarea.closest('.tender-form-textarea').find('.char-counter').text(len + '/' + max);
            };

            $root.find('.js-payment-delivery-step textarea[maxlength]').each(function () {
                syncCharCounter($(this));
            });

            $root.off('input.paymentChars', '.js-payment-delivery-step textarea[maxlength]')
                .on('input.paymentChars', '.js-payment-delivery-step textarea[maxlength]', function () {
                    syncCharCounter($(this));
                });

            const initPlainSelect = function ($select) {
                if (!$select.length) return;
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $select.select2({
                    language: 'ru',
                    placeholder: $select.data('placeholder') || '',
                    allowClear: true,
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });
            };

            initPlainSelect($root.find('.js-field-payment'));
            $root.find('.js-tender-contact').each(function () {
                initPlainSelect($(this));
            });

            $root.off('click.addDeliveryAddress', '.js-add-delivery-address')
                .on('click.addDeliveryAddress', '.js-add-delivery-address', function () {
                    const $list = $root.find('.js-delivery-address-list');
                    const $item = $(`
                        <div class="tender-form-list__item js-payment-list-item">
                            <div class="tender-form-textarea">
                                <textarea class="js-delivery-address" name="delivery_addresses[]" maxlength="500" rows="3" placeholder="Индекс, Страна Область, населенный пункт, Улица, дом, Строение/корпус, квартира/офис"></textarea>
                                <div class="tender-form-textarea__counter char-counter">0/500</div>
                            </div>
                        </div>
                    `);
                    $list.append($item);
                    syncCharCounter($item.find('textarea'));
                });

            $root.off('click.addTenderContact', '.js-add-tender-contact')
                .on('click.addTenderContact', '.js-add-tender-contact', function () {
                    const $list = $root.find('.js-contact-list');
                    const $item = $(`
                        <div class="tender-form-list__item js-payment-list-item">
                            <select class="select2 filter-select js-tender-contact" name="tender_contacts[]" data-placeholder="Выберите контакт из списка организации">
                                <option value=""></option>
                            </select>
                        </div>
                    `);
                    $list.append($item);
                    initPlainSelect($item.find('select'));
                });

            $root.off('click.addPaymentTerms', '.js-add-payment-terms')
                .on('click.addPaymentTerms', '.js-add-payment-terms', function () {
                    $.AlertManager.showError('Шаблоны условий оплаты будут доступны позже');
                });

            $root.off('click.createExternalContact', '.js-create-external-contact')
                .on('click.createExternalContact', '.js-create-external-contact', function () {
                    $.AlertManager.showError('Создание контакта вне организации будет доступно позже');
                });
        },

        resetPaymentDeliveryUi: function () {
            const $root = $(this.root);
            $root.find('.js-field-budget').val('');
            $root.find('.js-field-additional-delivery').val('');
            $root.find('.js-field-info-consent').prop('checked', false);

            const $addrList = $root.find('.js-delivery-address-list');
            $addrList.find('.js-payment-list-item').slice(1).remove();
            $addrList.find('.js-delivery-address').val('');
            $addrList.find('.char-counter').text('0/500');
            $root.find('.js-field-additional-delivery').closest('.tender-form-textarea').find('.char-counter').text('0/200');

            const $payment = $root.find('.js-field-payment');
            $payment.val(null).trigger('change');

            const $contactList = $root.find('.js-contact-list');
            $contactList.find('.js-payment-list-item').slice(1).each(function () {
                const $select = $(this).find('select');
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $(this).remove();
            });
            $contactList.find('.js-tender-contact').val(null).trigger('change');
        },

        /* ── Рассылка: инициализация UI ── */
        invitationSelected: [],   // { id, name } — id компании (число) или email

        initInvitationUi: function () {
            const self = this;
            const $root = $(this.root);
            const $step = $root.find('.tender-form-step--invitation');
            if (!$step.length) return;

            self._invActiveTab = 'all';
            self._invSuppliers = [];
            self._invSearchTimer = null;
            self.invitationSelected = [];
            let initialInvitationTab = true;

            this.invitationTabs = new TabsManager({
                container: $step.find('.js-inv-tabs'),
                defaultTab: 'all',
                hashSync: false,
                onChange: function ({ tab }) {
                    self._invActiveTab = String(tab || 'all');
                    if (initialInvitationTab) {
                        initialInvitationTab = false;
                        return;
                    }
                    $step.find('.js-inv-search').val('');
                    self.loadInvSuppliers();
                }
            });

            $root.off('input.invSearch', '.js-inv-search')
                .on('input.invSearch', '.js-inv-search', function () {
                    clearTimeout(self._invSearchTimer);
                    self._invSearchTimer = setTimeout(function () {
                        self.loadInvSuppliers();
                    }, 250);
                });

            $root.off('click.invItem', '.tender-invitation-list__item')
                .on('click.invItem', '.tender-invitation-list__item', function () {
                    var id = String($(this).data('id') || '');
                    var name = String($(this).data('name') || '');
                    if (!id) return;
                    var idx = self.invitationSelected.findIndex(function (s) { return String(s.id) === id; });
                    if (idx >= 0) {
                        self.invitationSelected.splice(idx, 1);
                    } else {
                        self.invitationSelected.push({ id: id, name: name });
                    }
                    self.syncPrivacyInviteSelectFromInvitation();
                    self.renderInvSupplierList();
                    self.renderInvSelected();
                    self.syncInvPublishBtn();
                });

            $root.off('click.invRemove', '.js-inv-remove')
                .on('click.invRemove', '.js-inv-remove', function () {
                    var id = String($(this).data('id') || '');
                    self.invitationSelected = self.invitationSelected.filter(function (s) {
                        return String(s.id) !== id;
                    });
                    self.syncPrivacyInviteSelectFromInvitation();
                    self.renderInvSelected();
                    self.renderInvSupplierList();
                    self.syncInvPublishBtn();
                });

            $root.off('click.invClear', '.js-inv-clear-all')
                .on('click.invClear', '.js-inv-clear-all', function () {
                    self.invitationSelected = [];
                    self.syncPrivacyInviteSelectFromInvitation();
                    self.renderInvSelected();
                    self.renderInvSupplierList();
                    self.syncInvPublishBtn();
                });

            $root.off('click.invEmailAdd', '.js-inv-email-add')
                .on('click.invEmailAdd', '.js-inv-email-add', function () {
                    self.addInvEmail();
                });
            $root.off('keydown.invEmail', '.js-inv-email-input')
                .on('keydown.invEmail', '.js-inv-email-input', function (e) {
                    if (e.key === 'Enter') { e.preventDefault(); self.addInvEmail(); }
                });

            $root.off('input.invMsg', '.js-inv-msg-textarea')
                .on('input.invMsg', '.js-inv-msg-textarea', function () {
                    var len = String($(this).val() || '').length;
                    var max = parseInt($(this).attr('maxlength'), 10) || 700;
                    var $counter = $step.find('.js-inv-msg-counter');
                    $counter.text(len + ' / ' + max);
                    $counter.toggleClass('is-limit', len >= max);
                    var text = $.trim($(this).val() || '');
                    var $extra = $step.find('.js-inv-preview-extra');
                    var $hint = $step.find('.js-inv-preview-hint');
                    if (text) {
                        $extra.text(text).show();
                        $hint.hide();
                    } else {
                        $extra.hide();
                        $hint.show();
                    }
                });

            $root.off('change.invSendType', '.js-inv-send-type-radio')
                .on('change.invSendType', '.js-inv-send-type-radio', function () {
                    self.syncInvPublishBtn();
                    if (this.value === 'auto') self.loadInvSuppliers();
                });

            $root.off('click.invPublish', '.js-tender-publish')
                .on('click.invPublish', '.js-tender-publish', function () {
                    if ($(this).prop('disabled')) return;
                    self.publish();
                });

            $root.off('click.invUpload', '.js-inv-upload-btn')
                .on('click.invUpload', '.js-inv-upload-btn', function () {
                    var input = document.createElement('input');
                    input.type = 'file';
                    input.accept = '.xlsx,.xls,.csv';
                    input.click();
                });
            $root.off('click.invChevron', '.js-inv-upload-chevron')
                .on('click.invChevron', '.js-inv-upload-chevron', function (e) {
                    e.stopPropagation();
                    var $dd = $step.find('.js-inv-upload-dropdown');
                    var $combo = $step.find('.js-inv-upload-combo');
                    var isOpen = $dd.is(':visible');
                    $dd.toggle(!isOpen);
                    $combo.toggleClass('is-open', !isOpen);
                });
            $root.off('click.invTemplate', '.tender-invitation-upload-dropdown__item')
                .on('click.invTemplate', '.tender-invitation-upload-dropdown__item', function (e) {
                    e.preventDefault();
                });
            $(document).off('click.invDropdown').on('click.invDropdown', function () {
                $step.find('.js-inv-upload-dropdown').hide();
                $step.find('.js-inv-upload-combo').removeClass('is-open');
            });

            self.syncInvPublishBtn();
        },

        /** Числовые company id из выбранных приглашений. */
        getInvitationCompanyIds: function () {
            return (this.invitationSelected || [])
                .map(function (s) { return String(s.id || ''); })
                .filter(function (id) { return /^\d+$/.test(id); });
        },

        /** Privacy-select → invitationSelected (email сохраняем). */
        syncInvitationSelectedFromPrivacy: function () {
            const self = this;
            const $select = $(this.root).find('.js-invite-select');
            const emails = (this.invitationSelected || []).filter(function (s) {
                return String(s.id || '').includes('@');
            });
            this.invitationSelected = [];
            let rows = [];
            if ($select.length && $select.hasClass('select2-hidden-accessible')) {
                rows = $select.select2('data') || [];
            }
            rows.forEach(function (item) {
                const id = String(item.id || '');
                if (!/^\d+$/.test(id)) return;
                self.invitationSelected.push({
                    id: id,
                    name: String(item.text || item.name || id)
                });
            });
            emails.forEach(function (row) {
                self.invitationSelected.push(row);
            });
        },

        /** invitationSelected → privacy .js-invite-select (только company id). */
        syncPrivacyInviteSelectFromInvitation: function () {
            const $select = $(this.root).find('.js-invite-select');
            if (!$select.length) return;
            $select.find('option').remove();
            (this.invitationSelected || []).forEach(function (s) {
                const id = String(s.id || '');
                if (!/^\d+$/.test(id)) return;
                const opt = new Option(s.name || id, id, true, true);
                $select.append(opt);
            });
            $select.trigger('change');
        },

        loadInvSuppliers: function () {
            const self = this;
            const $root = $(this.root);
            if ($root.find('input[name="inv_send_type"]:checked').val() !== 'auto') return Promise.resolve([]);
            const $list = $root.find('.js-inv-supplier-list');
            if (!$list.length) return Promise.resolve([]);

            const tab = self._invActiveTab || 'all';
            if (tab === 'groups') {
                self._invSuppliers = [];
                self.renderInvSupplierList();
                return Promise.resolve([]);
            }

            const q = $.trim($root.find('.js-inv-search').val() || '');
            return $.ajax({
                url: '/api/buyer/company/select/',
                method: 'GET',
                dataType: 'json',
                data: {
                    search: q,
                    supplier: 1,
                    page: 1
                }
            }).then(function (resp) {
                const bag = (resp && resp.data) || (resp && resp.payload) || resp || {};
                const rows = Array.isArray(bag.results) ? bag.results : [];
                self._invSuppliers = rows.map(function (row) {
                    return {
                        id: String(row.id || ''),
                        name: String(row.text || row.name || row.id || '')
                    };
                }).filter(function (s) { return /^\d+$/.test(s.id); });
                self.renderInvSupplierList();
                return self._invSuppliers;
            }).catch(function () {
                self._invSuppliers = [];
                self.renderInvSupplierList();
                return [];
            });
        },

        addInvEmail: function () {
            var self = this;
            var $root = $(this.root);
            var $input = $root.find('.js-inv-email-input');
            var email = $.trim($input.val() || '');
            if (!email || !email.includes('@')) return;
            if (self.invitationSelected.some(function (s) { return String(s.id) === email; })) {
                $input.val('');
                return;
            }
            self.invitationSelected.push({ id: email, name: email });
            $input.val('');
            self.renderInvSelected();
            self.syncInvPublishBtn();
        },

        renderInvSupplierList: function () {
            var self = this;
            var $root = $(this.root);
            var $list = $root.find('.js-inv-supplier-list');
            if (!$list.length) return;

            var suppliers = self._invSuppliers || [];

            var $empty = $list.find('.js-inv-list-empty');
            $list.find('.tender-invitation-list__item').remove();

            if (!suppliers.length) {
                $empty.show();
                return;
            }
            $empty.hide();

            var checkSvg = '<svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6L5 8.5L9.5 4" stroke="var(--white,#fff)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            suppliers.forEach(function (s) {
                var sid = String(s.id);
                var isChecked = self.invitationSelected.some(function (sel) { return String(sel.id) === sid; });
                var $item = $('<button type="button" class="tender-invitation-list__item' + (isChecked ? ' is-checked' : '') + '">'
                    + '<span class="tender-invitation-list__checkbox">' + (isChecked ? checkSvg : '') + '</span>'
                    + '<span class="tender-invitation-list__name"></span>'
                    + '</button>');
                $item.data('id', sid).data('name', s.name);
                $item.find('.tender-invitation-list__name').text(s.name);
                $list.append($item);
            });
        },

        renderInvSelected: function () {
            var self = this;
            var $root = $(this.root);
            var $count = $root.find('.js-inv-selected-count');
            var $empty = $root.find('.js-inv-selected-empty');
            var $panel = $root.find('.js-inv-selected-list');
            var $items = $root.find('.js-inv-selected-items');
            var $emailTags = $root.find('.js-inv-email-tags');

            /* Счётчик */
            var systemSelected = self.invitationSelected.filter(function (s) { return !s.id.includes('@'); });
            var emailSelected = self.invitationSelected.filter(function (s) { return s.id.includes('@'); });

            $count.text(self.invitationSelected.length);

            if (self.invitationSelected.length === 0) {
                $empty.show();
                $panel.hide();
            } else {
                $empty.hide();
                $panel.show();
                $items.empty();
                systemSelected.forEach(function (s) {
                    var $item = $('<div class="tender-invitation-selected__item">'
                        + '<span class="tender-invitation-selected__item-name"></span>'
                        + '<button type="button" class="tender-invitation-selected__item-remove js-inv-remove" title="Убрать">'
                        + '<svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 2l10 10M12 2L2 12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>'
                        + '</button>'
                        + '</div>');
                    $item.find('.tender-invitation-selected__item-name').text(s.name);
                    $item.find('.js-inv-remove').data('id', s.id);
                    $items.append($item);
                });
            }

            /* E-mail теги */
            $emailTags.empty();
            if (emailSelected.length) {
                emailSelected.forEach(function (s) {
                    var $tag = $('<span class="tender-invitation-email-tag">'
                        + '<span class="tender-invitation-email-tag__text"></span>'
                        + '<button type="button" class="tender-invitation-email-tag__remove js-inv-remove">'
                        + '<svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 2l10 10M12 2L2 12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>'
                        + '</button>'
                        + '</span>');
                    $tag.find('.tender-invitation-email-tag__text').text(s.name);
                    $tag.find('.js-inv-remove').data('id', s.id);
                    $emailTags.append($tag);
                });
                $emailTags.show();
            } else {
                $emailTags.hide();
            }
        },

        syncInvPublishBtn: function () {
            var $root = $(this.root);
            var $btn = $root.find('.js-tender-publish');
            var sendType = $root.find('input[name="inv_send_type"]:checked').val() || 'manual';
            $root.find('.js-inv-auto-content').toggle(sendType === 'auto');
            var isPrivate = $root.find('input[name="is_private"]:checked').val() === '1';
            var companyCount = this.getInvitationCompanyIds().length;
            var selectedCount = (this.invitationSelected || []).length;
            /* Вручную — публиковать можно; авто — нужны получатели; закрытая — company id */
            var hasRecipients = isPrivate ? companyCount > 0 : selectedCount > 0;
            var enabled = this.editingPublishedTender || sendType === 'manual' || hasRecipients;
            $btn.prop('disabled', !enabled);
            $btn.text(this.editingPublishedTender ? 'Сохранить' : (sendType === 'auto' ? 'Опубликовать' : 'Опубликовать без рассылки'));
            this.syncCreationSteps();
        },

        initLotsUi: function () {
            const self = this;
            const $root = $(this.root);
            const $step = $root.find('.tender-form-step--lots');
            if (!$step.length) return;

            this.lotsTabs = new TabsManager({
                container: $root.find('.js-lots-tabs'),
                hashSync: false,
                defaultTab: this.lotsActiveTab || 'lots',
                onChange: function ({ tab }) {
                    self.lotsActiveTab = tab;
                    self.syncLotsTabUi();
                }
            });

            $root.off('click.lotsAdd', '.js-lots-add')
                .on('click.lotsAdd', '.js-lots-add', function () {
                    if (self.lotsActiveTab === 'documents') {
                        self.addLotDocument();
                    } else {
                        self.addLotPosition();
                    }
                });

            $root.off('click.lotsSelect', '.js-lot-item')
                .on('click.lotsSelect', '.js-lot-item', function (e) {
                    if ($(e.target).closest('.js-lot-dup, .js-lot-del, .js-doc-del').length) return;
                    const id = String($(this).data('id') || '');
                    if ($(this).hasClass('js-doc-item')) {
                        self.selectedDocId = id;
                        self.renderDocsList();
                        self.renderDocEditor();
                    } else {
                        self.selectedLotId = id;
                        self.renderLotsList();
                        self.renderLotEditor();
                    }
                });

            $root.off('click.lotsDup', '.js-lot-dup')
                .on('click.lotsDup', '.js-lot-dup', function (e) {
                    e.stopPropagation();
                    const id = String($(this).closest('[data-id]').data('id') || '');
                    self.duplicateLotPosition(id);
                });

            $root.off('click.lotsDel', '.js-lot-del')
                .on('click.lotsDel', '.js-lot-del', function (e) {
                    e.stopPropagation();
                    const id = String($(this).closest('[data-id]').data('id') || '');
                    self.deleteLotPosition(id);
                });

            $root.off('click.docsDel', '.js-doc-del')
                .on('click.docsDel', '.js-doc-del', function (e) {
                    e.stopPropagation();
                    const id = String($(this).closest('[data-id]').data('id') || '');
                    self.deleteLotDocument(id);
                });

            $root.off('click.lotsPreview', '.js-lots-preview')
                .on('click.lotsPreview', '.js-lots-preview', function () {
                    self.enterLotsPreview();
                });

            $root.off('click.lotsExitPreview', '.js-lots-exit-preview')
                .on('click.lotsExitPreview', '.js-lots-exit-preview', function () {
                    self.exitLotsPreview();
                });

            $root.off('click.lotsTpl', '.js-lots-apply-template')
                .on('click.lotsTpl', '.js-lots-apply-template', function () {
                    $.AlertManager.showError('Шаблоны позиций будут доступны позже');
                });

            $root.off('click.lotsAddField', '.js-lots-add-field')
                .on('click.lotsAddField', '.js-lots-add-field', function () {
                    $.AlertManager.showError('Дополнительные поля позиции будут доступны позже');
                });

            $root.off('click.lotsErrorDismiss', '.js-lots-error-dismiss')
                .on('click.lotsErrorDismiss', '.js-lots-error-dismiss', function () {
                    $root.find('.js-lots-error-banner').hide();
                });

            $root.off('click.budgetAccept', '.js-budget-accept-nmc')
                .on('click.budgetAccept', '.js-budget-accept-nmc', function () {
                    const summary = self.computeLotsBudget();
                    self.lotNmc = String(summary.sumPrices);
                    self.budgetActionsHidden = true;
                    self.renderLotsBudget();
                });

            $root.off('click.budgetClear', '.js-budget-clear-prices')
                .on('click.budgetClear', '.js-budget-clear-prices', function () {
                    self.lotPositions = self.lotPositions.map(function (p) {
                        return Object.assign({}, p, { maxPriceNoVat: '0' });
                    });
                    self.budgetActionsHidden = true;
                    self.renderLotsList();
                    self.renderLotEditor();
                    self.renderLotsBudget();
                });

            $root.off('input.lotsField change.lotsField', '.js-lot-field, .js-doc-field')
                .on('input.lotsField change.lotsField', '.js-lot-field, .js-doc-field', function () {
                    self.onLotsFieldChange($(this));
                });

            $root.off('click.lotsUpload', '.js-lots-upload-zone')
                .on('click.lotsUpload', '.js-lots-upload-zone', function () {
                    $(this).closest('.tender-lots-upload').find('.js-lots-file-input').trigger('click');
                });

            $root.off('change.lotsFile', '.js-lots-file-input')
                .on('change.lotsFile', '.js-lots-file-input', function () {
                    const input = this;
                    const file = input.files && input.files[0] ? input.files[0] : null;
                    const kind = String($(input).data('kind') || 'lot');
                    const targetId = kind === 'doc' ? self.selectedDocId : self.selectedLotId;
                    input.value = '';
                    if (!file || !targetId) return;
                    if (!self.assertTenderUploadFile(file)) return;

                    const patch = {
                        fileName: file.name,
                        fileSize: file.size || 0,
                        file_link_id: null,
                        _file: file
                    };
                    if (kind === 'doc') {
                        self.updateLotDocById(targetId, patch);
                        if (self.selectedDocId === targetId) self.renderDocEditor();
                        self.renderDocsList();
                    } else {
                        self.updateLotPosById(targetId, patch);
                        if (self.selectedLotId === targetId) self.renderLotEditor();
                        self.renderLotsList();
                    }
                });

            $root.off('click.lotsFileRemove', '.js-lots-file-remove')
                .on('click.lotsFileRemove', '.js-lots-file-remove', function () {
                    const kind = String($(this).data('kind') || 'lot');
                    const clearPatch = {
                        fileName: '',
                        fileSize: 0,
                        file_link_id: null,
                        _file: null
                    };
                    if (kind === 'doc') {
                        self.updateLotDocById(self.selectedDocId, clearPatch);
                        self.renderDocEditor();
                        self.renderDocsList();
                    } else {
                        self.updateLotPosById(self.selectedLotId, clearPatch);
                        self.renderLotEditor();
                        self.renderLotsList();
                    }
                });

            $root.off('click.docRequired', '.js-doc-required')
                .on('click.docRequired', '.js-doc-required', function () {
                    const doc = self.getSelectedDoc();
                    if (!doc) return;
                    self.updateSelectedDoc('isRequired', !doc.isRequired);
                    self.renderDocEditor();
                    self.renderDocsList();
                });

            $root.off('click.previewPosRow', '.js-preview-pos-row')
                .on('click.previewPosRow', '.js-preview-pos-row', function () {
                    const id = String($(this).data('id') || '');
                    self.exitLotsPreview(id);
                });
        },

        resetLotsUi: function () {
            const isPrequal = this.isLotsPrequal();
            this.lotIdSeq = 0;
            this.lotsActiveTab = 'lots';
            this.lotsPreviewMode = false;
            this.budgetActionsHidden = false;
            this.lotNmc = '0';
            this.lotVatRate = '20%';

            const posId = this.nextLotId('pos_');
            this.lotPositions = [{
                id: posId,
                name: isPrequal ? 'Позиция №1' : 'Позиция N1',
                quantity: '',
                unit: 'шт.',
                maxPriceNoVat: '0',
                vatRate: '0%',
                deliveryPlace: '',
                comment: '',
                fileName: '',
                fileSize: 0,
                file_link_id: null
            }];
            this.lotDocuments = [];
            this.selectedLotId = posId;
            this.selectedDocId = '';
            this.syncLotsStepUi();
        },

        syncLotsStepUi: function () {
            const $root = $(this.root);
            this.lotsPreviewMode = false;
            $root.find('.js-lots-edit').show();
            $root.find('.js-lots-preview-view').hide();
            $root.find('.js-lots-error-banner').hide();
            $root.find('.js-lots-tabs .tab-btn[data-tab="documents"] > a').text(
                this.isLotsPrequal() ? 'Запрашиваемые документы' : 'Документы'
            );
            this.syncLotsTabUi();
            this.renderLotsList();
            this.renderDocsList();
            this.renderLotEditor();
            this.renderDocEditor();
            this.renderLotsBudget();
            this.syncLotsBudgetVisibility();
        },

        showLotsError: function (message) {
            const $root = $(this.root);
            $root.find('.js-lots-error-text').text(message || 'Заполните позицию');
            $root.find('.js-lots-error-banner').show();
        },

        hasReadyLotPositions: function () {
            const self = this;
            return (this.lotPositions || []).some(function (pos) {
                return self.getLotStatus(pos) === 'valid';
            });
        },

        nextLotId: function (prefix) {
            this.lotIdSeq += 1;
            return prefix + this.lotIdSeq + '_' + Date.now();
        },

        isLotsPrequal: function () {
            return this.selectedTypeCode === 'prequalification';
        },

        showLotsBudget: function () {
            const code = this.selectedTypeCode;
            return code === 'price_request' || code === 'proposal_request' || code === 'auction';
        },

        syncLotsBudgetVisibility: function () {
            const $root = $(this.root);
            const show = this.showLotsBudget() && this.lotsActiveTab === 'lots' && !this.lotsPreviewMode;
            $root.find('.js-lots-budget-col').toggle(show);
        },

        syncLotsTabUi: function () {
            const $root = $(this.root);
            const isDocs = this.lotsActiveTab === 'documents';
            const $tabs = $root.find('.js-lots-tabs .tab-btn');
            $tabs.removeClass('selected').find('[role="tab"]').attr('aria-selected', 'false');
            $tabs.filter('[data-tab="' + this.lotsActiveTab + '"]').addClass('selected')
                .find('[role="tab"]').attr('aria-selected', 'true');
            $root.find('.js-lots-list').toggle(!isDocs);
            $root.find('.js-docs-list').toggle(isDocs);
            $root.find('.js-lot-editor-wrap').toggle(!isDocs);
            $root.find('.js-doc-editor-wrap').toggle(isDocs);
            $root.find('.js-lots-add-label').text(isDocs ? 'Добавить документ' : 'Добавить позицию');
            const hasItems = isDocs ? this.lotDocuments.length > 0 : this.lotPositions.length > 0;
            $root.find('.js-lots-editor-empty-text').text(
                hasItems
                    ? (isDocs ? 'Выберите документ из списка слева' : 'Выберите позицию из списка слева')
                    : (isDocs ? 'Добавьте документ' : 'Добавьте позицию')
            );
            this.syncLotsBudgetVisibility();
            this.syncLotsEditorEmpty();
        },

        syncLotsEditorEmpty: function () {
            const $root = $(this.root);
            const isDocs = this.lotsActiveTab === 'documents';
            const empty = isDocs ? !this.getSelectedDoc() : !this.getSelectedLot();
            $root.find('.js-lots-editor-empty').toggle(empty);
            if (isDocs) {
                $root.find('.js-doc-editor-wrap').toggle(!empty);
            } else {
                $root.find('.js-lot-editor-wrap').toggle(!empty);
            }
        },

        getSelectedLot: function () {
            const id = this.selectedLotId;
            return this.lotPositions.find(function (p) { return p.id === id; }) || null;
        },

        getSelectedDoc: function () {
            const id = this.selectedDocId;
            return this.lotDocuments.find(function (d) { return d.id === id; }) || null;
        },

        getLotStatus: function (pos) {
            const hasName = String(pos.name || '').trim().length > 0;
            const hasQty = Number(pos.quantity) > 0;
            const hasUnit = String(pos.unit || '').trim().length > 0;
            if (this.isLotsPrequal()) {
                if (hasName && hasQty && hasUnit) return 'valid';
                const nameIsFresh = !hasName
                    || String(pos.name).trim().indexOf('Позиция N') === 0
                    || String(pos.name).trim().indexOf('Позиция №') === 0;
                if (nameIsFresh && !hasQty) return 'draft';
                return 'incomplete';
            }
            const hasDelivery = String(pos.deliveryPlace || '').trim().length > 0;
            if (hasName && hasQty && hasUnit && hasDelivery) return 'valid';
            const nameIsFresh = !hasName || String(pos.name).trim().indexOf('Позиция N') === 0;
            if (nameIsFresh && !hasQty && !hasDelivery) return 'draft';
            return 'incomplete';
        },

        getDocStatus: function (doc) {
            const hasName = String(doc.name || '').trim().length > 0;
            if (hasName) return 'valid';
            if (!hasName && !doc.fileName && !doc.isRequired) return 'draft';
            return 'incomplete';
        },

        updateSelectedLot: function (field, value) {
            const id = this.selectedLotId;
            this.lotPositions = this.lotPositions.map(function (p) {
                if (p.id !== id) return p;
                const next = Object.assign({}, p);
                next[field] = value;
                return next;
            });
        },

        updateSelectedDoc: function (field, value) {
            const id = this.selectedDocId;
            this.lotDocuments = this.lotDocuments.map(function (d) {
                if (d.id !== id) return d;
                const next = Object.assign({}, d);
                next[field] = value;
                return next;
            });
        },

        updateLotPosById: function (id, patch) {
            this.lotPositions = this.lotPositions.map(function (p) {
                if (p.id !== id) return p;
                return Object.assign({}, p, patch);
            });
        },

        updateLotDocById: function (id, patch) {
            this.lotDocuments = this.lotDocuments.map(function (d) {
                if (d.id !== id) return d;
                return Object.assign({}, d, patch);
            });
        },

        onLotsFieldChange: function ($el) {
            const field = String($el.data('field') || '');
            if (!field) return;
            let value = $el.attr('type') === 'checkbox' ? $el.is(':checked') : $el.val();
            if (field === 'quantity') {
                const digits = String(value || '').replace(/\D/g, '');
                value = (!digits || digits === '0') ? '' : String(parseInt(digits, 10));
                $el.val(value);
            }
            if ($el.hasClass('js-doc-field')) {
                this.updateSelectedDoc(field, value);
                if (field === 'name' || field === 'description' || field === 'isRequired') {
                    this.renderDocsList();
                }
                if (field === 'name') {
                    this.renderDocEditorHead();
                }
                if (field === 'description') {
                    $el.closest('.tender-lots-textarea').find('.js-lots-char-counter')
                        .text(String(value || '').length + '/200');
                }
            } else {
                this.updateSelectedLot(field, value);
                if (field === 'name' || field === 'quantity' || field === 'unit'
                    || field === 'deliveryPlace'
                    || field === 'maxPriceNoVat' || field === 'vatRate') {
                    this.renderLotsList();
                }
                if (field === 'name') {
                    this.renderLotEditorHead();
                }
                if (field === 'comment') {
                    $el.closest('.tender-lots-textarea').find('.js-lots-char-counter')
                        .text(String(value || '').length + '/200');
                }
                if (field === 'maxPriceNoVat' || field === 'quantity' || field === 'vatRate') {
                    if (this.budgetActionsHidden) this.budgetActionsHidden = false;
                    this.renderLotsBudget();
                }
                if (this.publishIssuesByStep && this.publishIssuesByStep.lots) {
                    this.refreshPublishIssueForStep('lots');
                }
            }
        },

        addLotPosition: function () {
            const id = this.nextLotId('pos_');
            const n = this.lotPositions.length + 1;
            const isPrequal = this.isLotsPrequal();
            this.lotPositions.push({
                id: id,
                name: isPrequal ? ('Позиция №' + n) : ('Позиция N' + n),
                quantity: '',
                unit: 'шт.',
                maxPriceNoVat: '0',
                vatRate: '0%',
                deliveryPlace: '',
                comment: '',
                fileName: '',
                fileSize: 0,
                file_link_id: null
            });
            this.selectedLotId = id;
            this.lotsActiveTab = 'lots';
            this.syncLotsTabUi();
            this.renderLotsList();
            this.renderLotEditor();
            this.renderLotsBudget();
        },

        duplicateLotPosition: function (id) {
            const src = this.lotPositions.find(function (p) { return p.id === id; });
            if (!src) return;
            const newId = this.nextLotId('pos_');
            this.lotPositions.push(Object.assign({}, src, {
                id: newId,
                dbId: 0,
                name: (src.name || '') + ' (Копия)'
            }));
            this.selectedLotId = newId;
            this.renderLotsList();
            this.renderLotEditor();
            this.renderLotsBudget();
        },

        deleteLotPosition: function (id) {
            if (this.lotPositions.length <= 1) return;
            this.lotPositions = this.lotPositions.filter(function (p) { return p.id !== id; });
            if (this.selectedLotId === id) {
                this.selectedLotId = this.lotPositions[0] ? this.lotPositions[0].id : '';
            }
            this.renderLotsList();
            this.renderLotEditor();
            this.renderLotsBudget();
            this.syncLotsEditorEmpty();
        },

        addLotDocument: function () {
            const id = this.nextLotId('doc_');
            this.lotDocuments.push({
                id: id,
                name: '',
                description: '',
                fileName: '',
                fileSize: 0,
                file_link_id: null,
                isRequired: false
            });
            this.selectedDocId = id;
            this.lotsActiveTab = 'documents';
            this.syncLotsTabUi();
            this.renderDocsList();
            this.renderDocEditor();
        },

        deleteLotDocument: function (id) {
            this.lotDocuments = this.lotDocuments.filter(function (d) { return d.id !== id; });
            if (this.selectedDocId === id) {
                this.selectedDocId = this.lotDocuments[0] ? this.lotDocuments[0].id : '';
            }
            this.renderDocsList();
            this.renderDocEditor();
            this.syncLotsEditorEmpty();
        },

        lotStatusIconHtml: function (status) {
            if (status === 'valid') {
                return '<div class="tender-lots-item__status" title="Заполнено">'
                    + '<svg viewBox="0 0 19.5 19.5" fill="none"><path d="M9.75 0C15.1348 0 19.5 4.36522 19.5 9.75C19.5 15.1348 15.1348 19.5 9.75 19.5C4.36522 19.5 0 15.1348 0 9.75C0 4.36522 4.36522 0 9.75 0ZM13.1855 6.88965C12.8485 6.64913 12.3803 6.72749 12.1396 7.06445L8.9043 11.5938L7.28027 9.96973C6.98738 9.67683 6.51262 9.67683 6.21973 9.96973C5.92683 10.2626 5.92683 10.7374 6.21973 11.0303L8.46973 13.2803C8.62555 13.4361 8.84191 13.5152 9.06152 13.4971C9.28124 13.4789 9.48221 13.3649 9.61035 13.1855L13.3604 7.93555C13.6009 7.59851 13.5225 7.13034 13.1855 6.88965Z" fill="var(--success-dark)"/></svg>'
                    + '</div>';
            }
            if (status === 'incomplete') {
                return '<div class="tender-lots-item__status" title="Не заполнено">'
                    + '<svg viewBox="0 0 19.5 19.5" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 9.75C0 4.36522 4.36522 0 9.75 0C15.1348 0 19.5 4.36522 19.5 9.75C19.5 15.1348 15.1348 19.5 9.75 19.5C4.36522 19.5 0 15.1348 0 9.75ZM9.75 6C10.1642 6 10.5 6.33579 10.5 6.75V10.5C10.5 10.9142 10.1642 11.25 9.75 11.25C9.33579 11.25 9 10.9142 9 10.5V6.75C9 6.33579 9.33579 6 9.75 6ZM9.75 14.25C10.1642 14.25 10.5 13.9142 10.5 13.5C10.5 13.0858 10.1642 12.75 9.75 12.75C9.33579 12.75 9 13.0858 9 13.5C9 13.9142 9.33579 14.25 9.75 14.25Z" fill="var(--orange-600)"/></svg>'
                    + '</div>';
            }
            return '<div class="tender-lots-item__status tender-lots-item__status--draft" title="Черновик"></div>';
        },

        formatLotParams: function (pos) {
            const parts = [];
            const qty = Number(pos.quantity);
            if (qty > 0 && pos.unit) parts.push(qty + '\u00a0' + pos.unit);
            if (!this.isLotsPrequal()) {
                const price = Number(pos.maxPriceNoVat);
                if (price > 0) {
                    const total = qty > 0 ? qty * price : price;
                    parts.push(total.toLocaleString('ru-RU', { maximumFractionDigits: 0 }) + '\u00a0₽');
                }
                if (pos.vatRate) parts.push('НДС\u00a0' + pos.vatRate);
            }
            if (!parts.length) return '';
            return parts.map(function (part, i) {
                return (i ? '<span class="tender-lots-item__dot"></span>' : '')
                    + '<span class="tender-lots-item__param">' + $('<div>').text(part).html() + '</span>';
            }).join('');
        },

        renderLotsList: function () {
            const self = this;
            const $list = $(this.root).find('.js-lots-list');
            $list.empty();
            const isPrequal = this.isLotsPrequal();
            this.lotPositions.forEach(function (pos, idx) {
                const selected = pos.id === self.selectedLotId;
                const status = self.getLotStatus(pos);
                const title = pos.name || (isPrequal ? ('Позиция №' + (idx + 1)) : ('Позиция ' + (idx + 1)));
                const params = self.formatLotParams(pos);
                const $item = $(
                    '<div class="tender-lots-item js-lot-item' + (selected ? ' is-selected' : '') + '" data-id="' + pos.id + '">'
                    + '<div class="tender-lots-item__border" aria-hidden="true"></div>'
                    + '<div class="tender-lots-item__row">'
                    + '<div class="tender-lots-item__num">' + (idx + 1) + '</div>'
                    + '<div class="tender-lots-item__main">'
                    + '<div class="tender-lots-item__title"></div>'
                    + (params ? '<div class="tender-lots-item__params">' + params + '</div>' : '')
                    + '</div>'
                    + '<div class="tender-lots-item__right">'
                    + '<div class="tender-lots-item__actions">'
                    + '<button type="button" class="button secondary small icon-only js-lot-dup" title="Дублировать" aria-label="Дублировать позицию">'
                    + '<svg viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 3C0 1.34315 1.34315 0 3 0H11.25C12.9069 0 14.25 1.34315 14.25 3V4.5H15C16.6569 4.5 18 5.84315 18 7.5V15C18 16.6569 16.6569 18 15 18H7.5C5.84315 18 4.5 16.6569 4.5 15V14.25H3C1.34315 14.25 0 12.9069 0 11.25V3ZM4.5 12.75V7.5C4.5 5.84315 5.84315 4.5 7.5 4.5H12.75V3C12.75 2.17157 12.0784 1.5 11.25 1.5H3C2.17157 1.5 1.5 2.17157 1.5 3V11.25C1.5 12.0784 2.17157 12.75 3 12.75H4.5ZM7.5 6C6.67157 6 6 6.67157 6 7.5V15C6 15.8284 6.67157 16.5 7.5 16.5H15C15.8284 16.5 16.5 15.8284 16.5 15V7.5C16.5 6.67157 15.8284 6 15 6H7.5Z" fill="currentColor"/></svg>'
                    + '</button>'
                    + '<button type="button" class="button secondary small icon-only js-lot-del" title="Удалить" aria-label="Удалить позицию">'
                    + '<svg><use href="#icon-trash"></use></svg>'
                    + '</button>'
                    + '</div>'
                    + self.lotStatusIconHtml(status)
                    + '</div></div></div>'
                );
                $item.find('.tender-lots-item__title').text(title);
                $list.append($item);
            });
        },

        renderDocsList: function () {
            const self = this;
            const $list = $(this.root).find('.js-docs-list');
            $list.empty();
            const isPrequal = this.isLotsPrequal();
            this.lotDocuments.forEach(function (doc, idx) {
                const selected = doc.id === self.selectedDocId;
                const status = self.getDocStatus(doc);
                const title = doc.name || (isPrequal ? ('Документ №' + (idx + 1)) : ('Документ ' + (idx + 1)));
                let statusHtml = '';
                if (status === 'valid') {
                    statusHtml = '<div class="tender-lots-doc-status tender-lots-doc-status--valid"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6.2 4.8 8.5 9.5 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>';
                } else if (status === 'incomplete') {
                    statusHtml = '<div class="tender-lots-doc-status tender-lots-doc-status--incomplete"><span class="tender-lots-doc-status__dot"></span></div>';
                }
                const $item = $(
                    '<div class="tender-lots-item tender-lots-item--doc js-lot-item js-doc-item' + (selected ? ' is-selected' : '') + '" data-id="' + doc.id + '">'
                    + '<div class="tender-lots-item__border" aria-hidden="true"></div>'
                    + '<div class="tender-lots-item__row">'
                    + '<div class="tender-lots-item__num">' + (idx + 1) + '</div>'
                    + '<div class="tender-lots-item__main">'
                    + '<div class="flex-row" style="display:flex;align-items:center;gap:8px;min-width:0;width:100%;">'
                    + '<div class="tender-lots-item__title"></div>'
                    + (doc.isRequired ? '<span class="badge badge--shared badge--small error">Обяз.</span>' : '')
                    + '</div>'
                    + (doc.description ? '<div class="tender-lots-item__desc"></div>' : '')
                    + '</div>'
                    + statusHtml
                    + '<button type="button" class="button secondary small icon-only js-doc-del" title="Удалить" aria-label="Удалить документ"><svg><use href="#icon-trash"></use></svg></button>'
                    + '</div></div>'
                );
                $item.find('.tender-lots-item__title').text(title);
                if (doc.description) $item.find('.tender-lots-item__desc').text(doc.description);
                $list.append($item);
            });
        },

        renderLotEditorHead: function () {
            const pos = this.getSelectedLot();
            if (!pos) return;
            const idx = this.lotPositions.findIndex(function (p) { return p.id === pos.id; });
            const title = pos.name || ('Позиция №' + (idx + 1));
            $(this.root).find('.js-lot-editor-wrap .js-lot-editor-title').text(title);
        },

        renderDocEditorHead: function () {
            const doc = this.getSelectedDoc();
            if (!doc) return;
            const idx = this.lotDocuments.findIndex(function (d) { return d.id === doc.id; });
            const isPrequal = this.isLotsPrequal();
            const title = String(doc.name || '').trim()
                ? doc.name
                : (isPrequal ? ('Документ №' + (idx + 1)) : 'Документ');
            $(this.root).find('.js-doc-editor-wrap .js-doc-editor-title').text(title);
        },

        lotsInputHtml: function (opts) {
            opts = opts || {};
            return '<div class="tender-lots-input' + (opts.error ? ' is-error' : '') + '">'
                + '<div class="tender-lots-input__border" aria-hidden="true"></div>'
                + '<div class="tender-lots-input__inner">'
                + (opts.select
                    ? '<select class="js-lot-field" data-field="' + opts.field + '">' + opts.options + '</select>'
                      + '<svg class="tender-lots-input__chevron" viewBox="0 0 20 20" fill="none"><path d="M5.75 8.25L10 12.5L14.25 8.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></svg>'
                    : '<input type="text" class="' + (opts.doc ? 'js-doc-field' : 'js-lot-field') + '" data-field="' + opts.field + '" value="" placeholder="' + (opts.placeholder || '') + '">'
                      + (opts.endIcon || ''))
                + '</div></div>';
        },

        lotsUploadHtml: function (kind, fileName, fileSize) {
            let fileHtml = '';
            if (fileName) {
                const ext = (String(fileName).split('.').pop() || 'FILE').toUpperCase();
                const kb = Math.round((fileSize || 0) / 1024);
                fileHtml = '<div class="tender-lots-upload__file">'
                    + '<div class="tender-lots-upload__file-main">'
                    + '<div class="tender-lots-upload__ext">' + $('<div>').text(ext).html() + '</div>'
                    + '<div><div class="tender-lots-upload__name"></div><div class="tender-lots-upload__size">' + kb + ' KB</div></div>'
                    + '</div>'
                    + '<button type="button" class="tender-lots-upload__remove js-lots-file-remove" data-kind="' + kind + '" aria-label="Удалить">'
                    + '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>'
                    + '</button></div>';
            }
            return '<div class="tender-lots-upload">'
                + '<div class="tender-lots-upload__zone js-lots-upload-zone">'
                + '<div class="tender-lots-upload__inner">'
                + '<div class="tender-lots-upload__icon"><svg><use href="#icon-file-upload"></use></svg></div>'
                + '<div class="tender-lots-upload__text"><span class="tender-lots-upload__link">Нажмите сюда</span> или перетащите файл для загрузки файлов</div>'
                + '<div class="tender-lots-upload__hints"><span>Поддерживаемый формат: .word, PDF </span><span>Макс. размер файлов: 10 MB</span></div>'
                + '</div></div>'
                + fileHtml
                + '<input type="file" class="js-lots-file-input" data-kind="' + kind + '" accept=".doc,.docx,.pdf">'
                + '</div>';
        },

        renderLotEditor: function () {
            const $wrap = $(this.root).find('.js-lot-editor-wrap');
            const pos = this.getSelectedLot();
            this.syncLotsEditorEmpty();
            if (!pos) {
                $wrap.empty();
                return;
            }
            const isPrequal = this.isLotsPrequal();
            const idx = this.lotPositions.findIndex(function (p) { return p.id === pos.id; });
            const units = ['шт.', 'кг', 'м', 'л', 'компл.', 'уп.'];
            const vats = ['0%', '10%', '20%', 'Без НДС'];
            const unitOpts = units.map(function (u) {
                return '<option value="' + u + '"' + (pos.unit === u ? ' selected' : '') + '>' + u + '</option>';
            }).join('');
            const vatOpts = vats.map(function (v) {
                return '<option value="' + v + '"' + (pos.vatRate === v ? ' selected' : '') + '>' + v + '</option>';
            }).join('');

            const searchIcon = '<div class="tender-lots-input__icon"><svg><use href="#icon-search"></use></svg></div>';
            let priceRow = '';
            if (!isPrequal) {
                priceRow = '<div class="tender-lots-field tender-lots-field--price"><div class="tender-lots-field__label">Макс. цена без НДС</div>'
                    + this.lotsInputHtml({ field: 'maxPriceNoVat', placeholder: '0' }) + '</div>'
                    + '<div class="tender-lots-field tender-lots-field--vat"><div class="tender-lots-field__label">НДС</div>'
                    + this.lotsInputHtml({ field: 'vatRate', select: true, options: vatOpts }) + '</div>';
            }

            const html = '<div class="tender-lots-editor">'
                + '<div class="tender-lots-editor__head"><div class="tender-lots-editor__head-inner">'
                + '<div class="tender-lots-editor__title js-lot-editor-title"></div>'
                + '<div class="tender-lots-editor__actions">'
                + '<button type="button" class="button secondary small icon-only js-lot-dup" title="Дублировать" aria-label="Дублировать позицию" data-id="' + pos.id + '">'
                + '<svg viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 2.5C0 1.11929 1.11929 0 2.5 0H9.375C10.7557 0 11.875 1.11929 11.875 2.5V3.75H12.5C13.8807 3.75 15 4.86929 15 6.25V12.5C15 13.8807 13.8807 15 12.5 15H6.25C4.86929 15 3.75 13.8807 3.75 12.5V11.875H2.5C1.11929 11.875 0 10.7557 0 9.375V2.5ZM3.75 10.625V6.25C3.75 4.86929 4.86929 3.75 6.25 3.75H10.625V2.5C10.625 1.80964 10.0654 1.25 9.375 1.25H2.5C1.80964 1.25 1.25 1.80964 1.25 2.5V9.375C1.25 10.0654 1.80964 10.625 2.5 10.625H3.75ZM6.25 5C5.55964 5 5 5.55964 5 6.25V12.5C5 13.1904 5.55964 13.75 6.25 13.75H12.5C13.1904 13.75 13.75 13.1904 13.75 12.5V6.25C13.75 5.55964 13.1904 5 12.5 5H6.25Z" fill="currentColor"/></svg>'
                + '</button>'
                + '<button type="button" class="button secondary small icon-only js-lot-del" title="Удалить" aria-label="Удалить позицию" data-id="' + pos.id + '"><svg><use href="#icon-trash"></use></svg></button>'
                + '</div></div></div>'
                + '<div class="tender-lots-editor__body"><div class="tender-lots-editor__body-inner">'
                + '<div class="tender-lots-field"><div class="tender-lots-field__label">Наименование позиции</div>'
                + this.lotsInputHtml({ field: 'name', placeholder: 'Введите наименование позиции' }) + '</div>'
                + '<div class="tender-lots-field-row">'
                + '<div class="tender-lots-field"><div class="tender-lots-field__label">Количество</div>'
                + this.lotsInputHtml({ field: 'quantity', placeholder: isPrequal ? '0' : '1' }) + '</div>'
                + '<div class="tender-lots-field"><div class="tender-lots-field__label">Ед. измерения</div>'
                + this.lotsInputHtml({ field: 'unit', select: true, options: unitOpts }) + '</div>'
                + priceRow
                + '</div>'
                + (isPrequal ? '' : (
                    '<div class="tender-lots-field"><div class="tender-lots-field__label">Место поставки</div>'
                    + this.lotsInputHtml({ field: 'deliveryPlace', placeholder: 'Введите или выберите из списка', endIcon: searchIcon }) + '</div>'
                    + '<button type="button" class="button outline small js-lots-add-field">'
                    + '<svg viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 3.125C10.3452 3.125 10.625 3.40482 10.625 3.75V9.375H16.25C16.5952 9.375 16.875 9.65482 16.875 10C16.875 10.3452 16.5952 10.625 16.25 10.625H10.625V16.25C10.625 16.5952 10.3452 16.875 10 16.875C9.65482 16.875 9.375 16.5952 9.375 16.25V10.625H3.75C3.40482 10.625 3.125 10.3452 3.125 10C3.125 9.65482 3.40482 9.375 3.75 9.375H9.375V3.75C9.375 3.40482 9.65482 3.125 10 3.125Z" fill="currentColor"/></svg>'
                    + '<span>Добавить поле</span></button>'
                ))
                + '<div class="tender-lots-field"><div class="tender-lots-field__section">Добавить закупочную документацию</div>'
                + this.lotsUploadHtml('lot', pos.fileName, pos.fileSize) + '</div>'
                + '<div class="tender-lots-field"><div class="tender-lots-field__section">Комментарий</div>'
                + '<div class="tender-lots-textarea"><div class="tender-lots-textarea__border" aria-hidden="true"></div>'
                + '<div class="tender-lots-textarea__inner">'
                + '<textarea class="js-lot-field" data-field="comment" maxlength="200" placeholder="Введите"></textarea>'
                + '<div class="tender-lots-textarea__counter js-lots-char-counter">' + String(pos.comment || '').length + '/200</div>'
                + '</div></div></div>'
                + '</div></div></div>';

            $wrap.html(html);
            $wrap.find('.js-lot-editor-title').text(pos.name || ('Позиция №' + (idx + 1)));
            $wrap.find('[data-field="name"]').val(pos.name || '');
            $wrap.find('[data-field="quantity"]').val(pos.quantity || '');
            $wrap.find('[data-field="maxPriceNoVat"]').val(pos.maxPriceNoVat === '0' ? '' : (pos.maxPriceNoVat || ''));
            $wrap.find('[data-field="deliveryPlace"]').val(pos.deliveryPlace || '');
            $wrap.find('[data-field="comment"]').val(pos.comment || '');
            if (pos.fileName) $wrap.find('.tender-lots-upload__name').text(pos.fileName);
        },

        renderDocEditor: function () {
            const $wrap = $(this.root).find('.js-doc-editor-wrap');
            const doc = this.getSelectedDoc();
            this.syncLotsEditorEmpty();
            if (!doc) {
                $wrap.empty();
                return;
            }
            const isPrequal = this.isLotsPrequal();
            const idx = this.lotDocuments.findIndex(function (d) { return d.id === doc.id; });
            const title = String(doc.name || '').trim()
                ? doc.name
                : (isPrequal ? ('Документ №' + (idx + 1)) : 'Документ');

            const html = '<div class="tender-lots-editor">'
                + '<div class="tender-lots-editor__head"><div class="tender-lots-editor__head-inner">'
                + '<div class="tender-lots-editor__title js-doc-editor-title"></div>'
                + '<div class="tender-lots-editor__actions">'
                + '<button type="button" class="button secondary small icon-only js-doc-del" title="Удалить" aria-label="Удалить документ"><svg><use href="#icon-trash"></use></svg></button>'
                + '</div></div></div>'
                + '<div class="tender-lots-editor__body"><div class="tender-lots-editor__body-inner tender-lots-editor__body-inner--doc">'
                + '<div class="tender-lots-field"><div class="tender-lots-field__label">Название документа'
                + (isPrequal ? '<span class="tender-lots-field__req">*</span>' : '') + '</div>'
                + this.lotsInputHtml({ field: 'name', placeholder: isPrequal ? 'Введите название документа' : 'Введите название', doc: true }) + '</div>'
                + '<div class="tender-lots-field"><div class="tender-lots-field__section">Описание</div>'
                + '<div class="tender-lots-textarea"><div class="tender-lots-textarea__border" aria-hidden="true"></div>'
                + '<div class="tender-lots-textarea__inner">'
                + '<textarea class="js-doc-field" data-field="description" maxlength="200" placeholder="Введите"></textarea>'
                + '<div class="tender-lots-textarea__counter js-lots-char-counter">' + String(doc.description || '').length + '/200</div>'
                + '</div></div></div>'
                + (isPrequal ? '' : '<div class="tender-lots-editor-divider" aria-hidden="true"></div>')
                + '<div class="tender-lots-field"><div class="tender-lots-field__section">'
                + (isPrequal ? 'Добавить закупочную документацию' : 'Загрузить шаблон документа') + '</div>'
                + this.lotsUploadHtml('doc', doc.fileName, doc.fileSize) + '</div>'
                + '<div class="tender-lots-check js-doc-required' + (doc.isRequired ? ' is-checked' : '') + '">'
                + '<div class="tender-lots-check__box">'
                + (doc.isRequired ? '<svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7.2 5.2 9.9 11.5 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>' : '')
                + '</div>'
                + '<span class="tender-lots-check__label">'
                + (isPrequal ? 'Отметить обязательным' : 'Отметить загрузку обязательным')
                + '</span></div>'
                + '</div></div></div>';

            $wrap.html(html);
            $wrap.find('.js-doc-editor-title').text(title);
            $wrap.find('[data-field="name"]').val(doc.name || '');
            $wrap.find('[data-field="description"]').val(doc.description || '');
            if (doc.fileName) $wrap.find('.tender-lots-upload__name').text(doc.fileName);
        },

        parseVatPercent: function (vatRate) {
            if (!vatRate || vatRate === 'Без НДС') return 0;
            const n = parseFloat(vatRate);
            return isNaN(n) ? 0 : n;
        },

        computeLotsBudget: function () {
            const self = this;
            let sumPrices = 0;
            let sumVat = 0;
            this.lotPositions.forEach(function (pos) {
                const qty = Number(pos.quantity) || 0;
                const price = Number(pos.maxPriceNoVat) || 0;
                const lineTotal = qty * price;
                const vatPct = self.parseVatPercent(pos.vatRate);
                sumPrices += lineTotal;
                sumVat += lineTotal * (vatPct / 100);
            });
            const nmc = Number(this.lotNmc) || 0;
            const diff = nmc - sumPrices;
            const usagePercent = nmc > 0 ? (sumPrices / nmc) * 100 : null;
            return { sumPrices: sumPrices, sumVat: sumVat, nmc: nmc, diff: diff, usagePercent: usagePercent };
        },

        formatCurrency: function (n) {
            return Number(n || 0).toLocaleString('ru-RU', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' ₽';
        },

        renderLotsBudget: function () {
            const $root = $(this.root);
            const summary = this.computeLotsBudget();
            const isSynced = Math.abs(summary.diff) < 0.01 && summary.nmc > 0 && summary.sumPrices > 0;
            const hasAnyPrice = this.lotPositions.some(function (p) { return Number(p.maxPriceNoVat) > 0; });
            const showButtons = this.lotPositions.length > 0 && hasAnyPrice && !this.budgetActionsHidden;

            $root.find('.js-budget-sum-prices').text(this.formatCurrency(summary.sumPrices))
                .toggleClass('is-synced', isSynced);
            $root.find('.js-budget-sum-vat').text(this.formatCurrency(summary.sumVat));
            $root.find('.js-budget-nmc').text(this.formatCurrency(summary.nmc))
                .toggleClass('is-synced', isSynced);
            $root.find('.js-budget-lot-vat').text(this.lotVatRate || '—');
            $root.find('.js-budget-diff').text(this.formatCurrency(summary.diff))
                .toggleClass('is-synced', isSynced);
            $root.find('.js-budget-ratio').text(
                summary.usagePercent !== null ? summary.usagePercent.toFixed(0) + '%' : '—'
            ).toggleClass('is-synced', isSynced);
            $root.find('.js-budget-synced-msg').toggle(isSynced);
            $root.find('.js-budget-actions').toggle(showButtons);
            this.syncLotsBudgetVisibility();
        },

        enterLotsPreview: function () {
            this.lotsPreviewMode = true;
            const $root = $(this.root);
            $root.find('.js-lots-edit').hide();
            $root.find('.js-lots-preview-view').show();
            this.renderLotsPreview();
        },

        exitLotsPreview: function (targetLotId) {
            this.lotsPreviewMode = false;
            if (targetLotId) {
                this.selectedLotId = targetLotId;
                this.lotsActiveTab = 'lots';
            }
            const $root = $(this.root);
            $root.find('.js-lots-preview-view').hide();
            $root.find('.js-lots-edit').show();
            this.syncLotsTabUi();
            this.renderLotsList();
            this.renderLotEditor();
            this.renderLotsBudget();
        },

        renderLotsPreview: function () {
            const self = this;
            const $root = $(this.root);
            const isPrequal = this.isLotsPrequal();
            $root.find('.js-preview-pos-count').text(String(this.lotPositions.length));
            $root.find('.js-preview-doc-count').text(String(this.lotDocuments.length));
            $root.find('.js-preview-docs-title').text(
                isPrequal ? 'Запрашиваемые документы' : 'Требуемые документы'
            );

            const $pos = $root.find('.js-preview-pos-table');
            if (!this.lotPositions.length) {
                $pos.html(
                    '<div class="tender-lots-preview-empty">'
                    + '<div class="tender-lots-preview-empty__icon"><svg width="24" height="24"><use href="#icon-document-text"></use></svg></div>'
                    + '<div class="tender-lots-preview-empty__text">Нет добавленных позиций</div></div>'
                );
            } else {
                let rows = '';
                this.lotPositions.forEach(function (pos, idx) {
                    const price = pos.maxPriceNoVat && pos.maxPriceNoVat !== '0'
                        ? Number(pos.maxPriceNoVat).toLocaleString('ru-RU') + ' ₽'
                        : '—';
                    rows += '<tr class="js-preview-pos-row" data-id="' + pos.id + '">'
                        + '<td class="is-center"><span class="is-semibold">' + (idx + 1) + '</span></td>'
                        + '<td><span class="is-medium">' + $('<div>').text(pos.name || '—').html() + '</span></td>'
                        + '<td class="is-center">' + $('<div>').text(pos.quantity || '—').html() + '</td>'
                        + '<td class="is-center">' + $('<div>').text(pos.unit || '—').html() + '</td>'
                        + (isPrequal
                            ? '<td><span class="' + (pos.comment ? '' : 'is-muted') + '">' + $('<div>').text(pos.comment || '—').html() + '</span></td>'
                            : '<td class="is-center">' + price + '</td>'
                              + '<td class="is-center">' + $('<div>').text(pos.vatRate || '—').html() + '</td>'
                              + '<td>' + $('<div>').text(pos.deliveryPlace || '—').html() + '</td>')
                        + '<td class="is-center">' + (pos.fileName
                            ? '<span class="is-medium" style="color:var(--primary-900)">1</span>'
                            : '<span class="is-muted">—</span>') + '</td>'
                        + '<td class="is-center"><button type="button" class="tender-lots-preview-edit-btn" title="Редактировать"><svg width="16" height="16"><use href="#icon-pencil"></use></svg></button></td>'
                        + '</tr>';
                });
                $pos.html(
                    '<div class="tender-lots-preview-table-wrap"><table class="tender-lots-preview-table' + (isPrequal ? ' is-prequal' : '') + '">'
                    + '<thead><tr>'
                    + '<th class="is-center">№</th><th>Наименование</th><th class="is-center">Количество</th><th class="is-center">Ед.</th>'
                    + (isPrequal
                        ? '<th>Комментарий</th>'
                        : '<th class="is-center">Макс. цена без НДС</th><th class="is-center">НДС</th><th>Место поставки</th>')
                    + '<th class="is-center">Док.</th><th class="is-center"> </th>'
                    + '</tr></thead><tbody>' + rows + '</tbody></table></div>'
                );
            }

            const $docs = $root.find('.js-preview-doc-table');
            if (!this.lotDocuments.length) {
                $docs.html(
                    '<div class="tender-lots-preview-empty">'
                    + '<div class="tender-lots-preview-empty__icon"><svg width="24" height="24"><use href="#icon-document-text"></use></svg></div>'
                    + '<div class="tender-lots-preview-empty__text">Документы не требуются</div></div>'
                );
            } else {
                let docRows = '';
                this.lotDocuments.forEach(function (doc, idx) {
                    docRows += '<tr>'
                        + '<td class="is-center"><span class="is-semibold">' + (idx + 1) + '</span></td>'
                        + '<td><span class="is-medium">' + $('<div>').text(doc.name || '—').html() + '</span></td>'
                        + '<td><span class="' + (doc.description ? '' : 'is-muted') + '">' + $('<div>').text(doc.description || '—').html() + '</span></td>'
                        + '<td class="is-center">' + (doc.fileName
                            ? '<span class="is-medium" style="color:var(--success-dark)">Прикреплён</span>'
                            : '<span class="is-muted">Нет</span>') + '</td>'
                        + '<td class="is-center">' + (doc.isRequired
                            ? '<span class="badge badge--shared badge--small error">Да</span>'
                            : '<span class="is-muted">Нет</span>') + '</td>'
                        + '</tr>';
                });
                $docs.html(
                    '<div class="tender-lots-preview-table-wrap"><table class="tender-lots-preview-table" style="min-width:640px">'
                    + '<thead><tr>'
                    + '<th class="is-center">№</th><th>Наименование документа</th><th>Описание</th>'
                    + '<th class="is-center">' + (isPrequal ? 'Файл' : 'Шаблон') + '</th>'
                    + '<th class="is-center">Обязательный</th>'
                    + '</tr></thead><tbody>' + docRows + '</tbody></table></div>'
                );
            }
        },

        syncMinStepUi: function () {
            const $root = $(this.root);
            const on = $root.find('.js-field-min-step').is(':checked') && $root.find('.js-field-auto-extend-change').is(':checked');
            $root.find('.js-min-step-radios').toggleClass('visible', on);
        },

        splitDatetime: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) {
                return { date: '', time: '' };
            }
            const m = raw.match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2}))?/);
            if (!m) {
                return { date: '', time: '' };
            }
            return {
                date: m[3] + '.' + m[2] + '.' + m[1],
                time: (m[4] && m[5]) ? (m[4] + ':' + m[5]) : ''
            };
        },

        composeDatetime: function (dateStr, timeStr) {
            const rawDate = $.trim(dateStr || '');
            if (!rawDate) return '';
            let y = '', m = '', d = '';
            if (/^\d{2}\.\d{2}\.\d{4}$/.test(rawDate)) {
                const parts = rawDate.split('.');
                d = parts[0];
                m = parts[1];
                y = parts[2];
            } else if (/^\d{4}[\/-]\d{2}[\/-]\d{2}$/.test(rawDate)) {
                const parts = rawDate.split(/[\/-]/);
                y = parts[0];
                m = parts[1];
                d = parts[2];
            } else {
                return '';
            }
            let time = $.trim(timeStr || '') || '00:00';
            if (/^\d{1,2}:\d{2}$/.test(time)) {
                const tp = time.split(':');
                time = String(tp[0]).padStart(2, '0') + ':' + String(tp[1]).padStart(2, '0') + ':00';
            } else if (/^\d{1,2}:\d{2}:\d{2}$/.test(time)) {
                const tp = time.split(':');
                time = String(tp[0]).padStart(2, '0') + ':' + tp[1] + ':' + tp[2];
            } else {
                time = '00:00:00';
            }
            return y + '-' + m + '-' + d + ' ' + time;
        },

        criterionTypeLabel: function (type) {
            const map = { text: 'Текст', date: 'Дата', file: 'Файл', confirmation: 'Подтверждение' };
            return map[type] || 'Текст';
        },

        renderExtraInfoFields: function () {
            const $list = $(this.root).find('.js-extra-info-list');
            const self = this;
            $list.empty();
            this.extraInfoFields.forEach(function (row) {
                const $item = $(`
                    <div class="add-info-extra-row" data-id="${row.id}">
                        <div class="input-box">
                            <input type="text" class="js-extra-info-input" placeholder="Введите информацию для поставщиков" value="">
                        </div>
                        <button type="button" class="add-info-extra-remove js-extra-info-remove" aria-label="Удалить поле">
                            <svg><use href="#icon-x-mark"></use></svg>
                        </button>
                    </div>
                `);
                $item.find('.js-extra-info-input').val(row.value || '');
                $list.append($item);
            });
        },

        renderExtraApprovers: function () {
            const $list = $(this.root).find('.js-extra-approver-list');
            $list.empty();
            this.extraApprovers.forEach(function (row) {
                const $item = $(`
                    <div class="add-info-extra-row" data-id="${row.id}">
                        <div class="input-box input-box--search">
                            <input type="text" class="js-extra-approver-input" placeholder="Добавить утверждающего" value="">
                            <svg><use href="#icon-search"></use></svg>
                        </div>
                        <button type="button" class="add-info-extra-remove js-extra-approver-remove" aria-label="Удалить утверждающего">
                            <svg><use href="#icon-x-mark"></use></svg>
                        </button>
                    </div>
                `);
                $item.find('.js-extra-approver-input').val(row.value || '');
                $list.append($item);
            });
        },

        bindCriteriaDnD: function () {
            const self = this;
            if (this._criteriaDnDBound) {
                return;
            }
            this._criteriaDnDBound = true;

            const $root = $(this.root);
            const deadZone = 4;

            const clearDropIndicators = function () {
                $root.find('.js-criteria-item-wrap').removeClass('is-drop-above is-drop-below');
            };

            const computeOverIndex = function (clientY) {
                const $items = $root.find('.js-criteria-item-wrap');
                if (!$items.length) {
                    return 0;
                }
                let over = $items.length - 1;
                $items.each(function (index) {
                    const rect = this.getBoundingClientRect();
                    const midY = rect.top + rect.height / 2;
                    if (clientY < midY) {
                        over = index;
                        return false;
                    }
                });
                return over;
            };

            const updateDropIndicators = function (drag, overIndex) {
                clearDropIndicators();
                if (!drag || !drag.didMove) {
                    return;
                }
                $root.find('.js-criteria-item-wrap').each(function (index) {
                    const id = String($(this).data('id') || '');
                    if (id === drag.draggingId) {
                        return;
                    }
                    if (overIndex === index && overIndex < drag.startIndex) {
                        $(this).addClass('is-drop-above');
                    }
                    if (overIndex === index && overIndex > drag.startIndex) {
                        $(this).addClass('is-drop-below');
                    }
                });
            };

            $root.on('pointerdown', '.js-criterion-drag', function (e) {
                const $wrap = $(this).closest('.js-criteria-item-wrap');
                const id = String($wrap.data('id') || '');
                const startIndex = $root.find('.js-criteria-item-wrap').index($wrap);
                e.preventDefault();
                this.setPointerCapture(e.originalEvent.pointerId);
                self.criteriaDrag = {
                    draggingId: id,
                    startIndex: startIndex,
                    overIndex: startIndex,
                    startY: e.clientY,
                    didMove: false
                };
            });

            $root.on('pointermove', '.js-criteria-list', function (e) {
                const drag = self.criteriaDrag;
                if (!drag || !drag.draggingId) {
                    return;
                }
                const dy = e.clientY - drag.startY;
                if (!drag.didMove && Math.abs(dy) <= deadZone) {
                    return;
                }
                drag.didMove = true;
                drag.overIndex = computeOverIndex(e.clientY);
                const $item = $root.find('.js-criteria-item-wrap[data-id="' + drag.draggingId + '"] .js-criteria-item');
                $item.addClass('is-dragging').css('transform', 'translateY(' + dy + 'px)');
                updateDropIndicators(drag, drag.overIndex);
            });

            const finishDrag = function () {
                const drag = self.criteriaDrag;
                if (!drag || !drag.draggingId) {
                    return;
                }
                const $item = $root.find('.js-criteria-item-wrap[data-id="' + drag.draggingId + '"] .js-criteria-item');
                $item.removeClass('is-dragging').css('transform', '');
                clearDropIndicators();

                if (drag.didMove && drag.overIndex !== null && drag.overIndex !== drag.startIndex) {
                    const next = self.criteria.slice();
                    const moved = next.splice(drag.startIndex, 1)[0];
                    next.splice(drag.overIndex, 0, moved);
                    self.criteria = next;
                    self.renderCriteria();
                }

                self.criteriaDrag = null;
            };

            $root.on('pointerup pointercancel', '.js-criteria-list', function () {
                finishDrag();
            });
        },

        renderCriteria: function () {
            const self = this;
            const $list = $(this.root).find('.js-criteria-list');
            $list.empty();
            this.criteria.forEach(function (item) {
                const $wrap = $('<div class="js-criteria-item-wrap">').attr('data-id', item.id);
                const $row = $(`
                    <div class="js-criteria-item" data-id="${item.id}">
                        <div class="criteria-drag js-criterion-drag" aria-label="Перетащить">
                            <svg><use href="#icon-grip-vertical"></use></svg>
                        </div>
                        <div class="criteria-info">
                            <div class="criteria-name-row">
                                <span class="criteria-name"></span>
                            </div>
                            <div class="criteria-meta"></div>
                        </div>
                        <div class="criteria-actions">
                            <button type="button" class="criteria-action-btn js-criterion-edit" aria-label="Редактировать">
                                <svg><use href="#icon-pencil"></use></svg>
                            </button>
                            <button type="button" class="criteria-action-btn is-delete js-criterion-remove" aria-label="Удалить">
                                <svg><use href="#icon-trash"></use></svg>
                            </button>
                        </div>
                    </div>
                `);
                const $nameRow = $row.find('.criteria-name-row');
                $nameRow.find('.criteria-name').text(item.name || '');
                if (item.required) {
                    $nameRow.append($('<span class="badge badge--shared badge--small error">').text('Обязательное'));
                }
                const meta = self.criterionTypeLabel(item.type) + (item.description ? (' · ' + item.description) : '');
                $row.find('.criteria-meta').text(meta);
                $wrap.append($row);
                $list.append($wrap);
            });
            this.bindCriteriaDnD();
        },

        buildNpcPreviewHtml: function (state) {
            const name = $.trim(state.name || '') || 'Название критерия';
            const description = $.trim(state.description || '');
            const required = !!state.required;
            const type = state.type || 'text';
            const confirmationText = $.trim(state.confirmationText || '') || 'Принимаю';
            let fieldHtml = '';

            if (type === 'text') {
                fieldHtml = '<div class="npc-preview-input">Введите значение…</div>';
            } else if (type === 'date') {
                fieldHtml = '<div class="npc-preview-input is-date"><span>дд.мм.гггг</span><svg><use href="#icon-calendar"></use></svg></div>';
            } else if (type === 'file') {
                fieldHtml = `
                    <div class="npc-preview-file">
                        <div class="npc-preview-file-icon"><svg><use href="#icon-file-upload"></use></svg></div>
                        <div class="npc-preview-file-title">Перетащите файл сюда</div>
                        <div class="npc-preview-file-hint">или <span class="link">выберите с устройства</span></div>
                    </div>
                `;
            } else {
                fieldHtml = `
                    <div class="npc-preview-confirm">
                        <div class="npc-preview-confirm-box"></div>
                        <div class="npc-preview-confirm-text"></div>
                    </div>
                `;
            }

            const $preview = $(`
                <div class="npc-preview-header">
                    <svg><use href="#icon-eye"></use></svg>
                    <span>Предпросмотр для поставщика</span>
                </div>
                <div class="npc-preview-card">
                    <div class="npc-preview-accent"></div>
                    <div class="npc-preview-content">
                        <div>
                            <div class="npc-preview-title"></div>
                            <div class="npc-preview-desc" style="display:none;"></div>
                        </div>
                        <div class="npc-preview-field"></div>
                    </div>
                </div>
            `);

            $preview.find('.npc-preview-title').text(name);
            if (required) {
                $preview.find('.npc-preview-title').append('<span class="required-mark">*</span>');
            }
            if (description) {
                $preview.find('.npc-preview-desc').text(description).show();
            }
            $preview.find('.npc-preview-field').html(fieldHtml);
            if (type === 'confirmation') {
                $preview.find('.npc-preview-confirm-text').text(confirmationText);
            }
            return $preview;
        },

        updateNpcModalState: function ($container, state) {
            const self = this;
            const name = $.trim($container.find('.js-npc-name').val() || '');
            const description = $.trim($container.find('.js-npc-desc').val() || '');
            const type = String($container.find('.js-npc-type').val() || 'text');
            const required = $container.find('.js-npc-required').is(':checked');
            const confirmationText = $.trim($container.find('.js-npc-confirmation').val() || '');
            const next = {
                name: name,
                description: description,
                type: type,
                required: required,
                confirmationText: confirmationText
            };
            $container.find('.js-npc-confirmation-wrap').toggle(type === 'confirmation');
            $container.find('.js-npc-preview').empty().append(self.buildNpcPreviewHtml(next));
            const canSave = name.length > 0;
            $container.find('.js-npc-save').toggleClass('is-disabled', !canSave).prop('disabled', !canSave);
            return next;
        },

        openCriterionDialog: function (initial) {
            const self = this;
            const editing = initial || null;
            const dialog = new $.DialogManager({
                title: editing ? 'Редактировать критерий' : 'Добавить критерий',
                width: '780px',
                showConfirmButton: false,
                showCancelButton: false,
                content: `
                    <div class="npc-modal-body">
                        <div class="npc-modal-form">
                            <div>
                                <div class="npc-field-label">Название критерия <span class="required-mark">*</span></div>
                                <div class="input-box">
                                    <input type="text" class="input js-npc-name" placeholder="Например: Сертификат ISO 9001">
                                </div>
                            </div>
                            <div>
                                <div class="npc-field-label">Описание (опционально)</div>
                                <textarea class="input js-npc-desc" rows="3" placeholder="Подсказка для поставщика при заполнении" style="width:100%;resize:none;"></textarea>
                            </div>
                            <div>
                                <div class="npc-field-label">Тип ответа</div>
                                <input type="hidden" class="js-npc-type" value="text">
                                <div class="npc-type-buttons">
                                    <button type="button" class="npc-type-btn is-active" data-type="text">
                                        <svg><use href="#icon-document-text"></use></svg>
                                        <span>Текст</span>
                                    </button>
                                    <button type="button" class="npc-type-btn" data-type="date">
                                        <svg><use href="#icon-calendar"></use></svg>
                                        <span>Дата</span>
                                    </button>
                                    <button type="button" class="npc-type-btn" data-type="file">
                                        <svg><use href="#icon-file-upload"></use></svg>
                                        <span>Файл</span>
                                    </button>
                                    <button type="button" class="npc-type-btn" data-type="confirmation">
                                        <svg><use href="#icon-check"></use></svg>
                                        <span>Подтверждение</span>
                                    </button>
                                </div>
                            </div>
                            <label class="tender-creation-checkbox">
                                <input type="checkbox" class="js-npc-required">
                                <span class="checkbox-ui"></span>
                                <span class="checkbox-text">Обязательное поле</span>
                            </label>
                            <div class="js-npc-confirmation-wrap" style="display:none;">
                                <div class="npc-field-label">Текст подтверждения</div>
                                <div class="input-box">
                                    <input type="text" class="input js-npc-confirmation" placeholder="Например: Я подтверждаю, что...">
                                </div>
                            </div>
                        </div>
                        <div class="npc-modal-preview">
                            <div class="js-npc-preview"></div>
                        </div>
                    </div>
                    <div class="npc-modal-footer">
                        <button type="button" class="button secondary large modal-cancel">Отмена</button>
                        <button type="button" class="button primary large js-npc-save is-disabled" disabled>Добавить</button>
                    </div>
                `,
                onOpen: function ($container) {
                    const initialType = editing ? (editing.type || 'text') : 'text';
                    if (editing) {
                        $container.find('.js-npc-name').val(editing.name || '');
                        $container.find('.js-npc-desc').val(editing.description || '');
                        $container.find('.js-npc-type').val(initialType);
                        $container.find('.js-npc-required').prop('checked', !!editing.required);
                        $container.find('.js-npc-confirmation').val(editing.confirmationText || '');
                        $container.find('.js-npc-save').text('Сохранить');
                    }
                    $container.find('.npc-type-btn').removeClass('is-active')
                        .filter('[data-type="' + initialType + '"]').addClass('is-active');

                    const refresh = function () {
                        self.updateNpcModalState($container);
                    };

                    $container.on('input change', '.js-npc-name, .js-npc-desc, .js-npc-confirmation', refresh);
                    $container.on('change', '.js-npc-required', refresh);
                    $container.on('click', '.npc-type-btn', function () {
                        const type = String($(this).data('type') || 'text');
                        $container.find('.js-npc-type').val(type);
                        $container.find('.npc-type-btn').removeClass('is-active');
                        $(this).addClass('is-active');
                        refresh();
                    });

                    refresh();

                    $container.find('.js-npc-save').on('click', function () {
                        if ($(this).prop('disabled')) {
                            return;
                        }
                        const state = self.updateNpcModalState($container);
                        const next = {
                            id: editing && editing.id ? editing.id : ('npc_' + Date.now()),
                            serverId: editing && editing.serverId ? editing.serverId : 0,
                            name: state.name,
                            description: state.description,
                            type: state.type,
                            required: state.required,
                            confirmationText: state.type === 'confirmation' ? state.confirmationText : ''
                        };
                        if (editing) {
                            self.criteria = self.criteria.map(function (c) {
                                return String(c.id) === String(editing.id) ? next : c;
                            });
                        } else {
                            self.criteria.push(next);
                        }
                        self.renderCriteria();
                        dialog.close();
                    });
                }
            });
        },

        initInviteSelects: function () {
            $(this.root).find('.js-invite-select').each(function () {
                const $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $select.select2({
                    language: 'ru',
                    placeholder: $select.data('placeholder') || 'Выберите компании',
                    allowClear: true,
                    multiple: true,
                    ajax: {
                        url: '/api/buyer/company/select/',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,
                                supplier: 1,
                                page: params.page || 1
                            };
                        },
                        processResults: function (data) {
                            return data.data || { results: [] };
                        },
                        cache: true
                    }
                });
            });
        },

        syncBasicContinue: function () {
            /* Навигация свободная: «Продолжить» на basic не блокируем. */
            $(this.root).find('[data-step="basic"] .js-tender-step-next').prop('disabled', false);
        },

        $categoryTree: function () {
            return $(this.root).find('.js-classifier-tree[data-tab="common"]');
        },

        setCategoryTreeStatus: function (text) {
            this.$categoryTree().html(
                '<div class="creation-card-hint js-classifier-tree-status" style="margin:8px 0;"></div>'
            ).find('.js-classifier-tree-status').text(text);
        },

        loadCategoryTree: function (force) {
            const self = this;
            if (!force && this.categoryTreeRows && this.categoryTreeRows.length) {
                this.renderCategoryTree(this.categoryTreeRows);
                return this.categoryTreePromise || Promise.resolve(this.categoryTreeRows);
            }
            if (!force && this.categoryTreePromise) {
                return this.categoryTreePromise;
            }

            this.setCategoryTreeStatus('Загрузка классификатора…');
            this.categoryTreePromise = $.fRequest({
                url: '/api/buyer/category/tree/',
                method: 'POST',
                showMessages: false,
                data: {},
                onSuccess: function () { return false; }
            }).then(function (reply) {
                if (reply.error) {
                    self.setCategoryTreeStatus('Не удалось загрузить классификатор');
                    self.categoryTreePromise = null;
                    return [];
                }
                self.categoryTreeRows = Array.isArray(reply.categories) ? reply.categories : [];
                self.categoryClassifierType = parseInt(reply.classifier_type, 10) || 4;
                self.renderCategoryTree(self.categoryTreeRows);
                return self.categoryTreeRows;
            }).catch(function () {
                self.setCategoryTreeStatus('Не удалось загрузить классификатор');
                self.categoryTreePromise = null;
                return [];
            });

            return this.categoryTreePromise;
        },

        buildCategoryForest: function (rows) {
            const byId = {};
            const roots = [];
            (rows || []).forEach(function (row) {
                const id = parseInt(row.id, 10) || 0;
                if (!id) return;
                byId[id] = {
                    id: id,
                    parent_id: parseInt(row.parent_id, 10) || 0,
                    name: String(row.name || ''),
                    left_key: parseInt(row.left_key, 10) || 0,
                    right_key: parseInt(row.right_key, 10) || 0,
                    children: []
                };
            });
            Object.keys(byId).forEach(function (id) {
                const node = byId[id];
                if (byId[node.parent_id]) {
                    byId[node.parent_id].children.push(node);
                } else {
                    roots.push(node);
                }
            });
            return roots;
        },

        toggleCategoryExpand: function (id) {
            if (!id) return;
            if (!this.categoryExpandedIds) this.categoryExpandedIds = {};
            if (this.categoryExpandedIds[id]) delete this.categoryExpandedIds[id];
            else this.categoryExpandedIds[id] = true;

            const open = !!this.categoryExpandedIds[id];
            const $node = this.$categoryTree().find('.clf-node[data-id="' + id + '"]');
            $node.children('.clf-node__row').find('.js-clf-toggle')
                .toggleClass('is-expanded', open)
                .attr('aria-expanded', open ? 'true' : 'false');
            $node.children('.clf-node__children').toggleClass('is-open', open);
        },

        createCategoryNodeEl: function (node, selected) {
            const self = this;
            const hasChildren = !!(node.children && node.children.length);
            const open = hasChildren && !!(this.categoryExpandedIds && this.categoryExpandedIds[node.id]);
            const $node = $('<div class="clf-node"></div>')
                .attr({ 'data-id': node.id, 'data-parent-id': node.parent_id || 0, 'data-label': node.name });
            const $row = $('<div class="clf-node__row"></div>');

            if (hasChildren) {
                $row.append(
                    $('<button type="button" class="clf-node__toggle js-clf-toggle" aria-label="Развернуть"></button>')
                        .toggleClass('is-expanded', open)
                        .attr('aria-expanded', open ? 'true' : 'false')
                );
            } else {
                $row.append(
                    $('<button type="button" class="clf-node__toggle is-leaf" tabindex="-1" aria-hidden="true"></button>')
                        .prop('disabled', true)
                );
            }

            const $label = $(
                '<label class="tender-creation-checkbox">'
                + '<input type="checkbox" class="js-classifier-check" name="category[]">'
                + '<span class="checkbox-ui"></span>'
                + '<span class="checkbox-text"></span>'
                + '</label>'
            );
            $label.find('.js-classifier-check')
                .val(String(node.id))
                .attr({
                    'data-label': node.name,
                    'data-category-id': node.id,
                    'data-parent-id': node.parent_id || 0,
                    'data-left-key': node.left_key || 0,
                    'data-right-key': node.right_key || 0
                })
                .prop('checked', !!selected[node.id]);
            $label.find('.checkbox-text').text(node.name);
            $node.append($row.append($label));

            if (hasChildren) {
                const $children = $('<div class="clf-node__children"></div>').toggleClass('is-open', open);
                node.children.forEach(function (child) {
                    $children.append(self.createCategoryNodeEl(child, selected));
                });
                $node.append($children);
            }
            return $node;
        },

        syncCategoryCheckboxBranch: function ($checkbox) {
            if (this._categorySyncing || !$checkbox || !$checkbox.length) return;
            this._categorySyncing = true;

            const $all = this.$categoryTree().find('.js-classifier-check');
            const checked = $checkbox.prop('checked');
            const left = parseInt($checkbox.data('left-key'), 10) || 0;
            const right = parseInt($checkbox.data('right-key'), 10) || 0;

            if (left && right) {
                $all.each(function () {
                    const l = parseInt($(this).data('left-key'), 10) || 0;
                    const r = parseInt($(this).data('right-key'), 10) || 0;
                    if (l > left && r < right) {
                        $(this).prop('checked', checked);
                    }
                });
            }

            let parentId = parseInt($checkbox.data('parent-id'), 10) || 0;
            while (parentId > 0) {
                const $parent = $all.filter('[data-category-id="' + parentId + '"]');
                if (!$parent.length) break;
                const $children = $all.filter('[data-parent-id="' + parentId + '"]');
                $parent.prop('checked', !!$children.length && $children.filter(':checked').length === $children.length);
                parentId = parseInt($parent.data('parent-id'), 10) || 0;
            }

            this._categorySyncing = false;
        },

        renderCategoryTree: function (rows) {
            const $tree = this.$categoryTree();
            const list = rows || [];
            const selected = {};
            this.getSelectedCategoryIds().forEach(function (id) { selected[id] = true; });

            $tree.empty();
            if (!list.length) {
                this.setCategoryTreeStatus('Классификатор пуст');
                this.renderClassifierSelected($(this.root).find('.creation-card-categories-results[data-tab="common"]'));
                return;
            }

            if (!this.categoryExpandedIds) {
                this.categoryExpandedIds = {};
                list.forEach(function (row) {
                    if (!(parseInt(row.parent_id, 10) || 0)) {
                        const id = parseInt(row.id, 10) || 0;
                        if (id) this.categoryExpandedIds[id] = true;
                    }
                }, this);
            }

            const self = this;
            this.buildCategoryForest(list).forEach(function (node) {
                $tree.append(self.createCategoryNodeEl(node, selected));
            });

            const query = String($(this.root).find('.js-classifier-search[data-tab="common"]').val() || '');
            if ($.trim(query)) this.filterCategoryTree(query);

            this.renderClassifierSelected($(this.root).find('.creation-card-categories-results[data-tab="common"]'));
        },

        filterCategoryTree: function (query) {
            const $tree = this.$categoryTree();
            const q = String(query || '').toLowerCase().trim();
            const self = this;

            if (!q) {
                $tree.find('.clf-node').removeClass('is-search-hidden').each(function () {
                    const id = parseInt($(this).data('id'), 10) || 0;
                    if (!id || !$(this).children('.clf-node__children').length) return;
                    const open = !!(self.categoryExpandedIds && self.categoryExpandedIds[id]);
                    $(this).children('.clf-node__row').find('.js-clf-toggle')
                        .toggleClass('is-expanded', open)
                        .attr('aria-expanded', open ? 'true' : 'false');
                    $(this).children('.clf-node__children').toggleClass('is-open', open);
                });
                return;
            }

            const mark = function ($node) {
                let childMatch = false;
                $node.children('.clf-node__children').children('.clf-node').each(function () {
                    if (mark($(this))) childMatch = true;
                });
                const visible = String($node.data('label') || '').toLowerCase().indexOf(q) !== -1 || childMatch;
                $node.toggleClass('is-search-hidden', !visible);
                if (visible && childMatch) {
                    $node.children('.clf-node__row').find('.js-clf-toggle')
                        .addClass('is-expanded').attr('aria-expanded', 'true');
                    $node.children('.clf-node__children').addClass('is-open');
                }
                return visible;
            };

            $tree.children('.clf-node').each(function () { mark($(this)); });
        },

        getSelectedCategoryIds: function () {
            const ids = [];
            this.$categoryTree().find('.js-classifier-check:checked').each(function () {
                const id = parseInt($(this).val(), 10) || 0;
                if (id) ids.push(id);
            });
            return ids;
        },

        setSelectedCategoryIds: function (ids) {
            const selected = {};
            (ids || []).forEach(function (id) {
                id = parseInt(id, 10) || 0;
                if (id) selected[id] = true;
            });
            const $panel = $(this.root).find('.creation-card-categories-results[data-tab="common"]');
            $panel.find('.js-classifier-check').each(function () {
                $(this).prop('checked', !!selected[parseInt($(this).val(), 10) || 0]);
            });
            this.renderClassifierSelected($panel);
        },

        renderClassifierSelected: function ($panel) {
            const $tags = $panel.find('.js-classifier-selected');
            const $empty = $panel.find('.js-classifier-empty');
            $tags.empty();
            const selected = [];
            $panel.find('.js-classifier-check:checked').each(function () {
                selected.push({
                    value: String($(this).val() || ''),
                    label: String($(this).data('label') || $(this).closest('label').find('.checkbox-text').text() || '')
                });
            });
            selected.forEach(function (item) {
                const $tag = $(`
                    <span class="tag" data-value="${item.value}">
                        <span class="tag-label"></span>
                        <button type="button" class="tag-remove js-classifier-tag-remove" aria-label="Убрать">×</button>
                    </span>
                `);
                $tag.find('.tag-label').text(item.label);
                $tags.append($tag);
            });
            $empty.toggleClass('is-hidden', selected.length > 0);
        },

        formatFileSize: function (bytes) {
            const size = Number(bytes) || 0;
            if (size < 1024) return size + ' B';
            if (size < 1024 * 1024) return Math.round(size / 1024) + ' kb';
            return (size / (1024 * 1024)).toFixed(1).replace(/\.0$/, '') + ' МБ';
        },

        /** Клиентская проверка файла ТЗ/лота (сервер проверяет ещё раз). */
        assertTenderUploadFile: function (file) {
            if (!file) return false;
            const maxSize = 10 * 1024 * 1024;
            const name = String(file.name || '');
            const ext = name.split('.').pop().toLowerCase();
            if (!(ext === 'pdf' || ext === 'doc' || ext === 'docx')) {
                $.AlertManager.showError('Допустимы только PDF и Word (.doc, .docx)');
                return false;
            }
            if (file.size > maxSize) {
                $.AlertManager.showError('Максимальный размер файла — 10 МБ');
                return false;
            }
            return true;
        },

        /**
         * Upload одного файла в уже созданный черновик.
         * @returns {Promise<{file_link_id:number,filename:string,size:number,ext:string}|null>}
         */
        uploadTenderFile: function (file) {
            const self = this;
            if (!file) return Promise.resolve(null);
            if (!(this.tenderId > 0)) {
                $.AlertManager.showError('Сначала сохраните черновик');
                return Promise.resolve(null);
            }

            const fd = new FormData();
            fd.append('file', file);
            return $.fRequest({
                url: '/api/buyer/tender/' + self.tenderId + '/file/upload/',
                method: 'POST',
                data: fd,
                showMessages: false
            }).then(function (reply) {
                const linkId = reply && parseInt(reply.file_link_id, 10);
                if (!reply || reply.error || !(linkId > 0)) {
                    const msg = (reply && reply.message)
                        || (reply && reply.tender ? 'Неверный ответ сервера при загрузке файла' : null)
                        || 'Не удалось загрузить файл';
                    $.AlertManager.showError(msg);
                    return null;
                }
                return {
                    file_link_id: linkId,
                    filename: String(reply.filename || file.name || ''),
                    size: parseInt(reply.size, 10) || file.size || 0,
                    ext: String(reply.ext || '').toLowerCase()
                };
            });
        },

        /** Загрузить все локально выбранные файлы (ТЗ / позиции / документы) перед save. */
        uploadPendingTenderFiles: function () {
            const self = this;
            if (!(this.tenderId > 0)) return Promise.resolve(false);

            let chain = Promise.resolve(true);

            (this.techSpecFiles || []).forEach(function (entry, index) {
                if (!entry || !entry._file || parseInt(entry.file_link_id, 10) > 0) return;
                chain = chain.then(function (ok) {
                    if (!ok) return false;
                    return self.uploadTenderFile(entry._file).then(function (uploaded) {
                        if (!uploaded) return false;
                        const next = Object.assign({}, self.techSpecFiles[index], {
                            id: 'fl-' + uploaded.file_link_id,
                            name: uploaded.filename,
                            size: self.formatFileSize(uploaded.size),
                            ext: uploaded.ext,
                            file_link_id: uploaded.file_link_id,
                            _file: null
                        });
                        self.techSpecFiles[index] = next;
                        return true;
                    });
                });
            });

            (this.lotPositions || []).forEach(function (pos) {
                if (!pos || !pos._file || parseInt(pos.file_link_id, 10) > 0) return;
                const posId = pos.id;
                chain = chain.then(function (ok) {
                    if (!ok) return false;
                    return self.uploadTenderFile(pos._file).then(function (uploaded) {
                        if (!uploaded) return false;
                        self.updateLotPosById(posId, {
                            fileName: uploaded.filename,
                            fileSize: uploaded.size,
                            file_link_id: uploaded.file_link_id,
                            _file: null
                        });
                        return true;
                    });
                });
            });

            (this.lotDocuments || []).forEach(function (doc) {
                if (!doc || !doc._file || parseInt(doc.file_link_id, 10) > 0) return;
                const docId = doc.id;
                chain = chain.then(function (ok) {
                    if (!ok) return false;
                    return self.uploadTenderFile(doc._file).then(function (uploaded) {
                        if (!uploaded) return false;
                        self.updateLotDocById(docId, {
                            fileName: uploaded.filename,
                            fileSize: uploaded.size,
                            file_link_id: uploaded.file_link_id,
                            _file: null
                        });
                        return true;
                    });
                });
            });

            return chain.then(function (ok) {
                if (ok) {
                    self.renderTechSpecFiles();
                    self.renderLotsList();
                    self.renderDocsList();
                    if (self.getSelectedLot()) self.renderLotEditor();
                    if (self.getSelectedDoc()) self.renderDocEditor();
                }
                return !!ok;
            });
        },

        addTechSpecFiles: function (fileList) {
            if (!fileList || !fileList.length) return;
            const self = this;
            Array.prototype.forEach.call(fileList, function (file) {
                if (!self.assertTenderUploadFile(file)) return;
                const ext = String(file.name || '').split('.').pop().toLowerCase();
                self.techSpecFiles.push({
                    id: 'local-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8),
                    name: file.name,
                    size: self.formatFileSize(file.size),
                    ext: ext,
                    file_link_id: null,
                    _file: file
                });
            });
            this.renderTechSpecFiles();
        },

        renderTechSpecFiles: function () {
            const $list = $(this.root).find('.js-tech-spec-file-list');
            $list.empty();
            this.techSpecFiles.forEach(function (file) {
                const extLabel = String(file.ext || 'file').toUpperCase();
                const status = parseInt(file.file_link_id, 10) > 0
                    ? 'Файл загружен'
                    : 'Будет загружен при сохранении';
                const $item = $(`
                    <div class="file-item" data-id="${file.id}">
                        <span class="file-left">
                            <span class="file-icon"></span>
                            <div class="file-info">
                                <span class="file-name"></span>
                                <span class="file-meta">
                                    <span class="file-status"></span>
                                    <span class="file-size">• ${file.size}</span>
                                </span>
                            </div>
                        </span>
                        <div class="file-actions">
                            <button type="button" class="file-remove button secondary js-tech-spec-file-remove" aria-label="Удалить">
                                <svg><use href="#icon-x-mark"></use></svg>
                            </button>
                        </div>
                    </div>
                `);
                $item.find('.file-icon').text(extLabel);
                $item.find('.file-name').text(file.name);
                $item.find('.file-status').text(status);
                $list.append($item);
            });
        },

        collectLotsPayload: function () {
            const self = this;
            const items = (this.lotPositions || []).filter(function (pos) {
                return self.getLotStatus(pos) !== 'draft';
            }).map(function (pos, index) {
                const qtyRaw = String(pos.quantity || '').replace(',', '.');
                const qty = parseFloat(qtyRaw);
                return {
                    id: parseInt(pos.dbId, 10) || 0,
                    name: $.trim(pos.name || ''),
                    qty: !isNaN(qty) ? qty : 0,
                    unit: pos.unit || 'шт.',
                    max_price_no_vat: pos.maxPriceNoVat,
                    vat_rate: pos.vatRate || '',
                    delivery_place: pos.deliveryPlace || '',
                    comment: pos.comment || '',
                    file_link_id: pos.file_link_id || null,
                    sort: index
                };
            }).filter(function (row) {
                return $.trim(row.name) !== '';
            });

            const documents = [];
            (this.techSpecFiles || []).forEach(function (file, index) {
                if (!(parseInt(file.file_link_id, 10) > 0)) return;
                documents.push({
                    id: parseInt(file.dbId, 10) || 0,
                    kind: 'tech_spec',
                    name: file.name || 'Техническое задание',
                    description: '',
                    is_required: 1,
                    file_link_id: parseInt(file.file_link_id, 10),
                    sort: index
                });
            });
            (this.lotDocuments || []).forEach(function (doc, index) {
                const name = $.trim(doc.name || '');
                if (!name && !(parseInt(doc.file_link_id, 10) > 0)) return;
                documents.push({
                    id: parseInt(doc.dbId, 10) || 0,
                    kind: 'requirement',
                    name: name || ('Документ ' + (index + 1)),
                    description: doc.description || '',
                    is_required: doc.isRequired ? 1 : 0,
                    file_link_id: doc.file_link_id || null,
                    sort: index
                });
            });

            return { items: items, documents: documents };
        },

        loadList: function () {
            const self = this;
            const $root = $(this.root);
            $root.find('.js-tenders-error').hide();

            return $.fRequest({
                url: '/api/buyer/tender/list/',
                method: 'GET',
                showMessages: false,
                onSuccess: function (reply) {
                    self.items = reply.items || [];
                    self.fillStatusFilter();
                    self.applyFilters();
                },
                onError: function (reply) {
                    $root.find('.js-tenders-list').hide().empty();
                    $root.find('.js-tenders-empty').hide();
                    $root.find('.js-tenders-error').show();
                    $root.find('.js-tenders-error-text').text(reply.message || 'Ошибка загрузки');
                }
            });
        },

        fillStatusFilter: function () {
            const $select = $(this.root).find('.js-tenders-filter-status');
            const current = this.filterStatus;
            const names = {};
            this.items.forEach(function (item) {
                const name = String(item.status_name || '').trim();
                if (name) names[name] = true;
            });
            $select.find('option:not(:first)').remove();
            Object.keys(names).sort(function (a, b) {
                return a.localeCompare(b, 'ru');
            }).forEach(function (name) {
                $select.append($('<option>').val(name).text(name));
            });
            $select.val(current);
        },

        resolveBucket: function (statusCode) {
            if (statusCode === 'draft') return 'draft';
            if (statusCode === 'suspended') return 'on_hold';
            if (statusCode === 'archived' || statusCode === 'cancelled' || statusCode === 'unsuccessful') {
                return 'archived';
            }
            return 'active';
        },

        formatDate: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) return '';
            const d = new Date(raw.replace(' ', 'T'));
            if (isNaN(d.getTime())) return '';
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const yyyy = d.getFullYear();
            return dd + '.' + mm + '.' + yyyy;
        },

        formatDeadline: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) return '—';
            const d = new Date(raw.replace(' ', 'T'));
            if (isNaN(d.getTime())) return '—';
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const yyyy = d.getFullYear();
            const hh = String(d.getHours()).padStart(2, '0');
            const mi = String(d.getMinutes()).padStart(2, '0');
            return dd + '.' + mm + '.' + yyyy + ' в ' + hh + ':' + mi;
        },

        calcDaysLeft: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) return null;
            const d = new Date(raw.replace(' ', 'T'));
            if (isNaN(d.getTime())) return null;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return Math.floor((d.getTime() - today.getTime()) / 86400000);
        },

        formatDaysLeft: function (daysLeft) {
            if (daysLeft === null || daysLeft === undefined) return '';
            if (daysLeft < 0) return 'Приём завершён';
            const n = daysLeft;
            const mod10 = n % 10;
            const mod100 = n % 100;
            let word = 'дней';
            if (mod100 < 11 || mod100 > 14) {
                if (mod10 === 1) word = 'день';
                else if (mod10 >= 2 && mod10 <= 4) word = 'дня';
            }
            return 'Осталось ' + n + ' ' + word;
        },

        applyFilters: function () {
            const self = this;
            const query = String(this.search || '').trim().toLowerCase();
            const filtered = this.items.filter(function (item) {
                const bucket = self.resolveBucket(item.status_code);
                if (self.bucket !== 'all' && bucket !== self.bucket) return false;
                if (self.filterType && String(item.type_code || '') !== self.filterType) return false;
                if (self.filterStatus && String(item.status_name || '') !== self.filterStatus) return false;
                if (!query) return true;
                const hay = [
                    item.title,
                    item.number,
                    item.type_name,
                    item.status_name
                ].join(' ').toLowerCase();
                return hay.indexOf(query) !== -1;
            });
            this.renderList(filtered);
        },

        renderList: function (items) {
            const $root = $(this.root);
            const $list = $root.find('.js-tenders-list');
            const $empty = $root.find('.js-tenders-empty');
            const hasFilters = !!(this.search || this.filterType || this.filterStatus || this.bucket !== 'all');
            $list.empty();

            if (!items.length) {
                $list.hide();
                $empty.show();
                $root.find('.js-tenders-empty-title').text(hasFilters ? 'Ничего не найдено' : 'Нет тендеров');
                $root.find('.js-tenders-empty-des').text(
                    hasFilters
                        ? 'Измените поиск или сбросьте фильтры.'
                        : 'Здесь отображаются все процедуры вашей компании. Создайте тендер, чтобы начать закупку.'
                );
                $root.find('.js-tenders-empty-create').toggle(!hasFilters);
                $root.find('.js-tenders-empty-clear').toggle(!!hasFilters);
                return;
            }

            $empty.hide();
            $list.show();

            const self = this;
            items.forEach(function (item) {
                const created = self.formatDate(item.create_datetime) || '—';
                const deadline = self.formatDeadline(item.end_at);
                const daysLeft = self.calcDaysLeft(item.end_at);
                const daysLabel = self.formatDaysLeft(daysLeft);
                const expired = daysLeft !== null && daysLeft < 0;
                const access = item.is_private == 1 || item.is_private === true ? 'Закрытый' : 'Открытый';
                const tone = item.status_tone || 'info';
                const methodLine = (item.type_name || 'Тендер') + (item.number ? ' №' + item.number : '');

                const $card = $(`
                    <article class="buyer-tender-card" data-id="${item.id}">
                        <div class="buyer-tender-card__main">
                            <div class="buyer-tender-card__title-row">
                                <h3 class="buyer-tender-card__title"></h3>
                                <span class="badge badge--shared badge--medium ${tone} js-buyer-tender-status"></span>
                            </div>
                            <div class="buyer-tender-card__meta">
                                <span>Дата создания: ${created}</span>
                                <span class="buyer-tender-card__dot" aria-hidden="true"></span>
                                <span class="buyer-tender-card__method"></span>
                                <span class="buyer-tender-card__dot" aria-hidden="true"></span>
                                <span>${access}</span>
                            </div>
                        </div>
                        <div class="buyer-tender-card__metric">
                            <span class="buyer-tender-card__metric-label">Приглашено</span>
                            <span class="buyer-tender-card__metric-value">${parseInt(item.invited_count, 10) || 0}</span>
                        </div>
                        <div class="buyer-tender-card__metric">
                            <span class="buyer-tender-card__metric-label">Участники</span>
                            <span class="buyer-tender-card__metric-value">${parseInt(item.participants_count, 10) || 0}</span>
                        </div>
                        <div class="buyer-tender-card__metric">
                            <span class="buyer-tender-card__metric-label">Предложения</span>
                            <span class="buyer-tender-card__metric-value">${parseInt(item.proposals_count, 10) || 0}</span>
                        </div>
                        <div class="buyer-tender-card__deadline">
                            <span class="buyer-tender-card__metric-label">Окончание приёма заявок</span>
                            <div class="buyer-tender-card__deadline-row">
                                <span class="buyer-tender-card__metric-value${expired ? ' is-expired' : ''}">${deadline}</span>
                                ${daysLabel ? `<span class="badge badge--shared badge--small badge--tag badge--outline ${expired ? 'disabled' : 'neutral'}">${daysLabel}</span>` : ''}
                            </div>
                        </div>
                        <button type="button" class="buyer-tender-card__more" aria-label="Действия" disabled>⋮</button>
                    </article>`);
                $card.find('.buyer-tender-card__title').text(item.title || 'Без названия');
                $card.find('.js-buyer-tender-status').text(item.status_name || '—');
                $card.find('.buyer-tender-card__method').text(methodLine);
                $list.append($card);
            });
        },

        openDraft: function (tenderId) {
            const self = this;
            return $.fRequest({
                url: '/api/buyer/tender/' + tenderId + '/',
                method: 'GET',
                showMessages: false
            }).then(function (reply) {
                if (!reply || reply.error || !reply.tender) {
                    $.AlertManager.showError((reply && reply.message) || 'Не удалось открыть черновик');
                    return false;
                }
                if (!parseInt(reply.can_edit, 10)) {
                    $.AlertManager.showError('Редактирование недоступно: по тендеру уже подана заявка или статус не позволяет правку.');
                    return false;
                }
                const type = reply.tender.type || {};
                self.openCreate({
                    id: parseInt(type.id, 10) || 3,
                    code: type.code || 'price_request',
                    title: 'Редактирование процедуры'
                }, reply);
                return true;
            });
        },

        applyLotsFromDetail: function (items, documents) {
            const self = this;
            this.lotIdSeq = 0;
            const rows = Array.isArray(items) ? items : [];
            this.lotPositions = rows.map(function (row) {
                const qty = row.qty != null && row.qty !== '' ? String(row.qty) : '';
                return {
                    id: self.nextLotId('pos_'),
                    dbId: parseInt(row.id, 10) || 0,
                    name: row.name || '',
                    quantity: qty,
                    unit: row.unit || 'шт.',
                    maxPriceNoVat: row.max_price_no_vat != null && row.max_price_no_vat !== ''
                        ? String(row.max_price_no_vat)
                        : '0',
                    vatRate: row.vat_rate || '0%',
                    deliveryPlace: row.delivery_place || '',
                    comment: row.comment || '',
                    fileName: row.file_name || '',
                    fileSize: 0,
                    file_link_id: parseInt(row.file_link_id, 10) || null
                };
            });
            if (!this.lotPositions.length) {
                const posId = this.nextLotId('pos_');
                this.lotPositions = [{
                    id: posId,
                    name: this.isLotsPrequal() ? 'Позиция №1' : 'Позиция N1',
                    quantity: '',
                    unit: 'шт.',
                    maxPriceNoVat: '0',
                    vatRate: '0%',
                    deliveryPlace: '',
                    comment: '',
                    fileName: '',
                    fileSize: 0,
                    file_link_id: null
                }];
            }

            this.techSpecFiles = [];
            this.lotDocuments = [];
            (Array.isArray(documents) ? documents : []).forEach(function (doc) {
                const kind = String(doc.kind || '');
                const fileLinkId = parseInt(doc.file_link_id, 10) || 0;
                if (kind === 'tech_spec') {
                    if (fileLinkId > 0) {
                        self.techSpecFiles.push({
                            id: self.nextLotId('ts_'),
                            dbId: parseInt(doc.id, 10) || 0,
                            name: doc.file_name || doc.name || 'Техническое задание',
                            file_link_id: fileLinkId
                        });
                    }
                    return;
                }
                self.lotDocuments.push({
                    id: self.nextLotId('doc_'),
                    dbId: parseInt(doc.id, 10) || 0,
                    name: doc.name || '',
                    description: doc.description || '',
                    fileName: doc.file_name || '',
                    fileSize: 0,
                    file_link_id: fileLinkId || null,
                    isRequired: !!parseInt(doc.is_required, 10)
                });
            });
            this.selectedLotId = this.lotPositions[0] ? this.lotPositions[0].id : '';
            this.selectedDocId = this.lotDocuments[0] ? this.lotDocuments[0].id : '';
            this.renderTechSpecFiles();
            this.syncLotsStepUi();
        },

        applyDraftDetail: function (detail) {
            const $root = $(this.root);
            const tender = (detail && detail.tender) || {};
            const statusCode = String((tender.status && tender.status.code) || $root.attr('data-status-code') || '');
            this.editingPublishedTender = statusCode !== '' && statusCode !== 'draft';
            $root.find('.js-tender-save-draft').text(this.editingPublishedTender ? 'Сохранить изменения' : 'Сохранить в черновик');
            $root.find('.js-tender-publish').show();
            $root.find('.js-tender-apply-template').prop('disabled', this.editingPublishedTender);
            const flagOn = function (value) {
                return value === true || value === 1 || value === '1';
            };

            this.tenderId = parseInt(tender.id || tender.tender_id, 10) || 0;
            $root.find('.js-tender-id').val(String(this.tenderId));
            $root.find('.js-field-title').val(tender.title || '');

            $root.find('input[name="is_private"][value="' + (flagOn(tender.is_private) ? '1' : '0') + '"]')
                .prop('checked', true);
            $root.find('.js-private-invites').toggleClass('visible', flagOn(tender.is_private));

            const pastPrequalId = parseInt(tender.past_prequal_tender_id, 10) || 0;
            const showPastPrequal = flagOn(tender.is_private) && this.selectedTypeCode === 'price_request';
            $root.find('.js-past-prequal-wrap').toggle(showPastPrequal);
            $root.find('.js-field-past-prequal').prop('checked', pastPrequalId > 0);
            $root.find('.js-past-prequal-block').toggleClass('visible', pastPrequalId > 0);
            if (pastPrequalId > 0) {
                $root.find('.js-field-past-prequal-query').val(String(pastPrequalId));
            } else {
                $root.find('.js-field-past-prequal-query').val('');
            }

            $root.find('.js-field-approval-required').prop('checked', flagOn(tender.approval_required));
            $root.find('.js-approval-period-wrap').toggleClass('visible', flagOn(tender.approval_required));
            const docsParts = this.splitDatetime(tender.docs_end_at);
            const hasApprovalPeriod = flagOn(tender.approval_required) && !!(docsParts.date || docsParts.time);
            $root.find('.js-field-approval-period').prop('checked', hasApprovalPeriod);
            $root.find('.js-approval-period-fields').toggleClass('visible', hasApprovalPeriod);
            if (hasApprovalPeriod) {
                $root.find('.js-field-approval-date').val(docsParts.date);
                $root.find('.js-field-approval-time').val(docsParts.time);
            } else {
                $root.find('.js-field-approval-date, .js-field-approval-time').val('');
            }

            $root.find('.js-field-hide-initial-price').prop('checked', flagOn(tender.hide_initial_price));
            $root.find('.js-field-hide-participants').prop('checked', flagOn(tender.hide_participants_count));
            $root.find('.js-field-hide-prices').prop('checked', flagOn(tender.hide_participant_prices));
            $root.find('.js-hide-prices-options').toggleClass('visible', flagOn(tender.hide_participant_prices));
            if (tender.rank_prices_mode === 'use') {
                $root.find('input[name="rank_prices"][value="yes"]').prop('checked', true);
            } else if (flagOn(tender.hide_participant_prices)) {
                $root.find('input[name="rank_prices"][value="no"]').prop('checked', true);
            }
            $root.find('.js-field-organizer-sees-names').prop('checked', flagOn(tender.organizer_sees_names));
            $root.find('.js-field-itemized').prop('checked', flagOn(tender.itemized_enabled));
            $root.find('.js-field-allow-analogues').prop('checked', flagOn(tender.allow_analogues));
            $root.find('.js-field-require-docs').prop('checked', flagOn(tender.require_additional_docs));
            $root.find('.js-field-only-reduction').prop('checked', flagOn(tender.only_price_reduction));
            $root.find('.js-field-auto-extend-no-offers').prop('checked', flagOn(tender.auto_extend_no_offers));
            $root.find('.js-field-auto-extend-change').prop('checked', flagOn(tender.auto_extend_on_change));
            $root.find('.js-renewal-inner').toggleClass('visible', flagOn(tender.auto_extend_on_change));
            if (tender.auto_extend_period_min) {
                $root.find('.js-field-renewal-period').val(String(tender.auto_extend_period_min)).trigger('change');
            }
            $root.find('.js-field-min-step').prop('checked', flagOn(tender.min_step_enabled));
            if (tender.min_step_base === 'best' || tender.min_step_base === 'own') {
                $root.find('.js-field-min-step-base[value="' + tender.min_step_base + '"]').prop('checked', true);
            }
            const minStepType = String(tender.min_step_type || 'amount') === 'percent' ? 'percent' : 'amount';
            $root.find('.js-min-step-type').removeClass('active')
                .filter('[data-type="' + minStepType + '"]').addClass('active');
            $root.find('.js-min-step-amount').toggle(minStepType === 'amount');
            $root.find('.js-min-step-percent').toggle(minStepType === 'percent');
            if (tender.min_step_value != null && tender.min_step_value !== '') {
                if (minStepType === 'percent') {
                    $root.find('.js-field-min-step-percent').val(String(tender.min_step_value));
                } else {
                    $root.find('.js-field-min-step-value').val(String(tender.min_step_value));
                }
            }
            if (tender.vat_mode) {
                $root.find('input.js-field-vat[value="' + tender.vat_mode + '"]').prop('checked', true);
            }
            if (tender.budget != null && tender.budget !== '') {
                $root.find('.js-field-budget').val(tender.budget);
            }
            $root.find('.js-field-payment').val(tender.payment_terms || '');
            $root.find('.js-field-additional-delivery').val(tender.additional_delivery_info || '');
            if (tender.delivery_terms) {
                const addresses = String(tender.delivery_terms).split('\n').filter(Boolean);
                $root.find('.js-delivery-address').each(function (idx) {
                    $(this).val(addresses[idx] || '');
                });
            }
            if (tender.additional_info) {
                $root.find('.js-field-additional-info').val(tender.additional_info);
            }

            const endParts = this.splitDatetime(tender.end_at);
            $root.find('.js-field-end-date').val(endParts.date);
            $root.find('.js-field-end-time').val(endParts.time);
            $root.find('.js-field-docs-date').val(docsParts.date);
            $root.find('.js-field-docs-time').val(docsParts.time);
            const openParts = this.splitDatetime(tender.opening_at);
            $root.find('.js-field-result-date').val(openParts.date);

            this.criteria = (detail.criteria || []).map(function (row, index) {
                return {
                    id: row.id ? ('crit_' + row.id) : ('npc_' + Date.now() + '_' + index),
                    serverId: parseInt(row.id, 10) || 0,
                    name: row.name || '',
                    description: row.description || '',
                    type: row.type === 'non_price' ? 'text' : (row.type || 'text'),
                    required: !!parseInt(row.is_mandatory, 10),
                    confirmationText: row.confirmation_text || ''
                };
            });
            this.renderCriteria();

            this.invitationSelected = (detail.invitations || []).map(function (row) {
                const id = String(row.supplier_company_id || row.id || '');
                return {
                    id: id,
                    name: row.company_name || ('ID ' + id)
                };
            }).filter(function (row) { return row.id && row.id !== '0'; });
            const inviteIds = this.invitationSelected.map(function (row) { return row.id; });
            $root.find('.js-invite-select').val(inviteIds.length ? inviteIds : null).trigger('change');

            this.applyLotsFromDetail(detail.items, detail.documents);
            this.syncMinStepUi();
            this.syncBasicContinue();
            // Редактирование: все шаги уже заполнены, открыты для перехода.
            this.highestStepReached = this.steps.length - 1;
        },

        openCreate: function (method, detail) {
            const self = this;
            const $root = $(this.root);
            method = method || {};
            this.tenderId = 0;
            this.stepIndex = 0;
            this.highestStepReached = 0;
            this.editingPublishedTender = false;
            $root.find('.js-tender-save-draft').text('Сохранить в черновик');
            $root.find('.js-tender-publish').show();
            $root.find('.js-tender-apply-template').prop('disabled', false);
            this.selectedTypeId = parseInt(method.id, 10) || 3;
            this.selectedTypeCode = method.code || 'price_request';

            $root.find('.js-tender-id').val('0');
            $root.find('.js-tender-type').val(String(this.selectedTypeId));
            $root.find('.js-creation-title').text(method.title || 'Создание процедуры');

            $root.find('.js-field-title').val('');
            this.resetPaymentDeliveryUi();
            this.resetLotsUi();
            $root.find('.js-field-end-date, .js-field-end-time, .js-field-docs-date, .js-field-docs-time, .js-field-result-date').val('');
            $root.find('.js-field-additional-info, .js-field-approver').val('');
            $root.find('.js-field-itemized, .js-field-allow-analogues, .js-field-auto-extend-no-offers, .js-field-only-reduction, .js-field-require-docs').prop('checked', false);
            $root.find('.js-field-auto-extend-change, .js-field-min-step').prop('checked', true);
            $root.find('.js-renewal-inner').addClass('visible');
            $root.find('.js-field-renewal-period').val('30').trigger('change');
            $root.find('input[name="increment"][value="own"]').prop('checked', true);
            $root.find('.js-min-step-type').removeClass('active').filter('[data-type="amount"]').addClass('active');
            $root.find('.js-min-step-amount').show().find('input').val('1000');
            $root.find('.js-min-step-percent').hide().find('input').val('');
            $root.find('input[name="vat_option"][value="with_vat"]').prop('checked', true);
            $root.find('.js-field-approval-required, .js-field-approval-period, .js-field-past-prequal').prop('checked', false);
            $root.find('.js-field-hide-initial-price, .js-field-hide-participants, .js-field-hide-prices, .js-field-organizer-sees-names').prop('checked', false);
            $root.find('input[name="rank_prices"]').prop('checked', false);
            $root.find('input[name="is_private"][value="0"]').prop('checked', true);
            $root.find('.js-invite-select').val(null).trigger('change');
            $root.find('.js-private-invites, .js-past-prequal-block, .js-approval-period-wrap, .js-approval-period-fields, .js-hide-prices-options').removeClass('visible');
            $root.find('.js-price-request-only').toggle(this.selectedTypeCode === 'price_request');
            $root.find('.js-prequal-only').toggle(this.selectedTypeCode === 'prequalification');
            $root.find('.js-past-prequal-wrap').hide();
            if (this.classifierTabs) {
                this.classifierTabs.showTab('common');
            }
            $root.find('.js-classifier-search').val('');
            $root.find('.js-field-tags-search').val('');
            $root.find('.js-tags-selected').empty();
            this.categoryExpandedIds = null;
            this.techSpecFiles = [];
            this.renderTechSpecFiles();
            this.criteria = [];
            this.invitationSelected = [];
            this.extraInfoFields = [];
            this.extraApprovers = [];
            this.renderExtraInfoFields();
            this.renderExtraApprovers();
            this.renderCriteria();
            this.syncMinStepUi();
            this.syncBasicContinue();

            if (detail && detail.tender) {
                this.applyDraftDetail(detail);
            }

            this.loadCategoryTree().then(function () {
                if (detail && detail.classifiers && detail.classifiers.length) {
                    const type = parseInt(self.categoryClassifierType, 10) || 4;
                    const ids = detail.classifiers.map(function (row) {
                        if (parseInt(row.classifier_type, 10) !== type) return 0;
                        return parseInt(row.classifier_id, 10) || 0;
                    }).filter(Boolean);
                    self.setSelectedCategoryIds(ids);
                } else {
                    self.setSelectedCategoryIds([]);
                }
            });

            $root.find('.js-tenders-list-view').hide();
            $root.find('.js-tender-create-view').show();
            $root.addClass('tender-create');
            this.goStep(0);
        },

        leaveForm: function () {
            this.navigateCabinet('/cabinet/buyer/tenders/');
        },

        closeCreate: function () {
            this.leaveForm();
        },

        canJumpToStep: function (index) {
            if (index < 0 || index >= this.steps.length) return false;
            const key = this.steps[index];
            if (this.publishIssuesByStep && this.publishIssuesByStep[key]) return true;
            return index <= this.highestStepReached;
        },

        goStep: function (index) {
            if (index < 0 || index >= this.steps.length) return;
            this.stepIndex = index;
            this.highestStepReached = Math.max(this.highestStepReached, index);
            const step = this.steps[index];
            const self = this;
            const $root = $(this.root);
            const isWide = step === 'lots' || step === 'invitation';

            $root.toggleClass('is-wide', isWide);
            $root.find('.js-tender-create-body').toggleClass('is-wide', isWide);

            $root.find('.js-tender-step-panel').hide();
            $root.find('.js-tender-step-panel[data-step="' + step + '"]').show();

            this.syncCreationSteps();

            this.syncStepValidationBanners();

            if (step === 'basic') this.syncBasicContinue();
            if (step === 'invitation') {
                this.syncInvitationSelectedFromPrivacy();
                this.loadInvSuppliers();
                this.renderInvSelected();
                this.syncInvPublishBtn();
            }
            if (step === 'lots') {
                this.syncLotsStepUi();
            }
        },

        syncCreationSteps: function () {
            const self = this;
            const $root = $(this.root);
            $root.find('.js-tender-steps .creation-step').each(function (i) {
                const $el = $(this);
                const key = self.steps[i];
                const current = i === self.stepIndex;
                const completed = i <= self.highestStepReached;
                $el.toggleClass('is-current', current)
                    .toggleClass('completed', completed)
                    .toggleClass('next', i > self.highestStepReached)
                    .toggleClass('is-invalid', !!(self.publishIssuesByStep && self.publishIssuesByStep[key]))
                    .toggleClass('is-clickable', self.canJumpToStep(i) && !current);
                if (current) $el.attr('aria-current', 'step');
                else $el.removeAttr('aria-current');
            });
        },

        getPublishIssues: function () {
            const $root = $(this.root);
            const issues = [];
            const title = $.trim($root.find('.js-field-title').val() || '');
            if (!title) {
                issues.push({
                    step: 'basic',
                    field: 'title',
                    message: 'Укажите наименование извещения'
                });
            }

            const endAt = this.composeDatetime(
                $root.find('.js-field-end-date').val(),
                $root.find('.js-field-end-time').val()
            );
            if (!endAt) {
                issues.push({
                    step: 'purchase_params',
                    field: 'end_at',
                    message: 'Укажите конечный срок подачи заявок'
                });
            } else {
                const endTimestamp = Date.parse(endAt.replace(' ', 'T') + '+03:00');
                if (!Number.isNaN(endTimestamp) && endTimestamp <= Date.now()) {
                    issues.push({
                        step: 'purchase_params',
                        field: 'end_at',
                        message: 'Конечный срок подачи заявок должен быть позже текущего времени'
                    });
                }
            }

            if (!this.hasReadyLotPositions()) {
                issues.push({
                    step: 'lots',
                    field: 'items',
                    message: 'Заполните хотя бы одну позицию, чтобы продолжить'
                });
            }

            const isPrivate = $root.find('input[name="is_private"]:checked').val() === '1';
            if (isPrivate) {
                const inviteCount = this.getInvitationCompanyIds
                    ? this.getInvitationCompanyIds().length
                    : (($root.find('.js-invite-select').val() || []).length);
                if (inviteCount < 1) {
                    issues.push({
                        step: 'privacy',
                        field: 'invitations',
                        message: 'Добавьте хотя бы одного приглашённого поставщика'
                    });
                }
            }

            return issues;
        },

        applyPublishIssues: function (issues) {
            const map = {};
            (issues || []).forEach(function (row) {
                const step = String(row.step || '');
                if (!step || map[step]) return;
                map[step] = String(row.message || 'Заполните обязательные поля');
            });
            this.publishIssuesByStep = Object.keys(map).length ? map : null;
            this.syncStepValidationBanners();
            this.goStep(this.stepIndex);
        },

        clearPublishIssues: function () {
            this.publishIssuesByStep = null;
            $(this.root).find('.js-step-validation-banner').hide();
            this.goStep(this.stepIndex);
        },

        syncStepValidationBanners: function () {
            const self = this;
            const $root = $(this.root);
            $root.find('.js-step-validation-banner').each(function () {
                const $banner = $(this);
                const step = String($banner.data('step') || '');
                const msg = self.publishIssuesByStep && self.publishIssuesByStep[step];
                if (msg) {
                    $banner.find('.js-step-validation-text').text(msg);
                    $banner.show();
                } else {
                    $banner.hide();
                }
            });
        },

        refreshPublishIssueForStep: function (step) {
            if (!this.publishIssuesByStep || !this.publishIssuesByStep[step]) return;
            const still = this.getPublishIssues().some(function (row) {
                return String(row.step) === step;
            });
            if (!still) {
                delete this.publishIssuesByStep[step];
                if (!Object.keys(this.publishIssuesByStep).length) {
                    this.publishIssuesByStep = null;
                }
                this.syncStepValidationBanners();
                this.goStep(this.stepIndex);
            }
        },

        jumpToFirstPublishIssue: function (issues) {
            const list = issues || [];
            if (!list.length) return;
            const step = String(list[0].step || '');
            const index = this.steps.indexOf(step);
            if (index >= 0) this.goStep(index);
        },

        collectStepData: function (step) {
            const $root = $(this.root);
            const data = {};
            const isPriceRequest = this.selectedTypeCode === 'price_request';

            if (step === 'privacy') {
                data.is_private = $root.find('input[name="is_private"]:checked').val() === '1' ? 1 : 0;
                data.approval_required = $root.find('input[name="approval_required"]').is(':checked') ? 1 : 0;
                if (data.is_private) {
                    const inviteIds = ($root.find('.js-invite-select').val() || []).filter(function (id) {
                        return /^\d+$/.test(String(id));
                    });
                    data.invitations = inviteIds.length ? inviteIds : '';
                }
                if (data.approval_required && $root.find('.js-field-approval-period').is(':checked')) {
                    const approvalEnd = this.composeDatetime(
                        $root.find('.js-field-approval-date').val(),
                        $root.find('.js-field-approval-time').val()
                    );
                    if (approvalEnd) data.docs_end_at = approvalEnd;
                }
                if (isPriceRequest) {
                    if (data.is_private && $root.find('.js-field-past-prequal').is(':checked')) {
                        const rawPrequal = $.trim($root.find('.js-field-past-prequal-query').val() || '');
                        const prequalId = parseInt(rawPrequal, 10) || 0;
                        data.past_prequal_tender_id = (prequalId > 0 && String(prequalId) === rawPrequal)
                            ? prequalId
                            : 0;
                    } else {
                        data.past_prequal_tender_id = 0;
                    }
                    data.hide_initial_price = $root.find('.js-field-hide-initial-price').is(':checked') ? 1 : 0;
                    data.hide_participants_count = $root.find('.js-field-hide-participants').is(':checked') ? 1 : 0;
                    data.hide_participant_prices = $root.find('.js-field-hide-prices').is(':checked') ? 1 : 0;
                    if (data.hide_participant_prices) {
                        const rank = $root.find('input[name="rank_prices"]:checked').val();
                        data.rank_prices_mode = rank === 'yes' ? 'use' : 'dont_use';
                    }
                    data.organizer_sees_names = $root.find('.js-field-organizer-sees-names').is(':checked') ? 1 : 0;
                }
            } else if (step === 'basic') {
                data.title = $.trim($root.find('.js-field-title').val() || '');
            } else if (step === 'purchase_params') {
                data.end_at = this.composeDatetime(
                    $root.find('.js-field-end-date').val(),
                    $root.find('.js-field-end-time').val()
                );
                const docsEnd = this.composeDatetime(
                    $root.find('.js-field-docs-date').val(),
                    $root.find('.js-field-docs-time').val()
                );
                if (docsEnd) data.docs_end_at = docsEnd;
                const resultAt = this.composeDatetime(
                    $root.find('.js-field-result-date').val(),
                    '00:00'
                );
                if (resultAt) data.opening_at = resultAt;

                if (this.selectedTypeCode === 'prequalification') {
                    data.retendering_enabled = 0;
                    data.itemized_enabled = 0;
                } else if (isPriceRequest) {
                    data.itemized_enabled = $root.find('.js-field-itemized').is(':checked') ? 1 : 0;
                    data.allow_analogues = $root.find('.js-field-allow-analogues').is(':checked') ? 1 : 0;
                    data.auto_extend_no_offers = $root.find('.js-field-auto-extend-no-offers').is(':checked') ? 1 : 0;
                    data.auto_extend_on_change = $root.find('.js-field-auto-extend-change').is(':checked') ? 1 : 0;
                    if (data.auto_extend_on_change) {
                        data.auto_extend_period_min = parseInt($root.find('.js-field-renewal-period').val(), 10) || 0;
                    }
                    data.min_step_enabled = $root.find('.js-field-min-step').is(':checked') ? 1 : 0;
                    if (data.min_step_enabled) {
                        data.min_step_base = $root.find('.js-field-min-step-base:checked').val() || 'own';
                        const stepType = $root.find('.js-min-step-type.active').data('type') || 'amount';
                        data.min_step_type = stepType === 'percent' ? 'percent' : 'amount';
                        const rawStep = stepType === 'percent'
                            ? $root.find('.js-field-min-step-percent').val()
                            : $root.find('.js-field-min-step-value').val();
                        const parsedStep = parseFloat(String(rawStep || '').replace(',', '.'));
                        if (!isNaN(parsedStep)) data.min_step_value = parsedStep;
                    }
                    data.only_price_reduction = $root.find('.js-field-only-reduction').is(':checked') ? 1 : 0;
                    data.vat_mode = $root.find('input.js-field-vat:checked').val() || 'with_vat';
                }

                data.require_additional_docs = $root.find('.js-field-require-docs').is(':checked') ? 1 : 0;
                const infoParts = [];
                const mainInfo = $.trim($root.find('.js-field-additional-info').val() || '');
                if (mainInfo) infoParts.push(mainInfo);
                $root.find('.js-extra-info-list input').each(function () {
                    const extra = $.trim($(this).val() || '');
                    if (extra) infoParts.push(extra);
                });
                if (infoParts.length) data.additional_info = infoParts.join('\n');
            } else if (step === 'payment_delivery') {
                data.budget = $.trim($root.find('.js-field-budget').val() || '');
                data.payment_terms = $.trim($root.find('.js-field-payment').val() || '');
                const addresses = [];
                $root.find('.js-delivery-address').each(function () {
                    const value = $.trim($(this).val() || '');
                    if (value) addresses.push(value);
                });
                data.delivery_terms = addresses.join('\n');
                data.additional_delivery_info = $.trim($root.find('.js-field-additional-delivery').val() || '');
                data.currency = 'RUB';
            } else if (step === 'invitation') {
                /* invitations в save не шлём — источник .js-invite-select на privacy */
                const addMsg = $.trim($root.find('.js-inv-msg-textarea').val() || '');
                if (addMsg) data.invitation_message = addMsg;
                data.invitation_send_type = $root.find('input[name="inv_send_type"]:checked').val() || 'manual';
            }
            return data;
        },

        /** Все поля мастера одним объектом (для save draft / publish). */
        collectAllFormData: function () {
            const self = this;
            const classifiers = this.getSelectedCategoryIds().map(function (id) {
                return { classifier_type: parseInt(self.categoryClassifierType, 10) || 4, classifier_id: id };
            });
            const data = { type: this.selectedTypeId, classifiers: classifiers.length ? classifiers : '' };
            this.steps.forEach(function (step) {
                if (step === 'lots') return;
                Object.assign(data, self.collectStepData(step));
            });
            if (this.selectedTypeCode === 'price_request' || this.selectedTypeCode === 'proposal_request') {
                data.criteria = (this.criteria || []).map(function (item, index) {
                    return {
                        id: item.serverId || 0,
                        name: item.name || '',
                        type: item.type || 'text',
                        is_mandatory: item.required ? 1 : 0,
                        description: item.description || '',
                        confirmation_text: item.type === 'confirmation' ? (item.confirmationText || '') : '',
                        sort: index
                    };
                }).filter(function (row) {
                    return $.trim(row.name) !== '';
                });
                if (!data.criteria.length) data.criteria = '';
            }
            if (this.steps.indexOf('lots') !== -1 || this.lotPositions.length || this.techSpecFiles.length || this.lotDocuments.length) {
                const lots = this.collectLotsPayload();
                if (this.steps.indexOf('lots') !== -1 || this.lotPositions.length) {
                    data.items = lots.items.length ? lots.items : '';
                }
                data.documents = lots.documents.length ? lots.documents : '';
            }
            return data;
        },

        saveDraft: function (options) {
            const self = this;
            const opts = options || {};
            let data = this.collectAllFormData();

            if (opts.publish) {
                const issues = this.getPublishIssues();
                if (issues.length) {
                    this.applyPublishIssues(issues);
                    this.jumpToFirstPublishIssue(issues);
                    $.AlertManager.showError('Заполните обязательные поля перед публикацией');
                    return Promise.resolve(false);
                }
                this.clearPublishIssues();
            }

            return this.ensureDraft(data).then(function (ok) {
                if (!ok) return false;
                return self.uploadPendingTenderFiles();
            }).then(function (ok) {
                if (!ok) return false;
                data = self.collectAllFormData();
                return self.postSave(data, { silent: !!opts.publish });
            }).then(function (ok) {
                if (!ok) return false;
                return opts.publish ? self.requestPublish() : true;
            });
        },

        ensureDraft: function (data) {
            const self = this;
            if (this.tenderId > 0) return Promise.resolve(true);

            const form = data || this.collectAllFormData();
            const title = $.trim(form.title || '');
            return $.fRequest({
                url: '/api/buyer/tender/create/',
                method: 'POST',
                showMessages: false,
                data: {
                    type: form.type || this.selectedTypeId,
                    title: title,
                    classifiers: form.classifiers
                },
                onSuccess: function (reply) {
                    self.tenderId = parseInt(reply.tender_id || 0, 10) || self.tenderId;
                    $(self.root).find('.js-tender-id').val(String(self.tenderId));
                    $(self.root).find('.js-field-title').val(title);
                    self.syncEditUrlAfterCreate();
                }
            }).then(function (reply) {
                return !reply.error && self.tenderId > 0;
            });
        },

        postSave: function (data, options) {
            const self = this;
            const opts = options || {};
            if (!this.tenderId) return Promise.resolve(false);

            const payload = Object.assign({ id: this.tenderId }, data || {});
            delete payload.type;
            delete payload.number;
            if (payload.title != null) {
                payload.title = $.trim(payload.title);
            }

            const savedPositionIds = Array.isArray(payload.items)
                ? (this.lotPositions || []).filter(function (pos) {
                    return self.getLotStatus(pos) !== 'draft' && $.trim(pos.name || '') !== '';
                }).map(function (pos) { return pos.id; })
                : [];

            const savedDocumentIds = Array.isArray(payload.documents) ? {
                tech_spec: (this.techSpecFiles || []).filter(function (file) {
                    return parseInt(file.file_link_id, 10) > 0;
                }).map(function (file) { return file.id; }),
                requirement: (this.lotDocuments || []).filter(function (doc) {
                    return $.trim(doc.name || '') !== '' || parseInt(doc.file_link_id, 10) > 0;
                }).map(function (doc) { return doc.id; })
            } : {};

            return $.fRequest({
                url: '/api/buyer/tender/save/',
                method: 'POST',
                showMessages: !opts.silent,
                data: payload,
                onSuccess: function (reply) {
                    self.tenderId = parseInt(reply.tender_id || 0, 10) || self.tenderId;
                    $(self.root).find('.js-tender-id').val(String(self.tenderId));
                    if (Array.isArray(reply.items)) {
                        reply.items.forEach(function (row, index) {
                            const position = (self.lotPositions || []).find(function (pos) {
                                return pos.id === savedPositionIds[index];
                            });
                            if (position) {
                                position.dbId = parseInt(row.id, 10) || 0;
                            }
                        });
                    }
                    if (Array.isArray(reply.documents)) {
                        const offsets = { tech_spec: 0, requirement: 0 };
                        reply.documents.forEach(function (row) {
                            const kind = row.kind;
                            const ids = savedDocumentIds[kind];
                            if (!ids) return;
                            const localId = ids[offsets[kind]++];
                            const source = kind === 'tech_spec' ? self.techSpecFiles : self.lotDocuments;
                            const document = (source || []).find(function (entry) {
                                return entry.id === localId;
                            });
                            if (document) document.dbId = parseInt(row.id, 10) || 0;
                        });
                    }
                    if (Array.isArray(reply.criteria)) {
                        reply.criteria.forEach(function (row, index) {
                            if (self.criteria[index]) self.criteria[index].serverId = parseInt(row.id, 10) || 0;
                        });
                    }
                    return opts.silent ? false : (reply.message || 'Сохранено');
                }
            }).then(function (reply) {
                return !reply.error;
            });
        },

        requestPublish: function () {
            if (this.editingPublishedTender) return Promise.resolve(false);
            const self = this;
            return $.fRequest({
                url: '/api/buyer/tender/publish/',
                method: 'POST',
                showMessages: false,
                data: { id: self.tenderId },
                onSuccess: function (reply) {
                    self.clearPublishIssues();
                    self.closeCreate();
                    $.AlertManager.showSuccess(reply.message || 'Опубликовано');
                    return false;
                },
                onError: function (reply) {
                    const issues = Array.isArray(reply.issues) ? reply.issues : null;
                    if (issues && issues.length) {
                        self.applyPublishIssues(issues);
                        self.jumpToFirstPublishIssue(issues);
                    }
                    $.AlertManager.showError(
                        reply.message || 'Заполните обязательные поля перед публикацией'
                    );
                    return false;
                }
            }).then(function (reply) {
                return !reply.error;
            });
        },

        publish: function () {
            return this.saveDraft({ publish: !this.editingPublishedTender });
        }
    });

    $.Cabinet.registerPage('tender', {
        root: null,
        tabs: null,
        applicationTabs: null,
        tenderId: 0,
        activeTab: 'overview',
        listLoaded: false,
        applications: [],
        pricesVisible: 0,
        busy: false,
        currentApplicationId: 0,
        detailSubtab: 'lots',
        canStartReview: 0,
        reviewStarted: 0,
        rankingLoaded: false,
        ranking: null,
        participantsSearch: '',
        canSelectWinner: 0,
        winnerSelectionCompleted: 0,
        itemizedEnabled: 0,
        winnerLoaded: false,
        winnerState: null,
        winnerBusy: false,
        resultsUiMode: 'all',
        positionalDraft: {},

        init: function (root) {
            this.root = root;
            this.tenderId = parseInt(root.getAttribute('data-id') || '0', 10) || 0;
            this.activeTab = 'overview';
            this.listLoaded = false;
            this.applications = [];
            this.pricesVisible = 0;
            this.busy = false;
            this.currentApplicationId = 0;
            this.detailSubtab = 'lots';
            this.canStartReview = parseInt(root.getAttribute('data-can-start-review') || '0', 10) || 0;
            this.reviewStarted = parseInt(root.getAttribute('data-review-started') || '0', 10) || 0;
            this.rankingLoaded = false;
            this.ranking = null;
            this.evaluationSort = 'rank';
            this.evaluationCollapsed = {};
            this.evaluationOrder = [];
            this.evaluationDragging = 0;
            this.evaluationTemplates = null;
            this.evaluationTemplateBusy = false;
            this.participantsSearch = '';
            this.canSelectWinner = parseInt(root.getAttribute('data-can-select-winner') || '0', 10) || 0;
            this.winnerSelectionCompleted = parseInt(root.getAttribute('data-winner-selection-completed') || '0', 10) || 0;
            this.itemizedEnabled = parseInt(root.getAttribute('data-itemized-enabled') || '0', 10) || 0;
            this.winnerLoaded = false;
            this.winnerState = null;
            this.winnerBusy = false;
            this.resultsUiMode = 'all';
            this.positionalDraft = {};
            this.bindEvents();
            this.syncEvaluationChrome();
            this.syncResultsChrome();
            this.openFromQuery();
        },

        bindEvents: function () {
            const self = this;
            const $root = $(this.root);
            let legacyTab = '';
            try {
                legacyTab = String(new URLSearchParams(window.location.search || '').get('tab') || '');
            } catch (e) {
                legacyTab = '';
            }

            this.tabs = new TabsManager({
                container: $root.find('.js-tender-tabs'),
                defaultTab: this.isAllowedTab(legacyTab) ? legacyTab : 'overview',
                hashSync: true,
                classes: {
                    btn: '.tender-page__tabs .tab-btn',
                    content: '.tab-content',
                    active: 'selected'
                },
                contentScope: '.tabs-content',
                onChange: function ({ tab }) {
                    self.onTabChange(String(tab || 'overview'));
                }
            });

            this.applicationTabs = new TabsManager({
                container: $root.find('.js-tender-application-main'),
                contentScope: '.tender-application__panels',
                defaultTab: 'lots',
                hashSync: false,
                onChange: function ({ tab }) {
                    self.detailSubtab = String(tab || 'lots');
                }
            });

            $root.on('click', '.js-buyer-copy-address', function () {
                const button = this;
                const text = $(button).closest('.buyer-tender-overview__value-row')
                    .find('.js-buyer-copy-source').text().trim();
                self.copyToClipboard(text, button);
            });




            $root.on('click', '.js-tender-participant-card', function () {
                const id = parseInt(this.getAttribute('data-id') || '0', 10) || 0;
                if (id > 0) {
                    self.openApplication(id);
                }
            });

            $root.on('click', '.js-tender-participant-chat', function (event) {
                event.preventDefault();
                event.stopPropagation();
            });

            $root.on('click', '.js-tender-participants-back', function () {
                self.showListView();
            });

            $root.on('click', '.js-tender-application-decision-approve', function () {
                const gate = String(this.getAttribute('data-gate') || '');
                const code = String(this.getAttribute('data-code') || '');
                if (gate && code) {
                    self.submitDecision(gate, code, '');
                }
            });

            $root.on('click', '.js-tender-application-decision-reject-open', function () {
                const $card = $(this).closest('.js-tender-application-decision-card');
                $card.find('.js-tender-application-decision-reject-form').prop('hidden', false);
                $card.find('.js-tender-application-decision-reject-comment').trigger('focus');
            });

            $root.on('click', '.js-tender-application-decision-reject-cancel', function () {
                const $form = $(this).closest('.js-tender-application-decision-reject-form');
                $form.prop('hidden', true);
                $form.find('.js-tender-application-decision-reject-comment').val('');
            });

            $root.on('click', '.js-tender-application-decision-reject-confirm', function () {
                const $card = $(this).closest('.js-tender-application-decision-card');
                const gate = String($card.attr('data-gate') || '');
                const code = String(this.getAttribute('data-code') || '');
                const comment = String($card.find('.js-tender-application-decision-reject-comment').val() || '').trim();
                if (!gate || !code) {
                    return;
                }
                if (!comment) {
                    $card.find('.js-tender-application-decision-error')
                        .text('Укажите причину отклонения')
                        .prop('hidden', false);
                    return;
                }
                self.submitDecision(gate, code, comment);
            });

            $root.on('click', '.js-buyer-start-review', function () {
                self.startReview();
            });

            $root.off('.tenderEvaluation');
            $root.on('click.tenderEvaluation', '.js-tender-evaluation-proposal', function () {
                const id = parseInt(this.getAttribute('data-id') || '0', 10);
                if (id > 0) self.openApplication(id);
            });

            $root.on('click.tenderEvaluation', '.js-tender-evaluation-template-settings', function () {
                self.openEvaluationTemplateDialog(false);
            });
            $root.on('click.tenderEvaluation', '.js-tender-evaluation-template-delete', function () {
                self.openEvaluationTemplateDialog(true);
            });
            $root.on('click.tenderEvaluation', '.js-tender-evaluation-template-retry', function () {
                self.loadEvaluationTemplates();
            });
            $root.on('change.tenderEvaluation', '.js-tender-evaluation-template', function () {
                self.changeEvaluationTemplate('select', {id: this.value});
            });
            $root.on('click.tenderEvaluation', '.js-tender-evaluation-move', function () {
                const from = Number(this.getAttribute('data-id'));
                const index = self.evaluationOrder.indexOf(from);
                const target = self.evaluationOrder[index + Number(this.getAttribute('data-direction'))];
                self.moveEvaluationSupplier(from, target);
                const $buttons = $(self.root).find('.js-tender-evaluation-move[data-id="' + from + '"]');
                const $sameDirection = $buttons.filter('[data-direction="' + this.getAttribute('data-direction') + '"]:enabled');
                ($sameDirection.length ? $sameDirection : $buttons.filter(':enabled').first()).trigger('focus');
            });
            $root.on('dragstart.tenderEvaluation', '.js-tender-evaluation-drag', function (event) {
                self.evaluationDragging = Number(this.getAttribute('data-id'));
                event.originalEvent.dataTransfer.setData('text/plain', String(self.evaluationDragging));
                event.originalEvent.dataTransfer.effectAllowed = 'move';
            });
            $root.on('dragover.tenderEvaluation', '.js-tender-evaluation-column', function (event) {
                if (!self.evaluationDragging) return;
                event.preventDefault();
                event.originalEvent.dataTransfer.dropEffect = 'move';
                $(self.root).find('.is-evaluation-drop-target').removeClass('is-evaluation-drop-target');
                $(this).addClass('is-evaluation-drop-target');
            });
            $root.on('drop.tenderEvaluation', '.js-tender-evaluation-column', function (event) {
                if (!self.evaluationDragging) return;
                event.preventDefault();
                self.moveEvaluationSupplier(self.evaluationDragging, Number(this.getAttribute('data-id')));
                self.evaluationDragging = 0;
                $(self.root).find('.is-evaluation-drop-target').removeClass('is-evaluation-drop-target');
            });
            $root.on('dragend.tenderEvaluation', '.js-tender-evaluation-drag', function () {
                self.evaluationDragging = 0;
                $(self.root).find('.is-evaluation-drop-target').removeClass('is-evaluation-drop-target');
            });

            $root.on('change.tenderEvaluation', '.js-tender-evaluation-sort', function () {
                self.evaluationSort = this.value;
                self.renderRanking(self.ranking);
            });

            $root.on('click.tenderEvaluation', '.js-tender-evaluation-toggle', function () {
                const key = this.getAttribute('data-group');
                self.evaluationCollapsed[key] = !self.evaluationCollapsed[key];
                const open = !self.evaluationCollapsed[key];
                $(this).attr('aria-expanded', String(open));
                $(self.root).find('[data-evaluation-group="' + key + '"]').prop('hidden', !open);
            });

            $root.on('input', '.js-tender-participants-search', function () {
                self.participantsSearch = String($(this).val() || '');
                self.renderApplicationsList();
            });

            $root.on('click', '.js-buyer-results-mode-btn', function () {
                const mode = String(this.getAttribute('data-mode') || 'all');
                self.setResultsUiMode(mode);
            });

            $root.on('click', '.js-buyer-results-col-select', function (event) {
                event.preventDefault();
                const id = parseInt(this.getAttribute('data-id') || '0', 10) || 0;
                if (id > 0) {
                    self.selectWinnerAll(id);
                }
            });

            $root.on('keydown', '.js-buyer-results-col-select', function (event) {
                if (event.key !== 'Enter' && event.key !== ' ') {
                    return;
                }
                event.preventDefault();
                const id = parseInt(this.getAttribute('data-id') || '0', 10) || 0;
                if (id > 0) {
                    self.selectWinnerAll(id);
                }
            });

            $root.on('click', '.js-buyer-results-cell', function (event) {
                event.preventDefault();
                self.pickPositionalCell(this);
            });

            $root.on('keydown', '.js-buyer-results-cell', function (event) {
                if (event.key !== 'Enter' && event.key !== ' ') {
                    return;
                }
                event.preventDefault();
                self.pickPositionalCell(this);
            });

            $root.on('click', '.js-buyer-winner-complete', function () {
                self.openCompleteDialog();
            });

            $root.on('click', '.js-buyer-winner-finish-wo', function () {
                self.openFinishWithoutDialog();
            });
        },

        isAllowedTab: function (tab) {
            if (tab === 'overview' || tab === 'participants' || tab === 'evaluation') {
                return true;
            }
            if (tab === 'results') {
                return !!(this.reviewStarted || this.winnerSelectionCompleted);
            }
            return false;
        },

        copyToClipboard: function (text, button) {
            if (!text) {
                return;
            }
            const done = function () {
                const $button = $(button).addClass('is-done');
                window.setTimeout(function () {
                    $button.removeClass('is-done');
                }, 1500);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done).catch(function () {});
                return;
            }
            const $tmp = $('<textarea>').val(text).css({ position: 'fixed', opacity: 0 }).appendTo('body');
            $tmp[0].select();
            try {
                document.execCommand('copy');
                done();
            } catch (e) {
                // ignore
            }
            $tmp.remove();
        },

        openFromQuery: function () {
            let applicationId = 0;
            try {
                const params = new URLSearchParams(window.location.search || '');
                applicationId = parseInt(params.get('application') || '0', 10) || 0;
            } catch (e) {
                applicationId = 0;
            }
            if (applicationId > 0) {
                this.showTab('participants');
                this.openApplication(applicationId);
            }
        },

        showTab: function (tab) {
            const allowed = this.isAllowedTab(tab) ? tab : 'overview';
            if (this.tabs) {
                this.tabs.showTab(allowed);
            } else {
                this.onTabChange(allowed);
            }
        },

        onTabChange: function (tab) {
            if (!this.isAllowedTab(tab)) {
                return;
            }
            this.activeTab = tab;
            this.replaceQuery({
                tab: null,
                application: tab === 'participants' ? (this.currentApplicationId || null) : null
            });
            if (tab === 'participants' && !this.listLoaded) {
                this.loadApplications();
            }
            if (tab === 'evaluation') {
                if (!this.evaluationTemplates && !this.evaluationTemplateBusy) this.loadEvaluationTemplates();
                this.syncEvaluationChrome();
                if (this.reviewStarted && !this.rankingLoaded) {
                    this.loadRanking();
                }
            }
            if (tab === 'results') {
                this.loadWinnerState();
            }
        },

        replaceQuery: function (patch) {
            try {
                const url = new URL(window.location.href);
                Object.keys(patch || {}).forEach(function (key) {
                    const value = patch[key];
                    if (value === null || value === '' || value === undefined) {
                        url.searchParams.delete(key);
                    } else {
                        url.searchParams.set(key, String(value));
                    }
                });
                if (window.history && typeof window.history.replaceState === 'function') {
                    window.history.replaceState(null, '', url.pathname + url.search + url.hash);
                }
            } catch (e) {
                // ignore
            }
        },

        syncEvaluationChrome: function () {
            const $root = $(this.root);
            const started = !!this.reviewStarted;
            const canStart = !!this.canStartReview && !started;
            const $cta = $root.find('.js-buyer-start-review');
            const $started = $root.find('.js-buyer-evaluation-started');
            const $empty = $root.find('.js-buyer-evaluation-empty');
            const $ranking = $root.find('.js-buyer-evaluation-ranking');
            const $hint = $root.find('.js-buyer-evaluation-hint');

            $cta.prop('hidden', started);
            $cta.prop('disabled', !canStart);
            if (canStart) {
                $cta.removeAttr('title');
            } else if (!started) {
                $cta.attr('title', 'Доступно после окончания срока приёма заявок');
            }
            $started.prop('hidden', !started);
            $empty.prop('hidden', started);
            $ranking.prop('hidden', !(started && this.rankingLoaded && this.ranking));
            if (started) {
                $hint.text('Цены вскрыты. Клик по поставщику открывает карточку заявки на вкладке «Участники».');
            } else {
                $hint.text('После окончания приёма нажмите «Начать рассмотрение заявок», чтобы вскрыть цены и сравнить предложения.');
            }
        },

        applyReviewState: function (review) {
            if (!review || typeof review !== 'object') {
                return;
            }
            if (review.can_start !== undefined) {
                this.canStartReview = review.can_start ? 1 : 0;
                this.root.setAttribute('data-can-start-review', String(this.canStartReview));
            }
            if (review.is_started !== undefined) {
                this.reviewStarted = review.is_started ? 1 : 0;
                this.root.setAttribute('data-review-started', String(this.reviewStarted));
            }
            this.syncEvaluationChrome();
            this.syncResultsTabEnabled();
            this.syncResultsChrome();
        },

        syncResultsTabEnabled: function () {
            const enabled = !!(this.reviewStarted || this.winnerSelectionCompleted);
            const $tab = $(this.root).find('.js-tender-tabs .tab-btn[data-tab="results"]');
            const $link = $tab.find('[role="tab"], a');
            if (!$tab.length) {
                return;
            }
            $tab.toggleClass('is-disabled', !enabled);
            if (enabled) {
                $link.removeAttr('aria-disabled').removeAttr('title').removeAttr('tabindex');
            } else {
                $link.attr('aria-disabled', 'true')
                    .attr('title', 'Доступно после начала рассмотрения заявок')
                    .attr('tabindex', '-1');
            }
        },

        canEditWinner: function () {
            const state = this.winnerState;
            if (!state) {
                return !!this.canSelectWinner;
            }
            return !!(parseInt(state.can_edit, 10) || 0);
        },

        syncResultsChrome: function () {
            const $root = $(this.root);
            const started = !!(this.reviewStarted || this.winnerSelectionCompleted);
            const loaded = !!this.winnerLoaded && !!this.winnerState;
            const completed = !!this.winnerSelectionCompleted;
            const state = this.winnerState || {};
            const canEdit = this.canEditWinner() && !completed;
            const canComplete = !!(parseInt(state.can_complete, 10) || 0);
            const uiMode = this.resultsUiMode === 'positional' ? 'positional' : 'all';
            const itemized = !!this.itemizedEnabled;

            $root.find('.js-buyer-results-empty').prop('hidden', started);
            $root.find('.js-buyer-results-body').prop('hidden', !(started && loaded));

            $root.find('.js-buyer-winner-finish-wo').prop('hidden', !canEdit);
            $root.find('.js-buyer-winner-complete')
                .prop('hidden', !canEdit)
                .prop('disabled', !(canEdit && canComplete));

            $root.find('.js-buyer-results-method').prop('hidden', !(canEdit && itemized));
            $root.find('.js-buyer-results-mode-btn').removeClass('is-active');
            $root.find('.js-buyer-results-mode-btn[data-mode="' + uiMode + '"]').addClass('is-active');
        },

        ensureRankingLoaded: function () {
            const self = this;
            if (this.rankingLoaded && this.ranking) {
                return Promise.resolve(this.ranking);
            }
            return new Promise(function (resolve, reject) {
                $.fRequest({
                    url: '/api/buyer/tender/' + self.tenderId + '/ranking/',
                    method: 'GET',
                    showMessages: false,
                    onSuccess: function (reply) {
                        self.rankingLoaded = true;
                        self.ranking = reply;
                        self.renderRanking(reply);
                        self.syncEvaluationChrome();
                        resolve(reply);
                    },
                    onError: function (reply) {
                        reject(reply || {});
                    }
                }).catch(function (err) {
                    reject(err || {});
                });
            });
        },

        loadWinnerState: function () {
            const self = this;
            const $root = $(this.root);
            if (this.tenderId <= 0) {
                return;
            }
            if (!this.reviewStarted && !this.winnerSelectionCompleted) {
                this.syncResultsChrome();
                return;
            }

            $root.find('.js-buyer-results-error').prop('hidden', true).text('');
            $root.find('.js-buyer-results-loading').prop('hidden', false);
            $root.find('.js-buyer-results-body').prop('hidden', true);
            $root.find('.js-buyer-results-empty').prop('hidden', true);

            this.ensureRankingLoaded()
                .then(function () {
                    return new Promise(function (resolve, reject) {
                        $.fRequest({
                            url: '/api/buyer/tender/' + self.tenderId + '/winner/',
                            method: 'GET',
                            showMessages: false,
                            onSuccess: function (reply) {
                                resolve(reply);
                            },
                            onError: function (reply) {
                                reject(reply || {});
                            }
                        }).catch(function (err) {
                            reject(err || {});
                        });
                    });
                })
                .then(function (state) {
                    $root.find('.js-buyer-results-loading').prop('hidden', true);
                    self.applyWinnerState(state);
                    self.renderResults();
                    self.syncResultsChrome();
                })
                .catch(function (reply) {
                    $root.find('.js-buyer-results-loading').prop('hidden', true);
                    $root.find('.js-buyer-results-error')
                        .text((reply && reply.message) || 'Не удалось загрузить результаты')
                        .prop('hidden', false);
                });
        },

        applyWinnerState: function (state) {
            if (!state || typeof state !== 'object') {
                return;
            }
            const self = this;
            this.winnerLoaded = true;
            this.winnerState = state;

            if (state.itemized_enabled !== undefined) {
                this.itemizedEnabled = parseInt(state.itemized_enabled, 10) || 0;
                this.root.setAttribute('data-itemized-enabled', String(this.itemizedEnabled));
            }
            if (state.winner_selection_completed !== undefined) {
                this.winnerSelectionCompleted = parseInt(state.winner_selection_completed, 10) || 0;
                this.root.setAttribute(
                    'data-winner-selection-completed',
                    String(this.winnerSelectionCompleted)
                );
            }

            const canEdit = !!(parseInt(state.can_edit, 10) || 0);
            this.canSelectWinner = canEdit ? 1 : 0;
            this.root.setAttribute('data-can-select-winner', String(this.canSelectWinner));

            const mode = String(state.winner_selection_mode || 'none');
            if (mode === 'positional') {
                this.resultsUiMode = 'positional';
            } else if (mode === 'all') {
                this.resultsUiMode = 'all';
            } else if (this.resultsUiMode !== 'positional' && this.resultsUiMode !== 'all') {
                this.resultsUiMode = 'all';
            }

            this.positionalDraft = {};
            if (mode === 'positional' && state.awards && Array.isArray(state.awards.items)) {
                state.awards.items.forEach(function (row) {
                    if (!row) {
                        return;
                    }
                    const tid = parseInt(row.tender_item_id, 10) || 0;
                    const aid = parseInt(row.application_id, 10) || 0;
                    if (tid > 0 && aid > 0) {
                        self.positionalDraft[tid] = aid;
                    }
                });
            }

            this.syncResultsTabEnabled();
        },

        applyWinnerReply: function (reply) {
            const $root = $(this.root);
            let state = reply;
            if (reply && reply.winner && typeof reply.winner === 'object') {
                state = reply.winner;
            }
            this.applyWinnerState(state);

            if (reply && reply.tender && reply.tender.status && reply.tender.status.name) {
                $root.find('.js-buyer-tender-status').first()
                    .attr('class', 'badge badge--shared badge--medium js-buyer-tender-status ' + (reply.tender.status.tone || 'info'))
                    .text(String(reply.tender.status.name));
            }

            this.renderResults();
            this.syncResultsChrome();
        },

        setResultsUiMode: function (mode) {
            const self = this;
            if (mode !== 'all' && mode !== 'positional') {
                return;
            }
            if (mode === 'positional' && !this.itemizedEnabled) {
                return;
            }
            if (!this.canEditWinner()) {
                return;
            }

            const serverMode = String(
                (this.winnerState && this.winnerState.winner_selection_mode) || 'none'
            );
            if (serverMode !== 'none' && serverMode !== mode) {
                this.clearWinnerDraft(function () {
                    self.resultsUiMode = mode;
                    self.renderResults();
                    self.syncResultsChrome();
                });
                return;
            }

            this.resultsUiMode = mode;
            this.renderResults();
            this.syncResultsChrome();
        },

        winnerSummaryForDialog: function () {
            const self = this;
            const state = this.winnerState || {};
            const rows = this.mergeResultsRows();
            const awards = state.awards || {};
            const mode = String(state.winner_selection_mode || 'none');
            const currency = (this.ranking && this.ranking.currency)
                ? String(this.ranking.currency)
                : 'RUB';
            const supplierName = function (applicationId) {
                let name = 'Заявка #' + applicationId;
                rows.forEach(function (row) {
                    if (parseInt(row.application_id, 10) === applicationId) {
                        const supplier = row.supplier || {};
                        name = String(supplier.fullname || supplier.name || name);
                    }
                });
                return name;
            };

            const info = {
                mode: mode,
                method: mode === 'positional' ? 'Попозиционный выбор' : 'Единый поставщик',
                proposals: rows.length,
                winnerName: '',
                winnerTotal: '',
                lines: []
            };

            if (mode === 'positional') {
                const positions = this.collectNoticePositions();
                const byTid = {};
                (Array.isArray(awards.items) ? awards.items : []).forEach(function (item) {
                    const tid = parseInt(item && item.tender_item_id, 10) || 0;
                    const aid = parseInt(item && item.application_id, 10) || 0;
                    if (tid > 0 && aid > 0) {
                        byTid[tid] = aid;
                    }
                });
                positions.forEach(function (pos) {
                    const aid = byTid[pos.tender_item_id] || 0;
                    if (aid > 0) {
                        info.lines.push({
                            name: pos.name,
                            value: supplierName(aid)
                        });
                    }
                });
                return info;
            }

            const winnerId = parseInt(awards.application_id, 10) || 0;
            if (winnerId > 0) {
                info.winnerName = supplierName(winnerId);
                rows.forEach(function (row) {
                    if (parseInt(row.application_id, 10) === winnerId && row.total !== null && row.total !== undefined) {
                        info.winnerTotal = self.formatMoney(row.total, currency);
                    }
                });
            }
            return info;
        },

        openCompleteDialog: function () {
            const self = this;
            const state = this.winnerState || {};
            if (!this.canEditWinner() || !(parseInt(state.can_complete, 10) || 0)) {
                return;
            }
            if (!$.DialogManager) {
                this.completeWinnerSelection();
                return;
            }

            const info = this.winnerSummaryForDialog();
            let rowsHtml = '<div class="buyer-winner-dialog__row">'
                + '<span class="buyer-winner-dialog__label">Метод выбора</span>'
                + '<span class="buyer-winner-dialog__value">' + this.escapeHtml(info.method) + '</span>'
                + '</div>';
            if (info.winnerName) {
                rowsHtml += '<div class="buyer-winner-dialog__row">'
                    + '<span class="buyer-winner-dialog__label">Победитель</span>'
                    + '<span class="buyer-winner-dialog__value">' + this.escapeHtml(info.winnerName)
                    + (info.winnerTotal ? ' — ' + this.escapeHtml(info.winnerTotal) : '')
                    + '</span></div>';
            }
            if (info.lines.length) {
                let itemsHtml = '';
                info.lines.forEach(function (line) {
                    itemsHtml += '<li class="buyer-winner-dialog__item">'
                        + '<span class="buyer-winner-dialog__item-name">' + self.escapeHtml(line.name) + '</span>'
                        + '<span class="buyer-winner-dialog__item-value">' + self.escapeHtml(line.value) + '</span>'
                        + '</li>';
                });
                rowsHtml += '<div class="buyer-winner-dialog__row buyer-winner-dialog__row--stacked">'
                    + '<span class="buyer-winner-dialog__label">Победители по позициям</span>'
                    + '<ul class="buyer-winner-dialog__items">' + itemsHtml + '</ul>'
                    + '</div>';
            }
            rowsHtml += '<div class="buyer-winner-dialog__row">'
                + '<span class="buyer-winner-dialog__label">Рассмотрено предложений</span>'
                + '<span class="buyer-winner-dialog__value">' + String(info.proposals) + '</span>'
                + '</div>';

            const dialog = new $.DialogManager({
                title: 'Завершить выбор победителя?',
                width: '560px',
                showConfirmButton: false,
                showCancelButton: false,
                content: '<div class="buyer-winner-dialog">'
                    + '<p class="buyer-winner-dialog__text">'
                    + 'После завершения итоги станут доступны поставщикам, изменить выбор будет нельзя.'
                    + '</p>'
                    + '<div class="buyer-winner-dialog__summary">' + rowsHtml + '</div>'
                    + '</div>'
                    + '<div class="buyer-winner-dialog__footer">'
                    + '<button type="button" class="button secondary large modal-cancel">Отмена</button>'
                    + '<button type="button" class="button primary large js-buyer-winner-complete-confirm">Подтвердить</button>'
                    + '</div>',
                onOpen: function ($container) {
                    $container.find('.js-buyer-winner-complete-confirm').on('click', function () {
                        dialog.close();
                        self.completeWinnerSelection();
                    });
                }
            });
        },

        openFinishWithoutDialog: function () {
            const self = this;
            if (!this.canEditWinner()) {
                return;
            }
            if (!$.DialogManager) {
                return;
            }

            const dialog = new $.DialogManager({
                title: 'Завершить без победителя?',
                width: '560px',
                showConfirmButton: false,
                showCancelButton: false,
                content: '<div class="buyer-winner-dialog">'
                    + '<p class="buyer-winner-dialog__warning">'
                    + 'Процедура будет закрыта без победителя. Действие необратимо,'
                    + ' поставщики увидят, что закупка завершена без выбора.'
                    + '</p>'
                    + '<label class="buyer-winner-dialog__label" for="buyer-finish-wo-reason">'
                    + 'Причина завершения без победителя'
                    + '</label>'
                    + '<textarea id="buyer-finish-wo-reason" rows="4"'
                    + ' class="input buyer-winner-dialog__input js-buyer-finish-wo-reason"'
                    + ' placeholder="Например: все предложения превысили начальную цену"></textarea>'
                    + '<p class="buyer-winner-dialog__error js-buyer-finish-wo-error" hidden></p>'
                    + '</div>'
                    + '<div class="buyer-winner-dialog__footer">'
                    + '<button type="button" class="button secondary large modal-cancel">Отмена</button>'
                    + '<button type="button" class="button destruct large js-buyer-finish-wo-confirm">Завершить</button>'
                    + '</div>',
                onOpen: function ($container) {
                    $container.find('.js-buyer-finish-wo-reason').trigger('focus');
                    $container.find('.js-buyer-finish-wo-confirm').on('click', function () {
                        const reason = String($container.find('.js-buyer-finish-wo-reason').val() || '').trim();
                        if (!reason) {
                            $container.find('.js-buyer-finish-wo-error')
                                .text('Укажите причину завершения без победителя')
                                .prop('hidden', false);
                            return;
                        }
                        dialog.close();
                        self.finishWithoutWinner(reason);
                    });
                }
            });
        },

        winnerRequest: function (path, data, okMessage) {
            const self = this;
            const $root = $(this.root);
            if (this.tenderId <= 0 || this.winnerBusy) {
                return;
            }
            this.winnerBusy = true;
            $root.find('.js-buyer-results-error').prop('hidden', true).text('');

            $.fRequest({
                url: '/api/buyer/tender/' + this.tenderId + '/winner/' + path,
                method: 'POST',
                showMessages: true,
                data: data || {},
                onSuccess: function (reply) {
                    self.winnerBusy = false;
                    self.applyWinnerReply(reply);
                },
                onError: function (reply) {
                    self.winnerBusy = false;
                    $root.find('.js-buyer-results-error')
                        .text((reply && reply.message) || okMessage || 'Не удалось выполнить действие')
                        .prop('hidden', false);
                    self.syncResultsChrome();
                }
            }).catch(function () {
                self.winnerBusy = false;
                $root.find('.js-buyer-results-error')
                    .text(okMessage || 'Не удалось выполнить действие')
                    .prop('hidden', false);
                self.syncResultsChrome();
            });
        },

        selectWinnerAll: function (applicationId) {
            const id = parseInt(applicationId, 10) || 0;
            if (id <= 0 || !this.canEditWinner()) {
                return;
            }
            this.winnerRequest('select/', {
                mode: 'all',
                application_id: id
            }, 'Не удалось выбрать победителя');
        },

        pickPositionalCell: function (cell) {
            if (!cell || !this.canEditWinner() || this.resultsUiMode !== 'positional') {
                return;
            }
            const tid = parseInt(cell.getAttribute('data-tender-item-id') || '0', 10) || 0;
            const aid = parseInt(cell.getAttribute('data-id') || '0', 10) || 0;
            if (tid <= 0 || aid <= 0) {
                return;
            }
            if ((parseInt(this.positionalDraft[tid], 10) || 0) === aid) {
                delete this.positionalDraft[tid];
            } else {
                this.positionalDraft[tid] = aid;
            }
            this.renderResults();
            this.syncResultsChrome();
            this.applyPositionalSelection();
        },

        applyPositionalSelection: function () {
            if (!this.canEditWinner() || this.resultsUiMode !== 'positional') {
                return;
            }
            const items = [];
            const draft = this.positionalDraft || {};
            Object.keys(draft).forEach(function (tidKey) {
                const tid = parseInt(tidKey, 10) || 0;
                const aid = parseInt(draft[tidKey], 10) || 0;
                if (tid > 0 && aid > 0) {
                    items.push({
                        tender_item_id: tid,
                        application_id: aid
                    });
                }
            });
            this.winnerRequest('select/', {
                mode: 'positional',
                items: items
            }, 'Не удалось сохранить попозиционный выбор');
        },

        clearWinnerDraft: function (onDone) {
            const self = this;
            const $root = $(this.root);
            if (this.tenderId <= 0 || this.winnerBusy || !this.canEditWinner()) {
                return;
            }
            this.winnerBusy = true;
            $root.find('.js-buyer-results-error').prop('hidden', true).text('');

            $.fRequest({
                url: '/api/buyer/tender/' + this.tenderId + '/winner/clear/',
                method: 'POST',
                showMessages: true,
                data: {},
                onSuccess: function (reply) {
                    self.winnerBusy = false;
                    self.applyWinnerReply(reply);
                    if (typeof onDone === 'function') {
                        onDone();
                    }
                },
                onError: function (reply) {
                    self.winnerBusy = false;
                    $root.find('.js-buyer-results-error')
                        .text((reply && reply.message) || 'Не удалось сбросить выбор')
                        .prop('hidden', false);
                    self.syncResultsChrome();
                }
            }).catch(function () {
                self.winnerBusy = false;
                $root.find('.js-buyer-results-error')
                    .text('Не удалось сбросить выбор')
                    .prop('hidden', false);
                self.syncResultsChrome();
            });
        },

        completeWinnerSelection: function () {
            if (!this.canEditWinner()) {
                return;
            }
            const state = this.winnerState || {};
            if (!(parseInt(state.can_complete, 10) || 0)) {
                return;
            }
            this.winnerRequest('complete/', {}, 'Не удалось завершить выбор победителя');
        },

        finishWithoutWinner: function (reason) {
            if (!this.canEditWinner()) {
                return;
            }
            const text = String(reason || '').trim();
            if (!text) {
                return;
            }
            this.winnerRequest('finish-without/', { reason: text }, 'Не удалось завершить без победителя');
        },

        mergeResultsRows: function () {
            const rankingApps = (this.ranking && Array.isArray(this.ranking.applications))
                ? this.ranking.applications
                : [];
            const eligible = (this.winnerState && Array.isArray(this.winnerState.eligible))
                ? this.winnerState.eligible
                : [];
            const byId = {};

            rankingApps.forEach(function (row) {
                if (!row) {
                    return;
                }
                const id = parseInt(row.application_id, 10) || 0;
                if (id > 0) {
                    byId[id] = Object.assign({}, row);
                }
            });

            eligible.forEach(function (row) {
                if (!row) {
                    return;
                }
                const id = parseInt(row.application_id, 10) || 0;
                if (id <= 0) {
                    return;
                }
                if (!byId[id]) {
                    byId[id] = {
                        application_id: id,
                        supplier: row.supplier || {},
                        admission_status: row.admission_status,
                        total: null,
                        rank: null,
                        items: []
                    };
                }
                byId[id].is_winner = parseInt(row.is_winner, 10) || 0;
                if (row.supplier) {
                    byId[id].supplier = row.supplier;
                }
                if (row.admission_status) {
                    byId[id].admission_status = row.admission_status;
                }
            });

            const ordered = [];
            const seen = {};
            rankingApps.forEach(function (row) {
                const id = parseInt(row && row.application_id, 10) || 0;
                if (id > 0 && byId[id] && !seen[id]) {
                    ordered.push(byId[id]);
                    seen[id] = true;
                }
            });
            eligible.forEach(function (row) {
                const id = parseInt(row && row.application_id, 10) || 0;
                if (id > 0 && byId[id] && !seen[id]) {
                    ordered.push(byId[id]);
                    seen[id] = true;
                }
            });
            return ordered;
        },

        collectNoticePositions: function () {
            const byTid = {};
            const apps = (this.ranking && Array.isArray(this.ranking.applications))
                ? this.ranking.applications
                : [];
            apps.forEach(function (row) {
                if (!row || !Array.isArray(row.items)) {
                    return;
                }
                row.items.forEach(function (item) {
                    if (!item) {
                        return;
                    }
                    const tid = parseInt(item.tender_item_id, 10) || 0;
                    if (tid <= 0 || byTid[tid]) {
                        return;
                    }
                    byTid[tid] = {
                        tender_item_id: tid,
                        name: String(item.name || 'Позиция'),
                        sort: parseInt(item.sort, 10) || 0
                    };
                });
            });
            const awards = this.winnerState && this.winnerState.awards;
            if (awards && Array.isArray(awards.items)) {
                awards.items.forEach(function (item) {
                    if (!item) {
                        return;
                    }
                    const tid = parseInt(item.tender_item_id, 10) || 0;
                    if (tid <= 0) {
                        return;
                    }
                    if (!byTid[tid]) {
                        byTid[tid] = {
                            tender_item_id: tid,
                            name: String(item.name || 'Позиция'),
                            sort: tid
                        };
                    }
                });
            }
            return Object.keys(byTid).map(function (k) {
                return byTid[k];
            }).sort(function (a, b) {
                return (a.sort - b.sort) || (a.tender_item_id - b.tender_item_id);
            });
        },

        renderResults: function () {
            const self = this;
            const $root = $(this.root);
            const state = this.winnerState;
            if (!state) {
                return;
            }

            const rows = this.mergeResultsRows();
            const currency = (this.ranking && this.ranking.currency)
                ? String(this.ranking.currency)
                : 'RUB';
            const itemized = !!(this.ranking && parseInt(this.ranking.itemized_enabled, 10))
                || !!this.itemizedEnabled;
            const awards = state.awards || {};
            let winnerAppId = parseInt(awards.application_id, 10) || 0;
            if (!winnerAppId) {
                rows.forEach(function (row) {
                    if (parseInt(row && row.is_winner, 10)) {
                        winnerAppId = parseInt(row.application_id, 10) || winnerAppId;
                    }
                });
            }
            const mode = String(state.winner_selection_mode || 'none');
            const completed = !!(parseInt(state.winner_selection_completed, 10) || 0);
            const finishedWo = !!(parseInt(state.finished_without_winner, 10) || 0);
            const canEdit = this.canEditWinner() && !completed;
            const uiMode = this.resultsUiMode === 'positional' ? 'positional' : 'all';
            const positions = this.collectNoticePositions();
            const assignedCount = mode === 'all' && winnerAppId > 0
                ? positions.length
                : (Array.isArray(awards.items) ? awards.items.length : 0);
            // В UI positional считаем черновик селектов; иначе — awards с сервера.
            let selectedPositions = assignedCount;
            if (uiMode === 'positional') {
                selectedPositions = 0;
                Object.keys(this.positionalDraft || {}).forEach(function (tidKey) {
                    if ((parseInt(self.positionalDraft[tidKey], 10) || 0) > 0) {
                        selectedPositions += 1;
                    }
                });
                if (!canEdit && Array.isArray(awards.items)) {
                    selectedPositions = awards.items.length;
                }
            }
            const totalPositions = positions.length;
            const remainingPositions = Math.max(0, totalPositions - selectedPositions);

            $root.find('.js-buyer-results-proposals').text(String(rows.length));
            const showPosStats = uiMode === 'positional' && totalPositions > 0;
            $root.find('.js-buyer-results-stat-selected').prop('hidden', !showPosStats);
            $root.find('.js-buyer-results-stat-remaining').prop('hidden', !showPosStats);
            if (showPosStats) {
                $root.find('.js-buyer-results-selected')
                    .text(String(selectedPositions) + ' из ' + String(totalPositions));
                $root.find('.js-buyer-results-remaining').text(String(remainingPositions));
            }

            const $unassigned = $root.find('.js-buyer-results-unassigned');
            const pending = [];
            if (showPosStats && canEdit) {
                positions.forEach(function (pos, index) {
                    if (!(parseInt(self.positionalDraft[pos.tender_item_id], 10) || 0)) {
                        pending.push(index + 1);
                    }
                });
            }
            if (pending.length) {
                $unassigned
                    .text('Требуют решения: поз. ' + pending.join(', '))
                    .prop('hidden', false);
            } else {
                $unassigned.prop('hidden', true).text('');
            }

            const $badge = $root.find('.js-buyer-results-badge');
            const $winner = $root.find('.js-buyer-results-winner');
            const $finished = $root.find('.js-buyer-results-finished-wo');
            $badge.prop('hidden', true).text('').removeClass('is-draft is-done');
            $winner.prop('hidden', true);
            $finished.prop('hidden', true);

            let winnerName = '';
            let winnerTotal = '';
            if (winnerAppId > 0) {
                winnerName = 'Заявка #' + winnerAppId;
                rows.forEach(function (row) {
                    if (parseInt(row.application_id, 10) !== winnerAppId) {
                        return;
                    }
                    const supplier = row.supplier || {};
                    winnerName = String(supplier.fullname || supplier.name || winnerName);
                    if (row.total !== null && row.total !== undefined) {
                        winnerTotal = self.formatMoney(row.total, currency);
                    }
                });
            }
            const $winnerTotal = $root.find('.js-buyer-results-winner-total');
            if (winnerTotal) {
                $winnerTotal.text('Итого ' + winnerTotal).prop('hidden', false);
            } else {
                $winnerTotal.prop('hidden', true).text('');
            }

            if (finishedWo) {
                $badge.addClass('is-done').text('Без победителя').prop('hidden', false);
                $finished.prop('hidden', false);
                $root.find('.js-buyer-results-finished-reason')
                    .text(String(state.finished_without_winner_reason || '—'));
            } else if (completed) {
                $badge.addClass('is-done').text('Выбор победителей завершён').prop('hidden', false);
                if (mode === 'all' && winnerName) {
                    $winner.prop('hidden', false);
                    $root.find('.js-buyer-results-winner-name').text(winnerName);
                }
            } else if (mode === 'all' && winnerAppId > 0) {
                $badge.addClass('is-done').text('Все позиции распределены').prop('hidden', false);
                $winner.prop('hidden', false);
                $root.find('.js-buyer-results-winner-name').text(winnerName);
            } else if (mode === 'positional' || uiMode === 'positional') {
                if (selectedPositions <= 0) {
                    $badge.addClass('is-draft').text('Победители ещё не выбраны').prop('hidden', false);
                } else if (totalPositions > 0 && selectedPositions < totalPositions) {
                    $badge.addClass('is-draft').text('Выбор победителей продолжается').prop('hidden', false);
                } else {
                    $badge.addClass('is-done').text('Все позиции распределены').prop('hidden', false);
                }
            } else {
                $badge.addClass('is-draft').text('Победители ещё не выбраны').prop('hidden', false);
            }

            this.renderResultsTable({
                rows: rows,
                positions: positions,
                currency: currency,
                itemized: itemized,
                winnerAppId: winnerAppId,
                uiMode: uiMode,
                canEdit: canEdit
            });
        },

        renderResultsTable: function (options) {
            const self = this;
            const $wrap = $(this.root).find('.js-buyer-results-table-wrap');
            if (!$wrap.length) {
                return;
            }
            const rows = options.rows || [];
            const positions = options.positions || [];
            const currency = options.currency || 'RUB';
            const itemized = !!options.itemized;
            const uiMode = options.uiMode === 'positional' ? 'positional' : 'all';
            const canEdit = !!options.canEdit;
            const winnerAppId = parseInt(options.winnerAppId, 10) || 0;

            $wrap.empty();
            if (!rows.length) {
                $wrap.html(
                    '<p class="buyer-results-table__empty">Нет допущенных предложений для выбора.</p>'
                );
                return;
            }

            // Строки таблицы — позиции извещения; для непозиционной закупки одна строка «Предложение».
            const bodyRows = (itemized && positions.length)
                ? positions
                : [{ tender_item_id: 0, name: 'Предложение целиком', sort: 0 }];

            const priceByApp = {};
            rows.forEach(function (row) {
                const aid = parseInt(row.application_id, 10) || 0;
                const map = {};
                if (Array.isArray(row.items)) {
                    row.items.forEach(function (item) {
                        const tid = parseInt(item && item.tender_item_id, 10) || 0;
                        if (tid > 0) {
                            map[tid] = item;
                        }
                    });
                }
                priceByApp[aid] = map;
            });

            let head = '<tr><th scope="col" class="buyer-results-table__corner">Позиция</th>';
            rows.forEach(function (row) {
                const aid = parseInt(row.application_id, 10) || 0;
                const supplier = row.supplier || {};
                const name = String(supplier.fullname || supplier.name || ('Заявка #' + aid));
                const isWinner = winnerAppId > 0 && aid === winnerAppId;
                const clickable = canEdit && uiMode === 'all';
                const total = (row.total !== null && row.total !== undefined)
                    ? self.formatMoney(row.total, currency)
                    : '—';

                head += '<th scope="col" class="buyer-results-table__supplier'
                    + (isWinner ? ' is-winner' : '') + '">'
                    + '<span class="buyer-results-table__supplier-name">' + self.escapeHtml(name) + '</span>'
                    + '<span class="buyer-results-table__supplier-total">' + self.escapeHtml(total) + '</span>';
                if (isWinner) {
                    head += '<span class="badge badge--shared badge--small success">Победитель</span>';
                } else if (clickable) {
                    head += '<button type="button" class="button secondary small js-buyer-results-col-select"'
                        + ' data-id="' + aid + '">Выбрать по всем позициям</button>';
                }
                head += '</th>';
            });
            head += '</tr>';

            let body = '';
            bodyRows.forEach(function (pos, index) {
                const tid = parseInt(pos.tender_item_id, 10) || 0;
                const picked = tid > 0 ? (parseInt(self.positionalDraft[tid], 10) || 0) : 0;
                const label = (itemized && positions.length)
                    ? (String(index + 1) + '. ' + pos.name)
                    : pos.name;

                body += '<tr><th scope="row" class="buyer-results-table__position">'
                    + self.escapeHtml(label) + '</th>';

                rows.forEach(function (row) {
                    const aid = parseInt(row.application_id, 10) || 0;
                    const item = tid > 0 ? (priceByApp[aid] || {})[tid] : null;
                    const hasPrice = tid > 0
                        ? !!item
                        : (row.total !== null && row.total !== undefined);
                    const isColWinner = winnerAppId > 0 && aid === winnerAppId;
                    const isPicked = uiMode === 'positional' && tid > 0 && picked === aid;
                    const selectable = canEdit && uiMode === 'positional' && tid > 0 && hasPrice;

                    let priceHtml = '<span class="buyer-results-table__empty-cell">—</span>';
                    if (hasPrice) {
                        const perUnit = item
                            ? self.formatMoney(item.price_per_unit, currency)
                            : self.formatMoney(row.total, currency);
                        const lineTotal = item
                            ? self.formatMoney(item.line_total, currency)
                            : '';
                        priceHtml = '<span class="buyer-results-table__price">' + self.escapeHtml(perUnit) + '</span>';
                        if (lineTotal) {
                            priceHtml += '<span class="buyer-results-table__line-total">'
                                + self.escapeHtml(lineTotal) + '</span>';
                        }
                    }

                    const classes = ['buyer-results-table__cell'];
                    if (isColWinner && uiMode === 'all') {
                        classes.push('is-winner');
                    }
                    if (isPicked) {
                        classes.push('is-selected');
                    }
                    if (selectable) {
                        classes.push('is-selectable', 'js-buyer-results-cell');
                    }

                    body += '<td class="' + classes.join(' ') + '"'
                        + (selectable
                            ? ' role="button" tabindex="0" data-id="' + aid + '"'
                                + ' data-tender-item-id="' + tid + '"'
                                + ' aria-pressed="' + (isPicked ? 'true' : 'false') + '"'
                            : '')
                        + '>' + priceHtml + '</td>';
                });

                body += '</tr>';
            });

            $wrap.html(
                '<div class="buyer-results-table__scroll">'
                + '<table class="buyer-results-table__table">'
                + '<thead>' + head + '</thead>'
                + '<tbody>' + body + '</tbody>'
                + '</table></div>'
            );
        },

        startReview: function () {
            const self = this;
            const $root = $(this.root);
            if (this.tenderId <= 0 || this.busy || this.reviewStarted || !this.canStartReview) {
                return;
            }

            this.busy = true;
            $root.find('.js-buyer-evaluation-error').prop('hidden', true).text('');
            $root.find('.js-buyer-start-review').prop('disabled', true);

            $.fRequest({
                url: '/api/buyer/tender/' + this.tenderId + '/start-review/',
                method: 'POST',
                showMessages: true,
                data: {},
                onSuccess: function (reply) {
                    self.busy = false;
                    self.applyReviewState(reply.tender && reply.tender.review);
                    if (reply.tender && reply.tender.status && reply.tender.status.name) {
                        $root.find('.js-buyer-tender-status').first()
                            .attr('class', 'badge badge--shared badge--medium js-buyer-tender-status ' + (reply.tender.status.tone || 'info'))
                    .text(String(reply.tender.status.name));
                    }
                    if (reply.ranking) {
                        self.rankingLoaded = true;
                        self.ranking = reply.ranking;
                        self.renderRanking(reply.ranking);
                        self.syncEvaluationChrome();
                    } else {
                        self.loadRanking();
                    }
                },
                onError: function (reply) {
                    self.busy = false;
                    self.syncEvaluationChrome();
                    $root.find('.js-buyer-evaluation-error')
                        .text((reply && reply.message) || 'Не удалось начать рассмотрение')
                        .prop('hidden', false);
                }
            }).catch(function () {
                self.busy = false;
                self.syncEvaluationChrome();
                $root.find('.js-buyer-evaluation-error')
                    .text('Не удалось начать рассмотрение')
                    .prop('hidden', false);
            });
        },

        loadRanking: function () {
            const self = this;
            const $root = $(this.root);
            if (this.tenderId <= 0) {
                return;
            }

            $root.find('.js-buyer-evaluation-error').prop('hidden', true).text('');
            $root.find('.js-buyer-evaluation-loading').prop('hidden', false);
            $root.find('.js-buyer-evaluation-ranking').prop('hidden', true);

            $.fRequest({
                url: '/api/buyer/tender/' + this.tenderId + '/ranking/',
                method: 'GET',
                showMessages: false,
                onSuccess: function (reply) {
                    self.rankingLoaded = true;
                    self.ranking = reply;
                    $root.find('.js-buyer-evaluation-loading').prop('hidden', true);
                    self.renderRanking(reply);
                    self.syncEvaluationChrome();
                },
                onError: function (reply) {
                    $root.find('.js-buyer-evaluation-loading').prop('hidden', true);
                    $root.find('.js-buyer-evaluation-error')
                        .text((reply && reply.message) || 'Не удалось загрузить сравнение')
                        .prop('hidden', false);
                }
            }).catch(function () {
                $root.find('.js-buyer-evaluation-loading').prop('hidden', true);
                $root.find('.js-buyer-evaluation-error')
                    .text('Не удалось загрузить сравнение')
                    .prop('hidden', false);
            });
        },

        formatMoney: function (value, currency) {
            const amount = Number(value);
            const cur = String(currency || 'RUB');
            const suffix = cur === 'RUB' || cur === '₽' ? ' ₽' : (' ' + cur);
            if (!isFinite(amount)) {
                return '—';
            }
            return amount.toLocaleString('ru-RU', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }) + suffix;
        },

        loadEvaluationTemplates: function () {
            const self = this;
            if (this.evaluationTemplateBusy) return;
            this.evaluationTemplateBusy = true;
            this.renderEvaluationTemplateControls();
            $(this.root).find('.js-tender-evaluation-template-error').prop('hidden', true);
            $.fRequest({
                url: '/api/buyer/evaluation-template/list/',
                method: 'GET',
                showMessages: false,
                onSuccess: function (state) {
                    self.evaluationTemplateBusy = false;
                    self.evaluationTemplates = state;
                    self.renderEvaluationTemplateControls();
                },
                onError: function () {
                    self.evaluationTemplateBusy = false;
                    self.renderEvaluationTemplateControls();
                    $(self.root).find('.js-tender-evaluation-template-error').prop('hidden', false);
                }
            }).catch(function () {
                self.evaluationTemplateBusy = false;
                self.renderEvaluationTemplateControls();
                $(self.root).find('.js-tender-evaluation-template-error').prop('hidden', false);
            });
        },

        renderEvaluationTemplateControls: function () {
            const $root = $(this.root);
            const state = this.evaluationTemplates;
            const disabled = this.evaluationTemplateBusy || !state;
            const $select = $root.find('.js-tender-evaluation-template');
            if (state) {
                $select.empty();
                state.templates.forEach(function (template) {
                    $select.append($('<option>').val(template.id).text(template.name));
                });
                $select.val(state.selected_id);
            }
            $select.prop('disabled', disabled);
            $root.find('.js-tender-evaluation-template-settings').prop('disabled', disabled);
            $root.find('.js-tender-evaluation-template-delete').prop('disabled', disabled || !state || state.templates.length <= 1);
        },

        changeEvaluationTemplate: function (action, data, onSuccess, onError) {
            const self = this;
            if (this.evaluationTemplateBusy) return;
            this.evaluationTemplateBusy = true;
            this.renderEvaluationTemplateControls();
            const fail = function (reply) {
                self.evaluationTemplateBusy = false;
                self.renderEvaluationTemplateControls();
                if (onError) onError(reply && reply.message || 'Не удалось сохранить шаблон');
                else $(self.root).find('.js-tender-evaluation-template-error').prop('hidden', false);
            };
            $.fRequest({
                url: '/api/buyer/evaluation-template/' + action + '/',
                method: 'POST',
                data: data,
                showMessages: false,
                onSuccess: function (state) {
                    self.evaluationTemplateBusy = false;
                    self.evaluationTemplates = state;
                    self.renderEvaluationTemplateControls();
                    $(self.root).find('.js-tender-evaluation-template-error').prop('hidden', true);
                    if (onSuccess) onSuccess();
                },
                onError: fail
            }).catch(fail);
        },

        openEvaluationTemplateDialog: function (remove) {
            const self = this;
            if (!this.evaluationTemplates || this.evaluationTemplateBusy) return;
            const template = this.evaluationTemplates.templates.find(function (item) {
                return item.id === self.evaluationTemplates.selected_id;
            });
            if (!template || (remove && this.evaluationTemplates.templates.length <= 1)) return;
            const fields = [
                ['includeSpecification', 'Стоимость полной спецификации'],
                ['includePositions', 'Позиции'],
                ['includeDocuments', 'Поданные документы'],
                ['includeRanks', 'Ранги по стоимости'],
                ['includeAnalogs', 'Аналоги по позициям']
            ];
            let content = '<form class="tender-evaluation-template-form">';
            if (remove) {
                content += '<p>Шаблон «' + this.escapeHtml(template.name) + '» будет удалён.</p>';
            } else {
                content += '<label class="tender-evaluation-template-form__name">Название шаблона'
                    + '<input class="input" name="name" maxlength="120" required></label>'
                    + '<fieldset><legend>Разделы таблицы в файле</legend>';
                fields.forEach(function (field) {
                    content += '<label><input type="checkbox" name="' + field[0] + '"> ' + field[1] + '</label>';
                });
                content += '</fieldset>';
            }
            content += '<p class="tender-evaluation__error js-template-error" role="alert" hidden></p>'
                + '<div class="tender-evaluation-template-form__actions"><button type="button" class="button secondary modal-cancel">Отменить</button>'
                + '<button type="submit" class="button ' + (remove ? 'destruct' : 'primary') + '">' + (remove ? 'Удалить' : 'Сохранить') + '</button></div></form>';
            const dialog = new $.DialogManager({
                title: remove ? 'Удалить шаблон?' : 'Настройка шаблона',
                width: 560,
                content: content,
                onOpen: function ($container) {
                    const $form = $container.find('form');
                    $form.find('[name="name"]').val(template.name);
                    fields.forEach(function (field) {
                        $form.find('[name="' + field[0] + '"]').prop('checked', !!template.settings[field[0]]);
                    });
                    $form.on('submit', function (event) {
                        event.preventDefault();
                        const data = {id: template.id};
                        if (!remove) {
                            data.name = String($form.find('[name="name"]').val() || '').trim();
                            data.settings = {};
                            fields.forEach(function (field) {
                                data.settings[field[0]] = $form.find('[name="' + field[0] + '"]').prop('checked') ? 1 : 0;
                            });
                        }
                        const $button = $form.find('[type="submit"]');
                        $button.prop('disabled', true);
                        $form.find('.js-template-error').prop('hidden', true);
                        self.changeEvaluationTemplate(remove ? 'delete' : 'save', data, function () {
                            dialog.close();
                        }, function (message) {
                            $button.prop('disabled', false);
                            $form.find('.js-template-error').text(message).prop('hidden', false);
                        });
                    });
                }
            });
        },

        moveEvaluationSupplier: function (from, target) {
            const order = this.evaluationOrder.slice();
            const sourceIndex = order.indexOf(from);
            const targetIndex = order.indexOf(target);
            if (sourceIndex < 0 || targetIndex < 0 || sourceIndex === targetIndex) return;
            order.splice(sourceIndex, 1);
            order.splice(targetIndex, 0, from);
            this.evaluationOrder = order;
            this.evaluationSort = 'manual';
            $(this.root).find('.js-tender-evaluation-sort').val('manual');
            this.renderRanking(this.ranking);
        },

        renderRanking: function (payload) {
            const self = this;
            const $wrap = $(this.root).find('.js-tender-evaluation-table');
            const apps = payload && Array.isArray(payload.applications) ? payload.applications.slice() : [];
            const currency = payload && payload.currency || 'RUB';
            const name = function (row) {
                const supplier = row.supplier || {};
                return String(supplier.fullname || supplier.name || 'Поставщик');
            };
            const numeric = function (value) {
                return value === null || value === undefined || value === '' || !Number.isFinite(Number(value)) ? null : Number(value);
            };
            const manualOrder = new Map(this.evaluationOrder.map(function (id, index) { return [id, index]; }));
            apps.sort(function (a, b) {
                if (self.evaluationSort === 'manual') {
                    const ai = manualOrder.has(Number(a.application_id)) ? manualOrder.get(Number(a.application_id)) : Number.MAX_SAFE_INTEGER;
                    const bi = manualOrder.has(Number(b.application_id)) ? manualOrder.get(Number(b.application_id)) : Number.MAX_SAFE_INTEGER;
                    return ai - bi || Number(a.application_id) - Number(b.application_id);
                }
                let compared = 0;
                if (self.evaluationSort === 'name') {
                    compared = name(a).localeCompare(name(b), 'ru');
                } else {
                    const key = self.evaluationSort === 'rank' ? 'rank' : 'total';
                    const av = numeric(a[key]);
                    const bv = numeric(b[key]);
                    if (av === null || bv === null) {
                        compared = av === bv ? 0 : (av === null ? 1 : -1);
                    } else {
                        compared = (av - bv) * (self.evaluationSort === 'total-desc' ? -1 : 1);
                    }
                }
                return compared || Number(a.application_id) - Number(b.application_id);
            });
            this.evaluationOrder = apps.map(function (app) { return Number(app.application_id); });
            const esc = function (value) { return self.escapeHtml(String(value)); };
            const money = function (value) { return numeric(value) === null ? '—' : esc(self.formatMoney(value, currency)); };
            const catalog = payload && payload.comparison || {};
            const count = apps.length + 1;
            let html = '<table class="base-table tender-evaluation__table" aria-label="Сравнение предложений">'
                + '<colgroup><col class="tender-evaluation__label-column">'
                + apps.map(function () { return '<col class="tender-evaluation__supplier-column">'; }).join('')
                + '</colgroup><thead><tr><th scope="col">Показатель / позиция</th>';
            apps.forEach(function (app, index) {
                const id = Number(app.application_id);
                html += '<th scope="col" class="js-tender-evaluation-column" data-id="' + id + '">'
                    + '<div class="tender-evaluation__supplier-head"><span class="tender-evaluation__drag js-tender-evaluation-drag" draggable="true" data-id="' + id + '" title="Перетащить столбец" aria-hidden="true">⠿</span>'
                    + '<span>' + esc(name(app)) + '</span></div>'
                    + '<div class="tender-evaluation__column-actions">'
                    + '<button type="button" class="button ghost small icon-only js-tender-evaluation-move" data-id="' + id + '" data-direction="-1" aria-label="' + esc('Переместить ' + name(app) + ' влево') + '"' + (index === 0 ? ' disabled' : '') + '>←</button>'
                    + '<button type="button" class="button ghost small icon-only js-tender-evaluation-move" data-id="' + id + '" data-direction="1" aria-label="' + esc('Переместить ' + name(app) + ' вправо') + '"' + (index === apps.length - 1 ? ' disabled' : '') + '>→</button></div></th>';
            });
            html += '</tr></thead>';
            const group = function (key, label) {
                const open = !self.evaluationCollapsed[key];
                return '<tbody><tr class="tender-evaluation__group"><td colspan="' + count + '">'
                    + '<button type="button" class="button ghost small js-tender-evaluation-toggle" data-group="' + key
                    + '" aria-expanded="' + open + '" aria-controls="tender-evaluation-' + self.tenderId + '-' + key + '">'
                    + '<span class="tender-evaluation__chevron" aria-hidden="true">⌃</span>' + esc(label) + '</button></td></tr></tbody>'
                    + '<tbody id="tender-evaluation-' + self.tenderId + '-' + key + '" data-evaluation-group="' + key + '"' + (open ? '' : ' hidden') + '>';
            };
            const row = function (label, cells) {
                return '<tr><td>' + label + '</td>' + cells.map(function (cell) { return '<td>' + cell + '</td>'; }).join('') + '</tr>';
            };
            if (!apps.length) {
                html += '<tbody><tr><td class="base-table__empty">Нет поданных предложений для сравнения или все предложения отклонены по допуску.</td></tr></tbody>';
            } else {
                html += group('specification', 'Стоимость полной спецификации');
                html += row('Цена с НДС', apps.map(function (app) {
                    return money(app.comparison ? app.comparison.totals.with_vat : app.total);
                }));
                html += row('Ставка НДС', apps.map(function (app) {
                    const rates = app.comparison && app.comparison.totals.vat_rates || [];
                    return rates.length ? esc(rates.join(', ')) : '—';
                }));
                html += row('Сумма НДС', apps.map(function (app) { return money(app.comparison && app.comparison.totals.vat); }));
                html += row('Сумма без НДС', apps.map(function (app) { return money(app.comparison && app.comparison.totals.without_vat); }));
                html += '</tbody>';
                const positions = new Map();
                (Array.isArray(catalog.items) ? catalog.items : []).forEach(function (item) {
                    positions.set(Number(item.tender_item_id), item);
                });
                const values = new Map();
                apps.forEach(function (app) {
                    const byId = new Map();
                    const items = app.comparison ? app.comparison.items : app.items;
                    (Array.isArray(items) ? items : []).forEach(function (item) {
                        const id = Number(item.tender_item_id);
                        if (id > 0) {
                            if (!positions.has(id)) positions.set(id, item);
                            byId.set(id, item);
                        }
                    });
                    values.set(Number(app.application_id), byId);
                });
                if (positions.size) {
                    html += group('positions', 'Позиции');
                    Array.from(positions.values()).sort(function (a, b) {
                        return Number(a.sort) - Number(b.sort) || Number(a.tender_item_id) - Number(b.tender_item_id);
                    }).forEach(function (item, index) {
                        let label = esc((index + 1) + '. ' + item.name);
                        const details = [item.qty, item.unit].filter(function (value) { return value !== null && value !== undefined && value !== ''; });
                        if (item.vat_rate !== null && item.vat_rate !== undefined && item.vat_rate !== '') {
                            details.push('НДС ' + item.vat_rate);
                        }
                        if (details.length) label += '<span class="base-table__hint tender-evaluation__unit-price">' + esc(details.join(' ')) + '</span>';
                        html += row(label, apps.map(function (app) {
                            const value = values.get(Number(app.application_id)).get(Number(item.tender_item_id));
                            if (!value) return '—';
                            return money(value.line_total) + '<span class="base-table__hint tender-evaluation__unit-price">'
                                + money(value.price_per_unit) + (value.unit ? '/' + esc(value.unit) : '') + '</span>';
                        }));
                    });
                    html += '</tbody>';
                }
                const fileLink = function (app, record, kind, recordId) {
                    if (!record || !Number(record.file_link_id)) return 'Не предоставлен';
                    const url = '/api/buyer/tender/' + self.tenderId + '/applications/' + Number(app.application_id)
                        + '/' + kind + '/' + Number(recordId) + '/file/';
                    return '<a href="' + url + '" hx-boost="false">'
                        + esc(record.file_name || record.name || 'Скачать файл') + '</a>';
                };
                const requirements = Array.isArray(catalog.documents) ? catalog.documents : [];
                const requirementIds = new Set(requirements.map(function (document) { return Number(document.id); }));
                const applicationDocuments = new Map();
                let hasExtraDocuments = false;
                apps.forEach(function (app) {
                    const documents = app.comparison && app.comparison.documents || [];
                    const required = new Map();
                    const extra = [];
                    documents.forEach(function (document) {
                        const id = Number(document.tender_document_id);
                        if (requirementIds.has(id)) required.set(id, document);
                        else if (Number(document.file_link_id)) extra.push(document);
                    });
                    if (extra.length) hasExtraDocuments = true;
                    applicationDocuments.set(Number(app.application_id), {required: required, extra: extra});
                });
                html += group('documents', 'Поданные документы');
                requirements.forEach(function (requirement) {
                    html += row(esc(requirement.name), apps.map(function (app) {
                        const document = applicationDocuments.get(Number(app.application_id)).required.get(Number(requirement.id));
                        return document ? fileLink(app, document, 'documents', document.id) : 'Не предоставлен';
                    }));
                });
                if (hasExtraDocuments) {
                    html += row('Дополнительные документы', apps.map(function (app) {
                        const documents = applicationDocuments.get(Number(app.application_id)).extra;
                        return documents.length ? documents.map(function (document) {
                            return '<div class="tender-evaluation__file">' + fileLink(app, document, 'documents', document.id) + '</div>';
                        }).join('') : '—';
                    }));
                }
                if (!requirements.length && !hasExtraDocuments) {
                    html += '<tr><td colspan="' + count + '" class="base-table__empty">Документы не предоставлены</td></tr>';
                }
                html += '</tbody>';
                const criteria = Array.isArray(catalog.criteria) ? catalog.criteria : [];
                const answers = new Map();
                apps.forEach(function (app) {
                    answers.set(Number(app.application_id), new Map((app.comparison && app.comparison.criteria || []).map(function (answer) {
                        return [Number(answer.criterion_id), answer];
                    })));
                });
                html += group('criteria', 'Неценовые критерии');
                criteria.forEach(function (criterion) {
                    html += row(esc(criterion.name), apps.map(function (app) {
                        const answer = answers.get(Number(app.application_id)).get(Number(criterion.id));
                        if (!answer) return '—';
                        if (criterion.type === 'file') return fileLink(app, answer, 'criteria', criterion.id);
                        if (criterion.type === 'confirmation') {
                            return Number(answer.confirmed)
                                ? '<span class="badge badge--shared badge--medium success">Принимаю</span>'
                                : 'Не подтверждено';
                        }
                        return answer.value === null || answer.value === undefined || answer.value === '' ? '—' : esc(answer.value);
                    }));
                });
                if (!criteria.length) {
                    html += '<tr><td colspan="' + count + '" class="base-table__empty">Неценовые критерии не заданы</td></tr>';
                }
                html += '</tbody>';
                html += '<tfoot><tr><td></td>';
                apps.forEach(function (app) {
                    html += '<td><button type="button" class="button outline js-tender-evaluation-proposal" data-id="'
                        + (parseInt(app.application_id, 10) || 0) + '">Посмотреть предложение</button></td>';
                });
                html += '</tr></tfoot>';
            }
            html += '</table>';
            $wrap.css('--table-min-width', (360 + apps.length * 300) + 'px').html(html);
            $(this.root).find('.js-buyer-evaluation-ranking').prop('hidden', false);
        },

        showListView: function () {
            const $root = $(this.root);
            this.currentApplicationId = 0;
            $root.find('.js-tender-participants-list-view').prop('hidden', false);
            $root.find('.js-tender-participants-detail-view').prop('hidden', true);
            $root.find('.tender-page__panel--participants').addClass('tender-page__panel--with-aside');
            $root.find('.js-tender-participants-aside').prop('hidden', false);
            this.replaceQuery({ application: null });
        },
        showDetailView: function () {
            const $root = $(this.root);
            $root.find('.js-tender-participants-list-view').prop('hidden', true);
            $root.find('.js-tender-participants-detail-view').prop('hidden', false);
            $root.find('.tender-page__panel--participants').removeClass('tender-page__panel--with-aside');
            $root.find('.js-tender-participants-aside').prop('hidden', true);
        },

        gateField: function (gate) {
            if (gate === 'approval') {
                return 'approval_status';
            }
            if (gate === 'qualification') {
                return 'qualification_status';
            }
            if (gate === 'admission') {
                return 'admission_status';
            }
            return '';
        },

        submitDecision: function (gate, code, comment) {
            const self = this;
            const $root = $(this.root);
            const field = this.gateField(gate);
            const applicationId = this.currentApplicationId;
            if (!field || this.tenderId <= 0 || applicationId <= 0 || this.busy) {
                return;
            }

            this.busy = true;
            $root.find('.js-tender-application-decision-error').prop('hidden', true).text('');
            $root.find('.js-tender-application-decision-approve, .js-tender-application-decision-reject-open, .js-tender-application-decision-reject-confirm')
                .prop('disabled', true);

            const data = {};
            data[field] = code;
            if (comment) {
                data.comment = comment;
            }

            $.fRequest({
                url: '/api/buyer/tender/' + this.tenderId + '/applications/' + applicationId + '/decision/',
                method: 'POST',
                showMessages: true,
                data: data,
                onSuccess: function (reply) {
                    self.busy = false;
                    self.pricesVisible = parseInt(reply.prices_visible, 10) || 0;
                    self.renderApplicationDetail(reply.application || null);
                    self.listLoaded = false;
                },
                onError: function (reply) {
                    self.busy = false;
                    $root.find('.js-tender-application-decision-approve, .js-tender-application-decision-reject-open, .js-tender-application-decision-reject-confirm')
                        .prop('disabled', false);
                    const msg = (reply && reply.message) || 'Не удалось сохранить решение';
                    $root.find('.js-tender-application-decision-card[data-gate="' + gate + '"] .js-tender-application-decision-error')
                        .text(msg)
                        .prop('hidden', false);
                }
            }).catch(function () {
                self.busy = false;
                $root.find('.js-tender-application-decision-approve, .js-tender-application-decision-reject-open, .js-tender-application-decision-reject-confirm')
                    .prop('disabled', false);
                $root.find('.js-tender-application-decision-card[data-gate="' + gate + '"] .js-tender-application-decision-error')
                    .text('Не удалось сохранить решение')
                    .prop('hidden', false);
            });
        },

        loadApplications: function () {
            const self = this;
            const $root = $(this.root);
            if (this.tenderId <= 0) {
                return;
            }

            $root.find('.js-tender-participants-error').prop('hidden', true).text('');
            $root.find('.js-tender-participants-empty').prop('hidden', true);
            $root.find('.js-tender-participants-list').prop('hidden', true).empty();
            $root.find('.js-tender-participants-loading').prop('hidden', false);

            $.fRequest({
                url: '/api/buyer/tender/' + this.tenderId + '/applications/',
                method: 'GET',
                showMessages: false,
                onSuccess: function (reply) {
                    self.listLoaded = true;
                    self.applications = Array.isArray(reply.applications) ? reply.applications : [];
                    self.pricesVisible = parseInt(reply.prices_visible, 10) || 0;
                    $root.find('.js-tender-participants-loading').prop('hidden', true);
                    $root.find('.js-tender-tabs .tab-btn[data-tab="participants"] .tabs-count')
                        .text(String(self.applications.length));
                    self.renderApplicationsList();
                },
                onError: function (reply) {
                    $root.find('.js-tender-participants-loading').prop('hidden', true);
                    $root.find('.js-tender-participants-error')
                        .text((reply && reply.message) || 'Не удалось загрузить участников')
                        .prop('hidden', false);
                }
            }).catch(function () {
                $root.find('.js-tender-participants-loading').prop('hidden', true);
                $root.find('.js-tender-participants-error')
                    .text('Не удалось загрузить участников')
                    .prop('hidden', false);
            });
        },

        statusName: function (status) {
            if (!status) {
                return '—';
            }
            return String(status.name || status.code || '—');
        },

        formatDateTime: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) {
                return '—';
            }
            const d = new Date(raw.replace(' ', 'T'));
            if (isNaN(d.getTime())) {
                return raw;
            }
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const yyyy = d.getFullYear();
            const hh = String(d.getHours()).padStart(2, '0');
            const mi = String(d.getMinutes()).padStart(2, '0');
            return dd + '.' + mm + '.' + yyyy + ', ' + hh + ':' + mi;
        },

        gateBadge: function (label, status) {
            const code = String((status && status.code) || 'not_required');
            const name = this.statusName(status);
            let tone = 'neutral';
            if (code === 'approved' || code === 'passed' || code === 'admitted') {
                tone = 'success';
            } else if (code === 'pending') {
                tone = 'warning';
            } else if (code === 'rejected' || code === 'failed') {
                tone = 'error';
            } else if (code === 'not_required') {
                tone = 'neutral';
            }
            return (
                '<div class="tender-application-decision__head">'
                + '<span class="tender-application-decision__label">' + this.escapeHtml(label) + '</span>'
                + '<span class="badge badge--shared badge--small ' + this.escapeHtml(status && status.tone || tone) + '">' + this.escapeHtml(name) + '</span>'
                + '</div>'
            );
        },

        gateDecisionCard: function (opts) {
            const gate = String(opts.gate || '');
            const label = String(opts.label || '');
            const status = opts.status || null;
            const comment = String(opts.comment || '').trim();
            const canDecide = !!opts.canDecide;
            const approveCode = String(opts.approveCode || '');
            const rejectCode = String(opts.rejectCode || '');
            const approveLabel = String(opts.approveLabel || 'Одобрить');
            const rejectLabel = String(opts.rejectLabel || 'Отклонить');

            let html = '<div class="tender-application-decision js-tender-application-decision-card" data-gate="'
                + this.escapeHtml(gate) + '">';
            html += this.gateBadge(label, status);
            if (comment) {
                html += '<p class="tender-application-decision__comment">' + this.escapeHtml(comment) + '</p>';
            }
            if (canDecide) {
                html += '<div class="tender-application-decision__actions">'
                    + '<button type="button" class="button primary small js-tender-application-decision-approve" data-gate="'
                    + this.escapeHtml(gate) + '" data-code="' + this.escapeHtml(approveCode) + '">'
                    + this.escapeHtml(approveLabel) + '</button>'
                    + '<button type="button" class="button secondary small js-tender-application-decision-reject-open">'
                    + this.escapeHtml(rejectLabel) + '</button>'
                    + '</div>'
                    + '<div class="tender-application-decision__reject js-tender-application-decision-reject-form" hidden>'
                    + '<label class="tender-application-decision__reject-label">Причина отклонения'
                    + '<textarea class="input tender-application-decision__reject-comment js-tender-application-decision-reject-comment" rows="3"'
                    + ' placeholder="Обязательно при отклонении"></textarea></label>'
                    + '<div class="tender-application-decision__reject-actions">'
                    + '<button type="button" class="button primary small js-tender-application-decision-reject-confirm" data-code="'
                    + this.escapeHtml(rejectCode) + '">Подтвердить</button>'
                    + '<button type="button" class="button secondary small js-tender-application-decision-reject-cancel">Отмена</button>'
                    + '</div></div>';
            }
            html += '<p class="tender-application-decision__error js-tender-application-decision-error" hidden></p>';
            html += '</div>';
            return html;
        },

        escapeHtml: function (value) {
            return String(value == null ? '' : value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        },

        inlineGateBadge: function (kind, status) {
            const code = String((status && status.code) || '');
            if (!code || code === 'not_required') {
                return '';
            }

            const labels = {
                approval: { approved: 'Одобрен', pending: 'Ожидает одобрения', rejected: 'Отклонён' },
                qualification: { passed: 'Квалифицирован', pending: 'Ожидает квалификации', failed: 'Не квалифицирован' }
            };
            const label = (labels[kind] || {})[code] || this.statusName(status);

            let tone = 'neutral';
            let icon = 'icon-clock';
            if (code === 'approved' || code === 'passed') {
                tone = 'ok';
                icon = code === 'passed' ? 'icon-check-circle' : 'icon-check';
            } else if (code === 'pending') {
                tone = 'wait';
                icon = 'icon-clock';
            } else if (code === 'rejected' || code === 'failed') {
                tone = 'error';
                icon = 'icon-x-mark';
            }

            return '<span class="tender-participant-card__badge is-' + tone + '">'
                + '<span class="tender-participant-card__badge-icon" aria-hidden="true">'
                + '<svg><use href="#' + icon + '"></use></svg></span>'
                + '<span>' + this.escapeHtml(label) + '</span></span>';
        },

        participationStatusLabel: function (status) {
            const code = String((status && status.code) || '');
            if (code === 'submitted') {
                return 'Подал предложение';
            }
            if (code === 'withdrawn') {
                return 'Отозвал предложение';
            }
            if (code === 'draft') {
                return 'Принял участие';
            }
            return this.statusName(status);
        },

        participantMetaHtml: function (supplier) {
            const row = supplier || {};
            const address = String(row.address || '').trim();
            const phone = String(row.phone || '').trim();
            const inn = String(row.inn || '').trim();
            const parts = [];

            if (address) {
                parts.push('<span class="tender-participant-card__meta-address"></span>');
            } else if (inn) {
                parts.push('<span class="tender-participant-card__meta-address"></span>');
            }
            if (phone) {
                if (parts.length) {
                    parts.push('<span class="tender-participant-card__meta-dot" aria-hidden="true"></span>');
                }
                parts.push('<span class="tender-participant-card__meta-phone"></span>');
            }

            return parts.join('');
        },

        filterApplications: function (items) {
            const query = String(this.participantsSearch || '').trim().toLowerCase();
            const list = Array.isArray(items) ? items : [];
            if (!query) {
                return list.slice();
            }
            return list.filter(function (app) {
                if (!app) {
                    return false;
                }
                const supplier = app.supplier || {};
                const haystack = [
                    supplier.fullname,
                    supplier.name,
                    supplier.inn,
                    app.status && app.status.name,
                    app.status && app.status.code
                ].join(' ').toLowerCase();
                return haystack.indexOf(query) !== -1;
            });
        },

        renderApplicationsList: function () {
            const $root = $(this.root);
            const $list = $root.find('.js-tender-participants-list');
            const allItems = this.applications || [];
            const items = this.filterApplications(allItems);
            $list.empty();

            $root.find('.js-tender-participants-empty').prop('hidden', true);
            $root.find('.js-tender-participants-search-empty').prop('hidden', true);

            if (!allItems.length) {
                $list.prop('hidden', true);
                $root.find('.js-tender-participants-empty').prop('hidden', false);
                return;
            }

            if (!items.length) {
                $list.prop('hidden', true);
                $root.find('.js-tender-participants-search-empty').prop('hidden', false);
                return;
            }

            $list.prop('hidden', false);

            const self = this;
            items.forEach(function (app) {
                const supplier = app.supplier || {};
                const title = supplier.fullname || supplier.name || ('Поставщик #' + (supplier.id || app.supplier_company_id || '—'));
                const statusCode = app.status && app.status.code ? app.status.code : '';
                const badges = self.inlineGateBadge('qualification', app.qualification_status)
                    + self.inlineGateBadge('approval', app.approval_status);
                const address = String(supplier.address || '').trim();
                const phone = String(supplier.phone || '').trim();
                const inn = String(supplier.inn || '').trim();
                const metaPrimary = address || (inn ? ('ИНН ' + inn) : '');
                const $card = $(
                    '<li>'
                    + '<button type="button" class="tender-participant-card js-tender-participant-card" data-id="'
                    + (parseInt(app.id, 10) || 0) + '">'
                    + '<div class="tender-participant-card__main">'
                    + '<div class="tender-participant-card__head">'
                    + '<span class="tender-participant-card__title"></span>'
                    + '<span class="tender-participant-card__badges">' + badges + '</span>'
                    + '</div>'
                    + '<div class="tender-participant-card__meta">' + self.participantMetaHtml(supplier) + '</div>'
                    + '</div>'
                    + '<div class="tender-participant-card__side">'
                    + '<span class="tender-participant-card__chat is-disabled js-tender-participant-chat"'
                    + ' title="Чат пока недоступен" aria-disabled="true" role="img"'
                    + ' aria-label="Чат пока недоступен">'
                    + '<svg aria-hidden="true"><use href="#icon-message"></use></svg>'
                    + '</span>'
                    + '<span class="tender-participant-card__divider" aria-hidden="true"></span>'
                    + '<span class="tender-participant-card__status-wrap">'
                    + '<span class="tender-participant-card__status"></span>'
                    + '</span>'
                    + '</div>'
                    + '</button>'
                    + '</li>'
                );
                $card.find('.tender-participant-card__title').text(title);
                if (metaPrimary) {
                    $card.find('.tender-participant-card__meta-address').text(metaPrimary);
                }
                if (phone) {
                    $card.find('.tender-participant-card__meta-phone').text(phone);
                }
                $card.find('.tender-participant-card__status')
                    .addClass(statusCode ? ('is-' + statusCode) : '')
                    .text(self.participationStatusLabel(app.status));
                $list.append($card);
            });
        },

        openApplication: function (applicationId) {
            const self = this;
            const $root = $(this.root);
            if (this.tenderId <= 0 || applicationId <= 0) {
                return;
            }

            this.currentApplicationId = applicationId;
            if (this.activeTab !== 'participants') {
                this.showTab('participants');
            }
            this.showDetailView();
            this.replaceQuery({ application: applicationId });

            const $body = $root.find('.js-tender-application-body');
            $body.prop('hidden', true);
            $root.find('.js-tender-application-panel-body').empty();
            $root.find('.js-tender-application-sidebar').empty();
            $root.find('.js-tender-application-error').prop('hidden', true).text('');
            $root.find('.js-tender-application-loading').prop('hidden', false);

            $.fRequest({
                url: '/api/buyer/tender/' + this.tenderId + '/applications/' + applicationId + '/',
                method: 'GET',
                showMessages: false,
                onSuccess: function (reply) {
                    $root.find('.js-tender-application-loading').prop('hidden', true);
                    self.pricesVisible = parseInt(reply.prices_visible, 10) || 0;
                    self.renderApplicationDetail(reply.application || null);
                },
                onError: function (reply) {
                    $root.find('.js-tender-application-loading').prop('hidden', true);
                    $root.find('.js-tender-application-error')
                        .text((reply && reply.message) || 'Не удалось открыть заявку')
                        .prop('hidden', false);
                }
            }).catch(function () {
                $root.find('.js-tender-application-loading').prop('hidden', true);
                $root.find('.js-tender-application-error')
                    .text('Не удалось открыть заявку')
                    .prop('hidden', false);
            });
        },

        renderApplicationDetail: function (app) {
            const $root = $(this.root);
            const $body = $root.find('.js-tender-application-body');
            const $sidebar = $root.find('.js-tender-application-sidebar');
            if (!app) {
                $body.prop('hidden', true);
                $root.find('.js-tender-application-panel-body').empty();
                $sidebar.empty();
                $root.find('.js-tender-application-error')
                    .text('Заявка не найдена')
                    .prop('hidden', false);
                return;
            }

            this.currentApplicationId = parseInt(app.id, 10) || this.currentApplicationId;

            const supplier = app.supplier || {};
            const title = supplier.fullname || supplier.name || ('Поставщик #' + (app.supplier_company_id || '—'));
            const pricesVisible = !!this.pricesVisible;
            const self = this;
            const actions = app.gate_actions || {};
            const el = this.root;
            const approvalCode = String((app.approval_status && app.approval_status.code) || '');
            const qualificationCode = String((app.qualification_status && app.qualification_status.code) || '');
            const criteria = Array.isArray(app.criteria) ? app.criteria : [];

            let gatesHtml = '<div class="tender-application__decisions">';
            if (criteria.length) {
                gatesHtml += '<div class="tender-application-decision">'
                    + this.gateBadge(
                        'Неценовые критерии',
                        { code: app.nonprice_done ? 'approved' : 'pending', name: app.nonprice_done ? 'Готово' : 'Не закрыты' }
                    )
                    + '</div>';
            }
            if (approvalCode && approvalCode !== 'not_required') {
                gatesHtml += this.gateDecisionCard({
                    gate: 'approval',
                    label: 'Одобрение',
                    status: app.approval_status,
                    comment: app.approval_comment,
                    canDecide: !!(actions.approval && parseInt(actions.approval.can_decide, 10)),
                    approveCode: 'approved',
                    rejectCode: 'rejected',
                    approveLabel: 'Одобрить',
                    rejectLabel: 'Отклонить'
                });
            }
            if (qualificationCode && qualificationCode !== 'not_required') {
                gatesHtml += this.gateDecisionCard({
                    gate: 'qualification',
                    label: 'Квалификация',
                    status: app.qualification_status,
                    comment: app.qualification_comment,
                    canDecide: !!(actions.qualification && parseInt(actions.qualification.can_decide, 10)),
                    approveCode: 'passed',
                    rejectCode: 'failed',
                    approveLabel: 'Квалификация пройдена',
                    rejectLabel: 'Отклонить'
                });
            }
            gatesHtml += this.gateDecisionCard({
                gate: 'admission',
                label: 'Заявка',
                status: app.admission_status,
                comment: app.admission_comment,
                canDecide: !!(actions.admission && parseInt(actions.admission.can_decide, 10)),
                approveCode: 'admitted',
                rejectCode: 'rejected',
                approveLabel: 'Принять заявку',
                rejectLabel: 'Отклонить'
            });
            gatesHtml += '</div>';

            const panels = {
                notice: this.renderApplicationNoticePanel(el),
                lots: this.renderApplicationLotsPanel(app, pricesVisible),
                documents: this.renderApplicationDocsPanel(app),
                nonPrice: this.renderApplicationCriteriaPanel(app)
            };
            Object.keys(panels).forEach(function (id) {
                $root.find('.js-tender-application-panel-body[data-panel="' + id + '"]').html(panels[id]);
            });

            const active = panels[self.detailSubtab] ? self.detailSubtab : 'lots';
            this.detailSubtab = active;
            if (this.applicationTabs) {
                this.applicationTabs.showTab(active);
            }

            const statusCode = (app.status && app.status.code) ? app.status.code : '';
            const offerTotal = this.computeApplicationOfferTotal(app, pricesVisible);
            const sumLabel = pricesVisible && offerTotal != null
                ? this.formatMoney(offerTotal, 'RUB') + ' с НДС'
                : (pricesVisible ? '—' : 'Скрыто до вскрытия');

            let sidebarHtml = '<div class="tender-application-participant__head">'
                + '<div class="tender-application-participant__identity">'
                + '<span class="tender-application-participant__avatar" aria-hidden="true">'
                + '<svg width="20" height="20" viewBox="0 0 24 24" fill="none">'
                + '<path d="M3 21V7.5L12 3l9 4.5V21H3z" stroke="currentColor" stroke-width="1.5"/>'
                + '<path d="M9 21v-6h6v6" stroke="currentColor" stroke-width="1.5"/>'
                + '</svg></span>'
                + '<div class="tender-application-participant__identity-main">'
                + '<p class="tender-application-participant__supplier js-tender-application-supplier"></p>'
                + '<span class="tender-participant-card__status'
                + (statusCode ? ' is-' + this.escapeHtml(statusCode) : '') + '">'
                + this.escapeHtml(this.participationStatusLabel(app.status)) + '</span>'
                + '</div></div>';
            if (app.submitted_at) {
                sidebarHtml += '<p class="tender-application-participant__meta">Подано: '
                    + this.escapeHtml(this.formatDateTime(app.submitted_at)) + '</p>';
            }
            sidebarHtml += '<p class="tender-application-participant__meta">Сумма: '
                + '<span class="tender-application-participant__sum">'
                + this.escapeHtml(sumLabel) + '</span></p>';
            sidebarHtml += '</div>';
            sidebarHtml += '<div class="tender-application-participant__block">'
                + '<p class="tender-application-participant__title">Решения организатора</p>'
                + gatesHtml
                + '</div>';

            $sidebar.html(sidebarHtml);
            $sidebar.find('.js-tender-application-supplier').text(title);
            $body.prop('hidden', false);
        },

        noticeAttr: function (el, name) {
            if (!el || !el.getAttribute) {
                return '';
            }
            return String(el.getAttribute(name) || '').trim();
        },

        renderApplicationNoticePanel: function (el) {
            const self = this;
            const title = this.noticeAttr(el, 'data-notice-title') || '—';
            const number = this.noticeAttr(el, 'data-notice-number') || '—';
            const method = this.noticeAttr(el, 'data-notice-method') || '—';
            const status = this.noticeAttr(el, 'data-notice-status') || '—';
            const access = this.noticeAttr(el, 'data-notice-access') || '—';
            const deadline = this.noticeAttr(el, 'data-notice-deadline') || '—';
            let description = this.noticeAttr(el, 'data-notice-description');
            if (!description && title && title !== '—') {
                description = 'Закупка по процедуре «' + title + '». Условия поставки и оплаты — согласно извещению.';
            }
            if (!description) description = '—';
            const item = function (label, value, copyable) {
                let html = '<div class="detail-field"><p class="detail-field__label">' + self.escapeHtml(label) + '</p>';
                const content = '<span>' + self.escapeHtml(value) + '</span>';
                if (copyable && value && value !== '—') {
                    html += '<div class="detail-field__value detail-copy-value">' + content
                        + '<button type="button" class="button ghost icon-only detail-copy js-detail-copy" data-copy="' + self.escapeHtml(value)
                        + '" aria-label="Скопировать ' + self.escapeHtml(label) + '"><svg width="14" height="14" aria-hidden="true"><use href="#icon-position-copy"></use></svg></button></div>';
                } else {
                    html += '<p class="detail-field__value">' + self.escapeHtml(value) + '</p>';
                }
                return html + '</div>';
            };
            const blocks = [
                {
                    title: 'Сведения о процедуре',
                    rows: [
                        item('Наименование', title, false),
                        item('Номер извещения', number, true),
                        item('Способ закупки', method, false),
                        item('Этап', status, false),
                        item('Тип доступа', access, false)
                    ]
                },
                {
                    title: 'Условия',
                    rows: [
                        item('Срок подачи предложений', deadline, false),
                        item('Описание', description, false)
                    ]
                }
            ];

            let html = '<div class="tender-notice tender-application__notice">';
            blocks.forEach(function (block) {
                html += '<section class="detail-section"><h4 class="detail-section__title">' + self.escapeHtml(block.title)
                    + '</h4><div class="detail-fields">' + block.rows.join('') + '</div></section>';
            });
            return html + '</div>';
        },

        computeApplicationOfferTotal: function (app, pricesVisible) {
            if (!pricesVisible) {
                return null;
            }
            const items = Array.isArray(app && app.items) ? app.items : [];
            let total = 0;
            let hasAny = false;
            items.forEach(function (item) {
                if (!item || item.price_per_unit == null || item.price_per_unit === '') {
                    return;
                }
                const price = Number(item.price_per_unit);
                const qty = item.qty != null && item.qty !== '' ? Number(item.qty) : 1;
                if (!isFinite(price)) {
                    return;
                }
                hasAny = true;
                total += price * (isFinite(qty) ? qty : 1);
            });
            return hasAny ? total : null;
        },

        formatPlainMoney: function (value) {
            const amount = Number(value);
            if (!isFinite(amount)) {
                return '—';
            }
            return amount.toLocaleString('ru-RU', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        },

        renderApplicationLotsPanel: function (app, pricesVisible) {
            const self = this;
            const items = Array.isArray(app.items) ? app.items : [];
            if (!items.length) {
                return '<p class="tender-application__empty">Позиции по лотам не добавлены.</p>';
            }

            const offerTotal = this.computeApplicationOfferTotal(app, pricesVisible);
            const offerTotalLabel = pricesVisible && offerTotal != null
                ? this.formatPlainMoney(offerTotal)
                : '—';

            let html = '<div class="tender-application__positions">';
            html += '<div class="table-scroll tender-application-positions" tabindex="0" role="region" aria-label="Позиции предложения">'
                + '<table class="base-table" aria-label="Позиции предложения"><colgroup>'
                + '<col class="tender-positions__number"><col class="tender-positions__name"><col class="tender-positions__quantity"><col class="tender-positions__unit">'
                + '<col class="tender-positions__vat"><col class="tender-positions__price"><col class="tender-positions__price"><col></colgroup>'
                + '<thead><tr>'
                + '<th scope="col">Номер</th><th scope="col">Наименование</th><th scope="col">Количество</th><th scope="col">Ед.</th>'
                + '<th scope="col">НДС</th><th scope="col">НМЦ позиции</th><th scope="col">Цена участника, ₽</th><th scope="col">Место поставки</th>'
                + '</tr></thead><tbody>';

            items.forEach(function (item, idx) {
                let offerCell = '—';
                if (pricesVisible && item.price_per_unit != null && item.price_per_unit !== '') {
                    offerCell = self.formatPlainMoney(item.price_per_unit);
                } else if (!pricesVisible) {
                    offerCell = '—';
                }
                const nmc = item.max_price_no_vat != null && item.max_price_no_vat !== ''
                    ? self.formatPlainMoney(item.max_price_no_vat)
                    : '—';
                html += '<tr>'
                    + '<td>' + (idx + 1) + '</td>'
                    + '<td title="' + self.escapeHtml(item.name || '') + '">'
                    + self.escapeHtml(item.name || '—') + '</td>'
                    + '<td>' + self.escapeHtml(item.qty != null && item.qty !== '' ? item.qty : '—') + '</td>'
                    + '<td>' + self.escapeHtml(item.unit || '—') + '</td>'
                    + '<td>' + self.escapeHtml(item.vat_rate || '—') + '</td>'
                    + '<td>' + self.escapeHtml(nmc) + '</td>'
                    + '<td>' + self.escapeHtml(offerCell) + '</td>'
                    + '<td title="' + self.escapeHtml(item.delivery_place || '') + '">'
                    + self.escapeHtml(item.delivery_place || '—') + '</td>'
                    + '</tr>';
            });

            html += '</tbody>'
                + '<tfoot><tr><td colspan="8">'
                + 'Итого по предложению: ' + self.escapeHtml(offerTotalLabel) + ' ₽'
                + '</td></tr></tfoot></table></div>';

            html += '<div class="tender-application-summary">'
                + '<p class="tender-application-summary__title">Итого по заявке</p>'
                + '<div class="tender-application-summary__row">'
                + '<span>Сумма без НДС</span><span>'
                + self.escapeHtml(offerTotalLabel) + ' ₽</span></div>'
                + '<div class="tender-application-summary__row">'
                + '<span>НДС</span><span>—</span></div>'
                + '<div class="tender-application-summary__divider" aria-hidden="true"></div>'
                + '<div class="tender-application-summary__row is-total">'
                + '<span>Итого</span><span>'
                + self.escapeHtml(offerTotalLabel) + ' ₽</span></div>'
                + '</div></div>';

            return html;
        },

        renderApplicationDocsPanel: function (app) {
            const self = this;
            const docs = Array.isArray(app.documents) ? app.documents : [];
            if (!docs.length) {
                return '<p class="tender-application__empty">Документы не приложены.</p>';
            }

            let html = '<div class="table-scroll tender-application-documents" tabindex="0" role="region" aria-label="Документы предложения">'
                + '<table class="base-table" aria-label="Документы предложения">'
                + '<thead><tr>'
                + '<th scope="col">№</th><th scope="col">Наименование</th><th scope="col">Тип</th><th scope="col">Дата загрузки</th><th scope="col">Статус</th>'
                + '</tr></thead><tbody>';

            docs.forEach(function (doc, idx) {
                const hasFile = (parseInt(doc.file_link_id, 10) || 0) > 0;
                html += '<tr>'
                    + '<td>' + (idx + 1) + '</td>'
                    + '<td>' + self.escapeHtml(doc.name || 'Документ') + '</td>'
                    + '<td>—</td>'
                    + '<td>—</td>'
                    + '<td><span class="badge badge--shared badge--small neutral">'
                    + (hasFile ? 'Загружен' : 'Нет файла') + '</span></td>'
                    + '</tr>';
            });
            html += '</tbody></table></div>';
            return html;
        },

        renderApplicationCriteriaPanel: function (app) {
            const self = this;
            const criteria = Array.isArray(app.criteria) ? app.criteria : [];
            if (!criteria.length) {
                return '<p class="tender-application__empty">Нет данных.</p>';
            }

            let html = '<div class="tender-application__criteria">';
            criteria.forEach(function (row, idx) {
                const type = row.type === 'non_price' ? 'text' : (row.type || 'text');
                let answer = '—';
                if (type === 'confirmation') answer = row.confirmed ? 'Подтверждено' : 'Не подтверждено';
                else if (type === 'file') answer = row.file_name || 'Файл не загружен';
                else if (row.value) answer = row.value;
                html += '<div class="detail-field">'
                    + '<p class="detail-field__label">'
                    + (idx + 1) + '. ' + self.escapeHtml(row.name || ('Критерий #' + (row.criterion_id || '')))
                    + (row.is_mandatory ? ' *' : '')
                    + '</p>'
                    + '<p class="detail-field__value">'
                    + self.escapeHtml(answer) + '</p>';
                if (type === 'file' && row.file_link_id && app.id) {
                    html += '<a href="/api/buyer/tender/' + encodeURIComponent(this.tenderId)
                        + '/applications/' + encodeURIComponent(app.id)
                        + '/criteria/' + encodeURIComponent(row.criterion_id) + '/file/">Скачать файл</a>';
                }
                html += '</div>';
            }, this);
            html += '</div>';
            return html;
        }
    });
})(jQuery);
