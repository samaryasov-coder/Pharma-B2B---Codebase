<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:47:26
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/configure/ConfigureRedirectDialog.html" */ ?>
<?php /*%%SmartyHeaderCode:16859323176a8c83ae038507-23329903%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cb4552ec1441fcef1bdae84d7decc13f23946c57' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/configure/ConfigureRedirectDialog.html',
      1 => 1782814434,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16859323176a8c83ae038507-23329903',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'route_id' => 0,
    'domain_name' => 0,
    'route' => 0,
    'route_disabled' => 0,
    'is_301' => 0,
    'route_code' => 0,
    'wa_app_url' => 0,
    'domain_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c83ae04e182_59356175',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c83ae04e182_59356175')) {function content_6a8c83ae04e182_59356175($_smarty_tpl) {?>
<div class="dialog s-settings-redirect-dialog" id="js-settings-redirect-dialog">
    <div class="dialog-background"></div>
    <div class="dialog-body" style="">

        <header class="dialog-header">
            <h4>Правило перенаправления</h4>
        </header>
        <div class="dialog-content">
            <form>
            <div class="fields">
                <input type="hidden" name="id" value="<?php if (strlen($_smarty_tpl->tpl_vars['route_id']->value)){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['route_id']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
                <div class="field-group field vertical old-address">
                    <div class="name">Старый адрес</div>
                    <div class="value">
                        <span class="s-domain-url bold break-word s-small"><?php echo waIdna::dec((($tmp = @$_smarty_tpl->tpl_vars['domain_name']->value)===null||$tmp==='' ? '' : $tmp));?>
/</span>
                        <input type="text" name="params[url]" value="<?php if (strlen($_smarty_tpl->tpl_vars['route_id']->value)){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['route']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?>*<?php }?>" class="js-old-url bold long s-small" />
                    </div>
                </div>
                <div class="field field-glymph align-center">
                    <div class="value">
                        <span class="icon custom-px-4 text-dark-gray"><i class="fas fa-long-arrow-alt-down"></i></span>
                    </div>
                </div>
                <div class="field-group field vertical new-address">
                    <div class="name">Новый адрес</div>
                    <div class="value">
                        <input type="text" name="params[redirect]" value="<?php if (!empty($_smarty_tpl->tpl_vars['route']->value['redirect'])){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['route']->value['redirect'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" class="js-new-url bold longer s-small" />
                        <p class="hint">Если у нового адреса тот же домен, то можно указать только часть после домена, например, <em>/about/</em>.</p>
                    </div>
                </div>
                <div class="field field-redirect-disabled">
                    <div class="value">
                        <?php if (empty($_smarty_tpl->tpl_vars['route']->value['disabled'])&&ifempty($_smarty_tpl->tpl_vars['route']->value['disabled'])<=0){?>
                            <?php $_smarty_tpl->tpl_vars['route_disabled'] = new Smarty_variable(null, null, 0);?>
                        <?php }else{ ?>
                            <?php $_smarty_tpl->tpl_vars['route_disabled'] = new Smarty_variable(1, null, 0);?>
                        <?php }?>

                        <div class="switch-with-text">
                            <span class="switch smaller" id="switch-redirect-dialog-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['route_id']->value)===null||$tmp==='' ? 'new' : $tmp);?>
">
                                <input type="checkbox" id="switch-redirect" name="params[disabled]" value="<?php if ($_smarty_tpl->tpl_vars['route_disabled']->value){?>1<?php }else{ ?>0<?php }?>" <?php if (!$_smarty_tpl->tpl_vars['route_disabled']->value){?>checked<?php }?>>
                                <input type="hidden" id="switch-redirect-hidden" name="params[disabled]" value="<?php if ($_smarty_tpl->tpl_vars['route_disabled']->value){?>1<?php }else{ ?>0<?php }?>">
                            </span>
                            <label class="bold s-small" for="switch-redirect" data-active-text="Включено" data-inactive-text="Выключено"><?php if (!$_smarty_tpl->tpl_vars['route_disabled']->value){?>Включено<?php }else{ ?>Выключено<?php }?></label>
                        </div>
                        <script>
                            ( function($) {
                                $switch = $("#switch-redirect-dialog-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['route_id']->value)===null||$tmp==='' ? 'new' : $tmp);?>
");
                                $switch.waSwitch({
                                    ready: function (wa_switch) {
                                        let $label = wa_switch.$wrapper.siblings('label');
                                        let $input = wa_switch.$wrapper.find('input');
                                        wa_switch.$label = $label;
                                        wa_switch.$input = $input;
                                        wa_switch.active_text = $label.data('active-text');
                                        wa_switch.inactive_text = $label.data('inactive-text');
                                    },
                                    change: function(active, wa_switch) {
                                        if (active) {
                                            wa_switch.$input.each(function(){
                                                $(this).val('0')
                                            });
                                            wa_switch.$label.text(wa_switch.active_text);
                                        }
                                        else {
                                            wa_switch.$input.each(function(){
                                                $(this).val('1')
                                            });
                                            wa_switch.$label.text(wa_switch.inactive_text);
                                        }
                                    }
                                });
                            })(jQuery);
                        </script>
                    </div>
                </div>
                <div class="field field-response-code">
                    <div class="value">
                        <?php if (empty($_smarty_tpl->tpl_vars['route']->value['code'])||$_smarty_tpl->tpl_vars['route']->value['code']==301){?>
                            <?php $_smarty_tpl->tpl_vars['route_code'] = new Smarty_variable(301, null, 0);?>
                            <?php $_smarty_tpl->tpl_vars['is_301'] = new Smarty_variable(1, null, 0);?>
                        <?php }else{ ?>
                            <?php $_smarty_tpl->tpl_vars['route_code'] = new Smarty_variable(302, null, 0);?>
                            <?php $_smarty_tpl->tpl_vars['is_301'] = new Smarty_variable(null, null, 0);?>
                        <?php }?>
                        <div class="toggle small" id="toggle-response-code">
                            <span class="<?php if ($_smarty_tpl->tpl_vars['is_301']->value){?>selected<?php }?>" data-id="301">Постоянное (301)</span>
                            <span class="<?php if (!$_smarty_tpl->tpl_vars['is_301']->value){?>selected<?php }?>" data-id="302">Временное (302)</span>
                            <input type="hidden" name="params[code]" value="<?php echo $_smarty_tpl->tpl_vars['route_code']->value;?>
" <?php if (!$_smarty_tpl->tpl_vars['is_301']->value){?> checked<?php }?>>
                        </div>
                        <div class="hint">Постоянное перенаправление с кодом ответа 301 сообщает поисковым системам, что новый URL должен индексироваться вместо старого. Используйте временное перенаправление с кодом ответа 302, если вы планируете отменить его в будущем.</div>
                    </div>

                </div>

                <div id="s-route-comment" class="field vertical">
                    <div class="name">Комментарий</div>
                    <div class="value">
                        <textarea name="params[comment]" class="s-comment"><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['route']->value['comment'])===null||$tmp==='' ? null : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</textarea>
                    </div>
                </div>
            </div>
        </form></div>
        <footer class="dialog-footer">
            <div class="flexbox middle space-8 wrap">
                <div class="">
                    <button class="js-save button">Сохранить</button>
                    <button class="js-close-dialog button light-gray">Отмена</button>
                </div>
                <?php if (strlen($_smarty_tpl->tpl_vars['route_id']->value)){?>
                    <div class="wide align-right">
                        <button class="js-delete red nobutton"><i class="fas fa-trash-alt"></i> <span class="desktop-only">Удалить</span></button>
                    </div>
                <?php }?>
                <div class="js-place-for-errors state-error-hint<?php if (strlen($_smarty_tpl->tpl_vars['route_id']->value)){?> width-100<?php }?>"></div>
            </div>
        </footer>
    </div>
</div>

<script>(function() { "use strict";

    const site_app_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
;
    const domain_id = <?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
;
    const $route_id = <?php if (strlen($_smarty_tpl->tpl_vars['route_id']->value)){?><?php echo $_smarty_tpl->tpl_vars['route_id']->value;?>
<?php }else{ ?>''<?php }?>;
    const save_url = site_app_url + '?module=configure&action=redirectSave' + '&domain_id=' + domain_id + '&route=' + $route_id;
    const delete_url = site_app_url + '?module=configure&action=redirectDelete' + '&domain_id=' + domain_id;

    const $wrapper = $('#js-settings-redirect-dialog');
    const $form = $wrapper.find('form');
    const $save_button = $wrapper.find('.js-save');
    const $place_for_errors = $wrapper.find('.js-place-for-errors');
    var dialog;
    initToggle()

    function initToggle() {
        setTimeout(() => {
            $form.find("#toggle-response-code").waToggle({
                change: function(event, target, toggle) {
                    const input = toggle.$wrapper.find('input');
                    input.attr('checked') ? input.attr('checked', false) : input.attr('checked', true);
                    input.val($(target).data('id'));
                }
            });
        }, 1);
    }

    // Save to server when user clicks Save button
    $save_button.on('click', function() {
        saveHandler();
        return false;
    });
    $form.submit(function() {
        saveHandler();
        return false;
    });

    // Delete page when user clicks on Delete button
    $wrapper.on('click', '.js-delete', function() {
        $.waDialog.confirm({
                title: 'Удаление перенаправления',
                text: 'Перенаправление будет удалено из сайта. Продолжить?',
                success_button_title: "Удалить",
                success_button_class: 'danger',
                cancel_button_title: "Отмена",
                cancel_button_class: 'light-gray',
                onSuccess: deleteHandler
            });
        });

    function deleteHandler() {
        if ($route_id) {
            $.post(delete_url, { 'route': $route_id }).then(function(r) {
                handleResponse(r, () => {
                    $wrapper.data('dialog').close();
                    if (typeof $.site.reloadWithScrollTo === 'function') {
                        $.site.reloadWithScrollTo();
                    } else {
                        $(document).trigger('wa_delete_route', [$route_id, r.data]);
                    }
                });
            }, function(r) {
                console.log('Error saving page settings', arguments);
                updateRoutingErrors(r.errors);
            });
        }
    }


    let errors = [];

    function updateRoutingErrors(errors) {

        if ($.isArray(errors)) {
            errors.forEach(function(e) {
                var $field = null;
                if (e.field) {
                    $field = $form.find('[name="'+e.field+'"]');
                }
                const $msg = $('<div class="state-error-hint custom-mt-4"></div>').html(e.description);

                if($field && $field.length) {
                    $field.addClass('state-error').after($msg);
                }
            });
            return
        }
            $place_for_errors.append(errors);

    }

    function validateUrls() {
        // Rule address contains unsupported character, regexp for define it
        const invalid_url_regexp = /(\&|\$|\+|\,|\;|\=|\?|\@|\#|\[|\]|\}|\||\^|\%)/;
        const $old_url = $form.find('.js-old-url'),
              old_url = $old_url.val(),
              res = old_url.match(invalid_url_regexp);
        if (res) {
            //$settings_form_status.html('');
            let title = 'Невозможно сохранить правило',
                content = 'В адресе правила содержится недопустимый символ <strong class="highlighted">%s</strong>.';
            content = content.replace(/\%s/, res[0]);
            errors.push({
                field: $old_url.attr("name"),
                description: content
            });
        }

        const $new_url = $form.find('.js-new-url'),
              new_url = $new_url.val();
        if (!isValidURL(new_url)) {
            let content = 'Введите корректный URL-адрес, начинающийся с <mark>/</mark>, <mark>http://</mark> или <mark>https://</mark>.';
            errors.push({
                field: $new_url.attr("name"),
                description: content
            });
        }

        if (errors.length) {
            console.log(errors)
            updateRoutingErrors(errors);
            return false
        }

        return true;
    }

    function saveHandler() {
        //clear errors
        errors = [];
        $form.find('.state-error').removeClass('state-error');
        $form.find('.state-error-hint').remove();
        $place_for_errors.empty();

        // Validating unsupported characters in url
        if (!validateUrls()) return

        $.post(save_url, $form.serialize(), 'json').then(function(r) {
            handleResponse(r, () => {
                $wrapper.data('dialog').close();
                if (typeof $.site.reloadWithScrollTo === 'function') {
                    $.site.reloadWithScrollTo();
                } else {
                    $(document).trigger('wa_update_route', [$route_id, r.data]);
                }
            });
        }, function() {
            console.log('Error saving page settings', arguments);
            updateRoutingErrors(r.errors);
        });
    }

    
    function isValidURL(url) {
        return url.match(/^(https?:\/\/|\/)([a-zA-Z0-9а-яА-ЯёЁ\-]+)/i);
    }
    

    function handleResponse(res, cbSuccess) {
        if (!res) return;

        $place_for_errors.empty();

        if (res.errors) {
            $place_for_errors.append(res.errors);

        } else if (res.data && res.data.confirm) {
            $place_for_errors.append(res.data.confirm);
        }
        if (res?.data?.routing_errors?.incorrect) {
            updateRoutingErrors(res.data.routing_errors);
        }
        if (res.status === 'ok' && typeof cbSuccess === 'function') {
            cbSuccess(res);
        }
    }

})();</script>
<?php }} ?>