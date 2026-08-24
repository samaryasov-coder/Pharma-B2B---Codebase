<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/map/MapOverviewTree.inc.html" */ ?>
<?php /*%%SmartyHeaderCode:18750821996a889cef87b613-06108808%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '772a21cfeabba2d57913843ce9ffbcac33aa4f01' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/map/MapOverviewTree.inc.html',
      1 => 1783409806,
      2 => 'file',
    ),
    '1afbae1badf5070909a6f4d14220b2908d34ddfc' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/map/MapOverviewAlerts.inc.html',
      1 => 1782814434,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18750821996a889cef87b613-06108808',
  'function' => 
  array (
    'row_newposition' => 
    array (
      'parameter' => 
      array (
        'row' => 
        array (
        ),
        'offset' => NULL,
        '_class' => '',
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'offset' => 0,
    'row' => 0,
    '_class' => 0,
    '_offset' => 0,
    'sidebar_mode' => 0,
    'apps_to_add' => 0,
    'app' => 0,
    'wa_url' => 0,
    'wa' => 0,
    'wa_backend_url' => 0,
    'auth_enabled' => 0,
    'wa_app_url' => 0,
    'domain_id' => 0,
    'table_rows' => 0,
    'p' => 0,
    'is_misconfigured_settlement' => 0,
    'route' => 0,
    'app_disabled' => 0,
    'is_htmleditor' => 0,
    'app_id' => 0,
    'page_id' => 0,
    'is_main' => 0,
    'show_over_another_section' => 0,
    'is_parent' => 0,
    'is_hidden_route' => 0,
    'unpublished_page' => 0,
    'is_broken_route_url' => 0,
    'frontend_link' => 0,
    '_main_page_icon' => 0,
    'n' => 0,
    'c_page_id' => 0,
    'page_status' => 0,
    'page_is_draft' => 0,
    'app_hashes' => 0,
    'preview_url_param' => 0,
    'disable_set_main_page' => 0,
    'is_premium' => 0,
    'js_settings_link_class' => 0,
    '_next_offset' => 0,
    'i' => 0,
    'auth_link' => 0,
    'auth_route' => 0,
    'profile_disabled' => 0,
    'auth_apps' => 0,
    '_disabled' => 0,
    'wa_static_url' => 0,
    '_route_name' => 0,
    'domain' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889cef929838_84620413',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889cef929838_84620413')) {function content_6a889cef929838_84620413($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
?>
<?php if (!function_exists('smarty_template_function_row_newposition')) {
    function smarty_template_function_row_newposition($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['row_newposition']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
<?php $_smarty_tpl->tpl_vars['_offset'] = new Smarty_variable((($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['offset']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['row']->value['offset_level'] : $tmp))===null||$tmp==='' ? null : $tmp), null, 0);?>
<tr class="drag-newposition<?php if ($_smarty_tpl->tpl_vars['_class']->value){?> <?php echo trim($_smarty_tpl->tpl_vars['_class']->value);?>
<?php }?>"
    data-app-id="<?php echo htmlspecialchars((string)ifset($_smarty_tpl->tpl_vars['row']->value,'app_id',ifset($_smarty_tpl->tpl_vars['row']->value,'app','id','')), ENT_QUOTES, 'UTF-8', true);?>
"
    data-row-type="<?php echo ifset($_smarty_tpl->tpl_vars['row']->value,'row_type','');?>
"
    <?php if (empty($_smarty_tpl->tpl_vars['_offset']->value)){?>data-root="1"<?php }else{ ?>data-offset="<?php echo $_smarty_tpl->tpl_vars['_offset']->value;?>
"<?php }?>
>
    <td <?php if (!empty($_smarty_tpl->tpl_vars['_offset']->value)){?>style="padding-left: <?php echo $_smarty_tpl->tpl_vars['_offset']->value*2;?>
em;"<?php }?>><div></div></td>
    <td colspan="3"><div></div></td>
    <td style="display: none;">pos: <?php echo $_smarty_tpl->tpl_vars['_offset']->value;?>
</td>
</tr>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', "_main_page_icon", null); ob_start(); ?>
<span class="s-main-page-icon"><i class="fas fa-home"></i></span>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->tpl_vars['is_premium'] = new Smarty_variable(waLicensing::check('site')->isPremium(), null, 0);?>

<div class="site-map flexbox vertical s-map-page" id="s-map-page">
    <div class="site-map-toolbar-sticky flexbox full-width <?php if (!empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?>custom-pt-12 custom-px-16 custom-mb-20<?php }else{ ?>custom-mb-24<?php }?>">
        <div class="dropdown map-page-dropdown" id="js-dropdown-add">
            <button class="dropdown-toggle button small blue">
                <i class="fas fa-plus"></i>
                Добавить
            </button>
            <div class="dropdown-body custom-mt-2">
                <ul class="menu">
                    <li>
                        <a href="javascript:void(0);" id="js-add-blockpage">
                            <i class="fas fa-layer-group"></i>
                            <span class="nowrap">
                                <span class="custom-mr-4">Страницу-конструктор</span>
                                <span class="badge green smaller">Новое</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" id="js-add-old-page" data-app-id="site">
                            <i class="fas fa-file-alt"></i>
                            <span>Страницу</span>
                        </a>
                    </li>
                    
                    <div>
                        <span class="uppercase bold text-gray custom-py-8 custom-px-12 small">Раздел</span>
                    </div>
                    <?php  $_smarty_tpl->tpl_vars['app'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['app']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['apps_to_add']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['app']->key => $_smarty_tpl->tpl_vars['app']->value){
$_smarty_tpl->tpl_vars['app']->_loop = true;
?>
                        <?php if ($_smarty_tpl->tpl_vars['app']->value['id']!='site'){?>
                        <li>
                            <a href="javascript:void(0);" class="js-add-app" data-app-id="<?php echo $_smarty_tpl->tpl_vars['app']->value['id'];?>
">
                                <i class="icon"><img src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['app']->value['icon'][24];?>
" alt=""></i>
                                <span><?php echo $_smarty_tpl->tpl_vars['app']->value['name'];?>
</span>
                            </a>
                        </li>
                        <?php }?>
                    <?php }
if (!$_smarty_tpl->tpl_vars['app']->_loop) {
?>
                        <div class="gray custom-py-8 custom-px-12">
                            <?php if (!$_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin('installer')||$_smarty_tpl->tpl_vars['wa']->value->isSingleAppMode()){?>
                                Приложения для добавления разделов не установлены.
                            <?php }else{ ?>
                                <?php echo sprintf_wp('To add sections, <%s>install apps<%s>',sprintf('a href="%s" class="text-dark-gray underline" target="_blank"',((string)$_smarty_tpl->tpl_vars['wa_backend_url']->value)."installer/store/?filters%5Btype%5D=app"),'/a');?>
 <i class="fas fa-external-link-alt text-dark-gray fa-sm"></i>
                            <?php }?>
                        </div>
                    <?php } ?>
                    <div>
                        <hr>
                    </div>
                    <li>
                        <a <?php if ($_smarty_tpl->tpl_vars['auth_enabled']->value){?>class="s-disabled"<?php }?> href="javascript:void(0);" id="js-add-personal">
                            <i class="fas fa-key"></i>
                            <span>Личный кабинет <i class="fas fa-check custom-ml-4 s-icon-active"></i></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php if (empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?>
            
            <div class="switch-with-text">
                <span class="switch smaller" id="enable-flat-switch">
                    <input type="checkbox" id="enable-flat">
                </span>
                <label for="enable-flat">Режим маршрутизации</label>
                <script>
                (function($) {
                    const switchTo = () => {
                        const href = '<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
map/overview/?domain_id=<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
&routing=1';
                        if (window.history) {
                            history.replaceState(history.state, document.title, href);
                            $.site.reload();
                        } else {
                            location.href = href;
                        }
                    };
                    $('#enable-flat-switch').waSwitch({
                        change: (is_active, instance) => {
                            if (!is_active) return;
                            switchTo();
                        }
                    });
                })(jQuery);
                </script>
            </div>
        <?php }?>
    </div>

    <?php /*  Call merged included template "./MapOverviewAlerts.inc.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./MapOverviewAlerts.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '18750821996a889cef87b613-06108808');
content_6a889cef8914a5_37656285($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./MapOverviewAlerts.inc.html" */?>

        <table class="site-map-tree-table borderless single-lined blank<?php if (!empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?> custom-mt-0<?php }?>">
            <tbody>
            <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['n'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['table_rows']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['row']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['n']->value = $_smarty_tpl->tpl_vars['row']->key;
 $_smarty_tpl->tpl_vars['row']->index++;
 $_smarty_tpl->tpl_vars['row']->first = $_smarty_tpl->tpl_vars['row']->index === 0;
?>
                <?php $_smarty_tpl->tpl_vars['p'] = new Smarty_variable($_smarty_tpl->tpl_vars['row']->value, null, 0);?>

                <?php $_smarty_tpl->tpl_vars['c_page_id'] = new Smarty_variable(0, null, 0);?>
                <?php if (!empty($_smarty_tpl->tpl_vars['p']->value['id'])){?>
                    <?php $_smarty_tpl->tpl_vars['c_page_id'] = new Smarty_variable($_smarty_tpl->tpl_vars['p']->value['id'], null, 0);?>
                <?php }elseif(!empty($_smarty_tpl->tpl_vars['p']->value['route_id'])){?>
                    <?php $_smarty_tpl->tpl_vars['c_page_id'] = new Smarty_variable($_smarty_tpl->tpl_vars['p']->value['route_id'], null, 0);?>
                <?php }?>

                <?php if (!$_smarty_tpl->tpl_vars['row']->first){?>
                    <?php smarty_template_function_row_newposition($_smarty_tpl,array('row'=>$_smarty_tpl->tpl_vars['row']->value));?>

                <?php }?>

                <?php $_smarty_tpl->tpl_vars['frontend_link'] = new Smarty_variable($_smarty_tpl->tpl_vars['p']->value['frontend_link'], null, 0);?>
                <?php $_smarty_tpl->tpl_vars['js_settings_link_class'] = new Smarty_variable('', null, 0);?>
                <?php $_smarty_tpl->tpl_vars['app_disabled'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['row']->value['app']['disabled']), null, 0);?>
                <?php $_smarty_tpl->tpl_vars['is_misconfigured_settlement'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['row']->value['misconfigured_settlement']), null, 0);?>
                <?php $_smarty_tpl->tpl_vars['is_main'] = new Smarty_variable(!$_smarty_tpl->tpl_vars['is_misconfigured_settlement']->value&&!empty($_smarty_tpl->tpl_vars['row']->value['is_main']), null, 0);?>
                <?php $_smarty_tpl->tpl_vars['is_parent'] = new Smarty_variable(empty($_smarty_tpl->tpl_vars['row']->value['offset_level']), null, 0);?>
                <?php $_smarty_tpl->tpl_vars['disable_set_main_page'] = new Smarty_variable(false, null, 0);?>
                <?php $_smarty_tpl->tpl_vars['app_id'] = new Smarty_variable(null, null, 0);?>
                <?php $_smarty_tpl->tpl_vars['route'] = new Smarty_variable(array(), null, 0);?>
                <?php if ($_smarty_tpl->tpl_vars['row']->value['row_type']==='route_app'){?>
                    <?php $_smarty_tpl->tpl_vars['route'] = new Smarty_variable($_smarty_tpl->tpl_vars['row']->value, null, 0);?>
                    <?php $_smarty_tpl->tpl_vars['is_broken_route_url'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['row']->value['is_broken_route_url'])&&$_smarty_tpl->tpl_vars['row']->value['is_broken_route_url'], null, 0);?>
                    <?php $_smarty_tpl->tpl_vars['is_hidden_route'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['route']->value['private'])&&$_smarty_tpl->tpl_vars['route']->value['private']||!empty($_smarty_tpl->tpl_vars['route']->value['disabled']), null, 0);?>
                    <?php $_smarty_tpl->tpl_vars['unpublished_page'] = new Smarty_variable((!empty($_smarty_tpl->tpl_vars['route']->value['app'])&&!is_array($_smarty_tpl->tpl_vars['route']->value['app']))||(!empty($_smarty_tpl->tpl_vars['route']->value['root_page']['id'])&&$_smarty_tpl->tpl_vars['route']->value['root_page']['status']=='0'), null, 0);?>
                    <?php $_smarty_tpl->tpl_vars['app_id'] = new Smarty_variable(ifset($_smarty_tpl->tpl_vars['route']->value,'app','id',''), null, 0);?>
                    <?php $_smarty_tpl->tpl_vars['show_over_another_section'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['route']->value['show_over_another_section']), null, 0);?>
                    <tr
                        class="dr<?php if ($_smarty_tpl->tpl_vars['app_disabled']->value){?><?php echo " ";?>
disable-link<?php }?><?php if ($_smarty_tpl->tpl_vars['is_htmleditor']->value&&!empty($_smarty_tpl->tpl_vars['route']->value['root_page']['id'])&&$_smarty_tpl->tpl_vars['app_id']->value=='site'&&$_smarty_tpl->tpl_vars['route']->value['root_page']['id']==$_smarty_tpl->tpl_vars['page_id']->value){?><?php echo " ";?>
selected<?php echo " ";?>
js-current<?php }?><?php if ($_smarty_tpl->tpl_vars['is_main']->value){?><?php echo " ";?>
is-main<?php }?><?php if ($_smarty_tpl->tpl_vars['show_over_another_section']->value){?><?php echo " ";?>
url-overlap<?php }?><?php if ($_smarty_tpl->tpl_vars['show_over_another_section']->value&&empty($_smarty_tpl->tpl_vars['route']->value['show_under_another_section'])){?><?php echo " ";?>
show-over-another-section<?php }?>"
                        data-route-id="<?php echo $_smarty_tpl->tpl_vars['route']->value['route_id'];?>
" <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['root_page']['id'])){?>data-page-id="<?php echo $_smarty_tpl->tpl_vars['route']->value['root_page']['id'];?>
"<?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['app_id']->value=='site'){?>data-html-page="1"<?php }?> data-app-id="<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
"
                        data-row-type="<?php echo $_smarty_tpl->tpl_vars['row']->value['row_type'];?>
"<?php if ($_smarty_tpl->tpl_vars['is_parent']->value){?> data-root<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['route']->value['url'])){?> data-route="<?php echo $_smarty_tpl->tpl_vars['route']->value['url'];?>
"<?php }?>
                    >
                        <td class="nowrap page-name <?php if ($_smarty_tpl->tpl_vars['is_hidden_route']->value||$_smarty_tpl->tpl_vars['app_disabled']->value){?>gray<?php }?>">
                            <div class="flexbox middle space-8 page-name-wrapper">
                                <?php if ($_smarty_tpl->tpl_vars['row']->value['row_type']==='route_app'){?>
                                    <?php if ($_smarty_tpl->tpl_vars['route']->value['app']['id']=='site'){?>
                                        <span class="page-icon"><i class="fas fa-file-alt"></i></span>
                                        <span class="text-ellipsis"><?php echo htmlspecialchars((string)ifset($_smarty_tpl->tpl_vars['route']->value,'_name',ifset($_smarty_tpl->tpl_vars['route']->value['app'],'name',$_smarty_tpl->tpl_vars['route']->value['app']['id'])), ENT_QUOTES, 'UTF-8', true);?>
</span>
                                    <?php }else{ ?>
                                        <span class="page-icon">
                                            <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['app']['icon'])){?>
                                            <img src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['route']->value['app']['icon'][24];?>
" alt="">
                                            <?php }else{ ?>
                                            <i class="fas fa-question"></i>
                                            <?php }?>
                                        </span>
                                        <span class="text-ellipsis">
                                            <?php echo htmlspecialchars((string)ifset($_smarty_tpl->tpl_vars['route']->value,'_name',ifset($_smarty_tpl->tpl_vars['route']->value['app'],'name',$_smarty_tpl->tpl_vars['route']->value['app']['id'])), ENT_QUOTES, 'UTF-8', true);?>

                                        </span>
                                    <?php }?>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['unpublished_page']->value){?>
                                    <span class="icon small disabled-icon custom-mr-8"><i class="fas fa-eye-slash"></i></span>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['app_disabled']->value||$_smarty_tpl->tpl_vars['is_misconfigured_settlement']->value||$_smarty_tpl->tpl_vars['is_broken_route_url']->value){?>
                                    <span class="js-misconfigured-settlement icon small"><i class="fas fa-exclamation-triangle"></i></span>
                                <?php }?>
                            </div>
                        </td>
                        <?php if (1){?>
                        <td class="page-route<?php if ($_smarty_tpl->tpl_vars['app_disabled']->value||$_smarty_tpl->tpl_vars['is_misconfigured_settlement']->value){?> js-misconfigured-settlement strike gray<?php }?>">
                            <div>
                                <a href="javascript:void(<?php echo htmlspecialchars((string)json_encode((($tmp = @$_smarty_tpl->tpl_vars['route']->value['route_id'])===null||$tmp==='' ? 0 : $tmp)), ENT_QUOTES, 'UTF-8', true);?>
)" data-href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['frontend_link']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-url="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['route']->value['url_formatted'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="gray"><?php if ($_smarty_tpl->tpl_vars['is_main']->value){?><?php echo $_smarty_tpl->tpl_vars['_main_page_icon']->value;?>
<?php }else{ ?><?php echo htmlspecialchars((string)smarty_modifier_truncate($_smarty_tpl->tpl_vars['route']->value['url_formatted'],30,'...',false,true), ENT_QUOTES, 'UTF-8', true);?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['show_over_another_section']->value){?><span data-wa-tooltip-content="Страница отображается вместо раздела с таким же адресом. Ее вложенные страницы становятся недоступными.">&nbsp;<i class="fas fa-exclamation-circle text-light-gray"></i></span><?php }?></a>
                                <i class="shortener"></i>
                            </div>
                        </td>
                        <?php }else{ ?>
                        <td class="page-route min-width custom-p-0"></td>
                        <?php }?>
                        <?php $_smarty_tpl->tpl_vars['js_settings_link_class'] = new Smarty_variable('js-section-settings', null, 0);?>
                <?php }elseif($_smarty_tpl->tpl_vars['row']->value['row_type']==='htmlpage'){?>
                    <?php $_smarty_tpl->tpl_vars['app_id'] = new Smarty_variable(ifset($_smarty_tpl->tpl_vars['p']->value,'app_id',''), null, 0);?>
                    <tr class="dr<?php if ($_smarty_tpl->tpl_vars['is_htmleditor']->value&&$_smarty_tpl->tpl_vars['app_id']->value=='site'&&$_smarty_tpl->tpl_vars['p']->value['id']==$_smarty_tpl->tpl_vars['page_id']->value){?> selected js-current<?php }?>" data-page-id="<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
"
                        data-parent-id="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['p']->value['parent_id'])===null||$tmp==='' ? '' : $tmp);?>
" data-parent-route-id="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['p']->value['route_params']['route_id'])===null||$tmp==='' ? '' : $tmp);?>
"
                        data-app-id="<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
"<?php if (!empty($_smarty_tpl->tpl_vars['row']->value['offset_level'])){?> data-offset="<?php echo $_smarty_tpl->tpl_vars['row']->value['offset_level'];?>
"<?php }?>
                        data-html-page="1" data-row-type="<?php echo $_smarty_tpl->tpl_vars['row']->value['row_type'];?>
"
                    >
                        <td class="nowrap page-name<?php if (!empty($_smarty_tpl->tpl_vars['p']->value['private'])&&$_smarty_tpl->tpl_vars['p']->value['private']){?> gray<?php }?>" style="padding-left: <?php echo $_smarty_tpl->tpl_vars['row']->value['offset_level']*2;?>
em">
                            <div class="page-name-wrapper flexbox middle space-8">
                                <span class="page-icon"><i class="fas fa-file-alt"></i></span>
                                <span class="text-ellipsis"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['p']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                                <?php if ($_smarty_tpl->tpl_vars['p']->value['status']=='0'){?>
                                    <span class="icon small disabled-icon custom-mr-8"><i class="fas fa-eye-slash"></i></span>
                                <?php }?>
                            </div>
                        </td>

                        <?php if (1){?>
                        <td class="page-route">
                            <div>
                                <a href="javascript:void(<?php echo htmlspecialchars((string)json_encode((($tmp = @$_smarty_tpl->tpl_vars['row']->value['route_id'])===null||$tmp==='' ? 0 : $tmp)), ENT_QUOTES, 'UTF-8', true);?>
)" data-href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['frontend_link']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-url="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['p']->value['url'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="gray">/<?php if ($_smarty_tpl->tpl_vars['p']->value['url']){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['p']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></a>
                                <i class="shortener"></i>
                            </div>
                        </td>
                        <?php }else{ ?>
                        <td class="page-route min-width custom-p-0"></td>
                        <?php }?>
                        <?php $_smarty_tpl->tpl_vars['js_settings_link_class'] = new Smarty_variable('js-old-page-settings', null, 0);?>

                <?php }elseif($_smarty_tpl->tpl_vars['row']->value['row_type']==='blockpage'){?>
                    <?php $_smarty_tpl->tpl_vars['disable_set_main_page'] = new Smarty_variable($_smarty_tpl->tpl_vars['p']->value['status']!=='final_published', null, 0);?>
                    <?php $_smarty_tpl->tpl_vars['app_id'] = new Smarty_variable('site', null, 0);?>
                    <tr
                        class="dr<?php if (!$_smarty_tpl->tpl_vars['is_htmleditor']->value&&($_smarty_tpl->tpl_vars['p']->value['id']==$_smarty_tpl->tpl_vars['page_id']->value||$_smarty_tpl->tpl_vars['p']->value['id']==waRequest::get('final_page_id'))){?><?php echo " ";?>
selected js-current<?php }?><?php if ($_smarty_tpl->tpl_vars['is_main']->value){?><?php echo " ";?>
is-main<?php }?><?php if (!empty($_smarty_tpl->tpl_vars['p']->value['show_over_another_section'])){?><?php echo " ";?>
url-overlap<?php }?>"
                        data-page-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['p']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
" <?php if (!empty($_smarty_tpl->tpl_vars['row']->value['offset_level'])){?>data-offset="<?php echo $_smarty_tpl->tpl_vars['row']->value['offset_level'];?>
"<?php }?>
                        data-parent-id="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['p']->value['parent_id'])===null||$tmp==='' ? '' : $tmp);?>
"
                        data-row-type="<?php echo $_smarty_tpl->tpl_vars['row']->value['row_type'];?>
"<?php if ($_smarty_tpl->tpl_vars['is_parent']->value){?> data-root<?php }?>
                    >
                        <td class="nowrap page-name"<?php if (!empty($_smarty_tpl->tpl_vars['row']->value['offset_level'])){?> style="padding-left: <?php echo $_smarty_tpl->tpl_vars['row']->value['offset_level']*2;?>
em"<?php }?>>
                            <div class="page-name-wrapper flexbox middle space-8">
                                <span class="page-icon"><i class="fas fa-layer-group"></i></span>
                                <span class="text-ellipsis <?php if ($_smarty_tpl->tpl_vars['p']->value['id']==$_smarty_tpl->tpl_vars['page_id']->value){?>bold<?php }?>"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['p']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                                <?php if ($_smarty_tpl->tpl_vars['p']->value['status']==='final_unpublished'){?>
                                    <span class="icon small disabled-icon custom-mr-8" title="Не опубликовано"><i class="fas fa-eye-slash"></i></span>
                                <?php }elseif(!empty($_smarty_tpl->tpl_vars['p']->value['draft_changed'])){?>
                                    <span class="icon smaller rounded bg-yellow custom-mr-8 custom-mt-2" title="Есть неопубликованные изменения"></span>
                                <?php }?>
                            </div>
                        </td>
                        <?php if (1){?>
                        <td class="page-route">
                            <div>
                                <a href="javascript:void(0)" data-href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['frontend_link']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-url="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['p']->value['url'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" class="gray"><?php if ($_smarty_tpl->tpl_vars['is_main']->value){?><?php echo $_smarty_tpl->tpl_vars['_main_page_icon']->value;?>
<?php }else{ ?>/<?php if ($_smarty_tpl->tpl_vars['p']->value['url']){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['p']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
/<?php }?><?php }?></a>
                                <i class="shortener"></i>
                                <?php if (!empty($_smarty_tpl->tpl_vars['p']->value['show_over_another_section'])){?>
                                    <span data-wa-tooltip-content="Страница отображается вместо раздела с таким же адресом. Ее вложенные страницы становятся недоступными.">
                                        &nbsp;<i class="fas fa-exclamation-circle text-light-gray"></i>
                                    </span>
                                <?php }?>
                            </div>
                        </td>
                        <?php }else{ ?>
                        <td class="page-route min-width custom-p-0"></td>
                        <?php }?>
                        <?php $_smarty_tpl->tpl_vars['js_settings_link_class'] = new Smarty_variable('js-page-settings', null, 0);?>
                <?php }?>
                        <td class="page-actions">
                            <div class="dropdown dropdown-actions-li" id="dropdown-actions-li-<?php echo $_smarty_tpl->tpl_vars['n']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['c_page_id']->value;?>
" style="height: auto;">
                                <a href="javascript:void(0)" class="dropdown-toggle without-arrow text-gray custom-p-0"><i class="icon fas fa-ellipsis-h"></i></a>
                                <div class="dropdown-body right">
                                    <ul class="menu">
                                        <?php if (!$_smarty_tpl->tpl_vars['app_disabled']->value){?>
                                            <li>
                                                <?php $_smarty_tpl->tpl_vars['page_status'] = new Smarty_variable('', null, 0);?>
                                                <?php if ($_smarty_tpl->tpl_vars['row']->value['row_type']==='route_app'){?>
                                                    <?php if ($_smarty_tpl->tpl_vars['app_id']->value==='site'){?>
                                                        <?php $_smarty_tpl->tpl_vars['page_status'] = new Smarty_variable(ifset($_smarty_tpl->tpl_vars['row']->value,'root_page','status',''), null, 0);?>
                                                    <?php }?>
                                                <?php }else{ ?>
                                                    <?php $_smarty_tpl->tpl_vars['page_status'] = new Smarty_variable(ifset($_smarty_tpl->tpl_vars['row']->value,'status',''), null, 0);?>
                                                <?php }?>

                                                <?php $_smarty_tpl->tpl_vars['preview_url_param'] = new Smarty_variable('', null, 0);?>
                                                <?php $_smarty_tpl->tpl_vars['page_is_draft'] = new Smarty_variable($_smarty_tpl->tpl_vars['page_status']->value==='final_unpublished'||$_smarty_tpl->tpl_vars['page_status']->value=='0', null, 0);?>
                                                <?php if ($_smarty_tpl->tpl_vars['page_is_draft']->value&&isset($_smarty_tpl->tpl_vars['app_hashes']->value[$_smarty_tpl->tpl_vars['app_id']->value])){?>
                                                    <?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['row']->value['row_type']==='blockpage'){?><?php echo "preview_hash";?><?php }else{ ?><?php echo "preview";?><?php }?><?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['preview_url_param'] = new Smarty_variable("?".$_tmp1."=".((string)$_smarty_tpl->tpl_vars['app_hashes']->value[$_smarty_tpl->tpl_vars['app_id']->value]), null, 0);?>
                                                <?php }?>
                                                <a href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['frontend_link']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php echo $_smarty_tpl->tpl_vars['preview_url_param']->value;?>
" target="_blank">
                                                    <i class="icon fas fa-globe smaller"></i><?php if ($_smarty_tpl->tpl_vars['page_is_draft']->value){?>Просмотр черновика<?php }else{ ?>Посмотреть на сайте<?php }?> <i class="fas fa-external-link-alt new-window custom-ml-auto"></i>
                                                </a>
                                            </li>
                                            
                                            <?php if ($_smarty_tpl->tpl_vars['app_id']->value==='site'||!empty($_smarty_tpl->tpl_vars['apps_to_add']->value[$_smarty_tpl->tpl_vars['app_id']->value]['pages'])){?>
                                                <li><a class="js-map-add-subpage" href="javascript:void(0)"><i class="icon fas fa-plus"></i>Добавить подстраницу</a></li>
                                            <?php }?>
                                            <?php if (!$_smarty_tpl->tpl_vars['is_main']->value&&$_smarty_tpl->tpl_vars['is_parent']->value){?>
                                                <li><a href="javascript:void(0)" class="js-map-set-main-page<?php if ($_smarty_tpl->tpl_vars['disable_set_main_page']->value){?> s-disabled<?php }?>"<?php if ($_smarty_tpl->tpl_vars['disable_set_main_page']->value){?> title="Главную страницу нельзя сделать неопубликованной. Сделайте главными другой раздел или страницу."<?php }?>><i class="icon fas fa-home"></i>Сделать главной страницей сайта</a></li>
                                            <?php }?>

                                            <?php if ($_smarty_tpl->tpl_vars['row']->value['row_type']==='blockpage'||$_smarty_tpl->tpl_vars['row']->value['row_type']==='htmlpage'||$_smarty_tpl->tpl_vars['row']->value['row_type']==='route_app'){?>
                                                <li><a href="javascript:void(0)" class="js-map-duplicate<?php if (!$_smarty_tpl->tpl_vars['is_premium']->value){?> opacity-60<?php }?>">
                                                    <i class="icon fas fa-copy"></i>Дублировать
                                                    <?php if (!$_smarty_tpl->tpl_vars['is_premium']->value){?> <i class="fas fa-crown text-purple custom-ml-4"></i><?php }?>
                                                </a></li>
                                            <?php }?>
                                        <?php }?>
                                        <li><a class="js-map-delete" href="javascript:void(0)"><i class="icon fas fa-trash-alt"></i>Удалить</a></li>
                                    </ul>
                                </div>

                            </div>
                            <script>
                                (function($) {
                                    const page_settings_skeleton_body = '<div class="skeleton"><span class="skeleton-header"></span><span class="skeleton-line"></span><span class="skeleton-line"></span></div>';
                                    $("#dropdown-actions-li-<?php echo $_smarty_tpl->tpl_vars['n']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['c_page_id']->value;?>
").waDropdown({
                                        hover: false,
                                        /*page_settings_skeleton_body: function(dropdown){
                                            $menu = dropdown.$wrapper.find('.menu');
                                            $menu.on('click', '#s-map-add-inside', function (e) {
                                                setTimeout(() => {
                                                    $("#dropdown-add-inside-<?php echo $_smarty_tpl->tpl_vars['n']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['c_page_id']->value;?>
").find('.dropdown-toggle').trigger('click')
                                                }, 50)
                                            })
                                        },*/
                                    });
                                })(jQuery);
                            </script>
                            
                        </td>

                        <td class="page-settings">
                            <?php if ($_smarty_tpl->tpl_vars['js_settings_link_class']->value){?>
                            <div>
                                <a class="<?php echo $_smarty_tpl->tpl_vars['js_settings_link_class']->value;?>
" href="javascript:void(<?php echo htmlspecialchars((string)json_encode(ifempty($_smarty_tpl->tpl_vars['row']->value,'id',ifset($_smarty_tpl->tpl_vars['route']->value,'root_page','id',0))), ENT_QUOTES, 'UTF-8', true);?>
)"><i class="fas fa-cog"></i></a>
                            </div>
                            <?php }?>
                        </td>
                    </tr>
                    <?php if (!empty($_smarty_tpl->tpl_vars['row']->value['offset_level'])){?>
                        <?php $_smarty_tpl->tpl_vars['_next_offset'] = new Smarty_variable(ifset($_smarty_tpl->tpl_vars['table_rows']->value[$_smarty_tpl->tpl_vars['n']->value+1],'offset_level',0), null, 0);?>
                        <?php if ($_smarty_tpl->tpl_vars['_next_offset']->value<$_smarty_tpl->tpl_vars['row']->value['offset_level']){?>
                            <?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['row']->value['offset_level']+1 - (0) : 0-($_smarty_tpl->tpl_vars['row']->value['offset_level'])+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
                                <?php $_smarty_tpl->tpl_vars['_offset'] = new Smarty_variable($_smarty_tpl->tpl_vars['row']->value['offset_level']-$_smarty_tpl->tpl_vars['i']->value, null, 0);?>
                                <?php if ($_smarty_tpl->tpl_vars['_offset']->value>0&&$_smarty_tpl->tpl_vars['_offset']->value>$_smarty_tpl->tpl_vars['_next_offset']->value){?>
                                    <?php ob_start();?><?php if ($_smarty_tpl->tpl_vars['_offset']->value===1&&!empty($_smarty_tpl->tpl_vars['show_over_another_section']->value)){?><?php echo "show-over-another-section";?><?php }?><?php $_tmp2=ob_get_clean();?><?php smarty_template_function_row_newposition($_smarty_tpl,array('row'=>$_smarty_tpl->tpl_vars['row']->value,'offset'=>$_smarty_tpl->tpl_vars['_offset']->value,'_class'=>$_tmp2));?>

                                <?php }?>
                            <?php }} ?>
                        <?php }?>
                    <?php }elseif($_smarty_tpl->tpl_vars['row']->value['row_type']==='blockpage'&&ifset($_smarty_tpl->tpl_vars['table_rows']->value[$_smarty_tpl->tpl_vars['n']->value+1],'row_type','')!=='blockpage'){?>
                        <?php smarty_template_function_row_newposition($_smarty_tpl,array('row'=>$_smarty_tpl->tpl_vars['row']->value));?>

                    <?php }?>
            <?php } ?>
            <?php if ($_smarty_tpl->tpl_vars['table_rows']->value){?>
                <?php smarty_template_function_row_newposition($_smarty_tpl,array('row'=>end($_smarty_tpl->tpl_vars['table_rows']->value)));?>

            <?php }?>
        </tbody>

        <tbody class="s-personal-pages<?php if ($_smarty_tpl->tpl_vars['auth_enabled']->value){?> s-personal-enabled<?php }?>">
            
            <tr data-personal-id="main" data-enabled="1" data-personal>
                <td class="nowrap page-name js-personal-settings">
                    <div class="flexbox middle space-8">
                        <span class="page-icon"><i class="fas fa-key"></i></span>
                        <span>Личный кабинет</span>
                    </div>
                </td>
                <?php if (0){?>
                <td class="js-personal-settings"><a href="javascript:void(0)" class="black">/my/</a></td>
                <?php }else{ ?>
                <td class="page-route min-width custom-p-0"></td>
                <?php }?>
                <td class="page-link page-actions"><div><a id="js-personal-link" href="<?php echo $_smarty_tpl->tpl_vars['auth_link']->value;?>
" target="_blank" data-route="<?php echo $_smarty_tpl->tpl_vars['auth_route']->value;?>
"><i class="fas fa-external-link-alt fa-sm"></i></a></div></td>
                <td class="page-settings"><div><a class="js-personal-settings" href="javascript:void(0)"><i class="fas fa-cog text-gray"></i></a></div></td>
            </tr>

            <tr class="<?php if (!empty($_smarty_tpl->tpl_vars['profile_disabled']->value)){?>s-disabled<?php }?>" data-personal-id="profile" data-personal>
                <td class="nowrap page-name js-profile-settings">
                    <div class="flexbox middle space-8">
                        <div style="width: 1rem;"></div>
                        <span class="page-icon icon size-18"><i class="fas fa-user-circle"></i></span>
                        <span>Мой профиль</span>
                        <i class="fas fa-ban s-icon-disable"></i>
                    </div>
                </td>
                <?php if (0){?>
                <td class="js-profile-settings"><a href="javascript:void(0)" class="black">/my/profile/</a></td>
                <?php }else{ ?>
                <td class="page-route min-width custom-p-0"></td>
                <?php }?>
                <td class="page-link page-actions"></td>
                <td class="page-settings"><div><a class="js-profile-settings" href="javascript:void(0)"><i class="fas fa-cog text-gray"></i></a></div></td>
            </tr>

            <?php  $_smarty_tpl->tpl_vars['app'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['app']->_loop = false;
 $_smarty_tpl->tpl_vars['app_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['auth_apps']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['app']->key => $_smarty_tpl->tpl_vars['app']->value){
$_smarty_tpl->tpl_vars['app']->_loop = true;
 $_smarty_tpl->tpl_vars['app_id']->value = $_smarty_tpl->tpl_vars['app']->key;
?>
                <?php $_smarty_tpl->tpl_vars['_route_name'] = new Smarty_variable($_smarty_tpl->tpl_vars['app']->value['items'][0], null, 0);?>
                <?php $_smarty_tpl->tpl_vars['_disabled'] = new Smarty_variable($_smarty_tpl->tpl_vars['app']->value['state']==='disabled', null, 0);?>
                <tr class="<?php if ($_smarty_tpl->tpl_vars['_disabled']->value){?>s-disabled<?php }?>" data-app-id="<?php echo $_smarty_tpl->tpl_vars['app']->value['id'];?>
" data-personal data-link="<?php echo $_smarty_tpl->tpl_vars['app']->value['link'];?>
">
                    <td class="nowrap page-name js-personal-app-settings">
                        <div class="flexbox middle space-8">
                            <?php if ($_smarty_tpl->tpl_vars['app']->value['state']==='no_route'||empty($_smarty_tpl->tpl_vars['app']->value['link'])){?>
                                <div style="width: 1rem;"></div>
                            <?php }else{ ?>
                                <div class="js-sort s-sort" style="width: 1rem;"><i class="fas fa-grip-vertical text-light-gray"></i></div>
                            <?php }?>
                            <span class="page-icon icon size-20"><img src="<?php echo $_smarty_tpl->tpl_vars['wa_static_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['app']->value['icon'][24];?>
" alt="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['app']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
"></span>
                            <span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_route_name']->value, ENT_QUOTES, 'UTF-8', true);?>
</span>
                            <i class="fas fa-ban s-icon-disable"></i>
                        </div>
                    </td>
                    <?php if (0){?>
                    <td class="js-personal-app-settings">
                        <?php if (!empty($_smarty_tpl->tpl_vars['app']->value['link'])){?>
                            <a href="javascript:void(0)" class="black" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['app']->value['link_path'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['app']->value['link_path'], ENT_QUOTES, 'UTF-8', true);?>
</a>
                        <?php }?>
                    </td>
                    <?php }else{ ?>
                    <td class="page-route min-width custom-p-0"></td>
                    <?php }?>
                    <td class="page-link page-actions"></td>
                    <td class="page-settings"><div><a class="js-personal-app-settings" href="javascript:void(0)"><i class="fas fa-cog text-gray"></i></a></div></td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>

<script>
(function() { "use strict";
    const site_app_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
;
    const domain_id = <?php echo json_encode($_smarty_tpl->tpl_vars['domain_id']->value);?>
;
    const editor_page_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
 + 'editor/page/%s/?domain_id='+domain_id;
    const editor_html_page_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
 + 'htmleditor/page/%s/?domain_id='+domain_id;
    const $table = $('.site-map-tree-table');
    const $drawer = $('.site-editor-left-drawer:visible');
    if (typeof $.site.loadMap === 'undefined') {
        $.site.loadMap = $.site.reload;
    }

    $("#js-dropdown-add").waDropdown({
        hover: false,
        ready: function(dropdown) {
                const $menu = dropdown.$wrapper.find('.menu');
                $menu.on('click', '.js-add-app,#js-add-old-page', function(e){
                    const params = new URLSearchParams({
                        domain_id: domain_id,
                        app: $(this).data('app-id'),
                    });
                    $.get(site_app_url+'?module=map&action=sectionSettingsDialog&' + params, function(html) {
                        $.waDialog({
                            html: html
                        });
                    });
                    return false;
                })
                
                $menu.on('click', '#js-add-personal', function(e){
                    $.get(site_app_url+'?module=map&action=personalSettingsDialog&domain_id=' + domain_id, function(html) {
                        $.waDialog({
                            html: html,
                        });
                    });
                    return false;
                })
                $menu.on('click', '#js-add-blockpage', function(e){
                    openBlockpageSettings({ is_new: 1 });
                })
            }
    });

    // autoscroll to line in drawer
    <?php if ($_smarty_tpl->tpl_vars['page_id']->value&&waRequest::get('scroll_to')){?>
        const $row = $table.find('.dr.selected');
        if ($row.length) {
            const y = $row.offset().top - $drawer.find('.s-site-header').height() - $row.height();
            $drawer.find('.drawer-content').get(0).scrollTo(0, y);
        }
    <?php }?>

    // Show page settings dialog when user clicks on a cog
    $table.on('click', '.js-personal-settings', function(e) {
        const url = site_app_url+'?module=map&action=personalSettingsDialog&domain_id=' + domain_id;
        $.site.helper.preventDupeRequest(() => {
            return $.get(url, function(html) {
                $.waDialog({
                    html: html,
                });
            });
        }, url);
        return false;
    });
    $table.on('click', '.js-profile-settings', function(e) {
        const url = site_app_url+'?module=map&action=personalProfileDialog&domain_id=' + domain_id;
        $.site.helper.preventDupeRequest(() => {
            return $.get(url, function(html) {
                $.waDialog({
                    html: html,
                });
            });
        }, url);
        return false;
    });
    $table.on('click', '.js-personal-app-settings', function(e) {
        const $tr = $(this).closest('tr');
        const params = new URLSearchParams({
            app_id: $tr.data('app-id'),
            domain_id
        });
        const url = site_app_url+'?module=map&action=personalAppDialog&' + params;

        $.site.helper.preventDupeRequest(() => {
            return $.get(url, function(html) {
                const d = $.waDialog({
                    html: html,
                });
                setTimeout(() => {
                    d.resize();
                });
            });
        }, url);
        return false;
    })

    const onPageDelete = (dialog, callback) => {
        dialog.$wrapper.off('page_deleted').on('page_deleted', function() {
            setTimeout(() => {
                dialog.close();
            }, 100);
            if (typeof callback === 'function') {
                callback();
            }
        });
    };

    function openBlockpageSettings(params, $tr) {
        params = { ...params, domain_id };
        const url = site_app_url+'?module=map&action=pageSettingsDialog&' + new URLSearchParams(params);
        const dfd = $.Deferred();

        $.site.helper.preventDupeRequest(() => {
            return $.get(url, function(html) {
                const dialog = $.waDialog({
                    html: html
                });

                if ($tr) {
                    onPageDelete(dialog, () => { removePagesRecursiveByRow($tr); });
                }

                dfd.resolve(dialog);
            }).fail(function() {
                dfd.reject();
            });
        }, url);

        return dfd.promise();
    }

    $table.on('click', '.js-page-settings', function() {
        const $tr = $(this).closest('tr');
        openBlockpageSettings({ page_id: $tr.data('page-id') }, $tr);
        return false;
    });

    $table.on('click', '.js-old-page-settings', function() {
        const $item = $(this).closest('.dr');
        const params = {
            page_id: $item.data('page-id') || 0,
            parent_id: $item.data('parent-id') || 0,
            app_id: $item.data('app-id') || 'site',
        };

        $.site.helper.preventDupeRequest(() => {
            return htmlPageSettingsDialog(params, $item);
        }, JSON.stringify(params));

        return false;
    });
    function htmlPageSettingsDialog (params, $item) {
        params = params || {};
        const full_params = new URLSearchParams({
            domain_id,
            ...params
        });

        // С одной стороны, нужно вернуть промис с диалогом. С другой - обеспечить возможность .abort()
        // для $.site.helper.preventDupeRequest(). Поэтому такая конструкция.
        const xhr = $.get(site_app_url+'?module=map&action=htmlPageSettingsDialog&' + full_params);
        const promise = xhr.then(function(html) {
            const dialog = $.waDialog({
                html: html
            });
            if ($item) {
                onPageDelete(dialog, () => { removePagesRecursiveByRow($item); });
            }
            return dialog;
        });
        promise.abort = function() {
            xhr.abort();
        };
        return promise;
    }

    function duplicateHtmlPage(app_id, page_id) {
        const dfd = $.Deferred();
        $.post('?module=map&action=duplicate', {
            type: 'htmlpage',
            app: app_id,
            id: page_id
        }, 'json').then(function(data) {
            if (data && data.status == 'ok' && data.data && data.data.id) {
                dfd.resolve(data.data.id);
            } else {
                dfd.reject();
            }
        }, function() {
            dfd.reject();
        });
        return dfd.promise();
    }

    function duplicateBlockPage(page_id) {
        const dfd = $.Deferred();
        $.post('?module=map&action=duplicate', {
            type: 'blockpage',
            id: page_id
        }, 'json').then(function(data) {
            if (data && data.status == 'ok' && data.data && data.data.id) {
                dfd.resolve(data.data.id);
            } else {
                dfd.reject();
            }
        }, function() {
            dfd.reject();
        });
        return dfd.promise();
    }

    function duplicateRoute(route_id) {
        const dfd = $.Deferred();
        $.post('?module=map&action=duplicate', {
            type: 'route',
            domain_id,
            route_id
        }, 'json').then(function(data) {
            if (data && data.status == 'ok' && data.data && data.data.id) {
                dfd.resolve(data.data.id);
            } else {
                dfd.reject();
            }
        }, function() {
            dfd.reject();
        });
        return dfd.promise();
    }

    // Show page settings dialog when user clicks on a cog
    $table.on('click', '.js-section-settings', function() {
        const $tr = $(this).closest('tr');
        const params = new URLSearchParams({
            domain_id: domain_id,
            route: $tr.data('route-id'),
            page: $tr.data('page-id') ?? 0,
            details: 1
        });
        const url = site_app_url+'?module=map&action=sectionSettingsDialog&' + params;

        $.site.helper.preventDupeRequest(() => {
            return $.get(url, function(html) {
                const dialog = $.waDialog({
                    html: html
                });
                onPageDelete(dialog, () => { removePagesRecursiveByRow($tr); });
            });
        }, url);
        return false;
    });

    // When user clicks on "Duplicate" menu item in dropdown
    $table.on('click', '.js-map-duplicate', function (e) {
        <?php if ($_smarty_tpl->tpl_vars['is_premium']->value){?>
        e.preventDefault();

        const highlightCopiedRow = (id, app_id) => {
            $(document).trigger('page_saved.map', [{
                id,
                app_id,
                add: true
            }]);
        };
        const $item = $(this).closest('.dr');
        const app_id = $item.data('app-id');
        if ($item.data('row-type') === 'route_app') {
            duplicateRoute($item.data('route-id')).then(id => highlightCopiedRow(id, app_id));
        } else if ($item.data('row-type') === 'htmlpage') {
            const app_id = $item.data('app-id') || 'site';
            duplicateHtmlPage(app_id, $item.data('page-id')).then(id => highlightCopiedRow(id, app_id));
        } else { // 'blockpage'
            duplicateBlockPage($item.data('page-id')).then(id => highlightCopiedRow(id, app_id));
        }
        <?php }else{ ?>
        $.site.helper.showPremiumDialog();
        <?php }?>
    });

        // Open page when user clicks on a row in table
        $table.find('tbody:first').on('click', '.page-name,.page-route', function(e) {
            const $self = $(this).closest('tr');
            if ($self.hasClass('disable-link')) {
                return false;
            }

            const app_id = $self.data('app-id');

             //for old pages
            if ($self.data('html-page')) {
                if (app_id && app_id !== 'site') { //for old apps pages
                    let page_app_href = '#/pages/';
                    if (app_id === 'shop') {
                        page_app_href = '?action=storefronts#/pages/';
                    }
                    if (app_id === 'blog') {
                        page_app_href = '?module=pages#/';
                    }
                    const app_href = site_app_url.replace('site', app_id) + page_app_href + $self.data('page-id');
                    window.open(app_href, '_blank')
                    return
                }
                window.location.href = editor_html_page_url.replace('%s', $self.data('page-id')); //for old site pages
                return
            }
            //link for Apps
            if (app_id && app_id !== 'site') {
                window.open(site_app_url.replace('site', app_id), '_blank')
                return
            }
            //for block pages
            window.location.href = editor_page_url.replace('%s', $self.data('page-id'));
        });

        $table.on('click', '.js-map-add-subpage', function (e) {
            e.preventDefault();

            const $item = $(this).closest('.dr');
            const row_type = $item.data('row-type');

            const params = {
                app_id: $item.data('app-id') || 'site'
            };
            if (row_type === 'route_app') {
                params.route_id = $item.data('route-id') || '';
            } else {
                params.parent_id = $item.data('page-id') || '';
            }

            if (row_type === 'blockpage') {
                openBlockpageSettings({ ...params, is_new: 1 });
            } else {
                htmlPageSettingsDialog(params);
            }
        });

        $(document).off('personal_account_state_changed.map').on('personal_account_state_changed.map', (_, active) => {
            $table.find('.s-personal-pages').toggleClass('s-personal-enabled', active);
            $('#js-add-personal').toggleClass('s-disabled', active);
            $('[data-personal]', $table).toggleClass('hidden', !active);
        });
        $(document).off('personal_account_change_route.map').on('personal_account_change_route.map', (_, new_route) => {
            const $personal_link = $('#js-personal-link');

            const prev_route_path = $personal_link.data('route').replace(/\/?\*$/, '');
            const new_route_path = new_route.replace(/\/?\*$/, '') ? '/' + new_route.replace(/\/?\*$/, '') : '';
            const domain_url = $personal_link.attr('href').replace(prev_route_path, '').replace(/\/?\/my\//, '');
            $personal_link
                .attr('href', domain_url + new_route_path + '/my/')
                .data('route', new_route).attr('data-route', new_route);
        });
        $(document).off('page_saved.map').one('page_saved.map', (e, data) => {
            if (data.add) {
                $(document).one('wa_loaded', () => {
                    // for settlement page
                    if (data.route_id !== undefined) {
                        const $row = $(`.site-map-tree-table tbody:first [data-route-id="${ data.route_id }"]`);
                        if ($row.length) {
                            $row[0].scrollIntoView({ block: 'center' });
                            $row.addClass('selected');
                        }
                    // for nested page
                    } else if (data.id) {
                        let selector = `.site-map-tree-table tbody:first [data-page-id="${ data.id }"]`;
                        if (data.app_id) {
                            selector += `[data-app-id="${ data.app_id }"][data-html-page]`;
                        } else {
                            selector += '[data-row-type="blockpage"]';
                        }
                        const $row = $(selector);
                        if ($row.length) {
                            $row[0].scrollIntoView({ block: 'center' });
                            $row.addClass('selected');
                        }
                    }
                });
            }
            $.site.loadMap();
        });

        $(document).off('personal_app_state_changed.map').on('personal_app_state_changed.map', (_, { app_id, active }) => {
            $('[data-personal][data-app-id="'+app_id+'"]', $table).toggleClass('s-disabled', !active);
        });

        $table.on('click', '.js-map-set-main-page', function () {
            if ($(this).hasClass('s-disabled')) {
                return false;
            }
            const $item = $(this).closest('.dr');
            const page_id = $item.data('page-id');
            const route_id = $item.data('route-id');
            const app_id = $item.data('app-id');
            const payload = { type: $item.data('row-type') };

            if (app_id !== undefined) { payload.app_id = app_id; }
            if (route_id !== undefined) { payload.route_id = route_id; }
            if (page_id !== undefined) { payload.page_id = page_id; }
            $.post(site_app_url+'?module=map&action=setMainPage&domain_id='+domain_id, payload, function (r) {
                if (r && r.status === 'ok') {
                    $.site.loadMap();
                }
            }, "json");
        });

        $table.on('click', '.js-map-delete', function () {
            const $item = $(this).closest('.dr');

            let is_route = false;
            let delete_url = '';
            let payload = {};
            switch ($item.data('row-type')) {
                case 'route_app':
                    is_route = true;
                    delete_url = '?module=configure&action=redirectDelete&domain_id=' + domain_id;
                    payload = { route: $item.data('route-id') };
                    break;
                case 'blockpage':
                    delete_url = '?module=editor&action=pageDelete&domain_id=' + domain_id;
                    payload = { id: $item.data('page-id') };
                    break;
                case 'htmlpage':
                    delete_url = '?module=htmlPages&action=pageDelete&domain_id=' + domain_id;
                    payload = { id: $item.data('page-id') };
            }

            const app_id = $item.data('app-id');
            if (app_id) {
                delete_url += '&app_id=' + app_id;
            }

            const onSuccess = function (d) {
                const $loading = $('<span><i class="fas fa-spinner fa-spin"></i></span>');
                d.$block.find('.dialog-footer').append($loading);
                payload = Object.assign(payload, { confirm_multiple_delete: 1 });
                $.post(site_app_url + delete_url, payload, function (r) {
                    if (r?.status === 'ok') {
                        removePagesRecursiveByRow($item);
                    }
                }, "json").always(function() {
                    $loading.remove();
                });
            };
            const main_page_alert_msg = $item.closest('.is-main').length ? $_('delete_main_page_alert') : '';
            const is_site = app_id === 'site';
            $.waDialog.confirm({
                title: is_route && !is_site ? $_('delete_route') : $_('delete_page'),
                text: main_page_alert_msg + (is_route && !is_site ? $_('delete_rule_msg') : $_('delete_page_msg')),
                success_button_title: $_('Delete'),
                success_button_class: 'danger',
                cancel_button_title: $_('Cancel'),
                cancel_button_class: 'light-gray',
                onSuccess: function (d) {
                    const cur_offset = $item.data('offset')||0;
                    if (cur_offset < ($item.nextAll('.dr:first').data('offset')||0)) {
                        $.waDialog.confirm({
                            title: $_('delete_nested_pages'),
                            text: is_route && !is_site ? $_('delete_route_with_nested_pages_msg') : $_('delete_page_with_nested_pages_msg'),
                            success_button_title: $_('Delete'),
                            success_button_class: 'danger',
                            cancel_button_title: $_('Cancel'),
                            cancel_button_class: 'light-gray',
                            onSuccess: onSuccess
                        });
                    } else {
                        onSuccess(d);
                    }
                }
            });
        });

        function removePagesRecursiveByRow ($tr) {
            const has_some_page_for_deleting = !!$table.find('.js-current').length;

            const removeDragPos = ($el) => {
                $el.prev('.drag-newposition').remove();
                const prev_offset = $el.prev().data('offset') || 0;
                $el.nextUntil('.dr').slice(0, -1).filter(function () {
                    return ($(this).data('offset')||0) > prev_offset;
                }).remove();
            }
            removeDragPos($tr);

            let page_id = $tr.data('page-id');
            let children_selector = `[data-parent-id="${ page_id }"]`;
            if ($tr.data('row-type') === 'route_app') {
                children_selector = `[data-parent-route-id="${ $tr.data('route-id') }"]`;
            }

            const app_id = $tr.data('app-id');
            const app_id_selector = app_id ? `[data-app-id="${ app_id }"]` : '';
            const removeChildren = (children_selector) => {
                $tr.nextAll(children_selector + app_id_selector).each(function () {
                    const $child = $(this);
                    removeDragPos($child);
                    $child.remove();
                    removeChildren(`[data-parent-id="${ $child.data('page-id') }"]${ app_id_selector }`);
                });
            };
            removeChildren(children_selector);

            $tr.remove();

            // main page is deleted
            if (!$('.site-map-tree-table tbody:first tr.is-main').length) {
                $.site.loadMap();
            }

            if (has_some_page_for_deleting && !$table.find('.js-current').length) {
                location.href = site_app_url + '?module=map&action=overview&domain_id=' + domain_id;
            }
        }

        // hover for td:first-child via td.page-route
        const page_routes = document.querySelectorAll('.site-map-tree-table tbody:first-child .page-route');
        page_routes.forEach(el => {
            el.addEventListener('mouseenter', (e) => {
                el.closest('.dr').querySelector('.page-name').classList.add('hover');
            });
            el.addEventListener('mouseleave', (e) => {
                el.closest('.dr').querySelector('.page-name').classList.remove('hover');
            });
        });
    })();
</script>

<script>(function() {
    const domain_id = <?php echo json_encode($_smarty_tpl->tpl_vars['domain']->value['id']);?>
;
    const domain = <?php echo json_encode($_smarty_tpl->tpl_vars['domain']->value['name']);?>
;
    if (!jQuery.fn.liveDraggable) {
        jQuery.fn.liveDraggable = function (opts) {
            this.each(function(i,el) {
                var self = $(this);
                if (self.data('init_draggable')) {
                    self.off("mouseover", self.data('init_draggable'));
                }
            });
            this.on("mouseover", function() {
                var self = $(this);
                if (!self.data("init_draggable")) {
                    self.data("init_draggable", arguments.callee).draggable(opts);
                }
            });
        };
    }
    if (!jQuery.fn.liveDroppable) {
        jQuery.fn.liveDroppable = function (opts) {
            this.each(function(i,el) {
                var self = $(this);
                if (self.data('init_droppable')) {
                    self.off("mouseover", self.data('init_droppable'));
                }
            });

            var init = function() {
                var self = $(this);
                if (!self.data("init_droppable")) {
                    self.data("init_droppable", arguments.callee).droppable(opts);
                    self.mouseover();
                }
            };
            init.call(this);
            this.off("mouseover", init).on("mouseover", init);
        };
    }

    $.ui.intersect = function() {
        const oldIntersect = $.ui.intersect;
        function t(t, e, i) {
            return t >= e && e + i > t
        }

        return function(e, i, s, n) {
            if (!i.offset)
                return !1;

            if (s === 'long-pointer') {
                var h = i.offset.left
                  , c = i.offset.top;
                return (t(n.pageY, c, i.proportions().height) || t(n.pageY, c, i.proportions().height + 5)) && t(n.pageX, h, i.proportions().width);
            } else {
                return oldIntersect(e, i, s, n);
            }
        }
    }();

        
        (function () {
            const table_selector = '.site-map-tree-table tbody:first ';
            const $table = $('.site-map-tree-table');
            const $list = $table.find('tbody:first');
            const droppable_bound_elements = [];
            let is_above_newposition = false;

            $.site.helper.loadSortableJS().then(() => {
                $table.find('tbody.s-personal-pages').sortable({
                    animation: 150,
                    handle: '.js-sort',
                    draggable: '[data-personal][data-app-id]:not(.s-disabled)',
                    onEnd: function () {
                        const apps = [];
                        $('[data-personal][data-app-id]', $table).each(function () {
                            apps.push($(this).data('app-id'));
                        });

                        $('#wa-app').trigger('wa_before_load');
                        $.post("?module=personal&action=appMove&domain_id={$domain_id}" , { apps }, function () {
                            $('#wa-app').trigger('wa_loaded');
                        }, "json");
                    }
                });
            });

            $('tr.dr:not(:first)', $list).liveDraggable({
                refreshPositions: true,
                revert: "invalid",
                cursor: "move",
                handle: "td:first",
                opacity: 0.75,
                zIndex: 9999,
                distance: 3,
                cursorAt: { left: 24 },
                helper: function() {
                    const $self = $(this);
                    const $new_tr = $('<tr/>').append('<table class="s-map-draggable-list"/>');
                    if ($self.hasClass('url-overlap')) {
                        setTimeout(() => {
                            showAlert('Эта страница не может быть перемещена, т.&nbsp;к. является лицевой для другого раздела. Можно переместить раздел, и она будет перемещена вместе с ним. Либо измените адрес и отмените свойство лицевой страницы.');
                        });
                        return $new_tr;
                    }

                    const $new_table = $new_tr.find('table').css({ 'min-width': '550px', 'width': '70%' });
                    const addChildrens = ($row, offset = 1) => {
                        const route_id = $row.data('route-id');
                        const page_id = $row.data('page-id');
                        let needs_children_selector = '';

                        if (route_id !== undefined) {
                            needs_children_selector = `[data-parent-route-id="${route_id}"][data-parent-id=""]`;
                        } else if (page_id) {
                            needs_children_selector = `[data-parent-id="${page_id}"]`;
                        }
                        if (!needs_children_selector) {
                            return;
                        }

                        // find pages
                        let selector = `${table_selector}${needs_children_selector}`;
                        if ($row.data('row-type') === 'blockpage') {
                            selector += '[data-row-type="blockpage"]';
                        } else {
                            selector += '[data-html-page]';
                        }
                        const app_id = $row.data('app-id');
                        if (app_id) {
                            selector += `[data-app-id="${app_id}"]`;
                        }
                        $(selector).each(function () {
                            const $child = $(this).addClass('disable-drop');
                            $child.prev().addClass('disable-drop');
                            $new_table.append(calcPaddingLeft($child.clone(), offset));
                            addChildrens($child, offset + 1);
                        });
                    };
                    const addRows = () => {
                        $self.prev().addClass('disable-drop');
                        $new_table.append(calcPaddingLeft($self.addClass('disable-drop').clone()));
                        addChildrens($self);
                    };
                    const shouldAddRowsOverAnother = () => {
                        const $over_another_row = shouldGetOverAnotherRow($self);
                        if ($over_another_row.length) {
                            $new_table.append(calcPaddingLeft($over_another_row.addClass('disable-drop').clone()));
                            addChildrens($over_another_row);
                        }
                    };

                    shouldAddRowsOverAnother();
                    addRows();

                    $new_tr.find('.dr').addClass('ui-dragging');

                    return $new_tr.addClass('ui-draggable helper').css({
                        position: 'relative'
                    }).appendTo($self.closest('tbody'));
                },
                start: function (_, ui) {
                    is_above_newposition = false;
                    const $dr = $(this);
                    if ($dr.hasClass('url-overlap')) {
                        return false;
                    }
                    const row_type = $dr.data('row-type');
                    const app_id = $dr.data('app-id');
                    const is_root = $dr.data('root') !== undefined;
                    const is_html_page = !!$dr.data('html-page');

                    // define: new positions
                    let $new_positions = $();
                    if (row_type === 'blockpage') {
                        $new_positions = $dr.prevAll('.drag-newposition[data-row-type="blockpage"]:not(.disable-drop)')
                            .add($dr.nextAll(`[data-offset="${$dr.data('offset')}"]:first,[data-row-type="blockpage"]:not([data-offset]):first`)
                                .nextAll('.drag-newposition[data-row-type="blockpage"]:not(.disable-drop)')
                            );
                    } else {
                        // another page types
                        // const $all_new_positions = $(table_selector+'.dr:not([data-row-type="blockpage"])')
                        //     .prev('.drag-newposition:not(.disable-drop)')
                        const $all_new_positions =
                            $dr.prevAll('.drag-newposition:not(.disable-drop)')
                            .add($dr.nextAll(`[data-offset="${$dr.data('offset')}"]:first,.drag-newposition[data-root]:not(.disable-drop):first`)
                                .nextAll('.drag-newposition:not(.disable-drop)')
                            );

                        let filter_selector = (is_root ? '[data-root]' : `[data-app-id="${ app_id }"]`);
                        if (row_type !== 'route_app' && is_html_page) {
                            filter_selector += ':not([data-root])';
                        }
                        $new_positions = $all_new_positions.filter(filter_selector+':not([data-row-type="blockpage"])');
                    }
                    if ($new_positions.length) {
                        droppable_bound_elements.push($new_positions);
                        liveDroppableNewPosition($new_positions);
                    }

                    // define: where to put it
                    const enable_drag_avobe_el = row_type === 'blockpage' || (!is_root && is_html_page);
                    if (enable_drag_avobe_el) {
                        let elements_selector = table_selector+'tr.dr:not(.disable-drop)';

                        if (row_type === 'blockpage') {
                            elements_selector += '[data-row-type="blockpage"]';
                        } else {
                            elements_selector += `[data-app-id="${ app_id }"]`;
                        }

                        const $over_elements = $(elements_selector);
                        droppable_bound_elements.push($over_elements);
                        liveDroppableAboveElement($over_elements);
                    }
                },
                stop: function () {
                    $(table_selector+'.disable-drop').removeClass('disable-drop');

                    if (droppable_bound_elements.length) {
                        const destroyDroppable = (el) => {
                            $(el).each(function () {
                                const $el =  $(this);
                                if ($el.hasClass('ui-droppable')) {
                                    $el.droppable("destroy")
                                        .removeData('init_droppable')
                                        .off("mouseover");
                                }
                            });
                        };
                        droppable_bound_elements.forEach(destroyDroppable);
                        droppable_bound_elements.length = 0;
                    }
                }
            });

            /**
             * позиции, куда можно перемещать
             **/
            function liveDroppableNewPosition ($el) {
                $el.liveDroppable({
                    accept: '.dr',
                    tolerance: 'long-pointer',
                    greedy: true,
                    hoverClass: 'active',
                    out: function(event, ui) {
                        is_above_newposition = false;
                    },
                    over: function(event, ui) {
                        is_above_newposition = true;
                        $(this).css('height', '24px');
                        $(table_selector+'.drag-newparent').removeClass('drag-newparent');
                    },
                    deactivate: function(event, ui) {
                        var self = $(this);
                        if (self.hasClass('dragging') || self.hasClass('ui-droppable')) {
                            self.stop().animate({ height: '0' }, 300, null, function() { self.removeClass('dragging'); });
                        }
                    },
                    drop: function(event, ui) {
                        const dr = ui.draggable;
                        const dr_row_type = dr.data('row-type');
                        let id = dr.data('page-id');
                        let before_id = 0;

                        const self = $(this);
                        const self_offset = self.data('offset') || 0;

                        const before_filter_selector = self_offset ? `[data-offset="${self_offset}"]` : '';
                        const before = self.nextUntil(before_filter_selector ? '[data-root]' : '')
                            .filter(`.dr:not(.disable-drop)[data-row-type="${dr_row_type}"]${before_filter_selector}:first`);
                        if (before.length && !before.hasClass('helper')) {
                            before_id = before.data('page-id');
                        }

                        const sep = dr.prev();
                        // means that actually nothing will be changed
                        if ((id && id == before_id) || sep.get(0) == self.get(0) || dr.get(0) == self.prev().get(0)) {
                            return false;
                        }

                        // update parent-id only children.
                        let new_parent_id = dr.data('parent-id');
                        if (dr_row_type === 'blockpage' && self_offset > 0) {
                            new_parent_id = self.prevAll(`.dr[data-row-type="blockpage"][data-root]:first`).data('page-id');
                        } else if (dr.data('html-page') && self_offset > 1) {
                            new_parent_id = self.prevAll(`.dr[data-offset="${self_offset-1}"]:first`).data('page-id');
                        } else {
                            new_parent_id = '';
                        }

                        const self_route_id = self.prevAll(`.dr[data-row-type="route_app"][data-route-id]:first`).data('route-id');
                        if (checkDupeUrl(dr, {
                            new_offset: self_offset,
                            new_parent_id,
                            new_route_id: self_route_id
                        })) {
                            return false;
                        }

                        dr.data('parent-id', new_parent_id).attr('data-parent-id', new_parent_id);

                        const finish = finishDroppable.bind(this, ui);
                        // finish();

                        const callbacks_args = [
                            function() { finish(); },
                            function() {}
                        ];
                        if (dr_row_type === 'route_app') {
                            const data = {
                                route_id: dr.data('route-id') || ''
                            };
                            if (before.length) {
                                data.before_route_id = before.data('route-id') || '';
                            }
                            routeMove(data, ...callbacks_args);
                        } else {
                            const route = self.prevAll(`.dr[data-app-id="${self.data('app-id')}"][data-root]:first`).data('route') || '';
                            const data = {
                                id,
                                route,
                                parent_id: new_parent_id || '',
                                before_id: before_id,
                                app_id: dr_row_type === 'blockpage' ? 'site_blockpage' : dr.data('app-id'),
                                page_ids: getDraggablePageIds(ui)
                            };
                            pageMove(data, ...callbacks_args);
                        }
                    }
                });
            }

            /**
             * перенос над элементом
             **/
            function liveDroppableAboveElement($el) {
                $el.liveDroppable({
                    accept: 'tr.dr',
                    tolerance: 'pointer',
                    greedy: true,
                    out: function() {
                        $(this).removeClass('drag-newparent');
                    },
                    over: function(event, ui) {
                        var self = $(this); // tr
                        self.addClass('drag-newparent');
                        var dr = ui.draggable;
                        var drSelector = '.dr[data-page-id!="'+dr.attr('data-page-id')+'"]';
                        var nearby = $();

                        // helper to widen all spaces below the current tr and above next tr (which may be on another tree level, but not inside current)
                        var addBelow = function(nearby, current) {
                            if (!current.length) {
                                return nearby;
                            }
                            nearby = nearby.add(current.nextUntil(drSelector).filter('tr.drag-newposition:not(.disable-drop)'));
                            if (current.nextAll(drSelector).length > 0) {
                                return nearby;
                            }
                            return arguments.callee(nearby, current.parent().closest('tr'));
                        }

                        // widen all spaces above the current tr and below the prev tr (which may be on another tree level)
                        var above = self.prevAll(drSelector+':first');
                        if(above.length > 0) {
                            nearby = addBelow(nearby, above);
                        }

                        var old = $('.drag-newposition:animated, .drag-newposition.dragging').not(nearby);

                        // old.stop().animate({height: '2px'}, 300, null, function(){old.removeClass('dragging');});
                        nearby.stop().animate({height: '10px'}, 300, null, function(){nearby.addClass('dragging');});
                    },
                    drop: function(event, ui) {
                        if (is_above_newposition) {
                            return;
                        }

                        var self = $(this).removeClass('drag-newparent'); // tr
                        var parent_id = self.data('page-id') || 0;
                        var parent_offset = self.data('offset') || 0;
                        var parent_type = self.data('row-type');
                        var dr = ui.draggable;

                        // check
                        var id = dr.data('page-id');
                        // if (id == parent_id || (!self.data('html-page') && parent_type !== dr.data('row-type'))) {
                        if (id == parent_id) {
                            return false;
                        }

                        const correct_parent_id = parent_type === 'route_app' ? '' : parent_id;
                        if (checkDupeUrl(dr, {
                            new_offset: parent_offset + 1,
                            new_parent_id: correct_parent_id,
                            new_route_id: parent_type === 'route_app' ? self.data('route-id') : self.data('parent-route-id')
                        })) {
                            return false;
                        }

                        const finish = finishDroppable.bind(this, ui, true);
                        const route = self.data('route') || self.prevAll(`.dr[data-app-id="${self.data('app-id')}"][data-root]:first`).data('route') || '';
                        const data = {
                            id,
                            route,
                            parent_id: correct_parent_id,
                            before_id: 0,
                            app_id: dr.data('row-type') === 'blockpage' ? 'site_blockpage' : dr.data('app-id'),
                            page_ids: getDraggablePageIds(ui)
                        };
                        // finish();
                        pageMove(data, function() { finish() }, function() {});
                    }
                });
            }
        })();

        function checkDupeUrl (dr, { new_offset, new_parent_id, new_route_id }) {
            const row_type = dr.data('row-type');
            const is_blockpage = row_type === 'blockpage';
            const current_url = dr.find('a[data-url]').data('url');
            const $dupe = $(
                `.dr:not(.disable-drop)`+
                (is_blockpage ? '' : `[data-app-id="${dr.data('app-id')}"]`)+
                (is_blockpage ? '' : `[data-parent-route-id="${new_route_id}"]`)+
                `[data-offset="${new_offset}"]`+
                `[data-parent-id="${new_parent_id}"]`+
                `[data-row-type="${row_type}"] a[data-url="${current_url}"]`
            );

            if ($dupe.length) {
                showAlert('Перемещение невозможно: страницы с одинаковыми адресами не могут находиться на одном уровне.');
                return true;
            }

            return false;
        }

        function calcPaddingLeft (dr, offset) {
            var css_offset = offset ? (offset)*2 + 'em' : '';
            dr.find('td:first').css('padding-left', css_offset) //to do: if have child UPD add offset
            return dr;
        }
        function updateOffset ($el, offset) {
            $el.data('offset', offset).attr('data-offset', offset);
            calcPaddingLeft($el, offset);
        }
        function finishDroppable (ui, place_to_end = false) {
            const $drag_newposition = place_to_end ? $(this).removeClass('drag-newparent') : $(this);
            const $need_dr = ui.draggable;
            const offset = ($drag_newposition.data('offset') || 0) + (place_to_end ? 1 : 0);

            shouldGetOverAnotherRow($need_dr).add($need_dr).each(function () {
                const $dr = $(this);
                const $sep  = $dr.prev();
                const prev_offset = $dr.data('offset') || 0;

                updateOffset($dr, offset);
                updateOffset($sep, offset);
                if (place_to_end) {
                    let $last_sibling = $drag_newposition;
                    let $last = $();
                    let page_id = $last_sibling.data('page-id');
                    const dr_page_id = $dr.data('page-id');
                    let parent_selector = $last_sibling.data('row-type') === 'route_app'
                        ? `[data-parent-route-id="${$last_sibling.data('route-id')}"]`
                        : `[data-parent-id="${page_id}"]`;
                    let part_selector = $last_sibling.data('row-type') === 'blockpage' ? '[data-row-type="blockpage"]' : `[data-app-id="${$last_sibling.data('app-id')}"]`;
                    while (($last = $last_sibling.nextAll(`${parent_selector}:not([data-page-id="${dr_page_id}"])${part_selector}:last`)).length) {
                        $last_sibling = $last;
                        parent_selector = `[data-parent-id="${$last.data('page-id')}"]`;
                    }
                    $sep.insertAfter($last_sibling);
                    $dr.insertAfter($sep);
                } else {
                    $sep.insertBefore($drag_newposition);
                    $dr.insertBefore($drag_newposition);
                }

                let $children = ui.helper.find('tr');
                if ($children.length > 1) {
                    let $prev_row = $dr;
                    const $list = $('.site-map-tree-table tbody:first');
                    $children.first().nextUntil('[data-root]').each(function () {
                        const $fake_child = $(this);
                        const page_id = $fake_child.data('page-id');
                        const app_id = $fake_child.data('app-id');

                        let part_selector = $fake_child.data('row-type') === 'blockpage' ? '[data-row-type="blockpage"]' : `[data-app-id="${app_id}"]`;
                        const $child = $list.find(`${part_selector}[data-page-id="${page_id}"]:first`);
                        const $child_sep = $child.prev();
                        const new_offset = offset + ($child.data('offset') - prev_offset);

                        updateOffset($child, new_offset);
                        updateOffset($child_sep, new_offset);

                        $child_sep.insertAfter($prev_row);
                        $child.insertAfter($child_sep);

                        $prev_row = $child;
                        $fake_child.remove();
                    });
                    $children.first().remove();
                }

            });
        }

        function shouldGetOverAnotherRow($dr) {
            const url = $dr.find('[data-url]').data('url');
            const $over_another_row = $dr.prevAll('.dr.url-overlap.show-over-another-section:first')
                .find(`a[data-url="${url}"]`).closest('.dr');

            return $over_another_row;
        }

        function getDraggablePageIds (ui) {
            return $.map(ui.helper.find('tr[data-page-id]'), function (el) {
                return Number(el.dataset.pageId);
            }).filter(Boolean);
        }

        function pageMove(data, success, error) {
            $.site.log('pageMove', data);
            const payload = Object.assign(data, { domain_id });
            $('#wa-app').trigger('wa_before_load');
            $.post("?module=map&action=move", payload, function (r) {
                $.site.log('move:response', r);
                if (r?.status == 'ok') {
                    /* @deprecated update title
                    const { id, full_url, old_full_url } = r.data;
                    if (full_url && old_full_url) {
                        let add_selector = '';
                        if (payload.app_id === 'site_blockpage') {
                            add_selector = '[data-row-type="blockpage"]';
                        } else {
                            add_selector = `[data-app-id="${payload.app_id}"]`;
                        }
                        payload.page_ids.forEach(page_id => {
                            const $a = $(`[data-page-id="${page_id}"]${add_selector} .page-route a`);
                            if ($a.length) {
                                if (id == page_id) {
                                    $a.attr('title', '/' + full_url);
                                } else {
                                    // update $a.text() for old logic
                                    const _full_url = '/' + full_url + (full_url.length > 0 && full_url.substr(-1) != '/' ? '/' : '') + $a.attr('title').substr(old_full_url.length + 1);
                                    $a.attr('title', _full_url);
                                }
                            }
                        });
                    }
                    */
                    $('#wa-app').trigger('wa_loaded');
                    success();
                    $.site.loadMap();
                } else {
                    $('#wa-app').trigger('wa_load_fail');
                    error();
                }
            }, "json");
        }

        function routeMove(payload, success, error) {
            $.site.log('routeMove', payload);
            $('#wa-app').trigger('wa_before_load');
            $.post("?module=map&action=move&app_id=site_route&domain_id="+domain_id, payload, function (r) {
                $.site.log('move:response', r);
                if (r?.status == 'ok') {
                    $('#wa-app').trigger('wa_loaded');
                    success();
                    $.site.loadMap();
                } else {
                    if (r?.errors?.map) {
                        let msgs = r.errors.map(function(el) {
                            return el?.description || '';
                        });
                        showAlert(msgs.join("\n"));
                    }
                    $('#wa-app').trigger('wa_load_fail');
                    error();
                }
            }, "json");
        }

        function showAlert(text) {
            $.waDialog.alert({
                text,
                'button_title': 'Закрыть'
            });
        }
        
})();
</script>
<?php if (waRequest::get('is_new_blockpage')){?>
<script>
    (function() {
        const defaults = "<?php echo urlencode((($tmp = @waRequest::get('defaults'))===null||$tmp==='' ? '' : $tmp));?>
";
        const params = new URLSearchParams({
            is_new: 1,
            domain_id: "<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
",
            ...(defaults ? { defaults } : {})
        });
        $.get('?module=map&action=pageSettingsDialog&' + params, (html) => {
            if (!html) return;
            const dialog = $.waDialog({
                html
            });
        });

        if (window.history) {
            history.replaceState(null, '', "<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
map/overview/?domain_id=<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
");
        }
    })();
</script>
<?php }?>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/map/MapOverviewAlerts.inc.html" */ ?>
<?php if ($_valid && !is_callable('content_6a889cef8914a5_37656285')) {function content_6a889cef8914a5_37656285($_smarty_tpl) {?><div class="s-map-alerts">
<?php if ($_smarty_tpl->tpl_vars['show_alert_moving_to_settings']->value){?>
<div class="js-alert-moving-to-settings alert small">
    <div class="flexbox space-8">
        <div>
            <span class="icon"><i class="fas fa-info-circle"></i></span>
        </div>
        <div class="wide">
            Перенаправления и настройки приложений, не относящихся к карте сайта, переехали в раздел «Настройки».
        </div>
        <div>
            <a href="javascript:void(0)" id="js-hide-alert-moving-to-settings" class="alert-close"><i class="fas fa-times"></i></a>
        </div>
    </div>
    <script>
        $('#js-hide-alert-moving-to-settings').on('click', function () {
            $.post('?module=map&action=hideAlertMovingToSettings', null, function (r) {
                if (r && r.status === 'ok') {
                    $('.js-alert-moving-to-settings').remove();
                }
            });
        });
    </script>
</div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['has_redirect']->value){?>
<div class="alert small">
    <div class="flexbox space-8">
        <div>
            <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
        </div>
        <div class="wide">
            В настройках сайта есть перенаправления. Из-за них посетители могут попадать на другие адреса вместо страниц или разделов, которые есть или были ранее в карте сайта.
        </div>
        <div>
            <a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
settings/?domain_id=<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
&scroll_to=redirects" class="button gray nowrap">Смотреть настройки</a>
        </div>
    </div>
</div>
<?php }?>
<?php if (!$_smarty_tpl->tpl_vars['has_root_page']->value){?>
<div class="js-alert-no-main-page alert danger small">
    <div class="flexbox space-8">
        <div>
            <span><i class="fas fa-exclamation-triangle"></i></span>
        </div>
        <div class="wide">
            Сайт работает неправильно, потому что не выбрана главная страница.
        </div>
    </div>
</div>
<?php }?>
</div>
<?php }} ?>