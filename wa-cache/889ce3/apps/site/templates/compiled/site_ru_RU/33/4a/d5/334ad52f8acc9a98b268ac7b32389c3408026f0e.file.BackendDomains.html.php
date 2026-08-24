<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:15
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/BackendDomains.html" */ ?>
<?php /*%%SmartyHeaderCode:12183837356a889cf77c8ac5-80536652%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '334ad52f8acc9a98b268ac7b32389c3408026f0e' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/BackendDomains.html',
      1 => 1752051920,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12183837356a889cf77c8ac5-80536652',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    'sort' => 0,
    'sort_types' => 0,
    '_id' => 0,
    '_type' => 0,
    'is_list_view' => 0,
    'domains' => 0,
    'd' => 0,
    'protocol' => 0,
    'full_url' => 0,
    'domain_id' => 0,
    'is_alias' => 0,
    'wa_app_url' => 0,
    'frontend_link' => 0,
    'redirect' => 0,
    'main_link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889cf77f5136_49396852',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889cf77f5136_49396852')) {function content_6a889cf77f5136_49396852($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
if (!is_callable('smarty_modifier_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.replace.php';
?><script>
    document.title = "<?php echo $_smarty_tpl->tpl_vars['wa']->value->appName();?>
 — " + <?php echo json_encode($_smarty_tpl->tpl_vars['wa']->value->accountName(false));?>
;
</script>
<div class="article site-base">
  <div class="article-body">
    <div class="s-domains-page" id="s-domains-page" data-view="grid">
        <div class="s-domains-header flexbox wrap full-width custom-my-20 space-12">
            <h1 class="wide">Мои сайты</h1>
            <button class="s-domains-add js-domains-add custom-mr-0">
                <i class="fas fa-plus"></i>
                <span class="desktop-and-tablet-only">Новый сайт</span>
            </button>

            <div id="dropdown-sort" class="dropdown">
                <button type="button" class="dropdown-toggle button light-gray">
                    <span class="icon"><?php echo $_smarty_tpl->tpl_vars['sort_types']->value[$_smarty_tpl->tpl_vars['sort']->value]['icon'];?>
</span>
                    <span class="name desktop-and-tablet-only"><?php echo $_smarty_tpl->tpl_vars['sort_types']->value[$_smarty_tpl->tpl_vars['sort']->value]['title'];?>
</span>
                </button>
                <div class="dropdown-body right custom-mt-8">
                    <ul class="menu">
                        <?php  $_smarty_tpl->tpl_vars['_type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_type']->_loop = false;
 $_smarty_tpl->tpl_vars['_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['sort_types']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_type']->key => $_smarty_tpl->tpl_vars['_type']->value){
$_smarty_tpl->tpl_vars['_type']->_loop = true;
 $_smarty_tpl->tpl_vars['_id']->value = $_smarty_tpl->tpl_vars['_type']->key;
?>
                        <li<?php if ($_smarty_tpl->tpl_vars['sort']->value===$_smarty_tpl->tpl_vars['_id']->value){?> class="selected"<?php }?>>
                            <a href="javascript:void(0)" data-id="<?php echo $_smarty_tpl->tpl_vars['_id']->value;?>
">
                                <span class="icon"><?php echo $_smarty_tpl->tpl_vars['_type']->value['icon'];?>
</span>
                                <span class="name"><?php echo $_smarty_tpl->tpl_vars['_type']->value['title'];?>
</span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="toggle" id="toggle-list-view">
                <span data-toggle-id="grid">
                    <svg width="18" height="18" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.99998 2.3999H13.8C14.1313 2.3999 14.4 2.66853 14.4 2.9999V6.9999C14.4 7.33128 14.1313 7.5999 13.8 7.5999H8.99998C8.6686 7.5999 8.39998 7.33128 8.39998 6.9999V2.9999C8.39998 2.66853 8.6686 2.3999 8.99998 2.3999ZM6.99998 2.3999H2.19998C1.8686 2.3999 1.59998 2.66853 1.59998 2.9999V6.9999C1.59998 7.33128 1.8686 7.5999 2.19998 7.5999H6.99998C7.33135 7.5999 7.59998 7.33128 7.59998 6.9999V2.9999C7.59998 2.66853 7.33135 2.3999 6.99998 2.3999ZM1.59998 8.9999V12.9999C1.59998 13.3313 1.8686 13.5999 2.19998 13.5999H6.99998C7.33135 13.5999 7.59998 13.3313 7.59998 12.9999V8.9999C7.59998 8.66853 7.33135 8.3999 6.99998 8.3999H2.19998C1.8686 8.3999 1.59998 8.66853 1.59998 8.9999ZM8.99998 13.5999H13.8C14.1313 13.5999 14.4 13.3313 14.4 12.9999V8.9999C14.4 8.66853 14.1313 8.3999 13.8 8.3999H8.99998C8.6686 8.3999 8.39998 8.66853 8.39998 8.9999V12.9999C8.39998 13.3313 8.6686 13.5999 8.99998 13.5999Z" fill="currentColor" />
                    </svg>
                </span>
                <span data-toggle-id="list">
                    <svg width="18" height="18" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 2.5C2.44772 2.5 2 2.94772 2 3.5C2 4.05228 2.44772 4.5 3 4.5H13C13.5523 4.5 14 4.05228 14 3.5C14 2.94772 13.5523 2.5 13 2.5H3Z" fill="currentColor" />
                        <path d="M3 8.5C2.44772 8.5 2 8.94772 2 9.5C2 10.0523 2.44772 10.5 3 10.5H13C13.5523 10.5 14 10.0523 14 9.5C14 8.94772 13.5523 8.5 13 8.5H3Z" fill="currentColor" />
                        <path d="M2 6.5C2 5.94772 2.44772 5.5 3 5.5H13C13.5523 5.5 14 5.94772 14 6.5C14 7.05228 13.5523 7.5 13 7.5H3C2.44772 7.5 2 7.05228 2 6.5Z" fill="currentColor" />
                        <path d="M3 11.5C2.44772 11.5 2 11.9477 2 12.5C2 13.0523 2.44772 13.5 3 13.5H13C13.5523 13.5 14 13.0523 14 12.5C14 11.9477 13.5523 11.5 13 11.5H3Z" fill="currentColor" />
                    </svg>
                </span>
            </div>
            <script>
                (function($) {
                    const $toggle_list_view = $('#toggle-list-view');
                    let skip_update = true;

                    $toggle_list_view.waToggle({
                        change(event, target, toggle) {
                            const toggle_id = toggle.$active.data('toggle-id');
                            $('#s-domains-page').attr('data-view', toggle_id);

                            if (!skip_update) {
                                const action = toggle_id === 'list' ? 'set' : 'delete';
                                $.post('?module=domains&action=listView', { action });
                            }
                            skip_update = false;
                        }
                    });

                    $toggle_list_view.find(`[data-toggle-id="<?php if (!empty($_smarty_tpl->tpl_vars['is_list_view']->value)){?>list<?php }else{ ?>grid<?php }?>"]`).trigger('click');
                })(jQuery);
            </script>
        </div>

        <div class="s-domains-list" id="js-domains-list">
        <?php if (waRequest::isHttps()){?>
            <?php $_smarty_tpl->tpl_vars['protocol'] = new Smarty_variable('https://', null, 0);?>
        <?php }else{ ?>
            <?php $_smarty_tpl->tpl_vars['protocol'] = new Smarty_variable('http://', null, 0);?>
        <?php }?>
        <?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_smarty_tpl->tpl_vars['domain_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['domains']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
$_smarty_tpl->tpl_vars['d']->_loop = true;
 $_smarty_tpl->tpl_vars['domain_id']->value = $_smarty_tpl->tpl_vars['d']->key;
?>
            <?php $_smarty_tpl->tpl_vars['is_alias'] = new Smarty_variable($_smarty_tpl->tpl_vars['d']->value['is_alias'], null, 0);?>
            <?php $_smarty_tpl->tpl_vars['redirect'] = new Smarty_variable(empty($_smarty_tpl->tpl_vars['d']->value['redirect']) ? null : $_smarty_tpl->tpl_vars['d']->value['redirect'], null, 0);?>
            <?php if (!empty($_smarty_tpl->tpl_vars['d']->value['frontend_link'])){?>
                <?php $_smarty_tpl->tpl_vars['frontend_link'] = new Smarty_variable($_smarty_tpl->tpl_vars['d']->value['frontend_link'], null, 0);?>
            <?php }elseif(!empty($_smarty_tpl->tpl_vars['d']->value)){?>
                <?php $_smarty_tpl->tpl_vars['full_url'] = new Smarty_variable('', null, 0);?>
                <?php if (isset($_smarty_tpl->tpl_vars['d']->value['full_url'])){?>
                    <?php $_smarty_tpl->tpl_vars['full_url'] = new Smarty_variable("/".((string)$_smarty_tpl->tpl_vars['d']->value['full_url']), null, 0);?>
                    <?php if (isset($_smarty_tpl->tpl_vars['d']->value['route'])){?>
                        <?php $_smarty_tpl->tpl_vars['full_url'] = new Smarty_variable("/".((string)rtrim($_smarty_tpl->tpl_vars['d']->value['route'],'*')).((string)$_smarty_tpl->tpl_vars['d']->value['full_url']), null, 0);?>
                    <?php }?>
                <?php }elseif(isset($_smarty_tpl->tpl_vars['d']->value['url_formatted'])){?>
                    <?php $_smarty_tpl->tpl_vars['full_url'] = new Smarty_variable($_smarty_tpl->tpl_vars['d']->value['url_formatted'], null, 0);?>
                <?php }?>
                <?php $_smarty_tpl->tpl_vars['frontend_link'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['protocol']->value).((string)$_smarty_tpl->tpl_vars['d']->value['name']).((string)$_smarty_tpl->tpl_vars['full_url']->value), null, 0);?>
            <?php }?>

            <div data-domain-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain_id']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="flexbox">
                <?php if ($_smarty_tpl->tpl_vars['is_alias']->value){?>
                    <?php $_smarty_tpl->tpl_vars['main_link'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."?module=configure&domain_id=".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain_id']->value, ENT_QUOTES, 'UTF-8', true)), null, 0);?>
                <?php }else{ ?>
                    <?php $_smarty_tpl->tpl_vars['main_link'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."?module=map&action=overview&domain_id=".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain_id']->value, ENT_QUOTES, 'UTF-8', true)), null, 0);?>
                <?php }?>
                <div class="card cursor-pointer">
                    <div class="s-domain-view flex">
                        <?php if ($_smarty_tpl->tpl_vars['is_alias']->value){?>
                            <span class="icon size-48">
                                <i class="fas fa-clone text-light-gray"></i>
                            </span>
                        <?php }elseif(!empty($_smarty_tpl->tpl_vars['d']->value['is_pending'])){?>
                            <span class="icon size-48">
                                <i class="fas fa-clock text-light-gray"></i>
                            </span>
                        <?php }elseif(!empty($_smarty_tpl->tpl_vars['d']->value['redirect'])){?>
                            <span class="icon size-48">
                                <i class="fas fa-sign-in-alt text-light-gray"></i>
                            </span>
                        <?php }else{ ?>
                            <div class="image s-frame-wrapper" id="s-frame-wrapper-<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain_id']->value, ENT_QUOTES, 'UTF-8', true);?>
" style="visibility: hidden;position: absolute;">
                                <div class="blank-cover"></div>
                                <iframe sandbox="allow-scripts" scrolling="no" loading="lazy" onload="onIframeDomainLoaded(this)" src="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['frontend_link']->value, ENT_QUOTES, 'UTF-8', true);?>
"></iframe>
                            </div>
                            <span class="icon size-48">
                                <i class="fas fa-globe text-light-gray"></i>
                            </span>
                        <?php }?>
                    </div>
                    <div class="s-domain-title flexbox full-width space-8 custom-pt-12">
                        <div class="box-icon icon">
                            <?php if ($_smarty_tpl->tpl_vars['is_alias']->value){?>
                            <i class="fas fa-copy"></i>
                            <?php }elseif($_smarty_tpl->tpl_vars['redirect']->value){?>
                            <i class="fas fa-sign-in-alt"></i>
                            <?php }elseif(!empty($_smarty_tpl->tpl_vars['d']->value['favicon'])){?>
                            <i style="background-image: url('<?php echo $_smarty_tpl->tpl_vars['d']->value['favicon'];?>
');"></i>
                            <?php }else{ ?>
                            <i class="fas fa-globe"></i>
                            <?php }?>
                        </div>
                        <div class="title-wrapper wide">
                            <h4 class="title custom-m-0">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['main_link']->value;?>
">
                                    <span class="title-full"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['d']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                                    <span class="title-truncated"><?php echo htmlspecialchars((string)smarty_modifier_truncate($_smarty_tpl->tpl_vars['d']->value['title'],40,'...'), ENT_QUOTES, 'UTF-8', true);?>
</span>
                                </a>
                            </h4>
                        </div>

                        <div>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['main_link']->value;?>
" class="js-main-link"></a>
                        </div>
                        <div class="favicon-icon flexbox custom-mt-4">
                            <i class="icon custom-mt-2" style="background-image: url('<?php if (!empty($_smarty_tpl->tpl_vars['d']->value['favicon'])){?><?php echo $_smarty_tpl->tpl_vars['d']->value['favicon'];?>
<?php }?>');"></i>
                        </div>
                        <div class="box hidden"><a class="dropdown-toggle">
                            <i class="fas fa-ellipsis-h gray"></i>
                        </a></div>
                    </div>

                    <div class="s-domain-hint s-grid">
                        <?php if ($_smarty_tpl->tpl_vars['is_alias']->value){?>
                            <div class="hint text-ellipsis"><?php echo sprintf_wp('Mirror site (alias) for <strong>%s</strong>',smarty_modifier_replace(waIdna::dec(htmlspecialchars((string)$_smarty_tpl->tpl_vars['d']->value['is_alias'], ENT_QUOTES, 'UTF-8', true)),'www.',''));?>
</div>
                        <?php }elseif($_smarty_tpl->tpl_vars['redirect']->value){?>
                            <div class="hint text-ellipsis"><?php echo sprintf_wp('Redirected to <strong>%s</strong>',waIdna::dec(htmlspecialchars((string)smarty_modifier_replace($_smarty_tpl->tpl_vars['redirect']->value,array('http://','https://','www.'),''), ENT_QUOTES, 'UTF-8', true)));?>
</div>
                        <?php }?>
                    </div>

                </div>
            </div>

        <?php } ?>
        </div>
    </div>

    
    <div id="s-addsite-dialog-wrapper">
        <div class="dialog" id="s-addsite-dialog">
            <div class="dialog-background"> </div>
            <div class="dialog-body">
                <form>
                    <header class="dialog-header"><h1>Новый сайт</h1></header>
                    <div class="dialog-content">
                        <div class="custom-pb-20">
                            <span class="bold custom-pr-4">http://</span><input type="text" id="domain-name" name="name" class="bold long" value="" placeholder="mydomain.ru">
                            <div class="hint custom-pt-4">Например, mydomain.ru, www.mydomain.ru, subdomain.mydomain.ru</div>
                        </div>
                        <div class="custom-pb-20 s-small">Доменное имя должно быть зарегистрировано и настроено, чтобы указывать на этот аккаунт Webasyst.
                            <a href="https://support.webasyst.ru/47313/get-domain-name/" target="_blank" class="nowrap">
                                Помощь <i class="icon fas fa-external-link-alt smaller"></i>
                            </a>
                        </div>
                        <div>
                            <div class="s-add-menu">
                                <div class="custom-pb-12">
                                    <label>
                                        <span class="wa-radio">
                                            <input type="radio" name="alias" value="0" checked>
                                            <span></span>
                                        </span>
                                        Новый сайт
                                    </label>
                                </div>
                                <div class="custom-pb-8">
                                    <label>
                                        <span class="wa-radio">
                                            <input type="radio" name="alias" value="1">
                                            <span></span>
                                        </span>
                                        Зеркало сайта
                                        <span class="wa-select custom-ml-8">
                                            <select id="alias-domain" name="domain">
                                                <?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_smarty_tpl->tpl_vars['d_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['domains']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
$_smarty_tpl->tpl_vars['d']->_loop = true;
 $_smarty_tpl->tpl_vars['d_id']->value = $_smarty_tpl->tpl_vars['d']->key;
?>
                                                    <?php if (empty($_smarty_tpl->tpl_vars['d']->value['is_alias'])){?>
                                                        <option value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['d']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo smarty_modifier_replace(waIdna::dec(htmlspecialchars((string)$_smarty_tpl->tpl_vars['d']->value['name'], ENT_QUOTES, 'UTF-8', true)),'www.','');?>
</option>
                                                    <?php }?>
                                                <?php } ?>
                                            </select>
                                        </span>
                                        <script type="text/javascript">
                                            $('#alias-domain').on('change', function () {
                                                $('#alias-domain').parent().find('input').attr('checked', 'checked');
                                            });
                                        </script>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="dialog-footer">
                        <div class="flexbox middle wrap space-8">
                            <input type="submit" class="button" value="Создать сайт">
                            <a href="#" class="js-close-dialog button light-gray">Отмена</a>
                            <span class="state-error-hint"></span>
                        </div>
                    </footer>
                </form>
            </div>
        </div>
    </div>
    </div>
</div>

<script>
    function onIframeDomainLoaded(iframe) {
        const $parent = iframe.closest('.s-domain-view');
        const $frame_wrapper = iframe.closest('.s-frame-wrapper');
        $parent.classList.remove('flex');
        $frame_wrapper.style.visibility = null;
        $frame_wrapper.style.position = null;
        $parent.querySelector('span').remove();
    }

    (function() { "use strict";
    const $wrapper = $('#s-domains-page');
    const $domains_wrapper = $wrapper.find('#js-domains-list');

    $wrapper.on('click', '.js-domains-add', function(e) {
        const dialog_html = $wrapper.siblings("#s-addsite-dialog-wrapper").html();
        const add_url = '?module=domains&action=save';
        $.waDialog({
            html: dialog_html,
            onOpen: function ($dialog, dialog_instance) {
                const $form = $dialog.find("form");
                $form.on('submit', function () {
                        $dialog.find(".state-error-hint").empty().hide();
                        const $is_alias_selected = !!+$form.find('.s-add-menu input[type=radio]:checked').val();
                        $.post(add_url, $form.serialize(), function (response) {
                            if (response.status === 'ok') {
                                if ($is_alias_selected) {
                                    $.site.navigate('<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?module=configure&domain_id=' + response.data.id);
                                } else {
                                    $.site.navigate('<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?module=map&action=overview&domain_id=' + response.data.id);
                                }
                                dialog_instance.close();
                            } else if (response.status === 'fail') {
                                $dialog.find(".state-error-hint").html(response.errors).show();
                            }

                        }, "json");
                        return false;
                    })
            }
        });
    })

    $domains_wrapper.on('click', '[data-domain-id]', function(e) {
        e.preventDefault();
        var $a = $(e.target).closest('a[href]');
        if ($a.length) {
            return true;
        }
        var $el = $(this);
        var domain_id = $el.data('domain-id');
        var href = $el.find('a[href].js-main-link').prop('href');
        $.site.helper.preventDupeRequest(() => {
            return $.site.navigate(href);
        }, href);
        return false;
    });

    $('#dropdown-sort').waDropdown({
        hover: false,
        items: '.menu > li > a',
        update_title: false,
        change: function (e, target, dropdown) {
            dropdown.$button.find('.name').text(dropdown.$active.find('.name').text());
            dropdown.$button.find('.icon').html(dropdown.$active.find('.icon').html());

            const sort = dropdown.$active.data('id');
            $.site.loadContent('<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?list&sort=' + sort, null, true);
        }
    });

    <?php if (waRequest::get('show_add_dialog')){?>
        $wrapper.find('.js-domains-add').click();
        if (history.state?.content_url) {
            const content_url = history.state.content_url.replace('&show_add_dialog=1', '');
            history.replaceState({ content_url }, null, content_url);
        }
    <?php }?>
})();</script>
<?php }} ?>