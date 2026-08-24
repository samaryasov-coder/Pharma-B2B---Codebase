<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:08:56
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/settings/SettingsPage.html" */ ?>
<?php /*%%SmartyHeaderCode:8213309346a89bb885b03c9-23707210%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8ba2ed1833c2607a2bbc99873a0947f6dd9dd328' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/settings/SettingsPage.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8213309346a89bb885b03c9-23707210',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page' => 0,
    'f' => 0,
    'has_save_fields' => 0,
    'type' => 0,
    'code' => 0,
    'values' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89bb885d3e05_98065107',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89bb885d3e05_98065107')) {function content_6a89bb885d3e05_98065107($_smarty_tpl) {?><h2 class="mb16"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['page']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</h2><?php if (empty($_smarty_tpl->tpl_vars['page']->value['fields'])){?><p class="hint">В этом разделе пока нет настроек.</p><?php }else{ ?><?php $_smarty_tpl->tpl_vars['has_save_fields'] = new Smarty_variable(0, null, 0);?><?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['page']->value['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['f']->key;
?><?php if ((($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['tag'])===null||$tmp==='' ? 'input' : $tmp)=='input'||(($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['tag'])===null||$tmp==='' ? 'input' : $tmp)=='textarea'){?><?php $_smarty_tpl->tpl_vars['has_save_fields'] = new Smarty_variable(1, null, 0);?><?php }?><?php } ?><?php if ($_smarty_tpl->tpl_vars['has_save_fields']->value){?><form class="js-form form fields <?php if ($_smarty_tpl->tpl_vars['page']->value['form_vertical']){?> vertical<?php }?>" action="?module=settings&action=save"><input type="hidden" name="type" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['type']->value, ENT_QUOTES, 'UTF-8', true);?>
"><?php }?><div class="fields vertical"><?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['page']->value['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['f']->key;
?><div class="field"><?php if (!empty($_smarty_tpl->tpl_vars['f']->value['name'])){?><div class="name"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</div><?php }?><?php if ((($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['tag'])===null||$tmp==='' ? 'input' : $tmp)=='input'){?><div class="value<?php if ((($tmp = @$_smarty_tpl->tpl_vars['f']->value['required'])===null||$tmp==='' ? 0 : $tmp)){?> required<?php }?>"><input class="w100"type="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['type'])===null||$tmp==='' ? 'text' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
"name="settings[<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['code']->value, ENT_QUOTES, 'UTF-8', true);?>
]"<?php if ((($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['type'])===null||$tmp==='' ? 'text' : $tmp)=='checkbox'){?>value="1" <?php if ((($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['values']->value[$_smarty_tpl->tpl_vars['code']->value])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['f']->value['default'] : $tmp))===null||$tmp==='' ? '' : $tmp)){?>checked<?php }?><?php }else{ ?>value="<?php echo htmlspecialchars((string)(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['values']->value[$_smarty_tpl->tpl_vars['code']->value])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['f']->value['default'] : $tmp))===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>><?php if (!empty($_smarty_tpl->tpl_vars['f']->value['hint'])){?><div class="hint"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['hint'], ENT_QUOTES, 'UTF-8', true);?>
</div><?php }?></div><?php }elseif((($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['tag'])===null||$tmp==='' ? 'input' : $tmp)=='textarea'){?><div class="value<?php if ((($tmp = @$_smarty_tpl->tpl_vars['f']->value['required'])===null||$tmp==='' ? 0 : $tmp)){?> required<?php }?>"><textarea class="w100" name="settings[<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['code']->value, ENT_QUOTES, 'UTF-8', true);?>
]"><?php echo htmlspecialchars((string)(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['values']->value[$_smarty_tpl->tpl_vars['code']->value])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['f']->value['default'] : $tmp))===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</textarea><?php if (!empty($_smarty_tpl->tpl_vars['f']->value['hint'])){?><div class="hint"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['hint'], ENT_QUOTES, 'UTF-8', true);?>
</div><?php }?></div><?php }elseif((($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['tag'])===null||$tmp==='' ? 'input' : $tmp)=='button'){?><div class="value"><button type="button"class="button<?php if (!empty($_smarty_tpl->tpl_vars['f']->value['field']['class'])){?> <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['field']['class'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"<?php if (!empty($_smarty_tpl->tpl_vars['f']->value['field']['action'])){?>data-action="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['field']['action'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['f']->value['field']['params'])){?>data-params="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['field']['params'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['text'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['f']->value['name'] : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</button><?php if (!empty($_smarty_tpl->tpl_vars['f']->value['hint'])){?><div class="hint"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['hint'], ENT_QUOTES, 'UTF-8', true);?>
</div><?php }?></div><?php }elseif((($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['tag'])===null||$tmp==='' ? 'input' : $tmp)=='fileset_files'){?><div class="value"><div class="js-settings-fileset-table"<?php if (!empty($_smarty_tpl->tpl_vars['f']->value['field']['get_action'])){?>data-get-action="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['field']['get_action'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['f']->value['field']['delete_action'])){?>data-delete-action="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['field']['delete_action'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['f']->value['field']['get_params'])){?>data-get-params="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['field']['get_params'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['f']->value['field']['delete_params'])){?>data-delete-params="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['field']['delete_params'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>data-delete-id-param="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['delete_id_param'])===null||$tmp==='' ? 'file_id' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
"data-empty-text="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['empty_text'])===null||$tmp==='' ? 'Нет данных' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
"><table class="zebra"><thead><tr><th>Название</th><th>Тип</th><th>Комментарий</th><th>Файл</th><th></th></tr></thead><tbody class="js-settings-fileset-table-body"><tr><td colspan="5" class="hint">Загрузка...</td></tr></tbody></table></div><?php if (!empty($_smarty_tpl->tpl_vars['f']->value['hint'])){?><div class="hint"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f']->value['hint'], ENT_QUOTES, 'UTF-8', true);?>
</div><?php }?></div><?php }else{ ?><div class="value"><p class="hint">Неподдерживаемый тип поля: <?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['f']->value['field']['tag'])===null||$tmp==='' ? 'input' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</p></div><?php }?></div><?php } ?></div><?php if ($_smarty_tpl->tpl_vars['has_save_fields']->value){?><section class="bottombar pb2b-bottombar"><div class="article width-100"><div class="article-body custom-py-8 flexbox"><input type="submit" class="button" value="Сохранить"><div class="js-form-message form-message" style="display:none;"><span class="js-form-message-text"></span></div></div></div></section></form><?php }?><script>(function() {function escHtml(str) {return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');}function parseQueryParams(raw) {var out = {};var str = String(raw || '').trim();if (!str) {return out;}$.each(str.split('&'), function(i, part) {if (!part) {return;}var p = part.split('=');var key = decodeURIComponent((p[0] || '').replace(/\+/g, ' '));if (!key) {return;}var value = decodeURIComponent((p.slice(1).join('=') || '').replace(/\+/g, ' '));out[key] = value;});return out;}function renderFilesetRows($box, items) {var $body = $box.find('.js-settings-fileset-table-body');var emptyText = $box.data('empty-text') || 'Нет данных';if (!items || !items.length) {$body.html('<tr><td colspan="5" class="hint">' + escHtml(emptyText) + '</td></tr>');return;}var html = '';$.each(items, function(i, item) {var name = escHtml(item.name || '');var type = escHtml(item.type || '');var comment = escHtml(item.comment || '');var fileCell = '';if (item.download_url) {fileCell = '<a href="' + escHtml(item.download_url) + '">Скачать</a>';if (item.file_name) {fileCell += ' <span class="hint">' + escHtml(item.file_name) + '</span>';}} else {fileCell = '<span class="hint">Нет файла</span>';}var rowId = parseInt(item.id || item.file_id || 0, 10) || 0;html += '<tr>' +'<td>' + name + '</td>' +'<td>' + type + '</td>' +'<td>' + comment + '</td>' +'<td>' + fileCell + '</td>' +'<td><button type="button" class="button light-gray smallest js-settings-fileset-delete" data-id="' + rowId + '">Удалить</button></td>' +'</tr>';});$body.html(html);}function renderFilesetError($box, message) {var $body = $box.find('.js-settings-fileset-table-body');$body.html('<tr><td colspan="5" class="hint">' + escHtml(message || 'Ошибка загрузки') + '</td></tr>');}function loadFilesetTable($box) {var action = $box.data('get-action') || '';if (!action) {renderFilesetError($box, 'Не задан get_action');return;}var postData = parseQueryParams($box.data('get-params') || '');$.post(action, postData, function(r) {var d = (r && r.status === 'ok' && r.data) ? r.data : null;if (!d || d.error) {renderFilesetError($box, (d && d.message) ? d.message : 'Ошибка ответа сервера');return;}renderFilesetRows($box, d.items || []);}, 'json').fail(function() {renderFilesetError($box, 'Ошибка запроса');});}$('.js-settings-fileset-table').each(function() {loadFilesetTable($(this));});$(document).off('click.pb2bSettingsFilesetDelete').on('click.pb2bSettingsFilesetDelete', '.js-settings-fileset-delete', function() {if (!confirm('Удалить файл?')) return;var $btn = $(this);var $box = $btn.closest('.js-settings-fileset-table');var action = $box.data('delete-action') || '';if (!action) {alert('Не задан delete_action');return;}var id = parseInt($btn.data('id'), 10) || 0;if (!id) {alert('Не передан id файла');return;}var idParam = $box.data('delete-id-param') || 'file_id';var postData = parseQueryParams($box.data('delete-params') || '');postData[idParam] = id;$.post(action, postData, function(r) {var d = (r && r.status === 'ok' && r.data) ? r.data : null;if (!d || d.error) {alert((d && d.message) ? d.message : 'Ошибка');return;}loadFilesetTable($box);}, 'json').fail(function() {alert('Ошибка запроса');});});})();</script><?php }?>
<?php }} ?>