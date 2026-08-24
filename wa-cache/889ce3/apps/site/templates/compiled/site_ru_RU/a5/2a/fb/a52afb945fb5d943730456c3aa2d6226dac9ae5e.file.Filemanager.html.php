<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:45:58
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/filemanager/Filemanager.html" */ ?>
<?php /*%%SmartyHeaderCode:9718186776a8c8356bbfd62-05052344%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a52afb945fb5d943730456c3aa2d6226dac9ae5e' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/filemanager/Filemanager.html',
      1 => 1741870267,
      2 => 'file',
    ),
    '6764a4e175d8ad228226f61b45b8f6d3eed24613' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/domain_tabs.html',
      1 => 1745480410,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9718186776a8c8356bbfd62-05052344',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa_url' => 0,
    'wa_app' => 0,
    'wa' => 0,
    'wa_app_url' => 0,
    'domain_id' => 0,
    'domain_idn' => 0,
    'domain' => 0,
    'dirs' => 0,
    'sub_dirs_decoded' => 0,
    'files_path' => 0,
    'page' => 0,
    'domain_protocol' => 0,
    'domains_decode' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c8356bf8ea7_46058592',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c8356bf8ea7_46058592')) {function content_6a8c8356bf8ea7_46058592($_smarty_tpl) {?><script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/fileupload/jquery.iframe-transport.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/fileupload/jquery.fileupload.js"></script>

<div class="article site-base">
    <div class="article-body">
        <?php /*  Call merged included template "templates/actions/backend/includes/domain_tabs.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/actions/backend/includes/domain_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('selected'=>'files'), 0, '9718186776a8c8356bbfd62-05052344');
content_6a8c8356bc82f2_69100555($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/actions/backend/includes/domain_tabs.html" */?>

        <div class="flexbox s-files-page" id="s-files-page">
            <div class="sidebar s-internal-sidebar hidden">
                <div class="block not-padded">
                    <div class="s-files-baseurl">
                        <a href="#" class="s-baseurl router-link"><i class="icon fas fa-folder"></i>wa-data/public/site</a>
                    </div>

                    <div class="hierarchical" id="s-files-tree">

                    </div>
                </div>
            </div>
            <div class="content">
                <div class="s-breadcrumbs custom-pb-12">
                    <ul class="breadcrumbs">
                        <li class="active"><a href="#" class="s-baseurl router-link">wa-data/public/site</a></li>
                    </ul>
                </div>
                <div class="s-folder-actions-li flexbox middle" id="s-folder-actions-li">
                    <span class="icon cursor-pointer size-20 js-folder-action-back custom-pt-4"><i class="fas fa-arrow-circle-left text-light-gray"></i></span>
                    <div class="s-folder-action-name bold custom-pl-12" id="s-current-path"></div>
                    <div class="dropdown" id="dropdown-actions-li">
                        <a href="javascript:void(0)" class="dropdown-toggle without-arrow text-gray"><i class="icon fas fa-ellipsis-h"></i></a>
                        <div class="dropdown-body">
                            <ul class="menu">
                                <li><a id="s-folder-rename" href="javascript:void(0)"><i class="icon fas fa-pen edit"></i>Переименовать</a></li>
                                <li><a id="s-folder-move" href="javascript:void(0)"><i class="icon fas fa-share move"></i>Переместить в папку</a></li>
                                <li><a id="s-folder-delete" href="javascript:void(0)"><i class="icon fas fa-times-circle delete"></i>Удалить</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="s-editor custom-pt-20">
                    <div class="block s-grey-toolbar">

                        <!--<h4><span class="s-files-path-to-folder">wa-data/public/site</span><span id="s-current-path"></span></h4>-->
                        <div class="s-filelist-actions-menu flexbox wrap space-16 custom-mb-24">
                            <div>
                                <a href="javascript:void(0)" class="button small" id="s-upload-link" >
                                    <span class="icon custom-mr-8"><i class="fas fa-cloud-upload-alt upload small "></i></span>Загрузить файлы
                                </a>
                            </div>
                            <div>
                                <a  href="javascript:void(0)" class="button gray small" title="Новая папка" id="s-add-folder">
                                    <span class="icon custom-mr-8"><i class="icon fas fa-folder-plus add"></i></span>Новая папка
                                </a>
                            </div>

                            <div>
                                <a href="javascript:void(0)" class="button light-gray small js-action-select">
                                    <span class="icon custom-mr-8"><i class="icon fas fa-check-square"></i></span>Выбрать
                                </a>
                                <div class="dropdown js-action-selected hidden" id="dropdown-selected-files">
                                    <a href="javascript:void(0)" class="dropdown-toggle button light-gray small ">Выбрано
                                        <span class="badge smaller gray js-operation-badge" id="s-files-count">0</span>
                                    </a>
                                    <div class="dropdown-body">
                                        <ul class="menu">
                                            <li><a id="s-files-move" class="disabled" href="javascript:void(0)"><i class="icon fas fa-share move"></i>Переместить в папку</a></li>
                                            <li><a id="s-files-delete" class="disabled" href="javascript:void(0)"><i class="icon fas fa-times-circle delete"></i>Удалить</a></li>
                                        </ul>
                                    </div>
                                    <a href="javascript:void(0)" class="button light-gray small js-action-remove-selected">
                                        <span class="icon"><i class="fas fa-times"></i></span>
                                    </a>
                                </div>

                            </div>

                        </div>
                        <?php if (!$_smarty_tpl->tpl_vars['wa']->value->user()->getSettings($_smarty_tpl->tpl_vars['wa_app']->value,'hide_alert_files')){?>
                        <div class="s-alert-text alert custom-mb-8">
                            <div class="flexbox space-12 small">
                                <span> <i class="fas fa-info-circle"></i></span>
                                Загруженные здесь файлы доступны всем посетителям сайта без авторизации. Не загружайте личные файлы.
                                <a href="javascript:void(0)" class="alert-close custom-ml-auto"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                        <?php }?>
                        <div class="s-alert-text alert custom-mb-8">
                            <div class="flexbox space-12 small">
                                <span> <i class="fas fa-info-circle"></i></span>
                                <span><?php echo sprintf_wp('To upload a file to the site root; e.g., to confirm the site ownership for third-party services, add a “<em>%s</em>” rule in the <a href="%s" class="text-dark-gray underline">Settings</a>.',_w('Custom files in site root'),((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."settings/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value)."&scroll_to=custom-texts");?>
</span>
                            </div>
                        </div>

                    </div>
                    <table id="s-files-grid" class="s-filelist borderless single-lined blank">
                        <tr>
                            <th class="min-width">
                                <label>
                                    <span class="wa-checkbox">
                                        <input type="checkbox" class="all">
                                        <span>
                                            <span class="icon">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </span>
                                    </span>
                                </label>
                            </th>
                            <th>Файл</th>
                            <th>Изменено</th>
                            <th><span class="float-right">Размер</span></th>
                            <th></th>
                        </tr>
                    </table>
                    <div class="s-pagination"></div>
                </div>
            </div>
            <div id="s-add-folder-dialog-wrapper">
                <div class="dialog" id="s-add-folder-dialog">
                    <div class="dialog-background"> </div>
                    <div class="dialog-body">
                        <form>
                            <header class="dialog-header"><h1>Новая папка</h1></header>
                            <div class="dialog-content">
                                <div class="hint custom-pb-8">Название</div>
                                <input type="text" id="s-folder-name" name="name" class="bold small long custom-mb-16" value="" placeholder="Новая папка">
                                <div class="hint custom-pb-8">Расположение</div>
                                <span></span>
                            </div>
                            <footer class="dialog-footer">
                                <input type="submit" class="button" value="Создать">
                                <a href="#" class="js-close-dialog button light-gray">Отмена</a>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
            <div id="s-rename-dialog-wrapper">
                <div class="dialog" id="s-rename-dialog">
                    <div class="dialog-background"> </div>
                    <div class="dialog-body">
                        <form>
                            <header class="dialog-header"><h1>Переименовать</h1></header>
                            <div class="dialog-content">
                                <div class="hint custom-pb-8">Название</div>
                                <input type="text" id="s-name" name="name" class="bold small long custom-mb-16" value="" placeholder="">
                                <div class="hint custom-pb-8">Расположение</div>
                                <span></span>
                            </div>
                            <footer class="dialog-footer">
                                <input type="submit" class="button" value="Сохранить">
                                <a href="#" class="js-close-dialog button light-gray">Отмена</a>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
            <div id="s-upload-dialog-wrapper">
                <div class="dialog" id="s-upload-dialog">
                    <div class="dialog-background"> </div>
                    <div class="dialog-body">
                        <form id="s-upload-form" method="post" action="?module=files&action=upload" enctype="multipart/form-data">
                            <?php echo $_smarty_tpl->tpl_vars['wa']->value->csrf();?>

                            <header class="dialog-header"><h1>Загрузить файлы</h1></header>
                            <div class="dialog-content">
                                <div style="display: none">
                                    <input id="s-input-file" type="file" name="files[]" multiple>
                                </div>

                                <input type="hidden" name="path" id="s-upload-path" value="" />
                                <div class="loading" style="display:none; margin-top: 10px">
                                    <i class="icon fas fa-spinner fa-spin loading"></i> Загрузка...
                                </div>
                            </div>
                            <footer class="dialog-footer">
                                <input type="submit" class="button hidden" value="Загрузить">
                                <a href="#" class="js-close-dialog button light-gray">Отмена</a>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
            <div id="s-move-dialog-wrapper">
                <div class="dialog" id="s-move-dialog">
                    <div class="dialog-background"> </div>
                    <div class="dialog-body">
                        <form>
                            <header class="dialog-header"><h1>Переместить в папку <span style="color: #aaa;"></span></h1></header>
                            <div class="dialog-content">
                                <div class="wa-select">
                                    <select name="new_path" class="text-ellipsis width-100"></select>
                                </div>
                                <input type="hidden" name="path" />
                                <div id="s-move-dialog-files" style="display:none"></div>
                            </div>
                            <footer class="dialog-footer">
                                    <input type="submit" class="button" value="Переместить">
                                    <a href="#" class="js-close-dialog button light-gray">Отмена</a>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
            <div id="s-delete-dialog-wrapper">
                <div class="dialog" id="s-delete-dialog">
                    <div class="dialog-background"></div>
                    <div class="dialog-body">
                        <form>
                            <header class="dialog-header"><h1>Удалить файлы <span style="color: #aaa;"></span></h1></header>
                            <div class="dialog-content">
                                Файлы будут удалены без возможности восстановления.
                            </div>
                            <footer class="dialog-footer">
                                <input type="submit" class="button orange" value="Удалить">
                                <a href="#" class="js-close-dialog button light-gray">Отмена</a>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    SiteFilemanager = (function ($) {
        return class {
            constructor(options) {
                const that = this;
            // DOM
                that.$wrapper = options["$wrapper"];
                that.$files_tree = that.$wrapper.find('#s-files-tree');
                that.$internal_sidebar = that.$wrapper.find('.s-internal-sidebar');
                that.$content = that.$wrapper.find('.content');
                that.$toolbar = that.$content.find('.s-grey-toolbar');
                that.$breadcrumbs = that.$content.find('.s-breadcrumbs');
                that.$files_grid = that.$content.find('#s-files-grid');
                that.$content_wrapper = $('#wa-app');

            // VARS

                that.locales = options["locales"];
                that.page = options["$page"];
                that.domain_url = options["domain_url"];
                that.domain_protocol = options["domain_protocol"];
                that.$wa_app_url = options["$wa_app_url"];
                that.$domain_id = options["$domain_id"];
                that.$sub_dirs_decoded = options["$sub_dirs_decoded"];
                that.$dirs = options["$dirs"];
                that.$files_path = options["$files_path"];
                that.domains_decode = options["domains_decode"];
            // INIT
                that.initClass();

            /*TODO
            - не открываются файлы
            - проработать history.onPop (нужно ли?)
            - зачем нужны sub_dirs_decoded ?
            */
            };

            initClass() {
                const that = this;

                that.initDispatch(false, true);
                that.initFolderActions();
                that.initToolbar();
                that.initFilesGrid();

                that.$wrapper.on("click", "a.router-link", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var $link = $(this),
                        uri = $link.attr("href");
                        that.initDispatch(uri);
                });
            }

            initDispatch(hash, unset_state = false, loaded = true) {
                const that = this;
                let page = 1;
                let params = hash;

                if (hash === false) {
                    hash = that.$files_path;
                    page = that.page;
                }

                hash = hash.replace(/^[^#]*#\/*/, ''); /* fix sintax highlight*/

                if (params && (params.indexOf('?') != -1) && params.substr(-1) != '/') {
                    let tmp = params.substr(params.indexOf('?') + 1);
                    hash = hash.substr(0, hash.indexOf('?'));
                    tmp = tmp.split('=');
                    if (tmp[0] == 'page') {
                        page = tmp[1];
                    }
                }

                that.$files_path = hash;
                const page_url = page != 1 ? '&page=' + page : '';

                if (loaded && !unset_state) {
                    let url = that.$wa_app_url + 'files/' + hash + (hash ? '/' : '') +  '?domain_id=' + that.$domain_id + page_url;
                    history.pushState({
                        content_url: url
                    }, "", url);
                }
                if (loaded) {
                    that.initBreadcrumbsHtml();
                    that.filesAction(hash, page);
                }
                else {
                    const url = that.$wa_app_url + 'files/' + hash + '/' + '?domain_id=' + that.$domain_id + page_url;
                    $.site.loadContent(url);
                }
            }

            initFolderActions() {
                const that = this;
                const $actions_li = that.$content.find("#s-folder-actions-li");

                $actions_li.on('click', '.js-folder-action-back', function() { //add folder
                        that.initGoBack();
                })

                $actions_li.find("#dropdown-actions-li").waDropdown({
                    hover: false,
                    ready: function(dropdown){
                        const $menu = dropdown.$wrapper.find('.menu');
                        $menu.on('click', '#s-folder-rename', function(e){
                            that.renameDialog(that.$content.find("#s-current-path").text(), false, true)
                        })
                        $menu.on('click', '#s-folder-delete', function(e){
                            that.deleteDialog(that.$content.find("#s-current-path").text(), false, true)
                        })
                        $menu.on('click', '#s-folder-move', function(e){
                            that.moveDialog(that.$content.find("#s-current-path").text(), false, true)
                        })
                    },
                });
            }

            initToolbar() {
                const that = this,
                $toolbar = that.$toolbar;
                let checkbox_hidden = true;

                $toolbar.find('.alert-close').on('click', function() {
                    const $alert = $(this).closest('.s-alert-text');
                    $.post('?module=filemanager&action=hideAlertFiles', null, function (r) {
                        if (r?.status === 'ok') {
                            $alert.remove();
                        }
                    });
                })

                $toolbar.on('click', '.js-action-select', function() {
                    if (checkbox_hidden) {
                        that.$files_grid.addClass('checkbox-show');
                        $(this).addClass('hidden');
                        $toolbar.find('.js-action-selected').removeClass('hidden');
                        checkbox_hidden = false;
                    }

                })

                $toolbar.on('click', '.js-action-remove-selected', function() {
                    if (!checkbox_hidden) {
                        that.$files_grid.removeClass('checkbox-show')
                        $toolbar.find('.js-action-selected').addClass('hidden');
                        $toolbar.find('.js-action-select').removeClass('hidden');
                        checkbox_hidden = true;
                        that.$files_grid.find("input").prop('checked', false);
                        that.$toolbar.find("#s-files-count").text(0);
                        that.$toolbar.find("#dropdown-selected-files .dropdown-body a").addClass('disabled');
                    }
                })

                $toolbar.on('click', '#s-add-folder', function() { //add folder
                    const dialog_html = that.$wrapper.find("#s-add-folder-dialog-wrapper").html();
                    $.waDialog({
                        html: dialog_html,
                        onOpen: function ($dialog, dialog_instance) {
                            $dialog.find('span').html(that.filesPath(true));
                            $dialog.find('#s-folder-name').focus();

                            $dialog.find('form').on('submit', function(){
                                let folder = $dialog.find("#s-folder-name").val();
                                $dialog.find("input[type=submit]").prop('disabled', true);
                                $.post('?module=files&action=addFolder', { path: that.filesPath(), name: folder}, function (response) {
                                    if (response.status == 'ok') {
                                        that.initDispatch(that.filesPath(), false, false)
                                        dialog_instance.close();

                                    } else if (response.status == 'fail') {
                                        alert(response.errors);
                                        $dialog.find("input[type=submit]").prop('disabled', false);
                                    }
                                }, "json");
                                return false;
                            })
                        },
                    });
                    return false;
                });

                $toolbar.find("#dropdown-selected-files").waDropdown({
                    hover: false,
                    ready: function(dropdown){
                        const $menu = dropdown.$wrapper.find('.menu');
                        $menu.on('click', '#s-files-delete', function(e){
                            let files = [];
                            that.$files_grid.find("input:checked").each(function () {
                                if (!$(this).hasClass('all')) {
                                    files.push($(this).val());
                                }
                            });
                            if (!files.length) {
                                return false;
                            }
                            that.deleteDialog(files, true, false, true)
                        })

                        $menu.on('click', '#s-files-move', function(e){
                            that.moveDialog(false, true, false, true)
                        })
                    },
                });

                var jqXHR = null;

                $('#s-input-file').fileupload({
                    add: function(e, data) {
                        if (data.files[0]['size'] > <?php echo waRequest::getUploadMaxFilesize();?>
) {
                            alert('<?php ob_start();?><?php echo waRequest::getUploadMaxFilesize();?>
<?php $_tmp1=ob_get_clean();?><?php echo sprintf('Слишком большой файл. Либо установите ограничение на размер загружаемых файлов в конфигурации PHP выше %s байтов, либо попробуйте загрузить файл меньшего размера.',$_tmp1);?>
')

                        } else {
                            jqXHR = data.submit();
                        }
                    },
                    dataType: 'json',
                    start: function (e, data) {
                        const dialog_html = that.$wrapper.find("#s-upload-dialog-wrapper").html();
                        that.$dialog_upload = $.waDialog({
                            html: dialog_html,
                            onOpen: function ($dialog, dialog_instance) {
                                $dialog.find("div.loading").show();
                                $dialog.find("input[type=submit]").attr('disabled', 'disabled');
                                $dialog.on('click', '.js-close-dialog', function(){
                                    jqXHR.abort();
                                })
                            }
                        });
                    },
                    done: function (e, data) {
                        if (data.result.status == 'fail') {
                            alert(data.result.errors);
                        }
                        that.filesAction(that.filesPath());
                    },
                    stop: function () {
                        that.$dialog_upload.close();
                    }
                });

                $toolbar.on('click', '#s-upload-link', function() {
                    $('#s-input-file').click();
                });

            }

            initFilesGrid() {
                const that = this;
                that.$files_grid.on('change', "input", function () {
                    if ($(this).hasClass('all')) {
                        if ($(this).is(':checked')) {
                            that.$files_grid.find("input").prop('checked', true);
                        } else {
                            that.$files_grid.find("input").prop('checked', false);
                        }
                    } else {
                        if (!$(this).is(':checked')) {
                            that.$files_grid.find("input.all").prop('checked', false);
                        } else if (that.$files_grid.find("input:not(:checked)").length == 1) {
                            that.$files_grid.find("input.all").prop('checked', true);
                        }
                    }
                    var n = that.$files_grid.find("input:checked").length - (that.$files_grid.find("input.all").is(":checked") ? 1 : 0);
                    if (n > 0) {
                        that.$toolbar.find("#dropdown-selected-files .dropdown-body a").removeClass('disabled');
                    } else {
                        that.$toolbar.find("#dropdown-selected-files .dropdown-body a").addClass('disabled');
                    }

                    that.$toolbar.find("#s-files-count").text(n);
                });
            }

            filesAction(path, page) {
                const that = this,
                $files_tree = that.$files_tree;
                //let page = 1;
                let params = path;

                if ($files_tree.length) {
                    loadFiles();
                }

                function loadFiles () {
                    const $actions_li = that.$content.find("#s-folder-actions-li");
                    if (!params) {
                        $actions_li.hide();
                    } else {
                        $actions_li.show();
                    }

                    that.filesList(params, page);

                    $("#s-upload-path").val(params || '');
                    that.current_folder = params.substr(params.lastIndexOf('/') + 1) || '';
                    const path = that.domains_decode[that.current_folder] || that.current_folder;
                    that.$content.find("#s-current-path").html(path);
                    that.$toolbar.find("#s-files-count").html('0');
                    that.$files_grid.find("input.all").removeAttr('checked');
                    that.$toolbar.find("#dropdown-selected-files .dropdown-body a").addClass('disabled');
                };
            }

            filesList(path, page) {
                const that = this;
                if (!path) {
                    path = that.filesPath();
                }
                if (!page) {
                    page = filesPage();
                }
                let url;
                let url_class;
                let data = {
                    path: path
                };

                $.post("?module=filemanager&action=list&page=" + page, data, "json").then(function (response) {
                    that.$files_grid.find("tr.s-file").remove();
                    $("div.s-pagination").empty();
                    for (let i = 0; i < response.data.files.length; i++) {
                        let r = response.data.files[i];
                        if (r.is_file) {
                            url = 'http://' + that.domain_url + '/wa-data/public/site/' + path + '/' + r.file;
                            url_class = 's-file-name';
                        } else {
                            url = '#/' + path + (path ? '/' : '') + r.file;
                            url_class = 's-file-name router-link';
                        }

                        let checkbox = '<label><span class="wa-checkbox"><input type="checkbox" value="'+r.file+'"><span><span class="icon"><i class="fas fa-check"></i></span></span></span></label>';
                        const file_name = that.domains_decode[r.file] || r.file;
                        let html = '<tr class="s-file small"><td class="min-width">'+ checkbox +'</td>' +
                            '<td class="file-name-td"><div class="file-name flexbox middle space-8"><i class="icon text-gray fas fa-' + r.type + '"></i> <div><a class="'+ url_class +'" href="'+url+'">' + file_name + '</a><i class="shortener"></i></div></div></td>' +
                            '<td><div>' + r.datetime + '<i class="shortener"></i></div></td>' +
                            '<td><div><span class="float-right">' + getFileSize(r.size) + '</span><i class="shortener"></i></div></td>' +
                            '<td><div class="dropdown clickable" id="' + i + '-' + r.timestamp + '"><a href="javascript:void(0)" class="dropdown-toggle without-arrow text-gray"><i class="icon fas fa-ellipsis-h"></i></a>' +
                            '<div class="dropdown-body right"><ul class="menu">' +
                            '</ul></div></div></td></tr>';
                            that.$files_grid.append(html);
                            that.$files_grid.find('.dropdown.clickable').each(function(ind, el){
                                $(el).waDropdown({
                                    hover: false,
                                    ready: function(dropdown){
                                        const $menu = dropdown.$wrapper.find('.menu');
                                        $menu.on('click', '.file-rename', function(e){
                                            that.renameDialog(r.file, r.is_file, false)
                                        })
                                        $menu.on('click', '.file-delete', function(e){
                                            that.deleteDialog(r.file, r.is_file, false)
                                        })

                                        $menu.on('click', '.file-move', function(e){
                                            that.moveDialog(r.file, r.is_file, false)
                                        })
                                    },
                                    open: function(dropdown){
                                        const $menu = dropdown.$wrapper.find('.menu');
                                        if (!$menu.children().length) {
                                            const file = dropdown.$wrapper.closest('tr').find('input[type=checkbox]').val();
                                            const menu_list = that.getFileMenu(r.file, r.is_file);
                                            $menu.append(menu_list);
                                        }
                                    },
                                });
                            })
                    }
                    if (response.data.pages > 1) { //TO-DO test
                        let html = '<ul class="paging">';
                        for (let i = 1; i <= response.data.pages; i++) {
                            html += '<li class="' + (i == page ? 'selected' : '') + '"><a class="router-link" href="#/' + path + '?page=' + i + '">' + i + '</a></li>';
                        }
                        html += '</ul>';
                        $("div.s-pagination").html(html).show();
                    }
                }, function() {
                });

                function filesPage(hash) { //TO-DO Change
                    if (!hash) {
                        hash = location.hash;
                    }
                    hash = hash.split('/');
                    hash = hash[hash.length - 1];
                    if (hash && hash.substr(0, 1) == '?') {
                        hash = hash.substr(1).split('=');
                        if (hash[0] == 'page') {
                            return hash[1];
                        }
                    }
                    return 1;
                }

                function getFileSize(size) {
                    if (size < 1024) {
                            return size + ' ' + that.locales['b'];
                        } else if (size < 1024 * 1024) {
                            return Math.round(size/1024) + ' ' + that.locales['kb'];
                        } else if (size < 1024 * 1024 * 1024) {
                            return Math.round(size/(1024 * 1024)) + ' ' + that.locales['mb'];
                        } else {
                            return Math.round(size/(1024 * 1024 * 1024)) + ' ' + that.locales['gb'];
                        }
                    }
            }

            getFileMenu(file, is_file) {
                const that = this;
                const open_link_class = (is_file ? '' : 'router-link');
                const url = that.domain_url + '/wa-data/public/site/' + that.filesPath() + '/' + file;
                const open_link_url = (is_file ? that.domain_protocol + url : '#/' + that.filesPath() + '/' + file);

                const menu = $('<ul class="menu"></ul>');
                if (is_file) {
                    if (file.substr(-4) != '.php' && file.substr(-6) != '.phtml' && file.substr(0,1) != '.') {
                        menu.append('<li>' +
                            '<a href="'+ open_link_url + '" target="_blank" class="' + open_link_class + '">' +
                            '<i class="icon fas fa-link globe smaller"></i>' + that.locales['open'] + '<i class="icon fas fa-external-link-alt new-window"></i>' +
                            '</a>' +
                            '</li>');
                    }
                    if (file.substr(-4) != '.php' && file.substr(-6) != '.phtml') {
                        menu.append('<li>' +
                            '<a href="'+that.$wa_app_url+'?module=files&action=download&path=' +
                                that.filesPath() + '&file=' + file + '" download><i class="icon fas fa-download"></i>' + that.locales['download'] +
                            '</a></li>');
                    }
                }
                menu.append($('<li></li>').append('<a href="javascript:void(0);" class="file-rename"><i class="icon fas fa-edit edit"></i>' + that.locales['rename'] + '</a>'));
                menu.append($('<li></li>').append('<a href="javascript:void(0);" class="file-move"><i class="icon fas fa-share move"></i>' + that.locales['move'] + '</a>'));
                menu.append($('<li></li>').append('<a href="javascript:void(0);" class="file-delete"><i class="icon fas fa-trash-alt delete"></i>' + that.locales['delete'] + '</a>'));

                return menu.html();
            }

            //Used for all rename variations on page
            renameDialog(file, is_file = false, is_action = false) {
                const that = this;
                const dialog_html = that.$wrapper.find("#s-rename-dialog-wrapper").html();
                    $.waDialog({
                        html: dialog_html,
                        onOpen: function ($dialog, dialog_instance) {
                            $dialog.find('span').html(that.filesPath(true));
                            $dialog.find('#s-name').val(file).focus().select();

                            $dialog.find('form').on('submit', function(){
                                const name = $dialog.find('#s-name').val();
                                const additional_path = is_action ? '' : '/' + file;
                                let data = {
                                    path: that.filesPath(),
                                    name: name,
                                    file: file
                                };
                                if (!is_file) {
                                    data = {
                                    path: that.filesPath() + additional_path,
                                    name: name,
                                    };
                                }
                                $dialog.find("input[type=submit]").prop('disabled', true);
                                $.post('?module=files&action=rename', data, function (response) {
                                    if (response.status == 'ok') {
                                        dialog_instance.close();
                                        if (is_action) {
                                            //location.reload()
                                            that.initDispatch(response.data.hash.slice(8, -1), false, false)
                                        }
                                        else {
                                            that.filesAction(that.filesPath())
                                        }

                                    } else if (response.status == 'fail') {
                                        alert(response.errors);
                                        $dialog.find("input[type=submit]").prop('disabled', false);
                                    }
                                }, "json");
                                return false;
                            })
                        },
                    });
                    return false;
            }

            //Used for all delete variations on page
            deleteDialog(file, is_file = false, is_action = false, multiple = false) {
                const that = this;
                const dialog_html = that.$wrapper.find("#s-delete-dialog-wrapper").html();
                $.waDialog({
                    html: dialog_html,
                    onOpen: function ($dialog, dialog_instance) {
                        if (!is_file) {
                            $dialog.find('h1').html(that.locales['delete_folder_title'])
                            $dialog.find('.dialog-content').html(that.locales['delete_content'])
                        }
                        if (multiple) {
                            $dialog.find('h1 span').text('(' + file.length + ')')
                        }
                        $dialog.find('form').on('submit', function(){
                            const name = $dialog.find('#s-name').val();
                            const additional_path = is_action ? '' : '/' + file;
                            let data = {
                                path: that.filesPath(),
                                file: file
                            }
                            if (multiple) {
                                data = {
                                path: that.filesPath(),
                                "file[]": file
                                };
                            }
                            if (!is_file) {
                                data = {
                                path: that.filesPath() + additional_path,
                                };
                            }
                            $dialog.find("input[type=submit]").prop('disabled', true);
                            $.post('?module=files&action=delete', data, function (response) {
                                if (response.status == 'ok') {
                                    dialog_instance.close();
                                    if (is_action) {
                                       that.initGoBack(false);
                                    }
                                    else {
                                        that.initDispatch(that.filesPath(), false, false)
                                    }
                                } else if (response.status == 'fail') {
                                    alert(response.errors);
                                    $dialog.find("input[type=submit]").prop('disabled', false);
                                }
                            }, "json");
                            return false;
                        })
                    }
                });
                return false;
            }

            //Used for all move variations on page
            moveDialog(file, is_file = false, is_action = false, multiple = false) {
                const that = this;
                const dialog_html = that.$wrapper.find("#s-move-dialog-wrapper").html();
                $.waDialog({
                    html: dialog_html,
                    onOpen: function ($dialog, dialog_instance) {
                        const $form = $dialog.find('form');
                        $dialog.find("select").html(that.filesPathOptions(that.$dirs));
                        $dialog.find("input[name=path]").val(that.filesPath() + '/');
                        if (multiple) {
                            var n = 0;
                            that.$files_grid.find("input:checked").each(function () {
                                if (!$(this).hasClass('all')) {
                                    n++;
                                    $dialog.find("#s-move-dialog-files").append('<input type="hidden" name="file[]" value="' + $(this).val() + '" />');
                                }
                            });
                            if (!n) {
                                return false;
                            }
                            $dialog.find("h1 span").html('(' + n + ')');
                        } else {
                            $dialog.find("h1 span").empty();
                            if (!is_action) {
                            $dialog.find("#s-move-dialog-files").html('<input type="hidden" name="file" value="' + file + '" />');
                            }

                        }
                        $form.on('submit', function(){
                            $.post('?module=files&action=move', $form.serialize() , function (response) {
                                if (response.status == 'ok') {
                                    if (is_action) {
                                       that.initDispatch(response.data.hash.slice(0,-1), false, false);
                                    }
                                    else {
                                        that.initDispatch(that.filesPath())
                                    }
                                    dialog_instance.close();
                                } else if (response.status == 'fail') {
                                    alert(response.errors);
                                    $dialog.find("input[type=submit]").prop('disabled', false);
                                }
                            }, "json");
                            return false;
                        })
                    }
                })
            }

            initGoBack(load = true) {
                const that = this;
                let current_path = that.filesPath();
                const temp = current_path.lastIndexOf('/');
                const previous_path = temp > 0 ? current_path.slice(0, temp) : '';
                that.initDispatch(previous_path, false, load);
            }

            filesPath(full) {
                const that = this;
                const prefix = full ? 'wa-data/public/site/' : '';
                return prefix + that.$files_path;
            }

            filesPathOptions(data, prefix = '', prev_full_path = '') {
                const that = this;
                const max_length = 64;
                let id = '';
                let result = '';

                let current_folder = that.current_folder;

                if (prefix == '') {
                    result = '<option value="">wa-data/public/site</a>';
                    prefix = '&nbsp;&nbsp;&nbsp;'
                };

                for (var i = 0; i < data.length; i++) {
                    id = typeof(data[i]) == 'string' ? data[i] : data[i]['id'];
                    let selected = false;
                    let full_path = prev_full_path + id + '/';
                    if (current_folder === id) {
                        selected = true;
                    }

                    const full_name = that.domains_decode[id] || id;
                    const is_max_length = full_name.length > max_length;
                    const name = prefix + (is_max_length ? full_name.substring(0, max_length) + "..." : full_name);
                    result += `<option ${ selected ? 'selected="selected"' : '' } value="${ full_path }" ${ is_max_length ? `title=${ full_name }` : '' }>${ name }</option>`;

                    if (typeof(data[i]) != 'string') {
                        result +=  that.filesPathOptions(data[i]['childs'], prefix + '&nbsp;&nbsp;&nbsp;', full_path);
                    }
                }
                return result;
            }

            initBreadcrumbsHtml() {
                const that = this,
                $wrapper = that.$wrapper;

                that.$breadcrumbs.find('.breadcrumbs').html(getTreeHTML());

                function getTreeHTML() {
                    const breadcrumb_ar = that.$files_path.split('/');
                    let html = '<li><a href="#" class="s-baseurl router-link">wa-data/public/site</a></li>';
                    let li_class = '';
                    let link_class = 'router-link';
                    let link_href = '#';

                    if (breadcrumb_ar.length === 1 && breadcrumb_ar[0] === '') {
                        html = '<li class="active"><a href="javascript:void(0);" class="s-baseurl">wa-data/public/site</a></li>';

                    } else {
                        for (let i = 0; i < breadcrumb_ar.length; i++) {
                        link_href += '/' + breadcrumb_ar[i];
                        if (i === breadcrumb_ar.length - 1) {
                            li_class = 'active';
                            link_href = 'javascript:void(0);'
                            link_class = ''
                        }
                        const path = that.domains_decode[breadcrumb_ar[i]] || breadcrumb_ar[i];
                        html += '<li class="'+ li_class +'"><a href="'+ link_href +'" class="'+ link_class +'">'+ path +'</a></li>'
                        }
                    }
                    return html;
                }
            }
        }

    })(jQuery);

    (function ($) {
        document.title = 'Файлы — ' + <?php echo json_encode($_smarty_tpl->tpl_vars['domain_idn']->value);?>
;
        new SiteFilemanager({
            $wrapper: $("#s-files-page"),
            $domain_id: '<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
',
            domain_url: '<?php echo $_smarty_tpl->tpl_vars['domain']->value['name'];?>
',
            $wa_app_url: '<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
',
            $dirs: <?php echo json_encode($_smarty_tpl->tpl_vars['dirs']->value);?>
,
            $sub_dirs_decoded: <?php echo json_encode($_smarty_tpl->tpl_vars['sub_dirs_decoded']->value);?>
,
            $files_path: '<?php echo $_smarty_tpl->tpl_vars['files_path']->value;?>
',
            $page: '<?php echo (($tmp = @$_smarty_tpl->tpl_vars['page']->value)===null||$tmp==='' ? 1 : $tmp);?>
',
            domain_protocol: <?php echo json_encode($_smarty_tpl->tpl_vars['domain_protocol']->value);?>
,
            domains_decode: <?php echo json_encode($_smarty_tpl->tpl_vars['domains_decode']->value);?>
,
            locales: {
                open: 'Открыть',
                download: 'Скачать',
                rename: 'Переименовать',
                move: 'Переместить в папку',
                delete: 'Удалить',
                delete_folder_title: 'Удалить папку',
                delete_content: "Папка будет удалена без возможности восстановления.",
                b: "Б",
                kb: "кБ",
                mb: "МБ",
                gb: "ГБ",
            }
        });
        })(jQuery);

</script>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:45:58
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/domain_tabs.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c8356bc82f2_69100555')) {function content_6a8c8356bc82f2_69100555($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.replace.php';
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