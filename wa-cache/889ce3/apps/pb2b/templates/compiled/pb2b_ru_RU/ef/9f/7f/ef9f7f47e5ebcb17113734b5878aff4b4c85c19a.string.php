<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:08:35
         compiled from "ef9f7f47e5ebcb17113734b5878aff4b4c85c19a" */ ?>
<?php /*%%SmartyHeaderCode:4973778186a89bb73df8c42-89566257%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ef9f7f47e5ebcb17113734b5878aff4b4c85c19a' => 
    array (
      0 => 'ef9f7f47e5ebcb17113734b5878aff4b4c85c19a',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '4973778186a89bb73df8c42-89566257',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'type' => 0,
    'params' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89bb73dfb9a7_49811091',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89bb73dfb9a7_49811091')) {function content_6a89bb73dfb9a7_49811091($_smarty_tpl) {?><?php if (!empty($_smarty_tpl->tpl_vars['params']->value['config']['tender_types'][$_smarty_tpl->tpl_vars['type']->value]['name'])){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['params']->value['config']['tender_types'][$_smarty_tpl->tpl_vars['type']->value]['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['type']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?>
<?php }} ?>