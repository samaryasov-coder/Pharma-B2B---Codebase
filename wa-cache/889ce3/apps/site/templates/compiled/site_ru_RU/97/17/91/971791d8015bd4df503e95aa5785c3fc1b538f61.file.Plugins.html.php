<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:23
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/plugin/templates-no-sidebar/Plugins.html" */ ?>
<?php /*%%SmartyHeaderCode:10249171886a8c827fad7c85-09146426%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '971791d8015bd4df503e95aa5785c3fc1b538f61' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/plugin/templates-no-sidebar/Plugins.html',
      1 => 1750315810,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10249171886a8c827fad7c85-09146426',
  'function' => 
  array (
    'plugin_skeleton' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'wa_url' => 0,
    'wa' => 0,
    'container_class' => 0,
    'installer' => 0,
    'plugins' => 0,
    'plugin' => 0,
    'max_count_plugins' => 0,
    'plugins_hash' => 0,
    'other_count' => 0,
    'wa_backend_url' => 0,
    'plugin_module' => 0,
    'domain_id' => 0,
    'plugin_names' => 0,
    'is_ajax' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c827fb01885_02189486',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c827fb01885_02189486')) {function content_6a8c827fb01885_02189486($_smarty_tpl) {?><link href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/lib/codemirror.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/lib/codemirror.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/mode/xml/xml.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/mode/javascript/javascript.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/mode/css/css.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/ace/ace.js?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script>

<?php $_smarty_tpl->tpl_vars['max_count_plugins'] = new Smarty_variable(6, null, 0);?>
<style>
.s-installed-plugin-list .s-installed-list-thumbs {
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    list-style: none;
    margin-bottom: 0;
}
.s-installed-plugin-list .s-installed-list-thumbs .s-plugin-item {
    word-break: break-word;
    padding: 0;
    flex: 0 0 calc(33.33%);
    margin-right: 0;
    padding-bottom: 1rem;
}
.s-installed-plugin-list .s-installed-list-thumbs .s-plugin-item .s-plugin-image-wrapper {
    position: relative;
}
.wa-plugins-store-wrapper .wa-plugins-store-header { margin: 2rem 0 -0.5rem 1.25rem; position: relative; z-index: 5; }
</style>

<?php $_smarty_tpl->tpl_vars['plugin_names'] = new Smarty_variable(array(), null, 0);?>
<div id="wa-plugins-container" class="no-sidebar content flexbox<?php if (!empty($_smarty_tpl->tpl_vars['container_class']->value)){?> <?php echo $_smarty_tpl->tpl_vars['container_class']->value;?>
<?php }?>">
    <div class="content ">
        <div class="article wider fields">
            <div class="article-top ">
                <div class="fields-group s-installed-plugin-list<?php if (empty($_smarty_tpl->tpl_vars['installer']->value)){?> custom-pt-16<?php }?> custom-m-0">
                    <h4>Установленные</h4>
                    <?php if (!empty($_smarty_tpl->tpl_vars['plugins']->value)){?>
                        <ul class="s-installed-list-thumbs js-plugin-list" id="wa-plugin-list">
                            
                            <?php  $_smarty_tpl->tpl_vars['plugin'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['plugin']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['plugins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['plugin']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['plugin']->key => $_smarty_tpl->tpl_vars['plugin']->value){
$_smarty_tpl->tpl_vars['plugin']->_loop = true;
 $_smarty_tpl->tpl_vars['plugin']->index++;
?>
                                <?php $_smarty_tpl->createLocalArrayVariable('plugin_names', null, 0);
$_smarty_tpl->tpl_vars['plugin_names']->value[$_smarty_tpl->tpl_vars['plugin']->value['id']] = htmlspecialchars((string)$_smarty_tpl->tpl_vars['plugin']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
                                <li id="plugin-<?php echo $_smarty_tpl->tpl_vars['plugin']->value['id'];?>
" class="s-plugin-item"
                                    <?php if (!empty($_smarty_tpl->tpl_vars['plugin']->value['custom_settings_url'])){?>
                                        data-url="<?php echo $_smarty_tpl->tpl_vars['plugin']->value['custom_settings_url'];?>
"
                                    <?php }elseif(!empty($_smarty_tpl->tpl_vars['plugin']->value['custom_settings'])){?>
                                        data-settings="1"
                                    <?php }?>
                                    <?php if (($_smarty_tpl->tpl_vars['plugin']->index+1)>$_smarty_tpl->tpl_vars['max_count_plugins']->value){?> style="display: none;"<?php }?>
                                >
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['plugins_hash']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['plugin']->value['id'];?>
/">
                                        <?php if (!isset($_smarty_tpl->tpl_vars['plugin']->value['img'])){?>
                                            <span class="s-plugin-icon"><i class="fas fa-plug"></i></span>
                                        <?php }else{ ?>
                                            <span class="icon">
                                                <img class="s-plugin-icon" src="<?php echo wa_url();?>
<?php echo $_smarty_tpl->tpl_vars['plugin']->value['img'];?>
" alt="">
                                            </span>
                                        <?php }?>
                                        <span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['plugin']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php }else{ ?>
                        <div class="gray">
                            Плагины не установлены.
                        </div>
                    <?php }?>
                    <?php if (count($_smarty_tpl->tpl_vars['plugins']->value)>$_smarty_tpl->tpl_vars['max_count_plugins']->value){?>
                    <?php $_smarty_tpl->tpl_vars['other_count'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['plugins']->value)-$_smarty_tpl->tpl_vars['max_count_plugins']->value, null, 0);?>
                        <div class="custom-my-8">
                            <a href="javascript:void(0)" class="button light-gray full-width js-plugins-show-more">
                                <span class="visible-name">Показать еще (<?php echo $_smarty_tpl->tpl_vars['other_count']->value;?>
) <span class="show-more-number"></span><i class="icon fas fa-caret-down"></i></span>
                                <span class="hidden-name" style="display: none;">Скрыть <i class="icon fas fa-caret-up"></i></span></a>
                        </div>
                    <?php }?>
                </div>
            </div>

            <div id="wa-plugins-content" class="article-main-content" style="display:none;">
                <?php if (!empty($_smarty_tpl->tpl_vars['plugins']->value)||!empty($_smarty_tpl->tpl_vars['installer']->value)){?>Загрузка... <i class="fas fa-spinner fa-spin loading"></i><?php }?>
            </div>
            <div class="wa-plugins-store-wrapper">
                <h4 class="wa-plugins-store-header hidden">Все плагины</h4>
                <div id="wa-plugins-store" class="article-main-content" style="display:none;">
                    <div style="margin:2rem 0 -0.5rem 1.5rem;"><?php if (!empty($_smarty_tpl->tpl_vars['plugins']->value)||!empty($_smarty_tpl->tpl_vars['installer']->value)){?>Загрузка... <i class="fas fa-spinner fa-spin loading"></i><?php }?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!function_exists('smarty_template_function_plugin_skeleton')) {
    function smarty_template_function_plugin_skeleton($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['plugin_skeleton']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><div class="skeleton"><div><span class="skeleton-line" style="height: 40px;"></span><?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? 3+1 - (1) : 1-(3)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 1, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?><span class="skeleton-header" style="height: 100px;"></span><?php }} ?></div></div><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<script type="text/javascript">
(function ($) {
    var plugins_title = <?php echo json_encode(_ws('Plugins'));?>
;
    $.plugins = {
        $wrapper: null,
        $content: null,
        $store_content: null,
        options: {
            loading: '',
            path: '<?php echo $_smarty_tpl->tpl_vars['plugins_hash']->value;?>
/',
            useIframeTransport: false
        },
        path: {
            plugin: false,
            tail: null,
            params: {

            }
        },
        icon: {
            submit: '<i class="fas fa-spinner fa-spin loading"></i>',
            success: '<i class="fas fa-check-circle"></i>',
            error: '<i class="fas fa-times-circle"></i>'
        },

        ready: false,
        $menu: null,
        /**
         * @var Number
         */
        timer: null,
        xhr: null,

        init: function (options) {
            this.options = $.extend(this.options, options || { });
            if (this.ready) {
                return;
            }
            this.ready = true;
            this.$wrapper = $('#wa-plugins-container');
            this.$content = $("#wa-plugins-content");
            this.$store_content = $("#wa-plugins-store");
            this.$menu = $('.js-plugin-list');

            // Set up AJAX to never use cache
            $.ajaxSetup({
                cache: false
            });

            if ($.wa) {
                $.wa.errorHandler = function (xhr) {
                    if ((xhr.status === 403) || (xhr.status === 404)) {
                        var text = $(xhr.responseText);
                        if (text.find('.dialog-content').length) {
                            text = $('<div class="block double-padded"></div>').append(text.find('.dialog-content'));

                        } else {
                            text = $('<div class="block double-padded"></div>').append(text.find(':not(style)'));
                        }
                        this.$content.empty().append(text);
                        return false;
                    }
                    return true;
                };
            }

            this.dispatch(location.hash, true);

            if (this.$menu.find('> li:not(.js-plugins-list) > a').length) {
                this.helper.loadJqUI(function() {
                    Sortable.create($.plugins.$menu[0], {
                        draggable: 'li:not(.js-plugins-list)',
                        animation: 150,
                        removeCloneOnHide: true,
                        onEnd: function (evt) {
                            const $item = $(evt.item);
                            const { oldIndex, newIndex } = evt;

                            const revertSort = () => {
                                $item.swap(oldIndex);
                            };

                            $.ajax({
                                type: 'POST',
                                url: '?module=plugins&action=sort',
                                data: {
                                    slug: $item.attr('id').replace(/^plugin-/, ''),
                                    pos: newIndex
                                },
                                success: function (data, textStatus, jqXHR) {
                                    if (!data || !data.status || data.status != "ok") {
                                        revertSort();
                                    }

                                },
                                error: function () {
                                    revertSort();
                                }
                            });
                        }
                    });
                });
            }

            // Load plugins from store
            this.$store_content.load('<?php echo $_smarty_tpl->tpl_vars['wa_backend_url']->value;?>
installer/?module=plugins&action=view&slug=<?php echo $_smarty_tpl->tpl_vars['wa']->value->app();?>
&hide_back=1');
        },

        parsePath: function (path) {
            path = path.replace(new RegExp('^.*' + this.options.path), '');

            var splited_array = path.split("/"),
                tail = (splited_array.length > 1) ? splited_array[1] : null;

            return {
                plugin: path.replace(/\/.*$/, '') || null,
                tail: tail,
                raw: path
            };
        },

        dispatch: function (hash, force) {
            var $plugin;
            // in specific plugin inline script set it flag to true for iframe form posting
            this.options.useIframeTransport = false;

            if (hash === undefined) {
                hash = window.location.hash;
            }

            if (!hash) {
                $plugin = this.$menu.find('li:first > a:first');
                if ($plugin.length) {
                    hash = $plugin.attr('href');
                }
            }

            //
            // So, at this point `hash` can be either the full weindow.location.hash,
            // OR a part of the hash passed to us by wrapping controller
            // e.g. see pluginsAction in site.js
            //
            // parsePath() is supposed to deal with this mess
            var path = this.parsePath(hash);

            // Set a proper window.location.hash if we managed to parse the plugin
            if (path && path.plugin) {
                var full_hash = this.options.path + path.plugin;
                if (window.location.hash !== full_hash) {
                    if (window.history && window.history.pushState) {
                        const content_url = location.href + path.plugin;
                        window.history.pushState({ content_url }, null, content_url);
                    } else {
                        window.location.hash = full_hash;
                    }
                }
            }

            this.path.dispatch = path;
            var load = force || (path.plugin !== this.path.plugin);

            /* change plugins section */
            if (!load) {
                return;
            }

            this.path.tail = null;
            $plugin = $(path.plugin ? ("#plugin-" + path.plugin) : '.js-plugins-list');
            var url = this.helper.getContentUrl($plugin, path);
            if (!url) {
                // All plugins
                this.showList();
                this.showStore();
                $(document).trigger('load_all.wa_plugins');
                return;
            }

            var $content = this.$content.show();
            this.path.plugin = path.plugin;
            this.hideList();
            this.hideStore();
            $(document).trigger('load_plugin.wa_plugins');

            if (this.xhr) {
                this.xhr.abort();
            }
            $content.html(this.options.loading_plugin);
            var self = this;
            this.xhr = $.ajax({
                url: url,
                success: function (data) {
                    self.xhr = null;
                    if (self.path.plugin == path.plugin) {
                        $content.html(data);

                        // update title
                        if (self.path.plugin) {
                            document.title = self.options.plugin_names[self.path.plugin] + self.options.title_suffix;
                        } else {
                            document.title = plugins_title + self.options.title_suffix;
                        }

                        self.$menu.find('li.selected').removeClass('selected');
                        var href = self.options.path + (self.path.plugin ? self.path.plugin + '/' : '');
                        self.$menu.find('a[href="' + href + '"]').parents('li').addClass('selected');

                        $(document).trigger('wa_loaded');

                        if (!self.options.useIframeTransport) {
                            $('#plugins-settings-form').submit(function () {
                                self.saveHandlerAjax(this);
                                return false;
                            });
                        } else {
                            $('#plugins-settings-form').submit(function () {
                                self.saveHandlerIframe(this);
                            });
                        }
                    }
                    $(document).trigger('loaded_plugin.wa_plugins');
                }
            });
        },

        saveHandlerIframe: function (form) {
            var self = this;
            this.message('submit');
            $("#plugins-settings-iframe").one('load', function () {
                var r = null;
                try {
                    r = $.parseJSON($(this).contents().find('body').html());
                } catch (e) {
                }
                if (r && r.status == 'ok') {
                    var message = 'Сохранено';
                    if (r.data && r.data.message) {
                        message = r.data.message;
                    }
                    self.message('success', message);
                    $(self).trigger('success', [r]);
                } else {
                    self.message('error', r && r.errors || 'parsererror');
                    $(self).trigger('error', [r]);
                }
            });
        },
        saveHandlerAjax: function (form) {
            var self = this;
            this.message('submit');
            var $form = $(form),
                fields_data = $form.serializeArray(),
                form_data = new FormData();

            $.each(fields_data, function () {
                var field = $(this)[0];
                form_data.append(field.name, field.value);
            });

            // Add files
            var $file_controls = $form.find('input[type="file"]');
            $file_controls.each(function (i, input) {
                var $input = $(input);

                if (input['files'].length) {
                    form_data.append($input.attr('name'), input['files'][0]);
                }
            });

            $.ajax({
                url: $form.attr('action'),
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    if (data && (data.status == 'ok')) {
                        var message = 'Сохранено';
                        if (data.data && data.data.message) {
                            message = data.data.message;
                        }
                        self.message('success', message);
                        $(self).trigger('success', [data]);
                    } else {
                        self.message('error', data.errors || []);
                        $(self).trigger('error', [data]);
                    }
                },
                error: function (jqXHR, errorText) {
                    self.message('error', [
                        [errorText]
                    ]);
                    $(self).trigger('error', [errorText]);
                }
            });
        },

        helper: {
            getContentUrl: function ($item, path) {

                var url = '';
                if ($item.data('url')) {
                    url = $item.data('url');
                } else if ($item.data('settings')) {
                    url = '?plugin=' + path.plugin + '&module=settings';
                } else if (path.plugin) {
                    var plugin_module = '<?php if (empty($_smarty_tpl->tpl_vars['plugin_module']->value)){?>plugins<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['plugin_module']->value;?>
<?php }?>';
                    var param_domain_id = '<?php if (empty($_smarty_tpl->tpl_vars['domain_id']->value)){?><?php }else{ ?>&domain_id=<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
<?php }?>';
                    url = '?module=' + plugin_module + '&action=settings&id=' + path.plugin + param_domain_id;
                }

                url += ( path.tail ? "&" + path.tail : "" );

                return url;
            },

            loadJqUI: function(callback) {
                var files = [];
                if (!$.ui) {
                    files.push('wa-content/js/jquery-ui/jquery.ui.core.min.js');
                }
                if (!$.widget) {
                    files.push('wa-content/js/jquery-ui/jquery.ui.widget.min.js');
                }
                if (!$.ui || !$.ui.mouse) {
                    files.push('wa-content/js/jquery-ui/jquery.ui.mouse.min.js');
                }
                if (typeof Sortable === "undefined") {
                    files.push('wa-content/js/sortable/sortable.min.js');
                }
                if (!$().swap) {
                    files.push('wa-content/js/jquery-plugins/jquery.swap.js');
                }

                if (files.length) {
                    $.when.apply($, files.map(function(file) {
                        return $.getScript($.plugins.options.wa_url + file);
                    })).then(callback);
                } else {
                    callback();
                }
            }
        },

        message: function (status, message) {
            /* enable previous disabled inputs */

            var $container = $('#plugins-settings-form-status');
            $container.empty().show();
            var $parent = $container.parents('div.value');
            $parent.removeClass('errormsg successmsg status');

            if (this.timer) {
                clearTimeout(this.timer);
            }
            var timeout = null;
            $container.append(this.icon[status] || '');
            switch (status) {
                case 'submit':
                    $parent.addClass('status');
                    break;
                case 'error':
                    $parent.addClass('errormsg');
                    for (var i = 0; i < message.length; i++) {
                        $container.append(message[i][0]);
                    }
                    timeout = 20000;
                    break;
                case 'success':
                    if (message) {
                        $parent.addClass('successmsg');
                        $container.append(message);
                    }
                    timeout = 3000;
                    break;
            }
            if (timeout) {
                this.timer = setTimeout(function () {
                    $parent.removeClass('errormsg successmsg status');
                    $container.empty().show();
                }, timeout);
            }
        },

        showList: function () {
            $('.s-installed-plugin-list', this.$wrapper).show();
            this.$content.hide();
        },
        hideList: function () {
            $('.s-installed-plugin-list', this.$wrapper).hide();
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
            $('.wa-plugins-store-header').show();
        },
        hideStoreHeader: function () {
            $('.wa-plugins-store-header').hide();
        },
        enableStoreHeader: function () {
            $('.wa-plugins-store-header').removeClass('hidden');
        }
    };

    $.plugins.init({
        'wa_url': <?php echo json_encode($_smarty_tpl->tpl_vars['wa_url']->value);?>
,
        'loading': <?php echo json_encode('<div class="custom-ml-20 custom-mt-24">Загрузка... <i class="fas fa-spinner fa-spin loading"></i></div>');?>
,
        'loading_plugin': '<?php smarty_template_function_plugin_skeleton($_smarty_tpl,array());?>
',
        'title_suffix': ' — <?php echo strtr($_smarty_tpl->tpl_vars['wa']->value->accountName(false), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',
        'plugin_names': <?php echo json_encode($_smarty_tpl->tpl_vars['plugin_names']->value);?>

    });

    $('.js-plugins-show-more').on('click', function  () {
        $('.s-installed-list-thumbs .s-plugin-item:nth-child(n+<?php echo $_smarty_tpl->tpl_vars['max_count_plugins']->value+1;?>
)').slideToggle(300);
        $(this).find('.visible-name').toggle();
        $(this).find('.hidden-name').toggle();
    });

    <?php if (empty($_smarty_tpl->tpl_vars['is_ajax']->value)){?>
        $('.js-plugin-list a, .js-plugins-list').on('click', function  () {
            $.plugins.dispatch($(this).attr('href'), true);
            return false;
        });
    <?php }?>

    
    $(document).on('installer_after_install_go_to_settings', function(e, data) {
        if (data.type === 'plugin' && !data.is_payment && !data.is_shipping) {
            sessionStorage.setItem('wa_plugin_onload', data.id);
            location.reload();
        }
    });
    const wa_plugin_onload = sessionStorage.getItem('wa_plugin_onload');
    if (wa_plugin_onload) {
        $('.js-plugin-list > li#plugin-' + wa_plugin_onload + ' > a').on('click', function  () {
            sessionStorage.removeItem('wa_plugin_onload');
            $.plugins.dispatch($(this).attr('href'), true);
            return false;
        }).click();
    }

})(jQuery);
</script>
<?php }} ?>