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
        extraInfoFields: [],
        extraApprovers: [],
        criteriaDrag: null,
        _criteriaDnDBound: false,
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
            this.extraInfoFields = [];
            this.extraApprovers = [];
            this.criteriaDrag = null;
            this.bucket = 'all';
            this.search = '';
            this.filterType = '';
            this.filterStatus = '';
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

            this.bindEvents();
            this.initInviteSelects();
            this.initPurchaseParamsUi();
            this.initPaymentDeliveryUi();
            this.initLotsUi();
            this.initInvitationUi();
            this.loadList();
        },

        bindEvents: function () {
            const self = this;
            const $root = $(this.root);

            $root.find('.js-tenders-reload').click(function () {
                self.loadList();
            });

            $root.find('.js-tenders-list').on('click', '.buyer-tender-card', function (e) {
                if ($(e.target).closest('.buyer-tender-card__more').length) {
                    return;
                }
                const id = parseInt($(this).data('id'), 10) || 0;
                if (id > 0) {
                    window.location.href = '/cabinet/buyer/tender/' + id + '/';
                }
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

        initPaymentDeliveryUi: function () {
            const self = this;
            const $root = $(this.root);

            const syncCharCounter = function ($textarea) {
                const max = parseInt($textarea.attr('maxlength'), 10) || 0;
                const len = String($textarea.val() || '').length;
                $textarea.closest('.zc-textarea').find('.char-counter').text(len + '/' + max);
            };

            $root.find('.zc-step-payment textarea[maxlength]').each(function () {
                syncCharCounter($(this));
            });

            $root.off('input.paymentChars', '.zc-step-payment textarea[maxlength]')
                .on('input.paymentChars', '.zc-step-payment textarea[maxlength]', function () {
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
                        <div class="zc-payment-list__item">
                            <div class="zc-textarea">
                                <textarea class="js-delivery-address" name="delivery_addresses[]" maxlength="500" rows="3" placeholder="Индекс, Страна Область, населенный пункт, Улица, дом, Строение/корпус, квартира/офис"></textarea>
                                <div class="zc-textarea__counter char-counter">0/500</div>
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
                        <div class="zc-payment-list__item">
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
            $addrList.find('.zc-payment-list__item').slice(1).remove();
            $addrList.find('.js-delivery-address').val('');
            $addrList.find('.char-counter').text('0/500');
            $root.find('.js-field-additional-delivery').closest('.zc-textarea').find('.char-counter').text('0/200');

            const $payment = $root.find('.js-field-payment');
            $payment.val(null).trigger('change');

            const $contactList = $root.find('.js-contact-list');
            $contactList.find('.zc-payment-list__item').slice(1).each(function () {
                const $select = $(this).find('select');
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $(this).remove();
            });
            $contactList.find('.js-tender-contact').val(null).trigger('change');
        },

        /* ── Рассылка: инициализация UI ── */
        invitationSelected: [],   // { id, name }

        initInvitationUi: function () {
            const self = this;
            const $root = $(this.root);
            const $step = $root.find('.zc-step-invitation');
            if (!$step.length) return;

            /* Mock-данные поставщиков (в будущем загружать с API) */
            self._invSuppliersMock = {
                all: [
                    { id: 's1', name: 'АО «Металлоснаб»' },
                    { id: 's2', name: 'ООО «СнабСервис»' },
                    { id: 's3', name: 'ИП Козлов А.А.' },
                    { id: 's4', name: 'ООО «ТехноГрупп»' },
                    { id: 's5', name: 'ЗАО «СтальТрейд»' }
                ],
                my: [
                    { id: 's1', name: 'АО «Металлоснаб»' },
                    { id: 's4', name: 'ООО «ТехноГрупп»' }
                ],
                groups: [
                    { id: 'g1', name: 'Группа «Стройматериалы»' },
                    { id: 'g2', name: 'Группа «Металлоизделия»' }
                ]
            };
            self._invActiveTab = 'all';
            self.invitationSelected = [];

            $root.off('click.invTab', '.zc-inv-tab')
                .on('click.invTab', '.zc-inv-tab', function () {
                    self._invActiveTab = $(this).data('tab');
                    $step.find('.zc-inv-tab').removeClass('active');
                    $(this).addClass('active');
                    $step.find('.js-inv-search').val('');
                    self.renderInvSupplierList();
                });

            $root.off('input.invSearch', '.js-inv-search')
                .on('input.invSearch', '.js-inv-search', function () {
                    self.renderInvSupplierList();
                });

            $root.off('click.invItem', '.zc-inv-list__item')
                .on('click.invItem', '.zc-inv-list__item', function () {
                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    var idx = self.invitationSelected.findIndex(function (s) { return s.id === id; });
                    if (idx >= 0) {
                        self.invitationSelected.splice(idx, 1);
                    } else {
                        self.invitationSelected.push({ id: id, name: name });
                    }
                    self.renderInvSupplierList();
                    self.renderInvSelected();
                    self.syncInvPublishBtn();
                });

            $root.off('click.invRemove', '.js-inv-remove')
                .on('click.invRemove', '.js-inv-remove', function () {
                    var id = $(this).data('id');
                    self.invitationSelected = self.invitationSelected.filter(function (s) { return s.id !== id; });
                    self.renderInvSelected();
                    self.renderInvSupplierList();
                    self.syncInvPublishBtn();
                });

            $root.off('click.invClear', '.js-inv-clear-all')
                .on('click.invClear', '.js-inv-clear-all', function () {
                    self.invitationSelected = [];
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
            $root.off('click.invTemplate', '.zc-inv-upload-dropdown__item')
                .on('click.invTemplate', '.zc-inv-upload-dropdown__item', function (e) {
                    e.preventDefault();
                });
            $(document).off('click.invDropdown').on('click.invDropdown', function () {
                $step.find('.js-inv-upload-dropdown').hide();
                $step.find('.js-inv-upload-combo').removeClass('is-open');
            });

            self.renderInvSupplierList();
            self.syncInvPublishBtn();
        },

        addInvEmail: function () {
            var self = this;
            var $root = $(this.root);
            var $input = $root.find('.js-inv-email-input');
            var email = $.trim($input.val() || '');
            if (!email || !email.includes('@')) return;
            if (self.invitationSelected.some(function (s) { return s.id === email; })) {
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

            var tab = self._invActiveTab || 'all';
            var suppliers = (self._invSuppliersMock || {})[tab] || [];
            var q = $.trim($root.find('.js-inv-search').val() || '').toLowerCase();
            if (q) {
                suppliers = suppliers.filter(function (s) {
                    return s.name.toLowerCase().includes(q);
                });
            }

            var $empty = $list.find('.js-inv-list-empty');
            $list.find('.zc-inv-list__item').remove();

            if (!suppliers.length) {
                $empty.show();
                return;
            }
            $empty.hide();

            var checkSvg = '<svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6L5 8.5L9.5 4" stroke="var(--white,#fff)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            suppliers.forEach(function (s) {
                var isChecked = self.invitationSelected.some(function (sel) { return sel.id === s.id; });
                var $item = $('<button type="button" class="zc-inv-list__item' + (isChecked ? ' is-checked' : '') + '">'
                    + '<span class="zc-inv-list__checkbox">' + (isChecked ? checkSvg : '') + '</span>'
                    + '<span class="zc-inv-list__name"></span>'
                    + '</button>');
                $item.data('id', s.id).data('name', s.name);
                $item.find('.zc-inv-list__name').text(s.name);
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
                    var $item = $('<div class="zc-inv-selected__item">'
                        + '<span class="zc-inv-selected__item-name"></span>'
                        + '<button type="button" class="zc-inv-selected__item-remove js-inv-remove" title="Убрать">'
                        + '<svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 2l10 10M12 2L2 12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>'
                        + '</button>'
                        + '</div>');
                    $item.find('.zc-inv-selected__item-name').text(s.name);
                    $item.find('.js-inv-remove').data('id', s.id);
                    $items.append($item);
                });
            }

            /* E-mail теги */
            $emailTags.empty();
            if (emailSelected.length) {
                emailSelected.forEach(function (s) {
                    var $tag = $('<span class="zc-inv-email-tag">'
                        + '<span class="zc-inv-email-tag__text"></span>'
                        + '<button type="button" class="zc-inv-email-tag__remove js-inv-remove">'
                        + '<svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 2l10 10M12 2L2 12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>'
                        + '</button>'
                        + '</span>');
                    $tag.find('.zc-inv-email-tag__text').text(s.name);
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
            /* Вручную — можно публиковать всегда; Автоматически — нужны получатели */
            var enabled = sendType === 'manual' || this.invitationSelected.length > 0;
            $btn.prop('disabled', !enabled);
            /* Надпись на кнопке */
            $btn.text(sendType === 'auto' ? 'Опубликовать' : 'Опубликовать без рассылки');
        },

        initLotsUi: function () {
            const self = this;
            const $root = $(this.root);
            const $step = $root.find('.zc-step-lots');
            if (!$step.length) return;

            $root.off('click.lotsTab', '.js-lots-tab')
                .on('click.lotsTab', '.js-lots-tab', function () {
                    self.lotsActiveTab = String($(this).data('tab') || 'lots');
                    self.syncLotsTabUi();
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
                    $(this).closest('.zc-lots-upload').find('.js-lots-file-input').trigger('click');
                });

            $root.off('change.lotsFile', '.js-lots-file-input')
                .on('change.lotsFile', '.js-lots-file-input', function () {
                    const file = this.files && this.files[0] ? this.files[0] : null;
                    const kind = String($(this).data('kind') || 'lot');
                    if (kind === 'doc') {
                        self.updateSelectedDoc('fileName', file ? file.name : '');
                        self.updateSelectedDoc('fileSize', file ? file.size : 0);
                    } else {
                        self.updateSelectedLot('fileName', file ? file.name : '');
                        self.updateSelectedLot('fileSize', file ? file.size : 0);
                    }
                    this.value = '';
                    if (kind === 'doc') {
                        self.renderDocEditor();
                        self.renderDocsList();
                    } else {
                        self.renderLotEditor();
                        self.renderLotsList();
                    }
                });

            $root.off('click.lotsFileRemove', '.js-lots-file-remove')
                .on('click.lotsFileRemove', '.js-lots-file-remove', function () {
                    const kind = String($(this).data('kind') || 'lot');
                    if (kind === 'doc') {
                        self.updateSelectedDoc('fileName', '');
                        self.updateSelectedDoc('fileSize', 0);
                        self.renderDocEditor();
                        self.renderDocsList();
                    } else {
                        self.updateSelectedLot('fileName', '');
                        self.updateSelectedLot('fileSize', 0);
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
            const isPrequal = this.selectedTypeCode === 'prequalification';
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
                fileSize: 0
            }];

            const docId = this.nextLotId('doc_');
            this.lotDocuments = [{
                id: docId,
                name: isPrequal ? '' : 'Коммерческое предложение',
                description: '',
                fileName: '',
                fileSize: 0,
                isRequired: !isPrequal
            }];

            this.selectedLotId = posId;
            this.selectedDocId = docId;

            const $root = $(this.root);
            $root.find('.js-lots-edit').show();
            $root.find('.js-lots-preview-view').hide();
            $root.find('.js-lots-error-banner').hide();
            $root.find('.js-lots-docs-tab-label').text(isPrequal ? 'Запрашиваемые документы' : 'Документы');
            this.syncLotsTabUi();
            this.renderLotsList();
            this.renderDocsList();
            this.renderLotEditor();
            this.renderDocEditor();
            this.renderLotsBudget();
            this.syncLotsBudgetVisibility();
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
            $root.find('.js-lots-tab').removeClass('active')
                .filter('[data-tab="' + this.lotsActiveTab + '"]').addClass('active');
            $root.find('.js-lots-list').toggle(!isDocs);
            $root.find('.js-docs-list').toggle(isDocs);
            $root.find('.js-lot-editor-wrap').toggle(!isDocs);
            $root.find('.js-doc-editor-wrap').toggle(isDocs);
            $root.find('.js-lots-add-label').text(isDocs ? 'Добавить документ' : 'Добавить позицию');
            $root.find('.js-lots-editor-empty-text').text(
                isDocs ? 'Выберите документ из списка слева' : 'Выберите позицию из списка слева'
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
                    $el.closest('.zc-lots-textarea').find('.js-lots-char-counter')
                        .text(String(value || '').length + '/200');
                }
            } else {
                this.updateSelectedLot(field, value);
                if (field === 'name' || field === 'quantity' || field === 'unit'
                    || field === 'maxPriceNoVat' || field === 'vatRate') {
                    this.renderLotsList();
                }
                if (field === 'name') {
                    this.renderLotEditorHead();
                }
                if (field === 'comment') {
                    $el.closest('.zc-lots-textarea').find('.js-lots-char-counter')
                        .text(String(value || '').length + '/200');
                }
                if (field === 'maxPriceNoVat' || field === 'quantity' || field === 'vatRate') {
                    if (this.budgetActionsHidden) this.budgetActionsHidden = false;
                    this.renderLotsBudget();
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
                fileSize: 0
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
                isRequired: false
            });
            this.selectedDocId = id;
            this.lotsActiveTab = 'documents';
            this.syncLotsTabUi();
            this.renderDocsList();
            this.renderDocEditor();
        },

        deleteLotDocument: function (id) {
            if (this.lotDocuments.length <= 1) return;
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
                return '<div class="zc-lot-item__status" title="Заполнено">'
                    + '<svg viewBox="0 0 19.5 19.5" fill="none"><path d="M9.75 0C15.1348 0 19.5 4.36522 19.5 9.75C19.5 15.1348 15.1348 19.5 9.75 19.5C4.36522 19.5 0 15.1348 0 9.75C0 4.36522 4.36522 0 9.75 0ZM13.1855 6.88965C12.8485 6.64913 12.3803 6.72749 12.1396 7.06445L8.9043 11.5938L7.28027 9.96973C6.98738 9.67683 6.51262 9.67683 6.21973 9.96973C5.92683 10.2626 5.92683 10.7374 6.21973 11.0303L8.46973 13.2803C8.62555 13.4361 8.84191 13.5152 9.06152 13.4971C9.28124 13.4789 9.48221 13.3649 9.61035 13.1855L13.3604 7.93555C13.6009 7.59851 13.5225 7.13034 13.1855 6.88965Z" fill="var(--success-dark)"/></svg>'
                    + '</div>';
            }
            if (status === 'incomplete') {
                return '<div class="zc-lot-item__status" title="Не заполнено">'
                    + '<svg viewBox="0 0 19.5 19.5" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 9.75C0 4.36522 4.36522 0 9.75 0C15.1348 0 19.5 4.36522 19.5 9.75C19.5 15.1348 15.1348 19.5 9.75 19.5C4.36522 19.5 0 15.1348 0 9.75ZM9.75 6C10.1642 6 10.5 6.33579 10.5 6.75V10.5C10.5 10.9142 10.1642 11.25 9.75 11.25C9.33579 11.25 9 10.9142 9 10.5V6.75C9 6.33579 9.33579 6 9.75 6ZM9.75 14.25C10.1642 14.25 10.5 13.9142 10.5 13.5C10.5 13.0858 10.1642 12.75 9.75 12.75C9.33579 12.75 9 13.0858 9 13.5C9 13.9142 9.33579 14.25 9.75 14.25Z" fill="var(--orange-600)"/></svg>'
                    + '</div>';
            }
            return '<div class="zc-lot-item__status zc-lot-item__status--draft" title="Черновик"></div>';
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
                return (i ? '<span class="zc-lot-item__dot"></span>' : '')
                    + '<span class="zc-lot-item__param">' + $('<div>').text(part).html() + '</span>';
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
                    '<div class="zc-lot-item js-lot-item' + (selected ? ' is-selected' : '') + '" data-id="' + pos.id + '">'
                    + '<div class="zc-lot-item__border" aria-hidden="true"></div>'
                    + '<div class="zc-lot-item__row">'
                    + '<div class="zc-lot-item__num">' + (idx + 1) + '</div>'
                    + '<div class="zc-lot-item__main">'
                    + '<div class="zc-lot-item__title"></div>'
                    + (params ? '<div class="zc-lot-item__params">' + params + '</div>' : '')
                    + '</div>'
                    + '<div class="zc-lot-item__right">'
                    + '<div class="zc-lot-item__actions">'
                    + '<button type="button" class="zc-lot-item__action js-lot-dup" title="Дублировать">'
                    + '<svg viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 3C0 1.34315 1.34315 0 3 0H11.25C12.9069 0 14.25 1.34315 14.25 3V4.5H15C16.6569 4.5 18 5.84315 18 7.5V15C18 16.6569 16.6569 18 15 18H7.5C5.84315 18 4.5 16.6569 4.5 15V14.25H3C1.34315 14.25 0 12.9069 0 11.25V3ZM4.5 12.75V7.5C4.5 5.84315 5.84315 4.5 7.5 4.5H12.75V3C12.75 2.17157 12.0784 1.5 11.25 1.5H3C2.17157 1.5 1.5 2.17157 1.5 3V11.25C1.5 12.0784 2.17157 12.75 3 12.75H4.5ZM7.5 6C6.67157 6 6 6.67157 6 7.5V15C6 15.8284 6.67157 16.5 7.5 16.5H15C15.8284 16.5 16.5 15.8284 16.5 15V7.5C16.5 6.67157 15.8284 6 15 6H7.5Z" fill="currentColor"/></svg>'
                    + '</button>'
                    + '<button type="button" class="zc-lot-item__action js-lot-del" title="Удалить">'
                    + '<svg><use href="#icon-trash"></use></svg>'
                    + '</button>'
                    + '</div>'
                    + self.lotStatusIconHtml(status)
                    + '</div></div></div>'
                );
                $item.find('.zc-lot-item__title').text(title);
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
                    statusHtml = '<div class="zc-doc-status zc-doc-status--valid"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6.2 4.8 8.5 9.5 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>';
                } else if (status === 'incomplete') {
                    statusHtml = '<div class="zc-doc-status zc-doc-status--incomplete"><span class="zc-doc-status__dot"></span></div>';
                }
                const $item = $(
                    '<div class="zc-lot-item zc-lot-item--doc js-lot-item js-doc-item' + (selected ? ' is-selected' : '') + '" data-id="' + doc.id + '">'
                    + '<div class="zc-lot-item__border" aria-hidden="true"></div>'
                    + '<div class="zc-lot-item__row">'
                    + '<div class="zc-lot-item__num">' + (idx + 1) + '</div>'
                    + '<div class="zc-lot-item__main">'
                    + '<div class="flex-row" style="display:flex;align-items:center;gap:8px;min-width:0;width:100%;">'
                    + '<div class="zc-lot-item__title"></div>'
                    + (doc.isRequired ? '<span class="zc-lot-item__badge">Обяз.</span>' : '')
                    + '</div>'
                    + (doc.description ? '<div class="zc-lot-item__desc"></div>' : '')
                    + '</div>'
                    + statusHtml
                    + '<button type="button" class="zc-lot-item__icon-btn js-doc-del" title="Удалить"><svg><use href="#icon-trash"></use></svg></button>'
                    + '</div></div>'
                );
                $item.find('.zc-lot-item__title').text(title);
                if (doc.description) $item.find('.zc-lot-item__desc').text(doc.description);
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
            return '<div class="zc-lots-input' + (opts.error ? ' is-error' : '') + '">'
                + '<div class="zc-lots-input__border" aria-hidden="true"></div>'
                + '<div class="zc-lots-input__inner">'
                + (opts.select
                    ? '<select class="js-lot-field" data-field="' + opts.field + '">' + opts.options + '</select>'
                      + '<svg class="zc-lots-input__chevron" viewBox="0 0 20 20" fill="none"><path d="M5.75 8.25L10 12.5L14.25 8.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></svg>'
                    : '<input type="text" class="' + (opts.doc ? 'js-doc-field' : 'js-lot-field') + '" data-field="' + opts.field + '" value="" placeholder="' + (opts.placeholder || '') + '">'
                      + (opts.endIcon || ''))
                + '</div></div>';
        },

        lotsUploadHtml: function (kind, fileName, fileSize) {
            let fileHtml = '';
            if (fileName) {
                const ext = (String(fileName).split('.').pop() || 'FILE').toUpperCase();
                const kb = Math.round((fileSize || 0) / 1024);
                fileHtml = '<div class="zc-lots-upload__file">'
                    + '<div class="zc-lots-upload__file-main">'
                    + '<div class="zc-lots-upload__ext">' + $('<div>').text(ext).html() + '</div>'
                    + '<div><div class="zc-lots-upload__name"></div><div class="zc-lots-upload__size">' + kb + ' KB</div></div>'
                    + '</div>'
                    + '<button type="button" class="zc-lots-upload__remove js-lots-file-remove" data-kind="' + kind + '" aria-label="Удалить">'
                    + '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>'
                    + '</button></div>';
            }
            return '<div class="zc-lots-upload">'
                + '<div class="zc-lots-upload__zone js-lots-upload-zone">'
                + '<div class="zc-lots-upload__inner">'
                + '<div class="zc-lots-upload__icon"><svg><use href="#icon-file-upload"></use></svg></div>'
                + '<div class="zc-lots-upload__text"><span class="zc-lots-upload__link">Нажмите сюда</span> или перетащите файл для загрузки файлов</div>'
                + '<div class="zc-lots-upload__hints"><span>Поддерживаемый формат: .word, PDF </span><span>Макс. размер файлов: 10 MB</span></div>'
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

            const searchIcon = '<div class="zc-lots-input__icon"><svg><use href="#icon-search"></use></svg></div>';
            let priceRow = '';
            if (!isPrequal) {
                priceRow = '<div class="zc-lots-field zc-lots-field--price"><div class="zc-lots-field__label">Макс. цена без НДС</div>'
                    + this.lotsInputHtml({ field: 'maxPriceNoVat', placeholder: '0' }) + '</div>'
                    + '<div class="zc-lots-field zc-lots-field--vat"><div class="zc-lots-field__label">НДС</div>'
                    + this.lotsInputHtml({ field: 'vatRate', select: true, options: vatOpts }) + '</div>';
            }

            const html = '<div class="zc-lots-editor">'
                + '<div class="zc-lots-editor__head"><div class="zc-lots-editor__head-inner">'
                + '<div class="zc-lots-editor__title js-lot-editor-title"></div>'
                + '<div class="zc-lots-editor__actions">'
                + '<button type="button" class="zc-lot-item__icon-btn js-lot-dup" title="Дублировать" data-id="' + pos.id + '">'
                + '<svg viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 2.5C0 1.11929 1.11929 0 2.5 0H9.375C10.7557 0 11.875 1.11929 11.875 2.5V3.75H12.5C13.8807 3.75 15 4.86929 15 6.25V12.5C15 13.8807 13.8807 15 12.5 15H6.25C4.86929 15 3.75 13.8807 3.75 12.5V11.875H2.5C1.11929 11.875 0 10.7557 0 9.375V2.5ZM3.75 10.625V6.25C3.75 4.86929 4.86929 3.75 6.25 3.75H10.625V2.5C10.625 1.80964 10.0654 1.25 9.375 1.25H2.5C1.80964 1.25 1.25 1.80964 1.25 2.5V9.375C1.25 10.0654 1.80964 10.625 2.5 10.625H3.75ZM6.25 5C5.55964 5 5 5.55964 5 6.25V12.5C5 13.1904 5.55964 13.75 6.25 13.75H12.5C13.1904 13.75 13.75 13.1904 13.75 12.5V6.25C13.75 5.55964 13.1904 5 12.5 5H6.25Z" fill="currentColor"/></svg>'
                + '</button>'
                + '<button type="button" class="zc-lot-item__icon-btn js-lot-del" title="Удалить" data-id="' + pos.id + '"><svg><use href="#icon-trash"></use></svg></button>'
                + '</div></div></div>'
                + '<div class="zc-lots-editor__body"><div class="zc-lots-editor__body-inner">'
                + '<div class="zc-lots-field"><div class="zc-lots-field__label">Наименование позиции</div>'
                + this.lotsInputHtml({ field: 'name', placeholder: 'Введите наименование позиции' }) + '</div>'
                + '<div class="zc-lots-field-row">'
                + '<div class="zc-lots-field"><div class="zc-lots-field__label">Количество</div>'
                + this.lotsInputHtml({ field: 'quantity', placeholder: isPrequal ? '0' : '1' }) + '</div>'
                + '<div class="zc-lots-field"><div class="zc-lots-field__label">Ед. измерения</div>'
                + this.lotsInputHtml({ field: 'unit', select: true, options: unitOpts }) + '</div>'
                + priceRow
                + '</div>'
                + (isPrequal ? '' : (
                    '<div class="zc-lots-field"><div class="zc-lots-field__label">Место поставки</div>'
                    + this.lotsInputHtml({ field: 'deliveryPlace', placeholder: 'Введите или выберите из списка', endIcon: searchIcon }) + '</div>'
                    + '<button type="button" class="zc-lots-add-field js-lots-add-field">'
                    + '<svg viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 3.125C10.3452 3.125 10.625 3.40482 10.625 3.75V9.375H16.25C16.5952 9.375 16.875 9.65482 16.875 10C16.875 10.3452 16.5952 10.625 16.25 10.625H10.625V16.25C10.625 16.5952 10.3452 16.875 10 16.875C9.65482 16.875 9.375 16.5952 9.375 16.25V10.625H3.75C3.40482 10.625 3.125 10.3452 3.125 10C3.125 9.65482 3.40482 9.375 3.75 9.375H9.375V3.75C9.375 3.40482 9.65482 3.125 10 3.125Z" fill="currentColor"/></svg>'
                    + '<span>Добавить поле</span></button>'
                ))
                + '<div class="zc-lots-field"><div class="zc-lots-field__section">Добавить закупочную документацию</div>'
                + this.lotsUploadHtml('lot', pos.fileName, pos.fileSize) + '</div>'
                + '<div class="zc-lots-field"><div class="zc-lots-field__section">Комментарий</div>'
                + '<div class="zc-lots-textarea"><div class="zc-lots-textarea__border" aria-hidden="true"></div>'
                + '<div class="zc-lots-textarea__inner">'
                + '<textarea class="js-lot-field" data-field="comment" maxlength="200" placeholder="Введите"></textarea>'
                + '<div class="zc-lots-textarea__counter js-lots-char-counter">' + String(pos.comment || '').length + '/200</div>'
                + '</div></div></div>'
                + '</div></div></div>';

            $wrap.html(html);
            $wrap.find('.js-lot-editor-title').text(pos.name || ('Позиция №' + (idx + 1)));
            $wrap.find('[data-field="name"]').val(pos.name || '');
            $wrap.find('[data-field="quantity"]').val(pos.quantity || '');
            $wrap.find('[data-field="maxPriceNoVat"]').val(pos.maxPriceNoVat === '0' ? '' : (pos.maxPriceNoVat || ''));
            $wrap.find('[data-field="deliveryPlace"]').val(pos.deliveryPlace || '');
            $wrap.find('[data-field="comment"]').val(pos.comment || '');
            if (pos.fileName) $wrap.find('.zc-lots-upload__name').text(pos.fileName);
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

            const html = '<div class="zc-lots-editor">'
                + '<div class="zc-lots-editor__head"><div class="zc-lots-editor__head-inner">'
                + '<div class="zc-lots-editor__title js-doc-editor-title"></div>'
                + '<div class="zc-lots-editor__actions">'
                + '<button type="button" class="zc-lot-item__icon-btn js-doc-del" title="Удалить"><svg><use href="#icon-trash"></use></svg></button>'
                + '</div></div></div>'
                + '<div class="zc-lots-editor__body"><div class="zc-lots-editor__body-inner zc-lots-editor__body-inner--doc">'
                + '<div class="zc-lots-field"><div class="zc-lots-field__label">Название документа'
                + (isPrequal ? '<span class="zc-lots-field__req">*</span>' : '') + '</div>'
                + this.lotsInputHtml({ field: 'name', placeholder: isPrequal ? 'Введите название документа' : 'Введите название', doc: true }) + '</div>'
                + '<div class="zc-lots-field"><div class="zc-lots-field__section">Описание</div>'
                + '<div class="zc-lots-textarea"><div class="zc-lots-textarea__border" aria-hidden="true"></div>'
                + '<div class="zc-lots-textarea__inner">'
                + '<textarea class="js-doc-field" data-field="description" maxlength="200" placeholder="Введите"></textarea>'
                + '<div class="zc-lots-textarea__counter js-lots-char-counter">' + String(doc.description || '').length + '/200</div>'
                + '</div></div></div>'
                + (isPrequal ? '' : '<div class="zc-lots-editor-divider" aria-hidden="true"></div>')
                + '<div class="zc-lots-field"><div class="zc-lots-field__section">'
                + (isPrequal ? 'Добавить закупочную документацию' : 'Загрузить шаблон документа') + '</div>'
                + this.lotsUploadHtml('doc', doc.fileName, doc.fileSize) + '</div>'
                + '<div class="zc-lots-check js-doc-required' + (doc.isRequired ? ' is-checked' : '') + '">'
                + '<div class="zc-lots-check__box">'
                + (doc.isRequired ? '<svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7.2 5.2 9.9 11.5 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>' : '')
                + '</div>'
                + '<span class="zc-lots-check__label">'
                + (isPrequal ? 'Отметить обязательным' : 'Отметить загрузку обязательным')
                + '</span></div>'
                + '</div></div></div>';

            $wrap.html(html);
            $wrap.find('.js-doc-editor-title').text(title);
            $wrap.find('[data-field="name"]').val(doc.name || '');
            $wrap.find('[data-field="description"]').val(doc.description || '');
            if (doc.fileName) $wrap.find('.zc-lots-upload__name').text(doc.fileName);
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
                    '<div class="zc-lots-preview-empty">'
                    + '<div class="zc-lots-preview-empty__icon"><svg width="24" height="24"><use href="#icon-document-text"></use></svg></div>'
                    + '<div class="zc-lots-preview-empty__text">Нет добавленных позиций</div></div>'
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
                        + '<td class="is-center"><button type="button" class="zc-lots-preview-edit-btn" title="Редактировать"><svg width="16" height="16"><use href="#icon-pencil"></use></svg></button></td>'
                        + '</tr>';
                });
                $pos.html(
                    '<div class="zc-lots-preview-table-wrap"><table class="zc-lots-preview-table' + (isPrequal ? ' is-prequal' : '') + '">'
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
                    '<div class="zc-lots-preview-empty">'
                    + '<div class="zc-lots-preview-empty__icon"><svg width="24" height="24"><use href="#icon-document-text"></use></svg></div>'
                    + '<div class="zc-lots-preview-empty__text">Документы не требуются</div></div>'
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
                            ? '<span class="zc-lot-item__badge">Да</span>'
                            : '<span class="is-muted">Нет</span>') + '</td>'
                        + '</tr>';
                });
                $docs.html(
                    '<div class="zc-lots-preview-table-wrap"><table class="zc-lots-preview-table" style="min-width:640px">'
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
                    $nameRow.append($('<span class="criteria-badge">').text('Обязательное'));
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

            $root.find('.js-field-title, .js-field-number').val('');
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
            this.invitationSelected = [];
            this.extraInfoFields = [];
            this.extraApprovers = [];
            this.renderExtraInfoFields();
            this.renderExtraApprovers();
            this.renderCriteria();
            this.syncMinStepUi();
            this.syncBasicContinue();

            $root.find('.js-tenders-list-view').hide();
            $root.find('.js-tender-create-view').show();
            $root.addClass('tender-create');
            this.goStep(0);
        },

        closeCreate: function () {
            const $root = $(this.root);
            $root.removeClass('tender-create');
            $root.find('.js-tender-create-body').removeClass('is-wide');
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
            const isWide = step === 'lots' || step === 'invitation';

            $root.find('.js-tender-create-body').toggleClass('is-wide', isWide);

            $root.find('.js-tender-step-panel').hide();
            $root.find('.js-tender-step-panel[data-step="' + step + '"]').show();

            $root.find('.js-tender-steps .creation-step').each(function (i) {
                const $el = $(this);
                $el.removeClass('next completed');
                if (i < self.stepIndex) $el.addClass('completed');
                else if (i > self.stepIndex) $el.addClass('next');
            });

            if (step === 'basic') this.syncBasicContinue();
            if (step === 'invitation') {
                this.renderInvSupplierList();
                this.renderInvSelected();
                this.syncInvPublishBtn();
            }
            if (step === 'lots') {
                if (!this.lotPositions.length) {
                    this.resetLotsUi();
                } else {
                    this.lotsPreviewMode = false;
                    $root.find('.js-lots-edit').show();
                    $root.find('.js-lots-preview-view').hide();
                    $root.find('.js-lots-docs-tab-label').text(
                        this.isLotsPrequal() ? 'Запрашиваемые документы' : 'Документы'
                    );
                    this.syncLotsTabUi();
                    this.renderLotsList();
                    this.renderDocsList();
                    this.renderLotEditor();
                    this.renderDocEditor();
                    this.renderLotsBudget();
                }
            }
        },

        collectStepData: function (step) {
            const $root = $(this.root);
            const data = {};

            if (step === 'privacy') {
                data.is_private = $root.find('input[name="is_private"]:checked').val() === '1' ? 1 : 0;
                data.approval_required = $root.find('input[name="approval_required"]').is(':checked') ? 1 : 0;
                if (data.is_private) {
                    data.invitations = $root.find('.js-invite-select').val() || [];
                }
            } else if (step === 'basic') {
                data.title = $.trim($root.find('.js-field-title').val() || '');
                data.number = $.trim($root.find('.js-field-number').val() || '');
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
                    data.itemized_enabled = $root.find('.js-field-itemized').is(':checked') ? 1 : 0;
                }
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
                /* Выбранные поставщики (ID из системы + email вне системы) */
                const systemIds = this.invitationSelected
                    .filter(function (s) { return !s.id.includes('@'); })
                    .map(function (s) { return s.id; });
                if (systemIds.length) data.invitations = systemIds;
                /* Дополнительное сообщение */
                const addMsg = $.trim($root.find('.js-inv-msg-textarea').val() || '');
                if (addMsg) data.invitation_message = addMsg;
                /* Тип отправки */
                data.invitation_send_type = $root.find('input[name="inv_send_type"]:checked').val() || 'manual';
            }
            return data;
        },

        /** Все поля мастера одним объектом (для save draft / publish). */
        collectAllFormData: function () {
            const self = this;
            const data = { type: this.selectedTypeId };
            this.steps.forEach(function (step) {
                if (step === 'lots') return;
                Object.assign(data, self.collectStepData(step));
            });
            if (this.selectedTypeCode === 'price_request' || this.selectedTypeCode === 'proposal_request') {
                data.criteria = (this.criteria || []).map(function (item) {
                    return {
                        name: item.name || '',
                        type: item.type || 'non_price',
                        is_mandatory: item.required ? 1 : 0,
                        description: item.description || ''
                    };
                }).filter(function (row) {
                    return $.trim(row.name) !== '';
                });
            }
            return data;
        },

        saveDraft: function (options) {
            const self = this;
            const opts = options || {};
            const $root = $(this.root);
            const data = this.collectAllFormData();

            if (!data.title) {
                $.AlertManager.showError('Укажите наименование');
                const basicIndex = this.steps.indexOf('basic');
                if (basicIndex >= 0) this.goStep(basicIndex);
                return Promise.resolve(false);
            }
            if (!data.number) {
                data.number = 'DRAFT-' + Date.now();
                $root.find('.js-field-number').val(data.number);
            }

            if (opts.publish
                && (this.selectedTypeCode === 'price_request' || this.selectedTypeCode === 'proposal_request')
                && !(data.criteria && data.criteria.length)
            ) {
                $.AlertManager.showError('Добавьте хотя бы один критерий оценки для запроса цен');
                const paramsIndex = this.steps.indexOf('purchase_params');
                if (paramsIndex >= 0) this.goStep(paramsIndex);
                return Promise.resolve(false);
            }

            const persist = function () {
                // Ошибки save всегда показываем (критерии/поля); silent только глушил и их.
                return self.postSave(data, { silent: false });
            };

            const chain = !this.tenderId
                ? this.ensureDraft(data).then(function (ok) { return ok ? persist() : false; })
                : persist();

            return chain.then(function (ok) {
                if (!ok) return false;
                if (opts.publish) {
                    return self.requestPublish();
                }
                return true;
            });
        },

        ensureDraft: function (data) {
            const self = this;
            const $root = $(this.root);
            if (this.tenderId > 0) return Promise.resolve(true);

            const form = data || this.collectAllFormData();
            return $.fRequest({
                url: '/api/buyer/tender/create/',
                method: 'POST',
                showMessages: false,
                data: {
                    type: form.type || this.selectedTypeId,
                    title: form.title,
                    number: form.number
                },
                onSuccess: function (reply) {
                    self.tenderId = parseInt(reply.tender_id || 0, 10) || self.tenderId;
                    $root.find('.js-tender-id').val(String(self.tenderId));
                }
            }).then(function (reply) {
                return !reply.error && self.tenderId > 0;
            });
        },

        postSave: function (data, options) {
            const self = this;
            const $root = $(this.root);
            const opts = options || {};
            if (!this.tenderId) {
                return Promise.resolve(false);
            }

            const payload = Object.assign({ id: this.tenderId }, data || {});
            delete payload.type;

            return $.fRequest({
                url: '/api/buyer/tender/save/',
                method: 'POST',
                showMessages: !opts.silent,
                data: payload,
                onSuccess: function (reply) {
                    self.tenderId = parseInt(reply.tender_id || 0, 10) || self.tenderId;
                    $root.find('.js-tender-id').val(String(self.tenderId));
                    return opts.silent ? false : (reply.message || 'Сохранено');
                }
            }).then(function (reply) {
                return !reply.error;
            });
        },

        requestPublish: function () {
            const self = this;
            return $.fRequest({
                url: '/api/buyer/tender/publish/',
                method: 'POST',
                data: { id: self.tenderId },
                onSuccess: function (reply) {
                    self.closeCreate();
                    return reply.message || 'Опубликовано';
                }
            }).then(function (reply) {
                return !reply.error;
            });
        },

        publish: function () {
            this.saveDraft({ publish: true });
        }
    });
})(jQuery);
