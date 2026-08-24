<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:03:30
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/auth/layout.html" */ ?>
<?php /*%%SmartyHeaderCode:9171748476a89ba42302c57-23839799%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '242b3dbf23d6e5c331abb50dbf059cc752cbbb57' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/auth/layout.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9171748476a89ba42302c57-23839799',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa_theme_url' => 0,
    'wa_active_theme_path' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89ba42306a28_66792503',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89ba42306a28_66792503')) {function content_6a89ba42306a28_66792503($_smarty_tpl) {?><div class="background_icon background_icon__auth-top-left">
    <svg width="138" height="150">
        <use xlink:href="#icon-chemical"></use>
    </svg>
</div>

<div class="background_icon background_icon__auth-bottom-left">
    <svg width="156" height="257">
        <use xlink:href="#icon-chemical"></use>
    </svg>
</div>

<div class="background_icon background_icon__auth-center-right">
    <svg width="250" height="270" viewBox="0 0 160 150">
        <use xlink:href="#icon-chemical"></use>
    </svg>
</div>

<div class="auth">
    <img src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
img/icons/favicon.svg" alt="PharmaB2B">
    <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['wa_active_theme_path']->value)."/".((string)$_smarty_tpl->tpl_vars['auth_path']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</div>

<style>
    body {
        height: 100%;
    }
</style><?php }} ?>