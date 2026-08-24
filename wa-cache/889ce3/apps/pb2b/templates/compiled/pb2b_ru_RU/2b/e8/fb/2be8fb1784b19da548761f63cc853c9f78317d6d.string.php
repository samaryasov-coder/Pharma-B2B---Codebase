<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:08:35
         compiled from "2be8fb1784b19da548761f63cc853c9f78317d6d" */ ?>
<?php /*%%SmartyHeaderCode:14889613576a89bb73dfe241-59869615%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2be8fb1784b19da548761f63cc853c9f78317d6d' => 
    array (
      0 => '2be8fb1784b19da548761f63cc853c9f78317d6d',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '14889613576a89bb73dfe241-59869615',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'status' => 0,
    'params' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89bb73e001f8_39391959',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89bb73e001f8_39391959')) {function content_6a89bb73e001f8_39391959($_smarty_tpl) {?><?php if (!empty($_smarty_tpl->tpl_vars['params']->value['config']['tender_statuses'][$_smarty_tpl->tpl_vars['status']->value]['name'])){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['params']->value['config']['tender_statuses'][$_smarty_tpl->tpl_vars['status']->value]['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['status']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?>
<?php }} ?>