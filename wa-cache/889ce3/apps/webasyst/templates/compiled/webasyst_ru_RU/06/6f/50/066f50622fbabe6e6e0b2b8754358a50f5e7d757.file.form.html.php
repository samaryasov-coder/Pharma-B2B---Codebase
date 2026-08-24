<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/form.html" */ ?>
<?php /*%%SmartyHeaderCode:7608100956a88a073e4db05-26757012%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '066f50622fbabe6e6e0b2b8754358a50f5e7d757' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/form.html',
      1 => 1738158327,
      2 => 'file',
    ),
    '4e41f85a38106b57f138f01095f9c427434a6aa1' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/onetime_password_form.inc.html',
      1 => 1542030180,
      2 => 'file',
    ),
    '8d460abda9d000197cb4a9444a691aea029f345a' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/default_form.inc.html',
      1 => 1736497959,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7608100956a88a073e4db05-26757012',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'webasyst_id_auth_url' => 0,
    'bind_with_webasyst_contact' => 0,
    'webasyst_id_auth_result' => 0,
    'is_onetime_password_auth_type' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a88a073e6a5f3_21380095',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a88a073e6a5f3_21380095')) {function content_6a88a073e6a5f3_21380095($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['webasyst_id_auth_url']->value&&!$_smarty_tpl->tpl_vars['bind_with_webasyst_contact']->value){?>
    <div class="waid-login">
        <a href="<?php echo $_smarty_tpl->tpl_vars['webasyst_id_auth_url']->value;?>
" class="waid-auth-link js-webasyst-auth-link"><span class="icon"></span>&nbsp;Войти с&nbsp;Webasyst ID</a>
        <?php if (!empty($_smarty_tpl->tpl_vars['webasyst_id_auth_result']->value)&&empty($_smarty_tpl->tpl_vars['webasyst_id_auth_result']->value['status'])){?>
            <span class="error" data-code="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['webasyst_id_auth_result']->value['details']['error_code'])===null||$tmp==='' ? '' : $tmp);?>
">
            <?php echo (($tmp = @$_smarty_tpl->tpl_vars['webasyst_id_auth_result']->value['details']['error_message'])===null||$tmp==='' ? _ws('Unknown authorization error. Sign in with a login and a password.') : $tmp);?>

        </span>
        <?php }?>
    </div>
    <div class="waid-login custom-my-20">
        <span class="separator">или</span>
    </div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['is_onetime_password_auth_type']->value){?>
    <?php /*  Call merged included template "./onetime_password_form.inc.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./onetime_password_form.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '7608100956a88a073e4db05-26757012');
content_6a88a073e54dc4_78131069($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./onetime_password_form.inc.html" */?>
<?php }else{ ?>
    <?php /*  Call merged included template "./default_form.inc.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./default_form.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '7608100956a88a073e4db05-26757012');
content_6a88a073e60fa4_83868068($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./default_form.inc.html" */?>
<?php }?>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/onetime_password_form.inc.html" */ ?>
<?php if ($_valid && !is_callable('content_6a88a073e54dc4_78131069')) {function content_6a88a073e54dc4_78131069($_smarty_tpl) {?><div class="wa-login-onetime-password-form">
    <form action="" method="post">

        <?php  $_smarty_tpl->tpl_vars['params'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['params']->_loop = false;
 $_smarty_tpl->tpl_vars['field_id'] = new Smarty_Variable;
 $_from = (($tmp = @$_smarty_tpl->tpl_vars['fields']->value)===null||$tmp==='' ? array() : $tmp); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['params']->key => $_smarty_tpl->tpl_vars['params']->value){
$_smarty_tpl->tpl_vars['params']->_loop = true;
 $_smarty_tpl->tpl_vars['field_id']->value = $_smarty_tpl->tpl_vars['params']->key;
?>
            <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderField($_smarty_tpl->tpl_vars['field_id']->value,$_smarty_tpl->tpl_vars['params']->value);?>

        <?php } ?>

        <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderCaptcha();?>


        <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderRememberMe();?>


        <div class="field wa-buttons">
            <div class="value wa-submit">

                <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderMessages();?>


                <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderUncaughtErrors();?>


                <input type="hidden" name="wa_auth_login" value="1">

                <div class="wa-request-onetime-password-button-wrapper">
                    <button class="button blue wa-request-onetime-password-button" data-href="<?php echo $_smarty_tpl->tpl_vars['onetime_password_url']->value;?>
"><?php if (!empty($_smarty_tpl->tpl_vars['is_api_oauth']->value)){?>Получить код<?php }else{ ?>Получить код подтверждения<?php }?></button>
                    <?php if (!empty($_smarty_tpl->tpl_vars['is_api_oauth']->value)){?>
                        или
                        <a href="javascript:void(0);" class="wa-login-cancel gray underline">отмена</a>
                    <?php }?>
                    <i class="icon16 loading wa-request-onetime-password-button-loading" style="margin-top: -2px; display: none"></i>
                </div>

                <div class="wa-submit-button-wrapper" style="display: none;">
                    <input type="submit" value="Войти" class="button wa-login-submit">
                    <?php if (!empty($_smarty_tpl->tpl_vars['is_api_oauth']->value)){?>
                        или
                        <a href="javascript:void(0);" class="wa-login-cancel gray underline">отмена</a>
                    <?php }?>
                    <i class="icon16 loading wa-loading" style="display: none;"></i>
                </div>

                <?php if ($_smarty_tpl->tpl_vars['forgotpassword_url']->value&&empty($_smarty_tpl->tpl_vars['is_api_oauth']->value)){?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['forgotpassword_url']->value;?>
" class="wa-forgotpassword underline" data-type="forgotpassword">Забыли пароль?</a>
                <?php }?>
            </div>
        </div>

        <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderCsrf();?>


    </form>
</div>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/default_form.inc.html" */ ?>
<?php if ($_valid && !is_callable('content_6a88a073e60fa4_83868068')) {function content_6a88a073e60fa4_83868068($_smarty_tpl) {?><div class="wa-login-default-form">
    <?php if ($_smarty_tpl->tpl_vars['bind_with_webasyst_contact']->value){?>
    <?php echo $_smarty_tpl->getSubTemplate ("./webasyst_contact_info.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    <?php }?>
    <form action="" method="post">

        <?php  $_smarty_tpl->tpl_vars['params'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['params']->_loop = false;
 $_smarty_tpl->tpl_vars['field_id'] = new Smarty_Variable;
 $_from = (($tmp = @$_smarty_tpl->tpl_vars['fields']->value)===null||$tmp==='' ? array() : $tmp); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['params']->key => $_smarty_tpl->tpl_vars['params']->value){
$_smarty_tpl->tpl_vars['params']->_loop = true;
 $_smarty_tpl->tpl_vars['field_id']->value = $_smarty_tpl->tpl_vars['params']->key;
?>
            <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderField($_smarty_tpl->tpl_vars['field_id']->value,$_smarty_tpl->tpl_vars['params']->value);?>

        <?php } ?>

        <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderCaptcha();?>


        <div class="flexbox middle full-width nowrap custom-mt-16">
            <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderRememberMe();?>


            <?php if ($_smarty_tpl->tpl_vars['forgotpassword_url']->value&&empty($_smarty_tpl->tpl_vars['is_api_oauth']->value)){?>
                <a href="<?php echo $_smarty_tpl->tpl_vars['forgotpassword_url']->value;?>
<?php if ($_smarty_tpl->tpl_vars['force_login_form']->value){?>&force_login_form=1<?php }?>" class="wa-forgotpassword underline" data-type="forgotpassword">Забыли пароль?</a>
            <?php }?>
        </div>

        <div class="field wa-buttons">
            <div class="value wa-submit align-center">

                <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderMessages();?>


                <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderUncaughtErrors();?>


                <input type="hidden" name="wa_auth_login" value="1">

                <?php if ($_smarty_tpl->tpl_vars['bind_with_webasyst_contact']->value){?>
                    <div class="wa-login-and-bind-with-webasyst-id-button-wrapper">
                        <input type="submit" value="Войти и подключить Webasyst ID" class="button blue wa-login-submit">
                    </div>
                    <div>
                        <a href="javascript:void(0);" class="js-back-to-simple-login underline">Войти без подключения Webasyst ID</a>
                    </div>
                <?php }else{ ?>
                    <input type="submit" value="Войти" class="wa-login-submit button large">
                    <?php if (!empty($_smarty_tpl->tpl_vars['is_api_oauth']->value)){?>
                        или
                        <a href="javascript:void(0);" class="wa-login-cancel gray underline">отмена</a>
                    <?php }?>
                    <i class="icon size-16 loading wa-loading" style="display: none;"></i>
                <?php }?>

            </div>
        </div>

        <?php echo $_smarty_tpl->tpl_vars['renderer']->value->renderCsrf();?>


    </form>
</div>
<?php }} ?>