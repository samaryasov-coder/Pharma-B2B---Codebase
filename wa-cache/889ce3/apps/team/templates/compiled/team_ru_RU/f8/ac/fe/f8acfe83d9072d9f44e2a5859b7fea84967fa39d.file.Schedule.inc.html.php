<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/schedule/Schedule.inc.html" */ ?>
<?php /*%%SmartyHeaderCode:11980570936a889d14c0fea5-85568297%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f8acfe83d9072d9f44e2a5859b7fea84967fa39d' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/schedule/Schedule.inc.html',
      1 => 1676976922,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11980570936a889d14c0fea5-85568297',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'context' => 0,
    'wa' => 0,
    'selected_calendar_id' => 0,
    'calendars' => 0,
    'selected_user_id' => 0,
    'users' => 0,
    'data' => 0,
    'hide_labels' => 0,
    '_weekday_names' => 0,
    '_weekday_name' => 0,
    '_week' => 0,
    'period_start' => 0,
    '_current_month' => 0,
    '_day' => 0,
    '_now' => 0,
    '_is_profile' => 0,
    '_is_current_month' => 0,
    '_day_classes' => 0,
    '_event_groups' => 0,
    '_event_group' => 0,
    '_event' => 0,
    '_user_id' => 0,
    '_limit' => 0,
    '_calendar' => 0,
    '_can_edit' => 0,
    '_is_birthday' => 0,
    '_classes' => 0,
    '_styles' => 0,
    '_user' => 0,
    'title' => 0,
    '_is_part_of_day' => 0,
    '_styles2' => 0,
    '_events_in_week' => 0,
    '_full_count' => 0,
    '_view_count' => 0,
    '_is_admin' => 0,
    'selected_user' => 0,
    'selected_calendar' => 0,
    'period_end' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d14c60fa9_09573358',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d14c60fa9_09573358')) {function content_6a889d14c60fa9_09573358($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_join')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.join.php';
?><?php $_smarty_tpl->tpl_vars['_is_profile'] = new Smarty_variable((!empty($_smarty_tpl->tpl_vars['context']->value)&&$_smarty_tpl->tpl_vars['context']->value=="profile"), null, 0);?><?php $_smarty_tpl->tpl_vars['_user_id'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user("id"), null, 0);?><?php $_smarty_tpl->tpl_vars['_is_admin'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin($_smarty_tpl->tpl_vars['wa']->value->app()), null, 0);?><?php $_smarty_tpl->tpl_vars['_limit'] = new Smarty_variable(2, null, 0);?><?php if ($_smarty_tpl->tpl_vars['selected_calendar_id']->value&&!empty($_smarty_tpl->tpl_vars['calendars']->value[$_smarty_tpl->tpl_vars['selected_calendar_id']->value])){?><?php $_smarty_tpl->tpl_vars['selected_calendar'] = new Smarty_variable($_smarty_tpl->tpl_vars['calendars']->value[$_smarty_tpl->tpl_vars['selected_calendar_id']->value], null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['selected_calendar'] = new Smarty_variable($_smarty_tpl->tpl_vars['calendars']->value["all"], null, 0);?><?php }?><?php if ($_smarty_tpl->tpl_vars['selected_user_id']->value&&!empty($_smarty_tpl->tpl_vars['users']->value[$_smarty_tpl->tpl_vars['selected_user_id']->value])){?><?php $_smarty_tpl->tpl_vars['selected_user'] = new Smarty_variable($_smarty_tpl->tpl_vars['users']->value[$_smarty_tpl->tpl_vars['selected_user_id']->value], null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['selected_user'] = new Smarty_variable($_smarty_tpl->tpl_vars['users']->value["all"], null, 0);?><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['data']->value)){?><div class="t-calendar-wrapper"><?php if (empty($_smarty_tpl->tpl_vars['hide_labels']->value)){?><div class="t-labels-wrapper"><table><tr><?php $_smarty_tpl->tpl_vars['_weekday_names'] = new Smarty_variable(waDateTime::getWeekdayNames('ucfirst',false), null, 0);?><?php  $_smarty_tpl->tpl_vars['_weekday_name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_weekday_name']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_weekday_names']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_weekday_name']->key => $_smarty_tpl->tpl_vars['_weekday_name']->value){
$_smarty_tpl->tpl_vars['_weekday_name']->_loop = true;
?><td class="t-column"><?php echo $_smarty_tpl->tpl_vars['_weekday_name']->value;?>
</td><?php } ?></tr></table></div><?php }?><div class="t-calendar-block" id="t-calendar-block"><?php $_smarty_tpl->tpl_vars['_now'] = new Smarty_variable(waDateTime::format('Y-m-d'), null, 0);?><?php  $_smarty_tpl->tpl_vars['_week'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_week']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_week']->key => $_smarty_tpl->tpl_vars['_week']->value){
$_smarty_tpl->tpl_vars['_week']->_loop = true;
?><div class="t-week-row"><table class="t-week-background"><tr><?php  $_smarty_tpl->tpl_vars['_day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_week']->value['days_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['_day']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['_day']->key => $_smarty_tpl->tpl_vars['_day']->value){
$_smarty_tpl->tpl_vars['_day']->_loop = true;
 $_smarty_tpl->tpl_vars['_day']->index++;
?><?php $_smarty_tpl->tpl_vars['_current_month'] = new Smarty_variable(date('Y-m'), null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['period_start']->value)){?><?php $_smarty_tpl->tpl_vars['_current_month'] = new Smarty_variable(date('Y-m',strtotime($_smarty_tpl->tpl_vars['period_start']->value)), null, 0);?><?php }?><?php $_smarty_tpl->tpl_vars['_is_current_month'] = new Smarty_variable(($_smarty_tpl->tpl_vars['_current_month']->value===date('Y-m',strtotime($_smarty_tpl->tpl_vars['_day']->value['date']))), null, 0);?><?php $_smarty_tpl->tpl_vars['_day_classes'] = new Smarty_variable(array(), null, 0);?><?php if (($_smarty_tpl->tpl_vars['_day']->value['date']==((string)$_smarty_tpl->tpl_vars['_now']->value)." 00:00:00")){?><?php $_smarty_tpl->createLocalArrayVariable('_day_classes', null, 0);
$_smarty_tpl->tpl_vars['_day_classes']->value[] = "is-active";?><?php }elseif(!$_smarty_tpl->tpl_vars['_is_profile']->value&&!$_smarty_tpl->tpl_vars['_is_current_month']->value){?><?php $_smarty_tpl->createLocalArrayVariable('_day_classes', null, 0);
$_smarty_tpl->tpl_vars['_day_classes']->value[] = "is-unactive";?><?php }?><td class="t-column"><div class="t-day-ornament <?php echo smarty_modifier_join($_smarty_tpl->tpl_vars['_day_classes']->value,'');?>
" data-date="<?php echo $_smarty_tpl->tpl_vars['_day']->value['date'];?>
"></div></td><?php } ?></tr></table><table class="t-week-table"><tr><?php  $_smarty_tpl->tpl_vars['_day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_week']->value['days_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['_day']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['_day']->key => $_smarty_tpl->tpl_vars['_day']->value){
$_smarty_tpl->tpl_vars['_day']->_loop = true;
 $_smarty_tpl->tpl_vars['_day']->index++;
?><td class="t-column"><div class="t-day-wrapper"><?php echo waDateTime::date('j',$_smarty_tpl->tpl_vars['_day']->value['date']);?>
</div></td><?php } ?></tr><?php $_smarty_tpl->tpl_vars['_event_groups'] = new Smarty_variable(ifempty($_smarty_tpl->tpl_vars['_week']->value['events'],array()), null, 0);?><?php $_smarty_tpl->tpl_vars['_count'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['_event_groups']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['_events_in_week'] = new Smarty_variable(array("view"=>array(array(),array(),array(),array(),array(),array(),array()),"all"=>array(array(),array(),array(),array(),array(),array(),array())), null, 0);?><?php  $_smarty_tpl->tpl_vars['_event_group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_event_group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_event_groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['_event_group']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['_event_group']->key => $_smarty_tpl->tpl_vars['_event_group']->value){
$_smarty_tpl->tpl_vars['_event_group']->_loop = true;
 $_smarty_tpl->tpl_vars['_event_group']->iteration++;
?><tr><?php  $_smarty_tpl->tpl_vars['_event'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_event']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_event_group']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['_event']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['_event']->key => $_smarty_tpl->tpl_vars['_event']->value){
$_smarty_tpl->tpl_vars['_event']->_loop = true;
 $_smarty_tpl->tpl_vars['_event']->index++;
?><?php $_smarty_tpl->tpl_vars['_is_my_event'] = new Smarty_variable((!empty($_smarty_tpl->tpl_vars['_event']->value['contact_id'])&&$_smarty_tpl->tpl_vars['_event']->value['contact_id']==$_smarty_tpl->tpl_vars['_user_id']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['_can_edit'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['_event']->value['can_edit']), null, 0);?><?php $_smarty_tpl->tpl_vars['_calendar'] = new Smarty_variable(array("id"=>null,"bg_color"=>"#f00","font_color"=>"#fff"), null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['_event']->value['calendar_id'])&&!empty($_smarty_tpl->tpl_vars['calendars']->value[$_smarty_tpl->tpl_vars['_event']->value['calendar_id']])){?><?php $_smarty_tpl->tpl_vars['_calendar'] = new Smarty_variable($_smarty_tpl->tpl_vars['calendars']->value[$_smarty_tpl->tpl_vars['_event']->value['calendar_id']], null, 0);?><?php }?><?php if ($_smarty_tpl->tpl_vars['_event_group']->iteration<=$_smarty_tpl->tpl_vars['_limit']->value){?><?php if (isset($_smarty_tpl->tpl_vars['_event']->value['colspan'])){?><?php if ($_smarty_tpl->tpl_vars['_event']->value['colspan']>0){?><?php $_smarty_tpl->tpl_vars['_is_birthday'] = new Smarty_variable(($_smarty_tpl->tpl_vars['_event']->value['calendar_id']=="birthday"), null, 0);?><?php $_smarty_tpl->tpl_vars['_is_part_of_day'] = new Smarty_variable((!$_smarty_tpl->tpl_vars['_event']->value['is_allday']&&$_smarty_tpl->tpl_vars['_event']->value['colspan']==1), null, 0);?><?php $_smarty_tpl->tpl_vars['_styles'] = new Smarty_variable(array(), null, 0);?><?php if ($_smarty_tpl->tpl_vars['_event']->value['is_status']){?><?php if ($_smarty_tpl->tpl_vars['_calendar']->value['status_bg_color']){?><?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "color: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['status_font_color']).";";?><?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "background: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['status_bg_color']).";";?><?php }else{ ?><?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "color: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['font_color']).";";?><?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "background: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['bg_color']).";";?><?php }?><?php }else{ ?><?php if (!empty($_smarty_tpl->tpl_vars['_calendar']->value['status_bg_color'])){?><?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "color: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['font_color']).";";?><?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "background: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['bg_color']).";";?><?php }else{ ?><?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "color: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['bg_color']).";";?><?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "background: transparent;";?><?php $_smarty_tpl->createLocalArrayVariable('_styles', null, 0);
$_smarty_tpl->tpl_vars['_styles']->value[] = "box-shadow: inset 0 0 0 1px currentColor;";?><?php }?><?php }?><?php $_smarty_tpl->tpl_vars['_classes'] = new Smarty_variable(array(), null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['_event']->value['id'])&&!empty($_smarty_tpl->tpl_vars['_calendar']->value['id'])){?><?php $_smarty_tpl->createLocalArrayVariable('_classes', null, 0);
$_smarty_tpl->tpl_vars['_classes']->value[] = "js-view-event";?><?php }?><?php if ($_smarty_tpl->tpl_vars['_event']->value['colspan']==1){?><?php $_smarty_tpl->createLocalArrayVariable('_classes', null, 0);
$_smarty_tpl->tpl_vars['_classes']->value[] = "is-single";?><?php }?><?php if ($_smarty_tpl->tpl_vars['_event']->value['is_status']){?><?php $_smarty_tpl->createLocalArrayVariable('_classes', null, 0);
$_smarty_tpl->tpl_vars['_classes']->value[] = "is-status";?><?php }else{ ?><?php $_smarty_tpl->createLocalArrayVariable('_classes', null, 0);
$_smarty_tpl->tpl_vars['_classes']->value[] = "is-event";?><?php }?><td class="t-column" colspan="<?php echo $_smarty_tpl->tpl_vars['_event']->value['colspan'];?>
"><div class="t-event-wrapper<?php if ($_smarty_tpl->tpl_vars['_can_edit']->value&&!$_smarty_tpl->tpl_vars['_is_birthday']->value){?> js-move-event<?php }?><?php if ($_smarty_tpl->tpl_vars['_event']->value['day_count']>$_smarty_tpl->tpl_vars['_event']->value['colspan']){?> square-corner<?php }?>"<?php if (!empty($_smarty_tpl->tpl_vars['_event']->value['id'])){?>data-id="<?php echo $_smarty_tpl->tpl_vars['_event']->value['id'];?>
"<?php }?><?php if ($_smarty_tpl->tpl_vars['_can_edit']->value&&!$_smarty_tpl->tpl_vars['_is_birthday']->value&&!empty($_smarty_tpl->tpl_vars['_event']->value['day_count'])&&$_smarty_tpl->tpl_vars['_event']->value['day_count']>1){?>data-day-count="<?php echo $_smarty_tpl->tpl_vars['_event']->value['day_count'];?>
"data-move-hint="<?php echo _w('%d day','%d days',$_smarty_tpl->tpl_vars['_event']->value['day_count']);?>
"<?php }?>><?php $_smarty_tpl->tpl_vars['_user'] = new Smarty_variable(null, null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['users']->value[$_smarty_tpl->tpl_vars['_event']->value['contact_id']])){?><?php $_smarty_tpl->tpl_vars['_user'] = new Smarty_variable($_smarty_tpl->tpl_vars['users']->value[$_smarty_tpl->tpl_vars['_event']->value['contact_id']], null, 0);?><?php }?><div class="t-event-block <?php echo smarty_modifier_join($_smarty_tpl->tpl_vars['_classes']->value," ");?>
" style="<?php echo smarty_modifier_join($_smarty_tpl->tpl_vars['_styles']->value,'');?>
<?php if ($_smarty_tpl->tpl_vars['_user']->value){?><?php if ($_smarty_tpl->tpl_vars['_is_birthday']->value){?> color: var(--white); background-image: linear-gradient(90deg, #FA5959 0%, #E419AB 100%);<?php }?><?php }?>"><?php if (!empty($_smarty_tpl->tpl_vars['_event']->value['icon'])){?><i class="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_event']->value['icon'], ENT_QUOTES, 'UTF-8', true);?>
"></i><?php }elseif($_smarty_tpl->tpl_vars['_is_birthday']->value&&$_smarty_tpl->tpl_vars['_user']->value){?><?php $_smarty_tpl->tpl_vars['title'] = new Smarty_variable(sprintf(_w("%s's birthday — %s"),htmlspecialchars((string)$_smarty_tpl->tpl_vars['_user']->value['name'], ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['_event']->value['birthday_str']), null, 0);?><i class="fas fa-birthday-cake" title="<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
"></i><?php }?><?php if ($_smarty_tpl->tpl_vars['_user']->value){?><?php if ($_smarty_tpl->tpl_vars['_is_birthday']->value){?><span class="t-login custom-ml-4<?php if ($_smarty_tpl->tpl_vars['_event']->value['colspan']=='1'){?> desktop-and-tablet-only<?php }?>"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_user']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php }elseif(!empty($_smarty_tpl->tpl_vars['_user']->value['photo_url_16'])){?><i class="userpic userpic20" style="background-image: url(<?php echo $_smarty_tpl->tpl_vars['_user']->value['photo_url_16'];?>
)" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_user']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
"></i><?php }?><?php }?><?php if (!$_smarty_tpl->tpl_vars['_is_birthday']->value&&$_smarty_tpl->tpl_vars['_is_part_of_day']->value&&$_smarty_tpl->tpl_vars['_event']->value['start']){?><?php $_smarty_tpl->tpl_vars['_styles2'] = new Smarty_variable(array(), null, 0);?><span class="t-start" style="<?php echo smarty_modifier_join($_smarty_tpl->tpl_vars['_styles2']->value,'');?>
"><?php echo wa_date("H:i",strtotime($_smarty_tpl->tpl_vars['_event']->value['start']));?>
</span><?php }?><?php if (!$_smarty_tpl->tpl_vars['_is_birthday']->value){?><span class="nowrap custom-ml-4<?php if ($_smarty_tpl->tpl_vars['_event']->value['colspan']=='1'){?> desktop-and-tablet-only<?php }?>"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_event']->value['summary'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php }?></div></div></td><?php }?><?php $_smarty_tpl->createLocalArrayVariable('_events_in_week', null, 0);
$_smarty_tpl->tpl_vars['_events_in_week']->value["view"][$_smarty_tpl->tpl_vars['_event']->index][] = $_smarty_tpl->tpl_vars['_event']->value['id'];?><?php }else{ ?><td class="t-column">&nbsp;</td><?php }?><?php }?><?php if (isset($_smarty_tpl->tpl_vars['_event']->value['colspan'])){?><?php $_smarty_tpl->createLocalArrayVariable('_events_in_week', null, 0);
$_smarty_tpl->tpl_vars['_events_in_week']->value["all"][$_smarty_tpl->tpl_vars['_event']->index][] = $_smarty_tpl->tpl_vars['_event']->value['id'];?><?php }?><?php } ?></tr><?php } ?><tr><?php  $_smarty_tpl->tpl_vars['_day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_day']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_week']->value['days_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['_day']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['_day']->key => $_smarty_tpl->tpl_vars['_day']->value){
$_smarty_tpl->tpl_vars['_day']->_loop = true;
 $_smarty_tpl->tpl_vars['_day']->index++;
?><?php $_smarty_tpl->tpl_vars['_view_count'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['_events_in_week']->value["view"][$_smarty_tpl->tpl_vars['_day']->index]), null, 0);?><?php $_smarty_tpl->tpl_vars['_full_count'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['_events_in_week']->value["all"][$_smarty_tpl->tpl_vars['_day']->index]), null, 0);?><td class="t-column"><?php if ($_smarty_tpl->tpl_vars['_full_count']->value>$_smarty_tpl->tpl_vars['_view_count']->value){?><div class="t-action-wrapper align-center"><span class="t-action show-full-days-events" data-date="<?php echo $_smarty_tpl->tpl_vars['_day']->value['date'];?>
" data-events-id="<?php echo smarty_modifier_join($_smarty_tpl->tpl_vars['_events_in_week']->value["all"][$_smarty_tpl->tpl_vars['_day']->index],",");?>
"><span class="desktop-and-tablet-only">+</span> еще <span class="desktop-and-tablet-only"><?php echo $_smarty_tpl->tpl_vars['_full_count']->value-$_smarty_tpl->tpl_vars['_view_count']->value;?>
</span></span></div><?php }?></td><?php } ?></tr></table></div><?php } ?></div><script>( function($) {$.team.calendar = new TeamCalendar({$wrapper: $("#t-calendar-block"),user_id: "<?php echo $_smarty_tpl->tpl_vars['_user_id']->value;?>
",is_profile: <?php if ($_smarty_tpl->tpl_vars['_is_profile']->value){?>true<?php }else{ ?>false<?php }?>,has_right_to_change: <?php if (!$_smarty_tpl->tpl_vars['_is_profile']->value||($_smarty_tpl->tpl_vars['_is_admin']->value||teamUser::canEdit($_smarty_tpl->tpl_vars['selected_user']->value['id']))){?>true<?php }else{ ?>false<?php }?>,selected_user_id: <?php if ($_smarty_tpl->tpl_vars['selected_user']->value['id']>0){?><?php echo $_smarty_tpl->tpl_vars['selected_user']->value['id'];?>
<?php }else{ ?>false<?php }?>,selected_calendar_id: <?php if ($_smarty_tpl->tpl_vars['selected_calendar']->value['id']>0){?><?php echo $_smarty_tpl->tpl_vars['selected_calendar']->value['id'];?>
<?php }else{ ?>false<?php }?>,period_start: <?php if (!empty($_smarty_tpl->tpl_vars['period_start']->value)){?><?php echo json_encode($_smarty_tpl->tpl_vars['period_start']->value);?>
<?php }else{ ?>false<?php }?>,period_end: <?php if (!empty($_smarty_tpl->tpl_vars['period_end']->value)){?><?php echo json_encode($_smarty_tpl->tpl_vars['period_end']->value);?>
<?php }else{ ?>false<?php }?>,locales: {move: "%s дни"}});})(jQuery);</script></div><?php }else{ ?><div class="">Данные повреждены</div><?php }?>
<?php }} ?>