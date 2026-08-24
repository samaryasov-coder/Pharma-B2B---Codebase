<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:46:01
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/page/templates/Button.html" */ ?>
<?php /*%%SmartyHeaderCode:4760982876a8c83596c5f47-66136663%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b8482fd47450d35e3d6140a451292421a8b47919' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/page/templates/Button.html',
      1 => 1739968490,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4760982876a8c83596c5f47-66136663',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'cheat_sheet_name' => 0,
    'wa_backend_url' => 0,
    'wa' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c83596cf170_94545272',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c83596cf170_94545272')) {function content_6a8c83596cf170_94545272($_smarty_tpl) {?><div class="flexbox custom-ml-auto"<?php if ((($tmp = @$_smarty_tpl->tpl_vars['data']->value['is_block_page'])===null||$tmp==='' ? false : $tmp)){?> style="display: none;"<?php }?>>
    <button class="button nobutton js-show-variables gray"><i class="fas fa-dollar-sign"></i> Переменные</button>
    <button class="button nobutton js-show-cheatsheet gray" id="wa-editor-help-link-<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cheat_sheet_name']->value, ENT_QUOTES, 'UTF-8', true);?>
"><i class="fas fa-code"></i> Шпаргалка</button>
</div>

<script type="text/javascript">
    (function () {
        "use strict";
        const cheat_sheet_name = <?php echo json_encode($_smarty_tpl->tpl_vars['cheat_sheet_name']->value);?>
;
        let $cheatsheet_drawer;
        $.cheatsheet = $.cheatsheet || { };
        $.cheatsheet[cheat_sheet_name] = {
            data: <?php echo json_encode($_smarty_tpl->tpl_vars['data']->value);?>
,
            init: function () {
                this.createDrawer();
                this.getHelpEvent();
            },
            createDrawer() {
                const $cheatsheet_drawer_template = `
                            <div class="drawer js-cheatsheet-drawer" id="wa-editor-help-<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cheat_sheet_name']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                                <div class="drawer-background"></div>
                                <div class="drawer-body">
                                    <a href="#" class="drawer-close js-close-drawer"><i class="fas fa-times"></i></a>
                                    <div class="drawer-block"></div>
                                </div>
                            </div>`;
                if (!$('#wa-editor-help-<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cheat_sheet_name']->value, ENT_QUOTES, 'UTF-8', true);?>
').length) {
                    document.querySelector("body").insertAdjacentHTML('beforeend', $cheatsheet_drawer_template);
                }
                $cheatsheet_drawer = $('#wa-editor-help-<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cheat_sheet_name']->value, ENT_QUOTES, 'UTF-8', true);?>
')
            },
            getHelpEvent: function () {
                $("#wa-editor-help-link-" + cheat_sheet_name).on('click', function(e) {
                    e.preventDefault();

                    let $help = $cheatsheet_drawer.find('.drawer-block'),
                        data = $.cheatsheet[cheat_sheet_name].data;

                    /*                            if ($help.is(":visible")) {
                                                    $help.hide();
                                                    return false;
                                                }*/

                    let loadHelp = function (afterLoad) {
                        $help.load('<?php echo $_smarty_tpl->tpl_vars['wa_backend_url']->value;?>
?module=backendCheatSheet&action=cheatSheet&ui=<?php echo $_smarty_tpl->tpl_vars['wa']->value->whichUi();?>
', data, afterLoad);
                    };

                    let showHelp = function () {
                        $help.show();
                        let f = function (e) {
                            if ($(e.target).attr('id') == 'wa-editor-help-' + cheat_sheet_name || $(e.target).parents('#wa-editor-help-' + cheat_sheet_name).length) {
                                $(document).one('click', f);
                            } else {
                                $("#wa-editor-help-" + cheat_sheet_name).hide();
                            }
                        };
                        $(document).one('click', f);
                    };

                    if (!data.need_cache || !$help.data('loaded')) {
                        loadHelp(function () {
                            showHelp();
                            $help.data('loaded', 1);
                            $(document).trigger('wa_cheatsheet_load.' + cheat_sheet_name);
                        });
                    } else {
                        showHelp();
                    }

                    $.waDrawer({
                        $wrapper: $cheatsheet_drawer,
                        onOpen($drawer) {
                            $.cheatsheet[cheat_sheet_name].insertVarEvent($drawer)

                            if (!$('#js-cheatsheet-alerts').length) {
                                $('body:first').append(`<div id="js-cheatsheet-alerts" class="alert-fixed-box" style="top:0.5rem;right:3.5rem;">
                                    <div class="alert success js-copied-alert" style="display:none;">
                                        <i class="fas fa-check-circle"></i>
                                        Скопировано
                                    </div>
                                </div>`);
                            }
                        },
                        onBgClick() {
                            this.close();
                        }
                    });

                    return false;
                });
            },
            insertVarEvent: function ($drawer) {
                $drawer.on('click', ".js-var", function(e) {
                    e.preventDefault();
                    let el = $(this),
                        $design_content = $("#wa-design-content"),
                        $el_rte = $(".el-rte"),
                        $page_content = $('#wa-page-content');

                    if (el.children('i').length) {
                        el = el.children('i');
                    }
                    if (el.children('b').length) {
                        el = el.children('b');
                    }

                    $.wa.copyToClipboard(el.text()).then(() => {
                        const $copied_alert = $('#js-cheatsheet-alerts .js-copied-alert');
                        $copied_alert.show();
                        setTimeout(() => {
                            $copied_alert.hide();
                        }, 1500);
                    });
                });
            }
        };
        $(document).trigger('wa_cheatsheet_init.' + cheat_sheet_name);
        $.cheatsheet[cheat_sheet_name].init();

        const variables_template = html => `
            <div class="dialog" style="z-index:1030;">
                <div class="dialog-background"></div>
                <div class="dialog-body" style="width: 900px;">
                    <div class="dialog-content custom-p-0" data-cheatsheet-name="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['cheat_sheet_name']->value, ENT_QUOTES, 'UTF-8', true);?>
">${ html }</div>
                </div>
            </div>`;

        let is_loading = false;
        $('.js-show-variables').on('click', function(e) {
            if (is_loading) {
                return false;
            }
            is_loading = true;
            e.preventDefault();
            $.get('<?php echo $_smarty_tpl->tpl_vars['wa_backend_url']->value;?>
site/?module=variables&is_block_page=<?php echo (($tmp = @$_smarty_tpl->tpl_vars['data']->value['is_block_page'])===null||$tmp==='' ? false : $tmp);?>
&is_dialog=1', function(html) {
                $.waDialog({
                    html: variables_template(html),
                    onClose() {
                        const $cheatsheet_drawer = $('.js-cheatsheet-drawer');
                        const is_hide = !$cheatsheet_drawer.length || $cheatsheet_drawer.is(':hidden');
                        return is_hide;
                    }
                });
                is_loading = false;
            });
        });
    }());
</script>
<?php }} ?>