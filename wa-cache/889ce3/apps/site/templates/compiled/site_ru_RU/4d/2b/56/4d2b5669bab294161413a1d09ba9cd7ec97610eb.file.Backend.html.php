<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/layouts/Backend.html" */ ?>
<?php /*%%SmartyHeaderCode:8110262756a889cef935a90-73375805%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4d2b5669bab294161413a1d09ba9cd7ec97610eb' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/layouts/Backend.html',
      1 => 1774969796,
      2 => 'file',
    ),
    '6b7ea38f59b6adb6339ac02f17e1bf5de79bd8e5' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/editor/includes/publication_control.html',
      1 => 1774969796,
      2 => 'file',
    ),
    'af60e60d2a069fe1a735611d2814bbf4c981bc82' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/editor/includes/wa_header.html',
      1 => 1743679218,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8110262756a889cef935a90-73375805',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    'wa_url' => 0,
    'wa_app_static_url' => 0,
    'wa_app_url' => 0,
    'wa_backend_url' => 0,
    'hide_wa_app_icons' => 0,
    'rights' => 0,
    'wa_header_editor' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889cef96fdd0_92052845',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889cef96fdd0_92052845')) {function content_6a889cef96fdd0_92052845($_smarty_tpl) {?><!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php echo $_smarty_tpl->tpl_vars['wa']->value->appName();?>
 &mdash; <?php echo $_smarty_tpl->tpl_vars['wa']->value->accountName();?>
</title>

    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/jquery/jquery-ui.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/compiled/site.min.js?v<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"></script>

    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.elrte.ace.js?v<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"></script>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/ace/ace.js?v<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/pickr/pickr.min.js"></script>
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/pickr/themes/classic.min.css">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/pickr/themes/nano.min.css">

    <script>(function() { "use strict";
        window.wa_app = <?php echo json_encode($_smarty_tpl->tpl_vars['wa']->value->app());?>
; // for editor2.js
        //window.wa_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_url']->value);?>
; // for waEditorAceInit

        $.site.initBeforeLoad({
            wa_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_url']->value);?>
,
            app_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
,
            backend_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_backend_url']->value);?>
,
            //shop_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa']->value->getUrl('shop/frontend',array(),true));?>
,
            is_debug: <?php echo json_encode($_smarty_tpl->tpl_vars['wa']->value->debug());?>
,
            is_premium: <?php echo json_encode(waLicensing::check('site')->isPremium());?>
,
            title_pattern: '%s — ' + <?php echo json_encode($_smarty_tpl->tpl_vars['wa']->value->accountName(false));?>
,
            content_router_mode: <?php if (empty($_smarty_tpl->tpl_vars['hide_wa_app_icons']->value)){?>'xhr'<?php }else{ ?>'reload'<?php }?>,
            rights: <?php echo json_encode($_smarty_tpl->tpl_vars['rights']->value);?>
,
            lang: '<?php if ($_smarty_tpl->tpl_vars['wa']->value->locale()=="ru_RU"){?>ru<?php }else{ ?>en<?php }?>',
            locale: <?php echo json_encode(array("unable_to_load"=>_w("Unable to get the page from the server.")));?>

        });
    })();</script>
    
    <?php echo $_smarty_tpl->tpl_vars['wa']->value->css();?>

    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/site.backend.min.css?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/jquery/jquery-ui.css?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
" rel="stylesheet">
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.js?v<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script>
    <?php echo $_smarty_tpl->tpl_vars['wa']->value->js();?>

    <script>
        $.wa.locale = <?php echo json_encode(array("Close"=>_w("Close"),"Cancel"=>_ws("Cancel"),"Delete"=>_w("Delete"),"delete_route"=>_w("Delete section?"),"delete_page"=>_w("Delete page?"),"delete_nested_pages"=>_w("Delete subpages?"),"delete_rule_msg"=>_w("The section will be removed from the site map. Continue?"),"delete_page_msg"=>_w("The page will be deleted without the ability to restore. Continue?"),"delete_route_with_nested_pages_msg"=>_w("With the selected section, its subpages will be deleted, too. Continue?"),"delete_page_with_nested_pages_msg"=>_w("With the selected page, its subpages will be deleted, too. Continue?"),"delete_main_page_alert"=>sprintf("<div class=\"alert danger\">%s</div>",_w('If you remove the homepage, the site will not be operating properly. Be sure to select a page or a section as the homepage right after that.'))));?>
;
    </script>
</head>
<body>
<div id="wa">

    
    <?php if (empty($_smarty_tpl->tpl_vars['hide_wa_app_icons']->value)){?>
        <?php echo $_smarty_tpl->tpl_vars['wa']->value->header();?>

    <?php }else{ ?>

        <?php $_smarty_tpl->_capture_stack[0][] = array('default', 'wa_header_editor', null); ob_start(); ?>
            <?php /*  Call merged included template "templates/actions/editor/includes/wa_header.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/actions/editor/includes/wa_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '8110262756a889cef935a90-73375805');
content_6a889cef959194_88406181($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/actions/editor/includes/wa_header.html" */?>
        <?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

        <?php echo $_smarty_tpl->tpl_vars['wa']->value->header(array('custom'=>array('main'=>$_smarty_tpl->tpl_vars['wa_header_editor']->value,'aux'=>'<span id="js-wa-header-aux"></span>')));?>

    <?php }?>

    <div id="wa-app" class="blank">
        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

    </div>

</div>
</body>
</html>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/editor/includes/wa_header.html" */ ?>
<?php if ($_valid && !is_callable('content_6a889cef959194_88406181')) {function content_6a889cef959194_88406181($_smarty_tpl) {?>

<style>
.site-editor-wa-header-wrapper { height: 4rem; margin: 0 .5rem 0 .5rem; }
.site-editor-wa-header-wrapper .site-screen-toggle { margin: 0 auto; }
.site-editor-wa-header-wrapper .site-editor-page-name { max-width: 13rem; }
.site-editor-wa-header-wrapper .site-editor-page-settings:not(:hover) { color: var(--gray); }
.site-editor-wa-header-wrapper.block_editor .site-editor-page-name { max-width: 46%; min-width: 2.39rem; }
.site-editor-wa-header-wrapper.block_editor .site-editor-page-name-wrapper { min-width: 20rem; max-width: 40%; }

@media screen and (min-width: 1150px) {
    .site-editor-wa-header-wrapper.block_editor .site-editor-page-name-wrapper { max-width: unset; }
    .site-editor-wa-header-wrapper.block_editor .site-editor-page-name { max-width: 13rem; }
}
@media screen and (max-width: 760px) {
    .site-editor-wa-header-wrapper { margin-inline: 1rem; }
    .site-editor-wa-header-wrapper.block_editor .site-editor-page-name-wrapper { min-width: 5rem; }
}
</style>

<div class="site-editor-wa-header-wrapper flexbox full-width middle <?php echo $_smarty_tpl->tpl_vars['custom_header_type']->value;?>
">
    <div class="site-editor-page-name-wrapper flexbox middle nowrap">
        <button class="button light-gray small custom-mr-12" id="js-wa-header-hamburger" type="button" title="Карта сайта">
            <i class="fas fa-bars"></i>
        </button>

        <div class="site-editor-page-name text-ellipsis">
            <span class="js-hamburger-draft-icon icon disabled-icon custom-mr-8 text-glyph desktop-only hidden" title="Не опубликовано"><i class="fas fa-eye-slash"></i></span>
            <span class="js-hamburger-draft-changed-icon icon rounded bg-yellow smaller custom-mr-2 desktop-only hidden" title="Есть неопубликованные изменения"></span>
            <span class="js-hamburger-label bold desktop-only"></span>
        </div>

        <a href="javascript:void(0)" id="js-wa-header-settings" class="site-editor-page-settings small custom-mx-8 custom-mt-4 desktop-only" title="Настройки страницы">
            <i class="fas fa-cog large"></i>
        </a>

        
    </div>

    <?php if ($_smarty_tpl->tpl_vars['custom_header_type']->value=='block_editor'){?>
        <div class="wide flexbox middle desktop-only">

            <div class="small toggle site-screen-toggle" id="js-wa-header-screen-toggle">
                <span id="mobile"><i class="fas fa-mobile-alt"></i></span>
                <span id="tablet"><i class="fas fa-tablet-alt"></i></span>
                <span id="laptop"><i class="fas fa-laptop"></i></span>
                <span id="" class="selected"><i class="fas fa-desktop"></i></span>
            </div>

        </div>

        <?php echo $_smarty_tpl->tpl_vars['wa']->value->getCheatSheetButton(array('is_block_page'=>true,'hide_common_blocks'=>true));?>

    <?php }?>
    <div class="flexbox">
        <?php if ($_smarty_tpl->tpl_vars['custom_header_type']->value=='block_editor'){?>
            <button class="button small light-gray desktop-and-tablet-only custom-ml-12" id="js-wa-header-undo">
                <i class="fas fa-undo"></i>
            </button>

            <button class="button small light-gray desktop-and-tablet-only" id="js-wa-header-redo">
                <i class="fas fa-redo"></i>
            </button>

            <?php /*  Call merged included template "./publication_control.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./publication_control.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '8110262756a889cef935a90-73375805');
content_6a889cef961ad0_11594997($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./publication_control.html" */?>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['custom_header_type']->value=='html_editor'){?>
            <span id="wa-editor-status" class="custom-mr-16" style="display: none"></span>
            <input id="wa-page-button" type="submit" class="js-page-submit-button button green small" value="Сохранить" />
            <a class="button small light-gray desktop-and-tablet-only text-blue" id="js-wa-header-preview" target="_blank">
                <i class="fas fa-external-link-alt"></i>
                <span class="desktop-and-tablet-only custom-ml-4">Посмотреть</span>
            </a>
        <?php }?>
    </div>
</div>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/editor/includes/publication_control.html" */ ?>
<?php if ($_valid && !is_callable('content_6a889cef961ad0_11594997')) {function content_6a889cef961ad0_11594997($_smarty_tpl) {?>

<style>
#js-wa-header-publish .dropdown-body { width: auto; }
#js-wa-header-publish:not(.is-draft) .draft-only { display: none !important; }
#js-wa-header-publish.is-draft .not-draft-only { display: none !important; }
#js-wa-header-publish:not(.is-published) .published-only { display: none !important; }
#js-wa-header-publish.is-published:not(.has-unsaved-changes) .draft-or-has-changes-only { display: none !important; }
#js-wa-header-publish:not(.is-published.has-unsaved-changes) .published-unsaved-only { display: none !important; }
#js-wa-header-publish.is-draft .published-no-changes-only { display: none !important; }
#js-wa-header-publish.is-published.has-unsaved-changes .published-no-changes-only { display: none !important; }
#js-wa-header-publish li.blank > * { background-color: var(--background-color-blank); cursor: default; }
#js-wa-header-publish li > * { white-space: nowrap; }

#js-wa-header-publish:not(.is-loading) .loading-only { display: none !important; }
#js-wa-header-publish.is-loading .not-loading-only { display: none !important; }
#js-wa-header-publish:not(.is-publishing) .publishing-only { display: none !important; }
#js-wa-header-publish.is-publishing .not-publishing-only { display: none !important; }

#js-wa-header-publish .heading.heading.heading.heading.heading { white-space: nowrap; margin-top: .75rem; margin-left: .75rem; }
#js-wa-header-publish .button { white-space: nowrap; }

#js-wa-header-publish.is-draft > button { background-color: var(--blue); }
#js-wa-header-publish.is-published > button { background-color: var(--green); }
#js-wa-header-publish.is-draft.is-publishing > button { background-color: var(--green); }
#js-wa-header-publish.is-published.has-unsaved-changes > button { background-color: var(--yellow); color: var(--black); }

@media screen and (max-width: 760px) {
    .dropdown > .dropdown-body { left: auto; right: 0; }
}
</style>

<div id="js-wa-header-publish" class="dropdown is-draft">
    <button class="dropdown-toggle button small">
        <i class="fas fa-globe not-loading-only not-publishing-only"></i>
        <i class="fas fa-spinner fa-spin loading-only"></i>
        <i class="fas fa-spinner fa-spin publishing-only"></i>
        <span class="desktop-only">Просмотр и публикация</span>
    </button>
    <div class="dropdown-body not-loading-only not-publishing-only">
        <h5 class="heading black">Опубликованная версия</h5>
        <ul class="menu">
            <li class="draft-only blank">
                <span class="item"><span class="gray small">Страница не опубликована.</span></span>
            </li>
            <li class="published-only">
                <a href="javascript:void(0)" class="js-published-link" target="_blank">
                    <span class="icon"><i class="fas fa-external-link-alt text-blue"></i></span>
                    Посмотреть
                </a>
            </li>
            <li class="published-only">
                <a href="javascript:void(0)" class="js-withdraw-publication">
                    <span class="icon"><i class="fas fa-eye-slash text-red"></i></span>
                    Сделать неопубликованной
                </a>
            </li>
        </ul>
        <h5 class="heading black">Черновик</h5>
        <ul class="menu">
            <li class="published-no-changes-only blank">
                <span class="item"><span class="gray small">Нет изменений для публикации</span></span>
            </li>
            <li class="draft-or-has-changes-only">
                <a href="javascript:void(0)" class="js-draft-preview-link" target="_blank">
                    <span class="icon"><i class="fas fa-external-link-alt text-gray"></i></span>
                    Открыть предпросмотр
                </a>
            </li>
            <li class="published-unsaved-only not-draft-only">
                <a href="javascript:void(0)" class="js-discard-draft">
                    <span class="icon"><i class="fas fa-history text-gray"></i></span>
                    Отменить все изменения
                </a>
            </li>
            <li class="draft-only blank">
                <a href="javascript:void(0)">
                    <button class="js-publish button width-100 blue">
                        Опубликовать черновик
                    </button>
                </a>
            </li>
            <li class="published-unsaved-only blank">
                <a href="javascript:void(0)">
                    <button class="js-publish button width-100 yellow">
                        Опубликовать изменения
                    </button>
                </a>
            </li>
        </ul>
    </div>
</div>
<script>(function() { "use strict";
    const $wrapper = $("#js-wa-header-publish");
    var domain_root_url = null;
    $wrapper.waDropdown();
    $wrapper.data('controller', initPublicationControl());

    var empty_undo_means_no_unsaved_changes = true;
    var page_id = null;

    function initPublicationControl() {
        const that = {
            initPageData,
            updatePageData,
            updateHasUnsavedChanges,
            undoLimitExceeded,
            undoneFully,
            spinnerOff,
            spinnerOn
        };
        init();
        return that;

        function init() {
            // Пользователь нажал кнопку Опубликовать изменения
            $wrapper.on('click', '.js-publish', function() {
                $wrapper.addClass('is-publishing');
                $.post('?module=editor&action=pagePublish', { id: page_id }, function() {
                    if ($wrapper.hasClass('is-draft')) {
                        window.location.reload();
                    } else {
                        empty_undo_means_no_unsaved_changes = false;
                        updateWrapperClasses(true, false);
                    }
                }).always(function() {
                    $wrapper.removeClass('is-publishing');
                });
            });

            // Пользователь нажал кнопку Отменить изменения
            $wrapper.on('click', '.js-discard-draft', function() {
                $.waDialog.confirm({
                    title: 'Сброс изменений в черновике',
                    text: 'После сброса изменений черновик станет выглядеть так же, как опубликованная версия. Изменения будут удалены безвозвратно.',
                    success_button_title: 'Сбросить неопубликованные изменения',
                    success_button_class: 'danger',
                    cancel_button_title: 'Не сбрасывать',
                    cancel_button_class: 'light-gray',
                    onSuccess: () => {
                        $wrapper.addClass('is-publishing');
                        $.post('?module=editor&action=pagePublish', { id: page_id, operation: 'rollback' }, function() {
                            window.location.reload();
                        }).always(function() {
                            $wrapper.removeClass('is-publishing');
                        });
                    }
                });
            });

            // Пользователь нажал кнопку Отменить публикацию
            $wrapper.on('click', '.js-withdraw-publication', function() {
                $.waDialog.confirm({
                    title: 'Отмена публикации страницы',
                    text: 'После отмены публикации останется только черновик страницы. Опубликованная версия будет удалена безвозвратно.',
                    success_button_title: 'Удалить опубликованную версию',
                    success_button_class: 'danger',
                    cancel_button_title: 'Не отменять публикацию',
                    cancel_button_class: 'light-gray',
                    onSuccess() {
                        $wrapper.addClass('is-publishing');
                        $.post('?module=editor&action=pagePublish', { id: page_id, operation: 'unpublish' }, function(r) {
                            if (r.status == 'ok' && r.data.page_id) {
                                const url = location.href.replace(/\/editor\/page\/\d+\//, '/editor/page/'+r.data.page_id+'/');
                                window.location.href = url;
                            } else {
                                console.error('Unable to cancel publication', r);
                            }
                        }).always(function() {
                            $wrapper.removeClass('is-publishing');
                        });
                    }
                });
            });
        }

        // Выставляет CSS классы в зависимости от первоначального состояния страницы.
        function initPageData(init_domain_root_url, draft_page, published_page) {
            page_id = draft_page.id;
            domain_root_url = init_domain_root_url;
            const is_published = published_page.status === 'final_published';
            const has_unsaved_changes = draft_page.create_datetime !== draft_page.update_datetime;
            empty_undo_means_no_unsaved_changes = is_published && !has_unsaved_changes;
            updateWrapperClasses(is_published, has_unsaved_changes);

            $wrapper.find('.js-published-link').attr('href', domain_root_url + fixSlashes(published_page.full_url));
            updatePreviewLinks(draft_page);
        }

        // Юзер изменил основные параметры страницы (название, url и т.п.)
        function updatePageData(draft_page) {
            empty_undo_means_no_unsaved_changes = false;
            updateWrapperClasses($wrapper.hasClass('is-published'), true);
            updatePreviewLinks(draft_page);
        }

        // Сигнализирует о переполнении очереди UNDO (сейчас очередь UNDO бесконечная и эта функция не используется, но кто знает...)
        function undoLimitExceeded() {
            empty_undo_means_no_unsaved_changes = false;
        }

        // Сигнализирует о том, что очередь UNDO закончилась (юзер отменил все изменения)
        function undoneFully() {
            if (empty_undo_means_no_unsaved_changes) {
                updateWrapperClasses(true, false);
            }
        }

        // Начало любого сохранения блока (включая UNDO и REDO)
        function spinnerOn() {
            $wrapper.addClass('is-loading');
        }

        // Окончание любого сохранения блока (включая UNDO и REDO)
        function spinnerOff() {
            $wrapper.removeClass('is-loading');
            updateWrapperClasses($wrapper.hasClass('is-published'), true);
        }

        function updateHasUnsavedChanges(has_unsaved_changes) {
            setTimeout(function() {
                updateWrapperClasses($wrapper.hasClass('is-published'), has_unsaved_changes);
            }, 50);
        }

        function updateWrapperClasses(is_published, has_unsaved_changes) {
            if (is_published) {
                $wrapper.removeClass('is-draft');
                $wrapper.addClass('is-published');
                if (has_unsaved_changes) {
                    $wrapper.addClass('has-unsaved-changes');
                } else {
                    $wrapper.removeClass('has-unsaved-changes');
                }
                $(document).trigger('page_has_unsaved_changes', [has_unsaved_changes]);
            } else {
                $wrapper.removeClass('is-published has-unsaved-changes');
                $wrapper.addClass('is-draft');
            }
        }

        function updatePreviewLinks(draft_page) {
            $wrapper.find('.js-draft-preview-link').attr('href', domain_root_url + fixSlashes(draft_page.full_url) + (draft_page.preview_hash ? '?preview_hash='+draft_page.preview_hash : ''));
        }

        function fixSlashes(url) {
            if (url && url.length && url[url.length-1] != '/') {
                url += '/';
            }
            return url;
        }
    }

})();</script>
<?php }} ?>