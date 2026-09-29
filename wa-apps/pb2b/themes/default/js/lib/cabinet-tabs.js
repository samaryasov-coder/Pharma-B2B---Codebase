/**
 * Общие underline-табы кабинета.
 * Разметка: .js-ds-tabs > .js-ds-tabs-tab[data-tab]
 * Панели ищутся в ближайшем .js-ds-tabs-scope (или в родителе таббара).
 */
(function ($) {
    'use strict';

    function activate($tabs, tabId) {
        const id = String(tabId || '').trim();
        if (!id || !$tabs.length) {
            return;
        }

        $tabs.find('.js-ds-tabs-tab').each(function () {
            const on = String(this.getAttribute('data-tab') || '') === id;
            $(this)
                .toggleClass('is-active', on)
                .attr('aria-selected', on ? 'true' : 'false');
        });

        let $scope = $tabs.closest('.js-ds-tabs-scope');
        if (!$scope.length) {
            $scope = $tabs.parent();
        }

        $scope.find('.js-ds-tabs-panel').each(function () {
            const on = String(this.getAttribute('data-tab-panel') || '') === id;
            $(this).toggleClass('is-active', on).prop('hidden', !on);
        });

        $tabs.trigger('ds-tabs:change', [id]);
    }

    $(document).on('click', '.js-ds-tabs-tab', function (event) {
        event.preventDefault();
        const $tab = $(this);
        if ($tab.hasClass('is-disabled') || $tab.prop('disabled')) {
            return;
        }
        const tabId = String($tab.attr('data-tab') || '').trim();
        if (!tabId) {
            return;
        }
        activate($tab.closest('.js-ds-tabs'), tabId);
    });

    window.Pb2bCabinetTabs = {
        activate: activate
    };
})(jQuery);
