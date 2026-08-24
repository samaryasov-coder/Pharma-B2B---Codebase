<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:08:56
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/settings/Settings.html" */ ?>
<?php /*%%SmartyHeaderCode:14644095376a89bb8846b232-41805130%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a40a857b4705c88958ac260bb7705181468726e' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/settings/Settings.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14644095376a89bb8846b232-41805130',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'settings' => 0,
    'setting' => 0,
    'key' => 0,
    'setting_type' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89bb88473461_64570398',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89bb88473461_64570398')) {function content_6a89bb88473461_64570398($_smarty_tpl) {?><div class="content flexbox wrap-mobile">
	<div class="sidebar flexbox bordered-right bordered-left width-adaptive-wider mobile-friendly" id="pb2b_settings_sidebar">
        <nav class="sidebar-mobile-toggle">
            <div class="box align-center">
                <a href="#"><i class="fas fa-bars"></i> Настройки системы</a>
            </div>
        </nav>
        <div class="sidebar-body">
            <h5 class="heading mt15">Настройки системы</h5>

            <ul class="menu appls-settings-tab-controls mt25">
				<?php  $_smarty_tpl->tpl_vars['setting'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['setting']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['settings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['setting']->key => $_smarty_tpl->tpl_vars['setting']->value){
$_smarty_tpl->tpl_vars['setting']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['setting']->key;
?>
                    <?php $_smarty_tpl->tpl_vars['setting_type'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['setting']->value['type'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['key']->value : $tmp), null, 0);?>
					<li>
						<a href="#/settings/page/type=<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['setting_type']->value, ENT_QUOTES, 'UTF-8', true);?>
">
							<i class="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['setting']->value['icon'])===null||$tmp==='' ? 'fas fa-cog' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
"></i>
							<span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['setting']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
						</a>
					</li>
				<?php } ?>
			</ul>
			
        </div>
    </div>

    <div class="content">
        <div class="article">
            <div class="article-body pb2b-settings-ajax pb60"></div>
        </div>
    </div>
</div>
<?php }} ?>