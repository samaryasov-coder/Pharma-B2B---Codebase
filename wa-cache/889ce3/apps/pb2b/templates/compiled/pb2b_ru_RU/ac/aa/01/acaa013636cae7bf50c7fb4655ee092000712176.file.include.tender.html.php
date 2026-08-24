<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:10
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/layouts/include.tender.html" */ ?>
<?php /*%%SmartyHeaderCode:15115108726a889d66b25643-82949236%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'acaa013636cae7bf50c7fb4655ee092000712176' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/layouts/include.tender.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15115108726a889d66b25643-82949236',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d66b274f8_59533543',
  'variables' => 
  array (
    'sidebar_filters' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d66b274f8_59533543')) {function content_6a889d66b274f8_59533543($_smarty_tpl) {?><div class="pb2b-sidebar-block mt0 pt0 pb0">
    <a href="#/tender/edit/" class="button w100"><i class="fas fa-plus"></i> Добавить тендер</a>
</div>
<div class="pb2b-sidebar-block mt5 pt0 pb0">
    <a href="#/tender/" class="button w100"><i class="fas fa-list"></i> Все тендеры</a>
</div>

<?php if (!empty($_smarty_tpl->tpl_vars['sidebar_filters']->value['tender'])){?>
    <div class="pb2b-sidebar-block mt15 pt0 pb0">
        <form class="js-sidebar-filters-form" data-namespace="tender">
            <?php echo $_smarty_tpl->getSubTemplate ("../includes/include.sidebarFilters.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('filters'=>$_smarty_tpl->tpl_vars['sidebar_filters']->value['tender'],'namespace'=>"tender"), 0);?>

        </form>
    </div>
<?php }?>
<?php }} ?>