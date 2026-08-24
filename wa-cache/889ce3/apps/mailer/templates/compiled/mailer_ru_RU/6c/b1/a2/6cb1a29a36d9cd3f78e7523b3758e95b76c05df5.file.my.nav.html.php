<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/mailer/themes/default/my.nav.html" */ ?>
<?php /*%%SmartyHeaderCode:14721199246a889cef79eed7-16478222%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6cb1a29a36d9cd3f78e7523b3758e95b76c05df5' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/mailer/themes/default/my.nav.html',
      1 => 1540900260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14721199246a889cef79eed7-16478222',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'my_nav_selected' => 0,
    'wa' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889cef7a2aa9_26808563',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889cef7a2aa9_26808563')) {function content_6a889cef7a2aa9_26808563($_smarty_tpl) {?>

<li class="mailer <?php if ($_smarty_tpl->tpl_vars['my_nav_selected']->value=='subscriptions'){?>selected<?php }?>">
    <a href="<?php echo $_smarty_tpl->tpl_vars['wa']->value->getUrl('/frontend/mySubscriptions');?>
">Мои подписки</a>
</li>

<?php echo $_smarty_tpl->tpl_vars['wa']->value->globals('isMyAccount',true);?>

<?php }} ?>