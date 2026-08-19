(function($) {
    $.extend($.pb2b, {
        settings: {
            ajaxContent: null,
            ajaxRequest: null,

            init: function() {
                this.ajaxContent = $('.pb2b-settings-ajax');
                this.dispatchSettings();

                var self = this;
                $(window).on('hashchange.pb2bSettings', function() {
                    self.dispatchSettings();
                });
            },

            dispatchSettings: function(force) {
                var self = this;
                if (typeof(force) === 'undefined') force = false;
                var hash = window.location.hash.replace('#', '').split('/');
                if (hash.length < 2 || hash[1] !== 'settings') return;
            
                var action = hash[2] || 'page';
                var data = hash[3] || '';
            
                // если вдруг попали на старый формат #/settings/api - подменим на page
                if (action !== 'page') {
                    data = 'type=' + encodeURIComponent(action) + (data ? '&' + data : '');
                    action = 'page';
                }
            
                // вытащим type из data
                var type = 'general';
                if (data) {
                    var m = data.match(/(?:^|&)type=([^&]+)/);
                    if (m && m[1]) type = decodeURIComponent(m[1]);
                }
            
                // подсветка меню по type
                $('.appls-settings-tab-controls li').removeClass('selected');
                $('.appls-settings-tab-controls a[href="#/settings/page/type=' + type + '"]').closest('li').addClass('selected');
            
                // защита от повторной загрузки
                var key = action + '|' + data;
                if (!force && this._prevKey === key) return;
                this._prevKey = key;
            
                $.ajax({
                    method: 'post',
                    url: '?module=settings&action=page',
                    data: data,
                    cache: false,
                    success: function(html) {
                        self.ajaxContent.html(html);
                        if (typeof init === 'function') init();
                    }
                });
            },
            
        }  
    });
    // $.extend($.pb2b.action, {
    //     afterSubmit: function (data, form) {
    //         if (data.item.id) {
    //             let menu = $.pb2b.menu;
    //             let item = $(menu.menuItemClass+'[data-id="'+data.item.id+'"]');
    //             item.find('.name').text(data.item.name);
    //             form.find('.js-form-message').find('svg').hide();
    //             form.find('.js-form-message').find('.fa-check-circle').show();
    //             form.find('.js-form-message').find('.js-form-message-text').text(data.message ?? 'Данные сохранены');
    //             form.find('.js-form-message').removeClass('danger').addClass('success').show();
    //         }
    //     },
    //     categoryDelete: function (params) {
    //         $.post('?module=category&action=delete', params, function (Json) {
    //             if (Json.data.error) {
    //                 wa_pro_form.find('.js-form-message').find('svg').hide();
    //                 wa_pro_form.find('.js-form-message').find('.fa-skull').show();
    //                 wa_pro_form.find('.js-form-message').find('.js-form-message-text').text(Json.data.message);
    //                 wa_pro_form.find('.js-form-message').removeClass('success').addClass('danger').show();
    //             } else {
    //                 wa_pro_drawer.close();
    //                 $.pb2b.dispatch(1);
    //             }
    //         }, 'JSON');
    //     }
    // });
})(jQuery);
$(document).ready(function() {
    $.pb2b.settings.init();
});