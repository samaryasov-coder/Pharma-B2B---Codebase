<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/form_wrapper.html" */ ?>
<?php /*%%SmartyHeaderCode:6859705126a88a073efc811-92641731%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '52dc06d4db2eff07335f38ced5e0a463bf8459d0' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/form_wrapper.html',
      1 => 1738158327,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6859705126a88a073efc811-92641731',
  'function' => 
  array (
    'include_assets' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
    'include_form_js' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
    'wrap' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'include_js' => 0,
    'include_css' => 0,
    'wa_url' => 0,
    'wa' => 0,
    'wrapper_id' => 0,
    'namespace' => 0,
    'auth_config' => 0,
    'errors' => 0,
    'messages' => 0,
    'onetime_password_url' => 0,
    '_locale' => 0,
    'is_json_mode' => 0,
    'webasyst_id_auth_url' => 0,
    'bind_with_webasyst_contact' => 0,
    'wa_app_url' => 0,
    'class_id' => 0,
    'classes' => 0,
    'renderer' => 0,
    'html' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a88a073f0e471_49113360',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a88a073f0e471_49113360')) {function content_6a88a073f0e471_49113360($_smarty_tpl) {?>

<?php $_smarty_tpl->tpl_vars['include_js'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['include_js']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['include_css'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['include_css']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>





<?php if (!function_exists('smarty_template_function_include_assets')) {
    function smarty_template_function_include_assets($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['include_assets']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php if ($_smarty_tpl->tpl_vars['include_js']->value){?>
        <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/login/login-backend-form.min.js?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"></script>
    <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['include_css']->value){?>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php }?>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php if (!function_exists('smarty_template_function_include_form_js')) {
    function smarty_template_function_include_form_js($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['include_form_js']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php $_smarty_tpl->tpl_vars['_locale'] = new Smarty_variable(array('required'=>_ws('Field is required'),'login_required'=>_ws('Login is required'),'password_required'=>_ws('Password is required'),'captcha_required'=>_ws('Captcha is required')), null, 0);?>
    <script>
        (function($) {
            new WaBackendLogin({
                $wrapper: $("#<?php echo $_smarty_tpl->tpl_vars['wrapper_id']->value;?>
"),
                namespace: <?php echo json_encode($_smarty_tpl->tpl_vars['namespace']->value);?>
,
                auth_type: <?php echo json_encode($_smarty_tpl->tpl_vars['auth_config']->value['auth_type']);?>
,
                errors: <?php echo json_encode($_smarty_tpl->tpl_vars['errors']->value);?>
,
                messages: <?php echo json_encode($_smarty_tpl->tpl_vars['messages']->value);?>
,
                onetime_password_url: <?php echo json_encode($_smarty_tpl->tpl_vars['onetime_password_url']->value);?>
,
                locale: <?php echo json_encode($_smarty_tpl->tpl_vars['_locale']->value);?>
,
                is_json_mode: <?php echo json_encode((($tmp = @$_smarty_tpl->tpl_vars['is_json_mode']->value)===null||$tmp==='' ? false : $tmp));?>
,
                webasyst_id_auth_url: <?php echo json_encode((($tmp = @$_smarty_tpl->tpl_vars['webasyst_id_auth_url']->value)===null||$tmp==='' ? '' : $tmp));?>
,
                bind_with_webasyst_contact: <?php echo json_encode((($tmp = @$_smarty_tpl->tpl_vars['bind_with_webasyst_contact']->value)===null||$tmp==='' ? false : $tmp));?>
,
                wa_app_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>

            });
        })(jQuery);
    </script>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
?><?php if (!function_exists('smarty_template_function_wrap')) {
    function smarty_template_function_wrap($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['wrap']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php $_smarty_tpl->tpl_vars['class_id'] = new Smarty_variable('wa-login-form', null, 0);?>
    <?php $_smarty_tpl->tpl_vars['wrapper_id'] = new Smarty_variable(uniqid($_smarty_tpl->tpl_vars['class_id']->value), null, 0);?>

    <?php $_smarty_tpl->tpl_vars['classes'] = new Smarty_variable(array($_smarty_tpl->tpl_vars['class_id']->value), null, 0);?>

    <?php if ($_smarty_tpl->tpl_vars['include_js']->value){?>
        <?php $_smarty_tpl->createLocalArrayVariable('classes', null, 0);
$_smarty_tpl->tpl_vars['classes']->value[] = 'js-backend-auth-form';?>
    <?php }?>

    <?php $_smarty_tpl->tpl_vars['classes'] = new Smarty_variable(join(' ',$_smarty_tpl->tpl_vars['classes']->value), null, 0);?>

    <div class="<?php echo $_smarty_tpl->tpl_vars['classes']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['wrapper_id']->value;?>
">
        <h1 class="wa-login-form-title custom-mt-8 break-word"><?php echo htmlspecialchars((string)smarty_modifier_truncate($_smarty_tpl->tpl_vars['renderer']->value->getTitle(),32,'...'), ENT_QUOTES, 'UTF-8', true);?>
</h1>
        <?php echo $_smarty_tpl->tpl_vars['html']->value;?>

    </div>

    <?php if ($_smarty_tpl->tpl_vars['include_js']->value){?>
        <?php smarty_template_function_include_form_js($_smarty_tpl,array());?>

    <?php }?>

<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>






<?php smarty_template_function_include_assets($_smarty_tpl,array());?>

<?php smarty_template_function_wrap($_smarty_tpl,array());?>



<?php }} ?>