<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:43
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/layouts/Default.html" */ ?>
<?php /*%%SmartyHeaderCode:18663569596a889d13555324-52354570%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '279f3e5f8d24701280beb0aed4688bf13a497452' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/layouts/Default.html',
      1 => 1738158327,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18663569596a889d13555324-52354570',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    'wa_app_static_url' => 0,
    'wa_url' => 0,
    'geocoding' => 0,
    '_key' => 0,
    '_apikey' => 0,
    'backend_assets' => 0,
    'item' => 0,
    'sidebar' => 0,
    'content' => 0,
    'is_debug' => 0,
    'wa_app_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d13577e92_97470445',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d13577e92_97470445')) {function content_6a889d13577e92_97470445($_smarty_tpl) {?><?php if (!is_callable('smarty_block_wa_js')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/block.wa_js.php';
?><!DOCTYPE html><html lang="<?php if ($_smarty_tpl->tpl_vars['wa']->value->locale()){?><?php echo substr($_smarty_tpl->tpl_vars['wa']->value->locale(),0,2);?>
<?php }else{ ?>ru<?php }?>"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><title><?php echo $_smarty_tpl->tpl_vars['wa']->value->accountName();?>
 &mdash; <?php echo $_smarty_tpl->tpl_vars['wa']->value->appName();?>
</title><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/plugins/timepicker/jquery.timepicker.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/css/jquery-ui/base/jquery.ui.all.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/pickr/themes/classic.min.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/carousel/swiper.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"><?php echo $_smarty_tpl->tpl_vars['wa']->value->css();?>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/team.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"><script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery/jquery-3.6.0.min.js"></script><script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.js?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script>
        <?php $_smarty_tpl->smarty->_tag_stack[] = array('wa_js', array('file'=>"js/compiled/team-external.min.js",'uibundle'=>'')); $_block_repeat=true; echo smarty_block_wa_js(array('file'=>"js/compiled/team-external.min.js",'uibundle'=>''), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery/jquery-migrate-3.3.2.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/sortable/sortable.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/sortable/jquery-sortable.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.core.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.widget.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.mouse.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.droppable.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.draggable.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.touch-punch.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.position.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.autocomplete.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.datepicker.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.store.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/profileWebasystID.js
            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/carousel/swiper-bundle.min.js
        <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_wa_js(array('file'=>"js/compiled/team-external.min.js",'uibundle'=>''), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

        <?php $_smarty_tpl->smarty->_tag_stack[] = array('wa_js', array()); $_block_repeat=true; echo smarty_block_wa_js(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

            <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/pickr/pickr.min.js
        <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_wa_js(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

        <?php $_smarty_tpl->smarty->_tag_stack[] = array('wa_js', array('file'=>"js/compiled/team.min.js")); $_block_repeat=true; echo smarty_block_wa_js(array('file'=>"js/compiled/team.min.js"), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/plugins/timepicker/jquery.timepicker.min.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/team.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/sidebar.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/calendar.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/profile.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/profile.info.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/access.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/group.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/settings.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/api-token.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/users.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/long.action.process.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/map.js
            <?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/swiper.js
        <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_wa_js(array('file'=>"js/compiled/team.min.js"), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php if ($_smarty_tpl->tpl_vars['wa']->value->locale()!='en_US'){?><script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/i18n/jquery.ui.datepicker-<?php echo $_smarty_tpl->tpl_vars['wa']->value->locale();?>
.js"></script><?php }?><?php if ($_smarty_tpl->tpl_vars['geocoding']->value['type']==='google'){?><?php $_smarty_tpl->tpl_vars['_key'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['geocoding']->value['key'])===null||$tmp==='' ? '' : $tmp), null, 0);?><script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $_smarty_tpl->tpl_vars['_key']->value;?>
&lang=<?php echo $_smarty_tpl->tpl_vars['wa']->value->locale();?>
"></script><?php }elseif($_smarty_tpl->tpl_vars['geocoding']->value['type']==='yandex'){?><?php $_smarty_tpl->tpl_vars['_apikey'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['geocoding']->value['key'])===null||$tmp==='' ? '' : $tmp), null, 0);?><script src="https://api-maps.yandex.ru/2.1/?lang=<?php echo $_smarty_tpl->tpl_vars['wa']->value->locale();?>
&apikey=<?php echo $_smarty_tpl->tpl_vars['_apikey']->value;?>
" type="text/javascript"></script><?php }?><?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_assets']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
<?php } ?><script>( function($) {$.team.title_pattern = "%s — <?php echo $_smarty_tpl->tpl_vars['wa']->value->accountName();?>
";})(jQuery);</script></head><body><div id="wa"><?php echo $_smarty_tpl->tpl_vars['wa']->value->header();?>
<div class="t-app-wrapper flexbox wrap-mobile" id="wa-app"><?php if (!empty($_smarty_tpl->tpl_vars['sidebar']->value)){?><div class="t-sidebar-wrapper sidebar flexbox overflow-visible width-adaptive-wider mobile-friendly js-app-sidebar" id="t-sidebar-wrapper"><?php echo (($tmp = @$_smarty_tpl->tpl_vars['sidebar']->value)===null||$tmp==='' ? '' : $tmp);?>
</div><?php }?><div class="content blank" id="t-content"><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</div></div></div><script>(function($) {<?php if (!empty($_smarty_tpl->tpl_vars['sidebar']->value)){?>$('.js-app-sidebar').waShowSidebar();<?php }?>$.team.init({is_debug: <?php echo $_smarty_tpl->tpl_vars['is_debug']->value;?>
,app_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
,locales: {map_check_your_key: 'Проверьте свой ключ прежде всего <a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
settings/">здесь</a>',map_error_title: 'Ошибка',map_error_message: 'Ошибка карт Google, возможно, в настройках указан неверный ключ.'}});})(jQuery);</script></body></html>
<?php }} ?>