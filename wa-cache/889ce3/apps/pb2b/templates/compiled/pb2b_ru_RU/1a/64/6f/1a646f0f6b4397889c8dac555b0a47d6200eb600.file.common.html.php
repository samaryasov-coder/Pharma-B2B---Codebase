<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 17:22:37
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/tabs/common.html" */ ?>
<?php /*%%SmartyHeaderCode:2320034286a89dadd9c2086-27992876%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a646f0f6b4397889c8dac555b0a47d6200eb600' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/tabs/common.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2320034286a89dadd9c2086-27992876',
  'function' => 
  array (
  ),
  'unifunc' => 'content_6a89dadd9e4797_43285758',
  'variables' => 
  array (
    'object' => 0,
    'company_types' => 0,
    'company_type' => 0,
    'types_organization' => 0,
    'type_organization' => 0,
    'reserve_email' => 0,
    'reserve_phone' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89dadd9e4797_43285758')) {function content_6a89dadd9e4797_43285758($_smarty_tpl) {?><form class="js-form form fields" action="?module=company&action=save">
    <?php if (!empty($_smarty_tpl->tpl_vars['object']->value['id'])){?>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['object']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
">
    <?php }?>
    <div class="field">
        <div class="name">Название</div>
        <div class="value">
            <input type="text" name="data[name]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['name'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
        </div>
    </div>

    <div class="field">
        <div class="name">Тип компании</div>
        <div class="value">
            <div class="wa-select w100">
                <select name="data[company_type]" class="w100 js-company-type-select">
                    <option value="">Выберите тип</option>
                    <?php  $_smarty_tpl->tpl_vars['company_type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['company_type']->_loop = false;
 $_from = (($tmp = @$_smarty_tpl->tpl_vars['company_types']->value)===null||$tmp==='' ? array() : $tmp); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['company_type']->key => $_smarty_tpl->tpl_vars['company_type']->value){
$_smarty_tpl->tpl_vars['company_type']->_loop = true;
?>
                        <option value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['company_type']->value['id'])===null||$tmp==='' ? 0 : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" <?php if ($_smarty_tpl->tpl_vars['company_type']->value['id']==(($tmp = @$_smarty_tpl->tpl_vars['object']->value['company_type'])===null||$tmp==='' ? 0 : $tmp)){?>selected<?php }?>>
                            <?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['company_type']->value['name'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>

                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>

    <div class="field js-type-organization-field"<?php if ((($tmp = @$_smarty_tpl->tpl_vars['object']->value['company_type'])===null||$tmp==='' ? 0 : $tmp)!=1){?> style="display:none;"<?php }?>>
        <div class="name">Тип организации</div>
        <div class="value">
            <div class="wa-select w100">
                <select name="data[type_organization]" class="w100 js-type-organization-select"<?php if ((($tmp = @$_smarty_tpl->tpl_vars['object']->value['company_type'])===null||$tmp==='' ? 0 : $tmp)!=1){?> disabled<?php }?>>
                    <option value="">Выберите тип организации</option>
                    <?php  $_smarty_tpl->tpl_vars['type_organization'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type_organization']->_loop = false;
 $_from = (($tmp = @$_smarty_tpl->tpl_vars['types_organization']->value)===null||$tmp==='' ? array() : $tmp); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type_organization']->key => $_smarty_tpl->tpl_vars['type_organization']->value){
$_smarty_tpl->tpl_vars['type_organization']->_loop = true;
?>
                        <option value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['type_organization']->value['id'])===null||$tmp==='' ? 0 : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" <?php if ($_smarty_tpl->tpl_vars['type_organization']->value['id']==(($tmp = @$_smarty_tpl->tpl_vars['object']->value['type_organization'])===null||$tmp==='' ? 0 : $tmp)){?>selected<?php }?>>
                            <?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['type_organization']->value['name'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>

                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    
    <div class="field">
        <div class="name">Тип</div>
        <div class="value">

            <div class="">
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
            <input type="text" name="data[inn]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['inn'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
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

    <?php if (!empty($_smarty_tpl->tpl_vars['object']->value['id'])){?>
        <div class="field">
            <div class="name">Банк</div>
            <div class="value">
                <input type="text" name="data[bank]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['bank'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
            </div>
        </div>

        <div class="field">
            <div class="name">БИК</div>
            <div class="value">
                <input type="text" name="data[bik]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['bik'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
            </div>
        </div>

        <div class="field">
            <div class="name">Расчётный счёт</div>
            <div class="value">
                <input type="text" name="data[rs]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['rs'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
            </div>
        </div>

        <div class="field">
            <div class="name">Корреспондентский счёт</div>
            <div class="value">
                <input type="text" name="data[ks]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['ks'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
            </div>
        </div>

        <div class="field">
            <div class="name">Основной E-mail организации</div>
            <div class="value">
                <input type="email" name="data[registry_email]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['registry_email'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
            </div>
        </div>

        <?php if (isset($_smarty_tpl->tpl_vars['object']->value['reserve_emails'])){?>
            <?php  $_smarty_tpl->tpl_vars['reserve_email'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['reserve_email']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['object']->value['reserve_emails']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['reserve_email']->key => $_smarty_tpl->tpl_vars['reserve_email']->value){
$_smarty_tpl->tpl_vars['reserve_email']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['reserve_email']->key;
?>
                <div class="field">
                    <div class="name">Дополнительный E-mail</div>
                    <div class="value">
                        <input type="text" name="data[reserve_emails][]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['reserve_email']->value)===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
                    </div>
                </div>
            <?php } ?>
        <?php }?>
    
        <div class="field js-add-reserve-email-row">
            <div class="name"></div>
            <div class="value">
                <button type="button" class="button small light-gray js-add-reserve-email">Добавить резервный номер телефона</button>
            </div>
        </div>

        <div class="field">
            <div class="name">Номер телефона</div>
            <div class="value">
                <input type="text" name="data[phone]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['phone'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
            </div>
        </div>
        
        <?php if (isset($_smarty_tpl->tpl_vars['object']->value['reserve_phones'])){?>
            <?php  $_smarty_tpl->tpl_vars['reserve_phone'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['reserve_phone']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['object']->value['reserve_phones']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['reserve_phone']->key => $_smarty_tpl->tpl_vars['reserve_phone']->value){
$_smarty_tpl->tpl_vars['reserve_phone']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['reserve_phone']->key;
?>
                <div class="field">
                    <div class="name">Дополнительный телефон</div>
                    <div class="value">
                        <input type="text" name="data[reserve_phones][]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['reserve_phone']->value)===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
                    </div>
                </div>
            <?php } ?>
        <?php }?>
    
        <div class="field js-add-reserve-phone-row">
            <div class="name"></div>
            <div class="value">
                <button type="button" class="button small light-gray js-add-reserve-phone">Добавить резервный номер телефона</button>
            </div>
        </div>
    
        <div class="field">
            <div class="name">Контактное лицо</div>
            <div class="value">
                <input type="text" name="data[contact_person]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['contact_person'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
            </div>
        </div>
    
        <div class="field">
            <div class="name">Веб-сайт</div>
            <div class="value">
                <input type="url" name="data[site]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['object']->value['site'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="w100"><br>
            </div>
        </div>
    <?php }?>

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
</form>
<?php }} ?>