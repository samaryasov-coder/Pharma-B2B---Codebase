<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:28
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/themes/default/main.html" */ ?>
<?php /*%%SmartyHeaderCode:749625096a8c8284f360f1-11593484%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a2c9677054f2522ee0092aeb9583a140ac4f9d30' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/themes/default/main.html',
      1 => 1765875363,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '749625096a8c8284f360f1-11593484',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    'wa_app_url' => 0,
    'page' => 0,
    'wa_backend_url' => 0,
    'content' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c8284f3dc79_63203236',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c8284f3dc79_63203236')) {function content_6a8c8284f3dc79_63203236($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['wa']->value->currentUrl()==$_smarty_tpl->tpl_vars['wa_app_url']->value&&(empty($_smarty_tpl->tpl_vars['page']->value['id'])&&empty($_smarty_tpl->tpl_vars['page']->value['content']))){?>

    <div class="welcome">
        <h1>Добро пожаловать на ваш новый сайт!</h1>
        <p><?php echo sprintf('Перейдите в раздел «<a href="%s">Страницы</a>» и создайте страницу с пустым адресом. Здесь вы увидите ее текст.',($_smarty_tpl->tpl_vars['wa_backend_url']->value).('site/#/pages/'));?>
</p>
    </div>

<?php }else{ ?>

    <article class="content with-sidebar" itemscope itemtype="http://schema.org/WebPage">
        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>
 
    </article>

    <?php if (isset($_smarty_tpl->tpl_vars['page']->value)&&(empty($_smarty_tpl->tpl_vars['action']->value)||$_smarty_tpl->tpl_vars['action']->value=='page')){?>
        <div class="content">

            

        </div>
    <?php }?>

<?php }?>
<?php }} ?>