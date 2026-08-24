<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 17:13:13
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/client/tabs/create.html" */ ?>
<?php /*%%SmartyHeaderCode:680030376a89d8a98e55a3-25551973%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cdfd72146a4105378cd59c99f60e5b3ffda44e0f' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/client/tabs/create.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '680030376a89d8a98e55a3-25551973',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'object' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89d8a98f8076_63719771',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89d8a98f8076_63719771')) {function content_6a89d8a98f8076_63719771($_smarty_tpl) {?><form class="js-form form fields" action="?module=client&action=save">
    <div class="field">
        <div class="name">Название</div>
        <div class="value">
            <input type="text" name="data[name]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['name'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
        </div>
    </div>

    <div class="field">
        <div class="name">Тип</div>
        <div class="value">

            <div>
                <span class="switch js-wa-switch" id="switch-buyer-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['object']->value['id'])===null||$tmp==='' ? 'new' : $tmp);?>
">
                    <input type="checkbox"
                        id="demo-switch-buyer-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['object']->value['id'])===null||$tmp==='' ? 'new' : $tmp);?>
"
                        name="data[buyer]"
                        value="1"
                        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['object']->value['buyer'])===null||$tmp==='' ? 0 : $tmp)==1){?>checked<?php }?>>
                </span>
                <label for="demo-switch-buyer-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['object']->value['id'])===null||$tmp==='' ? 'new' : $tmp);?>
">Покупатель</label>
            </div>
            
            <div class="mt10">
                <span class="switch js-wa-switch" id="switch-supplier-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['object']->value['id'])===null||$tmp==='' ? 'new' : $tmp);?>
">
                    <input type="checkbox"
                        id="demo-switch-supplier-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['object']->value['id'])===null||$tmp==='' ? 'new' : $tmp);?>
"
                        name="data[supplier]"
                        value="1"
                        <?php if ((($tmp = @$_smarty_tpl->tpl_vars['object']->value['supplier'])===null||$tmp==='' ? 0 : $tmp)==1){?>checked<?php }?>>
                </span>
                <label for="demo-switch-supplier-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['object']->value['id'])===null||$tmp==='' ? 'new' : $tmp);?>
">Поставщик</label>
            </div>

        </div>
    </div>

    <div class="field">
        <div class="name">ИНН</div>
        <div class="value">
            <div class="flexbox middle">
                <input type="text" name="data[inn]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['inn'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100 js-inn">
                <button type="button" class="button gray js-dadata-fill" data-type="party" data-input="data[inn]">Заполнить</button>
            </div>
        </div>
    </div>

    <div class="field">
        <div class="name">КПП</div>
        <div class="value">
            <input type="text" name="data[kpp]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['kpp'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
        </div>
    </div>

    <div class="field">
        <div class="name">ОГРН</div>
        <div class="value">
            <input type="text" name="data[ogrn]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['ogrn'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
        </div>
    </div>

    <div class="field">
        <div class="name">Юридический адрес</div>
        <div class="value">
            <input type="text" name="data[jur_address]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['jur_address'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
        </div>
    </div>

    <div class="field">
        <div class="name">E-mail</div>
        <div class="value">
            <input type="text" name="data[registry_email]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['registry_email'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100">
        </div>
    </div>

    <div class="field">
        <div class="name">Телефон</div>
        <div class="value">
            <input type="text" name="data[phone]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['phone'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100">
        </div>
    </div>

    <div class="field">
        <div class="name">Веб-сайт</div>
        <div class="value">
            <input type="text" name="data[site]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['site'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100">
        </div>
    </div>

    <div class="field">
        <div class="name">Дата последнего контакта</div>
        <div class="value">
            <input type="date" name="data[last_contact_datetime]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['last_contact_datetime'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100">
        </div>
    </div>

    <div class="field">
        <div class="name">Комментарий</div>
        <div class="value">
            <textarea name="data[comment]" class="w100" rows="4"><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['comment'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</textarea>
        </div>
    </div>

    <div class="field">
        <div class="name">Банк</div>
        <div class="value">
            <input type="text" name="data[bank]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['bank'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100">
        </div>
    </div>

    <div class="field">
        <div class="name">БИК</div>
        <div class="value">
            <div class="flexbox middle">
                <input type="text" name="data[bik]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['bik'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100">
                <button type="button" class="button gray js-dadata-fill" data-type="bank" data-input="data[bik]">Заполнить</button>
            </div>
        </div>
    </div>

    <div class="field">
        <div class="name">р/с</div>
        <div class="value">
            <input type="text" name="data[rs]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['rs'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100">
        </div>
    </div>

    <div class="field">
        <div class="name">к/с</div>
        <div class="value">
            <input type="text" name="data[ks]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['ks'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100">
        </div>
    </div>

    <button class="button gray js-client-contact-add mt25">Добавить контакт</button>
    <div class="js-client-contacts"></div>
    

    <section class="bottombar pb2b-bottombar">
        <div class="article width-100">
            <div class="article-body custom-py-8 flexbox">
                <input type="submit" class="button" value="Сохранить">
                <span class="js-form-message form-message red" style="display: none;">
                    <span class="js-form-message-icons">
                        <i class="fas fa-check-circle" style="display:none;"></i>
                    </span>
                    <span class="js-form-message-text"></span>
                </span>
            </div>
        </div>
    </section>
</form><?php }} ?>