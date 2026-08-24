<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/remember_me.html" */ ?>
<?php /*%%SmartyHeaderCode:1733146956a88a073eba432-83644533%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '848acd72683c395bf2a6be98d1e7433c03f44d84' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/remember_me.html',
      1 => 1541778420,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1733146956a88a073eba432-83644533',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'is_api_oauth' => 0,
    'is_onetime_password_auth_type' => 0,
    'input_name' => 0,
    'checked' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a88a073ebeac2_45688215',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a88a073ebeac2_45688215')) {function content_6a88a073ebeac2_45688215($_smarty_tpl) {?><?php if (empty($_smarty_tpl->tpl_vars['is_api_oauth']->value)){?>
<div class="field field-remember-me"<?php if ($_smarty_tpl->tpl_vars['is_onetime_password_auth_type']->value){?> style="display:none;"<?php }?>>
    <div class="value">
        <label>
            <input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['input_name']->value;?>
" value="0">
            <input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['input_name']->value;?>
" value="1" <?php if ($_smarty_tpl->tpl_vars['checked']->value){?>checked="checked"<?php }?>> Запомнить меня
        </label>
    </div>
</div>
<?php }?>
<?php }} ?>