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
})(jQuery);