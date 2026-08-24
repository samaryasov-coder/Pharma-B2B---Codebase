<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 17:22:37
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/tabs/tenders.html" */ ?>
<?php /*%%SmartyHeaderCode:11034530476a89dadda3c2b1-12104146%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '14f2400e875b3d34d9039c5bc1f20b44f13abedb' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/tabs/tenders.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11034530476a89dadda3c2b1-12104146',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'object' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89dadda3e3b5_31871515',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89dadda3e3b5_31871515')) {function content_6a89dadda3e3b5_31871515($_smarty_tpl) {?><p class="hint">Тендеры, где компания указана организатором.</p>

<table class="js-data-table zebra" data-action="?module=tender&amp;action=list" data-hash="organizer_company_id=<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['object']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
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