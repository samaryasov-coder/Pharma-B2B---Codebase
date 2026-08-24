<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:28
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/themes/default/page.html" */ ?>
<?php /*%%SmartyHeaderCode:17973873006a8c8284cfdf02-43231129%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6c511fad5a8a21d8a8f481bbe7250277fd545b29' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/themes/default/page.html',
      1 => 1678097371,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17973873006a8c8284cfdf02-43231129',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'breadcrumbs' => 0,
    'breadcrumb' => 0,
    'page' => 0,
    'wa' => 0,
    'subpages' => 0,
    'p' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c8284d07a11_30168500',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c8284d07a11_30168500')) {function content_6a8c8284d07a11_30168500($_smarty_tpl) {?><?php if (!empty($_smarty_tpl->tpl_vars['breadcrumbs']->value)){?>
    <div class="breadcrumbs" itemprop="breadcrumb">
        <?php  $_smarty_tpl->tpl_vars['breadcrumb'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['breadcrumb']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['breadcrumbs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['breadcrumb']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['breadcrumb']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['breadcrumb']->key => $_smarty_tpl->tpl_vars['breadcrumb']->value){
$_smarty_tpl->tpl_vars['breadcrumb']->_loop = true;
 $_smarty_tpl->tpl_vars['breadcrumb']->iteration++;
 $_smarty_tpl->tpl_vars['breadcrumb']->last = $_smarty_tpl->tpl_vars['breadcrumb']->iteration === $_smarty_tpl->tpl_vars['breadcrumb']->total;
?>
            <a href="<?php echo $_smarty_tpl->tpl_vars['breadcrumb']->value['url'];?>
"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['breadcrumb']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</a>
            <?php if (!$_smarty_tpl->tpl_vars['breadcrumb']->last){?>
                <span class="chevron right"></span>
            <?php }?>
        <?php } ?>
    </div>
<?php }?>

<h1 itemprop="name"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['page']->value['name'])===null||$tmp==='' ? '(no name)' : $tmp);?>
</h1>

<?php $_smarty_tpl->tpl_vars['subpages'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->site->pages((($tmp = @$_smarty_tpl->tpl_vars['page']->value['id'])===null||$tmp==='' ? null : $tmp)), null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['subpages']->value){?>
    <ul class="sub-links wa-flex wa-flex-wrap">
        <?php  $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['p']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['subpages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['p']->key => $_smarty_tpl->tpl_vars['p']->value){
$_smarty_tpl->tpl_vars['p']->_loop = true;
?>
            <li><a class="button bg-gray" href="<?php echo $_smarty_tpl->tpl_vars['p']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['p']->value['name'];?>
</a></li>
        <?php } ?>
    </ul>
<?php }?>

<div id="page" role="main" itemprop="description">
    <?php echo $_smarty_tpl->tpl_vars['page']->value['content'];?>

</div>
<?php }} ?>