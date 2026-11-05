(function ($) {
    window.TabsManager = class TabsManager {
        constructor(options = {}) {
            this.container = $(options.container || 'body');
            this.classes = $.extend({
                btn: '.tab-btn',
                content: '.tab-content',
                active: 'selected'
            }, options.classes || {});
            this.defaultTab = options.defaultTab || null;
            this.hashSync = options.hashSync !== false;
            this.contentActive = options.contentActive || 'is-active';
            this.onChange = typeof options.onChange === 'function' ? options.onChange : null;
            this.$buttons = this.container.find(this.classes.btn);
            const $contentScope = options.contentScope
                ? this.container.find(options.contentScope).first()
                : this.container;
            this.$contents = $contentScope.find(this.classes.content);
            if (!this.$contents.length && options.contentAttribute) {
                this.$contents = $contentScope.find(options.contentAttribute);
            }
            if (options.contentScope && $contentScope.length) {
                this.$contents = this.$contents.filter(function () {
                    return $(this).closest(options.contentScope)[0] === $contentScope[0];
                });
            }
            this.bindEvents();
            if (this.hashSync) {
                this.showTabFromHash();
            } else {
                this.showTab(this.defaultTab || this.$buttons.first().data('tab'));
            }
        }

        bindEvents() {
            const self = this;
            this.$buttons.on('click', function (event) {
                if ($(this).hasClass('is-disabled')) {
                    event.preventDefault();
                    return;
                }
                const tab = $(this).data('tab');
                if (tab) {
                    event.preventDefault();
                    if (self.hashSync) {
                        window.location.hash = tab;
                    } else {
                        self.showTab(tab);
                    }
                }
            });
            if (!this.hashSync) {
                return;
            }
            $(window).on('hashchange', function () {
                const hash = String(window.location.hash || '').replace(/^#/, '');
                if (self.$buttons.filter(function () { return String($(this).data('tab')) === hash; }).length) {
                    self.showTabFromHash();
                }
            });
        }

        showTabFromHash() {
            let tab = String(window.location.hash || '').replace(/^#/, '');
            const $hashButton = this.$buttons.filter(function () {
                return String($(this).data('tab')) === tab;
            });
            if (!$hashButton.length || $hashButton.hasClass('is-disabled') || $hashButton.attr('aria-disabled') === 'true') {
                tab = this.defaultTab || this.$buttons.first().data('tab');
            }
            if (tab) {
                if (window.location.hash !== '#' + tab) {
                    window.location.hash = tab;
                }
                this.showTab(tab);
            }
        }

        showTab(tab) {
            const $button = this.$buttons.filter(function () {
                return String($(this).data('tab')) === String(tab);
            });
            if (!$button.length || $button.hasClass('is-disabled')) {
                return;
            }

            const $content = this.$contents.filter(function () {
                const $panel = $(this);
                return String($panel.data('tab')) === String(tab)
                    || String($panel.data('tab-panel')) === String(tab);
            });
            const previousTab = this.currentTab || null;
            this.currentTab = String(tab);

            this.$buttons.removeClass(this.classes.active).find('[role="tab"], a').attr('aria-selected', 'false');
            $button.addClass(this.classes.active).find('[role="tab"], a').attr('aria-selected', 'true');

            if (this.$contents.length) {
                this.$contents.removeClass(this.contentActive).prop('hidden', true);
                $content.addClass(this.contentActive).prop('hidden', false);
            }

            if (this.onChange) {
                this.onChange({
                    tab: this.currentTab,
                    prevTab: previousTab,
                    button: $button,
                    content: $content
                });
            }
        }
    };
    window.TabManager = window.TabsManager;
})(jQuery);
