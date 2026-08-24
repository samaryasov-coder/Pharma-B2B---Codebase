<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:35
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/design/templates/Theme.html" */ ?>
<?php /*%%SmartyHeaderCode:9489234616a8c828be74550-70822046%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e92dc7080ddae958dcf02efc667a3b5d4813f0b5' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/design/templates/Theme.html',
      1 => 1779891735,
      2 => 'file',
    ),
    '1b34cb762729dbdea3854c95e145628d342c9888' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/design/templates/ThemeDialogs.inc.html',
      1 => 1765875363,
      2 => 'file',
    ),
    'fb9e1844c12874685a562fe1163ef74796f74bb3' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/design/templates/Bottombar.inc.html',
      1 => 1687776337,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9489234616a8c828be74550-70822046',
  'function' => 
  array (
    '_renderThemeSetting' => 
    array (
      'parameter' => 
      array (
        '_setting_var' => '_setting_var',
        '_setting' => 
        array (
        ),
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'theme' => 0,
    'has_theme_usage_any_app' => 0,
    'fallback_has_theme_usage' => 0,
    'has_theme_usage' => 0,
    'wa_url' => 0,
    'wa' => 0,
    '_setting' => 0,
    '_global_divider' => 0,
    '_setting_var' => 0,
    '_h_level' => 0,
    '_var' => 0,
    '_item' => 0,
    '_field_name' => 0,
    'o' => 0,
    'v' => 0,
    '_mark_class' => 0,
    'k' => 0,
    '_url' => 0,
    'id' => 0,
    'show_theme_start_using' => 0,
    'theme_routes' => 0,
    'theme_warning_requirements' => 0,
    'theme_original_warning_requirements' => 0,
    'theme_parent_warning_requirements' => 0,
    '_is_trial' => 0,
    'preview_url' => 0,
    'settings' => 0,
    '_reset_is_disabled' => 0,
    '_reset_disabled_alert' => 0,
    'global_group_divideres' => 0,
    'support' => 0,
    'instruction' => 0,
    'wa_backend_url' => 0,
    '_is_single_app_mode' => 0,
    '_buy_link' => 0,
    'requirement' => 0,
    'theme_original_version' => 0,
    'theme_parent_original_version' => 0,
    'cover' => 0,
    't' => 0,
    '_r' => 0,
    's_var' => 0,
    'setting' => 0,
    'r' => 0,
    'current_route' => 0,
    'theme_route' => 0,
    'design_url' => 0,
    '_divider_id' => 0,
    '_divider' => 0,
    'wa_static_url' => 0,
    'child_themes' => 0,
    '_locale' => 0,
    'current_domain' => 0,
    'settlements_by_domain' => 0,
    '_theme_row' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c828c034978_65658926',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c828c034978_65658926')) {function content_6a8c828c034978_65658926($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_regex_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.regex_replace.php';
if (!is_callable('smarty_modifier_wa_datetime')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.wa_datetime.php';
if (!is_callable('smarty_modifier_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.replace.php';
?><?php $_smarty_tpl->tpl_vars['_is_trial'] = new Smarty_variable($_smarty_tpl->tpl_vars['theme']->value['type']===waTheme::TRIAL, null, 0);?>
<?php $_smarty_tpl->tpl_vars['has_theme_usage_any_app'] = new Smarty_variable($_smarty_tpl->tpl_vars['has_theme_usage_any_app']->value||!empty($_smarty_tpl->tpl_vars['fallback_has_theme_usage']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['has_theme_usage'] = new Smarty_variable($_smarty_tpl->tpl_vars['has_theme_usage']->value||!empty($_smarty_tpl->tpl_vars['fallback_has_theme_usage']->value), null, 0);?>

<script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/design/theme.settings.js?v<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"></script>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/css/wa/design/theme.settings.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
">
<?php if (!is_callable('smarty_function_html_options')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/function.html_options.php';
?><?php if (!function_exists('smarty_template_function__renderThemeSetting')) {
    function smarty_template_function__renderThemeSetting($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['_renderThemeSetting']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <?php $_smarty_tpl->tpl_vars['_h_level'] = new Smarty_variable($_smarty_tpl->tpl_vars['_setting']->value['level']+2, null, 0);?><?php if ($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='group_divider'){?><?php $_smarty_tpl->tpl_vars['_global_divider'] = new Smarty_variable($_smarty_tpl->tpl_vars['_setting']->value['level']==1, null, 0);?><?php $_smarty_tpl->tpl_vars['_not_empty_global_divider'] = new Smarty_variable($_smarty_tpl->tpl_vars['_global_divider']->value&&!empty($_smarty_tpl->tpl_vars['_setting']->value['items']), null, 0);?><div class="fields-group<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['invisible'])){?> invisible-setting<?php }?>"<?php if ($_smarty_tpl->tpl_vars['_global_divider']->value){?> data-divider-id="<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
"<?php }?>><h<?php echo $_smarty_tpl->tpl_vars['_h_level']->value;?>
 class="wa-theme-setting-divider-name js-divider-name js-search-item"data-name="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_setting']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
"data-divider-level="<?php echo $_smarty_tpl->tpl_vars['_setting']->value['level'];?>
"data-search="<?php echo strip_tags($_smarty_tpl->tpl_vars['_setting']->value['name']);?>
"><span class="<?php if ($_smarty_tpl->tpl_vars['_setting']->value['var']===waTheme::OBSOLETE_SETTINGS_DIVIDER){?>gray <?php }?>js-search-item-name"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['name'];?>
</span></h<?php echo $_smarty_tpl->tpl_vars['_h_level']->value;?>
><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['tooltip'])){?><span class="custom-ml-4" data-wa-tooltip-content="<?php echo $_smarty_tpl->tpl_vars['_setting']->value['tooltip'];?>
"><i class="fas fa-info-circle fa-xs divider-tooltip-<?php echo $_smarty_tpl->tpl_vars['_setting']->value['level'];?>
"></i></span><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['items'])){?><div class="wa-theme-settings-group js-settings-group custom-ml-0" data-divider-level="<?php echo $_smarty_tpl->tpl_vars['_setting']->value['level'];?>
"><?php  $_smarty_tpl->tpl_vars['_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_item']->_loop = false;
 $_smarty_tpl->tpl_vars['_var'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_setting']->value['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_item']->key => $_smarty_tpl->tpl_vars['_item']->value){
$_smarty_tpl->tpl_vars['_item']->_loop = true;
 $_smarty_tpl->tpl_vars['_var']->value = $_smarty_tpl->tpl_vars['_item']->key;
?><?php smarty_template_function__renderThemeSetting($_smarty_tpl,array('_setting_var'=>$_smarty_tpl->tpl_vars['_var']->value,'_setting'=>$_smarty_tpl->tpl_vars['_item']->value));?>
<?php } ?></div><?php }?></div><?php }elseif($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='paragraph'){?><div class="js-search-item" data-search=""><div class="wa-theme-paragraph hint"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['name'];?>
</div></div><?php }else{ ?><div class="field<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['invisible'])){?> invisible-setting<?php }?> js-search-item"data-name="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_setting']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
"data-search="<?php echo strip_tags($_smarty_tpl->tpl_vars['_setting']->value['name']);?>
"><div class="name"><?php if ($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='checkbox'){?><?php ob_start();?><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?><?php echo "parent_";?><?php }?><?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['_field_name'] = new Smarty_variable($_tmp1."settings[".((string)$_smarty_tpl->tpl_vars['_setting_var']->value)."]", null, 0);?><label class="js-search-item-name" for="<?php echo $_smarty_tpl->tpl_vars['_field_name']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['name'];?>
</label><?php }else{ ?><span class="js-search-item-name"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['name'];?>
</span><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['tooltip'])){?><span class="custom-ml-4" data-wa-tooltip-content="<?php echo $_smarty_tpl->tpl_vars['_setting']->value['tooltip'];?>
"><i class="fas fa-info-circle fa-xs"></i></span><?php }?></div><div class="value"><?php if ($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='select'){?><div class="wa-select small"><select name="<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?>parent_<?php }?>settings[<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
]"><?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['_setting']->value['options'],'selected'=>ifset($_smarty_tpl->tpl_vars['_setting']->value['value'])),$_smarty_tpl);?>
</select></div><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['description'])){?><div class="hint"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['description'];?>
</div><?php }?><?php }elseif($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='radio'){?><?php  $_smarty_tpl->tpl_vars['o'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['o']->_loop = false;
 $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_setting']->value['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['o']->key => $_smarty_tpl->tpl_vars['o']->value){
$_smarty_tpl->tpl_vars['o']->_loop = true;
 $_smarty_tpl->tpl_vars['v']->value = $_smarty_tpl->tpl_vars['o']->key;
?><label class="custom-mb-16<?php if (empty($_smarty_tpl->tpl_vars['o']->value['description'])){?> custom-mr-16<?php }?>"><span class="wa-radio"><input <?php if (ifset($_smarty_tpl->tpl_vars['_setting']->value['value'])==$_smarty_tpl->tpl_vars['v']->value){?>checked<?php }?> type="radio" value="<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
" name="<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?>parent_<?php }?>settings[<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
]" ><span></span></span><?php echo $_smarty_tpl->tpl_vars['o']->value['name'];?>
<?php if (!empty($_smarty_tpl->tpl_vars['o']->value['description'])){?><p class="hint custom-mb-12"><?php echo $_smarty_tpl->tpl_vars['o']->value['description'];?>
</p><?php }?></label><?php } ?><?php }elseif($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='color'||$_smarty_tpl->tpl_vars['_setting']->value['control_type']=='color_select'){?><?php if ($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='color_select'){?><?php $_smarty_tpl->tpl_vars['_mark_class'] = new Smarty_variable('', null, 0);?><?php if (strpos($_smarty_tpl->tpl_vars['_setting']->value['var'],'light')!==false){?><?php $_smarty_tpl->tpl_vars['_mark_class'] = new Smarty_variable(' light-mark', null, 0);?><?php }?><?php if (strpos($_smarty_tpl->tpl_vars['_setting']->value['var'],'dark')!==false){?><?php $_smarty_tpl->tpl_vars['_mark_class'] = new Smarty_variable(' dark-mark', null, 0);?><?php }?><ul class="wa-theme-color-select js-theme-color-select custom-m-0 custom-p-16 flexbox wrap space-16 middle<?php echo $_smarty_tpl->tpl_vars['_mark_class']->value;?>
"><?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_setting']->value['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?><li class="custom-m-0<?php if ($_smarty_tpl->tpl_vars['_setting']->value['value']==$_smarty_tpl->tpl_vars['k']->value){?> selected<?php }?>" data-value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" title="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['v']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['k']->value : $tmp), ENT_QUOTES, 'UTF-8', true);?>
" style="--wa-theme-color:<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
;"></li><?php } ?><li class="custom-m-0<?php if (!in_array($_smarty_tpl->tpl_vars['_setting']->value['value'],array_keys($_smarty_tpl->tpl_vars['_setting']->value['options']))){?> selected<?php }?>" data-value="<?php echo $_smarty_tpl->tpl_vars['_setting']->value['value'];?>
" data-picker><i class="fas fa-eye-dropper"></i></li></ul><div class="color-picker"<?php if (in_array($_smarty_tpl->tpl_vars['_setting']->value['value'],array_keys($_smarty_tpl->tpl_vars['_setting']->value['options']))){?> style="display: none;"<?php }?>><input class="color small" type="text" name="<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?>parent_<?php }?>settings[<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['_setting']->value['value'];?>
"></div><?php }?><?php if ($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='color'){?><input class="color small" type="text" name="<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?>parent_<?php }?>settings[<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['_setting']->value['value'];?>
"><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['description'])){?><div class="hint"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['description'];?>
</div><?php }?><?php }elseif($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='checkbox'){?><?php ob_start();?><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?><?php echo "parent_";?><?php }?><?php $_tmp2=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['_field_name'] = new Smarty_variable($_tmp2."settings[".((string)$_smarty_tpl->tpl_vars['_setting_var']->value)."]", null, 0);?><input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['_field_name']->value;?>
" value=""><label for="<?php echo $_smarty_tpl->tpl_vars['_field_name']->value;?>
"><span class="wa-checkbox"><input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['_field_name']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['_field_name']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['_setting']->value['value']){?>checked<?php }?> value="1"><span><span class="icon"><i class="fas fa-check"></i></span></span></span></label><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['description'])){?><div class="hint"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['description'];?>
</div><?php }?><?php }elseif($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='image_select'){?><ul class="wa-theme-image-select thumbs"><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?><?php $_smarty_tpl->tpl_vars['_url'] = new Smarty_variable($_smarty_tpl->tpl_vars['theme']->value['parent_theme']->getUrl(), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_url'] = new Smarty_variable($_smarty_tpl->tpl_vars['theme']->value->getUrl(), null, 0);?><?php }?><?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_setting']->value['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?><li<?php if ($_smarty_tpl->tpl_vars['_setting']->value['value']==$_smarty_tpl->tpl_vars['k']->value){?> class="selected"<?php }?> data-value="<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"><a href="#" class="transparent-sprite"><img src="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
"></a></li><?php } ?></ul><input type="hidden" name="<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?>parent_<?php }?>settings[<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
]" value="<?php echo $_smarty_tpl->tpl_vars['_setting']->value['value'];?>
"><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['description'])){?><div class="hint"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['description'];?>
</div><?php }?><?php }elseif($_smarty_tpl->tpl_vars['_setting']->value['control_type']=='image'){?><input type="hidden" name="<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?>parent_<?php }?>settings[<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
]" value="<?php echo ifset($_smarty_tpl->tpl_vars['_setting']->value['value']);?>
"><div class="upload-control"><div class="upload"><label class="link"><i class="fas fa-file-upload"></i><span>Выберите файл</span><input type="file" name="<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?>parent_<?php }?>image[<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
]" autocomplete="off"></label></div></div><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['value'])){?><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?><?php $_smarty_tpl->tpl_vars['_url'] = new Smarty_variable($_smarty_tpl->tpl_vars['theme']->value['parent_theme']->getUrl(), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_url'] = new Smarty_variable($_smarty_tpl->tpl_vars['theme']->value->getUrl(), null, 0);?><?php }?><div class="image"><br><img class="transparent-sprite" src="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['_setting']->value['value'];?>
"><br><a class="small delete-image text-red" href="#"><i class="fas fa-trash-alt"></i> Удалить</a></div><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['description'])){?><div class="hint"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['description'];?>
</div><?php }?><?php }else{ ?><div><?php if (!$_smarty_tpl->tpl_vars['_setting']->value['value']||strlen($_smarty_tpl->tpl_vars['_setting']->value['value'])<=50){?><input class="longer flexible small" id="flex-settings-<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
" type="text" name="<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?>parent_<?php }?>settings[<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
]" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_setting']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
"><?php }else{ ?><textarea class="width-80 width-100-mobile flexible small" id="flex-settings-<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
" name="<?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['parent'])){?>parent_<?php }?>settings[<?php echo $_smarty_tpl->tpl_vars['_setting_var']->value;?>
]"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_setting']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
</textarea><?php }?></div><?php if (!empty($_smarty_tpl->tpl_vars['_setting']->value['description'])){?><div class="hint"><?php echo $_smarty_tpl->tpl_vars['_setting']->value['description'];?>
</div><?php }?><?php }?></div></div><?php }?>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php $_smarty_tpl->tpl_vars['id'] = new Smarty_variable(uniqid("wa-theme-id".((string)$_smarty_tpl->tpl_vars['theme']->value['id'])), null, 0);?>
<div class="wa-theme article wider" id="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
">
    <div class="article-body">

        
        <h1 class="wa-theme-name wide custom-mb-16">
            <?php echo sprintf('Тема дизайна «%s»',htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true));?>

            <span class="hint">
                <?php echo $_smarty_tpl->tpl_vars['theme']->value['version'];?>

            </span>
        </h1>

        <?php if (empty($_smarty_tpl->tpl_vars['show_theme_start_using']->value)){?>
            <?php $_smarty_tpl->tpl_vars['show_theme_start_using'] = new Smarty_variable(!$_smarty_tpl->tpl_vars['theme_routes']->value&&empty($_smarty_tpl->tpl_vars['theme_warning_requirements']->value)&&empty($_smarty_tpl->tpl_vars['theme_original_warning_requirements']->value)&&empty($_smarty_tpl->tpl_vars['theme_parent_warning_requirements']->value), null, 0);?>
        <?php }?>
        <div class="wa-theme-name-section custom-mb-24">

            <?php if ($_smarty_tpl->tpl_vars['has_theme_usage']->value){?>
                <a id="theme-start-using" href="#" class="js-theme-start-using button green">Где используется</a>
            <?php }elseif(!empty($_smarty_tpl->tpl_vars['show_theme_start_using']->value)&&!$_smarty_tpl->tpl_vars['_is_trial']->value){?>
                <a id="theme-start-using" href="#" class="js-theme-start-using button green">Начать использовать</a>
            <?php }?>
            <?php if (!empty($_smarty_tpl->tpl_vars['preview_url']->value)){?>
                <a class="wa-theme-preview button dark-gray nowrap" style="display:none;" data-theme-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
" rel="noopener" target="_blank" data-href="<?php echo smarty_modifier_regex_replace($_smarty_tpl->tpl_vars['preview_url']->value,'/http:|https:/','');?>
" href="<?php echo $_smarty_tpl->tpl_vars['preview_url']->value;?>
">
                    <?php if ($_smarty_tpl->tpl_vars['has_theme_usage']->value){?>Смотреть на сайте<?php }else{ ?>Предпросмотр<?php }?>
                    <i class="fas fa-external-link-alt fa-xs custom-ml-4"></i>
                </a>
            <?php }?>

            
            <div class="dropdown js-theme-actions-dropdown custom-mr-8">
                <button class="dropdown-toggle button outlined light-1 <?php if ($_smarty_tpl->tpl_vars['wa']->value->isMobile()){?> full-width<?php }?>" type="button">Действия</button>
                <div class="dropdown-body dd-long dd-wide">
                    <ul class="menu">
                        <?php if (!$_smarty_tpl->tpl_vars['_is_trial']->value){?>
                            <?php if (count($_smarty_tpl->tpl_vars['theme']->value->related_themes)>1){?>
                                <li>
                                    <a class="theme-download js-theme-download" href="#">
                                        <i class="fas fa-save text-blue"></i>
                                        <span>Скачать архив с темой дизайна</span>
                                    </a>
                                </li>
                            <?php }else{ ?>
                                <li>
                                    <a href="?module=design&amp;action=themeDownload&amp;theme=<?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
" download>
                                        <i class="fas fa-file-download"></i>
                                        <span>Скачать архив с темой дизайна <span class="hint nowrap">.tar.gz</span></span>
                                    </a>
                                </li>
                            <?php }?>
                            <li>
                                <a class="theme-copy js-theme-copy" href="#" data-related="<?php if (count($_smarty_tpl->tpl_vars['theme']->value->related_themes)>1){?>1<?php }else{ ?>0<?php }?>">
                                    <i class="fas fa-copy"></i>
                                    <span>Создать клон темы</span>
                                </a>
                            </li>
                            <li>
                                <a class="theme-rename js-theme-rename" href="#">
                                    <i class="fas fa-pen"></i>
                                    <span>Переименовать тему</span>
                                </a>
                            </li>
                            <li class="gray">
                                <a class="js-theme-parent" href="javascript:void(0);">
                                    <i class="fas fa-link"></i>
                                    <span>Родительская тема дизайна: <strong><?php if ($_smarty_tpl->tpl_vars['theme']->value['parent_theme_id']){?><?php echo $_smarty_tpl->tpl_vars['theme']->value['parent_theme_id'];?>
<?php }else{ ?>не выбрана<?php }?></strong></span>
                                </a>
                            </li>
                            <?php if (!empty($_smarty_tpl->tpl_vars['settings']->value['items'])){?>
                                
                                <li class="top-padded">
                                    <a class="js-export-theme-settings" href="?module=design&amp;action=themeExportSettings&amp;theme=<?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
" download>
                                        <i class="fas fa-share text-green"></i>
                                        <span>Экспорт настроек темы
                                            <span class="hidden js-export-error hint flexbox">
                                                <span class="js-export-error-caption text-red"></span>
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                
                                <li class="bottom-padded bottom-padded">
                                    <a class="js-import-theme-settings" href="#">
                                        <i class="fas fa-file-import text-green"></i>
                                        <span>Импорт настроек темы</span>
                                    </a>
                                </li>
                            <?php }?>

                            <?php if (empty($_smarty_tpl->tpl_vars['theme_warning_requirements']->value)&&empty($_smarty_tpl->tpl_vars['theme_original_warning_requirements']->value)){?>
                                <?php $_smarty_tpl->tpl_vars['_reset_is_disabled'] = new Smarty_variable(false, null, 0);?>
                                <?php $_smarty_tpl->tpl_vars['_reset_disabled_alert'] = new Smarty_variable(null, null, 0);?>

                                <?php if ($_smarty_tpl->tpl_vars['theme']->value['type']!=waTheme::OVERRIDDEN){?>
                                    <?php $_smarty_tpl->tpl_vars['_reset_is_disabled'] = new Smarty_variable(true, null, 0);?>
                                    <?php $_smarty_tpl->tpl_vars['_reset_disabled_alert'] = new Smarty_variable(_ws('You did not apply customizations to this theme yet, and thus there is nothing to revert.'), null, 0);?>
                                <?php }?>

                                <?php if (!$_smarty_tpl->tpl_vars['theme']->value['path_original']&&$_smarty_tpl->tpl_vars['theme']->value['type']==waTheme::CUSTOM){?>
                                    <?php $_smarty_tpl->tpl_vars['_reset_is_disabled'] = new Smarty_variable(true, null, 0);?>
                                    <?php $_smarty_tpl->tpl_vars['_reset_disabled_alert'] = new Smarty_variable(_ws('Design theme was not installed from Webasyst Store.'), null, 0);?>
                                <?php }?>

                                <li>
                                    <a class="theme-reset js-theme-reset<?php if ($_smarty_tpl->tpl_vars['_reset_is_disabled']->value){?> disabled<?php }?>" href="#" title="Все изменения, которые вы вносили в тему дизайна, будут потеряны. Сбросить все изменения?"<?php if ($_smarty_tpl->tpl_vars['_reset_disabled_alert']->value){?> data-disabled-alert="<?php echo $_smarty_tpl->tpl_vars['_reset_disabled_alert']->value;?>
"<?php }?>>
                                        <i class="fas fa-broom text-orange"></i>
                                        <span>Сбросить все изменения</span>
                                    </a>
                                </li>
                            <?php }?>

                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['theme']->value['system']){?>
                            <li class="disabled">
                                <div class="item">
                                    <i class="fas fa-trash-alt text-red"></i>
                                    <span>
                                    Удалить тему
                                    <span class="hint flexbox">Эта тема дизайна не может быть удалена</span>
                                </span>
                                </div>
                            </li>
                        <?php }else{ ?>
                            <li>
                                <a class="js-theme-delete" href="#" data-confirm="Тема будет удалена без возможности восстановления.">
                                    <i class="fas fa-trash-alt text-red"></i>
                                    <span>
                                        Удалить тему
                                    </span>
                                </a>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            </div>

            <?php if (($_smarty_tpl->tpl_vars['has_theme_usage_any_app']->value||$_smarty_tpl->tpl_vars['_is_trial']->value)&&!empty($_smarty_tpl->tpl_vars['settings']->value['items'])&&!empty($_smarty_tpl->tpl_vars['global_group_divideres']->value)){?>
                
                <div class="search-setting-wrapper">
                    <div class="state-with-inner-icon left width-100">
                        <span class="icon"><i class="fas fa-search"></i></span>
                        <input type="search" class="js-search-setting width-100" autocomplete="off" placeholder="Найти настройку" />
                    </div>
                </div>
            <?php }?>

            
            <?php if (!empty($_smarty_tpl->tpl_vars['support']->value)||!empty($_smarty_tpl->tpl_vars['instruction']->value)){?>
                <div class="wa-theme-help">
                    <?php if (!empty($_smarty_tpl->tpl_vars['instruction']->value)){?>
                        <a class="button nobutton small nowrap" href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['instruction']->value, ENT_QUOTES, 'UTF-8', true);?>
" rel="noopener" target="_blank">Инструкция <i class="fas fa-external-link-alt fa-xs"></i></a>
                    <?php }?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['support']->value)){?>
                        <a class="button nobutton small nowrap" href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['support']->value, ENT_QUOTES, 'UTF-8', true);?>
" rel="noopener" target="_blank">Поддержка <i class="fas fa-external-link-alt fa-xs"></i></a>
                    <?php }?>
                </div>
            <?php }?>

        </div>

        <div class="wa-theme-info-section js-theme-info-section">
            
            <div class="wa-theme-actions js-theme-actions custom-mb-16">
                <?php if ($_smarty_tpl->tpl_vars['theme']->value['type']==waTheme::TRIAL){?>

                    
                    <div class="box shadowed custom-mb-24 custom-py-24 blank rounded wa-theme-trial align-center">

                        <h4><i class="fas fa-clock text-green"></i> <em><?php echo _ws('Theme status — trial');?>
</em></h4>

                        <?php if (!empty($_smarty_tpl->tpl_vars['preview_url']->value)){?>
                            <a class="wa-theme-preview button rounded light-gray" data-theme-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
" rel="noopener" target="_blank" data-href="<?php echo smarty_modifier_regex_replace($_smarty_tpl->tpl_vars['preview_url']->value,'/http:|https:/','');?>
" href="<?php echo $_smarty_tpl->tpl_vars['preview_url']->value;?>
">Открыть предпросмотр на сайте <i class="fas fa-external-link-alt fa-xs opacity-50"></i></a>
                        <?php }?>

                        <?php if ($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin('installer')){?>
                            <?php ob_start();?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp3=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['_buy_link'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_backend_url']->value)."installer/store/theme/".$_tmp3."/", null, 0);?>
                            <?php $_smarty_tpl->tpl_vars['_is_single_app_mode'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->isSingleAppMode(), null, 0);?>
                            <?php if ($_smarty_tpl->tpl_vars['_is_single_app_mode']->value){?>
                                <?php $_smarty_tpl->tpl_vars['_buy_link'] = new Smarty_variable((htmlspecialchars((string)(($_smarty_tpl->tpl_vars['wa']->value->locale()=='ru_RU' ? 'https://www.webasyst.ru/store/theme/' : 'https://www.webasyst.com/store/theme/')).($_smarty_tpl->tpl_vars['theme']->value['id']), ENT_QUOTES, 'UTF-8', true)).('/'), null, 0);?>
                            <?php }?>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['_buy_link']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_is_single_app_mode']->value){?> target="_blank"<?php }?> class="button green rounded">Купить тему</a>
                        <?php }?>

                        <p class="hint">
                            Пробные темы дизайна доступны только для предпросмотра. Купите тему, чтобы начать использовать ее на рабочей версии сайта для всех пользователей.
                        </p>
                    </div>

                <?php }elseif($_smarty_tpl->tpl_vars['theme']->value['type']==waTheme::ORIGINAL){?>

                    <?php if ($_smarty_tpl->tpl_vars['has_theme_usage_any_app']->value&&!empty($_smarty_tpl->tpl_vars['theme_warning_requirements']->value)){?>
                        <div class="alert warning">
                            <ul class="menu">
                                <?php  $_smarty_tpl->tpl_vars['requirement'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['requirement']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme_warning_requirements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['requirement']->key => $_smarty_tpl->tpl_vars['requirement']->value){
$_smarty_tpl->tpl_vars['requirement']->_loop = true;
?>
                                    <li><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['requirement']->value['warning'], ENT_QUOTES, 'UTF-8', true);?>
</li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php }?>

                    
                    <div class="alert success">
                        <i class="fas fa-check"></i>
                        <?php echo sprintf('Оригинальная версия <strong>%s</strong>',$_smarty_tpl->tpl_vars['theme']->value['version']);?>

                        <p class="hint custom-mt-8">Вы не вносили изменения в шаблоны дизайна и настройки этой темы дизайна.</p>
                    </div>

                <?php }elseif($_smarty_tpl->tpl_vars['theme']->value['type']==waTheme::OVERRIDDEN){?>

                    <?php if ($_smarty_tpl->tpl_vars['theme']->value['version']==$_smarty_tpl->tpl_vars['theme_original_version']->value){?>
                        <?php if (!$_smarty_tpl->tpl_vars['theme']->value['parent_theme']||$_smarty_tpl->tpl_vars['theme']->value['parent_theme']['type']==waTheme::ORIGINAL||$_smarty_tpl->tpl_vars['theme']->value['parent_theme']['version']==$_smarty_tpl->tpl_vars['theme_parent_original_version']->value){?>
                            
                            <div class="alert success">
                                <i class="fas fa-check bold"></i> <b><?php echo sprintf('Последняя версия <strong>%s</strong>',$_smarty_tpl->tpl_vars['theme']->value['version']);?>
</b>
                                <p class="custom-mt-8 hint"><?php echo sprintf('Установлена последняя версия темы дизайна %s.',htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true));?>
</p>
                            </div>
                        <?php }else{ ?>
                            
                            <?php if (!empty($_smarty_tpl->tpl_vars['theme_parent_warning_requirements']->value)){?>
                                <div class="alert warning">
                                    <strong>Обновление недоступно</strong>
                                    <ul class="menu">
                                        <?php  $_smarty_tpl->tpl_vars['requirement'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['requirement']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme']->value['parent_theme']->getWarningRequirements(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['requirement']->key => $_smarty_tpl->tpl_vars['requirement']->value){
$_smarty_tpl->tpl_vars['requirement']->_loop = true;
?>
                                            <li>
                                                <?php echo $_smarty_tpl->tpl_vars['requirement']->value['warning'];?>

                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>

                                
                            <?php }elseif($_smarty_tpl->tpl_vars['theme']->value['parent_theme']&&$_smarty_tpl->tpl_vars['theme']->value['parent_theme']['type']==waTheme::OVERRIDDEN&&$_smarty_tpl->tpl_vars['theme']->value['parent_theme']['version']!=$_smarty_tpl->tpl_vars['theme_parent_original_version']->value){?>
                                <div class="wa-theme-update-available alert info">
                                    <a class="theme-update js-theme-update bold" href="#" title="Все изменения, которые вы вносили в тему дизайна, будут потеряны. Сбросить все изменения?">
                                        <i class="fas fa-redo-alt"></i>
                                        <span>Доступно обновление</span>
                                    </a>
                                    <p class="hint custom-mt-8">Обновление темы дизайна требует вашего подтверждения, так как при обновлении шаблоны дизайна и настройки темы будут перезаписаны их новыми версиями, и это может повлиять на внешний вид и работоспособность вашего сайта. Щелкните, чтобы посмотреть список файлов, которые будут обновлены.</p>
                                </div>
                            <?php }?>
                        <?php }?>
                    <?php }else{ ?>
                        
                        <?php if ($_smarty_tpl->tpl_vars['theme']->value['version']!=$_smarty_tpl->tpl_vars['theme_original_version']->value&&!empty($_smarty_tpl->tpl_vars['theme_warning_requirements']->value)){?>
                            <div class="alert warning">
                                <strong>Обновление недоступно</strong>
                                <ul class="menu">
                                    <?php  $_smarty_tpl->tpl_vars['requirement'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['requirement']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme_warning_requirements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['requirement']->key => $_smarty_tpl->tpl_vars['requirement']->value){
$_smarty_tpl->tpl_vars['requirement']->_loop = true;
?>
                                        <li>
                                            <?php echo $_smarty_tpl->tpl_vars['requirement']->value['warning'];?>

                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            
                        <?php }elseif($_smarty_tpl->tpl_vars['theme']->value['version']!=$_smarty_tpl->tpl_vars['theme_original_version']->value&&!empty($_smarty_tpl->tpl_vars['theme_original_warning_requirements']->value)){?>
                            <div class="alert warning">
                                <strong>Обновление недоступно</strong>
                                <ul class="menu">
                                    <?php  $_smarty_tpl->tpl_vars['requirement'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['requirement']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme_original_warning_requirements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['requirement']->key => $_smarty_tpl->tpl_vars['requirement']->value){
$_smarty_tpl->tpl_vars['requirement']->_loop = true;
?>
                                        <li>
                                            <?php echo $_smarty_tpl->tpl_vars['requirement']->value['warning'];?>

                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            
                        <?php }elseif($_smarty_tpl->tpl_vars['theme']->value['version']!=$_smarty_tpl->tpl_vars['theme_original_version']->value){?>
                            <div class="wa-theme-update-available alert info">
                                <a class="theme-update js-theme-update bold" href="#" title="Все изменения, которые вы вносили в тему дизайна, будут потеряны. Сбросить все изменения?"><i class="fas fa-redo-alt"></i> <span><?php echo sprintf('Доступно обновление до версии %s',$_smarty_tpl->tpl_vars['theme_original_version']->value);?>
</span></a>
                                <p class="hint custom-mt-8">Обновление темы дизайна требует вашего подтверждения, так как при обновлении шаблоны дизайна и настройки темы будут перезаписаны их новыми версиями, и это может повлиять на внешний вид и работоспособность вашего сайта. Щелкните, чтобы посмотреть список файлов, которые будут обновлены.</p>
                            </div>
                        <?php }?>
                    <?php }?>

                <?php }else{ ?>

                    
                    <div class="wa-theme-orphan alert info">
                        <strong><i class="fas fa-copy"></i> <?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
</strong>
                        <p class="hint custom-mt-8"><?php echo sprintf('Эта тема дизайна была загружена в виде архива или создана как дубликат другой темы. Обновление для данной темы недоступно, так как оригинальной темы дизайна с таким же идентификатором (<strong>%s</strong>) не существует.',$_smarty_tpl->tpl_vars['theme']->value['id']);?>
</p>
                    </div>

                <?php }?>
            </div>

            <?php if (!$_smarty_tpl->tpl_vars['has_theme_usage']->value&&!$_smarty_tpl->tpl_vars['_is_trial']->value&&(!$_smarty_tpl->tpl_vars['has_theme_usage_any_app']->value||!empty($_smarty_tpl->tpl_vars['show_theme_start_using']->value))){?>
                <div class="wa-theme-use custom-mb-24">

                    <?php if (!$_smarty_tpl->tpl_vars['has_theme_usage_any_app']->value){?>
                        <?php if ($_smarty_tpl->tpl_vars['cover']->value){?>
                            <p>
                                <img src="<?php echo $_smarty_tpl->tpl_vars['cover']->value;?>
" class="wa-theme-cover" />
                            </p>
                        <?php }?>

                        <?php if (!empty($_smarty_tpl->tpl_vars['theme_warning_requirements']->value)){?>
                            <ul>
                                <?php  $_smarty_tpl->tpl_vars['requirement'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['requirement']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme_warning_requirements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['requirement']->key => $_smarty_tpl->tpl_vars['requirement']->value){
$_smarty_tpl->tpl_vars['requirement']->_loop = true;
?>
                                    <li><b><?php echo $_smarty_tpl->tpl_vars['requirement']->value['warning'];?>
</b></li>
                                <?php } ?>
                            </ul>
                        <?php }elseif(!empty($_smarty_tpl->tpl_vars['theme_original_warning_requirements']->value)){?>
                            <ul>
                                <?php  $_smarty_tpl->tpl_vars['requirement'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['requirement']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme_original_warning_requirements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['requirement']->key => $_smarty_tpl->tpl_vars['requirement']->value){
$_smarty_tpl->tpl_vars['requirement']->_loop = true;
?>
                                    <li><b><?php echo $_smarty_tpl->tpl_vars['requirement']->value['warning'];?>
</b></li>
                                <?php } ?>
                            </ul>
                        <?php }elseif(!empty($_smarty_tpl->tpl_vars['theme_parent_warning_requirements']->value)){?>
                            <ul>
                                <?php  $_smarty_tpl->tpl_vars['requirement'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['requirement']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme']->value['parent_theme']->getWarningRequirements(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['requirement']->key => $_smarty_tpl->tpl_vars['requirement']->value){
$_smarty_tpl->tpl_vars['requirement']->_loop = true;
?>
                                    <li><b><?php echo $_smarty_tpl->tpl_vars['requirement']->value['warning'];?>
</b></li>
                                <?php } ?>
                            </ul>
                        <?php }?>
                    <?php }?>

                </div>
            <?php }?>

            
            <div class="wa-theme-usage fields">
                
                <?php if (!empty($_smarty_tpl->tpl_vars['support']->value)||!empty($_smarty_tpl->tpl_vars['instruction']->value)){?>
                    <div class="wa-theme-help custom-my-6 semibold small">
                        <?php if (!empty($_smarty_tpl->tpl_vars['instruction']->value)){?>
                        <div class="custom-mb-4">
                            Инструкция: <a class="nowrap" href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['instruction']->value, ENT_QUOTES, 'UTF-8', true);?>
" rel="noopener" target="_blank"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['instruction']->value, ENT_QUOTES, 'UTF-8', true);?>
 <i class="fas fa-external-link-alt fa-xs"></i></a>
                        </div>
                        <?php }?>
                        <?php if (!empty($_smarty_tpl->tpl_vars['support']->value)){?>
                        <div>
                            Поддержка: <a class="nowrap" href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['support']->value, ENT_QUOTES, 'UTF-8', true);?>
" rel="noopener" target="_blank"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['support']->value, ENT_QUOTES, 'UTF-8', true);?>
 <i class="fas fa-external-link-alt fa-xs"></i></a>
                        </div>
                        <?php }?>
                    </div>
                <?php }?>

                
                <?php if (trim($_smarty_tpl->tpl_vars['theme']->value['about'])){?>
                    <div class="small">
                        <?php echo $_smarty_tpl->tpl_vars['theme']->value['about'];?>

                    </div>
                <?php }?>

                
                <?php if (!empty($_smarty_tpl->tpl_vars['theme']->value['thumbs'])){?>
                    <h6>Эскизы изображений</h6>
                    <p class="small">Эта тема дизайна использует эскизы изображений перечисленных ниже размеров. Если в настройках вашего приложения возможность автоматического создания эскизов «на лету» отключена, убедитесь, что все указанные размеры добавлены в список разрешенных. Если создание эскизов «на лету» разрешено, изображения указанных ниже размеров будут создаваться автоматически.</p>
                    <ul>
                        <?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['t']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme']->value['thumbs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
$_smarty_tpl->tpl_vars['t']->_loop = true;
?>
                            <li><?php echo $_smarty_tpl->tpl_vars['t']->value;?>
</li>
                        <?php } ?>
                    </ul>
                <?php }?>

                <div class="field">
                    <div class="name">
                        ID темы
                    </div>
                    <div class="value">
                        <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['id'], ENT_QUOTES, 'UTF-8', true);?>

                    </div>
                </div>

                <div class="field">
                    <div class="name">
                        Версия темы
                    </div>
                    <div class="value">
                        <?php echo $_smarty_tpl->tpl_vars['theme']->value['version'];?>

                    </div>
                </div>

                <?php if (!$_smarty_tpl->tpl_vars['_is_trial']->value){?>
                    <div class="field">
                        <div class="name">
                            Путь к папке темы
                        </div>
                        <div class="value">
                            <?php if ($_smarty_tpl->tpl_vars['theme']->value['type']==waTheme::ORIGINAL){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['original'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?><strong><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['custom'], ENT_QUOTES, 'UTF-8', true);?>
</strong><?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['theme']->value['type']!=waTheme::ORIGINAL){?>
                                <p class="hint">
                                    Последнее изменение: <strong><?php echo smarty_modifier_wa_datetime($_smarty_tpl->tpl_vars['theme']->value['mtime'],"humandatetime");?>
</strong>
                                </p>
                            <?php }?>
                        </div>
                    </div>

                    <?php if (count($_smarty_tpl->tpl_vars['theme_routes']->value)){?>
                        <div class="field">
                            <div class="name">
                                Использование темы
                            </div>
                            <?php $_smarty_tpl->tpl_vars['_theme_usages'] = new Smarty_variable(array(), null, 0);?>
                            <div class="value">
                                <ul class="js-wa-preview-dd-content">
                                <?php  $_smarty_tpl->tpl_vars['_r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_r']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme_routes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_r']->key => $_smarty_tpl->tpl_vars['_r']->value){
$_smarty_tpl->tpl_vars['_r']->_loop = true;
?>
                                    <li>
                                        <?php if ($_smarty_tpl->tpl_vars['_r']->value['_domain']!=$_smarty_tpl->tpl_vars['wa']->value->get('domain')&&$_smarty_tpl->tpl_vars['_r']->value['_id']!=$_smarty_tpl->tpl_vars['wa']->value->get('route')){?>
                                            <?php $_smarty_tpl->createLocalArrayVariable('_theme_usages', null, 0);
$_smarty_tpl->tpl_vars['_theme_usages']->value[] = htmlspecialchars(((string)$_smarty_tpl->tpl_vars['_r']->value['_domain'])."/".((string)$_smarty_tpl->tpl_vars['_r']->value['url']));?>
                                        <?php }?>
                                        <a rel="noopener" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['_r']->value['_url'];?>
" class="no-underline bold break-word">
                                            <span><?php echo $_smarty_tpl->tpl_vars['_r']->value['_domain_decoded'];?>
/<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['_r']->value['url'],'*','');?>
</span>
                                            <span class="count small"><i class="fas fa-external-link-alt fa-xs"></i></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php }?>

                    <?php if (count($_smarty_tpl->tpl_vars['theme_routes']->value)>1){?>
                        <p class="gray small">
                            <i class="fas fa-exclamation-triangle fa-xs"></i> <?php echo sprintf('Изменение настроек темы дизайна «%s» затронет все перечисленные поселения приложения. Если вы хотите сохранить индивидуальный дизайн для каждого поселения, используйте отдельные копии (дубликаты) темы дизайна.',htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true));?>

                        </p>
                    <?php }?>
                <?php }?>

            </div>
        </div>

        <?php if ($_smarty_tpl->tpl_vars['has_theme_usage_any_app']->value||$_smarty_tpl->tpl_vars['_is_trial']->value){?>
            
            <?php if (!empty($_smarty_tpl->tpl_vars['settings']->value['items'])){?>

                <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/farbtastic/farbtastic.js"></script>
                <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/farbtastic/farbtastic.css" type="text/css" />

                <iframe style="display: none" id="theme-settings-iframe" name="theme-settings-iframe"></iframe>
                <form id="theme-settings" method="post" action="?module=design&action=themeSettings&theme=<?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
" enctype="multipart/form-data" target="theme-settings-iframe">
                    <?php echo $_smarty_tpl->tpl_vars['wa']->value->csrf();?>

                    <div class="wa-theme-search-min-symbol bold js-search-min-symbol" style="display: none;">Минимум 3 символа</div>
                    <div class="wa-theme-search-result js-search-result" style="display: none;">Результаты поиска:</div>
                    <div class="wa-theme-search-no-result js-search-no-result" style="display: none;">Настройки не найдены</div>

                    <div class="wa-theme-settings-list fields js-settings-list">
                        <?php  $_smarty_tpl->tpl_vars['setting'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['setting']->_loop = false;
 $_smarty_tpl->tpl_vars['s_var'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['settings']->value['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['setting']->key => $_smarty_tpl->tpl_vars['setting']->value){
$_smarty_tpl->tpl_vars['setting']->_loop = true;
 $_smarty_tpl->tpl_vars['s_var']->value = $_smarty_tpl->tpl_vars['setting']->key;
?>
                            <?php smarty_template_function__renderThemeSetting($_smarty_tpl,array('_setting_var'=>$_smarty_tpl->tpl_vars['s_var']->value,'_setting'=>$_smarty_tpl->tpl_vars['setting']->value));?>

                        <?php } ?>
                    </div>
                </form>
            <?php }else{ ?>
                <p>
                    <br>
                    <em><?php echo sprintf('Тема дизайна «%s» не предоставляет опций для настройки внешнего вида. Используйте редактор шаблонов дизайна для настройки оформления.',htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true));?>
</em>
                </p>
            <?php }?>

        <?php }?>

        
        <?php /*  Call merged included template "./ThemeDialogs.inc.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('./ThemeDialogs.inc.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '9489234616a8c828be74550-70822046');
content_6a8c828bf20af1_60623486($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./ThemeDialogs.inc.html" */?>

        
        <div class="js-theme-settings-hidden-list" id="wa-theme-settings-hidden-list" style="display: none">
            <?php if (($_smarty_tpl->tpl_vars['has_theme_usage_any_app']->value||$_smarty_tpl->tpl_vars['_is_trial']->value||$_smarty_tpl->tpl_vars['has_theme_usage']->value)){?>
                <?php if (!empty($_smarty_tpl->tpl_vars['theme_routes']->value)){?>
                    <?php if (count($_smarty_tpl->tpl_vars['theme_routes']->value)==1){?>
                        <?php $_smarty_tpl->tpl_vars['theme_route'] = new Smarty_variable($_smarty_tpl->tpl_vars['theme_routes']->value[0], null, 0);?>
                    <?php }else{ ?>
                        <?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme_routes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value){
$_smarty_tpl->tpl_vars['r']->_loop = true;
?>
                            <?php if ($_smarty_tpl->tpl_vars['r']->value['_id']==$_smarty_tpl->tpl_vars['current_route']->value){?>
                                <?php $_smarty_tpl->tpl_vars['theme_route'] = new Smarty_variable($_smarty_tpl->tpl_vars['r']->value, null, 0);?>
                                <?php break 1?>
                            <?php }?>
                        <?php } ?>
                    <?php }?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['theme_route']->value)){?>
                        <ul class="menu custom-mb-0">
                            <li>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['design_url']->value;?>
theme=<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
&domain=<?php echo $_smarty_tpl->tpl_vars['theme_route']->value['_domain'];?>
&route=<?php echo $_smarty_tpl->tpl_vars['theme_route']->value['_id'];?>
&action=settings" class="js-theme-settings-link">
                                    <i class="fas fa-cog"></i>
                                    <span><?php echo htmlspecialchars((string)(($tmp = @waIdna::dec($_smarty_tpl->tpl_vars['theme_route']->value['_url_title']))===null||$tmp==='' ? 'Настройки' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</span>
                                </a>
                            </li>
                        </ul>
                    <?php }?>
                <?php }?>

                <ul class="menu<?php if (!empty($_smarty_tpl->tpl_vars['theme_route']->value)){?> custom-mt-0<?php }?> custom-mb-0 js-theme-settings-list">
                    <li class="bottom-padded">
                        <a href="javascript:void(0)" class="js-theme-info-link">
                            <i class="fas fa-info-circle"></i>
                            <span><?php echo sprintf('Тема %s',htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true));?>
</span>
                        </a>
                    </li>
                </ul>

                <?php if (empty($_smarty_tpl->tpl_vars['settings']->value['items'])||empty($_smarty_tpl->tpl_vars['global_group_divideres']->value)){?>
                    <div class="box align-center js-theme-not-use">
                        <p class="gray small custom-mt-16">
                            <?php echo sprintf(_ws('Design theme %s does not offer turnkey settings.'),htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true));?>

                        </p>
                    </div>
                <?php }elseif(!empty($_smarty_tpl->tpl_vars['settings']->value['items'])&&!empty($_smarty_tpl->tpl_vars['global_group_divideres']->value)){?>
                    <ul class="menu js-theme-settings-list">
                        <li class="selected bottom-padded hidden">
                            <a href="javascript:void(0);" data-divider-id="-1">
                                <span><?php echo $_smarty_tpl->tpl_vars['theme']->value['name'];?>
</span>
                            </a>
                        </li>
                        <?php  $_smarty_tpl->tpl_vars['_divider'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_divider']->_loop = false;
 $_smarty_tpl->tpl_vars['_divider_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['global_group_divideres']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_divider']->key => $_smarty_tpl->tpl_vars['_divider']->value){
$_smarty_tpl->tpl_vars['_divider']->_loop = true;
 $_smarty_tpl->tpl_vars['_divider_id']->value = $_smarty_tpl->tpl_vars['_divider']->key;
?>
                            <li>
                                <a href="javascript:void(0);" data-divider-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_divider_id']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                                    <i class="<?php echo strip_tags($_smarty_tpl->tpl_vars['_divider']->value['icon_class']);?>
"></i>
                                    <span><?php echo strip_tags($_smarty_tpl->tpl_vars['_divider']->value['name']);?>
</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php }?>
            <?php }else{ ?>
                <div class="box align-center js-theme-not-use">
                    <p class="gray small custom-mt-16">
                        <?php echo sprintf(_ws('%s design theme is currently not in use on your websites.'),htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true));?>

                    </p>
                </div>
            <?php }?>
        </div>
    </div>
</div>

<?php $_smarty_tpl->tpl_vars['_locale'] = new Smarty_variable(array('will_be_lost'=>_ws('All customizations you’ve made to this file will be lost!'),'update_notice'=>_ws('All selected files will be overwritten with their newest versions from the original theme. In case of incompatibility between your customizations and newer theme templates, CSS, and images, your site looks may change unexpectedly. There will be no way to automatically rollback this update. Update?'),'expand_all'=>_ws('Expand all'),'collapse_all'=>_ws('Collapse all'),'expand'=>_ws('Expand'),'collapse'=>_ws('Collapse'),'saving'=>_ws('Saving...'),'delete'=>_ws('Delete'),'are_you_sure'=>_ws('Are you sure?'),'cancel'=>_ws('Cancel'),'close'=>_ws('Close'),'error'=>_ws('Error'),'continue'=>_ws('Continue')), null, 0);?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', "_theme_row", null); ob_start(); ?>
<tr>
    <td>
        <label class="flexbox space-8">
            <span class="wa-checkbox custom-mt-2">
                <input class="js-theme-checkbox" type="checkbox" value="1" checked>
                <span>
                    <span class="icon">
                        <i class="fas fa-check"></i>
                    </span>
                </span>
            </span>
            <span class="js-theme-no-support icon custom-mt-2" title="<?php echo htmlspecialchars((string)sprintf('Не поддерживается темой «%s».',$_smarty_tpl->tpl_vars['theme']->value['name']), ENT_QUOTES, 'UTF-8', true);?>
">
                <i class="fas fa-ban text-orange"></i>
            </span>

            <span class="custom-pt-2 flexbox space-4 break-word">
                <span class="js-app-icon icon custom-pr-4"><img src="<?php echo $_smarty_tpl->tpl_vars['wa_static_url']->value;?>
wa-apps/site/img/site512.png"></span>
                <span class="js-name small"></span>
            </span>
        </label>
    </td>
    <td class="width-40 gray small">
        <span class="js-used-themes">
            
        </span>
    </td>
</tr>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<script>
    (function ($) {
        // remove theme settings to sidebar
        const $theme_settings_section = $('.js-theme-settings-section');
        const $theme_settings_list = $('.js-design-container').find('.js-theme-settings-hidden-list');

        // invoke only once / bug(?) with several times invoke waDesignLoad

        const url_params = waDesignParams(location.href);
        let action = '&action=theme';
        if (url_params.action) {
            action = ''
        }

        $('.js-theme-info-link')
            .attr('href', '<?php echo $_smarty_tpl->tpl_vars['design_url']->value;?>
theme=<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
' + action)
            .off('click.wa_theme_info_link').on('click.wa_theme_info_link', function(e) {
                e.preventDefault();
                sessionStorage.removeItem('wa_design_menu_id');
                const $theme_settings_section = $('.js-theme-settings-section');
                $theme_settings_section.data('menu-id', -1);
                const $theme_info_section = $('.js-theme-info-section');
                const $settings_section = $('.js-settings-list');
                $settings_section.hide();
                $theme_info_section.show();
                $('.bottombar').hide();
                const href = $(this).attr('href');
                $.wa.setHash(href.replace(/.*?#/, '#'));
                if (typeof window.waDesign !== 'undefined' && window.waDesign.waDesignLoad) {
                    window.waDesign.waDesignLoad(href.replace(/.*?#\//, '').replace(/\/$/, ''));
                }
            });

        $('.js-tabs-menu.js-design-actions').find('[data-action="theme"] a').attr('href', '#/design/theme=<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
');

        let menu_id = $theme_settings_section.data('menu-id');
        if(menu_id !== undefined) {
            let $settings_menu_selected = $theme_settings_list.find(`[data-divider-id="${ menu_id }"]`),
                $theme_info_section = $('.js-theme-info-section'),
                $settings_section = $('.js-settings-list'),
                $settings_section_selected = $settings_section.find(`[data-divider-id="${ menu_id }"]`);

            $settings_menu_selected.closest('li').addClass('selected').siblings().removeClass('selected')
            if(menu_id != -1){
                $theme_info_section.hide();
                $settings_section.show();
                $settings_section_selected.addClass('selected').siblings().removeClass('selected')
            } else {
                $settings_section.hide();
                $theme_info_section.show();
            }
        }

        if ($theme_settings_list.length) {
            $('.bottombar').hide();
        }

        if(!$theme_settings_section.data('loaded')) {
            $theme_settings_section.data('loaded', true).html($theme_settings_list.html());
        }else{
            $theme_settings_list.remove();
        }
        $theme_settings_section.data('loaded', true).html($theme_settings_list.html());

        // display uploaded filename
        $(".upload-control").waUpload();

        $(".js-theme-actions-dropdown").waDropdown();

        new WAThemeSettings({
            $wrapper: $("#<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"),
            theme_id: <?php echo json_encode($_smarty_tpl->tpl_vars['theme']->value['id']);?>
,
            theme_routes: <?php echo json_encode($_smarty_tpl->tpl_vars['theme_routes']->value);?>
,
            has_child_themes: !<?php echo json_encode(empty($_smarty_tpl->tpl_vars['child_themes']->value));?>
,
            design_url: <?php echo json_encode($_smarty_tpl->tpl_vars['design_url']->value);?>
,
            locale: <?php echo json_encode($_smarty_tpl->tpl_vars['_locale']->value);?>
,
            wa_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_url']->value);?>
,
            has_theme_usage: <?php echo json_encode($_smarty_tpl->tpl_vars['has_theme_usage']->value);?>
,
            current_domain: <?php echo json_encode($_smarty_tpl->tpl_vars['current_domain']->value);?>
,
            data: {
                settlements_by_domain: <?php echo json_encode($_smarty_tpl->tpl_vars['settlements_by_domain']->value);?>

            },
            templates: {
                toggle_group_all_settings: '<span class="button custom-ml-16 js-group-all-settings light-gray rounded smallest wa-theme-group-all-settings" style="font-size: 73%;padding: 3px 6px;">Показать все настройки группы <i class="js-icon fas fa-chevron-down"></i></span>',
                using_dialog_theme_row: <?php echo json_encode(preg_replace('!\s+!u', ' ',$_smarty_tpl->tpl_vars['_theme_row']->value));?>

            },
            locales: {
                theme_not_installed: 'Тема не установлена.',
                apps_outside_sitemap_title: 'Приложения вне карты сайта',
                apps_outside_sitemap_hint: 'Эти приложения отвечают за содержимое, которое может быть видно на сайте или быть доступно по отдельной ссылке, но не могут формировать полноценный раздел сайта.'
            }
        });

        $('#theme-settings [data-wa-tooltip-content]').waTooltip();

        <?php if (waRequest::get('show_start_using')){?>
            $('.js-theme-start-using').trigger('click');
        <?php }?>

        const $preview_dd = $('.js-wa-preview-dd');
        const $preview_btn = $preview_dd.find('.wa-theme-preview');
        const $preview_btn_ext_icon = $preview_btn.find('.fa-external-link-alt');
        const $preview_menu = $preview_dd.find('.dropdown-body > ul');
        $preview_btn.removeClass('dropdown-toggle');
        $preview_btn_ext_icon.removeClass('hidden');
        $preview_menu.empty();
        const $preview_dd_content = $('.js-wa-preview-dd-content li');
        if ($preview_dd_content.length > 1) {
            $preview_btn.addClass('dropdown-toggle');
            $preview_btn_ext_icon.addClass('hidden');
            $preview_menu.prepend($preview_dd_content.clone());

            $preview_dd.waDropdown({
                hover: false,
            });
        }
    })(jQuery);
</script>

<div class="wa-design-scroll-action" id="wa-design-scroll-top">
    <div class="icon-wrapper">
        <div class="icon-to-top"></div>
    </div>
    Наверх
</div>

<?php if (($_smarty_tpl->tpl_vars['theme_routes']->value||$_smarty_tpl->tpl_vars['_is_trial']->value||$_smarty_tpl->tpl_vars['has_theme_usage_any_app']->value)&&!empty($_smarty_tpl->tpl_vars['settings']->value['items'])){?>
    <?php /*  Call merged included template "./Bottombar.inc.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('./Bottombar.inc.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('_params'=>array('form_id'=>'theme-settings')), 0, '9489234616a8c828be74550-70822046');
content_6a8c828c02a734_67753534($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./Bottombar.inc.html" */?>
<?php }?>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:35
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/design/templates/ThemeDialogs.inc.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c828bf20af1_60623486')) {function content_6a8c828bf20af1_60623486($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
?>

<div class="dialog" id="wa-theme-update-dialog"></div>

<div class="dialog" id="wa-theme-reset-dialog">
    <div class="dialog-background"></div>
    <form class="dialog-body">
        <h3 class="dialog-header">Восстановить из исходной версии</h3>
        <div class="dialog-content">
            <p class="state-caution small"><i class="fas fa-exclamation-triangle"></i> Очистить все изменения, которые вы вносили в тему дизайна с помощью редактора дизайна, и вернуть тему дизайна к исходному состоянию?</p>
            <?php if ($_smarty_tpl->tpl_vars['theme']->value['parent_theme']&&$_smarty_tpl->tpl_vars['theme']->value['parent_theme']['type']==waTheme::OVERRIDDEN){?>
                <label class="small">
                    <span class="wa-checkbox">
                        <input type="checkbox" name="parent" value="1" checked="checked">
                        <span>
                            <span class="icon">
                                <i class="fas fa-check"></i>
                            </span>
                        </span>
                    </span>
                    <?php echo sprintf("Также сбросить все изменения, которые вносились в родительскую тему дизайна <strong>%s</strong>",$_smarty_tpl->tpl_vars['theme']->value['parent_theme_id']);?>

                </label>
            <?php }?>
        </div>
        <div class="dialog-footer flexbox middle space-8">
            <div>
                <input type="hidden" name="theme" value="<?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
">
                <input type="submit" class="button orange" value="Восстановить из исходной версии">
                <a href="#/design/themes/" class="js-close-dialog light-gray button">Отмена</a>
            </div>
        </div>
    </form>
</div>

<div class="dialog" id="wa-theme-start-using-dialog">
    <div class="dialog-background"></div>
    <form class="dialog-body dynamic-content">
        <h1 class="dialog-header break-word">Использовать тему <?php echo $_smarty_tpl->tpl_vars['theme']->value['name'];?>
</h1>
        <div class="dialog-content">
            <div class="fields">
                <div class="field vertical">
                    <div class="name bold custom-mb-8">Сайт</div>
                    <div class="value">
                        <div class="wa-select large wide">
                            <select name="domain" class="create-new-route-control text-ellipsis bold">
                                <?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['domains']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value){
$_smarty_tpl->tpl_vars['d']->_loop = true;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['d']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['d']->value===$_smarty_tpl->tpl_vars['current_domain']->value){?> selected<?php }?>><?php echo smarty_modifier_truncate(str_replace('www.','',waIdna::dec($_smarty_tpl->tpl_vars['d']->value)),64,'...',false,true);?>
</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <p class="hint custom-mt-16 js-wa-empty-site-map-hide">Карта этого сайта пустая. Добавьте на нее разделы или страницы, чтобы на них можно было использовать эту тему дизайна.</p>
                <div class="js-wa-empty-site-map-show">
                    <div id="js-alert-has-not-support-theme" class="alert warning small custom-mt-20 hidden">
                        <div class="flexbox space-8">
                            <div><i class="fas fa-ban"></i></div>
                            <div><?php echo htmlspecialchars((string)sprintf('Тема «%s» не может использоваться на всем сайте. Ниже отмечены страницы, разделы или приложения, которые не поддерживаются этой темой.',$_smarty_tpl->tpl_vars['theme']->value['name']), ENT_QUOTES, 'UTF-8', true);?>
</div>
                        </div>
                    </div>

                    <table id="wa-theme-start-using-dialog-routes">
                        <thead>
                            <tr>
                                <th>
                                    <label>
                                        <span class="wa-checkbox">
                                            <input type="checkbox" name="use_all_settlements" value="1"<?php if (empty($_smarty_tpl->tpl_vars['has_theme_usage']->value)){?> checked<?php }?>>
                                            <span>
                                                <span class="icon">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                            </span>
                                        </span>
                                        <span>На всем сайте</span>
                                    </label>
                                </th>
                                <th class="gray">Сейчас используется</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="dialog-footer">
            <div class="small custom-mb-24">
                <label class="js-wa-empty-site-map-show">
                    <span class="wa-checkbox">
                        <input type="checkbox" name="mobile_only" value="1" <?php if ($_smarty_tpl->tpl_vars['theme']->value['id']=='mobile'){?>checked<?php }?>>
                        <span>
                            <span class="icon">
                                <i class="fas fa-check"></i>
                            </span>
                        </span>
                    </span>
                    Использовать только для мобильных устройств
                </label>
            </div>
            <div class="dialog-footer-inner flexbox middle wrap space-8">
                <div>
                    <input type="hidden" name="theme" value="<?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
">
                    <input type="submit" class="button green" value="Начать использовать">
                    <a href="#" class="js-close-dialog button light-gray">Отмена</a>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="dialog" id="wa-theme-name-dialog">
    <div class="dialog-background"></div>
        <form class="dialog-body">
            <h3 class="dialog-header">Переименовать тему</h3>
            <div class="dialog-content">
                <span class="wa-theme-dialog-error" style="color: red;font-weight: bold;"></span>
                <div class="fields">
                    <div class="field">
                        <div class="name for-input">
                            Название темы
                        </div>
                        <div class="value">
                            <input type="text" id="wa-theme-rename-name" name="name" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" >
                        </div>
                    </div>
                    <div class="field">
                        <div class="name for-input">
                            ID темы
                        </div>
                        <div class="value">
                            <?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/<input type="text" id="wa-theme-rename-id" name="id" value="<?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
" class="bold shorter">
                            <p class="state-caution-hint"><i class="fas fa-exclamation-triangle"></i> ВАЖНО: изменяйте ID темы, только если вы полностью уверены в своих действиях. Если эта тема используется в ваших сайтах, вам придется вручную обновить настройки этих сайтов для использования этой или другой темы после изменения ID темы. В противном случае ваши сайты могут оказаться нерабочими из-за ошибки «Невозможно загрузить файл шаблона».</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dialog-footer flexbox middle space-8">
                <div>
                    <input type="submit" class="button orange" value="Сохранить">
                    <a href="#/design/themes/" class="js-close-dialog button light-gray">Отмена</a>
                </div>
            </div>
        </form>
</div>

<div class="dialog" id="wa-theme-copy-dialog">
    <div class="dialog-background"></div>
    <form class="dialog-body">
        <h3 class="dialog-header">Создать клон темы</h3>
        <div class="dialog-content">
            <div class="fields">
                <div class="field">
                    <div class="name for-input">
                        Название темы
                    </div>
                    <div class="value">
                        <input type="text" id="wa-theme-copy-name" name="name" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
 1" >
                    </div>
                </div>
                <div class="field">
                    <div class="name for-input">
                        ID темы
                    </div>
                    <div class="value">
                        <input type="text" id="wa-theme-copy-id" name="id" value="<?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
1" class="bold">
                    </div>
                </div>
                <div class="field">
                    <div class="name for-checkbox">
                        Клонировать темы
                    </div>
                    <div class="value small">
                        <ul>
                            <li>
                                <label class="bold">
                                    <span class="wa-radio">
                                        <input name="related" type="radio" value="1" checked="checked">
                                        <span></span>
                                    </span>
                                    <?php echo htmlspecialchars((string)sprintf('Тема «%s» для всех приложений (рекомендуется)',$_smarty_tpl->tpl_vars['theme']->value['name']), ENT_QUOTES, 'UTF-8', true);?>

                                </label>
                            </li>
                            <li>
                                <label>
                                    <span class="wa-radio">
                                        <input name="related" type="radio" value="0">
                                        <span></span>
                                    </span>
                                    <?php echo htmlspecialchars((string)sprintf('Тема «%s» только для приложения «%s»',$_smarty_tpl->tpl_vars['theme']->value['name'],ifempty($_smarty_tpl->tpl_vars['apps']->value[$_smarty_tpl->tpl_vars['theme']->value['app']]['name'],$_smarty_tpl->tpl_vars['theme']->value['app'])), ENT_QUOTES, 'UTF-8', true);?>

                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="dialog-footer flexbox middle space-8">
            <div>
                <input type="submit" class="button blue" value="Создать клон темы">
                <a href="#/design/themes/" class="js-close-dialog button light-gray">Отмена</a>
            </div>
        </div>
    </form>
</div>

<div class="dialog" id="wa-theme-download-dialog">
    <div class="dialog-background"></div>
    <form class="dialog-body">
        <h3 class="dialog-header"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</h3>
        <div class="dialog-content">

            <ul class="zebra">
                <?php  $_smarty_tpl->tpl_vars['related_theme'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['related_theme']->_loop = false;
 $_smarty_tpl->tpl_vars['related_theme_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['theme']->value['related_themes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['related_theme']->key => $_smarty_tpl->tpl_vars['related_theme']->value){
$_smarty_tpl->tpl_vars['related_theme']->_loop = true;
 $_smarty_tpl->tpl_vars['related_theme_id']->value = $_smarty_tpl->tpl_vars['related_theme']->key;
?>
                    <li<?php if (($_smarty_tpl->tpl_vars['theme']->value['app_id']==$_smarty_tpl->tpl_vars['related_theme']->value['app_id'])||($_smarty_tpl->tpl_vars['theme']->value['parent_theme_id']==$_smarty_tpl->tpl_vars['related_theme_id']->value)){?> class="bold"<?php }?>>
                        <a href="?module=design&amp;action=themeDownload&amp;theme=<?php echo $_smarty_tpl->tpl_vars['related_theme']->value['id'];?>
&amp;app_id=<?php echo $_smarty_tpl->tpl_vars['related_theme']->value['app_id'];?>
" download>
                            <i class="fas fa-save text-blue custom-mr-4"></i>
                            <span class="small"><?php echo htmlspecialchars((string)sprintf('Тема «%s» (версия %s) для приложения «%s»',$_smarty_tpl->tpl_vars['related_theme']->value['name'],$_smarty_tpl->tpl_vars['related_theme']->value['version'],ifempty($_smarty_tpl->tpl_vars['apps']->value[$_smarty_tpl->tpl_vars['related_theme']->value['app_id']]['name'],$_smarty_tpl->tpl_vars['related_theme']->value['app'])), ENT_QUOTES, 'UTF-8', true);?>
 <span class="hint">.tar.gz</span></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="dialog-footer">
            <a href="#/design/themes/" class="js-close-dialog button light-gray">Закрыть</a>
        </div>
    </form>
</div>

<div class="dialog" id="wa-theme-parent-dialog">
    <div class="dialog-background"></div>
    <form class="dialog-body">
        <h3 class="dialog-header">Родительская тема дизайна</h3>
        <div class="dialog-content">
            <span class="wa-theme-dialog-error" style="color: red;font-weight: bold;"></span>
            <div class="fields">
                <div class="field">
                    <div class="name for-input">
                        Сменить родительскую тему
                    </div>
                    <div class="value">
                        <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
">
                        <div class="wa-select">
                            <select name="parent_theme_id" style="max-width: 370px">
                                <option value=""<?php if (!$_smarty_tpl->tpl_vars['theme']->value['parent_theme_id']){?> selected="selected"<?php }?>>Не задана</option>
                                <?php  $_smarty_tpl->tpl_vars['info'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['info']->_loop = false;
 $_smarty_tpl->tpl_vars['app_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['parent_themes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['info']->key => $_smarty_tpl->tpl_vars['info']->value){
$_smarty_tpl->tpl_vars['info']->_loop = true;
 $_smarty_tpl->tpl_vars['app_id']->value = $_smarty_tpl->tpl_vars['info']->key;
?>
                                    <optgroup label="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['info']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
">
                                        <?php  $_smarty_tpl->tpl_vars['theme_name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['theme_name']->_loop = false;
 $_smarty_tpl->tpl_vars['parent_theme_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['info']->value['themes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['theme_name']->key => $_smarty_tpl->tpl_vars['theme_name']->value){
$_smarty_tpl->tpl_vars['theme_name']->_loop = true;
 $_smarty_tpl->tpl_vars['parent_theme_id']->value = $_smarty_tpl->tpl_vars['theme_name']->key;
?>
                                            <?php $_smarty_tpl->tpl_vars['parent_theme_id'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['app_id']->value).":".((string)$_smarty_tpl->tpl_vars['parent_theme_id']->value), null, 0);?>
                                            <option value="<?php echo $_smarty_tpl->tpl_vars['parent_theme_id']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['parent_theme_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['parent_theme_id']->value==$_smarty_tpl->tpl_vars['theme']->value['parent_theme_id']){?> selected="selected"<?php }elseif(($_smarty_tpl->tpl_vars['parent_theme_id']->value==((string)$_smarty_tpl->tpl_vars['theme']->value['app_id']).":".((string)$_smarty_tpl->tpl_vars['theme']->value['id']))||($_smarty_tpl->tpl_vars['parent_theme_id']->value==$_smarty_tpl->tpl_vars['theme']->value['id'])){?> disabled="disabled"<?php }?>><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme_name']->value, ENT_QUOTES, 'UTF-8', true);?>
 (<?php echo $_smarty_tpl->tpl_vars['parent_theme_id']->value;?>
)</option>
                                        <?php } ?>
                                    </optgroup>
                                <?php } ?>
                            </select>
                        </div>
                        <p class="state-caution-hint"><i class="fas fa-exclamation-triangle fa-sm"></i> ВАЖНО: Подключение родительской темы позволит использовать в данной теме дизайна HTML-шаблоны, CSS-стили и изображения из родительской темы. Если родительская тема уже задана, то имейте ввиду, что ее смена может привести к ошибкам доступа к уже используемым файлам родительской темы. Для любой темы дизайна можно задать не более одной родительской темы.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="dialog-footer flexbox middle space-8">
            <div>
                <input type="submit" class="button orange" value="Сохранить">
                <a href="#/design/themes/" class="js-close-dialog button light-gray">Отмена</a>
            </div>
        </div>
    </form>
</div>

<div class="dialog" id="wa-theme-import-settings-dialog">
    <div class="dialog-background"></div>
    <form class="dialog-body">
        <h3 class="dialog-header">Импорт настроек темы</h3>
        <div class="dialog-content">
            <p class="state-caution small"><i class="fas fa-exclamation-triangle"></i>Текущие настройки дизайна будут потеряны в ходе импорта. Экспортируйте текущие настройки до импорта, чтобы восстановить их при необходимости.</p>
            <div class="fields">
                <div class="field">
                    <div class="name for-button">
                        Выберите архив
                    </div>
                    <div class="value upload-file">
                        <div class="upload js-input-wrapper">
                            <label class="link button outlined">
                                <i class="fas fa-file-upload"></i>
                                <span>Выберите файл в формате TAR.GZ</span>
                                <input type="file" name="theme_settings" accept=".gz">
                            </label>
                        </div>
                        <span class="wa-archive-name js-archive-name"></span>
                    </div>
                </div>
                <div class="wa-theme-settings-import-dialog-error js-error-place"></div>
            </div>
        </div>
        <div class="dialog-footer">
            <input type="submit" class="button blue" value="Импорт" disabled="disabled">
            <a href="#/design/themes/" class="js-close-dialog button light-gray">Отмена</a>
            <i class="fas fa-spinner fa-spin loading" style="vertical-align: middle; margin-left: 6px; display: none;"></i>
        </div>
    </form>
</div>

<div class="dialog" id="wa-theme-blocking-removal-dialog">
    <div class="dialog-background"></div>
    <div class="dialog-body">
        <h3 class="dialog-header">Удаление темы дизайна невозможно</h3>
        <div class="dialog-content">
            <ol style="<?php if (!empty($_smarty_tpl->tpl_vars['theme_routes']->value)&&!empty($_smarty_tpl->tpl_vars['child_themes']->value)){?>padding-left: 16px;<?php }else{ ?>padding-left: 0;list-style: none;<?php }?>">
                <?php if (!empty($_smarty_tpl->tpl_vars['theme_routes']->value)){?>
                    <li<?php if (!empty($_smarty_tpl->tpl_vars['child_themes']->value)){?> class="custom-mb-32" <?php }?>>
                        <p>
                            Эта тема дизайна еще используется на вашем сайте.<br>
                            Чтобы удалить тему дизайна, сначала смените ее на другую тему в настройках следующих поселений:
                        </p>
                        <ul class="zebra">
                            <?php  $_smarty_tpl->tpl_vars['_r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_r']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['theme_routes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_r']->key => $_smarty_tpl->tpl_vars['_r']->value){
$_smarty_tpl->tpl_vars['_r']->_loop = true;
?>
                                <li>
                                    <a rel="noopener" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['_r']->value['_url'];?>
">
                                        <?php echo $_smarty_tpl->tpl_vars['_r']->value['_domain_decoded'];?>
/<?php echo $_smarty_tpl->tpl_vars['_r']->value['url'];?>

                                        <i class="fas fa-external-link-alt fa-xs"></i>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php }?>
                <?php if (!empty($_smarty_tpl->tpl_vars['child_themes']->value)){?>
                    <li>
                        <p>
                            Эта тема дизайна является родительской, и у нее есть дочерние темы.<br>
                            Удалите дочерние темы дизайна или выберите для них другую родительскую.
                        </p>
                        <ul class="zebra">
                            <?php  $_smarty_tpl->tpl_vars['_theme'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_theme']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['child_themes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_theme']->key => $_smarty_tpl->tpl_vars['_theme']->value){
$_smarty_tpl->tpl_vars['_theme']->_loop = true;
?>
                                <li><?php echo sprintf('Тема «%s» для приложения «%s»',htmlspecialchars((string)$_smarty_tpl->tpl_vars['_theme']->value['name'], ENT_QUOTES, 'UTF-8', true),htmlspecialchars((string)$_smarty_tpl->tpl_vars['_theme']->value['app_name'], ENT_QUOTES, 'UTF-8', true));?>
</li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php }?>
            </ol>
        </div>
        <div class="dialog-footer">
            <input type="submit" class="js-close-dialog button light-gray" value="Закрыть">
        </div>
    </div>
</div>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:36
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/design/templates/Bottombar.inc.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c828c02a734_67753534')) {function content_6a8c828c02a734_67753534($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['action'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['wa']->value->get('action'))===null||$tmp==='' ? '' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['form_id'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_params']->value['form_id'])===null||$tmp==='' ? '' : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['file'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['_params']->value['file']['id'])===null||$tmp==='' ? '' : $tmp), null, 0);?>
<div class="bottombar sticky bordered-top box flexbox middle space-16">
    <?php if ($_smarty_tpl->tpl_vars['action']->value==='theme'){?>
    <div class="article width-100">
        <div class="article-body flexbox middle space-16 custom-py-0">
    <?php }?>
        <input id="bb_submit" form="<?php echo $_smarty_tpl->tpl_vars['form_id']->value;?>
" type="submit" class="button green js-bb-submit"<?php if (!$_smarty_tpl->tpl_vars['form_id']->value){?> disabled<?php }?> value="Сохранить" />
        <?php if (!$_smarty_tpl->tpl_vars['form_id']->value){?><span class="state-error">Пустой параметр <code>form_id</code>.</span><?php }?>
        <em class="hint">Ctrl + S</em>
        <span id="wa-editor-status" class="custom-ml-24" style="display: none"></span>
        <?php if ($_smarty_tpl->tpl_vars['file']->value){?>
            <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['wa']->value->app();?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo (($tmp = @rawurlencode($_smarty_tpl->tpl_vars['file']->value))===null||$tmp==='' ? '' : $tmp);?>
<?php $_tmp2=ob_get_clean();?><?php echo $_smarty_tpl->tpl_vars['wa']->value->getCheatSheetButton(array('data'=>array("app"=>$_tmp1,"index"=>$_tmp2)));?>

        <?php }?>
    <?php if ($_smarty_tpl->tpl_vars['action']->value==='theme'){?>
        </div>
    </div>
    <?php }?>
</div>
<?php }} ?>