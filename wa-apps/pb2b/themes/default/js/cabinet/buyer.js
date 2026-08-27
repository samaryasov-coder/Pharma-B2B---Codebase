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
                                url: '/api/common/company/select/',
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
                            return `<a href="/api/common/docflow/request/template/download?id=${row.id}" class="button link large">${data.name}</a>`;
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
        tenderId: 0,
        stepIndex: 0,
        selectedTypeId: 3,
        selectedTypeCode: 'price_request',
        steps: ['privacy', 'basic', 'purchase_params', 'payment_delivery', 'lots', 'invitation'],
        items: [],
        tzFiles: [],
        criteria: [],
        bucket: 'all',
        search: '',
        filterType: '',
        filterStatus: '',

        init: function (root) {
            this.root = root;
            this.tenderId = 0;
            this.stepIndex = 0;
            this.selectedTypeId = 3;
            this.selectedTypeCode = 'price_request';
            this.items = [];
            this.tzFiles = [];
            this.criteria = [];
            this.bucket = 'all';
            this.search = '';
            this.filterType = '';
            this.filterStatus = '';

            this.bindEvents();
            this.initInviteSelects();
            this.initPurchaseParamsUi();
            this.loadList();
        },

        bindEvents: function () {
            const self = this;
            const $root = $(this.root);

            $root.find('.js-tenders-reload').click(function () {
                self.loadList();
            });

            $root.find('.js-tenders-tabs').on('click', 'a', function (e) {
                e.preventDefault();
                const $li = $(this).closest('li');
                $li.addClass('selected').siblings().removeClass('selected');
                self.bucket = String($li.data('bucket') || 'all');
                self.applyFilters();
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
                            if (String($selected.data('available')) !== '1') {
                                $.AlertManager.showError('Этот тип процедуры пока недоступен');
                                return;
                            }
                            self.openCreate({
                                id: parseInt($selected.data('id'), 10) || 0,
                                code: String($selected.data('code') || ''),
                                label: String($selected.data('label') || ''),
                                title: String($selected.data('title') || 'Создание процедуры')
                            });
                            dialog.close();
                        });
                    }
                });
            });

            $root.find('.js-tender-create-back, .js-tender-create-cancel').click(function () {
                self.closeCreate();
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

            $root.find('.js-classifier-tab').click(function () {
                const tab = String($(this).data('tab') || 'common');
                $root.find('.js-classifier-tab').removeClass('active');
                $(this).addClass('active');
                $root.find('.creation-card-categories-results').removeClass('active');
                $root.find('.creation-card-categories-results[data-tab="' + tab + '"]').addClass('active');
            });

            $root.find('.js-field-title').on('input', function () {
                self.syncBasicContinue();
            });

            $root.find('.js-classifier-check').on('change', function () {
                self.renderClassifierSelected($(this).closest('.creation-card-categories-results'));
            });

            $root.find('.js-classifier-selected').on('click', '.js-classifier-tag-remove', function () {
                const value = String($(this).closest('.tag').data('value') || '');
                const $panel = $(this).closest('.creation-card-categories-results');
                $panel.find('.js-classifier-check').filter(function () {
                    return String($(this).val()) === value;
                }).prop('checked', false);
                self.renderClassifierSelected($panel);
            });

            $root.find('.js-classifier-search').on('input', function () {
                const tab = String($(this).data('tab') || 'common');
                const query = String($(this).val() || '').toLowerCase().trim();
                const $tree = $root.find('.js-classifier-tree[data-tab="' + tab + '"]');
                $tree.find('.tender-creation-checkbox-wrap').each(function () {
                    const label = String($(this).data('label') || $(this).find('.checkbox-text').text() || '').toLowerCase();
                    $(this).toggle(!query || label.indexOf(query) !== -1);
                });
            });

            $root.find('.js-tz-dropzone').on('click', function (e) {
                if ($(e.target).closest('.js-tz-file-input').length) return;
                $root.find('.js-tz-file-input').trigger('click');
            });

            $root.find('.js-tz-dropzone').on('dragover dragenter', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('is-dragover');
            });

            $root.find('.js-tz-dropzone').on('dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('is-dragover');
            });

            $root.find('.js-tz-dropzone').on('drop', function (e) {
                const files = e.originalEvent && e.originalEvent.dataTransfer
                    ? e.originalEvent.dataTransfer.files
                    : null;
                self.addTzFiles(files);
            });

            $root.find('.js-tz-file-input').on('change', function () {
                self.addTzFiles(this.files);
                $(this).val('');
            });

            $root.find('.js-tz-file-list').on('click', '.js-tz-file-remove', function () {
                const id = String($(this).closest('.file-item').data('id') || '');
                self.tzFiles = self.tzFiles.filter(function (file) {
                    return String(file.id) !== id;
                });
                self.renderTzFiles();
            });

            $root.find('.js-field-retendering').change(function () {
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

            $root.find('.js-add-info-field, .js-add-approver').click(function () {
                $.AlertManager.showError('Будет доступно на следующем этапе');
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
                self.saveCurrentStep().then(function (ok) {
                    if (ok) self.goStep(self.stepIndex + 1);
                });
            });

            $root.find('.js-tender-step-prev').click(function () {
                self.goStep(self.stepIndex - 1);
            });

            $root.find('.js-tender-save-draft').click(function () {
                self.saveCurrentStep();
            });

            $root.find('.js-tender-publish, .js-tender-publish-footer').click(function () {
                self.publish();
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
                $root.find('.js-field-end-date, .js-field-docs-date, .js-field-result-date').each(function () {
                    utils.initDatePicker($(this), { dateFormat: 'd.m.Y', allowInput: false });
                });
            }
            if (utils && typeof utils.initTimePicker === 'function') {
                $root.find('.js-field-end-time, .js-field-docs-time').each(function () {
                    utils.initTimePicker($(this), {});
                });
            }

            this.syncMinStepUi();
            this.renderCriteria();
        },

        syncMinStepUi: function () {
            const $root = $(this.root);
            const on = $root.find('.js-field-min-step').is(':checked') && $root.find('.js-field-retendering').is(':checked');
            $root.find('.js-min-step-radios').toggleClass('visible', on);
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

        renderCriteria: function () {
            const self = this;
            const $list = $(this.root).find('.js-criteria-list');
            $list.empty();
            this.criteria.forEach(function (item) {
                const $row = $(`
                    <div class="js-criteria-item" data-id="${item.id}">
                        <div class="criteria-info">
                            <div class="criteria-name"></div>
                            <div class="criteria-meta"></div>
                        </div>
                        <div class="criteria-actions">
                            <button type="button" class="button secondary js-criterion-edit" aria-label="Редактировать">✎</button>
                            <button type="button" class="button secondary js-criterion-remove" aria-label="Удалить">✕</button>
                        </div>
                    </div>
                `);
                const $name = $row.find('.criteria-name');
                $name.text(item.name || '');
                if (item.required) {
                    $name.append($('<span class="criteria-badge">').text('Обязательное'));
                }
                const meta = self.criterionTypeLabel(item.type) + (item.description ? (' · ' + item.description) : '');
                $row.find('.criteria-meta').text(meta);
                $list.append($row);
            });
        },

        openCriterionDialog: function (initial) {
            const self = this;
            const editing = initial || null;
            const dialog = new $.DialogManager({
                title: editing ? 'Редактировать критерий' : 'Добавить критерий',
                width: '560px',
                showConfirmButton: false,
                showCancelButton: false,
                content: `
                    <div class="form-container">
                        <div class="input-wrap" style="margin-bottom:14px;">
                            <div class="input-title">Название критерия *</div>
                            <div class="input-box"><input type="text" class="input js-npc-name" placeholder="Например: Сертификат ISO 9001"></div>
                        </div>
                        <div class="input-wrap" style="margin-bottom:14px;">
                            <div class="input-title">Описание (опционально)</div>
                            <textarea class="input js-npc-desc" rows="3" placeholder="Подсказка для поставщика при заполнении" style="width:100%;"></textarea>
                        </div>
                        <div class="input-wrap" style="margin-bottom:14px;">
                            <div class="input-title">Тип ответа</div>
                            <select class="input js-npc-type" style="width:100%;">
                                <option value="text">Текст</option>
                                <option value="date">Дата</option>
                                <option value="file">Файл</option>
                                <option value="confirmation">Подтверждение</option>
                            </select>
                        </div>
                        <label class="tender-creation-checkbox" style="margin:8px 0 20px;">
                            <input type="checkbox" class="js-npc-required">
                            <span class="checkbox-ui"></span>
                            <span class="checkbox-text">Обязательное поле</span>
                        </label>
                        <div class="form-actions" style="display:flex;justify-content:flex-end;gap:12px;">
                            <button type="button" class="button secondary large modal-cancel">Отмена</button>
                            <button type="button" class="button primary large js-npc-save">Сохранить</button>
                        </div>
                    </div>
                `,
                onOpen: function ($container) {
                    if (editing) {
                        $container.find('.js-npc-name').val(editing.name || '');
                        $container.find('.js-npc-desc').val(editing.description || '');
                        $container.find('.js-npc-type').val(editing.type || 'text');
                        $container.find('.js-npc-required').prop('checked', !!editing.required);
                    }
                    $container.find('.js-npc-save').on('click', function () {
                        const name = $.trim($container.find('.js-npc-name').val() || '');
                        if (!name) {
                            $.AlertManager.showError('Укажите название критерия');
                            return;
                        }
                        const next = {
                            id: editing && editing.id ? editing.id : ('npc_' + Date.now()),
                            name: name,
                            description: $.trim($container.find('.js-npc-desc').val() || ''),
                            type: String($container.find('.js-npc-type').val() || 'text'),
                            required: $container.find('.js-npc-required').is(':checked')
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
            $(this.root).find('.js-invite-select, .js-invite-select-final').each(function () {
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
                        url: '/api/common/company/select/',
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
            const $root = $(this.root);
            const hasTitle = $.trim($root.find('.js-field-title').val() || '') !== '';
            $root.find('[data-step="basic"] .js-tender-step-next').prop('disabled', !hasTitle);
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

        addTzFiles: function (fileList) {
            if (!fileList || !fileList.length) return;
            const self = this;
            const maxSize = 10 * 1024 * 1024;
            const allowed = {
                'application/pdf': true,
                'application/msword': true,
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document': true
            };

            Array.prototype.forEach.call(fileList, function (file) {
                const name = String(file.name || '');
                const ext = name.split('.').pop().toLowerCase();
                const okExt = ext === 'pdf' || ext === 'doc' || ext === 'docx';
                const okType = !file.type || allowed[file.type];
                if (!okExt || !okType) {
                    $.AlertManager.showError('Допустимы только PDF и Word (.doc, .docx)');
                    return;
                }
                if (file.size > maxSize) {
                    $.AlertManager.showError('Максимальный размер файла — 10 МБ');
                    return;
                }
                self.tzFiles.push({
                    id: 'local-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8),
                    name: name,
                    size: self.formatFileSize(file.size),
                    ext: ext
                });
            });
            this.renderTzFiles();
        },

        renderTzFiles: function () {
            const $list = $(this.root).find('.js-tz-file-list');
            $list.empty();
            this.tzFiles.forEach(function (file) {
                const extLabel = String(file.ext || 'file').toUpperCase();
                const $item = $(`
                    <div class="file-item" data-id="${file.id}">
                        <span class="file-left">
                            <span class="file-icon"></span>
                            <div class="file-info">
                                <span class="file-name"></span>
                                <span class="file-meta">
                                    <span class="file-status">Файл загружен</span>
                                    <span class="file-size">• ${file.size}</span>
                                </span>
                            </div>
                        </span>
                        <div class="file-actions">
                            <button type="button" class="file-remove button secondary js-tz-file-remove" aria-label="Удалить">
                                <svg><use href="#icon-x-mark"></use></svg>
                            </button>
                        </div>
                    </div>
                `);
                $item.find('.file-icon').text(extLabel);
                $item.find('.file-name').text(file.name);
                $list.append($item);
            });
        },

        loadList: function () {
            const self = this;
            const $root = $(this.root);
            $root.find('.js-tenders-error').hide();

            $.fRequest({
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
            if (statusCode === 'priostanovlen') return 'on_hold';
            if (statusCode === 'arkhiv' || statusCode === 'otmenen' || statusCode === 'nesostoyalsya') {
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

        statusTone: function (statusName) {
            const name = String(statusName || '');
            if (name.indexOf('Предквалификация') !== -1) return 'success';
            if (name.indexOf('Отмен') !== -1) return 'error';
            if (name.indexOf('Переторж') !== -1 || name.indexOf('Ожидает') !== -1) return 'warning';
            if (name === 'Черновик' || name.indexOf('Заверш') !== -1) return 'neutral';
            return 'info';
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
                const tone = self.statusTone(item.status_name);
                const methodLine = (item.type_name || 'Тендер') + (item.number ? ' №' + item.number : '');

                const $card = $(`
                    <article class="buyer-tender-card" data-id="${item.id}">
                        <div class="buyer-tender-card__main">
                            <div class="buyer-tender-card__title-row">
                                <h3 class="buyer-tender-card__title"></h3>
                                <span class="buyer-tender-card__status buyer-tender-card__status--${tone}"></span>
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
                                ${daysLabel ? `<span class="buyer-tender-card__days${expired ? ' is-expired' : ''}">${daysLabel}</span>` : ''}
                            </div>
                        </div>
                        <button type="button" class="buyer-tender-card__more" aria-label="Действия" disabled>⋮</button>
                    </article>`);
                $card.find('.buyer-tender-card__title').text(item.title || 'Без названия');
                $card.find('.buyer-tender-card__status').text(item.status_name || '—');
                $card.find('.buyer-tender-card__method').text(methodLine);
                $list.append($card);
            });
        },

        openCreate: function (method) {
            const self = this;
            const $root = $(this.root);
            method = method || {};
            this.tenderId = 0;
            this.stepIndex = 0;
            this.selectedTypeId = parseInt(method.id, 10) || 3;
            this.selectedTypeCode = method.code || 'price_request';

            $root.find('.js-tender-id').val('0');
            $root.find('.js-tender-type').val(String(this.selectedTypeId));
            $root.find('.js-creation-title').text(method.title || 'Создание процедуры');

            $root.find('.js-field-title, .js-field-number, .js-field-budget, .js-field-payment, .js-field-delivery').val('');
            $root.find('.js-field-end-date, .js-field-end-time, .js-field-docs-date, .js-field-docs-time, .js-field-result-date').val('');
            $root.find('.js-field-additional-info, .js-field-approver').val('');
            $root.find('.js-field-itemized, .js-field-allow-analogues, .js-field-auto-extend-no-offers, .js-field-only-reduction, .js-field-require-docs').prop('checked', false);
            $root.find('.js-field-retendering, .js-field-min-step').prop('checked', true);
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
            $root.find('.js-invite-select, .js-invite-select-final').val(null).trigger('change');
            $root.find('.js-private-invites, .js-past-prequal-block, .js-approval-period-wrap, .js-approval-period-fields, .js-hide-prices-options').removeClass('visible');
            $root.find('.js-past-prequal-wrap').hide();
            $root.find('.js-price-request-only').toggle(this.selectedTypeCode === 'price_request');
            $root.find('.js-prequal-only').toggle(this.selectedTypeCode === 'prequalification');
            $root.find('.js-classifier-tab').removeClass('active').filter('[data-tab="common"]').addClass('active');
            $root.find('.creation-card-categories-results').removeClass('active').filter('[data-tab="common"]').addClass('active');
            $root.find('.js-classifier-search').val('');
            $root.find('.js-classifier-check').prop('checked', false);
            $root.find('.js-classifier-tree .tender-creation-checkbox-wrap').show();
            $root.find('.js-field-tags-search').val('');
            $root.find('.js-tags-selected').empty();
            $root.find('.creation-card-categories-results').each(function () {
                self.renderClassifierSelected($(this));
            });
            this.tzFiles = [];
            this.renderTzFiles();
            this.criteria = [];
            this.renderCriteria();
            this.syncMinStepUi();
            this.syncBasicContinue();

            $root.find('.js-tenders-list-view').hide();
            $root.find('.js-tender-create-view').show();
            this.goStep(0);
        },

        closeCreate: function () {
            const $root = $(this.root);
            $root.find('.js-tender-create-view').hide();
            $root.find('.js-tenders-list-view').show();
            this.loadList();
        },

        goStep: function (index) {
            if (index < 0 || index >= this.steps.length) return;
            this.stepIndex = index;
            const step = this.steps[index];
            const self = this;
            const $root = $(this.root);
            const hasOwnFooter = step === 'privacy' || step === 'basic' || step === 'purchase_params';
            const isLast = index === this.steps.length - 1;

            $root.find('.js-tender-step-panel').hide();
            $root.find('.js-tender-step-panel[data-step="' + step + '"]').show();

            $root.find('.js-tender-steps .creation-step').each(function (i) {
                const $el = $(this);
                $el.removeClass('next completed');
                if (i < self.stepIndex) $el.addClass('completed');
                else if (i > self.stepIndex) $el.addClass('next');
            });

            $root.find('.js-tender-steps-footer').toggle(!hasOwnFooter);
            $root.find('.js-tender-steps-footer .js-tender-step-prev').toggle(!hasOwnFooter && index > 0);
            $root.find('.js-tender-steps-footer .js-tender-step-next').toggle(!hasOwnFooter && !isLast);
            $root.find('.js-tender-publish, .js-tender-publish-footer').toggle(isLast && this.tenderId > 0);
            if (step === 'basic') this.syncBasicContinue();
        },

        collectStepData: function (step) {
            const $root = $(this.root);
            const data = { type: this.selectedTypeId };

            if (step === 'privacy') {
                data.is_private = $root.find('input[name="is_private"]:checked').val() === '1' ? 1 : 0;
                data.approval_required = $root.find('input[name="approval_required"]').is(':checked') ? 1 : 0;
                if (data.is_private) {
                    data.invitations = $root.find('.js-invite-select').val() || [];
                }
            } else if (step === 'basic') {
                data.title = $.trim($root.find('.js-field-title').val() || '');
                data.number = $.trim($root.find('.js-field-number').val() || '') || ('DRAFT-' + Date.now());
                $root.find('.js-field-number').val(data.number);
            } else if (step === 'purchase_params') {
                data.end_at = this.composeDatetime(
                    $root.find('.js-field-end-date').val(),
                    $root.find('.js-field-end-time').val()
                );
                const resultAt = this.composeDatetime(
                    $root.find('.js-field-result-date').val(),
                    '00:00'
                );
                if (resultAt) data.opening_at = resultAt;
                if (this.selectedTypeCode === 'prequalification') {
                    data.retendering_enabled = 0;
                    data.itemized_enabled = 0;
                } else {
                    data.retendering_enabled = $root.find('.js-field-retendering').is(':checked') ? 1 : 0;
                    data.itemized_enabled = $root.find('.js-field-itemized').is(':checked') ? 1 : 0;
                }
            } else if (step === 'payment_delivery') {
                data.budget = $.trim($root.find('.js-field-budget').val() || '');
                data.payment_terms = $.trim($root.find('.js-field-payment').val() || '');
                data.delivery_terms = $.trim($root.find('.js-field-delivery').val() || '');
                data.currency = 'RUB';
            } else if (step === 'invitation') {
                const invites = $root.find('.js-invite-select-final').val() || [];
                if (invites.length) data.invitations = invites;
            }
            return data;
        },

        saveCurrentStep: function () {
            const self = this;
            const step = this.steps[this.stepIndex];
            if (step === 'lots') return Promise.resolve(true);

            const data = this.collectStepData(step);
            if (step === 'basic') {
                if (!data.title) {
                    $.AlertManager.showError('Укажите наименование');
                    return Promise.resolve(false);
                }
            }

            const needDraft = (step !== 'privacy' && step !== 'basic' && !this.tenderId);
            const chain = needDraft
                ? this.ensureDraft().then(function (ok) { return ok ? self.postSave(step, data) : false; })
                : this.postSave(step, data);

            return chain.then(function (ok) {
                if (!ok) return false;
                if (step === 'purchase_params' && self.selectedTypeCode === 'price_request') {
                    return self.saveCriterion();
                }
                return true;
            });
        },

        ensureDraft: function () {
            const $root = $(this.root);
            if (this.tenderId > 0) return Promise.resolve(true);
            return this.postSave('basic', {
                type: this.selectedTypeId,
                title: $.trim($root.find('.js-field-title').val() || '') || 'Черновик',
                number: $.trim($root.find('.js-field-number').val() || '') || ('DRAFT-' + Date.now())
            });
        },

        postSave: function (step, data) {
            const self = this;
            const $root = $(this.root);
            return $.fRequest({
                url: '/api/buyer/tender/save/',
                method: 'POST',
                data: { id: this.tenderId || 0, step: step, data: data },
                onSuccess: function (reply) {
                    self.tenderId = parseInt(reply.tender_id || 0, 10) || self.tenderId;
                    $root.find('.js-tender-id').val(String(self.tenderId));
                    const isLast = self.stepIndex === self.steps.length - 1;
                    $root.find('.js-tender-publish, .js-tender-publish-footer').toggle(isLast && self.tenderId > 0);
                    return reply.message || 'Сохранено';
                }
            }).then(function (reply) {
                return !reply.error;
            });
        },

        saveCriterion: function () {
            if (!this.tenderId || !this.criteria.length) return Promise.resolve(true);
            const criteria = this.criteria.map(function (item) {
                return {
                    name: item.name,
                    type: 'non_price',
                    is_mandatory: item.required ? 1 : 0,
                    description: item.description || ''
                };
            });
            return $.fRequest({
                url: '/api/buyer/tender/' + this.tenderId + '/criterion/save/',
                method: 'POST',
                data: { criteria: criteria }
            }).then(function (reply) {
                return !reply.error;
            });
        },

        publish: function () {
            const self = this;
            if (!this.tenderId) {
                $.AlertManager.showError('Сначала сохраните черновик');
                return;
            }
            const before = this.selectedTypeCode === 'price_request'
                ? this.saveCriterion()
                : Promise.resolve(true);

            before.then(function (ok) {
                if (!ok) return;
                $.fRequest({
                    url: '/api/buyer/tender/publish/',
                    method: 'POST',
                    data: { id: self.tenderId },
                    onSuccess: function (reply) {
                        self.closeCreate();
                        return reply.message || 'Опубликовано';
                    }
                });
            });
        }
    });
})(jQuery);
