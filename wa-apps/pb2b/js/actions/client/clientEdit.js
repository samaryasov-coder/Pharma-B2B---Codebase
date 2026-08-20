(function($) {
    $.extend($.pb2b, {
        clientEdit: {
            getDataDadataBtn: null,
            addClientContactAdd: null,
            contactsWrap: null,
            contactIndex: 0,
            init: function() {
                this.getDataDadataBtn = $('.js-dadata-fill');
                this.addClientContactAdd = $('.js-client-contact-add');
                this.contactsWrap = $('.js-client-contacts');

                this.initAddContact();
                this.initDadataButton();
            },

            initAddContact: function() {
                var self = this;
                self.addClientContactAdd.on('click', function(e) {
                    e.preventDefault();
                    self.addContactBlock();
                });

                $(document).on('click', '.js-client-contact-remove', function(e) {
                    e.preventDefault();
                    $(this).closest('.js-client-contact-item').remove();
                });
            },

            addContactBlock: function() {
                var i = this.contactIndex++;

                var html = ''
                    + '<div class="fields-group js-client-contact-item">'
                    + '<div class="field">'
                    + '    <div class="name">ФИО</div>'
                    + '    <div class="value">'
                    + '        <input type="text" name="data[contacts]['+i+'][name]" class="w100">'
                    + '    </div>'
                    + '</div>'

                    + '<div class="field">'
                    + '    <div class="name">Должность</div>'
                    + '    <div class="value">'
                    + '         <input type="text" name="data[contacts]['+i+'][post]" class="w100">'
                    + '    </div>'
                    + '</div>'

                    + '<div class="field">'
                    + '    <div class="name">Телефон</div>'
                    + '    <div class="value">'
                    + '        <input type="text" name="data[contacts]['+i+'][phone]" class="w100">'
                    + '    </div>'
                    + '</div>'

                    + '<div class="field">'
                    + '    <div class="name">E-mail</div>'
                    + '    <div class="value">'
                    + '        <input type="text" name="data[contacts]['+i+'][email]" class="w100">'
                    + '    </div>'
                    + '</div>'
                    + '<button class="button red smallest mt15 js-client-contact-remove">Удалить</button>'
                    + '</div>';

                this.contactsWrap.prepend(html);
            },

            initDadataButton: function() {
                var self = this;

                this.getDataDadataBtn.on('click', function(e) {
                    e.preventDefault();
                    
                    var btn = $(this);
                    var type = btn.data('type');
                   
                    var input = btn.closest('.flexbox').find('input[type="text"]').first();
                    var query = $.trim(input.val() || '').replace(/\D+/g, '');
                        
                    $.post('?module=client&action=dadata', { type: type, query: query }, function(r) {
                        if (!r || r.status !== 'ok') { alert('Ошибка ответа сервера'); return; }
            
                        var payload = r.data || {};
                        if (payload.error) {
                            alert(payload.message ? payload.message : 'Ошибка DaData');
                            return;
                        }
            
                        self.applyDadata(type, payload.data || {});
                    }, 'json');
                
                });
            },

            applyDadata: function(type, d) {
                if (type === 'party') {
                    if (d.name !== undefined) $('input[name="data[name]"]').val(d.name);
                    if (d.inn !== undefined) $('input[name="data[inn]"]').val(d.inn);
                    if (d.kpp !== undefined) $('input[name="data[kpp]"]').val(d.kpp);
                    if (d.ogrn !== undefined) $('input[name="data[ogrn]"]').val(d.ogrn);
                    if (d.jur_address !== undefined) $('input[name="data[jur_address]"]').val(d.jur_address);
            
                    var $phone = $('input[name="data[phone]"]');
                    if (!$phone.val() && d.phone) $phone.val(d.phone);
            
                    var $email = $('input[name="data[registry_email]"]');
                    if (!$email.val() && d.registry_email) $email.val(d.registry_email);
                }
            
                if (type === 'bank') {
                    if (d.bic !== undefined) $('input[name="data[bik]"]').val(d.bic);
                    if (d.bank_name !== undefined) $('input[name="data[bank]"]').val(d.bank_name);
                    if (d.correspondent_account !== undefined) $('input[name="data[ks]"]').val(d.correspondent_account);
                    // rs - его обычно вводят вручную
                    // if (d.swift !== undefined) $('input[name="data[swift]"]').val(d.swift);
                }
            }
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
    $.pb2b.clientEdit.init();
});