<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:28
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/themes/Themes.html" */ ?>
<?php /*%%SmartyHeaderCode:15162869566a8c8284b74903-74087730%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c88fa7cd6781b1a36cd0be0d61a05120a2e3cf46' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/themes/Themes.html',
      1 => 1750315810,
      2 => 'file',
    ),
    '6453238e04104b43d5f9dcfe77ff3f884fef3a64' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/themes/Themes.installed.include.html',
      1 => 1772188986,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15162869566a8c8284b74903-74087730',
  'function' => 
  array (
    'theme_skeleton' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'design_url' => 0,
    'app' => 0,
    'routes' => 0,
    'r' => 0,
    'domains' => 0,
    'd' => 0,
    'themes_url' => 0,
    'wa' => 0,
    'wa_backend_url' => 0,
    'app_id' => 0,
    'domain' => 0,
    'domain_id' => 0,
    'wa_url' => 0,
    'wa_app_static_url' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c8284bb9181_28261275',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c8284bb9181_28261275')) {function content_6a8c8284bb9181_28261275($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.replace.php';
if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
?><div id="wa-themes-installed" style="display:none;">
    <?php /*  Call merged included template "./Themes.installed.include.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./Themes.installed.include.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '15162869566a8c8284b74903-74087730');
content_6a8c8284b77e56_36978922($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./Themes.installed.include.html" */?>
</div>

<div id="wa-theme-content" class="custom-mt-32 hidden">Загрузка... <i class="fas fa-spinner fa-spin loading"></i></div>

<div class="wa-themes-store-wrapper">
    <h4 class="wa-themes-store-header" style="display:none;">Все темы</h4>
    <div id="wa-themes-store" class="wa-themes-store" style="display:none;">
        <div style="margin:3rem 0 -0.5rem 0.5rem;">Загрузка... <i class="fas fa-spinner fa-spin loading"></i></div>
    </div>
</div>



<div class="dialog" id="wa-theme-start-using-dialog" data-url="<?php echo $_smarty_tpl->tpl_vars['design_url']->value;?>
">
    <div class="dialog-background"></div>
    <form class="dialog-body">
        <div class="dialog-content">
            <p><?php echo sprintf("Подключите тему дизайна к одному из существующих поселений приложения «%s» или создайте новое правило маршрутизации:",$_smarty_tpl->tpl_vars['app']->value['name']);?>
</p>

            <div class="fields">
                <div class="field">
                    <div class="name for-checkbox">Выберите правило</div>
                    <?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['routes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['r']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value){
$_smarty_tpl->tpl_vars['r']->_loop = true;
 $_smarty_tpl->tpl_vars['r']->index++;
 $_smarty_tpl->tpl_vars['r']->first = $_smarty_tpl->tpl_vars['r']->index === 0;
?>
                    <div class="value">
                        <label>
                            <span class="wa-radio">
                                <input name="route" value="<?php echo $_smarty_tpl->tpl_vars['r']->value['_domain'];?>
|<?php echo $_smarty_tpl->tpl_vars['r']->value['_id'];?>
" type="radio" <?php if ($_smarty_tpl->tpl_vars['r']->first){?>checked<?php }?>>
                                <span></span>
                            </span>
                            <?php echo smarty_modifier_replace(waIdna::dec(htmlspecialchars((string)$_smarty_tpl->tpl_vars['r']->value['_domain'], ENT_QUOTES, 'UTF-8', true)),'www.','');?>
/<?php echo $_smarty_tpl->tpl_vars['r']->value['url'];?>

                            <span class="hint"><?php if (isset($_smarty_tpl->tpl_vars['r']->value['theme'])){?><?php echo $_smarty_tpl->tpl_vars['r']->value['theme'];?>
<?php }else{ ?>default<?php }?></span>
                        </label>
                    </div>
                    <?php }
if (!$_smarty_tpl->tpl_vars['r']->_loop) {
?>
                    <div class="value gray">
                        <?php echo sprintf('На этом сайте нет поселений приложения «%s».',$_smarty_tpl->tpl_vars['app']->value['name']);?>

                    </div>
                    <?php } ?>
                </div>
                <div class="field">
                    <div class="name for-checkbox">Новое правило</div>
                    <div class="value">
                        <label>
                            <span class="wa-radio">
                                <input name="route" id="create-new-route-choice" value="new" type="radio"<?php if (!count($_smarty_tpl->tpl_vars['routes']->value)){?> checked<?php }?>>
                                <span></span>
                            </span>
                        </label>

                        <?php if (count($_smarty_tpl->tpl_vars['domains']->value)==1){?>
                        <input name="domain" type="hidden" value="<?php echo current($_smarty_tpl->tpl_vars['domains']->value);?>
">
                        <?php echo current($_smarty_tpl->tpl_vars['domains']->value);?>
/<?php }else{ ?>
                        <div class="wa-select">
                            <select name="domain" class="create-new-route-control">
                                <?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['domains']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
$_smarty_tpl->tpl_vars['d']->_loop = true;
?>
                                <option value="<?php echo $_smarty_tpl->tpl_vars['d']->value;?>
"><?php echo smarty_modifier_truncate(str_replace('www.','',waIdna::dec($_smarty_tpl->tpl_vars['d']->value)),23,'...',false,true);?>
</option>
                                <?php } ?>
                            </select>/
                        </div>
                        <?php }?>
                        <input type="text" name="url" value="" placeholder="*" class="short create-new-route-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="dialog-footer">
            <input type="hidden" name="theme" value="">
            <input type="submit" class="button green" data-value="<?php echo sprintf("Начать использовать тему «%s»",'%THEME%');?>
">
            или <a href="#" class="js-close-dialog">отмена</a>
        </div>
    </form>
</div>

<?php if (!function_exists('smarty_template_function_theme_skeleton')) {
    function smarty_template_function_theme_skeleton($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['theme_skeleton']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><div class="skeleton"><div><span class="skeleton-line" style="height: 40px;"></span><?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? 3+1 - (1) : 1-(3)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 1, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?><span class="skeleton-header" style="height: 100px;"></span><?php }} ?></div></div><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<script type="text/javascript">
    $(function () {
        $.themes = {
            $themes_installed: null,
            $content: null,
            $store_content: null,
            options: {
                loading: '<?php smarty_template_function_theme_skeleton($_smarty_tpl,array());?>
',
                path: '<?php echo $_smarty_tpl->tpl_vars['themes_url']->value;?>
'
            },
            path: {
                theme: false,
                tail: null,
                params: { }
            },

            ready: false,
            $menu: null,
            xhr: null,

            init: function (options) {
                this.options = $.extend(this.options, options || { });
                if (this.ready) {
                    return;
                }
                this.ready = true;

                this.$themes_installed = $("#wa-themes-installed");
                this.$content = $("#wa-theme-content");
                this.$store_content = $("#wa-themes-store");

                this.dispatch(location.hash, true);

                <?php if ($_smarty_tpl->tpl_vars['wa']->value->installer){?>
                    this.$store_content.load('<?php echo $_smarty_tpl->tpl_vars['wa_backend_url']->value;?>
installer/?module=themes&action=view&slug=<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
&return_hash=<?php ob_start();?><?php echo urlencode($_smarty_tpl->tpl_vars['domain']->value);?>
<?php $_tmp1=ob_get_clean();?><?php echo rawurlencode(((string)$_smarty_tpl->tpl_vars['design_url']->value)."theme=%theme_id%&domain=".$_tmp1."&show_start_using=1");?>
&full_width=1&hide_back=1&go_return_hash_after_installation=1',function(){
                        $('.wa-design-gray-toolbar h4 svg').hide();
                    });
                <?php }else{ ?>
                    // installer app does not exist
                    this.$store_content.html('').closest('.wa-themes-store-wrapper').hide();
                <?php }?>
            },

            parsePath: function (path) {
                path = path.replace(new RegExp('^.*' + this.options.path), '');

                var splited_array = path.split("/"),
                    tail = (splited_array.length > 1) ? splited_array[1] : null;

                return {
                    theme: path.replace(/\/.*$/, '') || null,
                    tail: tail,
                    raw: path
                };
            },

            dispatch: function (hash, force) {
                if (hash === undefined) {
                    hash = window.location.hash;
                }

                var $theme = $();
                var path = this.parsePath(hash);
                if (path && path.theme) {
                    var full_hash = this.options.path + path.theme;
                    if (window.location.hash !== full_hash) {
                        if (window.history && window.history.pushState) {
                            const content_url = location.href + path.theme;
                            window.history.pushState({ content_url }, null, content_url);
                        } else {
                            window.location.hash = full_hash;
                        }
                    }
                }

                var load = force || (path.theme !== this.path.theme);
                if (!load) {
                    return;
                }

                this.path.tail = null;
                if (path.theme) {
                    $theme = this.$themes_installed.find('.s-theme-wrapper[data-id="'+path.theme+'"]');
                }

                var url = this.helper.getContentUrl($theme, path);
                if (!url) {
                    // All themes
                    this.showList();
                    this.showStore();
                    $(document).trigger('load_all.wa_themes');
                    return;
                }

                this.path.theme = path.theme;
                this.$content.removeClass('hidden')
                this.hideList();
                this.hideStore();
                $(document).trigger('load_theme.wa_themes');

                if (this.xhr) {
                    this.xhr.abort();
                }
                this.$content.html(this.options.loading);
                var self = this;
                this.xhr = $.ajax({
                    url: url,
                    success: function (data) {
                        self.xhr = null;
                        if (self.path.theme == path.theme) {
                            self.$content.html(data);
                            $(document).trigger('wa_loaded');
                        }
                        $(document).trigger('loaded_theme.wa_themes');
                    }
                });
            },

            reloadThemesInstalled: function() {
                const is_extended_param = this.$themes_installed.find('.s-themes-list-wrapper.is-extended').length ? '&is_extended=1' : '';
                this.$themes_installed.load('?module=themes&action=themesInstalled' + is_extended_param);
            },

            helper: {
                getContentUrl: function ($item, path) {
                    var url = '';
                    if ($item.data('url')) {
                        url = $item.data('url');
                    } else if (path.theme) {
                        var param_domain_id = '<?php if (empty($_smarty_tpl->tpl_vars['domain_id']->value)){?><?php }else{ ?>&domain_id=<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
<?php }?>';
                        // url = '?module=design&action=theme&theme=' + path.theme + param_domain_id;
                        url = 'module=theme' + param_domain_id + '&' + path.theme;

                        const params = new URLSearchParams(url);
                        params.delete('action');
                        url = '?' + params;
                    }

                    url += ( path.tail ? "&" + path.tail : "" );

                    return url;
                }
            },

            showList: function () {
                this.$themes_installed.show();
                this.$content.addClass('hidden')
            },
            hideList: function () {
                this.$themes_installed.hide();
            },
            showStore: function () {
                this.$store_content.show();
                this.showStoreHeader();
            },
            hideStore: function () {
                this.$store_content.hide();
                this.hideStoreHeader();
            },
            showStoreHeader: function () {
                $('.wa-themes-store-header').show();
            },
            hideStoreHeader: function () {
                $('.wa-themes-store-header').hide();
            },
        }

        $.site.isWithoutReload = () => $('.s-themes').length > 0;
        $.themes.init();

        
        $(document).on('installer_after_install_go_to_settings', (e, data) => {
            if (data.type === 'theme') {
                sessionStorage.setItem('wa_theme_onload', data.id);
                location.reload();
            }
        });
        var wa_theme_onload = sessionStorage.getItem('wa_theme_onload');
        if (wa_theme_onload) {
            $('#js-installed-themes').find('[data-id="'+wa_theme_onload+'"] a').on('click', function  () {
                sessionStorage.removeItem('wa_theme_onload');
            }).click();
        }

        $(document).on('installer_installation_successfull', () => {
            $.themes.reloadThemesInstalled();
        });
    });
</script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.history.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/theme.js?v<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"></script>
<script type="text/javascript">
    $.theme.init();
</script>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:28
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/themes/Themes.installed.include.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c8284b77e56_36978922')) {function content_6a8c8284b77e56_36978922($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['max_count_themes'] = new Smarty_variable(5, null, 0);?>
<?php if (!empty($_smarty_tpl->tpl_vars['app_themes']->value)){?>
    <style>
        .s-themes .s-themes-list-wrapper:not(.is-extended) .s-themes-list .s-theme-wrapper:nth-child(n+<?php echo $_smarty_tpl->tpl_vars['max_count_themes']->value+1;?>
) {
            display: none;
        }
    </style>
    <?php $_smarty_tpl->tpl_vars['count_app_themes'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['app_themes']->value), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['_themes_url'] = new Smarty_variable(array(), null, 0);?>
    <?php if (!empty($_smarty_tpl->tpl_vars['routes']->value)){?>
        <?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['routes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value){
$_smarty_tpl->tpl_vars['r']->_loop = true;
?>
            <?php $_smarty_tpl->createLocalArrayVariable('r', null, 0);
$_smarty_tpl->tpl_vars['r']->value['theme'] = (($tmp = @$_smarty_tpl->tpl_vars['r']->value['theme'])===null||$tmp==='' ? 'default' : $tmp);?>
            <?php $_smarty_tpl->createLocalArrayVariable('r', null, 0);
$_smarty_tpl->tpl_vars['r']->value['theme_mobile'] = (($tmp = @$_smarty_tpl->tpl_vars['r']->value['theme_mobile'])===null||$tmp==='' ? 'default' : $tmp);?>
            
            <?php if ($_smarty_tpl->tpl_vars['r']->value['url']&&substr($_smarty_tpl->tpl_vars['r']->value['url'],-1)=="*"){?>
                <?php $_smarty_tpl->createLocalArrayVariable('r', null, 0);
$_smarty_tpl->tpl_vars['r']->value['url'] = substr($_smarty_tpl->tpl_vars['r']->value['url'],0,-1);?>
            <?php }?>
            <?php if (isset($_smarty_tpl->tpl_vars['app_themes']->value[$_smarty_tpl->tpl_vars['r']->value['theme']])){?>
                <?php $_smarty_tpl->createLocalArrayVariable('_themes_url', null, 0);
$_smarty_tpl->tpl_vars['_themes_url']->value[$_smarty_tpl->tpl_vars['r']->value['theme']] = ((('//').(waIdna::dec($_smarty_tpl->tpl_vars['r']->value['_domain']))).('/')).($_smarty_tpl->tpl_vars['r']->value['url']);?>
            <?php }?>
            <?php if (isset($_smarty_tpl->tpl_vars['app_themes']->value[$_smarty_tpl->tpl_vars['r']->value['theme_mobile']])&&$_smarty_tpl->tpl_vars['r']->value['theme']!=$_smarty_tpl->tpl_vars['r']->value['theme_mobile']){?>
                <?php $_smarty_tpl->createLocalArrayVariable('_themes_url', null, 0);
$_smarty_tpl->tpl_vars['_themes_url']->value[$_smarty_tpl->tpl_vars['r']->value['theme_mobile']] = ((('//').(waIdna::dec($_smarty_tpl->tpl_vars['r']->value['_domain']))).('/')).($_smarty_tpl->tpl_vars['r']->value['url']);?>
            <?php }?>
        <?php } ?>
    <?php }?>

    <div class="s-installed-themes-section">
        <div class="s-section-header">
            <span class="s-title">
                <h4>Установленные</h4>
            </span>
            <button class="js-theme-upload-link button light-gray small" type="button"><i class="fas fa-cloud-upload-alt"></i> Загрузить свою тему <span class="hint">.tar.gz</span></button>
        </div>
        <div id="js-installed-themes" class="s-themes-list-wrapper">
            <div class="s-themes-list custom-my-24">
                <?php  $_smarty_tpl->tpl_vars['_theme'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_theme']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['app_themes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_theme']->key => $_smarty_tpl->tpl_vars['_theme']->value){
$_smarty_tpl->tpl_vars['_theme']->_loop = true;
?>
                    <?php $_smarty_tpl->tpl_vars['_name'] = new Smarty_variable($_smarty_tpl->tpl_vars['_theme']->value->getName(), null, 0);?>
                    <?php $_smarty_tpl->tpl_vars['_cover_image'] = new Smarty_variable($_smarty_tpl->tpl_vars['_theme']->value->getCover(), null, 0);?>
                    <?php if (empty($_smarty_tpl->tpl_vars['_cover_image']->value)){?>
                        <?php $_smarty_tpl->tpl_vars['_cover_image'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_url']->value)."wa-content/img/design/themes/no-image.png", null, 0);?>
                    <?php }?>

                    <?php $_smarty_tpl->tpl_vars['_theme_id'] = new Smarty_variable($_smarty_tpl->tpl_vars['_theme']->value->id, null, 0);?>
                    <?php ob_start();?><?php echo urlencode($_smarty_tpl->tpl_vars['domain']->value);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['_theme_url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['design_url']->value)."theme=".((string)$_smarty_tpl->tpl_vars['_theme_id']->value)."&domain=".$_tmp1, null, 0);?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['used_domain_themes']->value[$_smarty_tpl->tpl_vars['_theme_id']->value])){?>
                        <?php $_smarty_tpl->tpl_vars['_theme_url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_theme_url']->value)."&route=".((string)$_smarty_tpl->tpl_vars['used_domain_themes']->value[$_smarty_tpl->tpl_vars['_theme_id']->value]), null, 0);?>
                    <?php }?>

                    <div class="s-theme-wrapper" data-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_theme_id']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-load="?module=design&action=theme&theme=<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_theme_id']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                        <?php if (empty($_smarty_tpl->tpl_vars['_themes_url']->value[$_smarty_tpl->tpl_vars['_theme_id']->value])){?>
                            <a class="s-image-wrapper card" href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_theme_url']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_name']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                                <img src="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_cover_image']->value, ENT_QUOTES, 'UTF-8', true);?>
" alt="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_name']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                            </a>
                        <?php }else{ ?>
                            <a class="s-image-wrapper card" href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_theme_url']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_name']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                                <img src="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_cover_image']->value, ENT_QUOTES, 'UTF-8', true);?>
" alt="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_name']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                            </a>
                            <a class="s-iframe-wrapper card hidden" href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_theme_url']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_name']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                                <iframe src="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['_themes_url']->value[$_smarty_tpl->tpl_vars['_theme_id']->value])===null||$tmp==='' ? '' : $tmp);?>
" onload="this.closest('.s-theme-wrapper').querySelector('.s-image-wrapper').remove();this.closest('.s-iframe-wrapper').classList.remove('hidden');"></iframe>
                            </a>
                        <?php }?>
                        <div class="s-name-wrapper">
                            <a class="s-name" href="<?php echo $_smarty_tpl->tpl_vars['_theme_url']->value;?>
" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_name']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                                <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_name']->value, ENT_QUOTES, 'UTF-8', true);?>

                            </a>
                        </div>
                        <div class="s-statuses small">
                            <?php if (isset($_smarty_tpl->tpl_vars['used_app_themes']->value[$_smarty_tpl->tpl_vars['_theme_id']->value])){?>
                                <?php if (isset($_smarty_tpl->tpl_vars['used_domain_themes']->value[$_smarty_tpl->tpl_vars['_theme_id']->value])){?>
                                    <span class="s-status green"><i class="fas fa-check text-green"></i> Используемая</span>
                                <?php }else{ ?>
                                    <span class="s-status"><i class="fas fa-check"></i> Используется на других сайтах.</span>
                                <?php }?>
                            <?php }else{ ?>
                                <span class="s-status gray">Неиспользуемая</span>
                            <?php }?>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <?php if ($_smarty_tpl->tpl_vars['count_app_themes']->value>$_smarty_tpl->tpl_vars['max_count_themes']->value){?>
                <button type="button" class="js-show-more-installed-themes button gray width-100" data-active="Показать еще" data-inactive="Скрыть"><span class="js-show-more-installed-themes-text">Показать еще</span> <?php echo $_smarty_tpl->tpl_vars['count_app_themes']->value-$_smarty_tpl->tpl_vars['max_count_themes']->value;?>
 <i class="fas fa-caret-down s-icon"></i></button>
            <?php }?>
        </div>

        
        <div class="dialog" id="wa-theme-upload-dialog">
            <div class="dialog-background"> </div>
            <form class="dialog-body" id="wa-theme-upload-form" method="post" action="?module=design&amp;action=themeUpload" enctype="multipart/form-data">
                <h3 class="dialog-header">Загрузить тему</h3>
                <div class="dialog-content">
                    <span class="wa-theme-dialog-error state-error-hint"></span>
                    <p>Загружаемая тема должна представлять собой правильный архив темы Webasyst (архив в формате .tar.gz с файлами темы и файлом-манифестом theme.xml).</p>
                    <div class="upload-area">
                        <div class="upload">
                            <label class="link">
                                <i class="fas fa-file-upload"></i>
                                <span>Выберите файл</span>
                                <input id="wa-input-file" type="file" name="theme_files[]" autocomplete="off">
                            </label>
                        </div>
                    </div>
                    <?php echo $_smarty_tpl->tpl_vars['wa']->value->csrf();?>

                    <div class="loading" style="display:none; margin-top: 10px">
                        <i class="fas fa-spinner fa-spin"></i> Загрузка...
                    </div>
                </div>
                <div class="dialog-footer">
                    <input type="submit" class="button green" value="Загрузить">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['themes_url']->value;?>
" class="js-close-dialog button light-gray">Отмена</a>
                </div>
            </form>
        </div>

        <script>
            (function($) {
                const $installed_themes = $("#js-installed-themes"),
                    $toggle = $installed_themes.find(".js-show-more-installed-themes"),
                    active_class = "is-extended";

                $toggle.on("click", function(event) {
                    event.preventDefault();
                    const is_active = $installed_themes.hasClass(active_class);
                    toggle(!is_active);
                });

                <?php if (waRequest::get('is_extended')){?>
                toggle(true)
                <?php }?>

                function toggle(show) {
                    const $icon = $toggle.find(".s-icon")[0],
                        bottom_icon_class = "fa-rotate-180",
                        $button = $('.js-show-more-installed-themes');

                    if (!$icon) {
                        return;
                    }

                    $button.find('.js-show-more-installed-themes-text').text($button.data(show ? 'inactive' : 'active'));
                    if (show) {
                        $icon.classList.add(bottom_icon_class);
                        $installed_themes.addClass(active_class);
                    } else {
                        $icon.classList.remove(bottom_icon_class);
                        $installed_themes.removeClass(active_class);
                    }
                }

                $(".js-theme-upload-link").on('click', function (e) {
                    e.preventDefault();

                    const $upload_dialog = $("#wa-theme-upload-dialog");

                    $(".wa-theme-dialog-error").text('');

                    $upload_dialog.find("div.loading").hide();
                    $.waDialog({
                        $wrapper: $upload_dialog.clone(),
                        onOpen($dialog, dialog) {
                            let $form = $dialog.find('form:first'),
                                $input_file = $dialog.find("#wa-input-file"),
                                $submit_btn = $dialog.find('[type="submit"]');

                            $dialog.find(".upload-area").waUpload({
                                is_uploadbox: true
                            });

                            $form.on('submit', function (e) {
                                e.preventDefault();
                                $submit_btn.addClass('disabled')
                                $dialog.find("div.loading").show();
                                const formData = new FormData(this);

                                postData($(this).attr('action'), formData)
                                    .then(
                                        (res) => {
                                            try {
                                                let response = $.parseJSON(res);
                                                if (response.status === 'fail') {
                                                    $dialog.find("div.loading").hide();
                                                    $input_file.val('');
                                                    handleError(response, $dialog);
                                                } else if (response.status === 'ok') {
                                                    dialog.close();
                                                    location.reload();
                                                }
                                            }catch (e){
                                                let response = {
                                                    'errors': []
                                                };
                                                let message = $(res).find('h1:first, h2:first');
                                                if (message.length) {
                                                    response.errors.push([message.text()]);
                                                } else {
                                                    response.errors.push(['JavaScript error: ' + e.message]);
                                                }
                                                $dialog.find("div.loading").hide();
                                                $input_file.val('');
                                                handleError(response, $dialog);
                                            }

                                        },
                                        (error) => {
                                            console.error(error)
                                        }
                                    );
                            })
                        }
                    });
                });

                async function postData(url, data) {
                    const response = await fetch(url, {
                        method: 'POST',
                        body: data,
                    });
                    return await response.text();
                }

                function handleError(data, $dialog) {
                    let error = '';
                    if (typeof data.errors == 'string') {
                        error += (error ? '\n' : '') + data.errors;
                    } else {
                        for (let error_item in data.errors) {
                            if(data.errors.hasOwnProperty(error_item)) {
                                error += (error ? '\n' : '') + data.errors[error_item][0];
                            }
                        }
                    }
                    if ($dialog.length) {
                        $dialog.find(".wa-theme-dialog-error").html(error + '<br><br>');
                    } else if ($(".wa-theme-dialog-error:first:visible").length) {
                        $(".wa-theme-dialog-error:first:visible").html('<br><br>' + error + '<br><br>');
                    } else {
                        alert('Error:' + error);
                    }
                    $dialog.find("[type=submit]").removeClass('disabled');
                }

                <?php if (empty($_smarty_tpl->tpl_vars['is_ajax']->value)){?>
                    $installed_themes.on('click', 'a', function  () {
                        $.themes.dispatch($(this).attr('href'), true);
                        return false;
                    });
                <?php }?>
            })(jQuery);
        </script>
    </div>

<?php }elseif(isset($_smarty_tpl->tpl_vars['app_themes']->value)){?>
    <div class="s-installed-themes-section">
        <h4>Установленные</h4>
        <!-- TODO: loc -->
        <div class="s-empty-message gray">Нет установленных тем.</div>
    </div>
<?php }?>
<?php }} ?>