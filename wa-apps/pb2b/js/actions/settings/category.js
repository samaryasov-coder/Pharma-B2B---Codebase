(function($) {
    $.extend($.pb2b.settings, {

    });
    $.extend($.pb2b.action, {
        afterSubmit: function (data, form) {
            if (data.item.id) {
                let menu = $.pb2b.menu;
                let item = $(menu.menuItemClass+'[data-id="'+data.item.id+'"]');
                item.find('.name').text(data.item.name);
                form.find('.js-form-message').find('svg').hide();
                form.find('.js-form-message').find('.fa-check-circle').show();
                form.find('.js-form-message').find('.js-form-message-text').text(data.message ?? 'Данные сохранены');
                form.find('.js-form-message').removeClass('danger').addClass('success').show();
            }
        },
        categoryDelete: function (params) {
            $.post('?module=category&action=delete', params, function (Json) {
                if (Json.data.error) {
                    wa_pro_form.find('.js-form-message').find('svg').hide();
                    wa_pro_form.find('.js-form-message').find('.fa-skull').show();
                    wa_pro_form.find('.js-form-message').find('.js-form-message-text').text(Json.data.message);
                    wa_pro_form.find('.js-form-message').removeClass('success').addClass('danger').show();
                } else {
                    wa_pro_drawer.close();
                    $.pb2b.dispatch(1);
                }
            }, 'JSON');
        }
    });
})(jQuery);
$(document).ready(function() {
    $.pb2b.settings.init();
});