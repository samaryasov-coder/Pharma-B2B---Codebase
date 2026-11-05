(function ($) {
    $(document).off('click.details', '.js-detail-copy').on('click.details', '.js-detail-copy', function (event) {
        event.preventDefault();
        const $button = $(this);
        const value = String(this.getAttribute('data-copy') || '');
        const label = String(this.getAttribute('aria-label') || 'Скопировать');
        const copied = function () {
            $button.addClass('is-copied').attr('aria-label', 'Скопировано').attr('title', 'Скопировано');
            window.setTimeout(function () {
                $button.removeClass('is-copied').attr('aria-label', label).attr('title', label);
            }, 1500);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(value).then(copied).catch(function () {
                window.prompt('Скопируйте значение', value);
            });
        } else {
            window.prompt('Скопируйте значение', value);
        }
    });
})(jQuery);
