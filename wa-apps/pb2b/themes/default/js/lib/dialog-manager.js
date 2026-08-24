(function ($) {
    class DialogManager {
        constructor(options = {}) {
            this.options = $.extend(true, {
                title: '',
                content: '',
                url: null,

                width: 500,

                showConfirmButton: false,
                showCancelButton: false,
                showCloseButton: true,

                confirmText: 'OK',
                cancelText: 'Отмена',

                confirmButtonClass: 'button primary',
                cancelButtonClass: 'button secondary',

                ajaxSubmitUrl: null,
                ajaxSubmitData: null,

                onOpen: null,
                onSuccess: null,
                onClose: null
            }, options);

            this._closed = false;
            this._init();
        }


        async _init() {
            try {
                const html = await this._loadContent();
                this._render(html);
            } catch (e) {
                Swal.fire('Ошибка', 'Не удалось загрузить данные', 'error');
            }
        }

        _loadContent() {
            if (this.options.url) {
                return $.ajax({
                    url: this.options.url,
                    method: this.options.method || 'GET',
                    data: this.options.data || {}
                });
            }

            return $.when(this.options.content || '');
        }

        _render(templateHtml) {

            const $tmp = $('<div>').html(templateHtml);
            const titleFromTemplate = $tmp.find('#dialog').data('title');

            const title = this._escape(
                this.options.title || titleFromTemplate || ''
            );

            Swal.fire({
                title,
                html: templateHtml,

                heightAuto: false,
                scrollbarPadding: false,

                width: typeof this.options.width === 'number'
                    ? `${this.options.width}px`
                    : this.options.width,

                customClass: {
                    popup: 'modal',
                    header: 'modal-head',
                    title: 'modal-title',
                    htmlContainer: 'modal-html-container',
                    confirmButton: this.options.confirmButtonClass,
                    cancelButton: this.options.cancelButtonClass,
                    closeButton: 'modal-close'
                },

                showConfirmButton: this.options.showConfirmButton,
                showCancelButton: this.options.showCancelButton,
                showCloseButton: this.options.showCloseButton,

                confirmButtonText: this.options.confirmText,
                cancelButtonText: this.options.cancelText,

                buttonsStyling: false,

                didOpen: (el) => {
                    const $container = $(Swal.getHtmlContainer());
                    $container.on('click', '.modal-cancel', () => {
                        this.close();
                    });
                    if (typeof this.options.onOpen === 'function') {
                        this.options.onOpen($container);
                    }
                },

                preConfirm: async () => {
                    if (!this.options.ajaxSubmitUrl) return;
                    return await $.fRequest({
                        url: this.options.ajaxSubmitUrl,
                        data: this.options.ajaxSubmitData || {},
                        button: Swal.getConfirmButton()
                    });
                }
            }).then((result) => {

                this._closed = true;

                if (result.isConfirmed && typeof this.options.onSuccess === 'function') {
                    this.options.onSuccess(result.value ?? null);
                }

                if (typeof this.options.onClose === 'function') {
                    this.options.onClose(result);
                }
            });
        }

        close() {
            if (this._closed) return;
            this._closed = true;
            Swal.close();
        }

        _escape(str) {
            return $('<div>').text(str || '').html();
        }


        static confirm({
           type = 'default',
           title,
           message,
           icon = '#icon-info',
           iconClass = 'neutral',
           width = 520,
           confirmText,
           cancelText,
           confirmButtonClass,
           cancelButtonClass,
           ajaxSubmitUrl,
           ajaxSubmitData,

           onConfirm
       }){
            const presets = {
                destruct: {
                    icon: '#icon-info',
                    iconClass: 'error',
                    confirmButtonClass: 'button large destruct',
                    confirmText: 'Удалить'
                },

                success: {
                    icon: '#icon-check-circle',
                    iconClass: 'success',
                    confirmButtonClass: 'button large primary',
                    confirmText: 'Принять'
                }
            };

            const preset = presets[type] || {};

            return new DialogManager({
                title: '',
                width,
                content: `
                    <div class="content-wrapper">
                        <span class="icon-square xxl ${preset.iconClass || iconClass}">
                            <svg><use href="${preset.icon || icon}"></use></svg>
                        </span>
        
                        <div class="confirm-content">
                            <span class="title">${$('<div>').text(title).html()}</span>
                            ${message?.trim() ? `<span class="message">${$('<div>').text(message).html()}</span>` : ''}
                        </div>
                    </div>
                `,
                showConfirmButton: true,
                showCancelButton: true,
                confirmText: confirmText || preset.confirmText,
                cancelText,
                confirmButtonClass: confirmButtonClass || preset.confirmButtonClass,
                cancelButtonClass,
                ajaxSubmitUrl,
                ajaxSubmitData,
                onSuccess: onConfirm
            });
       }
    }

    $(function () {
        $.DialogManager = DialogManager;
    });

})(jQuery);