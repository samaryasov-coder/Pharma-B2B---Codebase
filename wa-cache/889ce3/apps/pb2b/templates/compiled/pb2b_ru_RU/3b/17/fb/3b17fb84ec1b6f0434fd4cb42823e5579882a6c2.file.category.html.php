<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 17:22:37
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/tabs/category.html" */ ?>
<?php /*%%SmartyHeaderCode:2581548736a89dadda258e1-71852590%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3b17fb84ec1b6f0434fd4cb42823e5579882a6c2' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/company/tabs/category.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2581548736a89dadda258e1-71852590',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'object' => 0,
    'includes' => 0,
    'cat' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89dadda2e003_74283715',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89dadda2e003_74283715')) {function content_6a89dadda2e003_74283715($_smarty_tpl) {?><button class="button js-popup mt25" data-action="?module=company&action=categorySelect" data-params="id=<?php echo (($tmp = @$_smarty_tpl->tpl_vars['object']->value['id'])===null||$tmp==='' ? 0 : $tmp);?>
">Изменить</button>
<?php if (!empty($_smarty_tpl->tpl_vars['includes']->value['categories'])){?>
    <div class="mt25">
        <table class="zebra js-company-categories-table" style="width:100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Путь</th>
                    <!-- <th></th> -->
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_smarty_tpl->tpl_vars['includes']->value['categories'])){?>
                    <?php  $_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['includes']->value['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->key => $_smarty_tpl->tpl_vars['cat']->value){
$_smarty_tpl->tpl_vars['cat']->_loop = true;
?>
                        <tr data-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cat']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
">
                            <td><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cat']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
</td>
                            <td><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cat']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</td>
                            <td><?php if (!empty($_smarty_tpl->tpl_vars['cat']->value['full_url'])){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cat']->value['full_url'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?>-<?php }?></td>
                            <!-- <td>
                                <span class="js-company-category-remove cp"
                                    data-company-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['object']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
"
                                    data-category-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cat']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </td> -->
                        </tr>
                    <?php } ?>
                <?php }?>
            </tbody>
        </table>
    </div>
<?php }else{ ?>
    <div class="hint mt10">Категории не выбраны</div>
<?php }?><?php }} ?>