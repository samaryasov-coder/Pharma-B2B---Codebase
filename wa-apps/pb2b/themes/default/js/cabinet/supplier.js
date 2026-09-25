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

        init: function (root) {
            this.root = root;
            this.items = [];
            this.search = '';
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

            $root.find('.js-tenders-list').on('click', '.supplier-tender-card__fav', function (event) {
                event.preventDefault();
                event.stopPropagation();
            });

            $root.find('.js-tenders-list').on('click', '.supplier-tender-card', function () {
                const id = parseInt($(this).data('id'), 10) || 0;
                if (id <= 0) {
                    return;
                }
                window.location.href = '/cabinet/supplier/tender/' + id + '/';
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

        applyFilters: function () {
            const query = String(this.search || '').trim().toLowerCase();
            const filtered = this.items.filter(function (row) {
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

        formatPrice: function (tender) {
            if (tender.hide_initial_price == 1 || tender.hide_initial_price === true) {
                return 'Цена не указана';
            }
            const amount = parseFloat(tender.budget);
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
                $root.find('.js-tenders-empty-title').text(
                    hasSearch ? 'Ничего не найдено' : 'Нет доступных тендеров'
                );
                $root.find('.js-tenders-empty-des').text(
                    hasSearch
                        ? 'Измените поисковый запрос.'
                        : 'Здесь появятся открытые запросы цен в приёме заявок и закрытые, куда вас пригласили.'
                );
                return;
            }

            $empty.hide();
            $list.show();

            const self = this;
            items.forEach(function (row) {
                const tender = row.tender || {};
                const meta = row.card || {};
                const id = parseInt(tender.id, 10) || 0;
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

                const $card = $(
                    '<article class="supplier-tender-card" data-id="' + id + '">'
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
                $card.find('.supplier-tender-card__price-value').text(self.formatPrice(tender));
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

    // Карточка извещения: apply + форма КП (save/submit/withdraw).
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

            $root.on('click', '.js-supplier-tender-apply', function () {
                self.apply();
            });
            $root.on('click', '.js-supplier-kp-save', function () {
                self.save();
            });
            $root.on('click', '.js-supplier-kp-submit', function () {
                self.submit();
            });
            $root.on('click', '.js-supplier-kp-withdraw', function () {
                if (window.confirm('Отозвать заявку?')) {
                    self.withdraw();
                }
            });
            $root.on('change', '.js-kp-doc-file', function () {
                const $row = $(this).closest('.js-kp-doc-row');
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    return;
                }
                self.uploadDoc($row, file);
            });
        },

        setBusy: function (on) {
            this.busy = !!on;
            $(this.root).find('.js-supplier-kp-save, .js-supplier-kp-submit, .js-supplier-kp-withdraw, .js-supplier-tender-apply')
                .toggleClass('loading', this.busy)
                .prop('disabled', this.busy);
        },

        showMsg: function (text, isError) {
            const $msg = $(this.root).find('.js-supplier-kp-msg');
            if (!text) {
                $msg.hide().text('');
                return;
            }
            $msg.css('color', isError ? '#b42318' : '#027a48').text(text).show();
        },

        apply: function () {
            const self = this;
            const $root = $(this.root);
            const $error = $root.find('.js-supplier-tender-apply-error');

            if (this.busy || this.tenderId <= 0) {
                return;
            }

            this.setBusy(true);
            $error.hide().text('');

            $.fRequest({
                url: '/api/supplier/tender/' + this.tenderId + '/apply/',
                method: 'POST',
                showMessages: true,
                data: {},
                onSuccess: function () {
                    window.location.reload();
                },
                onError: function (reply) {
                    self.setBusy(false);
                    $error.text((reply && reply.message) || 'Не удалось создать заявку').show();
                }
            }).catch(function () {
                self.setBusy(false);
            });
        },

        collectPayload: function () {
            const $root = $(this.root);
            const items = [];
            $root.find('.js-kp-price').each(function () {
                const tenderItemId = parseInt($(this).data('tender-item-id'), 10) || 0;
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

            const criteria = [];
            $root.find('.supplier-kp-criteria__row').each(function () {
                const criterionId = parseInt($(this).data('criterion-id'), 10) || 0;
                if (criterionId <= 0) {
                    return;
                }
                criteria.push({
                    criterion_id: criterionId,
                    value: String($(this).find('.js-kp-criterion-value').val() || '').trim(),
                    confirmed: $(this).find('.js-kp-criterion-confirmed').is(':checked') ? 1 : 0
                });
            });

            const documents = [];
            $root.find('.js-kp-doc-row').each(function () {
                const tenderDocumentId = parseInt($(this).data('tender-document-id'), 10) || 0;
                const fileLinkId = parseInt($(this).attr('data-file-link-id'), 10) || 0;
                const appDocId = parseInt($(this).attr('data-app-doc-id'), 10) || 0;
                const name = String($(this).data('doc-name') || 'Документ');
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
            if ($root.find('.supplier-kp-criteria__row').length) {
                payload.criteria = criteria;
                let allConfirmed = true;
                criteria.forEach(function (c) {
                    if (!c.confirmed) {
                        allConfirmed = false;
                    }
                });
                if (allConfirmed && criteria.length) {
                    payload.nonprice_done = 1;
                }
            }
            if ($root.find('.js-kp-doc-row').length) {
                payload.documents = documents;
            }
            return payload;
        },

        uploadDoc: function ($row, file) {
            const self = this;
            if (this.busy || this.tenderId <= 0) {
                return;
            }

            const fd = new FormData();
            fd.append('file', file);

            this.setBusy(true);
            this.showMsg('Загрузка файла…', false);

            $.fRequest({
                url: '/api/supplier/tender/' + this.tenderId + '/file/upload/',
                method: 'POST',
                showMessages: false,
                data: fd,
                onSuccess: function (reply) {
                    const fileLinkId = parseInt(reply.file_link_id, 10) || 0;
                    $row.attr('data-file-link-id', String(fileLinkId));
                    $row.find('.js-kp-doc-status').text(
                        fileLinkId > 0
                            ? ('файл загружен (#' + fileLinkId + ')')
                            : 'файл не загружен'
                    );
                    self.setBusy(false);
                    self.showMsg('Файл загружен. Нажмите «Сохранить», чтобы привязать к заявке.', false);
                },
                onError: function (reply) {
                    self.setBusy(false);
                    self.showMsg((reply && reply.message) || 'Не удалось загрузить файл', true);
                }
            }).catch(function () {
                self.setBusy(false);
                self.showMsg('Не удалось загрузить файл', true);
            });
        },

        save: function () {
            const self = this;
            if (this.busy || this.tenderId <= 0) {
                return;
            }

            this.setBusy(true);
            this.showMsg('');

            $.fRequest({
                url: '/api/supplier/tender/' + this.tenderId + '/save/',
                method: 'POST',
                showMessages: true,
                data: { data: this.collectPayload() },
                onSuccess: function () {
                    window.location.reload();
                },
                onError: function (reply) {
                    self.setBusy(false);
                    self.showMsg((reply && reply.message) || 'Не удалось сохранить', true);
                }
            }).catch(function () {
                self.setBusy(false);
            });
        },

        submit: function () {
            const self = this;
            if (this.busy || this.tenderId <= 0) {
                return;
            }

            this.setBusy(true);
            this.showMsg('');

            $.fRequest({
                url: '/api/supplier/tender/' + this.tenderId + '/save/',
                method: 'POST',
                showMessages: false,
                data: { data: this.collectPayload() },
                onSuccess: function () {
                    $.fRequest({
                        url: '/api/supplier/tender/' + self.tenderId + '/submit/',
                        method: 'POST',
                        showMessages: true,
                        data: {},
                        onSuccess: function () {
                            window.location.reload();
                        },
                        onError: function (reply) {
                            self.setBusy(false);
                            self.showMsg((reply && reply.message) || 'Не удалось подать заявку', true);
                        }
                    }).catch(function () {
                        self.setBusy(false);
                    });
                },
                onError: function (reply) {
                    self.setBusy(false);
                    self.showMsg((reply && reply.message) || 'Не удалось сохранить перед подачей', true);
                }
            }).catch(function () {
                self.setBusy(false);
            });
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
})(jQuery);