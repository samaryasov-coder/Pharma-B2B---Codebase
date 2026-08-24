(function ($) {
    class AlertManager {
        constructor() {
            this.container = $('#alert-container');

            if (!this.container.length) {
                this.container = $('<div>', { id: 'alert-container' }).appendTo('body');
            }

            this.alerts = [];
            this.offsetStep = 10;
        }

        show({ type, title, message, duration = 2000 }) {
            const id = Math.random().toString(36).substr(2, 9);

            let messageHtml = '';
            if (message) {
                if (typeof message === 'object' && !Array.isArray(message)) {
                    messageHtml = Object.values(message).join('');
                } else if (Array.isArray(message)) {
                    messageHtml = message.join('');
                } else {
                    messageHtml = message.toString();
                }
            }

            const alertElement = $(`
                <div class="alert ${type}" data-id="${id}">

                    <div class="alert-content">
                        <div class="alert-icon"><i class="${this.getIcon(type)}"></i></div>
                        <div class="alert-text">
                            <span class="alert-title text-l2 fw-semibold">${title}</span>
                            <span class="alert-message text-p3 fw-regular">${messageHtml}</span>
                        </div>
                    </div>
                    <div class="alert-close"><i class="fa fa-close"></i></div>
                </div>
            `);

            const offset = this.alerts.length * this.offsetStep;

            alertElement.css({
                transform: `translateX(-50%) translateY(100%) translateY(-${offset}px)`,
                'z-index': 50 + this.alerts.length
            });

            const alertObj = {
                id,
                element: alertElement,
                removing: false
            };

            this.alerts.push(alertObj);
            this.container.append(alertElement);

            alertElement.find('.alert-close').on('click', () => {
                this.remove(id);
            });

            setTimeout(() => {
                alertElement.css({
                    transform: `translateX(-50%) translateY(-${offset}px)`,
                    opacity: 1
                });
            }, 10);

            if (duration > 0) {
                alertObj.timer = setTimeout(() => {
                    this.remove(id);
                }, duration);
            }

            return id;
        }

        remove(id) {
            const alert = this.alerts.find(a => a.id === id);

            if (!alert || alert.removing) return;

            alert.removing = true;

            if (alert.timer) {
                clearTimeout(alert.timer);
            }

            alert.element.addClass('hide');

            setTimeout(() => {
                const index = this.alerts.findIndex(a => a.id === id);
                if (index === -1) return;

                this.alerts[index].element.remove();
                this.alerts.splice(index, 1);

                this.updatePositions();
            }, 300);
        }

        updatePositions() {
            this.alerts.forEach((alert, index) => {
                const offset = index * this.offsetStep;

                alert.element.css({
                    transform: `translateX(-50%) translateY(0) translateY(-${offset}px)`,
                    'z-index': 50 + index
                });
            });
        }

        getIcon(type) {
            const icons = {
                success: 'fas fa-check-circle',
                danger: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            return icons[type] || 'fas fa-info-circle';
        }

        showSuccess(message, title = 'Успешно!') {
            return this.show({ type: 'success', title, message });
        }

        showError(message, title = 'Ошибка!') {
            return this.show({ type: 'danger', title, message });
        }

        showWarning(message, title = 'Внимание!') {
            return this.show({ type: 'warning', title, message });
        }

        showInfo(message, title = 'Информация!') {
            return this.show({ type: 'info', title, message });
        }
    }

    $(function () {$.AlertManager = new AlertManager();});

})(jQuery);