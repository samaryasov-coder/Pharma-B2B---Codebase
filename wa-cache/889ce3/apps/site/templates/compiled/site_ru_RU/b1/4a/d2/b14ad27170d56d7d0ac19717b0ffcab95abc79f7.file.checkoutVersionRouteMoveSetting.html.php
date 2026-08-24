<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:46:01
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/shop/templates/includes/checkoutVersionRouteMoveSetting.html" */ ?>
<?php /*%%SmartyHeaderCode:18631169516a8c8359654c59-73247953%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b14ad27170d56d7d0ac19717b0ffcab95abc79f7' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/shop/templates/includes/checkoutVersionRouteMoveSetting.html',
      1 => 1769513852,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18631169516a8c8359654c59-73247953',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c8359658082_02713118',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c8359658082_02713118')) {function content_6a8c8359658082_02713118($_smarty_tpl) {?>


<script>
    (function ($) {
        var $version_field = $('input[name="params[checkout_version]"]').parents('.field'),
            $theme_field = $('select[name="params[theme_mobile]"]').parents('.field');

        $version_field.insertAfter($theme_field);
        if ($version_field.find('input[name="params[checkout_version]"]:checked').val() == 2) {
            $version_field.hide();
        }
    })(jQuery);
</script>
<?php }} ?>