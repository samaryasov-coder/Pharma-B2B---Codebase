<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:03:28
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/error.html" */ ?>
<?php /*%%SmartyHeaderCode:12458147566a89ba4067c413-94283438%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f08b79853d3fb578b7e980e596d61d292899bc6a' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/error.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12458147566a89ba4067c413-94283438',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error_code' => 0,
    'error_message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89ba40682111_45216859',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89ba40682111_45216859')) {function content_6a89ba40682111_45216859($_smarty_tpl) {?><h1>
	<?php echo (($tmp = @$_smarty_tpl->tpl_vars['error_code']->value)===null||$tmp==='' ? "500" : $tmp);?>

	<?php echo (($tmp = @$_smarty_tpl->tpl_vars['error_message']->value)===null||$tmp==='' ? "Неизвестная ошибка" : $tmp);?>

</h1>

<?php }} ?>