<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:08:35
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/tender/Tender.html" */ ?>
<?php /*%%SmartyHeaderCode:6223354476a89bb73af94c3-34785997%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1d741d2e1f5a0d1b2879d61c8de4c144bebf25f1' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/tender/Tender.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6223354476a89bb73af94c3-34785997',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'hash' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89bb73afdb85_55720365',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89bb73afdb85_55720365')) {function content_6a89bb73afdb85_55720365($_smarty_tpl) {?><h1>Тендеры</h1>
<table class="js-data-table zebra" data-action="?module=tender&amp;action=list" data-hash="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['hash']->value)===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
    <thead>
    <tr>
        <th>Номер</th>
        <th>Наименование</th>
        <th>Тип</th>
        <th>Статус</th>
        <th>Организатор (компания)</th>
        <th>Создан</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>
<?php }} ?>