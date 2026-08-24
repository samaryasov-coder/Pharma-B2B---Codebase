<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:41:49
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendHeader.html" */ ?>
<?php /*%%SmartyHeaderCode:16777409056a8c825dbb8c42-33814113%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd8a688efd0381415a583aa59371dd168730b17de' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendHeader.html',
      1 => 1776411860,
      2 => 'file',
    ),
    'cb2bcd0151d277f5facb44b474fe01f28cf45775' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendLogo.inc.html',
      1 => 1725023146,
      2 => 'file',
    ),
    '5d824a68d26929dddeefe6a6fe8640e257c75866' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/dashboard/DashboardAnnouncementGroupList.inc.html',
      1 => 1713188366,
      2 => 'file',
    ),
    '39b654f3c86f045cfc8d9f933217f9f0270ed55a' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/dashboard/DashboardAnnouncement.html',
      1 => 1735210213,
      2 => 'file',
    ),
    '27c01dcaecb0eea6d4d55fd0394198daa3340c3a' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendHeaderCurrentUser.inc.html',
      1 => 1742899371,
      2 => 'file',
    ),
    '3b8b8c1850389c4c97d667f788594b28cc5868be' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendHeader20.html',
      1 => 1776411860,
      2 => 'file',
    ),
    'fa1493c946137d9aaa3772f025a44a733b113049' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendHeader13.html',
      1 => 1741870267,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16777409056a8c825dbb8c42-33814113',
  'function' => 
  array (
    '_renderHeaderItem' => 
    array (
      'parameter' => 
      array (
        '_id' => '',
        '_info' => 
        array (
        ),
      ),
      'compiled' => '',
    ),
    'get_icon' => 
    array (
      'parameter' => 
      array (
        'status' => NULL,
        'with_color' => false,
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'wa' => 0,
    'wa_url' => 0,
    'backend_url' => 0,
    'app_info' => 0,
    '_force_set_wa_backend_ui_version' => 0,
    'root_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c825dc90868_70067093',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c825dc90868_70067093')) {function content_6a8c825dc90868_70067093($_smarty_tpl) {?><div id="wa-nav">
    <?php if ($_smarty_tpl->tpl_vars['wa']->value->whichUI()=='2.0'){?>
        <script>
            if (typeof wa_url === "undefined") {
                wa_url = '<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
';
            }
            const backend_url = "<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
";
        </script>
        <?php /*  Call merged included template "./BackendHeader20.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./BackendHeader20.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '16777409056a8c825dbb8c42-33814113');
content_6a8c825dbbd555_94919551($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./BackendHeader20.html" */?>
    <?php }else{ ?>
        <?php /*  Call merged included template "./BackendHeader13.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./BackendHeader13.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '16777409056a8c825dbb8c42-33814113');
content_6a8c825dc5e5e5_65421270($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./BackendHeader13.html" */?>
    <?php }?>

    

    

    <script>
        function _setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
        }
    </script>

    <?php $_smarty_tpl->tpl_vars['_force_set_wa_backend_ui_version'] = new Smarty_variable(waSystemConfig::whichBackendUI(), null, 0);?>

    <?php if (ifset($_smarty_tpl->tpl_vars['app_info']->value,'ui','1.3')==='1.3,2.0'){?>

        <?php if ($_smarty_tpl->tpl_vars['wa']->value->debug()){?>

            
            <style>
                .wa-general-settings-place {
                    position: fixed;
                    right: 0;
                    bottom: 20px;
                    z-index: 9999;

                    pointer-events: none;
                    font-size: 16px;
                }

                .wa-general-settings-place__toggler {
                    position: absolute;
                    top: 0;
                    left: 0;
                    bottom: 0;

                    display: flex;
                    align-items: center;
                    justify-content: center;

                    width: 55px;
                    color: #1a9afe;
                    font-weight: bold;
                    cursor: pointer;
                }
                .wa-general-settings-place__toggler:hover {
                    text-decoration: underline;
                }

                .wa-general-settings-place__checkbox {
                    position: absolute;
                    left: -99999px;
                }

                .wa-general-settings-place__inner {
                    pointer-events: auto;
                    background-color: #fea;
                    border: 2px solid #eda;
                    border-radius: 4px;
                    padding: 12px;
                    padding-left: 55px;
                    box-shadow: 0 5px 20px -10px rgba(0,0,0,0.2), 0 3px 10px -5px rgba(0,0,0,0.1);
                    transform: translate(calc(100% - 55px));
                    transition: .3s transform;
                }

                .wa-general-settings-place__checkbox:checked + .wa-general-settings-place__inner {
                    transform: translate(-20px);
                }
            </style>

            <div class="wa-general-settings-place">
                <input type="checkbox" class="wa-general-settings-place__checkbox" id="wa-general-settings-place">

                <div class="wa-general-settings-place__inner">
                    <label class="wa-general-settings-place__toggler" for="wa-general-settings-place">
                        <?php if ($_smarty_tpl->tpl_vars['wa']->value->whichUI()=='1.3'){?>
                        1.3
                        <?php }else{ ?>
                        2.0
                        <?php }?>
                    </label>

                    <span class="desktop-only">Режим интерфейса (debug):</span>

                    <select class="not-styled" onChange="_setCookie('force_set_wa_backend_ui_version', this.options[this.selectedIndex].value, 180); window.location.reload();" style="font-size: 16px;">
                        <option value="1.3"<?php if ($_smarty_tpl->tpl_vars['_force_set_wa_backend_ui_version']->value=='1.3'){?> selected<?php }?>>1.3</option>
                        <option value="2.0"<?php if ($_smarty_tpl->tpl_vars['_force_set_wa_backend_ui_version']->value=='2.0'){?> selected<?php }?>>2.0</option>
                    </select>
                    <?php if ($_smarty_tpl->tpl_vars['_force_set_wa_backend_ui_version']->value=='2.0'){?>
                        <span style="display: inline-block; border-radius: 50%; border: 1px solid rgba(0,0,0,0.2); width: 18px; height: 18px; background: white; box-shadow: 0 3px 5px -2px rgba(0,0,0,0.2); position: relative; top: 3px; margin: 0 1px 0 8px; cursor: pointer" title="Светлый режим" data-wa-theme-mode="light"></span>
                        <span style="display: inline-block; border-radius: 50%; border: 1px solid rgba(0,0,0,0.2); width: 18px; height: 18px; background: #333; box-shadow: 0 3px 6px -2px rgba(0,0,0,0.42); position: relative; top: 3px; margin: 0 2px; cursor: pointer" title="Темный режим" data-wa-theme-mode="dark"></span>
                        <span style="display: inline-block; border-radius: 50%; overflow: hidden; border: 1px solid rgba(0,0,0,0.2); width: 18px; height: 18px; position: relative; top: 3px; margin: 0 2px; cursor: pointer" title="Авто (зависит от настроек устройства)" data-wa-theme-mode="auto"><i style="background: linear-gradient(-90deg, rgba(51,51,51,1) 40%, rgba(255,255,255,1) 60%); box-shadow: 0 3px 6px -2px rgba(0,0,0,0.42); display: block; width: 18px; height: 18px;"></i></span>
                    <?php }?>
                </div>
            </div>

        <?php }else{ ?>

            

            <?php if ($_smarty_tpl->tpl_vars['wa']->value->whichUI()=='1.3'&&$_smarty_tpl->tpl_vars['app_info']->value['id']!='webasyst'){?>

                <style>
                    @-webkit-keyframes wa2uiAlertBottom {
                        from { opacity: 0; -webkit-transform: translate3d(0, 10%, 0); transform: translate3d(0, 10%, 0); }
                        to { opacity: 1; -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
                    }
                    @keyframes wa2uiAlertBottom {
                        from { opacity: 0; -webkit-transform: translate3d(0, 10%, 0); transform: translate3d(0, 10%, 0); }
                        to { opacity: 1; -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
                    }

                    .popup-notification-new {
                        position: fixed;
                        right: 20px;
                        bottom: 20px;
                        z-index: 9999;

                        max-width: 600px;
                        color: #999;
                        background: #222;
                        border-radius: 24px;
                        padding: 24px 22px;
                        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05), 0 0.5rem 0.5rem -0.5rem rgba(0, 0, 0, 0.13);
                        -webkit-animation: 300ms wa2uiAlertBottom;
                        animation: 300ms wa2uiAlertBottom;
                    }

                    .popup-notification-new__close {
                        position: relative;
                        top: -5px;
                        right: 4px;

                        float: right;

                        color: #ccc;
                        font-size: 28px;
                        padding: 0px;
                    }

                    .popup-notification-new__title {
                        font-size: 19px;
                        margin-bottom: 12px;
                        color: #fff;
                        display: flex;
                    }

                    .popup-notification-new__title-img {
                        margin-right: 10px;
                        width: 24px;
                        height: 24px;
                    }

                    .popup-notification-new__title-color {
                        opacity: .42;
                    }

                    .popup-notification-new__text {
                        font-size: 13px;
                    }

                    .popup-notification-new__button {
                        position: relative;
                        top: 0;

                        display: inline-block;

                        color: #09f;
                        font-size: 16px;
                        font-weight: bold;
                        line-height: 1;
                        white-space: nowrap;

                        background: #fff;
                        border-radius: 13px;
                        border: none;
                        outline: 0 none;
                        padding: 12px 18px;
                        margin: 0 2px 0 0;

                        box-shadow: 0 0.25em 0.75em -0.25em rgba(0, 0, 0, 0.2);
                        transition: 0.1s;
                        cursor: pointer;
                    }

                    .popup-notification-new__icon {
                        position: relative;
                        top: 11px;
                        left: 2px;
                    }
                </style>

                <div class="popup-notification-new js-popup-notification-new"<?php if ($_smarty_tpl->tpl_vars['wa']->value->cookie('force_hide_wa2ui_teaser')&&$_smarty_tpl->tpl_vars['app_info']->value['id']!='ui'){?> style="display: none;"<?php }?>>
                    <a href="#" class="popup-notification-new__close js-popup-notification-new-close">&times;</a>
                    <h3 class="popup-notification-new__title">
                        <?php if ($_smarty_tpl->tpl_vars['app_info']->value['id']!='webasyst'){?><img src="<?php echo $_smarty_tpl->tpl_vars['root_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['app_info']->value['icon'][48];?>
" class="popup-notification-new__title-img"><?php }?>
                        <span>Старый интерфейс не поддерживается с 2022 года</span>
                    </h3>
                    <p class="popup-notification-new__text">Вы находитесь в старом режиме интерфейса, который не поддерживается и не развивается. Все новые возможности появляются только в новом интерфейсе.</p>
                    <button class="popup-notification-new__button" onClick="_setCookie('force_set_wa_backend_ui_version', '2.0', 180); window.location.reload();">Перейти на новый интерфейс</button>
                </div>

                <script>
                  ( function($) {
                    $('.js-popup-notification-new-close').click(function(e) {
                      e.preventDefault()
                      _setCookie('force_hide_wa2ui_teaser', 1, 180);
                      $(this).parent('.js-popup-notification-new').hide();
                    })
                  })(jQuery);
                </script>

            <?php }?>

        <?php }?>


    <?php }elseif(ifset($_smarty_tpl->tpl_vars['app_info']->value,'ui','1.3')==='1.3'&&!$_smarty_tpl->tpl_vars['wa']->value->debug()){?>

        <?php if ($_smarty_tpl->tpl_vars['_force_set_wa_backend_ui_version']->value=='2.0'){?>

            <div style="position: fixed; z-index: 9999; bottom: 20px; right: 20px; border-radius: 4px; background: #eee; padding: 25px; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05), 0 0.5rem 0.5rem -0.5rem rgba(0, 0, 0, 0.13); max-width: 500px;<?php if ($_smarty_tpl->tpl_vars['wa']->value->cookie('force_hide_wa2ui_teaser')&&$_smarty_tpl->tpl_vars['app_info']->value['id']!='ui'){?> display: none;<?php }?>">
                <a href="javascript:void(0)" onClick="_setCookie('force_hide_wa2ui_teaser', 1, 180); $(this).parent().hide();
    " style="padding: 6px; float: right; font-size: 24px; color: rgba(0,0,0,0.5); position: relative; top: -10px; right: -6px;">&times;</a>
                <h3 style="font-size: 20px; margin-bottom: 12px; color: #777; margin-top: -7px;">
                    <?php if ($_smarty_tpl->tpl_vars['app_info']->value['id']!='webasyst'){?><img src="<?php echo $_smarty_tpl->tpl_vars['root_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['app_info']->value['icon'][48];?>
" style="width: 24px; height: 24px; position: relative; top: 4px;"><?php }?>
                    Новый интерфейс пока не поддерживается
                </h3>
                <p style="font-size: 13px; margin-bottom: 0;">Вы включили режим нового интерфейса Webasyst 2, но это приложение его пока не поддерживает. Пожалуйста, свяжитесь с разработчиком приложения и узнайте, когда новый интерфейс станет доступен.</p>
            </div>

        <?php }?>

    <?php }?>

    

</div>
<?php if ($_smarty_tpl->tpl_vars['wa']->value->whichUI()=='2.0'){?>
<div class="wa-header-background dialog-background js-header-background"></div>
<?php }?>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:41:49
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendHeader20.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c825dbbd555_94919551')) {function content_6a8c825dbbd555_94919551($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
?>
<?php if (!function_exists('smarty_template_function__renderHeaderItem')) {
    function smarty_template_function__renderHeaderItem($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['_renderHeaderItem']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php if (!empty($_smarty_tpl->tpl_vars['_info']->value['app_id'])&&!empty($_smarty_tpl->tpl_vars['_info']->value['link'])){?><?php $_smarty_tpl->tpl_vars['_item_url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['backend_url']->value).((string)$_smarty_tpl->tpl_vars['_info']->value['app_id'])."/".((string)$_smarty_tpl->tpl_vars['_info']->value['link'])."/", null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_item_url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['backend_url']->value).((string)$_smarty_tpl->tpl_vars['_id']->value)."/", null, 0);?><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['_info']->value['version'])){?><?php $_smarty_tpl->tpl_vars['_version'] = new Smarty_variable("?v=".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['_info']->value['version'], ENT_QUOTES, 'UTF-8', true)), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_version'] = new Smarty_variable(null, null, 0);?><?php }?><li id="wa-app-<?php echo str_replace('.','-',$_smarty_tpl->tpl_vars['_id']->value);?>
" data-app="<?php echo $_smarty_tpl->tpl_vars['_id']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['_id']->value==$_smarty_tpl->tpl_vars['current_app']->value||stristr($_smarty_tpl->tpl_vars['request_uri']->value,$_smarty_tpl->tpl_vars['_item_url']->value)!==false){?> class="selected"<?php }?>><?php $_smarty_tpl->tpl_vars['_count'] = new Smarty_variable(null, null, 0);?><?php if ($_smarty_tpl->tpl_vars['counts']->value&&isset($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value])){?><?php if (is_array($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value])){?><?php $_smarty_tpl->tpl_vars['_item_url'] = new Smarty_variable($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value]['url'], null, 0);?><?php $_smarty_tpl->tpl_vars['_count'] = new Smarty_variable($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value]['count'], null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_count'] = new Smarty_variable($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value], null, 0);?><?php }?><?php }?><a href="<?php echo $_smarty_tpl->tpl_vars['_item_url']->value;?>
"<?php if (!$_smarty_tpl->tpl_vars['wa']->value->isMobile()&&$_smarty_tpl->tpl_vars['_info']->value['name']){?> data-wa-tooltip-content="<?php echo ifempty($_smarty_tpl->tpl_vars['_info']->value['name']);?>
"<?php }?>><?php if (isset($_smarty_tpl->tpl_vars['_info']->value['img'])){?><img<?php if (!empty($_smarty_tpl->tpl_vars['_info']->value['icon'][96])){?> data-src2="<?php echo $_smarty_tpl->tpl_vars['root_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['_info']->value['icon'][96];?>
<?php echo $_smarty_tpl->tpl_vars['_version']->value;?>
"<?php }?> src="<?php echo $_smarty_tpl->tpl_vars['root_url']->value;?>
<?php if (!empty($_smarty_tpl->tpl_vars['_info']->value['icon'][96])){?><?php echo $_smarty_tpl->tpl_vars['_info']->value['icon'][96];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['_info']->value['img'];?>
<?php }?><?php echo $_smarty_tpl->tpl_vars['_version']->value;?>
" alt=""><?php }?><span class="wa-app-name"><?php echo ifempty($_smarty_tpl->tpl_vars['_info']->value['name']);?>
</span><?php if ($_smarty_tpl->tpl_vars['_count']->value){?><span class="badge indicator"><?php echo $_smarty_tpl->tpl_vars['_count']->value;?>
</span><?php }?></a></li><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>
<?php if (!empty($_smarty_tpl->tpl_vars['header_top']->value)){?><?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['header_top']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['_']->value;?>
<?php } ?><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['include_wa_push']->value)){?><script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.push.js?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version('webasyst');?>
"></script><?php }?><?php $_smarty_tpl->tpl_vars['has_dasboard'] = new Smarty_variable(($_smarty_tpl->tpl_vars['current_app']->value==='webasyst'&&isset($_smarty_tpl->tpl_vars['activity']->value)), null, 0);?><?php $_smarty_tpl->tpl_vars['request_uri'] = new Smarty_variable(explode('?',$_smarty_tpl->tpl_vars['request_uri']->value,2), null, 0);?><?php $_smarty_tpl->tpl_vars['request_uri'] = new Smarty_variable($_smarty_tpl->tpl_vars['request_uri']->value[0], null, 0);?><div id="wa-header"><div id="wa-header-logo-area"><?php /*  Call merged included template "./BackendLogo.inc.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./BackendLogo.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('backend_url'=>$_smarty_tpl->tpl_vars['backend_url']->value,'company_name'=>$_smarty_tpl->tpl_vars['company_name']->value,'position'=>"header"), 0, '16777409056a8c825dbb8c42-33814113');
content_6a8c825dbd7cc9_05970134($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./BackendLogo.inc.html" */?><div class="width-100 custom-pr-16 custom-my-12"><div class="flexbox middle"><p class="wa-header-sitename flexbox middle space-16"><span class="h2 wa-sitename<?php if ($_smarty_tpl->tpl_vars['request_uri']->value==$_smarty_tpl->tpl_vars['backend_url']->value||$_smarty_tpl->tpl_vars['request_uri']->value==((string)$_smarty_tpl->tpl_vars['backend_url']->value)."/"){?> is-main-dashboard<?php }?>"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['wa']->value->accountName(),42);?>
</span></p></div><?php if ($_smarty_tpl->tpl_vars['has_dasboard']->value){?><div class="flexbox middle space-8 custom-ml-20 custom-mt-12"><?php if ($_smarty_tpl->tpl_vars['wa']->value->user()->getRights('team','edit_announcements')){?><a href="javascript:void(0)" class="js-new-announcement wa-announcement-button-new button rounded light-gray small"><i class="icon webasyst-magic-wand-ai-black text-gray custom-mr-4"></i>Объявление</a><?php }?><span class="js-header-datetime wa-header-datetime gray semibold nowrap"></span><div class="dropdown custom-ml-4" id="wa-announcement-links"><span class="dropdown-toggle without-arrow"><i class="fas fa-ellipsis-h custom-ml-4 text-gray"></i></span><div class="dropdown-body right"><ul class="menu"><li><a href="javascript:void(0)" class="js-show-hidden-announcements"><span>Показать прочитанные</span></a></li></ul></div></div></div><?php }?></div></div><div id="wa-header-content-area"><div class="wa-header-custom-main-content"><?php echo $_smarty_tpl->tpl_vars['custom_params']->value['main'];?>
</div><div id="wa-applist" class="js-applist js-applist-header<?php if (is_array($_smarty_tpl->tpl_vars['counts']->value)){?> counts-cached<?php }?><?php if ($_smarty_tpl->tpl_vars['custom_params']->value['main']){?> wa-applist-with-custom-main-content<?php }?><?php if ($_smarty_tpl->tpl_vars['wa']->value->isMobile()){?> is-mobile<?php }?>"><ul><?php  $_smarty_tpl->tpl_vars['_info'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_info']->_loop = false;
 $_smarty_tpl->tpl_vars['_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['header_items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_info']->key => $_smarty_tpl->tpl_vars['_info']->value){
$_smarty_tpl->tpl_vars['_info']->_loop = true;
 $_smarty_tpl->tpl_vars['_id']->value = $_smarty_tpl->tpl_vars['_info']->key;
?><?php smarty_template_function__renderHeaderItem($_smarty_tpl,array('_id'=>$_smarty_tpl->tpl_vars['_id']->value,'_info'=>$_smarty_tpl->tpl_vars['_info']->value));?>
<?php } ?></ul>
        </div>


    </div>
    <div id="wa-header-user-area" class="flexbox space-24">
        
        <?php $_smarty_tpl->tpl_vars['dashboards'] = new Smarty_variable(strpos($_smarty_tpl->tpl_vars['request_uri']->value,"/webasyst/dashboard/"), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['dashboard_url'] = new Smarty_variable(strpos($_smarty_tpl->tpl_vars['request_uri']->value,"/webasyst/dashboard/dashboard/"), null, 0);?>
        <?php $_smarty_tpl->tpl_vars['is_dashboards'] = new Smarty_variable(false, null, 0);?>
        <?php $_smarty_tpl->tpl_vars['is_dashboard'] = new Smarty_variable(false, null, 0);?>
        <?php if ($_smarty_tpl->tpl_vars['dashboards']->value!==false){?>
            <?php $_smarty_tpl->tpl_vars['is_dashboards'] = new Smarty_variable(true, null, 0);?>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['dashboard_url']->value!==false){?>
            <?php $_smarty_tpl->tpl_vars['is_dashboard'] = new Smarty_variable(true, null, 0);?>
        <?php }?>

        <?php if ($_smarty_tpl->tpl_vars['custom_params']->value['aux']||$_smarty_tpl->tpl_vars['header_user_area']->value['aux']){?><?php $_smarty_tpl->_capture_stack[0][] = array('default', "_header_custom_aux_content", null); ob_start(); ?><?php echo $_smarty_tpl->tpl_vars['custom_params']->value['aux'];?>
<?php  $_smarty_tpl->tpl_vars['_aux_content'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_aux_content']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['header_user_area']->value['aux']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_aux_content']->key => $_smarty_tpl->tpl_vars['_aux_content']->value){
$_smarty_tpl->tpl_vars['_aux_content']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['_aux_content']->value;?>
<?php } ?><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php if (trim($_smarty_tpl->tpl_vars['_header_custom_aux_content']->value)){?><div class="wa-header-custom-aux-content"><?php echo $_smarty_tpl->tpl_vars['_header_custom_aux_content']->value;?>
</div><?php }?><?php }?><?php if ($_smarty_tpl->tpl_vars['request_uri']->value==$_smarty_tpl->tpl_vars['backend_url']->value||$_smarty_tpl->tpl_vars['request_uri']->value==((string)$_smarty_tpl->tpl_vars['backend_url']->value)."/"||$_smarty_tpl->tpl_vars['is_dashboards']->value){?><?php }else{ ?><a class="wa-toggle-apps rotate180 js-toggle-apps" href="#" title="Все приложения"><i class="fas fa-th fa-lg"></i></a><?php }?><?php if (!$_smarty_tpl->tpl_vars['has_dasboard']->value){?><div class="dropdown" id="wa-notifications-dropdown"><button class="icon large wa-notifications-bell dropdown-toggle without-arrow js-notifications-bell" title="Уведомления"><i class="fas fa-bell"></i><?php if (!empty($_smarty_tpl->tpl_vars['notifications']->value)){?><span class="badge"><?php echo count($_smarty_tpl->tpl_vars['notifications']->value);?>
</span><?php }?></button><?php /*  Call merged included template "../dashboard/DashboardAnnouncement.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("../dashboard/DashboardAnnouncement.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('notifications'=>$_smarty_tpl->tpl_vars['notifications']->value), 0, '16777409056a8c825dbb8c42-33814113');
content_6a8c825dc03962_60268132($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "../dashboard/DashboardAnnouncement.html" */?></div><?php }?><?php if (!$_smarty_tpl->tpl_vars['wa']->value->debug()&&ifset($_smarty_tpl->tpl_vars['app_info']->value,'ui','1.3')==='1.3,2.0'&&$_smarty_tpl->tpl_vars['app_info']->value['id']!='webasyst'){?><div class="dropdown" id="wa2ui-dropdown"><span class="dropdown-toggle without-arrow js-wa2ui custom-pr-0" title="Новый интерфейс Webasyst 2" style="color: var(--text-color-link); cursor: pointer;"><span class="badge blue">2.0</span></span><div class="alert-fixed-box large hidden d-notification-wrapper js-notification-wrapper" id="d-notification-wrapper"><div class="wa-notification"><div class="wa-notification-body"><p>Отлично, вы в новом режиме интерфейса! Все новые возможности и развитие платформы — только здесь.</p><p>Попробуйте его в светлом и темном режимах:<span style="display: inline-block; border-radius: 50%; border: 1px solid rgba(0,0,0,0.2); width: 18px; height: 18px; background: white; box-shadow: 0 3px 5px -2px rgba(0,0,0,0.2); position: relative; top: 3px; margin: 0 5px 0 8px; cursor: pointer" title="Светлый режим" data-wa-theme-mode="light"></span><span style="display: inline-block; border-radius: 50%; border: 1px solid rgba(0,0,0,0.2); width: 18px; height: 18px; background: #333; box-shadow: 0 3px 6px -2px rgba(0,0,0,0.42); position: relative; top: 3px; margin: 0 3px 0 0; cursor: pointer" title="Темный режим" data-wa-theme-mode="dark"></span><span style="display: inline-block; border-radius: 50%; overflow: hidden; border: 1px solid rgba(0,0,0,0.2); width: 18px; height: 18px; position: relative; top: 3px; margin: 0 2px; cursor: pointer" title="Авто (зависит от настроек устройства)" data-wa-theme-mode="auto"><i style="background: linear-gradient(-90deg, rgba(51,51,51,1) 40%, rgba(255,255,255,1) 60%); box-shadow: 0 3px 6px -2px rgba(0,0,0,0.42); display: block; width: 18px; height: 18px;"></i></span></p><div class="flexbox space-8 middle"><button class="button gray outlined small custom-mr-4" onClick="_setCookie('force_set_wa_backend_ui_version', '1.3', 365); _setCookie('force_hide_wa2ui_teaser', 0, -1); window.location.reload();"><span>Вернуться в старый интерфейс</span></button><span class="small gray">&larr; не поддерживается</span></div></div></div></div></div><?php }?><?php /*  Call merged included template "./BackendHeaderCurrentUser.inc.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./BackendHeaderCurrentUser.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '16777409056a8c825dbb8c42-33814113');
content_6a8c825dc2a0e2_41420231($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./BackendHeaderCurrentUser.inc.html" */?>
    </div>
</div>
    <script id="wa-header-js"
            type="text/javascript"
            src="<?php echo $_smarty_tpl->tpl_vars['root_url']->value;?>
wa-content/js/jquery-wa/wa.header.js?<?php echo $_smarty_tpl->tpl_vars['wa_version']->value;?>
"
            <?php if (!$_smarty_tpl->tpl_vars['user']->value['timezone']){?> data-determine-timezone="1"<?php }?>>
    </script>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['root_url']->value;?>
wa-content/js/jquery-wa/wa.header.bell-announcement.js?<?php echo $_smarty_tpl->tpl_vars['wa_version']->value;?>
"></script>
    <script>
        $(function () {
            new WaHeader();
            new WaBellAnnouncement();

            /* Active All Notifications On Main Dashboard */
            
            <?php if ($_smarty_tpl->tpl_vars['has_new_notifications']->value){?>
                $('.js-notifications-bell').trigger('click', { disable_set_seen: true });
            <?php }?>

            <?php if (!$_smarty_tpl->tpl_vars['wa']->value->debug()){?>
                
                const $wa2ui = $('.js-wa2ui');
                $wa2ui.on('click', function (e){
                    e.preventDefault();
                    $(this).next('.alert-fixed-box').toggle().removeClass('hidden');
                });
            <?php }?>

            $("#wa-announcement-links").waDropdown({
                <?php if ($_smarty_tpl->tpl_vars['wa']->value->isMobile()){?>
                    hover: false
                <?php }?>
            });

            /* Frontend links */
            $("#wa-frontend-links").waDropdown({
                <?php if ($_smarty_tpl->tpl_vars['wa']->value->isMobile()){?>
                    hover: false
                <?php }?>
            });
        });
    </script>

    <?php $_smarty_tpl->tpl_vars['_request_uri'] = new Smarty_variable(rtrim($_smarty_tpl->tpl_vars['request_uri']->value,'/'), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['_bashend_url'] = new Smarty_variable(rtrim($_smarty_tpl->tpl_vars['backend_url']->value,'/'), null, 0);?>

    <?php if ($_smarty_tpl->tpl_vars['_request_uri']->value==$_smarty_tpl->tpl_vars['_bashend_url']->value||$_smarty_tpl->tpl_vars['_request_uri']->value==((string)$_smarty_tpl->tpl_vars['_bashend_url']->value)."/webasyst"||$_smarty_tpl->tpl_vars['dashboards']->value){?>
        <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/sortable/sortable.min.js"></script>
        <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/sortable/jquery-sortable.min.js"></script>
        <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/dashboard.js?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"></script>
    <?php }?>

    
    <?php if (!empty($_smarty_tpl->tpl_vars['header_bottom']->value)){?><?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['header_bottom']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['_']->value;?>
<?php } ?><?php }?>

<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:41:49
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendLogo.inc.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c825dbd7cc9_05970134')) {function content_6a8c825dbd7cc9_05970134($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
?><?php if ($_smarty_tpl->tpl_vars['logo']->value['mode']==='gradient'||empty($_smarty_tpl->tpl_vars['logo']->value['image']['thumbs'])){?><?php $_smarty_tpl->tpl_vars['two_lines'] = new Smarty_variable($_smarty_tpl->tpl_vars['logo']->value['two_lines'], null, 0);?><?php $_smarty_tpl->tpl_vars['text_value'] = new Smarty_variable(htmlspecialchars((string)trim($_smarty_tpl->tpl_vars['logo']->value['text']['value']), ENT_QUOTES, 'UTF-8', true), null, 0);?><?php if ($_smarty_tpl->tpl_vars['position']->value==='header'){?><div id="wa-account" style="background: linear-gradient(<?php echo $_smarty_tpl->tpl_vars['logo']->value['gradient']['angle'];?>
deg, <?php echo $_smarty_tpl->tpl_vars['logo']->value['gradient']['from'];?>
, <?php echo $_smarty_tpl->tpl_vars['logo']->value['gradient']['to'];?>
); color: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['logo']->value['text']['color'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['logo']->value['text']['default_color'] : $tmp);?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
"><?php }?><h3<?php if ($_smarty_tpl->tpl_vars['two_lines']->value){?> class="two-lines"<?php }?> title="<?php echo $_smarty_tpl->tpl_vars['company_name']->value;?>
" data-icon="fas fa-home" style="<?php if ($_smarty_tpl->tpl_vars['position']->value==='sidebar'){?>background: linear-gradient(<?php echo $_smarty_tpl->tpl_vars['logo']->value['gradient']['angle'];?>
deg, <?php echo $_smarty_tpl->tpl_vars['logo']->value['gradient']['from'];?>
, <?php echo $_smarty_tpl->tpl_vars['logo']->value['gradient']['to'];?>
); color: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['logo']->value['text']['color'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['logo']->value['text']['default_color'] : $tmp);?>
<?php }else{ ?>color: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['logo']->value['text']['color'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['logo']->value['text']['default_color'] : $tmp);?>
<?php }?>"><?php if ($_smarty_tpl->tpl_vars['text_value']->value){?><?php if ($_smarty_tpl->tpl_vars['two_lines']->value){?><?php echo nl2br(htmlspecialchars((string)trim((($tmp = @$_smarty_tpl->tpl_vars['logo']->value['text']['formatted_value'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['logo']->value['text']['value'] : $tmp)), ENT_QUOTES, 'UTF-8', true));?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['text_value']->value;?>
<?php }?><?php }else{ ?><i class="fas fa-home"></i><?php }?></h3><?php if ($_smarty_tpl->tpl_vars['position']->value==='header'){?></a></div><?php }?><?php }elseif($_smarty_tpl->tpl_vars['logo']->value['mode']==='image'&&!empty($_smarty_tpl->tpl_vars['logo']->value['image']['thumbs'])){?><?php $_smarty_tpl->tpl_vars['logo_url_1x'] = new Smarty_variable(($_smarty_tpl->tpl_vars['logo']->value['image']['thumbs']['64x64']['url']).($_smarty_tpl->tpl_vars['wa']->value->version(true)), null, 0);?><?php $_smarty_tpl->tpl_vars['logo_url_2x'] = new Smarty_variable(($_smarty_tpl->tpl_vars['logo']->value['image']['thumbs']['128x128']['url']).($_smarty_tpl->tpl_vars['wa']->value->version(true)), null, 0);?><?php $_smarty_tpl->tpl_vars['logo_url_3x'] = new Smarty_variable(($_smarty_tpl->tpl_vars['logo']->value['image']['thumbs']['192x192']['url']).($_smarty_tpl->tpl_vars['wa']->value->version(true)), null, 0);?><?php $_smarty_tpl->tpl_vars['logo_url_sidebar'] = new Smarty_variable(($_smarty_tpl->tpl_vars['logo']->value['image']['thumbs']['512x512']['url']).($_smarty_tpl->tpl_vars['wa']->value->version(true)), null, 0);?><div id="wa-account"<?php if ($_smarty_tpl->tpl_vars['position']->value==='sidebar'){?> class="wa-sidebar-logo"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
"><img<?php if ($_smarty_tpl->tpl_vars['position']->value==='sidebar'){?> src="<?php echo $_smarty_tpl->tpl_vars['logo_url_sidebar']->value;?>
"<?php }else{ ?> src="<?php echo $_smarty_tpl->tpl_vars['logo_url_2x']->value;?>
" srcset="<?php echo $_smarty_tpl->tpl_vars['logo_url_2x']->value;?>
 1x, <?php echo $_smarty_tpl->tpl_vars['logo_url_3x']->value;?>
 2x"<?php }?> class="wa-header-logo" alt="<?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['company_name']->value,2,'');?>
"></a></div><?php }?>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:41:49
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/dashboard/DashboardAnnouncement.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c825dc03962_60268132')) {function content_6a8c825dc03962_60268132($_smarty_tpl) {?><div class="alert-fixed-box large d-notification-wrapper js-notification-wrapper hide-scrollbar<?php if ($_smarty_tpl->tpl_vars['has_new_notifications']->value&&$_smarty_tpl->tpl_vars['has_old_notifications']->value){?> visible-only-unread<?php }?>"
    id="d-notification-wrapper"
    style="display: none;"
>
    <style>
        #wa-header .visible-only-unread > .wa-notification:not(.is-unread-group),
        #wa-header .visible-only-unread > .wa-notification ul.list li:not(.is-unread) { display: none; }
        #wa-header .wa-notification {  border-radius: 1rem; }
        #wa-header .wa-notification.alert { margin-bottom: 1rem; }
        #wa-header .wa-notification > .wa-notification-body { position: relative; padding: 0.75rem 2.25rem 1rem 1rem; }
        #wa-header .wa-notification ul.list li { display: flex; gap: 0.5rem; margin-top: 1rem; }
        #wa-header .wa-notification ul.list li:first-child,
        #wa-header .wa-notification ul.list li:not(.is-unread) + li.is-unread { margin-top: 0.25rem; }
        #wa-header .wa-notification .wa-announcement-close { position: absolute; top: 0.6125rem; right: 0.85rem; font-size: 0.9375rem; }
        #wa-header .wa-notification .wa-notification-content > p { margin: 0.5rem 0 0; }
        #wa-header .wa-notification .wa-notification-content > p:first-child { display: inline; margin-top: 0; }
        #wa-header .wa-notification .wa-notification-count { display: none; }

        #wa-header .wa-notification.is-group:not(.is-unread-group) { cursor: pointer; box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2), 0 0.5rem 0.5rem -0.75rem rgba(0, 0, 0, 0.33), 0 14px 0 -8px var(--notification-background-color), 0px 14px 10px -8px rgb(0,0,0,0.14); }
        #wa-header .wa-notification.is-group:not(.is-unread-group) ul.list li:not(:first-child) { display: none; }
        #wa-header .wa-notification.is-group:not(.is-unread-group) .wa-notification-count { display: inline-block; }

        #wa-header .alert-fixed-box { top: 4rem; max-height: calc(100% - 8rem); overflow-y: auto; padding: 1rem 1.5rem; }
        #wa-header .alert-fixed-box.visible-only-unread .wa-notification-bottom-load-more { display: none; }
        #wa-header .alert-fixed-box .wa-notification-bottom { display: flex; justify-content: center; height: 1.75rem; }
        #wa-header .alert-fixed-box .wa-notification-bottom-button { position: relative; -webkit-backdrop-filter: blur(0.35rem); backdrop-filter: blur(0.35rem); }
        @media screen and (max-width: 760px) {
            #wa-header .alert-fixed-box { right: -0.5rem; }
        }
    </style>
    <?php if (!empty($_smarty_tpl->tpl_vars['notifications']->value)){?>
        <?php /*  Call merged included template "./DashboardAnnouncementGroupList.inc.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./DashboardAnnouncementGroupList.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('notifications'=>$_smarty_tpl->tpl_vars['notifications']->value,'has_new_notifications'=>$_smarty_tpl->tpl_vars['has_new_notifications']->value,'new_notification_group_id_to_id'=>$_smarty_tpl->tpl_vars['new_notification_group_id_to_id']->value), 0, '16777409056a8c825dbb8c42-33814113');
content_6a8c825dc096f6_73514008($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./DashboardAnnouncementGroupList.inc.html" */?>
        <?php if ($_smarty_tpl->tpl_vars['has_new_notifications']->value&&$_smarty_tpl->tpl_vars['has_old_notifications']->value){?>
            <div class="wa-notification-bottom">
                <button type="button"
                    id="js-show-all-notifications"
                    class="wa-notification-bottom-button button light-gray rounded small"
                >Показать еще</button>
            </div>
        <?php }?>
        <script>
            $(function () {
                $('[data-wa-tooltip-content]').waTooltip();

                $('.js-announcement-group').on('click', function () {
                    $(this).removeClass('is-group')
                });

                $('#js-show-all-notifications').on('click', function () {
                    $('.js-notification-wrapper').removeClass('visible-only-unread');
                    $('.js-announcement-group').removeClass(['is-unread-group', 'is-group']);
                    $('.js-announcement-group .is-unread').removeClass('is-unread');
                    $(this).parent().remove();
                    if (window.WaBellAnnouncement && typeof WaBellAnnouncement.setSeen === 'function') {
                        WaBellAnnouncement.setSeen();
                    }
                });
            })
        </script>
    <?php }?>
    <div class="alert wa-notification wa-notification-empty"<?php if (!empty($_smarty_tpl->tpl_vars['notifications']->value)){?> style="display: none;"<?php }?>>
        <div class="wa-notification-body-empty">
            Новых уведомлений нет.
        </div>
    </div>

    <?php if ($_smarty_tpl->tpl_vars['notifications_load_more_url']->value){?>
        <div class="wa-notification-bottom wa-notification-bottom-load-more">
            <button type="button" data-load-more-url="<?php echo $_smarty_tpl->tpl_vars['notifications_load_more_url']->value;?>
" class="js-load-chunk-closed-notifications wa-notification-bottom-button button light-gray rounded small">Показать прочитанные</button>
            <div class="js-lazyloading-spinner spinner wa-notification-bottom-button custom-p-4 hidden"></div>
        </div>
        <script>
            $(function () {
                "use strict";
                const insertGroups = ($html) => {
                    const $loaded_groups = $html.find('.js-announcement-group');
                    if (!$loaded_groups.length) {
                        return;
                    }
                    $loaded_groups.find('.js-announcement-close').remove();

                    const $exists_groups = $('#d-notification-wrapper .alert.wa-notification');
                    if ($exists_groups.filter('.wa-notification-empty:visible').length) {
                        $loaded_groups.insertAfter($exists_groups);
                        $exists_groups.remove();
                    } else if ($exists_groups.length > 0) {
                        $loaded_groups.each(function () {
                            const $loaded_group = $(this);
                            const app_id = $loaded_group.data('app-id');
                            let $group_for_updating = $exists_groups.filter('[data-app-id="'+app_id+'"]');
                            if ($group_for_updating.length && !$group_for_updating.hasClass('js-announcement-single')) {
                                const contact_id = $loaded_group.data('contact-id');
                                if (contact_id && $group_for_updating.length > 1) {
                                    $group_for_updating = $group_for_updating.filter('[data-contact-id="'+contact_id+'"]');
                                }
                                $group_for_updating.find('ul.list').append($loaded_group.find('li.js-wa-announcement'));
                            } else {
                                $loaded_group.insertAfter($exists_groups.last());
                            }
                        });
                    } else {
                        $loaded_groups.prependTo($('#d-notification-wrapper'));
                    }

                    $('.js-announcement-group').removeClass('is-group');
                };

                $('.js-load-chunk-closed-notifications').on('click', function () {
                    const $self = $(this);
                    const $loading = $('.js-lazyloading-spinner');
                    const attr_url_key = 'data-load-more-url';
                    const url = $self.attr(attr_url_key);
                    if (!url) {
                        $self.parent().remove();
                        $loading.remove();
                        return false;
                    }

                    $self.addClass('hidden');
                    $loading.removeClass('hidden')
                    const $temp_html = $('<div />');
                    $temp_html.load(url, function () {
                        insertGroups($temp_html);

                        const new_url = $temp_html.children().attr(attr_url_key);
                        $self.attr(attr_url_key, new_url);

                        $temp_html.remove();
                        $('[data-wa-tooltip-content]').waTooltip();

                        if (new_url) {
                            $self.removeClass('hidden');
                            $self.text('Показать еще');
                        } else {
                            $self.parent().remove();
                        }
                        $loading.addClass('hidden');
                    });
                });
            })
        </script>
    <?php }?>
</div>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:41:49
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/dashboard/DashboardAnnouncementGroupList.inc.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c825dc096f6_73514008')) {function content_6a8c825dc096f6_73514008($_smarty_tpl) {?><?php if (!empty($_smarty_tpl->tpl_vars['notifications']->value)){?>
    <?php $_smarty_tpl->tpl_vars['has_some_new_notifications'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['new_notification_group_id_to_id']->value), null, 0);?>
    <?php  $_smarty_tpl->tpl_vars['group_notifications'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group_notifications']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['notifications']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group_notifications']->key => $_smarty_tpl->tpl_vars['group_notifications']->value){
$_smarty_tpl->tpl_vars['group_notifications']->_loop = true;
?>
        <?php $_smarty_tpl->tpl_vars['count_rows'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['group_notifications']->value['rows']) ? count($_smarty_tpl->tpl_vars['group_notifications']->value['rows']) : 0, null, 0);?>

        
        <?php if ($_smarty_tpl->tpl_vars['group_notifications']->value['app_id']==='installer'&&!empty($_smarty_tpl->tpl_vars['group_notifications']->value['is_virtual'])){?>
            <?php echo $_smarty_tpl->tpl_vars['group_notifications']->value['text'];?>

            <?php if ($_smarty_tpl->tpl_vars['count_rows']->value===1){?>
                <?php continue 1?>
            <?php }?>
        <?php }?>

        <div class="alert wa-notification js-announcement-group<?php if ($_smarty_tpl->tpl_vars['count_rows']->value>1){?> is-group<?php }?><?php if ($_smarty_tpl->tpl_vars['has_some_new_notifications']->value&&isset($_smarty_tpl->tpl_vars['new_notification_group_id_to_id']->value[$_smarty_tpl->tpl_vars['group_notifications']->value['id']])){?> is-unread-group<?php }?>"
            data-app-id="<?php echo $_smarty_tpl->tpl_vars['group_notifications']->value['app_id'];?>
"<?php if (!empty($_smarty_tpl->tpl_vars['group_notifications']->value['contact'])){?>
            data-contact-id="<?php echo $_smarty_tpl->tpl_vars['group_notifications']->value['contact']['id'];?>
"<?php }?>
        >
            <div class="wa-notification-body">

                <ul class="list custom-m-0">
                    <?php $_smarty_tpl->tpl_vars['_info'] = new Smarty_variable($_smarty_tpl->tpl_vars['group_notifications']->value['app'], null, 0);?>

                    
                    <?php if (!empty($_smarty_tpl->tpl_vars['_info']->value['version'])){?>
                        <?php $_smarty_tpl->tpl_vars['_version'] = new Smarty_variable("?v=".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['_info']->value['version'], ENT_QUOTES, 'UTF-8', true)), null, 0);?>
                    <?php }else{ ?>
                        <?php $_smarty_tpl->tpl_vars['_version'] = new Smarty_variable(null, null, 0);?>
                    <?php }?>

                    <?php $_smarty_tpl->tpl_vars['_is_contact'] = new Smarty_variable(false, null, 0);?>
                    <?php ob_start();?><?php if (!empty($_smarty_tpl->tpl_vars['_info']->value['icon'][20])){?><?php echo (string)$_smarty_tpl->tpl_vars['_info']->value['icon'][20];?><?php }else{ ?><?php echo (string)$_smarty_tpl->tpl_vars['_info']->value['img'];?><?php }?><?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['_icon_app'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['root_url']->value).$_tmp1.((string)$_smarty_tpl->tpl_vars['_version']->value), null, 0);?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['group_notifications']->value['contact'])){?>
                        <?php $_smarty_tpl->tpl_vars['_icon_app'] = new Smarty_variable($_smarty_tpl->tpl_vars['group_notifications']->value['contact']['photo_url_32'], null, 0);?>
                        <?php $_smarty_tpl->tpl_vars['_is_contact'] = new Smarty_variable(true, null, 0);?>
                    <?php }?>

                    <?php $_smarty_tpl->tpl_vars['_counter'] = new Smarty_variable($_smarty_tpl->tpl_vars['count_rows']->value, null, 0);?>
                    <?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['count_rows']->value-1+1 - (0) : 0-($_smarty_tpl->tpl_vars['count_rows']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
                        <?php $_smarty_tpl->tpl_vars['n'] = new Smarty_variable($_smarty_tpl->tpl_vars['group_notifications']->value['rows'][$_smarty_tpl->tpl_vars['i']->value], null, 0);?>
                        <?php if ($_smarty_tpl->tpl_vars['i']->value===0&&!empty($_smarty_tpl->tpl_vars['n']->value['is_virtual'])){?>
                            <?php $_smarty_tpl->tpl_vars['_counter'] = new Smarty_variable($_smarty_tpl->tpl_vars['_counter']->value-1, null, 0);?>
                            <?php continue 1?>
                        <?php }?>

                        <li class="js-wa-announcement<?php if ($_smarty_tpl->tpl_vars['has_some_new_notifications']->value&&isset($_smarty_tpl->tpl_vars['new_notification_group_id_to_id']->value[$_smarty_tpl->tpl_vars['group_notifications']->value['id']][$_smarty_tpl->tpl_vars['n']->value['id']])){?> is-unread<?php }?>"
                            data-id="<?php echo $_smarty_tpl->tpl_vars['n']->value['id'];?>
"
                        >
                            <span>
                                <img class="icon size-20 <?php if ($_smarty_tpl->tpl_vars['_is_contact']->value){?>userpic userpic-20<?php }?>"
                                    src="<?php echo $_smarty_tpl->tpl_vars['_icon_app']->value;?>
"
                                    data-wa-tooltip-content="<?php if ($_smarty_tpl->tpl_vars['_is_contact']->value){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['group_notifications']->value['contact']['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_info']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"
                                    data-wa-tooltip-placement="right"
                                >
                            </span>

                            <div>
                                <?php $_smarty_tpl->tpl_vars['visible_counter'] = new Smarty_variable($_smarty_tpl->tpl_vars['count_rows']->value>1&&($_smarty_tpl->tpl_vars['i']->value===0||($_smarty_tpl->tpl_vars['i']->value===1&&$_smarty_tpl->tpl_vars['_counter']->value<$_smarty_tpl->tpl_vars['count_rows']->value)), null, 0);?>
                                <?php if ($_smarty_tpl->tpl_vars['visible_counter']->value){?>
                                    <span class="wa-notification-count badge gray bold"><?php echo $_smarty_tpl->tpl_vars['_counter']->value;?>
</span>
                                <?php }?>
                                <span class="wa-notification-pinned<?php if (!$_smarty_tpl->tpl_vars['n']->value['is_pinned']){?> hidden<?php }?>"><i class="fas fa-bolt text-orange"></i></span>
                                <span class="wa-notification-content"><?php echo $_smarty_tpl->tpl_vars['n']->value['text'];?>
</span>
                                <span class="nowrap hint"><?php echo waDateTime::format('humandatetime',$_smarty_tpl->tpl_vars['n']->value['datetime']);?>
</span>
                            </div>
                        </li>
                    <?php }} ?>
                </ul>

                <a data-app-id="<?php echo $_smarty_tpl->tpl_vars['n']->value['app_id'];?>
" href="javascript:void(0);" class="custom-ml-16 custom-py-4 back js-announcement-close wa-announcement-close" title="Пометить как прочитанное"><i class="fas fa-times"></i></a>
            </div>
        </div>
    <?php } ?>
<?php }?>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:41:49
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendHeaderCurrentUser.inc.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c825dc2a0e2_41420231')) {function content_6a8c825dc2a0e2_41420231($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_wa_datetime')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.wa_datetime.php';
?><?php $_smarty_tpl->tpl_vars['calendars'] = new Smarty_variable(empty($_smarty_tpl->tpl_vars['calendars']->value) ? null : $_smarty_tpl->tpl_vars['calendars']->value, null, 0);?>
<?php $_smarty_tpl->tpl_vars['current_status'] = new Smarty_variable(empty($_smarty_tpl->tpl_vars['current_status']->value) ? null : $_smarty_tpl->tpl_vars['current_status']->value, null, 0);?>
<?php $_smarty_tpl->tpl_vars['change_status_access'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->team&&$_smarty_tpl->tpl_vars['calendars']->value, null, 0);?>

<?php if (!function_exists('smarty_template_function_get_icon')) {
    function smarty_template_function_get_icon($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['get_icon']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php $_smarty_tpl->tpl_vars['_icon'] = new Smarty_variable('fas fa-calendar-alt', null, 0);?>
    <?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable('', null, 0);?>
    <?php if ($_smarty_tpl->tpl_vars['status']->value){?>
        <?php if ($_smarty_tpl->tpl_vars['status']->value['id']==='birthday'){?>
            <?php $_smarty_tpl->tpl_vars['_icon'] = new Smarty_variable('fas fa-birthday-cake', null, 0);?>
        <?php }elseif(!empty($_smarty_tpl->tpl_vars['status']->value['icon'])){?>
            <?php ob_start();?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['status']->value['icon'], ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['_icon'] = new Smarty_variable($_tmp1, null, 0);?>
        <?php }?>

        <?php if ($_smarty_tpl->tpl_vars['with_color']->value){?>
            <?php if (!empty($_smarty_tpl->tpl_vars['status']->value['status_bg_color'])){?>
                <?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['status']->value['status_bg_color']).";", null, 0);?>
            <?php }elseif(!empty($_smarty_tpl->tpl_vars['status']->value['bg_color'])){?>
                <?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['status']->value['bg_color']).";", null, 0);?>
            <?php }?>
        <?php }?>
    <?php }?>
    <i class="<?php echo $_smarty_tpl->tpl_vars['_icon']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_style']->value){?> style="<?php echo $_smarty_tpl->tpl_vars['_style']->value;?>
"<?php }?>></i>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<div class="dropdown" id="wa-userprofile" data-user-id="<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['change_status_access']->value){?>data-current-status-id="<?php if ($_smarty_tpl->tpl_vars['current_status']->value){?><?php echo $_smarty_tpl->tpl_vars['current_status']->value['id'];?>
<?php }?>"<?php }?>>
    <a href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
?module=profile" id="js-user-dropdown-toggler" class="dropdown-toggle without-arrow userpic userpic-48" style="overflow: visible;">
        <img src="<?php echo $_smarty_tpl->tpl_vars['user']->value->getPhoto(96);?>
" alt="" class="wa-userpic" title="Мой профиль" />
        <?php if ($_smarty_tpl->tpl_vars['change_status_access']->value){?>
            <?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable('', null, 0);?>
            <?php if ($_smarty_tpl->tpl_vars['current_status']->value){?>
                <?php if (!empty($_smarty_tpl->tpl_vars['current_status']->value['status_bg_color'])){?>
                    <?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable("background-color: ".((string)$_smarty_tpl->tpl_vars['current_status']->value['status_bg_color']).";", null, 0);?>
                <?php }else{ ?>
                    <?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable("background-color: ".((string)$_smarty_tpl->tpl_vars['current_status']->value['bg_color']).";", null, 0);?>
                <?php }?>

                <?php if (!empty($_smarty_tpl->tpl_vars['current_status']->value['status_font_color'])){?>
                    <?php ob_start();?><?php echo ($_smarty_tpl->tpl_vars['_style']->value).("color: ".((string)$_smarty_tpl->tpl_vars['current_status']->value['status_font_color']).";");?>
<?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable($_tmp2, null, 0);?>
                <?php }else{ ?>
                    <?php ob_start();?><?php echo ($_smarty_tpl->tpl_vars['_style']->value).("color: ".((string)$_smarty_tpl->tpl_vars['current_status']->value['font_color']).";");?>
<?php $_tmp3=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable($_tmp3, null, 0);?>
                <?php }?>
            <?php }?>
            <span class="userstatus<?php if (!$_smarty_tpl->tpl_vars['current_status']->value){?> hidden<?php }?>"
                data-wa-tooltip-content="<?php if ($_smarty_tpl->tpl_vars['current_status']->value){?><?php if (!empty($_smarty_tpl->tpl_vars['current_status']->value['summary'])){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['current_status']->value['summary'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['current_status']->value['calendar_name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?><?php }?>"
                data-wa-tooltip-placement="left"
                style="<?php echo $_smarty_tpl->tpl_vars['_style']->value;?>
"
            >
                <?php smarty_template_function_get_icon($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['current_status']->value));?>

            </span>
        <?php }?>
    </a>
    <div class="dropdown-body<?php if (empty($_smarty_tpl->tpl_vars['dropdown_body_left']->value)){?> right<?php }?>">
        <div class="bricks custom-mt-12">
            <a class="brick selected" href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
?module=profile">
                <span class="icon"><i class="userpic size-24" style="background-image: url('<?php echo $_smarty_tpl->tpl_vars['user']->value->getPhoto(96);?>
');"></i></span>
                <span>Профиль</span>
            </a>
            <a href="javascript:void(0)" class="brick selected" data-wa-mode-toggle>
                <span class="icon"><i class="fas fa-adjust"></i></span>
                <span>Свет</span>
            </a>
            <a href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
?action=logout" class="brick selected">
                <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                <span>Выйти</span>
            </a>
        </div>

        <?php if ($_smarty_tpl->tpl_vars['is_user_connected_to_waid']->value){?>
            <div class="custom-pb-12 custom-px-12">
                <a href="https://www.webasyst.ru/my/" target="_blank" class="button white full-width smaller webasyst-id-button"><i class="icon webasyst-magic-wand custom-mr-8"></i>Мой Webasyst ID</a>
            </div>
        <?php }?>

        <?php if ($_smarty_tpl->tpl_vars['change_status_access']->value){?>
            <div class="blank custom-pt-12 custom-pb-4">
                <h5 class="heading flexbox">
                  <span class="wide nowrap"><?php echo smarty_modifier_wa_datetime(time(),'humandate');?>
</span>
                  <a href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
team/calendar/" class="button smaller light-gray rounded bold">Календарь</a>
                </h5>
                <ul id="js-user-dropdown-menu" class="menu custom-mb-0">
                    <li class="<?php if (!$_smarty_tpl->tpl_vars['current_status']->value){?>hidden<?php }?>" data-action-id="delete">
                        <a href="javascript:void(0)">
                            <i class="far fa-circle text-light-gray"></i>
                            <span>Без статуса</span>
                        </a>
                    </li>
                    <?php  $_smarty_tpl->tpl_vars['_c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['calendars']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_c']->key => $_smarty_tpl->tpl_vars['_c']->value){
$_smarty_tpl->tpl_vars['_c']->_loop = true;
?>
                        <?php $_smarty_tpl->tpl_vars['current_calendar_id'] = new Smarty_variable($_smarty_tpl->tpl_vars['current_status']->value ? $_smarty_tpl->tpl_vars['current_status']->value['calendar_id'] : null, null, 0);?>
                        <?php ob_start();?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_c']->value['default_status'], ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp4=ob_get_clean();?><?php ob_start();?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_c']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp5=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['status_name'] = new Smarty_variable($_smarty_tpl->tpl_vars['_c']->value['default_status'] ? $_tmp4 : $_tmp5, null, 0);?>
                        <?php if ($_smarty_tpl->tpl_vars['current_calendar_id']->value===$_smarty_tpl->tpl_vars['_c']->value['id']&&$_smarty_tpl->tpl_vars['current_status']->value['summary']){?>
                            <?php ob_start();?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['current_status']->value['summary'], ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp6=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['status_name'] = new Smarty_variable($_tmp6, null, 0);?>
                        <?php }?>

                        <li class="js-calendar-item<?php if ($_smarty_tpl->tpl_vars['current_calendar_id']->value===$_smarty_tpl->tpl_vars['_c']->value['id']){?> selected<?php }?><?php if (!$_smarty_tpl->tpl_vars['_c']->value['default_status']&&$_smarty_tpl->tpl_vars['current_calendar_id']->value!==$_smarty_tpl->tpl_vars['_c']->value['id']){?> hidden<?php }?>"
                            data-calendar-id="<?php echo $_smarty_tpl->tpl_vars['_c']->value['id'];?>
"
                        >
                            <a href="javascript:void(0)">
                                <?php smarty_template_function_get_icon($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['_c']->value,'with_color'=>true));?>

                                <span><?php echo $_smarty_tpl->tpl_vars['status_name']->value;?>
</span>
                            </a>
                        </li>
                    <?php } ?>
                    <li data-action-id="custom">
                        <a href="javascript:void(0)">
                            <i class="fas fa-ellipsis-h text-light-gray"></i>
                            <span>Другой статус...</span>
                        </a>
                    </li>
                </ul>
            </div>
        <?php }?>
    </div>
</div>
<script>
$(function () {
    /* User Profile Dropdown*/
    const userprofile_options = { hover_out_delay: 200 };
    <?php if ($_smarty_tpl->tpl_vars['wa']->value->isMobile()){?>
        userprofile_options.hover = false;
    <?php }?>
    const $userprofile = $("#wa-userprofile");
    $userprofile.waDropdown(userprofile_options);

    <?php if ($_smarty_tpl->tpl_vars['change_status_access']->value){?>
    /* Change status */
    const calendars_obj = Object.freeze(<?php echo json_encode($_smarty_tpl->tpl_vars['calendars']->value);?>
);
    const url_module = "<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
team/calendar/?module=schedule&action=";
    const $toggler = $('#js-user-dropdown-toggler');
    const $dropdown_menu = $('#js-user-dropdown-menu');
    const $remove_button = $dropdown_menu.find('[data-action-id="delete"]');

    const statusLoading = () => {
        const $userstatus = $toggler.find('.userstatus');
        const $userstatus_svg = $userstatus.find('svg');
        return {
            show: () => {
                $userstatus.append('<span class="js-loading"><i class="fas fa-spinner fa-spin text-white"></i></span>').removeClass('hidden');
                $userstatus_svg.addClass('hidden');
            },
            hide: () => {
                $userstatus.find('.js-loading').remove();
                $userstatus_svg.removeClass('hidden');
            }
        }
    };
    const initTooltip = () => {
        $toggler.find('.userstatus').removeAttr('data-tooltip').waTooltip();
    };
    initTooltip();

    const reRenderItems = (selected_calendar_id = null) => {
        $dropdown_menu.find('[data-calendar-id]').each(function () {
            const $_self = $(this);
            const _calendar_id = $_self.data('calendar-id');

            if (parseInt(_calendar_id) === parseInt(selected_calendar_id)) {
                $_self.removeClass('hidden');
                return;
            }

            const { default_status, name } = calendars_obj[_calendar_id];
            if (!default_status) {
                $_self.addClass('hidden');
            }
            $_self.find('span').text(default_status || name);
        });
    };
    const upsertStatus = (calendar_id, additional_payload = null, callback = null) => {
        const selected_status_id = $userprofile.data('current-status-id');
        const calendar = calendars_obj[calendar_id];

        statusLoading().show();
        const payload = {
            data: {
                summary: calendar.default_status,
                description: '',
                location: '',
                start: '<?php echo date("Y-m-d H",strtotime("+1 hours"));?>
:00:00',
                end: '<?php echo date("Y-m-d H",strtotime("+2 hours"));?>
:00:00',
                is_allday: 1,
                summary_type: 'default',
                calendar_id: calendar_id,
                contact_id: $userprofile.data('user-id'),
                is_status: 1,
                ...(selected_status_id ? { id: selected_status_id } : {}),
                ...(additional_payload && typeof additional_payload === 'object' ? additional_payload : {})
            }
        };
        $.post(url_module + "eventSave", payload, function(response) {
            if (response.status === "ok") {
                const $item = $dropdown_menu.find('[data-calendar-id="'+calendar_id+'"]');
                $item.find('span').text(payload.data.summary);
                $item.addClass('selected').siblings().removeClass('selected');

                $userprofile.data('current-status-id', response.data.id);
                $remove_button.removeClass('hidden');

                const $userstatus = $toggler.find('.userstatus');
                const $userstatus_clone = $userstatus.clone().remove();
                $userstatus_clone
                    .removeClass('hidden')
                    .attr('data-wa-tooltip-content', payload.data.summary)
                    .css('background-color', (calendar.status_bg_color || calendar.bg_color));

                const icon_class = calendar.icon || 'fas fa-calendar-alt';
                $userstatus_clone
                    .empty()
                    .append('<i class="'+icon_class+'" style="color:'+(calendar.status_font_color || calendar.font_color)+'"></i>');

                $toggler.append($userstatus_clone);
                initTooltip();
                reRenderItems(payload.data.calendar_id);

                if (response.data && response.data.message) {
                    alert(response.data.message);
                }

                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    };

    $dropdown_menu.find('[data-calendar-id],[data-action-id]').on('click', function () {
        const $self = $(this);
        if ($self.hasClass('selected')) {
            return true;
        }
        const action_id = $self.data('action-id');
        const calendar_id = $self.data('calendar-id');

        switch (action_id) {
            case 'custom':
                $.waDialog({
                    html: $('#todaystatus-dialog-template').html(),
                    onOpen: function ($d, instance) {
                        const $form = $d.find('form');
                        const $submit = $form.find(':submit');

                        $form.find(':radio').on('change', function () {
                            $submit.prop('disabled', !$(this).is(':checked'));
                        });
                        $form.on('submit', function (e) {
                            e.preventDefault();

                            const form_data = $form.serializeArray().reduce((acc, field) => {
                                acc[field.name] = field.value;
                                return acc;
                            }, {});

                            if (form_data.summary.trim()) {
                                form_data.summary_type = 'custom';
                            } else {
                                const { name, default_status } = calendars_obj[form_data.calendar_id];
                                form_data.summary = default_status || name;
                            }

                            $submit.prepend('<span class="custom-mr-4"><i class="fas fa-spinner fa-spin"></i></span>');
                            upsertStatus(form_data.calendar_id, form_data, () => {
                                instance.close();
                            });
                        });
                    }
                });
                break;

            case 'delete':
                const selected_status_id = $userprofile.data('current-status-id');
                if (!selected_status_id) {
                    break;
                }
                statusLoading().show();
                $.post(url_module + "eventDelete", { id: selected_status_id }, function(response) {
                    if (response.status === "ok") {
                        $toggler.find('.userstatus').addClass('hidden');
                        $userprofile.data('current-status-id', '');
                        $userprofile.find('li[data-calendar-id]').removeClass('selected');
                        $remove_button.addClass('hidden');
                        reRenderItems();
                        if (response.data && response.data.message) {
                            alert(response.data.message);
                        }
                    }
                    statusLoading().hide();
                });
                break;

            default:
                upsertStatus(calendar_id);
                break;
        }
    });
    <?php }?>
})
</script>

<?php if ($_smarty_tpl->tpl_vars['change_status_access']->value){?>
<script id="todaystatus-dialog-template" type="text/html">
    <div class="dialog">
        <div class="dialog-background"></div>
        <form class="dialog-body">
            <header class="dialog-header">
                <h1>Свой статус</h1>
            </header>

            <div class="dialog-content fields form">
                <div class="field">
                    <div class="name nowrap for-input">
                        Статус
                    </div>
                    <div class="value">
                        <input type="text" class="bold" name="summary" placeholder="Введите свой текст"/>
                    </div>
                </div>
                <div class="field">
                    <div class="name for-checkbox">
                        Календарь
                    </div>
                    <div class="value">
                        <?php  $_smarty_tpl->tpl_vars['_c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['calendars']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_c']->key => $_smarty_tpl->tpl_vars['_c']->value){
$_smarty_tpl->tpl_vars['_c']->_loop = true;
?>
                        <div class="custom-mb-8" data-calendar-id="<?php echo $_smarty_tpl->tpl_vars['_c']->value['id'];?>
">
                            <label title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_c']->value['default_status'], ENT_QUOTES, 'UTF-8', true);?>
">
                                <span class="wa-radio">
                                    <input type="radio" name="calendar_id" value="<?php echo $_smarty_tpl->tpl_vars['_c']->value['id'];?>
"/>
                                    <span></span>
                                </span>
                                <?php smarty_template_function_get_icon($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['_c']->value,'with_color'=>true));?>

                                <span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_c']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                            </label>
                        </div>
                        <?php } ?>
                        <p class="hint custom-mt-12">Статус будет автоматически связан с выбранным календарем в приложении «Команда».</p>
                    </div>
                </div>
            </div>

            <footer class="dialog-footer">
                <button type="submit" class="button" disabled>Сохранить</button>
                <button type="button" class="js-close-dialog button light-gray">Отмена</button>
            </footer>
        </form>
    </div>
</script>
<?php }?>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:41:49
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/backend/BackendHeader13.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c825dc5e5e5_65421270')) {function content_6a8c825dc5e5e5_65421270($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
?>
<?php if (!function_exists('smarty_template_function__renderHeaderItem')) {
    function smarty_template_function__renderHeaderItem($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['_renderHeaderItem']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php if (!empty($_smarty_tpl->tpl_vars['_info']->value['app_id'])&&!empty($_smarty_tpl->tpl_vars['_info']->value['link'])){?><?php $_smarty_tpl->tpl_vars['_item_url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['backend_url']->value).((string)$_smarty_tpl->tpl_vars['_info']->value['app_id'])."/".((string)$_smarty_tpl->tpl_vars['_info']->value['link'])."/", null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_item_url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['backend_url']->value).((string)$_smarty_tpl->tpl_vars['_id']->value)."/", null, 0);?><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['_info']->value['version'])){?><?php $_smarty_tpl->tpl_vars['_version'] = new Smarty_variable("?v=".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['_info']->value['version'], ENT_QUOTES, 'UTF-8', true)), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_version'] = new Smarty_variable(null, null, 0);?><?php }?><li id="wa-app-<?php echo str_replace('.','-',$_smarty_tpl->tpl_vars['_id']->value);?>
" data-app="<?php echo $_smarty_tpl->tpl_vars['_id']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['_id']->value==$_smarty_tpl->tpl_vars['current_app']->value||stristr($_smarty_tpl->tpl_vars['request_uri']->value,$_smarty_tpl->tpl_vars['_item_url']->value)!==false){?> class="selected"<?php }?>><?php $_smarty_tpl->tpl_vars['_count'] = new Smarty_variable(null, null, 0);?><?php if ($_smarty_tpl->tpl_vars['counts']->value&&isset($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value])){?><?php if (is_array($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value])){?><?php $_smarty_tpl->tpl_vars['_item_url'] = new Smarty_variable($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value]['url'], null, 0);?><?php $_smarty_tpl->tpl_vars['_count'] = new Smarty_variable($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value]['count'], null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_count'] = new Smarty_variable($_smarty_tpl->tpl_vars['counts']->value[$_smarty_tpl->tpl_vars['_id']->value], null, 0);?><?php }?><?php }?><a href="<?php echo $_smarty_tpl->tpl_vars['_item_url']->value;?>
"><?php if (isset($_smarty_tpl->tpl_vars['_info']->value['img'])){?><img<?php if (!empty($_smarty_tpl->tpl_vars['_info']->value['icon'][96])){?> data-src2="<?php echo $_smarty_tpl->tpl_vars['root_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['_info']->value['icon'][96];?>
<?php echo $_smarty_tpl->tpl_vars['_version']->value;?>
"<?php }?> src="<?php echo $_smarty_tpl->tpl_vars['root_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['_info']->value['img'];?>
<?php echo $_smarty_tpl->tpl_vars['_version']->value;?>
" alt="" style="max-width: 48px; max-height: 48px;"><?php }?><?php echo ifempty($_smarty_tpl->tpl_vars['_info']->value['name']);?>
<?php if ($_smarty_tpl->tpl_vars['_count']->value){?><span class="indicator"><?php echo $_smarty_tpl->tpl_vars['_count']->value;?>
</span><?php }?></a></li><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>

<?php if (!empty($_smarty_tpl->tpl_vars['header_top']->value)){?><?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['header_top']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['_']->value;?>
<?php } ?><?php }?><script type="text/javascript">var backend_url = "<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
";var webasyst_id_settings_url = <?php echo json_encode($_smarty_tpl->tpl_vars['webasyst_id_settings_url']->value);?>
;</script><?php if (!empty($_smarty_tpl->tpl_vars['include_wa_push']->value)){?><script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.push.js?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version('webasyst');?>
"></script><?php }?><div id="wa-announcement"><?php if (!empty($_smarty_tpl->tpl_vars['show_connection_banner']->value)){?><div class="js-webasyst-id-announcement js-webasyst-id-auth-announcement js-webasyst-id-connect-announcement w-webasyst-id-banner"><a href="javascript:void(0);" class="wa-announcement-close js-close-webasyst-id-announcement" title="close"  data-name="webasyst_id_announcement_close" rel="webasyst">&times;</a><p><i class="icon16 waid-green"></i><strong>Подключите вход с Webasyst ID</strong> — единый способ авторизации, который объединяет вход в бекенд Webasyst на вашем сайте, Центр заказчика Webasyst, все приложения, сайты и сервисы экосистемы Webasyst.&nbsp;<a href="javascript:void(0)"class="bold js-webasyst-id-connect"><?php echo sprintf('Подключить&nbsp;Webasyst&nbsp;ID на %s — бесплатно и безопасно!',mb_strtoupper(htmlspecialchars((string)$_smarty_tpl->tpl_vars['current_domain']->value, ENT_QUOTES, 'UTF-8', true)));?>
</a>&nbsp;<a href="javascript:void(0);" class="gray js-webasyst-id-helplink">Как это работает?</a></p></div><?php }elseif(!empty($_smarty_tpl->tpl_vars['webasyst_id_auth_banner']->value)){?><div class="js-webasyst-id-announcement js-webasyst-id-auth-announcement js-webasyst-id-auth-announcement w-webasyst-id-banner"><a href="javascript:void(0);" class="wa-announcement-close js-close-webasyst-id-announcement" title="close"  data-name="webasyst_id_announcement_close" rel="webasyst">&times;</a><p><i class="icon16 waid-green"></i> <strong>Подключите безопасный вход с двухфакторной аутентификацией (2FA).</strong> Ваш аккаунт будет подключен к Webasyst ID, и все попытки входа с новых устройств будут защищены подтверждением по SMS.<br><input type="tel" placeholder="+7" class="bold" style="font-size:15px;margin-top:10px;margin-right:10px;" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['webasyst_id_auth_banner']->value['phone'])===null||$tmp==='' ? '+7' : $tmp);?>
"><a href="javascript:void(0);" class="bold js-waid-link-phone" style="margin-right:10px;">Подключить</a><a href="javascript:void(0);" class="gray js-webasyst-id-helplink">Как это работает?</a><script>const $link_phone_alert = $('.js-webasyst-id-auth-announcement');$link_phone_alert.on('click', '.js-waid-link-phone', function (e) {e.preventDefault();const phone_number = $link_phone_alert.find('input').val();let phone_param = '';if (phone_number) {phone_param = '&phone=' + phone_number.replace(/[^-0-9\s.():+]/g,'')}const referrer_url = window.location.href;window.location.replace('<?php echo $_smarty_tpl->tpl_vars['webasyst_id_auth_banner']->value['url'];?>
&referrer_url=' + encodeURIComponent(referrer_url) + phone_param);});</script></p></div><?php }?></div><div id="wa-header"><div id="wa-account"><?php if ($_smarty_tpl->tpl_vars['request_uri']->value==$_smarty_tpl->tpl_vars['backend_url']->value||$_smarty_tpl->tpl_vars['request_uri']->value==((string)$_smarty_tpl->tpl_vars['backend_url']->value)."/"){?><h3 title="<?php echo $_smarty_tpl->tpl_vars['company_name']->value;?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['company_name']->value,18,'...');?>
<a href="<?php echo $_smarty_tpl->tpl_vars['company_url']->value;?>
" class="wa-frontend-link" target="_blank"><i class="icon16 new-window"></i></a></h3><a class="inline-link" id="show-dashboard-editable-mode" href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
"><b><i>Настроить виджеты</i></b></a><input id="close-dashboard-editable-mode" type="button" value="Готово" style="display: none;"><?php }else{ ?><a href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
" class="wa-dashboard-link"><h3 title="<?php echo $_smarty_tpl->tpl_vars['company_name']->value;?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['company_name']->value,18,'...');?>
</h3><span class="gray"><?php echo $_smarty_tpl->tpl_vars['date']->value;?>
</span></a><?php }?></div><div id="wa-usercorner" data-user-id="<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
"><div class="profile image32px"><div class="image"><a href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
?module=profile"><img width="32" height="32" src="<?php echo $_smarty_tpl->tpl_vars['user']->value->getPhoto(32);?>
" alt=""></a></div><div class="details"><a href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
?module=profile" id="wa-my-username"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value->getName(), ENT_QUOTES, 'UTF-8', true);?>
</a><p class="status"></p><?php if (ifset($_smarty_tpl->tpl_vars['app_info']->value,'ui','1.3')==='1.3,2.0'){?><a href="javascript:void(0);" style="background: #09f; border-radius: 8px; font-size: 12px; color: #fff; padding: 1px 4px; font-weight: bold; margin-right: 5px; white-space: nowrap;" onClick="_setCookie('force_set_wa_backend_ui_version', '2.0', 365); window.location.reload();" title="Включить новый интерфейс">Включить 2.0</a><?php }?><a class="hint" href="<?php echo $_smarty_tpl->tpl_vars['backend_url']->value;?>
?action=logout">выйти</a></div></div></div><div id="wa-applist"<?php if (is_array($_smarty_tpl->tpl_vars['counts']->value)){?> class="counts-cached"<?php }?>><ul><?php  $_smarty_tpl->tpl_vars['_info'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_info']->_loop = false;
 $_smarty_tpl->tpl_vars['_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['header_items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_info']->key => $_smarty_tpl->tpl_vars['_info']->value){
$_smarty_tpl->tpl_vars['_info']->_loop = true;
 $_smarty_tpl->tpl_vars['_id']->value = $_smarty_tpl->tpl_vars['_info']->key;
?><?php smarty_template_function__renderHeaderItem($_smarty_tpl,array('_id'=>$_smarty_tpl->tpl_vars['_id']->value,'_info'=>$_smarty_tpl->tpl_vars['_info']->value));?>
<?php } ?><li><a href="#" id="wa-moreapps"></a></li></ul>

        <?php if ($_smarty_tpl->tpl_vars['request_uri']->value==$_smarty_tpl->tpl_vars['backend_url']->value||$_smarty_tpl->tpl_vars['request_uri']->value==((string)$_smarty_tpl->tpl_vars['backend_url']->value)."/"){?>
            <div class="d-dashboard-header-content">
                <div class="d-dashboards-list-wrapper" id="d-dashboards-list-wrapper"></div>
                <div class="d-dashboard-link-wrapper" id="d-dashboard-link-wrapper">
                    <i class="icon10 lock-bw"></i> Этот дашборд видите только вы.
                </div>
            </div>
        <?php }?>
    </div>
</div>
<script id="wa-header-js" type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['root_url']->value;?>
wa-content/js/jquery-wa/wa.header-legacy.js?<?php echo $_smarty_tpl->tpl_vars['wa_version']->value;?>
"<?php if (!$_smarty_tpl->tpl_vars['user']->value['timezone']){?> data-determine-timezone="1"<?php }?>></script>


<?php if (!empty($_smarty_tpl->tpl_vars['header_middle']->value)){?><?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['header_middle']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['_']->value;?>
<?php } ?><?php }?>


<?php if (!empty($_smarty_tpl->tpl_vars['header_bottom']->value)){?><?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['header_bottom']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo $_smarty_tpl->tpl_vars['_']->value;?>
<?php } ?><?php }?>

<?php }} ?>