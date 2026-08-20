(function($) {
    $.extend($.pb2b, {
        companyEdit: {
            addPhoneBtn: null,
            addEmailBtn: null,

            init: function() {
                this.addPhoneBtn = $('.js-add-reserve-phone');
                this.addEmailBtn = $('.js-add-reserve-email');

                this.addPhoneBtn.on('click', function(e) {
                    e.preventDefault();
                    var $btnRow = $(this).closest('.js-add-reserve-phone-row');

                    var html = ''
                        + '<div class="field">'
                        + '  <div class="name">Дополнительный телефон</div>'
                        + '  <div class="value">'
                        + '    <input type="text" name="data[reserve_phones][]" value="" class="w100">'
                        + '  </div>'
                        + '</div>';

                    $btnRow.before(html);
                    $btnRow.prev('.js-reserve-phone-row').find('input').focus();
                });

                this.addEmailBtn.on('click', function(e) {
                    e.preventDefault();
                    var $btnRow = $(this).closest('.js-add-reserve-email-row');

                    var html = ''
                        + '<div class="field">'
                        + '  <div class="name">Дополнительный E-mail</div>'
                        + '  <div class="value">'
                        + '    <input type="text" name="data[reserve_emails][]" value="" class="w100">'
                        + '  </div>'
                        + '</div>';

                    $btnRow.before(html);
                    $btnRow.prev('.js-reserve-email-row').find('input').focus();
                });

                this.bindCompanyType();
                this.bindDocflowStandard();
            },

            bindDocflowStandard: function() {
                $(document)
                    .off('click.pb2bDocflowApplyStd')
                    .on('click.pb2bDocflowApplyStd', '.js-docflow-apply-standard', function(e) {
                        e.preventDefault();
                        var $w = $(this).closest('.js-docflow-standard-wrap');
                        var companyId = parseInt($w.data('company-id'), 10) || 0;
                        if (!companyId) {
                            return;
                        }
                        var scope = ($w.find('.js-docflow-standard-scope').val() || 'all');
                        var $msg = $w.find('.js-docflow-standard-msg');
                        $msg.text('');
                        $.post('?module=company&action=docflowTemplateApplyStandard', {
                            company_id: companyId,
                            process_type: 1,
                            scope: scope
                        }, function(r) {
                            var d = (r && r.status === 'ok' && r.data) ? r.data : null;
                            if (!d) {
                                $msg.text('Ошибка ответа сервера');
                                return;
                            }
                            if (d.error) {
                                $msg.text(d.message || 'Ошибка');
                                return;
                            }
                            $msg.text(d.message || 'Готово');
                            $.pb2b.dispatch(true);
                        }, 'json').fail(function() {
                            $msg.text('Ошибка запроса');
                        });
                    });
            },

            bindCompanyType: function() {
                var self = this;

                $(document)
                    .off('change.pb2bCompanyType')
                    .on('change.pb2bCompanyType', '.js-company-type-select', function() {
                        self.toggleOrganizationType($(this));
                    });

                $('.js-company-type-select').each(function() {
                    self.toggleOrganizationType($(this));
                });
            },

            toggleOrganizationType: function($companyTypeSelect) {
                if (!$companyTypeSelect || !$companyTypeSelect.length) {
                    return;
                }

                var $form = $companyTypeSelect.closest('form');
                var $organizationTypeField = $form.find('.js-type-organization-field').first();
                var $organizationTypeSelect = $organizationTypeField.find('.js-type-organization-select').first();
                if (!$organizationTypeField.length || !$organizationTypeSelect.length) {
                    return;
                }

                var companyType = $.trim($companyTypeSelect.val() || '');
                var isOrganization = companyType === '1';

                if (isOrganization) {
                    $organizationTypeField.show();
                    $organizationTypeSelect.prop('disabled', false);
                    return;
                }

                $organizationTypeSelect.val('');
                $organizationTypeSelect.prop('disabled', true);
                $organizationTypeField.hide();
            },
        }
    });
    $.extend($.pb2b.action, {
        afterSubmit(Data, Form) {
            if (Data.dispatch_url) {
                window.location.hash = Data.dispatch_url;
            } else {
                $.pb2b.dispatch(true);
            }
        }
    });
})(jQuery);

$(document).ready(function() {
    $.pb2b.companyEdit.init();
});