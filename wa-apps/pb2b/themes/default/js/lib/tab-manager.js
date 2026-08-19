(function($) {
    window.TabManager = class TabManager {
        constructor(options = {}) {
            this.container = $(options.container || 'body');

            // дефолтные классы
            this.classes = $.extend({
                btn: '.tab-btn',
                content: '.tab-content',
                active: 'selected'
            }, options.classes || {});

            this.defaultTab = options.defaultTab || null;
            this.onChange = typeof options.onChange === 'function' ? options.onChange : null;

            this.$buttons = this.container.find(this.classes.btn);
            this.$contents = this.container.find(this.classes.content);

            this.init();
        }

        init() {
            this.bindEvents();
            this.showTabFromHash();
        }

        bindEvents() {
            const self = this;

            this.$buttons.on('click', function() {
                const tab = $(this).data('tab');
                if (!tab) return;
                location.hash = tab;
            });

            $(window).on('hashchange', function() {
                self.showTabFromHash();
            });
        }

        showTabFromHash() {
            let hash = location.hash.replace('#', '');
            if (!hash) {
                hash = this.defaultTab || this.$buttons.first().data('tab');
            }

            if (!hash) return;
            window.location.hash = hash;

            this.showTab(hash);
        }

        showTab(tab) {
            const $btn = this.$buttons.filter(`[data-tab="${tab}"]`);
            const $content = this.$contents.filter(`[data-tab="${tab}"]`);

            if (!$btn.length || !$content.length) return;

            const prevTab = this.currentTab || null;
            this.currentTab = tab;

            this.$buttons.removeClass(this.classes.active);
            $btn.addClass(this.classes.active);

            this.$contents.hide();
            $content.show();

            if (this.onChange)
                this.onChange({tab, prevTab, button: $btn, content: $content});
        }

    }

})(jQuery);