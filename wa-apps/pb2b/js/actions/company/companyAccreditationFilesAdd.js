(function($) {
    $.extend($.pb2b, {
        companyAccreditationFilesAdd: {
            filUpload: null,

            init: function() {
                this.filUpload = $('#pb2b_company_accreditation_fileupload');
                this.filUpload.waUpload({
                    show_file_name: true
                });
            },
        }
    });
    $.extend($.pb2b.action, {
        afterSubmit: function (data, form) {
            if (data.item.id) {
                // let menu = $.pb2b.menu;
                // let item = $(menu.menuItemClass+'[data-id="'+data.item.id+'"]');
                // item.find('.name').text(data.item.name);
                // form.find('.js-form-message').find('svg').hide();
                // form.find('.js-form-message').find('.fa-check-circle').show();
                // form.find('.js-form-message').find('.js-form-message-text').text(data.message ?? 'Данные сохранены');
                // form.find('.js-form-message').removeClass('danger').addClass('success').show();
            }
        },
    });
})(jQuery);

$(document).ready(function() {
    $.pb2b.companyAccreditationFilesAdd.init();
});
