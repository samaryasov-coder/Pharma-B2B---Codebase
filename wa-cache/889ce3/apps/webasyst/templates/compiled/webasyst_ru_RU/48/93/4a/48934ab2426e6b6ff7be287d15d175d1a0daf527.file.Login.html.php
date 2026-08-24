<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/layouts/Login.html" */ ?>
<?php /*%%SmartyHeaderCode:15406832756a88a073f13626-65008738%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '48934ab2426e6b6ff7be287d15d175d1a0daf527' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/layouts/Login.html',
      1 => 1738158327,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15406832756a88a073f13626-65008738',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    'env' => 0,
    'wa_url' => 0,
    'background' => 0,
    'stretch' => 0,
    'dialog_class' => 0,
    'error' => 0,
    '_styles' => 0,
    '_classes' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a88a073f1fcf1_82343825',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a88a073f1fcf1_82343825')) {function content_6a88a073f1fcf1_82343825($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_join')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.join.php';
?><!DOCTYPE html><html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $_smarty_tpl->tpl_vars['wa']->value->accountName();?>
</title>
    <meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; user-scalable=0;">

    <?php if ($_smarty_tpl->tpl_vars['env']->value=='backend'){?><meta name="robots" content="noindex, nofollow"/><?php }?>

    <?php echo $_smarty_tpl->tpl_vars['wa']->value->css();?>


    <link href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/css/login/backend/login-page.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
" rel="stylesheet">
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.core.js?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script>
    <script>$.wa.determineTimezone(<?php echo json_encode($_smarty_tpl->tpl_vars['wa_url']->value);?>
);</script>
</head>
<body style="background: linear-gradient(63deg, #2847a1 0%, #6499cd 46%, #63569e 100%);">

<?php $_smarty_tpl->tpl_vars['_styles'] = new Smarty_variable(array(), null, 0);?>
<?php if (!empty($_smarty_tpl->tpl_vars['background']->value)){?>
    <?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "background-image: url('".((string)$_smarty_tpl->tpl_vars['background']->value)."');";?>
    <?php if (empty($_smarty_tpl->tpl_vars['stretch']->value)){?>
        <?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "background-size: auto;";?>
    <?php }?>
<?php }?>

<?php $_smarty_tpl->tpl_vars['_classes'] = new Smarty_variable(array(), null, 0);?>
<?php if (!empty($_smarty_tpl->tpl_vars['dialog_class']->value)){?>
    <?php $_smarty_tpl->createLocalArrayVariable('_classes', null, 0);
$_smarty_tpl->tpl_vars['_classes']->value[] = $_smarty_tpl->tpl_vars['dialog_class']->value;?>
<?php }?>
<?php if (!empty($_smarty_tpl->tpl_vars['error']->value)){?>
    <?php $_smarty_tpl->createLocalArrayVariable('_classes', null, 0);
$_smarty_tpl->tpl_vars['_classes']->value[] = "error";?>
<?php }?>

<div class="wa-backend-login-page" id="wa-login" style="<?php echo smarty_modifier_join($_smarty_tpl->tpl_vars['_styles']->value," ");?>
">
    <div class="wa-backend-login-wrapper <?php echo smarty_modifier_join($_smarty_tpl->tpl_vars['_classes']->value," ");?>
" id="js-backend-login-wrapper">
        <div class="wa-backend-login">
            <div class="wa-backend-login-content">
                <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

            </div>
            <div class="wa-poweredby">
                <a href="https://www.webasyst.ru" title="Webasyst" target="_blank">
                    <img class="icon size-24" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/img/webasyst-wand-bold-black.svg" alt="Webasyst">
                </a>
            </div>
        </div>
        <script>
            ( function($) {
                var $dialog = $('#js-backend-login-wrapper'),
                    $form_wrapper = $dialog.find('.js-backend-auth-form'),
                    form = $form_wrapper.data('WaAuthForm');
                if (form && typeof form.setFocus === "function") {
                    form.setFocus();
                }
            })(jQuery);
        </script>
    </div>
</div>

</body>
</html>
<?php }} ?>