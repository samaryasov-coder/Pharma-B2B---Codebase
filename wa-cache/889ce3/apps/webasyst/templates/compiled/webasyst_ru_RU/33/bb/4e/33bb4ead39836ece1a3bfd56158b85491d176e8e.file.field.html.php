<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:07
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/field.html" */ ?>
<?php /*%%SmartyHeaderCode:2475063166a88a073e7bdf0-02083527%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33bb4ead39836ece1a3bfd56158b85491d176e8e' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/login/templates/login/backend/field.html',
      1 => 1736497959,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2475063166a88a073e7bdf0-02083527',
  'function' => 
  array (
    'render_errors' => 
    array (
      'parameter' => 
      array (
        'errors' => 
        array (
        ),
      ),
      'compiled' => '',
    ),
    'render_wrapped' => 
    array (
      'parameter' => 
      array (
        'field_id' => '',
        'caption' => '',
        'html' => '',
        'class' => '',
        'style' => '',
      ),
      'compiled' => '',
    ),
    'render_field_html' => 
    array (
      'parameter' => 
      array (
        'field' => 
        array (
        ),
        'params' => 
        array (
        ),
      ),
      'compiled' => '',
    ),
    'render_field' => 
    array (
      'parameter' => 
      array (
        'field' => 
        array (
        ),
        'field_wrap_params' => 
        array (
        ),
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'errors' => 0,
    'error' => 0,
    'caption' => 0,
    'field_id' => 0,
    'class' => 0,
    'style' => 0,
    'html' => 0,
    'params' => 0,
    'clz' => 0,
    'classes' => 0,
    '_placeholder' => 0,
    'attrs' => 0,
    'field' => 0,
    'is_onetime_password_auth_type' => 0,
    '_field_id' => 0,
    'onetime_password_url' => 0,
    '_html' => 0,
    '_link_html_password' => 0,
    '_link_html_login' => 0,
    'is_hidden' => 0,
    '_params' => 0,
    '_style' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a88a073e98b10_36719994',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a88a073e98b10_36719994')) {function content_6a88a073e98b10_36719994($_smarty_tpl) {?>

<?php if (!function_exists('smarty_template_function_render_errors')) {
    function smarty_template_function_render_errors($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['render_errors']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php  $_smarty_tpl->tpl_vars['error'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['error']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['errors']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['error']->key => $_smarty_tpl->tpl_vars['error']->value){
$_smarty_tpl->tpl_vars['error']->_loop = true;
?>
        <em class="wa-error-msg"><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</em>
    <?php } ?>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php if (!function_exists('smarty_template_function_render_wrapped')) {
    function smarty_template_function_render_wrapped($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['render_wrapped']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php $_smarty_tpl->tpl_vars['caption'] = new Smarty_variable(htmlspecialchars((string)$_smarty_tpl->tpl_vars['caption']->value, ENT_QUOTES, 'UTF-8', true), null, 0);?>
    <div class="field field-<?php echo $_smarty_tpl->tpl_vars['field_id']->value;?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['class']->value)===null||$tmp==='' ? '' : $tmp);?>
" data-field-id="<?php echo $_smarty_tpl->tpl_vars['field_id']->value;?>
" style="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['style']->value)===null||$tmp==='' ? '' : $tmp);?>
">
        <div class="name">
            <?php echo $_smarty_tpl->tpl_vars['caption']->value;?>

        </div>
        <div class="value">
            <?php echo $_smarty_tpl->tpl_vars['html']->value;?>

            <?php smarty_template_function_render_errors($_smarty_tpl,array('errors'=>$_smarty_tpl->tpl_vars['errors']->value));?>

        </div>
    </div>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php if (!function_exists('smarty_template_function_render_field_html')) {
    function smarty_template_function_render_field_html($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['render_field_html']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>

    <?php $_smarty_tpl->tpl_vars['attrs'] = new Smarty_variable(array(), null, 0);?>

    <?php $_smarty_tpl->tpl_vars['classes'] = new Smarty_variable(array('wa-login-text-input','larger'), null, 0);?>
    <?php  $_smarty_tpl->tpl_vars['clz'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['clz']->_loop = false;
 $_from = (($tmp = @$_smarty_tpl->tpl_vars['params']->value['classes'])===null||$tmp==='' ? array() : $tmp); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['clz']->key => $_smarty_tpl->tpl_vars['clz']->value){
$_smarty_tpl->tpl_vars['clz']->_loop = true;
?>
        <?php $_smarty_tpl->createLocalArrayVariable('classes', null, 0);
$_smarty_tpl->tpl_vars['classes']->value[] = htmlspecialchars((string)$_smarty_tpl->tpl_vars['clz']->value, ENT_QUOTES, 'UTF-8', true);?>
    <?php } ?>
    <?php if ($_smarty_tpl->tpl_vars['errors']->value){?>
        <?php $_smarty_tpl->createLocalArrayVariable('classes', null, 0);
$_smarty_tpl->tpl_vars['classes']->value[] = 'wa-error';?>
    <?php }?>
    <?php $_smarty_tpl->tpl_vars['classes'] = new Smarty_variable(join(' ',$_smarty_tpl->tpl_vars['classes']->value), null, 0);?>

    <?php $_smarty_tpl->createLocalArrayVariable('attrs', null, 0);
$_smarty_tpl->tpl_vars['attrs']->value[] = "class=\"".((string)$_smarty_tpl->tpl_vars['classes']->value)."\"";?>

    <?php $_smarty_tpl->tpl_vars['_placeholder'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['params']->value['placeholder'])===null||$tmp==='' ? '' : $tmp), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['_placeholder'] = new Smarty_variable(htmlspecialchars((string)$_smarty_tpl->tpl_vars['_placeholder']->value, ENT_QUOTES, 'UTF-8', true), null, 0);?>

    <?php if (strlen($_smarty_tpl->tpl_vars['_placeholder']->value)>0){?>
        <?php $_smarty_tpl->createLocalArrayVariable('attrs', null, 0);
$_smarty_tpl->tpl_vars['attrs']->value[] = "placeholder=\"".((string)$_smarty_tpl->tpl_vars['_placeholder']->value)."\"";?>
    <?php }?>

    <?php if ((($tmp = @$_smarty_tpl->tpl_vars['params']->value['disabled'])===null||$tmp==='' ? false : $tmp)){?>
        <?php $_smarty_tpl->createLocalArrayVariable('attrs', null, 0);
$_smarty_tpl->tpl_vars['attrs']->value[] = 'disabled="disabled"';?>
    <?php }?>
    <?php $_smarty_tpl->tpl_vars['attrs'] = new Smarty_variable(join(' ',$_smarty_tpl->tpl_vars['attrs']->value), null, 0);?>

    <?php echo $_smarty_tpl->tpl_vars['field']->value->getHtml($_smarty_tpl->tpl_vars['params']->value,$_smarty_tpl->tpl_vars['attrs']->value);?>

<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>



<?php if (!function_exists('smarty_template_function_render_field')) {
    function smarty_template_function_render_field($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['render_field']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?><?php $_smarty_tpl->tpl_vars['_field_id'] = new Smarty_variable($_smarty_tpl->tpl_vars['field']->value->getId(), null, 0);?><?php $_smarty_tpl->tpl_vars['_params'] = new Smarty_variable($_smarty_tpl->tpl_vars['params']->value, null, 0);?><?php $_smarty_tpl->_capture_stack[0][] = array('default', "_html", null); ob_start(); ?><?php smarty_template_function_render_field_html($_smarty_tpl,array('field'=>$_smarty_tpl->tpl_vars['field']->value,'params'=>$_smarty_tpl->tpl_vars['params']->value));?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable('', null, 0);?><?php if ($_smarty_tpl->tpl_vars['is_onetime_password_auth_type']->value){?><?php if ($_smarty_tpl->tpl_vars['_field_id']->value==='password'){?><?php $_smarty_tpl->tpl_vars['_style'] = new Smarty_variable('display: none;', null, 0);?><?php $_smarty_tpl->_capture_stack[0][] = array('default', "_link_html_password", null); ob_start(); ?><div class="wa-request-onetime-password-link-wrapper" style="display: none;"><a href="<?php echo $_smarty_tpl->tpl_vars['onetime_password_url']->value;?>
" class="wa-request-onetime-password-link">Выслать еще раз</a><i class="icon16 loading wa-request-onetime-password-link-loading" style="margin-top: 0px; margin-left: 2px; display: none"></i></div><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php $_smarty_tpl->tpl_vars['_html'] = new Smarty_variable(($_smarty_tpl->tpl_vars['_html']->value).($_smarty_tpl->tpl_vars['_link_html_password']->value), null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['_field_id']->value==='login'){?><?php $_smarty_tpl->_capture_stack[0][] = array('default', "_link_html_login", null); ob_start(); ?><a class="wa-change-login-link" href="javascript:void(0);" style="display: none;">Изменить</a><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php $_smarty_tpl->tpl_vars['_html'] = new Smarty_variable(($_smarty_tpl->tpl_vars['_html']->value).($_smarty_tpl->tpl_vars['_link_html_login']->value), null, 0);?><?php }?><?php }?><?php if (!$_smarty_tpl->tpl_vars['is_hidden']->value){?><?php smarty_template_function_render_wrapped($_smarty_tpl,array('field_id'=>$_smarty_tpl->tpl_vars['_field_id']->value,'caption'=>(($tmp = @$_smarty_tpl->tpl_vars['_params']->value['caption'])===null||$tmp==='' ? '' : $tmp),'html'=>$_smarty_tpl->tpl_vars['_html']->value,'ext'=>(($tmp = @$_smarty_tpl->tpl_vars['_params']->value['ext'])===null||$tmp==='' ? '' : $tmp),'style'=>$_smarty_tpl->tpl_vars['_style']->value));?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['_html']->value;?>
<?php }?><?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>






<?php smarty_template_function_render_field($_smarty_tpl,array('field'=>$_smarty_tpl->tpl_vars['field']->value));?>



<?php }} ?>