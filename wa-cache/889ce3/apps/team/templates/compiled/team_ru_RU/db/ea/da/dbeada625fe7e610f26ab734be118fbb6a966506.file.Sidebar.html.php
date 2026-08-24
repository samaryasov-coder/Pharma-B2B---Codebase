<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:43
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/Sidebar.html" */ ?>
<?php /*%%SmartyHeaderCode:10597595786a889d1351d312-98259733%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dbeada625fe7e610f26ab734be118fbb6a966506' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/Sidebar.html',
      1 => 1738158327,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10597595786a889d1351d312-98259733',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    '_is_admin' => 0,
    '_has_rights' => 0,
    'is_reload' => 0,
    'module' => 0,
    'action' => 0,
    'wa_app_url' => 0,
    'all_count' => 0,
    'backend_sidebar' => 0,
    '_' => 0,
    'groups' => 0,
    '_g' => 0,
    '_can_add' => 0,
    'icon' => 0,
    'locations' => 0,
    '_l' => 0,
    'invited_count' => 0,
    'inactive_count' => 0,
    'noaccess_count' => 0,
    '_can_sort' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d13538e16_00252092',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d13538e16_00252092')) {function content_6a889d13538e16_00252092($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['module'] = new Smarty_variable(waRequest::param('module'), null, 0);?><?php $_smarty_tpl->tpl_vars['action'] = new Smarty_variable(waRequest::param('action'), null, 0);?><?php $_smarty_tpl->tpl_vars['_is_admin'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin($_smarty_tpl->tpl_vars['wa']->value->app()), null, 0);?><?php $_smarty_tpl->tpl_vars['_has_rights'] = new Smarty_variable(teamHelper::hasRights(), null, 0);?><?php $_smarty_tpl->tpl_vars['_can_sort'] = new Smarty_variable(($_smarty_tpl->tpl_vars['_is_admin']->value||$_smarty_tpl->tpl_vars['_has_rights']->value), null, 0);?><?php if (!$_smarty_tpl->tpl_vars['is_reload']->value){?><nav class="sidebar-mobile-toggle"><div class="box align-center"><a href="javascript:void(0);"><i class="fas fa-bars"></i>&nbsp;Показать меню</a></div></nav><?php if (teamHelper::hasRights('add_users')){?><div class="sidebar-header box custom-pt-20"><button class="button full-width align-center" id="t-new-user-link"><i class="fas fa-user-plus fa-w-20 custom-mr-4"></i> <span class="s-title small">Добавить сотрудника</span></button></div><?php }?><?php }?><div class="sidebar-body t-sidebar-block" id="t-sidebar"><ul class="menu mobile-friendly custom-my-8"><li class="<?php if ($_smarty_tpl->tpl_vars['module']->value=='users'&&!$_smarty_tpl->tpl_vars['action']->value){?>selected<?php }?>" id="all-users-sidebar-link"><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
"><span class="count js-count"><?php echo $_smarty_tpl->tpl_vars['all_count']->value;?>
</span><i class="fas fa-users"></i><span>Все сотрудники</span></a></li><li class="<?php if ($_smarty_tpl->tpl_vars['module']->value=="calendar"){?>selected<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
calendar/"><i class="fas fa-calendar-alt"></i><span>Календарь</span></a></li><!-- plugin hook: 'backend_sidebar.top_li' --><?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_sidebar']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo ifset($_smarty_tpl->tpl_vars['_']->value['top_li']);?>
<?php } ?></ul><div class="box"><form class="t-search-form state-with-inner-icon left width-100"><input class="t-search-field small full-width custom-mr-0" type="search" placeholder="Поиск сотрудника"><button class="icon t-search-submit" type="submit"><i class="fas fa-search"></i></button></form></div><!-- plugin hook: 'backend_sidebar.section' --><?php if (!empty($_smarty_tpl->tpl_vars['backend_sidebar']->value)){?><?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_sidebar']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo ifset($_smarty_tpl->tpl_vars['_']->value['section']);?>
<?php } ?><?php }?><div class="js-drop-block"><header class="heading custom-mt-16"><span>Группы</span><?php if (teamHelper::hasRights('add_groups')){?><a href="javascript:void(0);" class="count action js-add-user-group" title="Добавить группу"><i class="fas fa-plus-circle"></i></a><?php }?></header><?php if ($_smarty_tpl->tpl_vars['groups']->value){?><ul class="menu mobile-friendly collapsible t-groups-list"><?php  $_smarty_tpl->tpl_vars['_g'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_g']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_g']->key => $_smarty_tpl->tpl_vars['_g']->value){
$_smarty_tpl->tpl_vars['_g']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['_can_add'] = new Smarty_variable(teamHelper::hasRights("edit_users_in_group.".((string)$_smarty_tpl->tpl_vars['_g']->value['id'])), null, 0);?><?php $_smarty_tpl->tpl_vars['icon'] = new Smarty_variable('fas fa-user', null, 0);?><?php if ($_smarty_tpl->tpl_vars['_g']->value['icon']){?><?php if (strpos($_smarty_tpl->tpl_vars['_g']->value['icon'],'fa-')!==false){?><?php $_smarty_tpl->tpl_vars['icon'] = new Smarty_variable($_smarty_tpl->tpl_vars['_g']->value['icon'], null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['icon'] = new Smarty_variable('fas fa-user-friends', null, 0);?><?php }?><?php }?><li class="<?php if ($_smarty_tpl->tpl_vars['_can_add']->value){?>js-drop-place<?php }?>" data-group-id="<?php echo $_smarty_tpl->tpl_vars['_g']->value['id'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
group/<?php echo $_smarty_tpl->tpl_vars['_g']->value['id'];?>
/"><span class="count js-count"><?php echo $_smarty_tpl->tpl_vars['_g']->value['cnt'];?>
</span><i class="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['icon']->value, ENT_QUOTES, 'UTF-8', true);?>
"></i><span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_g']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span></a></li><?php } ?></ul><?php }else{ ?><div class="box custom-m-16"><p class="hint align-center">Нет групп</p></div><?php }?></div><div class="js-drop-block"><header class="heading"><span>Офисы</span><?php if (teamHelper::hasRights('add_groups')){?><a href="javascript:void(0);" class="count action js-add-user-location" title="Добавить офис"><i class="fas fa-plus-circle"></i></a><?php }?></header><?php if ($_smarty_tpl->tpl_vars['locations']->value){?><ul class="menu mobile-friendly collapsible t-locations-list"><?php  $_smarty_tpl->tpl_vars['_l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['locations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_l']->key => $_smarty_tpl->tpl_vars['_l']->value){
$_smarty_tpl->tpl_vars['_l']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['_can_add'] = new Smarty_variable(teamHelper::hasRights("edit_users_in_group.".((string)$_smarty_tpl->tpl_vars['_l']->value['id'])), null, 0);?><li class="<?php if ($_smarty_tpl->tpl_vars['_can_add']->value){?>js-drop-place<?php }?>" data-group-id="<?php echo $_smarty_tpl->tpl_vars['_l']->value['id'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
group/<?php echo $_smarty_tpl->tpl_vars['_l']->value['id'];?>
/"><span class="count js-count"><?php echo $_smarty_tpl->tpl_vars['_l']->value['cnt'];?>
</span><span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_l']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span></a></li><?php } ?></ul><?php }else{ ?><div class="box custom-m-16"><p class="hint align-center">Нет офисов</p></div><?php }?></div><ul class="menu mobile-friendly"><?php if (teamHelper::hasRights('add_users')&&$_smarty_tpl->tpl_vars['invited_count']->value){?><li data-invited-item><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
invited/"><span class="count js-count"><?php echo $_smarty_tpl->tpl_vars['invited_count']->value;?>
</span><i class="fas fa-clock text-orange"></i><span>Приглашения</span></a></li><?php }?><?php if (teamHelper::hasRights()&&$_smarty_tpl->tpl_vars['inactive_count']->value){?><li data-inactive-item><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
inactive/"><span class="count js-count"><?php echo $_smarty_tpl->tpl_vars['inactive_count']->value;?>
</span><i class="fas fa-ban"></i><span>Заблокированные</span></a></li><?php }?><?php if (teamHelper::hasRights()&&!empty($_smarty_tpl->tpl_vars['noaccess_count']->value)){?><li data-no-access-item><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
no-access/"><span class="count js-count"><?php echo $_smarty_tpl->tpl_vars['noaccess_count']->value;?>
</span><i class="fas fa-eye-slash"></i><span>Без доступа</span></a></li><?php }?><!-- plugin hook: 'backend_sidebar.bottom_li' --><?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_sidebar']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo ifset($_smarty_tpl->tpl_vars['_']->value['bottom_li']);?>
<?php } ?></ul></div><?php if (teamHelper::hasRights()&&!$_smarty_tpl->tpl_vars['is_reload']->value){?><div class="sidebar-footer shadowed"><ul class="menu"><?php if ($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin('webasyst')){?><li><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
access/"><i class="fas fa-key"></i><span>Доступ</span></a></li><li><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
api-tokens/"><i class="fas fa-tags"></i><span>Токены API</span></a></li><?php }?><li<?php if ($_smarty_tpl->tpl_vars['module']->value=='plugins'){?> class="selected"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
plugins/#/"><i class="fas fa-plug"></i><span>Плагины</span></a></li><li><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
settings/"><i class="fas fa-cog"></i><span>Настройки</span></a></li></ul></div><?php }?><script>( function($) {$.team.sidebar = new Sidebar($("#t-sidebar-wrapper"), {app_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
,updateTime: 1000 * 60 * 5,can_sort: <?php if ($_smarty_tpl->tpl_vars['_can_sort']->value&&!$_smarty_tpl->tpl_vars['wa']->value->isMobile()){?>true<?php }else{ ?>false<?php }?>,api: {reload: '?module=sidebar',inviteDialog: '?module=users&action=inviteform',group: '?module=group&action=edit',sort: '?module=group&action=sortSave',moveUser: '?module=group&action=userAdd'},classes: {selected: 'selected',drop: 'js-drop-place',highlighted: 'highlighted',noHighlight: 'js-no-highlight',uiDraggable: 'ui-draggable',initGroupDialog: 'js-add-user-group'}});})(jQuery);</script>
<?php }} ?>