<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:10
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/includes/include.sidebarFilters.html" */ ?>
<?php /*%%SmartyHeaderCode:9635382456a889d66b2c2c8-74852969%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '628876cb06a3077fb85e6ac13ae1b3641dbc7170' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/includes/include.sidebarFilters.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9635382456a889d66b2c2c8-74852969',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d66b3a7d8_89316142',
  'variables' => 
  array (
    'namespace' => 0,
    'filters' => 0,
    'filter' => 0,
    'value' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d66b3a7d8_89316142')) {function content_6a889d66b3a7d8_89316142($_smarty_tpl) {?><?php if (!isset($_smarty_tpl->tpl_vars['namespace']->value)){?><?php $_smarty_tpl->tpl_vars['namespace'] = new Smarty_variable('sidebar', null, 0);?><?php }?>

	<h5 class="heading">Фильтры</h5>
	
	<div class="sidebar-filter sidebar-filter-<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['namespace']->value, ENT_QUOTES, 'UTF-8', true);?>
 mt25">
		<?php  $_smarty_tpl->tpl_vars['filter'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['filter']->_loop = false;
 $_from = (($tmp = @$_smarty_tpl->tpl_vars['filters']->value)===null||$tmp==='' ? array() : $tmp); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['filter']->key => $_smarty_tpl->tpl_vars['filter']->value){
$_smarty_tpl->tpl_vars['filter']->_loop = true;
?>
	
			<?php if ($_smarty_tpl->tpl_vars['filter']->value['type']=='hidden'){?>
				<input type="hidden" name="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['namespace']->value, ENT_QUOTES, 'UTF-8', true);?>
[<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['filter']->value['code'], ENT_QUOTES, 'UTF-8', true);?>
]" value="<?php echo htmlspecialchars((string)ifempty($_smarty_tpl->tpl_vars['filter']->value['value'],''), ENT_QUOTES, 'UTF-8', true);?>
">
	
			<?php }else{ ?>
				<div class="sidebar-filter-field  ">
					<div class="sidebar-filter-caption"><span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['filter']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span></div>
	
					<div class="sidebar-filter-content">
	
						<?php if ($_smarty_tpl->tpl_vars['filter']->value['type']=='select'){?>
							<div class="sidebar-filter-block">
								<div class="wa-select w100">
									<select name="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['namespace']->value, ENT_QUOTES, 'UTF-8', true);?>
[<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['filter']->value['code'], ENT_QUOTES, 'UTF-8', true);?>
]">
										<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = (($tmp = @$_smarty_tpl->tpl_vars['filter']->value['values'])===null||$tmp==='' ? array() : $tmp); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
											<option value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['value']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
"<?php if (!empty($_smarty_tpl->tpl_vars['value']->value['checked'])){?> selected<?php }?>><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['value']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</option>
										<?php } ?>
									</select>
								</div>
							</div>
	
						<?php }elseif($_smarty_tpl->tpl_vars['filter']->value['type']=='checkbox'){?>
							<div class="sidebar-filter-block mt10">
								<ul class="sidebar-filter-checkbox-menu menu mt10">
									<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = (($tmp = @$_smarty_tpl->tpl_vars['filter']->value['values'])===null||$tmp==='' ? array() : $tmp); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
										
											<li class="sidebar-filter-checkbox" data-default="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['value']->value['default'])===null||$tmp==='' ? '0' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
											<input type="checkbox"
												name="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['namespace']->value, ENT_QUOTES, 'UTF-8', true);?>
[<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['filter']->value['code'], ENT_QUOTES, 'UTF-8', true);?>
][<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['value']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
]"
												<?php if (!empty($_smarty_tpl->tpl_vars['value']->value['checked'])){?> checked<?php }?>>
												<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['value']->value['name'], ENT_QUOTES, 'UTF-8', true);?>

											</li>
									
									<?php } ?>
								</ul>
							</div>	
	
						<?php }elseif($_smarty_tpl->tpl_vars['filter']->value['type']=='input'){?>
							<div class="sidebar-filter-block">
								<input type="text" class="w100"
									   name="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['namespace']->value, ENT_QUOTES, 'UTF-8', true);?>
[<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['filter']->value['code'], ENT_QUOTES, 'UTF-8', true);?>
]"
									   placeholder="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['filter']->value['placeholder'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
							</div>
	
						<?php }elseif($_smarty_tpl->tpl_vars['filter']->value['type']=='date-interval'){?>
							<div class="sidebar-filter-block">
								C
								<input type="date" name="company[create_datetime][from]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['filter']->value['from'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
								<br>
								По
								<input type="date" name="company[create_datetime][to]" value="<?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['filter']->value['to'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
">
							</div>
						<?php }?>
	
					</div>
				</div>
			<?php }?>
	
		<?php } ?>
	
		<div class="sidebar-filter-block mt15 mb30">
			<button type="submit" class="button green w100">Показать</button>
		</div>
	</div>



    



<?php }} ?>