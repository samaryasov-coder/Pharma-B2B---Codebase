/**
 * Общие underline-табы кабинета.
 * Разметка: .js-tabs > .js-tabs-tab[data-tab]
 * Панели ищутся в ближайшем .js-tabs-scope (или в родителе таббара).
 */
(function ($) {
    'use strict';

    function activate($tabs, tabId) {
        const id = String(tabId || '').trim();
        if (!id || !$tabs.length) {
            return;
        }

        $tabs.find('.js-tabs-tab').each(function () {
            const on = String(this.getAttribute('data-tab') || '') === id;
            $(this)
                .toggleClass('is-active', on)
                .attr('aria-selected', on ? 'true' : 'false');
        });

        let $scope = $tabs.closest('.js-tabs-scope');
        if (!$scope.length) {
            $scope = $tabs.parent();
        }

        $scope.find('.js-tabs-panel').filter(function () {
            return $(this).closest('.js-tabs-scope')[0] === $scope[0];
        }).each(function () {
            const on = String(this.getAttribute('data-tab-panel') || '') === id;
            $(this).toggleClass('is-active', on).prop('hidden', !on);
        });

        $tabs.trigger('tabs:change', [id]);
    }

    $(document).on('click', '.js-tabs-tab', function (event) {
        event.preventDefault();
        const $tab = $(this);
        if ($tab.hasClass('is-disabled') || $tab.prop('disabled')) {
            return;
        }
        const tabId = String($tab.attr('data-tab') || '').trim();
        if (!tabId) {
            return;
        }
        activate($tab.closest('.js-tabs'), tabId);
    });

    window.Pb2bCabinetTabs = {
        activate: activate
    };
})(jQuery);
