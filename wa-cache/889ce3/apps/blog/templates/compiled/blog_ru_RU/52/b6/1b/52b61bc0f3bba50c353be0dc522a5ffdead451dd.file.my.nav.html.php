<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/blog/themes/default/my.nav.html" */ ?>
<?php /*%%SmartyHeaderCode:6348617086a889cef75f8c1-20820212%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '52b61bc0f3bba50c353be0dc522a5ffdead451dd' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/blog/themes/default/my.nav.html',
      1 => 1540900260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6348617086a889cef75f8c1-20820212',
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
  'unifunc' => 'content_6a889cef764291_86303902',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889cef764291_86303902')) {function content_6a889cef764291_86303902($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['my_app']->value==$_smarty_tpl->tpl_vars['wa']->value->app()){?>
    <li class="blog <?php if ($_smarty_tpl->tpl_vars['my_nav_selected']->value=='profile'){?>selected<?php }?>">
        <a href="<?php echo $_smarty_tpl->tpl_vars['wa']->value->getUrl('/frontend/my');?>
">Мой профиль</a>
    </li>
<?php }?>
<?php }} ?>