<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileActivity.html" */ ?>
<?php /*%%SmartyHeaderCode:2399359116a889d14f2f368-56527357%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '02956a06f24e1a40c8433bd1578f8c91564e35c3' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileActivity.html',
      1 => 1715848205,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2399359116a889d14f2f368-56527357',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'activity' => 0,
    '_timestamp' => 0,
    '_activity_item' => 0,
    'time' => 0,
    '_last_datetime' => 0,
    '_period' => 0,
    'current_month_index' => 0,
    'before_month_index' => 0,
    '_current_month_index' => 0,
    'months' => 0,
    'count' => 0,
    '_max_id' => 0,
    'wa_app_url' => 0,
    'user_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d15003f45_93277426',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d15003f45_93277426')) {function content_6a889d15003f45_93277426($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['_timestamp'] = new Smarty_variable(waRequest::request("timestamp"), null, 0);?><?php $_smarty_tpl->tpl_vars['months'] = new Smarty_variable(waDateTime::getMonthNames(), null, 0);?><?php $_smarty_tpl->tpl_vars['_current_month_index'] = new Smarty_variable(date('n',time()), null, 0);?><section class="t-activity-wrapper break-word" id="t-activity-wrapper"><?php if (!empty($_smarty_tpl->tpl_vars['activity']->value)){?><?php $_smarty_tpl->tpl_vars['_period'] = new Smarty_variable(60*60*24*30, null, 0);?><?php $_smarty_tpl->tpl_vars['_last_datetime'] = new Smarty_variable(time(), null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['_timestamp']->value)){?><?php $_smarty_tpl->tpl_vars['_last_datetime'] = new Smarty_variable($_smarty_tpl->tpl_vars['_timestamp']->value, null, 0);?><?php }?><?php $_smarty_tpl->tpl_vars['_max_id'] = new Smarty_variable(null, null, 0);?><ul class="t-activity unstyled"><?php  $_smarty_tpl->tpl_vars['_activity_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_activity_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['activity']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['_activity_item']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['_activity_item']->iteration=0;
 $_smarty_tpl->tpl_vars['_activity_item']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['_activity_item']->key => $_smarty_tpl->tpl_vars['_activity_item']->value){
$_smarty_tpl->tpl_vars['_activity_item']->_loop = true;
 $_smarty_tpl->tpl_vars['_activity_item']->iteration++;
 $_smarty_tpl->tpl_vars['_activity_item']->index++;
 $_smarty_tpl->tpl_vars['_activity_item']->first = $_smarty_tpl->tpl_vars['_activity_item']->index === 0;
 $_smarty_tpl->tpl_vars['_activity_item']->last = $_smarty_tpl->tpl_vars['_activity_item']->iteration === $_smarty_tpl->tpl_vars['_activity_item']->total;
?><?php if ($_smarty_tpl->tpl_vars['_activity_item']->last){?><?php $_smarty_tpl->tpl_vars['_max_id'] = new Smarty_variable($_smarty_tpl->tpl_vars['_activity_item']->value['id'], null, 0);?><?php }?><?php $_smarty_tpl->tpl_vars['time'] = new Smarty_variable(strtotime($_smarty_tpl->tpl_vars['_activity_item']->value['datetime']), null, 0);?><?php $_smarty_tpl->tpl_vars['current_month_index'] = new Smarty_variable(date('n',$_smarty_tpl->tpl_vars['time']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['before_month_index'] = new Smarty_variable(date('n',$_smarty_tpl->tpl_vars['_last_datetime']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['_long_pause'] = new Smarty_variable(($_smarty_tpl->tpl_vars['_last_datetime']->value-$_smarty_tpl->tpl_vars['time']->value>$_smarty_tpl->tpl_vars['_period']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['_last_datetime'] = new Smarty_variable($_smarty_tpl->tpl_vars['time']->value, null, 0);?><?php if ($_smarty_tpl->tpl_vars['current_month_index']->value!=$_smarty_tpl->tpl_vars['before_month_index']->value||($_smarty_tpl->tpl_vars['_activity_item']->first&&$_smarty_tpl->tpl_vars['current_month_index']->value==$_smarty_tpl->tpl_vars['_current_month_index']->value)){?><li class="t-month-wrapper"><h5><?php echo $_smarty_tpl->tpl_vars['months']->value[date('n',$_smarty_tpl->tpl_vars['time']->value)];?>
 <?php echo date('Y',$_smarty_tpl->tpl_vars['time']->value);?>
</h5></li><?php }?><li class="t-activity-item small<?php if (!empty($_smarty_tpl->tpl_vars['_activity_item']->value['is_empty'])){?> is-empty<?php }?><?php if ($_smarty_tpl->tpl_vars['current_month_index']->value!=$_smarty_tpl->tpl_vars['before_month_index']->value){?> is-first<?php }?>" data-id="<?php echo $_smarty_tpl->tpl_vars['_activity_item']->value['id'];?>
" data-timestamp="<?php echo $_smarty_tpl->tpl_vars['_last_datetime']->value;?>
"><p class="t-app-badge"><?php if (!(empty($_smarty_tpl->tpl_vars['_activity_item']->value['app'])||empty($_smarty_tpl->tpl_vars['_activity_item']->value['app']['name']))){?><span class="badge" style="background: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['_activity_item']->value['app']['sash_color'])===null||$tmp==='' ? "#aaa" : $tmp);?>
;"><?php echo $_smarty_tpl->tpl_vars['_activity_item']->value['app']['name'];?>
</span><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['_activity_item']->value['datetime'])){?><span class="gray"><?php echo waDateTime::format('humandatetime',$_smarty_tpl->tpl_vars['_activity_item']->value['datetime']);?>
</span><?php }?></p><p><?php if (!empty($_smarty_tpl->tpl_vars['_activity_item']->value['action_name'])){?><span class="gray custom-mr-4"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_activity_item']->value['action_name'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['_activity_item']->value['params_html'])){?><span><?php echo $_smarty_tpl->tpl_vars['_activity_item']->value['params_html'];?>
</span><?php }?></p><span class="t-activity-point" style="background: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['_activity_item']->value['app']['sash_color'])===null||$tmp==='' ? "#aaa" : $tmp);?>
;"></span></li><?php } ?></ul><?php if (($_smarty_tpl->tpl_vars['count']->value==50)){?><div class="t-paging-wrapper flexbox middle" data-max-id="<?php echo $_smarty_tpl->tpl_vars['_max_id']->value;?>
"><i class="fas fa-spinner custom-mr-8 wa-animation-spin speed-1000"></i>Загрузка...</div><?php }?><script>( function($) {if (!$.team) {return;}$.team.app_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
;var $wrapper = $("#t-activity-wrapper");setLast();new ActivityLazyLoading({$wrapper: $wrapper,names: {list: ".t-activity",items: " > li",paging: ".t-paging-wrapper"},user_id: <?php if (!empty($_smarty_tpl->tpl_vars['user_id']->value)){?><?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
<?php }else{ ?>false<?php }?>,onLoad: setLast});function setLast() {var first_class = "is-first",last_class = "is-last";var $items = $wrapper.find(".t-activity-item." + first_class);$items.each( function() {var $prev = $(this).prev().prev();if (!$prev.hasClass(last_class)) {$prev.addClass(last_class);}});}})(jQuery);</script><?php }else{ ?><p class="custom-mt-8">В ленте этого сотрудника еще нет событий.</p><?php }?></section>
<?php }} ?>