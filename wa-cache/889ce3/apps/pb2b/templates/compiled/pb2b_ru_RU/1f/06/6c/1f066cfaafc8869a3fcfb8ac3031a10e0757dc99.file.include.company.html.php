<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:10
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/layouts/include.company.html" */ ?>
<?php /*%%SmartyHeaderCode:6012609346a889d66b28ca2-49646004%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1f066cfaafc8869a3fcfb8ac3031a10e0757dc99' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/layouts/include.company.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6012609346a889d66b28ca2-49646004',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d66b2a690_42559264',
  'variables' => 
  array (
    'sidebar_filters' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d66b2a690_42559264')) {function content_6a889d66b2a690_42559264($_smarty_tpl) {?><div class="pb2b-sidebar-block mt0 pt0 pb0">
    <a href="#/company/edit/" class="button w100"><i class="fas fa-plus"></i> Добавить компанию</a>
    
</div>
<div class="pb2b-sidebar-block mt5 pt0 pb0">
    <a href="#/company/" class="button w100"><i class="fas fa-list"></i> Посмотреть все компании</a>
</div>


<?php if (!empty($_smarty_tpl->tpl_vars['sidebar_filters']->value['company'])){?>
    <div class="pb2b-sidebar-block mt15 pt0 pb0">
        <form class="js-sidebar-filters-form" data-namespace="company">
            <?php echo $_smarty_tpl->getSubTemplate ("../includes/include.sidebarFilters.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('filters'=>$_smarty_tpl->tpl_vars['sidebar_filters']->value['company'],'namespace'=>"company"), 0);?>

        </form>
    </div>
<?php }?>
<?php }} ?>