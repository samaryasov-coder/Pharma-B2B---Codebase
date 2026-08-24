<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:08:41
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/Company.html" */ ?>
<?php /*%%SmartyHeaderCode:18091154886a89bb79384184-06705363%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e0550620ea61633fb32531d9947cd4b5b446842c' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/Company.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18091154886a89bb79384184-06705363',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'hash' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89bb79386bb5_36507525',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89bb79386bb5_36507525')) {function content_6a89bb79386bb5_36507525($_smarty_tpl) {?><h1>Компании</h1>
<table class="js-data-table zebra" data-action="?module=company&action=list" data-hash="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['hash']->value)===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
    <thead>
    <tr>
        <th>Название</th>
    </tr>
    </thead>
    <tbody></tbody>
</table><?php }} ?>