<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:23
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/layouts/BackendPlugins.html" */ ?>
<?php /*%%SmartyHeaderCode:4371478396a8c827fb05ea9-35136125%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '13b6709206089f027896c7b57f6309cb2a81cce8' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/layouts/BackendPlugins.html',
      1 => 1731401703,
      2 => 'file',
    ),
    '6764a4e175d8ad228226f61b45b8f6d3eed24613' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/domain_tabs.html',
      1 => 1745480410,
      2 => 'file',
    ),
    'e09f171acb1effbc0a23ddb5f267a257b91bacd3' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/layouts/includes/backend_with_tabs.html',
      1 => 1731401703,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4371478396a8c827fb05ea9-35136125',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c827fb2ff43_16574094',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c827fb2ff43_16574094')) {function content_6a8c827fb2ff43_16574094($_smarty_tpl) {?><?php /*  Call merged included template "./includes/backend_with_tabs.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./includes/backend_with_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('path'=>'plugins','selected'=>'plugins','content'=>$_smarty_tpl->tpl_vars['content']->value), 0, '4371478396a8c827fb05ea9-35136125');
content_6a8c827fb094f0_51878019($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./includes/backend_with_tabs.html" */?>
<script>
    $.plugins.enableStoreHeader();
</script>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:23
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/layouts/includes/backend_with_tabs.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c827fb094f0_51878019')) {function content_6a8c827fb094f0_51878019($_smarty_tpl) {?><div class="article site-base">
    <div class="article-body">
        <div>
            <?php /*  Call merged included template "templates/actions/backend/includes/domain_tabs.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/actions/backend/includes/domain_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('selected'=>$_smarty_tpl->tpl_vars['selected']->value), 0, '4371478396a8c827fb05ea9-35136125');
content_6a8c827fb0b924_37122164($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/actions/backend/includes/domain_tabs.html" */?>
            <div class="s-<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
">
                <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

            </div>
        </div>
    </div>
</div>
<script>
    if (location.hash && (new RegExp('#/<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/\\w+')).test(location.hash)) {
        $.site.breadcrumbs.showAll();
    } else {
        $.site.breadcrumbs.showRoot()
    }
    $(function () {
        const namespace = '<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
';
        const namespace_singular = namespace.slice(0,-1);
        const events = {
            load_all: 'load_all.wa_' + namespace,
            load_one: 'load_' + namespace_singular + '.wa_' + namespace
        };
        const instance = $[namespace];
        const open_plugin_map = new Map();
        let prev_frame_src = '';

        $(document).off(events.load_all).on(events.load_all, () => {
            $.site.breadcrumbs.showRoot();
        });
        $(document).off(events.load_one).on(events.load_one, () => {
            $.site.breadcrumbs.showAll();
        });

        $.site.breadcrumbs.on('click_parent', () => {
            instance.showStoreHeader();
            instance.showList();
            instance.showStore();
            if (open_plugin_map.get('page_loaded')) {
                open_plugin_map.clear();

                $('.wa-loading').removeClass('is-ready');
                $('.wa-loading').remove();
                if (history.state || !prev_frame_src) {
                    history.back();
                } else {
                    $('.js-store-frame').prop('src', prev_frame_src + '&t=' + Date.now());
                }
            }
        });

        const handlerPostMessage = (event) => {
            const message = getMessageData(event);
            if (!message) {
                return;
            }

            const { action } = message;
            if (action === 'route_inner' && message.data && String(message.data.href).startsWith(namespace_singular + '/')) {
                open_plugin_map.set(action, message.data.href);
                return;
            }

            if (action === 'page_loaded' && open_plugin_map.get('route_inner')) {
                if (message.current_path.includes(open_plugin_map.get('route_inner'))) {
                    instance.hideStoreHeader();
                    instance.hideList();
                    $.site.breadcrumbs.showAll();
                    open_plugin_map.set('page_loaded', true);

                } else if (open_plugin_map.get('page_loaded')) {
                    open_plugin_map.clear();
                    $.site.breadcrumbs.showRoot();
                }
                window.scrollTo(0, 0);
            }
        };

        $(window).off('message.wa_store').on('message.wa_store', (e) => {
            handlerPostMessage(e.originalEvent);
            if (!prev_frame_src) {
                prev_frame_src = $('.js-store-frame').prop('src');
            }
        });

        function getMessageData(event) {
            const { data } = event;
            if (!data) {
                return;
            }

            let obj = null;
            try {
                obj = JSON.parse(data);
            } catch { /* json parsing error */ }

            return obj;
        }
    });
</script>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:23
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/domain_tabs.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c827fb0b924_37122164')) {function content_6a8c827fb0b924_37122164($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.replace.php';
?><?php if (!isset($_smarty_tpl->tpl_vars['selected']->value)){?><?php $_smarty_tpl->tpl_vars['selected'] = new Smarty_variable('sitemap', null, 0);?><?php }?>
<?php $_smarty_tpl->tpl_vars['is_alias'] = new Smarty_variable(ifset($_smarty_tpl->tpl_vars['domain']->value['is_alias'],null), null, 0);?>
<?php $_smarty_tpl->tpl_vars['is_premium'] = new Smarty_variable(waLicensing::check('site')->isPremium(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['tabs'] = new Smarty_variable(array('sitemap'=>array('id'=>'sitemap','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."map/overview/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value),'name'=>_w('Site map'),'icon'=>'sitemap'),'settings'=>array('id'=>'settings','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."settings/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value),'name'=>_w('Settings'),'icon'=>'cog'),'design'=>array('id'=>'design','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."themes/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value)."#/themes/",'name'=>_w('Design themes'),'icon'=>'palette'),'plugins'=>array('id'=>'plugins','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."plugins/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value)."#/plugins/",'name'=>_w('Plugins'),'icon'=>'plug'),'files'=>array('id'=>'files','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."files/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value),'name'=>_w('Files'),'icon'=>'folder-open'),'variables'=>array('id'=>'variables','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."variables/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value),'name'=>'Переменные','icon'=>'dollar-sign')), null, 0);?>

<div class="s-site-header blank<?php if (!empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?> custom-p-16 custom-pb-0<?php }?>">
    <ul class="breadcrumbs custom-pb-8 ">
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?list">Мои сайты</a></li>
        <li class="js-site-breadcrumb hidden">
            <a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
map/overview/?domain_id=<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
"><?php echo smarty_modifier_replace(waIdna::dec(htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain']->value['title'], ENT_QUOTES, 'UTF-8', true)),'www.','');?>
</a>
        </li>
        <?php if (isset($_smarty_tpl->tpl_vars['tabs']->value[$_smarty_tpl->tpl_vars['selected']->value])){?>
            <li class="js-site-breadcrumb hidden">
                <a href="<?php echo $_smarty_tpl->tpl_vars['tabs']->value[$_smarty_tpl->tpl_vars['selected']->value]['url'];?>
" class="js-disable-router"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['tabs']->value[$_smarty_tpl->tpl_vars['selected']->value]['name'], ENT_QUOTES, 'UTF-8', true);?>
</a>
            </li>
        <?php }?>
    </ul>

    <div class="js-site-tabs-with-domain s-site-tabs custom-mb-<?php if (!empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?>8<?php }else{ ?>32<?php }?>">
        <h3 class="custom-my-0 site-domain-header">
            <span class="break-word"><?php echo smarty_modifier_replace(waIdna::dec(htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain']->value['title'], ENT_QUOTES, 'UTF-8', true)),'www.','');?>
</span>
            <a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value['name'];?>
" target="_blank" class="smallest button circle light-gray" title="Посмотреть">
                <i class="icon fas fa-external-link-alt"></i>
            </a>
            <a href="javascript:void(0)" class="smallest button circle light-gray js-duplicate-site-button" title="Копирование сайта">
                <i class="icon far fa-clone"></i>
            </a>
        </h3>

        <div class="flexbox middle">
            <ul class="s-tabs tabs wide nowrap overflow-dropdown blank custom-pt-8 <?php if (empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?>custom-px-16<?php }else{ ?>custom-pl-0<?php }?>"<?php if (empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?> style="margin: 0 -1.25rem;"<?php }?>>
                <?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['t']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
$_smarty_tpl->tpl_vars['t']->_loop = true;
?>
                    <?php $_smarty_tpl->tpl_vars['disabled'] = new Smarty_variable($_smarty_tpl->tpl_vars['is_alias']->value&&($_smarty_tpl->tpl_vars['t']->value['id']==='sitemap'||$_smarty_tpl->tpl_vars['t']->value['id']==='design'), null, 0);?>
                    <li class="<?php if ($_smarty_tpl->tpl_vars['selected']->value==$_smarty_tpl->tpl_vars['t']->value['id']){?>selected<?php }?> <?php if ($_smarty_tpl->tpl_vars['disabled']->value){?>disabled<?php }?>" <?php if ($_smarty_tpl->tpl_vars['disabled']->value){?>title="Раздел не доступен для зеркала сайта"<?php }?>>
                        <a href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['t']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
">
                            <i class="icon small fas fa-<?php echo $_smarty_tpl->tpl_vars['t']->value['icon'];?>
"></i>
                            <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['t']->value['name'], ENT_QUOTES, 'UTF-8', true);?>

                        </a>
                    </li>
                <?php } ?>
            </ul>
            <?php if (!$_smarty_tpl->tpl_vars['is_premium']->value){?>
                <div class="s-premium-link-wrapper s-tabs nowrap">
                    <a href="javascript:void(0)" id="js-premium-section" class="semibold text-purple"><i class="fas fa-crown text-purple"></i> Премиум</a>
                </div>
            <?php }?>
        </div>
    </div>
    <script>
        ( function($) {
            const $wrapper = $(".s-site-header");
            const domain_id = <?php echo json_encode($_smarty_tpl->tpl_vars['domain_id']->value);?>
;

            if (navigator.platform.indexOf('Mac') > -1) {
                setTimeout(() => {
                    $(".tabs", $wrapper).waTabs();
                });
            } else {
                $(".tabs", $wrapper).waTabs();
            }

            <?php if (empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?>
            $(function() {
                setTimeout(() => {
                    $('.tabs').resize();
                });
            });
            <?php }?>

            $.site.breadcrumbs = new class {
                constructor () {
                    this.events = {};
                }
                toggleMode(all_links) {
                    $('.js-site-tabs-with-domain', $wrapper).toggleClass('hidden', all_links);
                    $('.js-site-breadcrumb', $wrapper).toggleClass('hidden', !all_links);
                }
                callEvent(event_name) {
                    if (!event_name || !this.events[event_name]) {
                        return;
                    }
                    this.events[event_name].forEach(fn => fn.call(null));
                }
                showRoot() {
                    this.toggleMode(false);
                    this.callEvent('click_parent');
                    $(".tabs", $wrapper).trigger('resize');
                }
                showAll() {
                    this.toggleMode(true);
                    this.callEvent('click_child');
                }
                on(event_name, callback) {
                    if (callback && ['click_parent', 'click_child'].includes(event_name)) {
                        if (!this.events[event_name]) {
                            this.events[event_name] = [];
                        }
                        this.events[event_name].push(callback);
                    }
                }
            };

            $('.js-site-breadcrumb', $wrapper).on('click', function () {
                $.site.breadcrumbs.showRoot();
            });

            $('#js-premium-section').on('click', function () {
                $.site.helper.showPremiumDialog();
            });

            $wrapper.on('click', '.js-duplicate-site-button', function() {
                <?php if ($_smarty_tpl->tpl_vars['is_premium']->value){?>
                $.post('?module=domains&action=duplicateDialog', { domain_id }, function(html) {
                    if (html) {
                        $.waDialog({ html });
                    }
                });
                <?php }else{ ?>
                $.site.helper.showPremiumDialog();
                <?php }?>
                return false;
            });

        })(jQuery);
    </script>
</div>
<?php }} ?>