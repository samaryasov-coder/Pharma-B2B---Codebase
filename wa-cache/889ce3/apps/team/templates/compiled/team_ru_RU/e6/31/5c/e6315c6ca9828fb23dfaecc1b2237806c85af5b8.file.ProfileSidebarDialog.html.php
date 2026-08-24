<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:52
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileSidebarDialog.html" */ ?>
<?php /*%%SmartyHeaderCode:7011113366a889d1c27ac80-93331945%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e6315c6ca9828fb23dfaecc1b2237806c85af5b8' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileSidebarDialog.html',
      1 => 1637225471,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7011113366a889d1c27ac80-93331945',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'options' => 0,
    'wa' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d1c27fad6_64017175',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d1c27fad6_64017175')) {function content_6a889d1c27fad6_64017175($_smarty_tpl) {?><?php if (empty($_smarty_tpl->tpl_vars['options']->value['sectionId'])||empty($_smarty_tpl->tpl_vars['options']->value['userId'])){?>
    <p class="text-red">Отсутствует обязательный параметр.</p>
<?php }elseif($_smarty_tpl->tpl_vars['options']->value['sectionId']!=='calendar'){?>
    <?php echo $_smarty_tpl->tpl_vars['wa']->value->contactProfileSidebar($_smarty_tpl->tpl_vars['options']->value['userId'],array('active_section'=>$_smarty_tpl->tpl_vars['options']->value['sectionId']));?>

<?php }?>

<?php }} ?>