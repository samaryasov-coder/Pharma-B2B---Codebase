<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/login/Login.html" */ ?>
<?php /*%%SmartyHeaderCode:10522101506a88a073e35a44-20611546%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '99da48163884bbb58e9af95ec8440ff64180f6dd' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/login/Login.html',
      1 => 1541667600,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10522101506a88a073e35a44-20611546',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'class_id' => 0,
    'wrapper_id' => 0,
    'title' => 0,
    'renderer' => 0,
    'data' => 0,
    'errors' => 0,
    'messages' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a88a073e3cd86_99229494',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a88a073e3cd86_99229494')) {function content_6a88a073e3cd86_99229494($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['class_id'] = new Smarty_variable('wa-backend-login-form', null, 0);?>
<?php $_smarty_tpl->tpl_vars['wrapper_id'] = new Smarty_variable(uniqid($_smarty_tpl->tpl_vars['class_id']->value), null, 0);?>
<div class="<?php echo $_smarty_tpl->tpl_vars['class_id']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['wrapper_id']->value;?>
">
    <?php echo $_smarty_tpl->tpl_vars['renderer']->value->setTitle($_smarty_tpl->tpl_vars['title']->value);?>

    <?php echo $_smarty_tpl->tpl_vars['renderer']->value->render($_smarty_tpl->tpl_vars['data']->value,$_smarty_tpl->tpl_vars['errors']->value,$_smarty_tpl->tpl_vars['messages']->value);?>

</div>
<?php }} ?>