<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/photos/themes/default/my.nav.html" */ ?>
<?php /*%%SmartyHeaderCode:5393395016a889cef7bde01-86406158%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5ace6245c371e0c68cbced650d3549823f1476f4' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/photos/themes/default/my.nav.html',
      1 => 1540900260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5393395016a889cef7bde01-86406158',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'my_app' => 0,
    'wa' => 0,
    'my_nav_selected' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889cef7c1705_84628494',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889cef7c1705_84628494')) {function content_6a889cef7c1705_84628494($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['my_app']->value==$_smarty_tpl->tpl_vars['wa']->value->app()){?>
    <li class="photos <?php if ($_smarty_tpl->tpl_vars['my_nav_selected']->value=='profile'){?>selected<?php }?>">
        <a href="<?php echo $_smarty_tpl->tpl_vars['wa']->value->getUrl('/frontend/my');?>
">Мой профиль</a>
    </li>
<?php }?><?php }} ?>