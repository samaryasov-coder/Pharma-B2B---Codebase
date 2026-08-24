<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:44:23
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/files/themes/default/head.html" */ ?>
<?php /*%%SmartyHeaderCode:5705703026a8c82f7ad75c2-06940811%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bf51751605e312c8fcaf772585f2e9f7cd824534' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/files/themes/default/head.html',
      1 => 1540900260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5705703026a8c82f7ad75c2-06940811',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa_active_theme_url' => 0,
    'wa_theme_version' => 0,
    'wa_app_static_url' => 0,
    'wa' => 0,
    'frontend_assets' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c82f7ade030_18237106',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c82f7ade030_18237106')) {function content_6a8c82f7ade030_18237106($_smarty_tpl) {?><!-- css -->
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_active_theme_url']->value;?>
default.files.css?v<?php echo $_smarty_tpl->tpl_vars['wa_theme_version']->value;?>
" rel="stylesheet" type="text/css" />

<!-- js -->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_active_theme_url']->value;?>
default.files.js?v<?php echo $_smarty_tpl->tpl_vars['wa_theme_version']->value;?>
"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/lazy.load.js?v<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"></script>

<!-- plugin hook: 'frontend_assets' -->

<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['frontend_assets']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
    <?php echo $_smarty_tpl->tpl_vars['item']->value;?>

<?php } ?>
<?php }} ?>