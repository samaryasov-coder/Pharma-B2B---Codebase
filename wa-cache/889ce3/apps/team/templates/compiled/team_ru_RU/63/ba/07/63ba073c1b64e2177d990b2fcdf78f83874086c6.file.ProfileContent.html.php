<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:52
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/profile/ProfileContent.html" */ ?>
<?php /*%%SmartyHeaderCode:8202313186a889d1c31b2e3-75279077%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '63ba073c1b64e2177d990b2fcdf78f83874086c6' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/profile/ProfileContent.html',
      1 => 1637225471,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8202313186a889d1c31b2e3-75279077',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    'wa_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d1c320bb8_83524279',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d1c320bb8_83524279')) {function content_6a889d1c320bb8_83524279($_smarty_tpl) {?><!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><title></title><?php echo $_smarty_tpl->tpl_vars['wa']->value->css();?>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery/jquery-3.6.0.min.js"></script><script>window.wa_skip_ajax_setup=1;</script><script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.js?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script><?php echo $_smarty_tpl->tpl_vars['wa']->value->js();?>
</head><body class="blank"><div class="t-dynamic-content"><!-- %content_start% --><div class="box align-center"><div class="spinner custom-p-16"></div></div><!-- %content_end% --></div>

<script>( function($) {

    var ProfileSection = ( function($) {

        ProfileSection = function(options) {
            const that = this;

            // DOM
            that.$frame = options["$frame"];

            // VARS
            that.rootWindow = options["rootWindow"];

            // DYNAMIC VARS

            // INIT
            that.initClass();
        };

        ProfileSection.prototype.initClass = function() {
            this.bindEvents();
        };

        ProfileSection.prototype.bindEvents = function() {
            /* Make links open in parent window instead of iframe */
            $(document).on("click", "a", function() {
                const $link = $(this);
                if (!$link.attr("target")) {
                    $link.attr("target", "_parent");
                }
            });
        };

        ProfileSection.prototype.reload = function() {
            if (this.rootWindow.hasOwnProperty("isProfileSidebarSection")) {
                document.querySelector('body').setAttribute('data-reload', '');
            }
        };

        ProfileSection.prototype.initScrollWatcher = function( $block, callback ) {
            const that = this;

            if ( !(callback && (typeof callback === "function") ) ) {
                return false;
            }

            $(that.rootWindow).on("scroll resize", onScroll);

            function onScroll() {
                const is_exist = ( $.contains(that.rootWindow.document, that.$frame[0]) && $.contains(document, $block[0]) );
                if (is_exist) {
                    const visibleFrameH = getVisibleFrameHeight();
                    callback(visibleFrameH);
                } else {
                    $(that.rootWindow).off("scroll resize", onScroll);
                }
            }

            function getVisibleFrameHeight() {
                let $window = $(that.rootWindow),
                    display_w = parseInt( $window.width() ),
                    display_h = parseInt( $window.height() ),
                    scroll_top = parseInt( $window.scrollTop() ),
                    frame_o = that.$frame.offset(),
                    frame_top = parseInt(frame_o.top),
                    frame_w = parseInt(that.$frame.width()),
                    frame_h = parseInt(that.$frame.height()),
                    top, right, bottom, left;

                bottom = ( display_h + scroll_top - frame_top );
                if ( bottom < 0 ) {
                    bottom = 0;
                }
                if (bottom > frame_h) {
                    bottom = frame_h;
                }

                top = 0;
                if (scroll_top > frame_top) {
                    const delta = parseInt(scroll_top - frame_top);
                    top = (delta > frame_h) ? frame_h : delta;
                }

                left = right = null;

                return {
                    top,
                    right,
                    bottom,
                    left,
                    display: {
                        width: display_w,
                        height: display_h
                    },
                    frame: {
                        width: frame_w,
                        height: frame_h
                    }
                };
            }
        };

        ProfileSection.prototype.trigger = function() {
            const $ifr = this.rootWindow.$(this.$frame[0]);
            $ifr.trigger.apply($ifr, [].slice.call(arguments));
        };

        return ProfileSection;

    })(jQuery);

    window.profileSection = new ProfileSection({
        $frame: $(window.frameElement),
        rootWindow: window.parent
    });

    // for legacy
    window.profileTab = window.profileSection;
})(jQuery);</script>

</body>
</html>
<?php }} ?>