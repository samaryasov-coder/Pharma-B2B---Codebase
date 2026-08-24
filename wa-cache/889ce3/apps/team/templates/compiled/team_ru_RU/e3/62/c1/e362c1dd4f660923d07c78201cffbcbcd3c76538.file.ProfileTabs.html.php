<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/profile/ProfileTabs.html" */ ?>
<?php /*%%SmartyHeaderCode:9221490156a889d14c78552-34294893%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e362c1dd4f660923d07c78201cffbcbcd3c76538' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/profile/ProfileTabs.html',
      1 => 1718018908,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9221490156a889d14c78552-34294893',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tabs' => 0,
    'tab' => 0,
    'is_system_profile' => 0,
    'tab_id' => 0,
    'uniqid' => 0,
    'selected_tab' => 0,
    'profile_content_layout_template' => 0,
    'layout_html' => 0,
    'contact_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d14c8a5a6_23650052',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d14c8a5a6_23650052')) {function content_6a889d14c8a5a6_23650052($_smarty_tpl) {?>

<?php if ('is_profile_sidebar'){?>
    <?php  $_smarty_tpl->tpl_vars['tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tab']->_loop = false;
 $_smarty_tpl->tpl_vars['tab_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tab']->key => $_smarty_tpl->tpl_vars['tab']->value){
$_smarty_tpl->tpl_vars['tab']->_loop = true;
 $_smarty_tpl->tpl_vars['tab_id']->value = $_smarty_tpl->tpl_vars['tab']->key;
?>
        <?php if (!empty($_smarty_tpl->tpl_vars['tab']->value['html'])&&empty($_smarty_tpl->tpl_vars['tab']->value['url'])&&($_smarty_tpl->tpl_vars['is_system_profile']->value||$_smarty_tpl->tpl_vars['tab']->value['id']!='info'&&$_smarty_tpl->tpl_vars['tab']->value['id']!='access')){?>
            <div class="hidden js-tab-content-<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['tab_id']->value, ENT_QUOTES, 'UTF-8', true);?>
"><?php echo $_smarty_tpl->tpl_vars['tab']->value['html'];?>
</div>
        <?php }?>
    <?php } ?>
<?php }else{ ?>
<ul class="t-profile-tabs tabs overflow-arrows" id="t-profile-tabs-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
">
    <?php  $_smarty_tpl->tpl_vars['tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tab']->_loop = false;
 $_smarty_tpl->tpl_vars['tab_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tab']->key => $_smarty_tpl->tpl_vars['tab']->value){
$_smarty_tpl->tpl_vars['tab']->_loop = true;
 $_smarty_tpl->tpl_vars['tab_id']->value = $_smarty_tpl->tpl_vars['tab']->key;
?>
        <li class="t-tab <?php if ($_smarty_tpl->tpl_vars['selected_tab']->value==$_smarty_tpl->tpl_vars['tab_id']->value){?>selected<?php }?>" data-tab-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['tab_id']->value, ENT_QUOTES, 'UTF-8', true);?>
">
            <a href="javascript:void('<?php echo strtr($_smarty_tpl->tpl_vars['tab_id']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
')" data-url="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['tab']->value['url'])===null||$tmp==='' ? '' : $tmp);?>
" data-tab-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['tab_id']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                <?php echo $_smarty_tpl->tpl_vars['tab']->value['title'];?>

                <?php if (!empty($_smarty_tpl->tpl_vars['tab']->value['count'])){?>
                    <span class="hint"><?php echo $_smarty_tpl->tpl_vars['tab']->value['count'];?>
</span>
                <?php }?>
            </a>
        </li>
    <?php } ?>
</ul>

<div id="t-profile-tabs-iframes-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
" class="t-profile-tabs-iframes"></div>

<div class="hidden" id="t-profile-tabs-layout-html-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
"><?php $_smarty_tpl->tpl_vars['layout_html'] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['profile_content_layout_template']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0));?>
<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['layout_html']->value, ENT_QUOTES, 'UTF-8', true);?>
</div>

<div class="hidden" id="t-profile-tabs-html-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
">
    <?php  $_smarty_tpl->tpl_vars['tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tab']->_loop = false;
 $_smarty_tpl->tpl_vars['tab_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tab']->key => $_smarty_tpl->tpl_vars['tab']->value){
$_smarty_tpl->tpl_vars['tab']->_loop = true;
 $_smarty_tpl->tpl_vars['tab_id']->value = $_smarty_tpl->tpl_vars['tab']->key;
?>
        <?php if (!empty($_smarty_tpl->tpl_vars['tab']->value['html'])&&empty($_smarty_tpl->tpl_vars['tab']->value['url'])){?>
            <div data-tab-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['tab_id']->value, ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['tab']->value['html'], ENT_QUOTES, 'UTF-8', true);?>
</div>
        <?php }?>
    <?php } ?>
</div>

<script>$(function() { "use strict";

    var contact_id = <?php echo json_encode($_smarty_tpl->tpl_vars['contact_id']->value);?>
;
    var $tabs = $('#t-profile-tabs-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
');
    var $tabs_html = $('#t-profile-tabs-html-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
');
    var $iframes_wrapper = $('#t-profile-tabs-iframes-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
');
    var error_gettings_tab_contents_msg = "Error getting tab contents.";
    var $layout_html = $('#t-profile-tabs-layout-html-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
');

    $tabs.data('tabs_controller', {
        showTabHtml: showTabHtml,
        updateIframeHeight: updateIframeHeight,
        switchToTab: switchToTab
    });

    setTimeout(init, 0); // timeout to allow other JS to modify something

    function init() {

        $tabs.on("click", ".t-tab a", function() {
            var $link = $(this);
            var $tab = $link.closest(".t-tab");

            if ($tab.hasClass('selected')) {
                return false;
            }

            $tab.addClass('selected').siblings('.selected').removeClass('selected');
            var tab_id = $link.data('tab-id');

            // Is there an iframe ready to show?
            var $iframe = $iframes_wrapper.children().hide().filter(function() {
                return tab_id == $(this).data('tab-id');
            }).first();
            if ($iframe.length) {
                $iframe.show();
                return false;
            }

            // Is there content pre-loaded into DOM?
            var $content_wrapper = $tabs_html.children().filter(function() {
                return tab_id == $(this).data('tab-id');
            }).first();
            if ($content_wrapper.length) {
                showTabHtml(tab_id, $content_wrapper.first().text());
                $content_wrapper.remove();
                return false;
            }

            // Is there a URL to load tab contents from?
            var url = $link.data('url');
            if (url) {
                showTabHtml(tab_id, '<div class="block double-padded"><i class="icon16 loading"></i></div>');
                $.ajax({
                    url: url,
                    success: function(result) {
                        showTabHtml(tab_id, result);
                    },
                    error: function() {
                        console.log(error_gettings_tab_contents_msg);
                        console.log.apply(console, arguments);
                        showTabHtml(tab_id, '<div class="block double-padded"><i class="icon16 no"></i> '+error_gettings_tab_contents_msg+'</div>');
                    },
                    dataType: 'html'
                });
                return false;
            }

            // Nowhere to get contents from :(
            console.log(error_gettings_tab_contents_msg);
            console.log('No HTML contents and no URL to load from.');
            showTabHtml(tab_id, '<div class="block double-padded"><i class="icon16 no"></i> '+error_gettings_tab_contents_msg+'</div>');
            return false;
        });

        // Update height of visible iframe once in a while
        var interval = setInterval(function() {
            if (!$.contains(document.body, $iframes_wrapper[0])) {
                clearInterval(interval);
                return;
            }
            var iframe = $iframes_wrapper.children(':visible')[0];
            if (iframe) {
                updateIframeHeight(iframe);
            }
        }, 100);

        $tabs.children('.t-tab.is-selected').removeClass('is-selected').first().find('a').click();
    }

    function showTabHtml(tab_id, html) {
        // Remove existing iframe if there is one in DOM
        var $iframes = $iframes_wrapper.children().filter(function() {
            return tab_id == $(this).data('tab-id');
        });
        var is_visible = !$iframes.length || $iframes.first().is(':visible');
        $iframes.remove();

        // Create new iframe for the tab
        var iframe = document.createElement('iframe');
        var $iframe = $(iframe).data('tab-id', tab_id).appendTo($iframes_wrapper);
        if (!is_visible) {
            $iframe.hide();
        }

        delayWrite();

        return iframe;

        function delayWrite(times) {

            times = times || 0;

            // Write tab contents into iframe. Delaying because iframe might not be ready yet.
            // Also prevents JS errors from breaking something outside the iframe,
            // while still showing them in console natively.
            setTimeout(function() {
                if (!iframe.contentWindow) {
                    if (times <= 5) delayWrite(times + 1);
                    return;
                }
                iframe.contentWindow.document.open();
                iframe.contentWindow.document.write(
                    $layout_html.text().replace(/<!--\s*%content_start%\s*-->[\s\S]*<!--\s*%content_end%\s*-->/, html)
                );
                iframe.contentWindow.document.close();
                if ($iframe.is(':visible')) {
                    updateIframeHeight(iframe);
                    $tabs.find('.t-tab a[data-tab-id="'+tab_id+'"]').trigger('tab_content_updated');
                }
            }, times*150);
        }
    }

    // Helper for application code to manipulate tab when content is ready
    function switchToTab(tab_id, testCallback) {

        testCallback = testCallback || function() { return true; };
        var deferred = $.Deferred();
        var $tab_a = $tabs.find('.t-tab a[data-tab-id="'+tab_id+'"]').click();
        $tab_a.on('tab_content_updated', tryCallback);
        var interval = setInterval(tryCallback, 100);
        setTimeout(tryCallback, 0);
        return deferred.promise();

        function tryCallback() {
            var $iframe = $iframes_wrapper.children().filter(function() {
                return tab_id == $(this).data('tab-id');
            }).first();
            try {
                if (!$iframe[0].contentWindow || !testCallback($iframe)) {
                    return;
                }
                $tab_a.off('tab_content_updated', tryCallback);
                if (interval) {
                    clearInterval(interval);
                    interval = null;
                }
                setTimeout(function() {
                    deferred.resolve($iframe);
                }, 0);
            } catch (e) {
            }
        }
    }

    function updateIframeHeight(iframe) {
        try {
            var body = iframe.contentWindow.document.body,
                html = iframe.contentWindow.document.documentElement;

            iframe.style.height = Math.max(
                body.scrollHeight,
                body.offsetHeight,
                html.clientHeight,
                html.scrollHeight,
                html.offsetHeight
            ) + 'px';
        } catch (e) {
        }
    }

});
</script>
<?php }?>
<?php }} ?>