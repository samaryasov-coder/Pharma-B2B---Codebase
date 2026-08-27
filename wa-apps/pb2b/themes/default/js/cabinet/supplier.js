(function ($) {
    $.Cabinet.registerPage('accreditation', {
        tabs: null,
        customerRequestTable: null,
        idCustomerRequestTable: null,

        init: function(root) {
            this.root = root;
            this.idCustomerRequestTable = '#customerRequestTable';
            this.tabs = new TabManager({
                container: '.cabinet-container',
            });


            this.bindEvents();
            this.initTables();
        },

        bindEvents: function (){
            $('.create-request').click(function () {
                var dialog = new $.DialogManager({
                    url: `/cabinet/accreditation/form/request/`,
                    width: '500px',
                    onOpen: function($container) {
                        var $form = $container.find('form');

                        $form.on('input', '#comment', function() {
                            const max = this.maxLength || 200;
                            const length = this.value.length;

                            $(this).siblings('.char-counter').text(length + '/' + max);
                        });

                        $form.fSend({
                            onSuccess: function() {
                                htmx.trigger('.js-main-content', 'refresh');
                                dialog.close();
                            }
                        });


                        $form.find('#select_documents').select2({
                            language: 'ru',
                            placeholder: 'Выбрать',
                            allowClear: true,
                            multiple: true,
                            ajax: {
                                url: '/api/categories',
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    return { search: params.term };
                                },
                                processResults: function (data) {
                                    return {
                                        results: data.map(item => ({
                                            id: item.id,
                                            text: item.name
                                        }))
                                    };
                                },
                                cache: true
                            }
                        });
                    }
                });
            });
        },

        initTables: function (){
            const self = this;

            this.customerRequestTable = $(this.idCustomerRequestTable).DataTable({
                order: [[0, 'desc']],
                deferLoading: 0,
                ajax: {
                    url: '/api/supplier/docflow/request/list/',
                    type: 'GET'
                },
                columnDefs: [
                    ...$.Cabinet.getBaseColumnDefs(),
                    {
                        targets: 0,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return `<a class="button link large" hx-get="request/${row.id}/">${data}</a>`;
                        }
                    },
                    {
                        targets: 1,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return data.fullname;
                        }
                    },
                    {
                        target: 2,
                        type: 'datetime',
                        render: function(data, type, row) {
                            if (!data) return '';

                            const dt = luxon.DateTime.fromFormat(data, 'yyyy-MM-dd HH:mm:ss').setLocale('ru');
                            const months = [
                                'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
                                'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'
                            ];
                            return `${dt.toFormat('dd')} ${months[dt.month - 1]} ${dt.toFormat('yyyy, HH:mm')}`;
                        },
                    },
                    {
                        target: 4,
                        render: function (data, type, row, meta) {
                            if (!data) return '';
                            return `<span class="badge ${data.type}">${data.name}</span>`;
                        }
                    },
                    {
                        target: 5,
                        type: 'datetime',
                        render: function(data, type, row) {
                            if (!data) return '';
                            return luxon.DateTime.fromFormat(data, 'yyyy-MM-dd HH:mm:ss').toFormat('dd.MM.yyyy');
                        },
                    }
                ],
                columns: [
                    { data: 'procedure_code' },
                    { data: 'company_reviewer' },
                    { data: 'expires_datetime' },
                    { data: 'code' },
                    { data: 'status' },
                    { data: 'create_datetime' },
                ],
            });
        }
    })

    $.Cabinet.registerPage('request', {
        $root: null,
        id: null,
        cards: null,
        $submitRequest: null,
        $cancelRequest: null,
        MAX_SIZE: null,

        init(root) {
            this.$root = $(root);
            this.id = this.$root.data("id");
            this.cards = new Map();
            this.$submitRequest = $(".submit-request");
            this.$cancelRequest = $(".cancel-request");
            this.MAX_SIZE = 10 * 1024 * 1024;

            this.initCards();
            this.bindEvents();
        },

        MODE_MAP: {
            empty:          "editable",
            uploaded:       "editable",
            loading:        "locked",
            success:        "readonly",
            error:          "editable",
            error_format:   "editable",
            error_size:     "editable",
            server_edit:    "editable",
            server_locked:  "readonly"
        },

        PERMISSIONS: {
            locked:   { view: false, edit: false, comment: false },
            readonly: { view: true,  edit: false, comment: false },
            editable: { view: true,  edit: true,  comment: true  }
        },

        createFileState(opts = {}) {
            return {
                data: opts.data || null,
                name: opts.name || "",
                size: opts.size || "",
                text: opts.text || ""
            };
        },

        getMode(status) {
            return this.MODE_MAP[status] || "editable";
        },

        getPermissions(status) {
            const mode = this.getMode(status);
            return this.PERMISSIONS[mode];
        },



        initCards() {
            this.$root.find("[data-doc]").each((_, el) => {
                const $card = $(el);
                const id = $card.data("id");

                const state = this.buildState($card);
                this.cards.set(id, state);

                this.renderCard(id);
            });
        },

        buildState($card) {
            const raw = JSON.parse($card.attr("data-state") || "{}");
            let file = this.createFileState();
            let status = null;

            if (raw.file) {
                file = this.createFileState({
                    name: raw.file.name,
                    size: $.FileUtils.formatFileSize(raw.file.size),
                    text: "Документ загружен"
                });
                status = "server_edit";
            }
            else {
                file = this.createFileState();
                status = "empty";
            }

            if (!raw.status)
                status = "server_locked";

            return {
                file,
                status: status,
                comment: raw.comment || "",
                editing: false
            };
        },

        setState(id, patch) {
            const prev = this.cards.get(id);
            const next = { ...prev, ...patch };

            this.cards.set(id, next);
            this.renderCard(id);
        },


        bindEvents() {
            this.$submitRequest.click(() => this.handleSubmit());
            this.$cancelRequest.click(() => this.handleCancel());

            this.$root.on("click", "[data-action]", async (e) => {
                const action = $(e.currentTarget).data("action");
                const id = $(e.currentTarget).closest("[data-doc]").data("id");

                switch (action) {
                    case "attach":
                    case "replace-file":
                        await this.handleAttach(id);
                        break;

                    case "add-comment":
                    case "edit-comment":
                        this.setState(id, { editing: true });
                        break;

                    case "cancel-comment":
                        this.setState(id, { editing: false });
                        break;

                    case "save-comment":
                        this.handleSaveComment(id);
                        break;

                    case "remove-file":
                        this.setState(id, {
                            file: this.createFileState(),
                            comment: "",
                            status: "empty",
                            editing: false
                        });
                        break;
                }
            });
        },

        async handleAttach(id) {
            const file = await this.getFileFromDialog();
            if (!file) return;

            if (!$.FileUtils.isDocFile(file)) {
                this.setState(id, {
                    file: this.createFileState(),
                    status: "error_format",
                    comment: "",
                    editing: false
                });
                return;
            }

            if (file.size > this.MAX_SIZE) {
                this.setState(id, {
                    file: this.createFileState(),
                    status: "error_size",
                    comment: "",
                    editing: false
                });
                return;
            }

            this.setState(id, {
                file: this.createFileState({
                    data: file,
                    name: file.name,
                    size: $.FileUtils.formatFileSize(file.size),
                    text: "Файл прикреплен"
                }),
                status: "uploaded",
            });

            $.AlertManager.showInfo(
                `Документ <span class="huy">“${file.name}”</span> загружен и готов к отправке`,
                'Документ готов к отправке'
            );
        },

        handleSaveComment(id) {
            const $card = this.getCard(id);
            const text = $card.find("[data-role='comment-input']").val().trim();

            this.setState(id, {
                comment: text,
                editing: false
            });
        },

        async handleSubmit() {
            const uploadPromises = [];

            this.cards.forEach((state, id) => {
                const file = state.file;

                if (!file || !file.data || state.status === "success") return;

                const formData = new FormData();
                formData.append("file", file.data);
                formData.append("item_id", id);
                formData.append("comment", state.comment || "");

                this.setState(id, {
                    file: { ...file, text: "Загрузка..." },
                    status: "loading",
                });

                const p = $.fRequest({
                    url: "/api/supplier/docflow/request/file/upload/",
                    data: formData,
                    button: this.$submitRequest,
                    onSuccess: () => {
                        this.setState(id, {
                            file: { ...file, text: "Файл загружен" },
                            status: "success",
                        });
                        return false;
                    },
                    onError: (reply) => {
                        this.setState(id, {
                            file: { ...file, text: reply.message },
                            status: "error",
                        });
                        return false;
                    }
                }).catch(() => {
                    this.setState(id, {
                        file: { ...file, text: "Ошибка сервера" },
                        status: "error",
                    });
                });

                uploadPromises.push(p);
            });

            await Promise.all(uploadPromises);
            const hasError = [...this.cards.values()].some(
                state => state.status === "error"
            );
            if (hasError)
                return;


            $.DialogManager.confirm({
                type: 'success',
                title: 'Вы уверены, что хотите отправить заявку на одобрение?',
                confirmText: 'Отправить',
                width: 560,
                ajaxSubmitUrl: '/api/supplier/docflow/request/submit/',
                ajaxSubmitData: {id: this.id},
                onConfirm: () => {
                    $.Cabinet.htmxReload();
                }
            });

        },

        async handleCancel(){
            $.DialogManager.confirm({
                type: 'destruct',
                title: 'Вы уверены, что хотите отозвать заявку на одобрение?',
                confirmText: 'Отозвать',
                width: 560,
                ajaxSubmitUrl: '/api/supplier/docflow/request/cancel/',
                ajaxSubmitData: {id: this.id},
                onConfirm: () => {
                    $.Cabinet.htmxReload();
                }
            });
        },

        renderCard(id) {
            const state = this.cards.get(id);
            const $card = this.getCard(id);

            const $content = $card.find(".provider-content");
            const $footer = $card.find(".footer-actions");

            const perms = this.getPermissions(state.status);

            const status = this.getStatusFromFile(state.file, state.status);
            $card.find(".status.badge")
                .text(status.text)
                .attr("data-status", status.type);

            $content.empty();
            $footer.empty();

            if (state.file && (state.file.data || state.file.name)) {
                $content.append(this.renderFile(state.file, state.status, perms));
            }

            if (state.editing && perms.comment) {
                $content.append(this.renderEditor(state.comment));
            } else if (state.comment) {
                $content.append(this.renderComment(state.comment));
            }

            this.renderFooter($footer, state, perms);
        },

        renderFooter($footer, state, perms) {
            if (perms.edit && !(state.file.data || state.file.name)) {
                $footer.append(`
                <button class="button secondary" data-action="attach">
                    <svg><use href="#icon-paper-clip"></use></svg>
                    Прикрепить документ
                </button>
            `);
                return;
            }

            if (state.editing && perms.comment) {
                $footer.append(`
                    <button class="button secondary" data-action="cancel-comment">Отменить</button>
                    <button class="button primary" data-action="save-comment">Готово</button>
                `);
                return;
            }

            if (perms.comment) {
                if (state.comment) {
                    $footer.append(`
                        <button class="button secondary" data-action="edit-comment">
                            <svg><use href="#icon-pencil"></use></svg>
                            Редактировать комментарий
                        </button>
                    `);
                } else {
                    $footer.append(`
                        <button class="button secondary" data-action="add-comment">
                            <svg><use href="#icon-plus"></use></svg>
                            Добавить комментарий
                        </button>
                    `);
                }
            }
        },

        renderFile(file, status, perms) {
            const isLoading = status === "loading";

            return $(`
                <div class="file-item ${status}">
                    <span class="file-left">
                        <span class="file-ext icon square">
                            <svg><use href="#icon-document-text"></use></svg>
                        </span>
        
                        <div class="file-info">
                            <span class="file-name">${file.name}</span>
        
                            <span class="file-meta">
                                <span class="file-status">${file.text}</span>
                                ${isLoading ? "" : `<span class="file-size">• ${file.size}</span>`}
                            </span>
                        </div>
                    </span>
        
                    <div class="file-actions">
                        ${perms.view ? `
                            <button class="file-preview button secondary" data-action="preview-file">
                                <svg><use href="#icon-eye"></use></svg>
                            </button>` : ""}
        
                        ${perms.edit ? `
                            <button class="file-replace button secondary" data-action="replace-file">
                                <svg><use href="#icon-arrow-rounded-square"></use></svg>
                            </button>
                            <button class="file-remove button secondary" data-action="remove-file">
                                <svg><use href="#icon-x-mark"></use></svg>
                            </button>` : ""}
                    </div>
                </div>
            `);
        },

        renderEditor(text) {
            return $(`
            <div class="comment-editor">
                <div class="input-box">
                    <input class="input" type="text" data-role="comment-input" value="${text}">
                </div>
            </div>
        `);
        },

        renderComment(text) {
            return $(`
            <div class="comment-block">
                <span class="comment-title">Комментарий к предоставленному документу</span>
                <span class="comment-text">${text}</span>
            </div>
        `);
        },


        getCard(id) {
            return this.$root.find(`[data-id="${id}"]`);
        },

        getStatusFromFile(file, status) {
            switch (status) {
                case "empty":            return { type: "warning", text: "Ожидает документ" };
                case "uploaded":         return { type: "success", text: "Готов к отправке" };
                case "loading":          return { type: "",        text: "Загрузка" };
                case "success":          return { type: "success", text: "Документ загружен" };
                case "error_format":     return { type: "error",   text: "Ошибка формата" };
                case "error_size":       return { type: "error",   text: "Ошибка размера" };
                case "error":            return { type: "error",   text: "Ошибка загрузки" };
                case "server_edit":      return {};
                case "server_locked":    return {};
                default:                 return { type: "warning", text: "Ожидает документ" };
            }
        },

        getFileFromDialog() {
            return new Promise((resolve) => {
                const dialog = new $.DialogManager({
                    url: "/cabinet/supplier/request/form/file/attach/",
                    width: "600px",

                    onOpen: ($container) => {
                        const $form = $container.find("form");
                        const uploader = new $.FileUploader($form.find(".js-file-upload"));

                        $form.on("submit", (e) => {
                            e.preventDefault();
                            const file = uploader.getFile();
                            dialog.close();
                            resolve(file || null);
                        });
                    }
                });
            });
        }
    });

    $.Cabinet.registerPage('tenders', {
        init: function (root) {
            $(root).find('.js-tenders-list').hide().empty();
            $(root).find('.js-tenders-error').hide();
            $(root).find('.js-tenders-empty').show();
        }
    });
})(jQuery);