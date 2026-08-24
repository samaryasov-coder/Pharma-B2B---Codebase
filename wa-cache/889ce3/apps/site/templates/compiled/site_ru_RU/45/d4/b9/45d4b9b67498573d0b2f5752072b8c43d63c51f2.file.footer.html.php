<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:28
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/themes/default/footer.html" */ ?>
<?php /*%%SmartyHeaderCode:5826312996a8c8284f40661-94569125%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '45d4b9b67498573d0b2f5752072b8c43d63c51f2' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/themes/default/footer.html',
      1 => 1765875363,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5826312996a8c8284f40661-94569125',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa_url' => 0,
    'wa' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c8285001287_40120560',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c8285001287_40120560')) {function content_6a8c8285001287_40120560($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_wa_datetime')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.wa_datetime.php';
?><div class="copyright small">
    &copy; <?php echo smarty_modifier_wa_datetime(time(),"Y");?>

    <a href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['wa']->value->accountName();?>
</a>
</div>
<?php }} ?>