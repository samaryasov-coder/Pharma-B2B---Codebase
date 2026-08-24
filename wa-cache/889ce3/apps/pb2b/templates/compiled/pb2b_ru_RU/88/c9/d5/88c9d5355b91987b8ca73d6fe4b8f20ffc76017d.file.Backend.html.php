<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:10
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/layouts/Backend.html" */ ?>
<?php /*%%SmartyHeaderCode:20336984816a889d669a5915-64015874%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '88c9d5355b91987b8ca73d6fe4b8f20ffc76017d' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/layouts/Backend.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20336984816a889d669a5915-64015874',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d669d5d80_16415046',
  'variables' => 
  array (
    'wa' => 0,
    'wa_url' => 0,
    'wa_app_static_url' => 0,
    'lang' => 0,
    'wa_app_url' => 0,
    'top_menu' => 0,
    'm_options' => 0,
    'sidebar_mode' => 0,
    'm_item' => 0,
    'rights' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d669d5d80_16415046')) {function content_6a889d669d5d80_16415046($_smarty_tpl) {?><?php if (!is_callable('smarty_block_wa_js')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/block.wa_js.php';
?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $_smarty_tpl->tpl_vars['wa']->value->appName();?>
 - <?php echo $_smarty_tpl->tpl_vars['wa']->value->accountName();?>
</title>

    <?php echo $_smarty_tpl->tpl_vars['wa']->value->css();?>

    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery-plot/jquery.jqplot.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/ibutton/jquery.ibutton.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/css/jquery-ui/base/jquery.ui.datepicker.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/css/jquery-ui/base/jquery.ui.autocomplete.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/redactor/2/redactor.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/lib/codemirror.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/backend.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/fhierarchical.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/spectrum.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/fonticonpicker.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/datatables.css" rel="stylesheet" type="text/css">

    <?php echo $_smarty_tpl->tpl_vars['wa']->value->js();?>

    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery/jquery-migrate-3.3.2.min.js"></script>
    <script>
        window.jQuery = window.jQuery || window.$;
        if (window.jQuery && !window.jQuery.browser) {
            window.jQuery.browser = {};
        }
    </script>
    <?php $_smarty_tpl->smarty->_tag_stack[] = array('wa_js', array()); $_block_repeat=true; echo smarty_block_wa_js(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.core.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.dialog.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/ibutton/jquery.ibutton.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.history.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.store.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.core.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.widget.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.mouse.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.position.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.autocomplete.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.draggable.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.droppable.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.resizable.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.datepicker.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.slider.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.tmpl.min.js
    <?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.retina.js
    <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_wa_js(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.js?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script>
    <?php if (is_readable("wa-content/js/jquery-ui/i18n/jquery.ui.datepicker-".((string)$_smarty_tpl->tpl_vars['wa']->value->locale()).".js")){?>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/i18n/jquery.ui.datepicker-<?php echo $_smarty_tpl->tpl_vars['wa']->value->locale();?>
.js"></script>
    <?php }?>

    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/sortable/sortable.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/sortable/jquery-sortable.min.js" type="text/javascript"></script>

    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/tippy/popper.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/pb2b.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/fsend-v2.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/fsortable-ui2.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/flong-ui2.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/fhierarchical.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/ftransliterate.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/ftags.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/fmass.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/fsteps.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/fredactor.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/spectrum.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/fonticonpicker.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/lib/codemirror.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/mode/xml/xml.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/mode/javascript/javascript.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/mode/css/css.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/mode/htmlmixed/htmlmixed.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/fileupload/jquery.iframe-transport.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/fileupload/jquery.fileupload.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/gofileupload-ui2.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/redactor/2/redactor.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/jquery.dataTables.reload.plugin.js" type="text/javascript"></script>
    <?php $_smarty_tpl->tpl_vars['lang'] = new Smarty_variable(substr($_smarty_tpl->tpl_vars['wa']->value->locale(),0,2), null, 0);?>
    <?php if ($_smarty_tpl->tpl_vars['lang']->value!='en'){?><script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/redactor/2/<?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
.js" type="text/javascript"></script><?php }?>
    <script type="text/javascript">
        var wa_url = '<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
';
        var wa_app_url = '<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
';
        $(document).ready(function() {
            $.pb2b.init();
        });
    </script>
</head>
<body>
<div id="wa">
    <?php echo $_smarty_tpl->tpl_vars['wa']->value->header(array('custom'=>array('aux'=>'<button class="pb2b-sidebar-toggle pb2b-main-sidebar-toggle smallest"><i class="fas fa-bars"></i> Меню</button>')));?>

    <div class="m-app-wrapper flexbox wrap-mobile" id="wa-app">
        <div class="m-sidebar-wrapper sidebar flexbox overflow-visible width-adaptive-wider js-app-sidebar pb2b-sidebar pb2b-main-sidebar">
            <div class="sidebar-header box custom-pt-20">
                <div class="bricks pb2b-sidebar-bricks">
                    <?php  $_smarty_tpl->tpl_vars['m_options'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['m_options']->_loop = false;
 $_smarty_tpl->tpl_vars['m_item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['top_menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['m_options']->key => $_smarty_tpl->tpl_vars['m_options']->value){
$_smarty_tpl->tpl_vars['m_options']->_loop = true;
 $_smarty_tpl->tpl_vars['m_item']->value = $_smarty_tpl->tpl_vars['m_options']->key;
?>
                        <div title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['m_options']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" class="brick ellipsis<?php if ((($tmp = @$_smarty_tpl->tpl_vars['sidebar_mode']->value)===null||$tmp==='' ? 'dashboard' : $tmp)==$_smarty_tpl->tpl_vars['m_item']->value){?> selected accented<?php }?>" data-block="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['m_item']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                            <span class="icon"><i class="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['m_options']->value['icon'], ENT_QUOTES, 'UTF-8', true);?>
"></i></span>
                            <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['m_options']->value['name'], ENT_QUOTES, 'UTF-8', true);?>

                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="sidebar-body">
                <?php  $_smarty_tpl->tpl_vars['m_options'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['m_options']->_loop = false;
 $_smarty_tpl->tpl_vars['m_item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['top_menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['m_options']->key => $_smarty_tpl->tpl_vars['m_options']->value){
$_smarty_tpl->tpl_vars['m_options']->_loop = true;
 $_smarty_tpl->tpl_vars['m_item']->value = $_smarty_tpl->tpl_vars['m_options']->key;
?>
                    <?php if (isset($_smarty_tpl->tpl_vars['m_options']->value['sidebar'])){?>
                        <div class="pb2b-sidebar-content-block<?php if ($_smarty_tpl->tpl_vars['sidebar_mode']->value!=$_smarty_tpl->tpl_vars['m_item']->value){?> hidden<?php }?>" data-block="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['m_item']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                            <?php echo $_smarty_tpl->getSubTemplate ((('./include.').($_smarty_tpl->tpl_vars['m_item']->value)).('.html'), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

                        </div>
                    <?php }?>
                <?php } ?>
            </div>
            <div class="sidebar-footer shadowed pb2b-core-sidebar-footer">
                <ul class="menu">
                    <li class="sidebar-setup-link pb2b-core-sidebar-show">
                        <i class="fas fa-angle-double-up"></i>
                    </li>
                    <li class="sidebar-setup-link">
                        <a href="#/settings//">
                            <i class="fas fa-cog"></i>
                            <span>Настройки</span>
                        </a>
                    </li>
                    <?php if (!empty($_smarty_tpl->tpl_vars['rights']->value['system_control'])){?>
                        <li class="sidebar-setup-link">
                            <a href="#/settings//">
                                <i class="fas fa-cog"></i>
                                <span>Общие настройки</span>
                            </a>
                        </li>
                    <?php }?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['rights']->value['flow_control'])){?>
                        <li class="sidebar-setup-link">
                            <a href="#/helpdesk//">
                                <i class="fas fa-comments"></i>
                                <span>Запросы</span>
                            </a>
                        </li>
                    <?php }?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['rights']->value['system_plugins'])){?>
                        <li class="sidebar-plugins-link">
                            <a href="#/plugins/">
                                <i class="fas fa-plug"></i>
                                <span>Плагины</span>
                            </a>
                        </li>
                    <?php }?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['rights']->value['system_design'])){?>
                        <li class="sidebar-design-link">
                            <a href="#/design/themes/">
                                <i class="fas fa-palette"></i>
                                <span>Дизайн</span>
                            </a>
                        </li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <div class="content blank">
            <div id="s-content-block" class="pb60">
                <div class="article pb2b-base-article">
                    <div class="article-body pb2b-ajax-content">
                        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html><?php }} ?>