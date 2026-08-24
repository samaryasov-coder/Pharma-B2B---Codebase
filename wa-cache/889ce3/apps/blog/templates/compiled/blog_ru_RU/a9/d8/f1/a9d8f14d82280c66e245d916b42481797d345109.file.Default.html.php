<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:41:49
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/blog/templates/layouts/Default.html" */ ?>
<?php /*%%SmartyHeaderCode:2159589556a8c825db15ee5-19709877%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a9d8f14d82280c66e245d916b42481797d345109' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/blog/templates/layouts/Default.html',
      1 => 1751532629,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2159589556a8c825db15ee5-19709877',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    'title' => 0,
    'wa_app_static_url' => 0,
    'wa_url' => 0,
    'backend_assets' => 0,
    'item' => 0,
    'rights' => 0,
    'module' => 0,
    'action' => 0,
    'sidebar' => 0,
    '_is_flexbox' => 0,
    '_has_wrapper' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c825db25414_29633101',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c825db25414_29633101')) {function content_6a8c825db25414_29633101($_smarty_tpl) {?><?php if (!is_callable('smarty_block_wa_js')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/block.wa_js.php';
?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <?php $_smarty_tpl->tpl_vars['title'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->title(), null, 0);?>
  <title><?php if ($_smarty_tpl->tpl_vars['title']->value){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['title']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['wa']->value->appName();?>
<?php }?> &mdash; <?php echo $_smarty_tpl->tpl_vars['wa']->value->accountName();?>
</title>
  <?php echo $_smarty_tpl->tpl_vars['wa']->value->css();?>

  <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/blog.css?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
">

  <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery/jquery-3.6.0.min.js" type="text/javascript"></script>
  <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery/jquery-migrate-3.3.2.min.js"></script>
  <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.js?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script>

  <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.json.js"></script>
  <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.store.js"></script>
  <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.history.js"></script>
  <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.tmpl.min.js"></script>

  <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/contentRouter.js"></script>

  <?php $_smarty_tpl->smarty->_tag_stack[] = array('wa_js', array('file'=>"js/blog.min.js")); $_block_repeat=true; echo smarty_block_wa_js(array('file'=>"js/blog.min.js"), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

  <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/jquery.sticky.js
  <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/jquery.pageless2.js
  <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/blog.js
  <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/blogComments.js
  <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/jquery.form.js
  <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_wa_js(array('file'=>"js/blog.min.js"), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

  <?php echo $_smarty_tpl->tpl_vars['wa']->value->js(false);?>


  
  <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_assets']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
      <?php echo $_smarty_tpl->tpl_vars['item']->value;?>

  <?php } ?>

  <script>
  $.wa_blog = $.extend(true,$.wa_blog,{
    'rights':<?php echo json_encode($_smarty_tpl->tpl_vars['rights']->value);?>

  });
  </script>
</head>
<?php $_smarty_tpl->tpl_vars['_has_wrapper'] = new Smarty_variable(true, null, 0);?>
<?php if (($_smarty_tpl->tpl_vars['module']->value=='blog'&&$_smarty_tpl->tpl_vars['action']->value=='settings')||($_smarty_tpl->tpl_vars['module']->value=='post'&&$_smarty_tpl->tpl_vars['action']->value=='edit')||$_smarty_tpl->tpl_vars['module']->value=='plugins'||$_smarty_tpl->tpl_vars['module']->value=='pages'||$_smarty_tpl->tpl_vars['module']->value=='design'||$_smarty_tpl->tpl_vars['action']->value=='calendar'){?>
  <?php $_smarty_tpl->tpl_vars['_has_wrapper'] = new Smarty_variable(false, null, 0);?>
<?php }?>
<body>
  <div id="wa">
    <?php echo $_smarty_tpl->tpl_vars['wa']->value->header();?>

    <div id="wa-app" class="flexbox wrap-mobile">
      <?php echo $_smarty_tpl->tpl_vars['sidebar']->value;?>

      <?php $_smarty_tpl->tpl_vars['_is_flexbox'] = new Smarty_variable($_smarty_tpl->tpl_vars['module']->value=='pages'||($_smarty_tpl->tpl_vars['module']->value=='post'&&$_smarty_tpl->tpl_vars['action']->value=='edit'), null, 0);?>
      <main class="<?php if ($_smarty_tpl->tpl_vars['_is_flexbox']->value){?>flexbox <?php }?>content s-hide-scrollbar not-blank js-main-content">
        <?php if ($_smarty_tpl->tpl_vars['_has_wrapper']->value){?>
          <div class="article wide"><div class="article-body">
        <?php }?>
            <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

        <?php if ($_smarty_tpl->tpl_vars['_has_wrapper']->value){?>
          </div></div>
        <?php }?>
      </main>
    </div>
  </div><!-- #wa -->
</body>
</html>
<?php }} ?>