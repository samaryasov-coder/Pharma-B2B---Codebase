<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 17:22:37
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/tabs/accreditation.html" */ ?>
<?php /*%%SmartyHeaderCode:20337035256a89dadda30d62-47476519%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9bfb9aca6131c588d8364ca1d652f7c1b47d7f36' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/tabs/accreditation.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20337035256a89dadda30d62-47476519',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'object' => 0,
    'includes' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89dadda39418_65942590',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89dadda39418_65942590')) {function content_6a89dadda39418_65942590($_smarty_tpl) {?><button class="button js-popup mt25" 
    data-action="?module=company&action=accreditationFilesAdd" 
    data-params="id=<?php echo (($tmp = @$_smarty_tpl->tpl_vars['object']->value['id'])===null||$tmp==='' ? 0 : $tmp);?>
&template_id=<?php echo (($tmp = @$_smarty_tpl->tpl_vars['includes']->value['docflowTemplateAccreditation']['id'])===null||$tmp==='' ? 0 : $tmp);?>
">
    Добавить документ
</button>


<?php if (!empty($_smarty_tpl->tpl_vars['includes']->value['docflowTemplateAccreditation'])&&!empty($_smarty_tpl->tpl_vars['includes']->value['docflowTemplateAccreditation']['items'])){?>
    <table class="zebra mt25">
        <thead>
            <tr>
                <th>Название</th>
                <th>Тип компании</th>
                <th>Комментарий</th>
                <th>Файл</th>
            </tr>
        </thead>
        <tbody>
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['includes']->value['docflowTemplateAccreditation']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                <tr>
                    <td><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['item']->value['name'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</td>
                    <td><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['item']->value['company_type_name'])===null||$tmp==='' ? 'Все типы' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</td>
                    <td><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['item']->value['comment'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</td>
                    <td>
                        <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['file_id'])){?>
                            <a class="nowrap" href="?module=files&action=download&file_id=<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['item']->value['file_id'], ENT_QUOTES, 'UTF-8', true);?>
">
                                Скачать
                            </a>
                        <?php }else{ ?>
                            <span class="hint">Нет файла</span>
                        <?php }?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php }else{ ?>
    <div class="hint">Документы не добавлены</div>  
<?php }?><?php }} ?>