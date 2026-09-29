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
        root: null,
        items: [],
        search: '',
        listTab: 'all',

        init: function (root) {
            this.root = root;
            this.items = [];
            this.search = '';
            this.listTab = 'all';
            this.bindEvents();
            this.loadList();
        },

        bindEvents: function () {
            const self = this;
            const $root = $(this.root);

            $root.find('.js-tenders-reload').on('click', function () {
                self.loadList();
            });

            let searchTimer = null;
            const $reset = $root.find('.js-tenders-filters-reset');

            function syncReset(value) {
                $reset.prop('disabled', !String(value || '').trim());
            }

            syncReset('');
            $root.find('.js-tenders-search').on('input', function () {
                const value = String($(this).val() || '');
                syncReset(value);
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    self.search = value;
                    self.applyFilters();
                }, 200);
            });

            $reset.on('click', function () {
                $root.find('.js-tenders-search').val('');
                self.search = '';
                syncReset('');
                self.applyFilters();
            });

            $root.find('.js-tenders-tab').on('click keydown', function (event) {
                if (event.type === 'keydown' && event.which !== 13 && event.which !== 32) {
                    return;
                }
                event.preventDefault();
                const tab = String($(this).data('tab') || 'all');
                if (tab === self.listTab) {
                    return;
                }
                self.listTab = tab;
                $root.find('.js-tenders-tab').each(function () {
                    const active = String($(this).data('tab') || '') === tab;
                    $(this).toggleClass('is-active', active).attr('aria-selected', active ? 'true' : 'false');
                });
                self.applyFilters();
            });

            $root.find('.js-tenders-list').on('click', '.supplier-tender-card__fav', function (event) {
                event.preventDefault();
                event.stopPropagation();
            });

            $root.find('.js-tenders-list').on('click', '.supplier-tender-card', function () {
                const id = parseInt($(this).data('id'), 10) || 0;
                if (id <= 0) {
                    return;
                }
                const hasApp = String($(this).attr('data-has-application') || '') === '1';
                const base = '/cabinet/supplier/tender/' + id + '/';
                window.location.href = hasApp ? (base + 'participation/') : base;
            });
        },

        loadList: function () {
            const self = this;
            const $root = $(this.root);

            $root.find('.js-tenders-error').hide();
            $root.find('.js-tenders-empty').hide();
            $root.find('.js-tenders-list').hide().empty();
            $root.find('.js-tenders-loading').show();

            $.fRequest({
                url: '/api/supplier/tender/list/',
                method: 'GET',
                showMessages: false,
                onSuccess: function (reply) {
                    $root.find('.js-tenders-loading').hide();
                    self.items = Array.isArray(reply.items) ? reply.items : [];
                    self.applyFilters();
                },
                onError: function (reply) {
                    $root.find('.js-tenders-loading').hide();
                    $root.find('.js-tenders-list').hide().empty();
                    $root.find('.js-tenders-empty').hide();
                    $root.find('.js-tenders-error').show();
                    $root.find('.js-tenders-error-text').text(
                        (reply && reply.message) || 'Ошибка загрузки'
                    );
                }
            });
        },

        syncTabCounts: function (allCount, participationCount) {
            const $root = $(this.root);
            $root.find('.js-tenders-tab-count[data-tab="all"]').text(String(allCount));
            $root.find('.js-tenders-tab-count[data-tab="participations"]').text(String(participationCount));
            $root.find('.js-tenders-tab-count[data-tab="favorites"]').text('0');
        },

        syncSectionTitle: function () {
            const titles = {
                all: 'Все тендеры',
                participations: 'Активные участия',
                favorites: 'Избранные тендеры'
            };
            $(this.root).find('.js-tenders-section-title').text(titles[this.listTab] || titles.all);
        },

        applyFilters: function () {
            const query = String(this.search || '').trim().toLowerCase();
            const searched = this.items.filter(function (row) {
                if (!query) {
                    return true;
                }
                const tender = row.tender || {};
                const app = row.application || null;
                const hay = [
                    tender.title,
                    tender.number,
                    tender.type && tender.type.name,
                    tender.status && tender.status.name,
                    app && app.status && app.status.name,
                    app && app.status && app.status.code
                ].join(' ').toLowerCase();
                return hay.indexOf(query) !== -1;
            });

            const participations = searched.filter(function (row) {
                return !!row.application;
            });

            this.syncTabCounts(searched.length, participations.length);
            this.syncSectionTitle();

            let filtered = searched;
            if (this.listTab === 'participations') {
                filtered = participations;
            } else if (this.listTab === 'favorites') {
                filtered = [];
            }
            this.renderList(filtered);
        },

        formatPublishDate: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) {
                return '';
            }
            const d = new Date(raw.replace(' ', 'T'));
            if (isNaN(d.getTime())) {
                return '';
            }
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            return dd + '.' + mm + '.' + d.getFullYear();
        },

        formatPrice: function (tender, card) {
            if (tender.hide_initial_price == 1 || tender.hide_initial_price === true) {
                return 'Цена не указана';
            }
            card = card || {};
            const amount = parseFloat(
                card.display_budget != null && card.display_budget !== ''
                    ? card.display_budget
                    : tender.budget
            );
            if (!isFinite(amount) || amount <= 0) {
                return 'Цена не указана';
            }
            return amount.toLocaleString('ru-RU', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }) + ' ₽';
        },

        daysLeftLabel: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) {
                return '';
            }
            const end = new Date(raw.replace(' ', 'T'));
            if (isNaN(end.getTime())) {
                return '';
            }
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const endDay = new Date(end);
            endDay.setHours(0, 0, 0, 0);
            const diff = Math.round((endDay.getTime() - today.getTime()) / 86400000);
            if (diff < 0) {
                return '';
            }
            if (diff === 0) {
                return 'Сегодня';
            }
            const mod10 = diff % 10;
            const mod100 = diff % 100;
            if (mod10 === 1 && mod100 !== 11) {
                return 'Остался ' + diff + ' день';
            }
            if (mod10 >= 2 && mod10 <= 4 && (mod100 < 12 || mod100 > 14)) {
                return 'Осталось ' + diff + ' дня';
            }
            return 'Осталось ' + diff + ' дней';
        },

        formatDeadline: function (value) {
            const raw = String(value || '').trim();
            if (!raw || raw.indexOf('0000-00-00') === 0) {
                return '—';
            }
            const d = new Date(raw.replace(' ', 'T'));
            if (isNaN(d.getTime())) {
                return '—';
            }
            const dd = String(d.getDate()).padStart(2, '0');
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const yyyy = d.getFullYear();
            const hh = String(d.getHours()).padStart(2, '0');
            const mi = String(d.getMinutes()).padStart(2, '0');
            return dd + '.' + mm + '.' + yyyy + ' в ' + hh + ':' + mi;
        },

        applicationLabel: function (application) {
            if (!application || !application.status) {
                return 'Нет заявки';
            }
            const name = String(application.status.name || '').trim();
            if (name) {
                return name;
            }
            const code = String(application.status.code || '').trim();
            if (code === 'draft') {
                return 'Черновик';
            }
            if (code === 'submitted') {
                return 'Подана';
            }
            if (code === 'withdrawn') {
                return 'Отозвана';
            }
            return code || 'Нет заявки';
        },

        statusTone: function (statusName) {
            const name = String(statusName || '');
            if (name.indexOf('Отмен') !== -1 || name.indexOf('Отозв') !== -1) {
                return 'error';
            }
            if (name.indexOf('Приём') !== -1 || name.indexOf('Подан') !== -1) {
                return 'success';
            }
            if (name.indexOf('Черновик') !== -1 || name.indexOf('Ожид') !== -1) {
                return 'warning';
            }
            return 'info';
        },

        renderList: function (items) {
            const $root = $(this.root);
            const $list = $root.find('.js-tenders-list');
            const $empty = $root.find('.js-tenders-empty');
            const hasSearch = !!String(this.search || '').trim();
            $list.empty();

            if (!items.length) {
                $list.hide();
                $empty.show();
                if (hasSearch) {
                    $root.find('.js-tenders-empty-title').text('Ничего не найдено');
                    $root.find('.js-tenders-empty-des').text('Измените поисковый запрос.');
                } else if (this.listTab === 'favorites') {
                    $root.find('.js-tenders-empty-title').text('Нет избранных тендеров');
                    $root.find('.js-tenders-empty-des').text(
                        'В избранном пока нет тендеров. Отметьте карточку звёздочкой в списке «Все».'
                    );
                } else if (this.listTab === 'participations') {
                    $root.find('.js-tenders-empty-title').text('Нет активных участий');
                    $root.find('.js-tenders-empty-des').text(
                        'Здесь появятся тендеры, в которых вы уже создали заявку.'
                    );
                } else {
                    $root.find('.js-tenders-empty-title').text('Нет доступных тендеров');
                    $root.find('.js-tenders-empty-des').text(
                        'Здесь появятся открытые запросы цен в приёме заявок и закрытые, куда вас пригласили.'
                    );
                }
                return;
            }

            $empty.hide();
            $list.show();

            const self = this;
            items.forEach(function (row) {
                const tender = row.tender || {};
                const meta = row.card || {};
                const application = row.application || null;
                const id = parseInt(tender.id, 10) || 0;
                const hasApplication = !!application;
                const statusName = (tender.status && tender.status.name) || '—';
                const typeName = (tender.type && tender.type.name) || 'Тендер';
                const number = tender.number || '';
                const methodLine = typeName + (number ? ' №' + number : '');
                const tone = self.statusTone(statusName);
                const published = self.formatPublishDate(tender.published_at || tender.create_datetime);
                const daysLeft = self.daysLeftLabel(tender.end_at);
                const city = String(meta.city || '').trim();
                const organizer = String(meta.organizer || '').trim();
                const category = String(meta.category || '').trim();
                const mnn = Array.isArray(meta.mnn) ? meta.mnn : [];
                const appLabel = hasApplication ? self.applicationLabel(application) : '';

                const $card = $(
                    '<article class="supplier-tender-card" data-id="' + id + '" data-has-application="'
                    + (hasApplication ? '1' : '0') + '">'
                    + '<div class="supplier-tender-card__main">'
                    + '<div class="supplier-tender-card__info">'
                    + '<div class="supplier-tender-card__date-row">'
                    + '<span class="supplier-tender-card__date"></span>'
                    + '<span class="supplier-tender-card__status"></span>'
                    + '</div>'
                    + '<div class="supplier-tender-card__heading">'
                    + '<div class="supplier-tender-card__titles">'
                    + '<h3 class="supplier-tender-card__title"></h3>'
                    + '<div class="supplier-tender-card__number"></div>'
                    + '</div>'
                    + '<div class="supplier-tender-card__tags"></div>'
                    + '</div>'
                    + '</div>'
                    + '<div class="supplier-tender-card__place">'
                    + '<div class="supplier-tender-card__place-row js-city">'
                    + '<span class="supplier-tender-card__place-label">Город:</span>'
                    + '<span class="supplier-tender-card__place-value js-city-value"></span>'
                    + '</div>'
                    + '<div class="supplier-tender-card__place-row supplier-tender-card__place-row--org">'
                    + '<div class="supplier-tender-card__org">'
                    + '<span class="supplier-tender-card__place-label">Организатор:</span>'
                    + '<span class="supplier-tender-card__place-value js-org-value"></span>'
                    + '</div>'
                    + '<span class="supplier-tender-card__chip js-category"></span>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '<div class="supplier-tender-card__side">'
                    + '<div class="supplier-tender-card__offer">'
                    + '<div class="supplier-tender-card__price">'
                    + '<span class="supplier-tender-card__side-label">Начальная цена контракта</span>'
                    + '<span class="supplier-tender-card__price-value"></span>'
                    + '</div>'
                    + '<div class="supplier-tender-card__deadline">'
                    + '<span class="supplier-tender-card__side-label">Окончание приема заявок</span>'
                    + '<div class="supplier-tender-card__deadline-row">'
                    + '<span class="supplier-tender-card__deadline-value"></span>'
                    + '<span class="supplier-tender-card__days"></span>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '<button type="button" class="supplier-tender-card__fav" aria-label="Добавить в избранное">'
                    + '<svg aria-hidden="true"><use href="#icon-star"></use></svg>'
                    + '</button>'
                    + '</div>'
                    + '</article>'
                );

                $card.find('.supplier-tender-card__date').text(
                    published ? 'Дата публикации: ' + published : 'Дата публикации: —'
                );
                $card.find('.supplier-tender-card__status')
                    .addClass('supplier-tender-card__status--' + tone)
                    .text(statusName);
                $card.find('.supplier-tender-card__title').text(tender.title || 'Без названия');
                $card.find('.supplier-tender-card__number').text(methodLine);
                $card.find('.supplier-tender-card__price-value').text(self.formatPrice(tender, meta));
                $card.find('.supplier-tender-card__deadline-value').text(self.formatDeadline(tender.end_at));

                const $days = $card.find('.supplier-tender-card__days');
                if (daysLeft) {
                    $days.text(daysLeft);
                } else {
                    $days.remove();
                }

                const $tags = $card.find('.supplier-tender-card__tags');
                function addRequirement(label) {
                    const $tag = $('<span class="supplier-tender-card__req"></span>');
                    $tag.append('<svg aria-hidden="true"><use href="#icon-info"></use></svg>');
                    $tag.append($('<span></span>').text(label));
                    $tags.append($tag);
                }
                function addChip($host, label) {
                    $host.append('<svg aria-hidden="true"><use href="#icon-tag"></use></svg>');
                    $host.append($('<span class="supplier-tender-card__chip-label"></span>').text(label));
                }
                if (appLabel) {
                    addRequirement('Моя заявка: ' + appLabel);
                }
                if (meta.requires_prequalification == 1) {
                    addRequirement('Требуется предварительная квалификация');
                }
                if (tender.approval_required == 1 || tender.approval_required === true) {
                    addRequirement('Требуется одобрение поставщика');
                }
                if (!$tags.children().length) {
                    addRequirement('Требуется предварительная квалификация');
                    addRequirement('Требуется одобрение поставщика');
                }
                mnn.slice(0, 2).forEach(function (name) {
                    const label = String(name || '').trim();
                    if (!label) {
                        return;
                    }
                    const $chip = $('<span class="supplier-tender-card__chip"></span>');
                    addChip($chip, 'МНН: ' + label);
                    $tags.append($chip);
                });

                $card.find('.js-city-value').text(city || '—');
                $card.find('.js-org-value').text(organizer || '—');
                addChip(
                    $card.find('.js-category'),
                    category || 'Производственная тара и инструменты'
                );

                $list.append($card);
            });
        }
    });

    // Карточка извещения: витрина + вход в /participation/, отзыв из aside.
    $.Cabinet.registerPage('tender', {
        root: null,
        tenderId: 0,
        busy: false,

        init: function (root) {
            this.root = root;
            this.tenderId = parseInt(root.getAttribute('data-id') || '0', 10) || 0;
            this.busy = false;
            this.bindEvents();
        },

        bindEvents: function () {
            const self = this;
            const $root = $(this.root);

            $root.on('click', '.js-supplier-tender-fav', function (event) {
                event.preventDefault();
            });

            $root.on('click', '.js-notice-copy-address', function (event) {
                event.preventDefault();
                const text = String(this.getAttribute('data-copy') || '').trim();
                if (!text) {
                    return;
                }
                const done = function () {
                    const $btn = $(event.currentTarget);
                    $btn.attr('aria-label', 'Адрес скопирован');
                    window.setTimeout(function () {
                        $btn.attr('aria-label', 'Скопировать адрес поставки');
                    }, 1500);
                };
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(done).catch(function () {
                        window.prompt('Скопируйте адрес поставки', text);
                    });
                    return;
                }
                window.prompt('Скопируйте адрес поставки', text);
            });

            $root.on('click', '.js-supplier-ask-question', function (event) {
                event.preventDefault();
                const tab = String(this.getAttribute('data-tab') || 'questions');
                const $tab = $root.find('.js-ds-tabs-tab[data-tab="' + tab + '"]').first();
                if ($tab.length) {
                    $tab.trigger('click');
                }
            });

            $root.on('click', '.js-supplier-aside-withdraw', function () {
                if (self.busy) {
                    return;
                }
                if (window.confirm('Отозвать заявку?')) {
                    self.withdraw();
                }
            });
        },

        setBusy: function (on) {
            this.busy = !!on;
            $(this.root).find('.js-supplier-aside-withdraw')
                .toggleClass('loading', this.busy)
                .prop('disabled', this.busy);
        },

        showMsg: function (text, isError) {
            const $msg = $(this.root).find('.js-supplier-aside-msg');
            if (!$msg.length) {
                return;
            }
            if (!text) {
                $msg.prop('hidden', true).text('');
                return;
            }
            $msg
                .toggleClass('is-error', !!isError)
                .text(text)
                .prop('hidden', false);
        },

        withdraw: function () {
            const self = this;
            if (this.busy || this.tenderId <= 0) {
                return;
            }

            this.setBusy(true);
            this.showMsg('');

            $.fRequest({
                url: '/api/supplier/tender/' + this.tenderId + '/withdraw/',
                method: 'POST',
                showMessages: true,
                data: {},
                onSuccess: function () {
                    window.location.reload();
                },
                onError: function (reply) {
                    self.setBusy(false);
                    self.showMsg((reply && reply.message) || 'Не удалось отозвать', true);
                }
            }).catch(function () {
                self.setBusy(false);
            });
        }
    });
    // Wizard участия: stepper, nonPrice, approval/qualification wait, proposal (5.2–5.5).
    $.Cabinet.registerPage('participation', {
        root: null,
        tenderId: 0,
        flow: null,
        baseUrl: '',
        busy: false,

        init: function (root) {
            this.root = root;
            this.tenderId = parseInt(root.getAttribute('data-id') || '0', 10) || 0;
            this.baseUrl = String(root.getAttribute('data-participation-url') || '').trim()
                || ('/cabinet/supplier/tender/' + this.tenderId + '/participation/');
            this.flow = this.parseFlow(root.getAttribute('data-flow') || '');
            this.busy = false;
            this.bindEvents();
            this.refreshLotsSummary();
            this.refreshDocsSummary();
        },

        parseFlow: function (raw) {
            if (!raw) {
                return null;
            }
            try {
                return JSON.parse(raw);
            } catch (e) {
                return null;
            }
        },

        bindEvents: function () {
            const self = this;
            const $root = $(this.root);

            $root.on('click', '.js-participation-stepper .supplier-participation__stage.is-accessible', function (event) {
                event.preventDefault();
                const stage = String(this.getAttribute('data-stage') || '');
                if (!stage || !self.tenderId) {
                    return;
                }
                self.goStep(stage);
            });

            $root.on('keydown', '.js-participation-stepper .supplier-participation__stage.is-accessible', function (event) {
                if (event.key !== 'Enter' && event.key !== ' ') {
                    return;
                }
                event.preventDefault();
                const stage = String(this.getAttribute('data-stage') || '');
                if (stage) {
                    self.goStep(stage);
                }
            });

            $root.on('click', '.js-participation-save-next', function (event) {
                event.preventDefault();
                self.saveNonPriceAndNext();
            });

            $root.on('click', '.js-participation-proposal-save', function (event) {
                event.preventDefault();
                self.saveProposal(false);
            });
            $root.on('click', '.js-participation-proposal-submit', function (event) {
                event.preventDefault();
                self.saveProposal(true);
            });
            $root.on('click', '.js-participation-proposal-withdraw', function (event) {
                event.preventDefault();
                if (window.confirm('Отозвать заявку? Повторно участвовать в этом тендере будет нельзя.')) {
                    self.withdrawProposal();
                }
            });
            $root.on('click', '.js-participation-proposal-sub', function (event) {
                event.preventDefault();
                const sub = String(this.getAttribute('data-sub') || '').trim();
                if (sub === 'lots' || sub === 'documents') {
                    self.showProposalSub(sub);
                }
            });
            $root.on('input change', '.js-participation-price', function () {
                self.refreshLotsSummary();
            });
            $root.on('change', '.js-participation-doc-file', function () {
                const $row = $(this).closest('.js-participation-doc-row');
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    return;
                }
                self.uploadProposalDoc($row, file);
            });
        },

        showProposalSub: function (sub) {
            const $root = $(this.root);
            const $tabs = $root.find('.js-participation-proposal-sub');
            const $panels = $root.find('.supplier-participation-proposal__panel');
            if (!$tabs.length || !$panels.length) {
                return;
            }

            $tabs.each(function () {
                const on = String(this.getAttribute('data-sub') || '') === sub;
                $(this).toggleClass('is-active', on).attr('aria-selected', on ? 'true' : 'false');
            });
            $panels.each(function () {
                const on = String(this.getAttribute('data-sub') || '') === sub;
                if (on) {
                    this.removeAttribute('hidden');
                } else {
                    this.setAttribute('hidden', 'hidden');
                }
            });

            if (!this.baseUrl) {
                return;
            }
            const url = this.baseUrl
                + (this.baseUrl.indexOf('?') >= 0 ? '&' : '?')
                + 'step=proposal&sub=' + encodeURIComponent(sub);
            if (window.history && typeof window.history.replaceState === 'function') {
                window.history.replaceState(null, '', url);
            }
        },

        refreshLotsSummary: function () {
            const $root = $(this.root);
            const $summary = $root.find('.js-participation-lots-summary');
            if (!$summary.length) {
                return;
            }
            const total = parseInt($summary.attr('data-total') || '0', 10) || 0;
            let filled = 0;
            let sum = 0;
            $root.find('.js-participation-lot-row').each(function () {
                const qty = parseFloat(String($(this).attr('data-qty') || '1').replace(',', '.')) || 0;
                const raw = String($(this).find('.js-participation-price').val() || '').trim().replace(',', '.');
                const price = raw === '' ? null : parseFloat(raw);
                if (price !== null && !isNaN(price) && price > 0) {
                    filled += 1;
                    sum += price * (qty > 0 ? qty : 1);
                }
            });
            const filledLabel = filled === total && total > 0
                ? ('Заполнено ' + filled + ' из ' + total + ' позиций')
                : ('Заполнено ' + filled + ' из ' + total + ' позиций');
            $summary.find('.js-participation-lots-filled').text(filledLabel);
            $summary.find('.js-participation-lots-total').text(
                'Сумма по предложению: ' + this.formatMoney(sum) + ' ₽'
            );
        },

        formatMoney: function (value) {
            const n = Math.round((Number(value) || 0) * 100) / 100;
            const parts = String(n.toFixed(2)).split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            if (parts[1] === '00') {
                return parts[0];
            }
            return parts[0] + ',' + parts[1];
        },

        refreshDocsSummary: function () {
            const $root = $(this.root);
            const $summary = $root.find('.js-participation-docs-summary');
            if (!$summary.length) {
                return;
            }
            const total = parseInt($summary.attr('data-total') || '0', 10) || 0;
            const ready = $root.find('.js-participation-doc-row.is-ready').length;
            $summary.text(ready + ' из ' + total + ' документов готовы к отправке');
        },

        goStep: function (step) {
            if (!step || !this.baseUrl) {
                return;
            }
            const sep = this.baseUrl.indexOf('?') >= 0 ? '&' : '?';
            window.location.href = this.baseUrl + sep + 'step=' + encodeURIComponent(step);
        },

        showNonPriceMsg: function (text, isError) {
            const $msg = $(this.root).find('.js-participation-nonprice-msg');
            if (!$msg.length) {
                return;
            }
            if (!text) {
                $msg.prop('hidden', true).text('');
                return;
            }
            $msg
                .prop('hidden', false)
                .css('color', isError ? '#b42318' : '#027a48')
                .text(text);
        },

        setBusy: function (on) {
            this.busy = !!on;
            $(this.root).find(
                '.js-participation-save-next, .js-participation-proposal-save, '
                + '.js-participation-proposal-submit, .js-participation-proposal-withdraw'
            )
                .toggleClass('loading', this.busy)
                .prop('disabled', this.busy);
        },

        showProposalMsg: function (text, isError) {
            const $msg = $(this.root).find('.js-participation-proposal-msg');
            if (!$msg.length) {
                return;
            }
            if (!text) {
                $msg.prop('hidden', true).text('');
                return;
            }
            $msg
                .prop('hidden', false)
                .css('color', isError ? '#b42318' : '#027a48')
                .text(text);
        },

        collectProposalPayload: function () {
            const items = [];
            $(this.root).find('.js-participation-price').each(function () {
                const tenderItemId = parseInt(this.getAttribute('data-tender-item-id') || '0', 10) || 0;
                if (tenderItemId <= 0) {
                    return;
                }
                const raw = String($(this).val() || '').trim().replace(',', '.');
                const price = raw === '' ? null : parseFloat(raw);
                items.push({
                    tender_item_id: tenderItemId,
                    price_per_unit: price !== null && !isNaN(price) ? price : null
                });
            });

            const documents = [];
            $(this.root).find('.js-participation-doc-row').each(function () {
                const tenderDocumentId = parseInt(this.getAttribute('data-tender-document-id') || '0', 10) || 0;
                const fileLinkId = parseInt(this.getAttribute('data-file-link-id') || '0', 10) || 0;
                const appDocId = parseInt(this.getAttribute('data-app-doc-id') || '0', 10) || 0;
                const name = String(this.getAttribute('data-doc-name') || 'Документ');
                if (tenderDocumentId <= 0) {
                    return;
                }
                if (fileLinkId <= 0 && appDocId <= 0) {
                    return;
                }
                const row = {
                    tender_document_id: tenderDocumentId,
                    name: name,
                    file_link_id: fileLinkId > 0 ? fileLinkId : null
                };
                if (appDocId > 0) {
                    row.id = appDocId;
                }
                documents.push(row);
            });

            const payload = { items: items };
            if ($(this.root).find('.js-participation-doc-row').length) {
                payload.documents = documents;
            }
            return payload;
        },

        uploadProposalDoc: function ($row, file) {
            const self = this;
            if (this.busy || this.tenderId <= 0) {
                return;
            }
            const fd = new FormData();
            fd.append('file', file);
            this.setBusy(true);
            this.showProposalMsg('Загрузка файла…', false);
            $.fRequest({
                url: '/api/supplier/tender/' + this.tenderId + '/file/upload/',
                method: 'POST',
                showMessages: false,
                data: fd,
                onSuccess: function (reply) {
                    const fileLinkId = parseInt(reply.file_link_id, 10) || 0;
                    $row.attr('data-file-link-id', String(fileLinkId));
                    if (fileLinkId > 0) {
                        $row.addClass('is-ready');
                        $row.find('.js-participation-doc-status').text('Файл загружен');
                        $row.find('.js-participation-doc-upload-label').text('Заменить');
                    } else {
                        $row.removeClass('is-ready');
                        $row.find('.js-participation-doc-status').text('Файл не загружен');
                        $row.find('.js-participation-doc-upload-label').text('Загрузить');
                    }
                    self.refreshDocsSummary();
                    self.setBusy(false);
                    self.showProposalMsg('Файл загружен. Сохраняем в заявку…', false);
                    self.saveProposal(false);
                },
                onError: function (reply) {
                    self.setBusy(false);
                    self.showProposalMsg((reply && reply.message) || 'Не удалось загрузить файл', true);
                }
            }).catch(function () {
                self.setBusy(false);
                self.showProposalMsg('Не удалось загрузить файл', true);
            });
        },

        saveProposal: function (thenSubmit) {
            const self = this;
            if (this.busy || this.tenderId <= 0) {
                return;
            }
            if (thenSubmit) {
                const check = this.validateProposalBeforeSubmit();
                if (!check.ok) {
                    this.showProposalMsg(check.message, true);
                    if (check.sub) {
                        this.showProposalSub(check.sub);
                    }
                    return;
                }
            }
            this.setBusy(true);
            this.showProposalMsg('');
            $.fRequest({
                url: '/api/supplier/tender/' + this.tenderId + '/save/',
                method: 'POST',
                showMessages: !thenSubmit,
                data: { data: this.collectProposalPayload() },
                onSuccess: function () {
                    if (!thenSubmit) {
                        window.location.reload();
                        return;
                    }
                    $.fRequest({
                        url: '/api/supplier/tender/' + self.tenderId + '/submit/',
                        method: 'POST',
                        showMessages: true,
                        data: {},
                        onSuccess: function () {
                            window.location.href = '/cabinet/supplier/tender/' + self.tenderId + '/';
                        },
                        onError: function (reply) {
                            self.setBusy(false);
                            self.showProposalMsg((reply && reply.message) || 'Не удалось подать заявку', true);
                        }
                    }).catch(function () {
                        self.setBusy(false);
                        self.showProposalMsg('Не удалось подать заявку', true);
                    });
                },
                onError: function (reply) {
                    self.setBusy(false);
                    self.showProposalMsg((reply && reply.message) || 'Не удалось сохранить', true);
                }
            }).catch(function () {
                self.setBusy(false);
                self.showProposalMsg('Не удалось сохранить', true);
            });
        },

        validateProposalBeforeSubmit: function () {
            const $root = $(this.root);
            const $rows = $root.find('.js-participation-lot-row');
            if ($rows.length) {
                let missing = 0;
                $rows.each(function () {
                    const raw = String($(this).find('.js-participation-price').val() || '').trim().replace(',', '.');
                    const price = raw === '' ? null : parseFloat(raw);
                    if (price === null || isNaN(price) || price <= 0) {
                        missing += 1;
                    }
                });
                if (missing > 0) {
                    return {
                        ok: false,
                        sub: 'lots',
                        message: 'Укажите цену больше 0 по каждой позиции извещения'
                    };
                }
            }

            const $docs = $root.find('.js-participation-doc-row');
            if ($docs.length) {
                let missingDocs = 0;
                $docs.each(function () {
                    const required = String(this.getAttribute('data-required') || '') === '1';
                    const ready = $(this).hasClass('is-ready')
                        || (parseInt(this.getAttribute('data-file-link-id') || '0', 10) || 0) > 0;
                    if (required && !ready) {
                        missingDocs += 1;
                    }
                });
                if (missingDocs > 0) {
                    return {
                        ok: false,
                        sub: 'documents',
                        message: 'Загрузите обязательные документы'
                    };
                }
            }

            return { ok: true };
        },

        withdrawProposal: function () {
            const self = this;
            if (this.busy || this.tenderId <= 0) {
                return;
            }
            this.setBusy(true);
            this.showProposalMsg('');
            $.fRequest({
                url: '/api/supplier/tender/' + this.tenderId + '/withdraw/',
                method: 'POST',
                showMessages: true,
                data: {},
                onSuccess: function () {
                    window.location.href = '/cabinet/supplier/tender/' + self.tenderId + '/';
                },
                onError: function (reply) {
                    self.setBusy(false);
                    self.showProposalMsg((reply && reply.message) || 'Не удалось отозвать', true);
                }
            }).catch(function () {
                self.setBusy(false);
                self.showProposalMsg('Не удалось отозвать', true);
            });
        },

        collectCriteria: function () {
            const criteria = [];
            $(this.root).find('.supplier-participation-nonprice__row').each(function () {
                const criterionId = parseInt(this.getAttribute('data-criterion-id') || '0', 10) || 0;
                if (criterionId <= 0) {
                    return;
                }
                const $row = $(this);
                criteria.push({
                    criterion_id: criterionId,
                    value: String($row.find('.js-participation-criterion-value').val() || '').trim(),
                    confirmed: $row.find('.js-participation-criterion-confirmed').is(':checked') ? 1 : 0,
                    mandatory: this.getAttribute('data-mandatory') === '1'
                });
            });
            return criteria;
        },

        validateMandatory: function (criteria) {
            for (let i = 0; i < criteria.length; i += 1) {
                const row = criteria[i];
                if (!row.mandatory) {
                    continue;
                }
                if (!row.value && !row.confirmed) {
                    return false;
                }
            }
            return true;
        },

        nextStepFromGates: function (gates) {
            gates = gates || {};
            if (!gates.nonprice_done) {
                return 'nonPrice';
            }
            if (!gates.approval_ok) {
                return 'approval';
            }
            if (!gates.qualification_ok) {
                return 'qualification';
            }
            return 'proposal';
        },

        saveNonPriceAndNext: function () {
            const self = this;
            if (this.busy || this.tenderId <= 0) {
                return;
            }

            const criteria = this.collectCriteria();
            const hasMandatory = criteria.some(function (row) { return !!row.mandatory; });
            if (hasMandatory && !this.validateMandatory(criteria)) {
                this.showNonPriceMsg('Заполните или подтвердите все обязательные критерии', true);
                return;
            }

            const payloadCriteria = criteria.map(function (row) {
                return {
                    criterion_id: row.criterion_id,
                    value: row.value,
                    confirmed: row.confirmed
                };
            });

            this.setBusy(true);
            this.showNonPriceMsg('');

            const payload = { criteria: payloadCriteria };
            // nonprice_done ставим, если обязательных нет или они закрыты;
            // сервер сам тоже выставит при mandatoryCriteriaAnswered.
            if (!hasMandatory || this.validateMandatory(criteria)) {
                payload.nonprice_done = 1;
            }

            $.fRequest({
                url: '/api/supplier/tender/' + this.tenderId + '/save/',
                method: 'POST',
                showMessages: true,
                data: { data: payload },
                onSuccess: function (reply) {
                    const gates = (reply && reply.gates) || {};
                    if (hasMandatory && !gates.nonprice_done) {
                        self.setBusy(false);
                        self.showNonPriceMsg('Критерии сохранены, но обязательные ещё не закрыты', true);
                        return;
                    }
                    self.goStep(self.nextStepFromGates(gates));
                },
                onError: function (reply) {
                    self.setBusy(false);
                    self.showNonPriceMsg((reply && reply.message) || 'Не удалось сохранить', true);
                }
            }).catch(function () {
                self.setBusy(false);
                self.showNonPriceMsg('Не удалось сохранить', true);
            });
        }
    });
})(jQuery);
