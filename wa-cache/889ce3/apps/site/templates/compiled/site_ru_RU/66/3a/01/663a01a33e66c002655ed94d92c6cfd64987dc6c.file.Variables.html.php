<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:46:01
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/variables/Variables.html" */ ?>
<?php /*%%SmartyHeaderCode:10431977676a8c835965fcc8-21431872%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '663a01a33e66c002655ed94d92c6cfd64987dc6c' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/variables/Variables.html',
      1 => 1740579100,
      2 => 'file',
    ),
    '6764a4e175d8ad228226f61b45b8f6d3eed24613' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/domain_tabs.html',
      1 => 1745480410,
      2 => 'file',
    ),
    '00ed78089345b18bf0e523f7010400a54aa955a7' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/variables/includes/form.html',
      1 => 1742293248,
      2 => 'file',
    ),
    '93c57d33c7d16f21629fe157f9d960b9403c731d' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/unsaved_dialog.html',
      1 => 1742293248,
      2 => 'file',
    ),
    '2218dcec5dd93f43872df2a03d5618d73c2af8ae' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/variables/includes/main_script.html',
      1 => 1745664893,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10431977676a8c835965fcc8-21431872',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    'domain_idn' => 0,
    'mode' => 0,
    'variables' => 0,
    'b' => 0,
    'variable' => 0,
    'wa_url' => 0,
    'blocks' => 0,
    'block' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c83596ba039_15048900',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c83596ba039_15048900')) {function content_6a8c83596ba039_15048900($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['wa']->value->getCheatSheetButton(array('is_block_page'=>true,'hide_common_blocks'=>true));?>

<script type="text/javascript">
    document.title = 'Переменные — ' + <?php echo json_encode($_smarty_tpl->tpl_vars['domain_idn']->value);?>
;
</script>
<div class="article site-base s-variables">
    <div class="article-body">
        <?php /*  Call merged included template "templates/actions/backend/includes/domain_tabs.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/actions/backend/includes/domain_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('selected'=>'variables'), 0, '10431977676a8c835965fcc8-21431872');
content_6a8c8359665268_79321524($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/actions/backend/includes/domain_tabs.html" */?>

        <div class="flexbox">
            <div class="sidebar flexbox blank bordered-right custom-pr-20 width-18rem" style="top: 8rem;height: calc(100vh - var(--sidebar-scroll-offset, 8rem));">
                <div class="sidebar-header custom-mb-8 custom-mt-16">
                    <div class="flexbox middle full-width custom-pb-8">
                        <h4 class="custom-mb-0">Переменные <span class="js-wa-tooltip cursor-pointer" data-wa-tooltip-template="#s-variables-tooltip-header" data-wa-tooltip-placement="bottom"><i class="fas fa-question-circle text-light-gray smaller" ></i></span></h4>
                        <span class="smaller">
                            <a href="javascript:void(0);" class="button circle js-add-variable" title="Новая переменная">
                                <i class="fas fa-plus"></i>
                            </a>
                        </span>
                    </div>
                    <template id="s-variables-tooltip-header">
                        <p>Переменные позволяют добавлять одинаковую информацию в разные места сайта и быстро редактировать ее в едином месте.</p>
                        <p>«Текстовые» переменные предназначены для отображения простой информации, например, контактных данных.</p>
                        <p>«Блоки и коды» можно использовать для вывода больших блоков информации, вставки кода для интеграции со сторонними сервисами и др.</p>
                    </template>
                </div>
                <div class="sidebar-body">
                    <div class="toggle js-variables-types-toggle flex">
                        <div class="width-50<?php if ($_smarty_tpl->tpl_vars['mode']->value=='variables'){?> selected<?php }?>" data-type="variables">
                            <i class="fas fa-book large"></i>
                            <p class="custom-mt-8 small">Текстовые</p>
                        </div>
                        <div class="width-50<?php if ($_smarty_tpl->tpl_vars['mode']->value=='blocks'){?> selected<?php }?>" data-type="blocks">
                            <i class="fas fa-code large"></i>
                            <p class="custom-mt-8 small">Блоки и коды</p>
                        </div>
                    </div>
                    <ul id="ul-variables" class="menu<?php if ($_smarty_tpl->tpl_vars['mode']->value=='blocks'){?> hidden<?php }?>">
                        <?php  $_smarty_tpl->tpl_vars['b'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['b']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['variables']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['b']->key => $_smarty_tpl->tpl_vars['b']->value){
$_smarty_tpl->tpl_vars['b']->_loop = true;
?>
                            <li data-variable-id="<?php echo $_smarty_tpl->tpl_vars['b']->value['id'];?>
" class="rounded<?php if (!isset($_smarty_tpl->tpl_vars['b']->value['app'])){?> sortable<?php }?><?php if ($_smarty_tpl->tpl_vars['variable']->value&&$_smarty_tpl->tpl_vars['b']->value['id']==$_smarty_tpl->tpl_vars['variable']->value['id']){?> selected<?php }?>">
                                <a href="javascript:void(0);">
                                    <?php if (isset($_smarty_tpl->tpl_vars['b']->value['app'])||isset($_smarty_tpl->tpl_vars['b']->value['app_icon'])){?>
                                        <span class="icon">
                                            <img src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php if (isset($_smarty_tpl->tpl_vars['b']->value['app'])){?><?php echo $_smarty_tpl->tpl_vars['b']->value['app']['icon'][16];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['b']->value['app_icon'][16];?>
<?php }?>" alt="">
                                        </span>
                                    <?php }else{ ?>
                                        <i class="fas fa-dollar-sign"></i>
                                    <?php }?>
                                    <span class="s-block-item">
                                        <div><?php echo htmlspecialchars((string)trim((($tmp = @$_smarty_tpl->tpl_vars['b']->value['description'])===null||$tmp==='' ? '' : $tmp)), ENT_QUOTES, 'UTF-8', true);?>
</div>
                                        <div class="hint"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['b']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
</div>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <ul id="ul-blocks" class="menu<?php if ($_smarty_tpl->tpl_vars['mode']->value=='variables'){?> hidden<?php }?>">
                        <?php  $_smarty_tpl->tpl_vars['b'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['b']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['blocks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['b']->key => $_smarty_tpl->tpl_vars['b']->value){
$_smarty_tpl->tpl_vars['b']->_loop = true;
?>
                            <li data-block-id="<?php echo $_smarty_tpl->tpl_vars['b']->value['id'];?>
" class="rounded<?php if (!isset($_smarty_tpl->tpl_vars['b']->value['app'])){?> sortable<?php }?><?php if ($_smarty_tpl->tpl_vars['block']->value&&$_smarty_tpl->tpl_vars['b']->value['id']==$_smarty_tpl->tpl_vars['block']->value['id']){?> selected<?php }?>">
                                <a href="javascript:void(0);">
                                    <?php if (isset($_smarty_tpl->tpl_vars['b']->value['app'])||isset($_smarty_tpl->tpl_vars['b']->value['app_icon'])){?>
                                        <span class="icon">
                                            <img src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php if (isset($_smarty_tpl->tpl_vars['b']->value['app'])){?><?php echo $_smarty_tpl->tpl_vars['b']->value['app']['icon'][16];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['b']->value['app_icon'][16];?>
<?php }?>" alt="">
                                        </span>
                                    <?php }else{ ?>
                                        <i class="fas fa-dollar-sign"></i>
                                    <?php }?>
                                    <span class="s-block-item">
                                        <div><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['b']->value['description'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</div>
                                        <div class="hint"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['b']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
</div>
                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="content js-variable-editor flexbox vertical full-width">
                <?php /*  Call merged included template "templates/actions/variables/includes/form.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/actions/variables/includes/form.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('_type'=>'variable','_item'=>$_smarty_tpl->tpl_vars['variable']->value), 0, '10431977676a8c835965fcc8-21431872');
content_6a8c83596933b9_89985894($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/actions/variables/includes/form.html" */?>
                <?php /*  Call merged included template "templates/actions/variables/includes/form.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/actions/variables/includes/form.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('_type'=>'block','_item'=>$_smarty_tpl->tpl_vars['block']->value), 0, '10431977676a8c835965fcc8-21431872');
content_6a8c83596933b9_89985894($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/actions/variables/includes/form.html" */?>
            </div>
        </div>
        
        <?php /*  Call merged included template "templates/actions/variables/includes/main_script.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/actions/variables/includes/main_script.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '10431977676a8c835965fcc8-21431872');
content_6a8c83596a68d7_05166021($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/actions/variables/includes/main_script.html" */?>
        <script>
            $(function () {
                initFixSidebarBottomOffset();

                function initFixSidebarBottomOffset () {
                    const nav_height = ($('#wa-nav').height() || 0) + ($('ul.s-tabs').height() || 0);
                    const offset_top = $('.sidebar-header').offset().top - 16;
                    document.documentElement.style.cssText = `--sidebar-scroll-offset:${ offset_top }px`;
                    const fixSidebarScrollOffset = () => {
                        const new_offset_top = offset_top - Math.min(offset_top, document.documentElement.scrollTop);
                        document.documentElement.style.setProperty('--sidebar-scroll-offset', `${ Math.max(new_offset_top, nav_height) }px`);
                    };
                    let timer = null;

                    $(document).off('scroll.variables_sidebar').on('scroll.variables_sidebar', function () {
                        if (timer) {
                            return;
                        }

                        fixSidebarScrollOffset();

                        timer = setTimeout(() => {
                            fixSidebarScrollOffset();
                            timer = null;
                        }, 200);
                    });
                }
            })
        </script>
    </div>
</div>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:46:01
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/domain_tabs.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c8359665268_79321524')) {function content_6a8c8359665268_79321524($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.replace.php';
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
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:46:01
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/variables/includes/form.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c83596933b9_89985894')) {function content_6a8c83596933b9_89985894($_smarty_tpl) {?><form id="site-form-<?php echo $_smarty_tpl->tpl_vars['_type']->value;?>
" class="height-100 fields<?php if ($_smarty_tpl->tpl_vars['mode']->value!=((string)$_smarty_tpl->tpl_vars['_type']->value)."s"){?> hidden<?php }?>" method="post" action="<?php echo $_smarty_tpl->tpl_vars['wa_backend_url']->value;?>
site/?module=<?php echo $_smarty_tpl->tpl_vars['_type']->value;?>
s&action=save<?php if ($_smarty_tpl->tpl_vars['_item']->value&&!isset($_smarty_tpl->tpl_vars['_item']->value['app'])){?>&id=<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_item']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
    <div class="custom-px-24">
        <div class="field">
            <div class="value">
                <h4 class="heading custom-mb-8 custom-mx-0">Описание</h4>
                <input type="text" class="full-width" name="info[description]" value="<?php if ($_smarty_tpl->tpl_vars['_item']->value){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_item']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
                <p class="hint">Не отображается на сайте. Заполнять необязательно.</p>
            </div>
        </div>
        <div class="field">
            <div class="value">
                <h4 class="heading custom-my-8 custom-mx-0">Идентификатор</h4>
                <input name="info[id]" type="text" class="full-width<?php if (!$_smarty_tpl->tpl_vars['_item']->value&&(!$_smarty_tpl->tpl_vars['is_new_variable']->value&&!$_smarty_tpl->tpl_vars['is_new_block']->value)){?> state-error<?php }?>" value="<?php if ($_smarty_tpl->tpl_vars['_item']->value){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_item']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" spellcheck="false" />
                <p class="hint">Используйте латинские буквы и дефис (вместо пробела), например, «company-name».</p>
            </div>
        </div>

        <?php if ($_smarty_tpl->tpl_vars['_item']->value){?>
            <h4 class="heading custom-mb-8 custom-mx-0">Добавьте переменную в страницу или в шаблон дизайна</h4>
            <div class="alert info small custom-m-0">
                <div class="flexbox middle full-width wrap-mobile space-12">
                    <strong class="js-code-preview break-all">&#123;$wa-&gt;<?php echo $_smarty_tpl->tpl_vars['_type']->value;?>
("<span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_item']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
</span>")&#125;</strong>
                    <div>
                        <button type="button" class="button light-gray js-copy-to-clipboard nowrap" data-clipboard-text="&#123;$wa-&gt;<?php echo $_smarty_tpl->tpl_vars['_type']->value;?>
(&quot;<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_item']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
&quot;)&#125;">
                            <i class="fas fa-copy"></i>
                            <span class="custom-ml-4">Скопировать в буфер обмена</span>
                        </button>
                    </div>
                </div>
            </div>
            <?php if ($_smarty_tpl->tpl_vars['_type']->value=='block'){?>
                <p class="hint custom-mt-8">
                    <?php echo sprintf_wp('You can pass additional parameters to a variable after a comma: %s<br>Instead of %s must be specified an associative array of parameters; e.g., %s<br>Each passed parameter is accessible in the code by its name as a Smarty variable; e.g., %s',sprintf("<code class='nowrap'>&#123%s-&gt;block('%s', <strong>%s</strong>)&#125;</code>",'$wa',htmlspecialchars((string)$_smarty_tpl->tpl_vars['_item']->value['id'], ENT_QUOTES, 'UTF-8', true),'$params'),'<em>$params</em>',"<code class='nowrap'>['<strong>first</strong>' =&gt; 100, '<strong>second</strong>' =&gt; 500]</code>",'<code class="nowrap">&#123$sum = <strong>$first</strong> + <strong>$second</strong>&#125</code>');?>

                </p>
            <?php }?>
        <?php }?>

        <h4 class="heading custom-mb-8 custom-mx-0">
            Содержимое, которое будет видно на сайте вместо переменной
            <span>
                <span class="s-varibles-use-smaty-hint custom-ml-4 small js-wa-tooltip" data-wa-tooltip-content="В этом поле можно использовать HTML и Smarty.">
                    <i class="fas fa-question-circle text-light-gray"></i>
                </span>
            </span>
        </h4>

        <?php if ($_smarty_tpl->tpl_vars['_type']->value=='block'&&$_smarty_tpl->tpl_vars['_item']->value){?>
            <div class="flexbox middle">
                <?php if (isset($_smarty_tpl->tpl_vars['_item']->value['original'])){?>
                    <button type="button" class="button outlined s-block-view-original small"><i class="fas fa-file-alt"></i> Посмотреть оригинал</button>
                <?php }?>
                <?php if (!isset($_smarty_tpl->tpl_vars['_item']->value['app'])&&isset($_smarty_tpl->tpl_vars['_item']->value['original'])){?>
                    <button id="s-block-delete" type="button" class="button outlined orange small"><i class="fas fa-undo-alt"></i> Восстановить из оригинала</button>
                <?php }?>
            </div>

            <div id="s-block-view-original-dialog" class="dialog">
                <div class="dialog-background"></div>
                <div class="dialog-body">
                    <h1 class="dialog-header"><?php echo $_smarty_tpl->tpl_vars['_item']->value['id'];?>
</h1>
                    <div class="dialog-content">
                        <template><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_item']->value['original'], ENT_QUOTES, 'UTF-8', true);?>
</template>
                    </div>
                    <div class="dialog-footer">
                        <button type="button" class="button light-gray js-close-dialog">Закрыть</button>
                    </div>
                </div>
            </div>
        <?php }?>

        <div class="s-editor-core-wrapper bordered custom-mt-16 custom-p-0 box rounded" style="overflow: hidden;">
            <div class="ace">
                <textarea id="<?php echo $_smarty_tpl->tpl_vars['_type']->value;?>
-content" name="info[content]" class="s-entire-core width-100"><?php if ($_smarty_tpl->tpl_vars['_item']->value){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_item']->value['content'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></textarea>
            </div>
        </div>
    </div>
    <div class="flexbox middle space-8 bottombar sticky width-100 custom-mt-20 custom-px-32">
        <button type="submit" class="button">Сохранить</button>
        <?php if (isset($_smarty_tpl->tpl_vars['_is_dialog']->value)){?>
            <button type="button" class="button light-gray js-close-dialog" id="s-variable-close">Закрыть</button>
        <?php }?>
        <button type="button" class="button nobutton js-cheatsheet-show"><i class="fas fa-code"></i> Шпаргалка</button>
        <?php if ($_smarty_tpl->tpl_vars['_item']->value&&!isset($_smarty_tpl->tpl_vars['_item']->value['app'])&&!isset($_smarty_tpl->tpl_vars['_item']->value['original'])){?>
            <button type="button" class="button nobutton red custom-ml-auto" id="s-<?php echo $_smarty_tpl->tpl_vars['_type']->value;?>
-delete"><i class="fas fa-trash-alt"></i> Удалить</button>
        <?php }?>
    </div>
</form>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:46:01
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/variables/includes/main_script.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c83596a68d7_05166021')) {function content_6a8c83596a68d7_05166021($_smarty_tpl) {?><?php /*  Call merged included template "templates/actions/backend/includes/unsaved_dialog.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/actions/backend/includes/unsaved_dialog.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '10431977676a8c835965fcc8-21431872');
content_6a8c83596aa156_14384988($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/actions/backend/includes/unsaved_dialog.html" */?>
<script>
$(function() {
    new class waVariables {
        constructor() {
            this.$variables_list = $("#ul-variables");
            this.$blocks_list = $("#ul-blocks");
            this.$variable_form = $("#site-form-variable");
            this.$block_form = $("#site-form-block");
            this.dialog = this.$variables_list.closest('.dialog').data('dialog') || {
                resize() { },
                close() { }
            };
            this.is_dialog = !!this.dialog.$content;
            this.$content = this.is_dialog ? $(this.dialog.$content.get(0)) : $('#wa-app');

            this.wa_backend_url = '<?php echo $_smarty_tpl->tpl_vars['wa_backend_url']->value;?>
';
            this.wa_url = '<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
';

            <?php if ($_smarty_tpl->tpl_vars['variable']->value){?>
                this.variable = {
                    id: <?php if (!empty($_smarty_tpl->tpl_vars['variable']->value['id'])){?>'<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['variable']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
'<?php }else{ ?>null<?php }?>,
                    app: <?php if (isset($_smarty_tpl->tpl_vars['variable']->value['app'])){?>true<?php }else{ ?>null<?php }?>,
                    original: <?php if (isset($_smarty_tpl->tpl_vars['variable']->value['original'])){?><?php echo json_encode($_smarty_tpl->tpl_vars['variable']->value['original']);?>
<?php }else{ ?>null<?php }?>,
                };
            <?php }else{ ?>
                this.variable = null;
            <?php }?>

            <?php if ($_smarty_tpl->tpl_vars['block']->value){?>
                this.block = {
                    id: <?php if (!empty($_smarty_tpl->tpl_vars['block']->value['id'])){?>'<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['block']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
'<?php }else{ ?>null<?php }?>,
                    app: <?php if (isset($_smarty_tpl->tpl_vars['block']->value['app'])){?>true<?php }else{ ?>null<?php }?>,
                    original: <?php if (isset($_smarty_tpl->tpl_vars['block']->value['original'])){?><?php echo json_encode($_smarty_tpl->tpl_vars['block']->value['original']);?>
<?php }else{ ?>null<?php }?>,
                };
            <?php }else{ ?>
                this.block = null;
            <?php }?>

            this.active_type = '<?php echo $_smarty_tpl->tpl_vars['mode']->value;?>
';
            this.is_block_page = '<?php echo !empty($_smarty_tpl->tpl_vars['is_block_page']->value);?>
';
            this.is_dialog = '<?php echo !empty($_smarty_tpl->tpl_vars['is_dialog']->value);?>
';
            this.cheat_sheet_name = '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['cheat_sheet_name']->value)===null||$tmp==='' ? "webasyst" : $tmp);?>
';

            this.form_has_changes = false;

            this.locales = {
                confirm_restore_block_title: 'Восстановить блок?',
                confirm_restore_variable_title: 'Восстановить переменную?',
                confirm_delete_block_title: 'Удалить блок?',
                confirm_delete_variable_title: 'Удалить переменную?',

                confirm_restore_block_text: 'Восстановление из оригинала сбросит все изменения, которые вы применяли к блоку.',
                confirm_restore_variable_text: 'Будут потеряны все изменения, которые вы внесли в эту переменную.',
                confirm_delete_block_text: 'Блок будет удален и перестанет подключаться в шаблонах и на страницах, на которые был добавлен до этого.',
                confirm_delete_variable_text: 'После удаления переменная перестанет работать в шаблонах и на страницах сайта, где она использовалась.',
            };

            this.init();
        }

    
        init() {
            $('.js-wa-tooltip').waTooltip();

            this.initEditor('block');
            this.sortItems();
            this.contentUpdate();
            this.onSubmit();
            this.onToggle();
            this.onShowCheatsheet();
            this.onShowOriginal();
            if (this.variable?.id || this.block?.id) {
                this.onDelete();
                this.copyToClipboard();
            }
            if ((this.active_type === 'variables' && !this.variable) || (this.active_type === 'blocks' && !this.block)) {
                this.initTranslit();
            }
            this.onUnsaved();
        }

        initEditor(id) {
            waEditorAceInit({
                id: `${id}-content`,
                ace_editor_container: `wa-ace-editor-${id}-dialog`,
            });
            wa_editor.setOption('fontSize', 14);
            wa_editor.setOption('minLines', 10);
            this.dialog.resize();
        }

        initTranslit() {
            $.fn.onKeyFinish = function (defer, callback) {
                return this.each(function () {
                    var that = $(this);
                    that.currentValue = that.val();
                    that.interval = null;
                    $(this).off('keyup.wa_variables').on('keyup.wa_variables', function (e) {
                        clearInterval(that.interval);
                        if (that.currentValue != that.val()) {
                            that.interval = setInterval(function () {
                                clearInterval(that.interval);
                                callback.call(that);
                                that.currentValue = that.val();
                            }, defer);
                        }
                    });
                });
            };

            const $input_id = this.$content.find('input[name="info[id]"]:visible');

            this.$content.find('input[name="info[description]"]').onKeyFinish(300, function () {
                const url = $(this).val();
                if (url && (!$input_id.val() || !$input_id.data('changed'))) {
                    $.post("?module=htmlPages&action=translit", { str: url }, function (response) {
                        if (response.status === 'ok') {
                            if (!$input_id.val() || !$input_id.data('changed')) {
                                $input_id.val(response.data.str);
                            }
                        }
                    }, "json");
                }
            });

            $input_id.on('keyup', function () {
                const $self = $(this);

                $self.data('changed', 1);
                if (!$self.val()) {
                    $self.data('changed', 0);
                }
            });
        }

        sortItems() {
            const self = this;

            if (window.Sortable === undefined) {
                const $script = $("#wa-header-js");
                const path = $script.attr('src').replace(/wa-content\/js\/jquery-wa\/wa.header.js.*$/, '');

                const urls = [
                    "wa-content/js/sortable/sortable.min.js",
                    "wa-content/js/sortable/jquery-sortable.min.js",
                ];

                const loadScript = (url) => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            cache: true,
                            dataType: "script",
                            url: path + url,
                            success: resolve,
                            error: reject
                        });
                    });
                };

                loadScript(urls[0])
                    .then(() => loadScript(urls[1]))
                    .then(() => sort())
                    .catch(error => console.error(error));
            } else {
                sort()
            }
            function sort() {
                self.$variables_list.sortable({
                    animation: 150,
                    draggable: 'li.sortable',
                    onEnd: function (event) {
                        const li = $(event.item);
                        const id = li.data('variable-id');
                        const pos = li.prevAll('li.sortable').length + 1;
                        $.post(`${self.wa_backend_url}site/?module=variables&action=sort`, { id: id, pos: pos}, function () {
                        }, "json");
                    }
                });

                self.$blocks_list.sortable({
                    animation: 150,
                    draggable: 'li.sortable',
                    onEnd: function (event) {
                        const li = $(event.item);
                        const id = li.data('block-id');
                        const pos = li.prevAll('li.sortable').length + 1;
                        $.post(`${self.wa_backend_url}site/?module=blocks&action=sort`, { id: id, pos: pos}, function () {
                        }, "json");
                    }
                });
            }
        }

        onSubmit() {
            const self = this;
            self.$content.off('click').on('click', 'form:not(.hidden) [type=submit]', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                const $submit_btn = $(this);
                const form = $submit_btn.closest('form');
                const type = form.attr('id').includes('block') ? 'block' : 'variable';
                const original_submit_name = $submit_btn.text();

                waEditorUpdateSource({ 'id': 'block-content'});

                $(".state-error").removeClass('state-error');
                $(".state-error-hint").remove();
                $.post(form.attr('action'), form.serialize(), function (response) {
                    if (response.status == 'ok') {
                        const { data } = response;

                        $submit_btn.html('<i class="fas fa-check-circle"></i> Сохранено');
                        $submit_btn.removeClass('red').addClass('green');

                        const blockHtml = function(b) {
                            let icon = '<i class="fas fa-dollar-sign"></i>';
                            if (b.app_icon) {
                                icon = `<span class="icon"><img src="${wa_url}${b.app_icon["16"]}" alt=""></span>`;
                            }

                            return `<li data-${type}-id="${b.id}" class="rounded selected sortable">
                                        <a href="javascript:void(0);">
                                            ${icon}
                                            <span class="s-block-item">
                                                <div>${b.description.trim()}</div>
                                                <div class="hint">${b.id}</div>
                                            </span>
                                        </a>
                                    </li>`;
                        }

                        const blocks_ul = $(`#ul-${type}s`);

                        form.attr('action', updateFormActionIdParam(form.attr('action'), `id=${data.id}`));
                        form.find('.js-code-preview span').text(data.id || '');
                        form.find('[data-clipboard-text]').data('clipboard-text', form.find('.js-code-preview').text());

                        if (!self[type] || self[type].app) {

                            if (self[type]){
                                blocks_ul.find(`li[data-${type}-id="${data.id}"]`).remove();
                            }

                            blocks_ul.find("li.selected").removeClass('selected');

                            let insert_target = blocks_ul.find("li.sortable:last");

                            if (insert_target.length) {
                                insert_target.after(blockHtml(data));
                            } else {
                                insert_target = blocks_ul.find("li:first");
                                if (insert_target.length) {
                                    insert_target.before(blockHtml(data));
                                } else {
                                    blocks_ul.append(blockHtml(data));
                                }
                            }

                            if (!self[type]) {
                                blocks_ul.find(`li[data-${type}-id="${data.id}"] a`).click();
                            }
                        } else {
                            const li = blocks_ul.find(`li[data-${type}-id="${(data.old_id || data.id)}"]`);
                            if (data.old_id) {
                                li.replaceWith(blockHtml(data));
                            } else {
                                const $hint = li.find('.hint');
                                if ($hint.prev('div').length) {
                                    $hint.prev('div').html(data.description);
                                } else {
                                    $hint.before('<div>' + data.description + '</div>');
                                }
                            }
                        }
                        self.form_has_changes = false;
                    } else if (response.status == 'fail') {
                        if ($.isArray(response.errors)) {
                            const $field_val = form.find(response.errors[1]).addClass('state-error');
                            if (response.errors[2]) {
                                $field_val.after(`<div class="state-error-hint">${response.errors[0] || ''}</div>`);
                            }
                        } else {
                            err = 'Ошибка: ' + response.errors;
                            alert(err);
                        }
                        $submit_btn.removeClass('green').addClass('red');
                    }
                    setTimeout(() => {
                        $submit_btn.text(original_submit_name);
                        $submit_btn.removeClass(['green', 'red']);
                    }, 1500);
                }, "json");

                return false;
            });

            function updateFormActionIdParam(actionUrl, idParam) {
                const hasId = actionUrl.includes('id=');

                // Если параметр id уже существует, заменяем его
                if (hasId) {
                    return actionUrl.replace(/([?&])id=[^&]*/, `$1${idParam}`);
                }

                // Если параметра нет, добавляем его
                const separator = actionUrl.includes('?') ? '&' : '?';
                return `${actionUrl}${separator}${idParam}`;
            }
        }

        onToggle(){
            const self = this;

            $(".js-variables-types-toggle").waToggle({
                change: function(event, target, toggle) {
                    const type = $(target).data('type');
                    self.active_type = type;
                    self.$variables_list.toggleClass('hidden', type === 'blocks');
                    self.$blocks_list.toggleClass('hidden', type === 'variables');
                    self.$variable_form.toggleClass('hidden', type === 'blocks');
                    self.$block_form.toggleClass('hidden', type === 'variables');
                    self.dialog.resize();
                }
            });
        }

        contentUpdate(){
            const self = this;

            self.$variables_list.on('click', 'a', function(){
                update($(this).parent().data('variable-id'), 'variable_');
            });

            self.$blocks_list.on('click', 'a', function(){
                update($(this).parent().data('block-id'), '');
            });

            $('.js-add-variable').on('click', () => {
                if (self.form_has_changes) {
                    return false;
                }
                update('', self.active_type === 'blocks' ? '' : 'variable_')
            });

            function update(id = '', type = 'variable_') {
                const url = `${self.wa_backend_url}site/?module=variables&${type}id=${id}&is_block_page=${self.is_block_page}&is_dialog=${self.is_dialog}`;
                $.get(url, function(html) {
                    self.$content.html(html);
                    setTimeout(() => self.dialog.resize());
                });
            }

            self.$content.find('input[name="info[id]"]').on('focus', function() {
                $(this).removeClass('state-error');
            });
        }

        onDelete(){
            const self = this;

            $("#s-variable-delete").on('click', function () {
                self.form_has_changes = false;
                const is_original = !!self.variable?.original;
                self.confirmDeleteDialog({
                    title: is_original ? self.locales.confirm_restore_variable_title : self.locales.confirm_delete_variable_title,
                    text: is_original ? self.locales.confirm_restore_variable_text : self.locales.confirm_delete_variable_text,
                    is_original,
                    onSuccess: () => {
                        $.post(`${self.wa_backend_url}site/?module=variables&action=delete`, { id: self.variable.id }, function (response) {
                            if (response.status == 'ok') {
                                const $ul_blocks = $("#ul-variables");
                                $(".js-variable-editor").empty();
                                $ul_blocks.find(`li[data-variable-id="${self.variable.id}"]`).remove();

                                if($ul_blocks.find('li').length) {
                                    $ul_blocks.find('li:first a').click();
                                }
                            }
                        }, "json");
                    }
                });

                return false;
            });

            $("#s-block-delete").on('click', function () {
                self.form_has_changes = false;
                const is_original = !!self.block?.original;
                self.confirmDeleteDialog({
                    title: is_original ? self.locales.confirm_restore_block_title : self.locales.confirm_delete_block_title,
                    text: is_original ? self.locales.confirm_restore_block_text : self.locales.confirm_delete_block_text,
                    is_original,
                    onSuccess: () => {
                        $.post(`${self.wa_backend_url}site/?module=blocks&action=delete`, { id: self.block.id }, function (response) {
                            if (response.status == 'ok') {
                                const $ul_blocks = $("#ul-blocks");
                                $(".js-variable-editor").empty();
                                $ul_blocks.find(`li[data-block-id="${self.block.id}"]`).remove();

                                if($ul_blocks.find('li').length) {
                                    $ul_blocks.find('li:first a').click();
                                }
                            }
                        }, "json");
                    }
                });

                return false;
            });
        }

        copyToClipboard(){
            $('.js-copy-to-clipboard').on('click', async function () {
                const $btn = $(this);
                const $icon = $btn.find('[data-icon]');
                const $btn_text_wrapper = $btn.find('span');
                const $btn_text = $btn_text_wrapper.text();

                try {
                    await $.wa.copyToClipboard($(this).data('clipboard-text'));

                    $btn.addClass('green');
                    $icon.attr('data-icon', 'check-circle');
                    $btn_text_wrapper.text('Скопировано');
                } catch (e) {
                    console.error(e);

                    $btn.addClass('red');
                    $icon.attr('data-icon', 'times-circle');
                    $btn_text_wrapper.text('Ошибка копирования');
                } finally {
                    setTimeout(() => {
                        $btn.removeClass('green red');
                        $btn.find('[data-icon]').attr('data-icon', 'copy');
                        $btn_text_wrapper.text($btn_text);
                    }, 1000)
                }
            });
        }

        onShowCheatsheet(){
            $('.js-cheatsheet-show').on('click', () => {
                $(`#wa-editor-help-link-${this.cheat_sheet_name}`).click();
                setTimeout(() => $(`#wa-editor-help-${this.cheat_sheet_name}`).show());
            });
        }

        onShowOriginal() {
            $('.s-block-view-original').on('click', function() {
                const d = $('#s-block-view-original-dialog');
                if (d.length) {
                    $.waDialog({
                        $wrapper: d.clone(),
                        onOpen: function($dialog, dialog) {
                            dialog.$content.append(`<div id="s-block-original" style="width: 100%; height: 220px;"></div>`);
                            $('#s-block-original').html($dialog.find('template').html());

                            const editor = ace.edit('s-block-original');
                            ace.config.set("basePath", wa_url + 'wa-content/js/ace/');

                            setEditorTheme();
                            document.documentElement.addEventListener('wa-theme-change', setEditorTheme);

                            function setEditorTheme() {
                                const theme = document.documentElement.dataset.theme;

                                if (theme === 'dark') {
                                    editor.setTheme("ace/theme/monokai");
                                } else {
                                    editor.setTheme("ace/theme/eclipse");
                                }
                            }

                            const session = editor.getSession();
                            session.setMode("ace/mode/css");
                            session.setMode("ace/mode/javascript");
                            session.setMode("ace/mode/smarty");

                            session.setUseWrapMode(true);
                            editor.renderer.setShowGutter(false);
                            editor.setShowPrintMargin(false);
                            editor.setFontSize(13);
                            editor.setHighlightActiveLine(false);
                            editor.setReadOnly(true);

                            setTimeout(function () {
                                let newHeight = editor.getSession().getScreenLength() * editor.renderer.lineHeight + editor.renderer.scrollBar.getWidth();
                                if (newHeight < 220) {
                                    newHeight = 220;
                                }
                                $('#s-block-original').height(newHeight.toString() + "px");
                                editor.resize();
                                dialog.resize();
                            }, 50);
                        }
                    });
                }
                return false;
            });
        }

        onUnsaved() {
            const self = this;

            // bind events
            const change_event = 'input.wa_variables_onunsaved';
            this.$variable_form.off(change_event).on(change_event, function () {
                if ($(this).is(':visible')) {
                    self.form_has_changes = true;
                }
            });
            this.$block_form.off(change_event).on(change_event, function () {
                if ($(this).is(':visible')) {
                    self.form_has_changes = true;
                }
            });
            wa_editor.getSession().on('change', () => {
                self.form_has_changes = true;
            });

            // show dialog
            const $forms = this.$variable_form.add(this.$block_form);
            const $links = $(`${self.is_dialog ? '.dialog .s-variables a:not([target="_blank"])' : '#wa a:not([target="_blank"])'}, .js-variables-types-toggle > [data-type]`);
            const event_name = 'click.wa_variables_unsaved';

            $links.off(event_name).on(event_name, showDialog);

            if (self.is_dialog) {
                const oldOnClose = self.dialog.onClose;
                self.dialog.onClose = () => {
                    return oldOnClose() && showDialog(null, () => {
                        self.dialog.close();
                    });
                };
            }

            const unbindEvent = () => $links.off(event_name);

            function showDialog (e, onClose) {
                if (!self.form_has_changes) {
                    if (!e || !$(this).closest('.js-variables-types-toggle').length) {
                        unbindEvent();
                    }
                    return true;
                }

                const $a = $(this);
                if ($a.attr('href') === '#' || String($a.attr('href')).startsWith('javascript:')) {
                    return true;
                }

                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                $.confirmUnsaved({
                    onSave() {
                        self.form_has_changes = false;
                        unbindEvent();
                        $forms.filter(':visible').find('[type="submit"]').click();
                        self.dialog.close();
                        $a.click();
                    },
                    onLeave() {
                        self.form_has_changes = false;
                        unbindEvent();
                        if (typeof onClose === 'function') {
                            onClose();
                        } else {
                            $a.click();
                        }
                    }
                });

                return false;
            };
        }

        confirmDeleteDialog({ title, text, is_original, onSuccess }) {
            $.waDialog.confirm({
                title,
                text,
                success_button_title: is_original ? 'Восстановить из оригинала' :$_('Delete'),
                success_button_class: is_original ? 'orange' : 'danger',
                cancel_button_title: $_('Cancel'),
                cancel_button_class: 'light-gray',
                onSuccess
            });
        }
    }
    
})
</script>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:46:01
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/unsaved_dialog.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c83596aa156_14384988')) {function content_6a8c83596aa156_14384988($_smarty_tpl) {?><template id="unsaved-form-dialog-template">
    <div class="dialog">
        <div class="dialog-background"></div>
        <div class="dialog-body">
            <div class="dialog-header">
                <h2>Сохранить изменения?</h2>
            </div>
            <div class="dialog-content">
                <p>Ваши изменения будут потеряны, если их не сохранить.</p>
            </div>
            <div class="dialog-footer flexbox middle">
                <button class="js-save-button button green" type="button">Сохранить</button>
                <button class="js-dialog-close button light-gray" type="button">Остаться</button>
                <button class="js-leave-button button outlined orange custom-ml-auto" type="button">Уйти без сохранения</button>
            </div>
        </div>
    </div>
</template>
<script>
    (function ($) {
        $.confirmUnsaved = function ({ onSave = () => null, onLeave = () => null }) {
            $.waDialog({
                html: $('#unsaved-form-dialog-template').html(),
                onOpen ($d, d) {
                    d.$block.find('.js-save-button').on('click', () => {
                        if (typeof onSave === 'function') {
                            onSave();
                        }
                        d.close();
                    });
                    d.$block.find('.js-leave-button').on('click', () => {
                        if (typeof onLeave === 'function') {
                            onLeave();
                        }
                        d.close();
                    });
                }
            })
        }
    })(jQuery);
</script>
<?php }} ?>