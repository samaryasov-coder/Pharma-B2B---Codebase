<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:08:39
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/client/Client.html" */ ?>
<?php /*%%SmartyHeaderCode:8389025686a89bb77b15fd1-97132538%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c7b3510270faf2dcc79242de5f91fcc3d138f4ed' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/client/Client.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8389025686a89bb77b15fd1-97132538',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'hash' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89bb77b18b60_55825731',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89bb77b18b60_55825731')) {function content_6a89bb77b18b60_55825731($_smarty_tpl) {?><h1>Клиенты</h1>
<table class="js-data-table zebra" data-action="?module=client&action=list" data-hash="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['hash']->value)===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
    <thead>
    <tr>
        <th>Название</th>
    </tr>
    </thead>
    <tbody></tbody>
</table><?php }} ?>