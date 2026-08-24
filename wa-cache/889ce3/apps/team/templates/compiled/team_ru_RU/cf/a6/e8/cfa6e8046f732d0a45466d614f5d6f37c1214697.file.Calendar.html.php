<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/sidebarWidgets/Calendar.html" */ ?>
<?php /*%%SmartyHeaderCode:18002919266a889d14be32a0-01537055%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cfa6e8046f732d0a45466d614f5d6f37c1214697' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/sidebarWidgets/Calendar.html',
      1 => 1679410623,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18002919266a889d14be32a0-01537055',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'calendar_widget' => 0,
    'calendar_widget_data' => 0,
    'days_data' => 0,
    '_day_data' => 0,
    'week_data' => 0,
    '__event_groups' => 0,
    '__event_group' => 0,
    '__e' => 0,
    '_day_event' => 0,
    '_max_day_count' => 0,
    '_day_date_formatted' => 0,
    '__event' => 0,
    '_calendar' => 0,
    '__user' => 0,
    '_is_birthday' => 0,
    'square_corner_side' => 0,
    '__events_in_week' => 0,
    '_now' => 0,
    '_badge_styles' => 0,
    'title' => 0,
    '_full_count' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d14c09fb0_18143969',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d14c09fb0_18143969')) {function content_6a889d14c09fb0_18143969($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['calendar_widget_data'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['calendar_widget']->value['data'][0])===null||$tmp==='' ? array() : $tmp), null, 0);?><?php $_smarty_tpl->tpl_vars['week_data'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['calendar_widget_data']->value['events'])===null||$tmp==='' ? array() : $tmp), null, 0);?><?php $_smarty_tpl->tpl_vars['days_data'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['calendar_widget_data']->value['days_data'])===null||$tmp==='' ? array() : $tmp), null, 0);?><div class="t-calendar-wrapper"><?php if (!empty($_smarty_tpl->tpl_vars['calendar_widget_data']->value)){?><?php $_smarty_tpl->tpl_vars['_now'] = new Smarty_variable(waDateTime::date('Y-m-d'), null, 0);?><ul class="t-profile-sidebar-calendar-widget js-sidebar-profile-dialog cursor-pointer"id="t-calendar-widget"data-dialog-header="Календарь"data-dialog-header="Календарь"data-dialog-team-calendar="Календарь команды"data-dialog-width="1000px"data-section-id="calendar"><?php $_smarty_tpl->tpl_vars['_day_event'] = new Smarty_variable(array(), null, 0);?><?php  $_smarty_tpl->tpl_vars['_day_data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_day_data']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['days_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['_day_data']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['_day_data']->key => $_smarty_tpl->tpl_vars['_day_data']->value){
$_smarty_tpl->tpl_vars['_day_data']->_loop = true;
 $_smarty_tpl->tpl_vars['_day_data']->index++;
?><?php $_smarty_tpl->tpl_vars['_day_date_formatted'] = new Smarty_variable(waDateTime::date('Y-m-d',$_smarty_tpl->tpl_vars['_day_data']->value['date']), null, 0);?><?php $_smarty_tpl->tpl_vars['__event_groups'] = new Smarty_variable(ifempty($_smarty_tpl->tpl_vars['week_data']->value,array()), null, 0);?><?php $_smarty_tpl->tpl_vars['__event'] = new Smarty_variable(array(), null, 0);?><?php $_smarty_tpl->tpl_vars['__events_in_week'] = new Smarty_variable(array("all"=>array(array(),array(),array(),array(),array(),array(),array())), null, 0);?><?php if ($_smarty_tpl->tpl_vars['__event_groups']->value){?><?php $_smarty_tpl->tpl_vars['__event_groups'] = new Smarty_variable(array_reverse($_smarty_tpl->tpl_vars['__event_groups']->value), null, 0);?><?php  $_smarty_tpl->tpl_vars['__event_group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['__event_group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['__event_groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['__event_group']->key => $_smarty_tpl->tpl_vars['__event_group']->value){
$_smarty_tpl->tpl_vars['__event_group']->_loop = true;
?><?php  $_smarty_tpl->tpl_vars['__e'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['__e']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['__event_group']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['__e']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['__e']->key => $_smarty_tpl->tpl_vars['__e']->value){
$_smarty_tpl->tpl_vars['__e']->_loop = true;
 $_smarty_tpl->tpl_vars['__e']->index++;
?><?php if (isset($_smarty_tpl->tpl_vars['__e']->value['colspan'])){?><?php $_smarty_tpl->createLocalArrayVariable('__events_in_week', null, 0);
$_smarty_tpl->tpl_vars['__events_in_week']->value["all"][$_smarty_tpl->tpl_vars['__e']->index][] = $_smarty_tpl->tpl_vars['__e']->value['id'];?><?php }?><?php if (isset($_smarty_tpl->tpl_vars['__e']->value['day_count'])){?><?php $_smarty_tpl->createLocalArrayVariable('_day_event', null, 0);
$_smarty_tpl->tpl_vars['_day_event']->value[$_smarty_tpl->tpl_vars['__e']->value['day_count']][$_smarty_tpl->tpl_vars['__e']->value['date']] = $_smarty_tpl->tpl_vars['__e']->value;?><?php }?><?php } ?><?php } ?><?php $_smarty_tpl->tpl_vars['_max_day_count'] = new Smarty_variable(max(array_keys($_smarty_tpl->tpl_vars['_day_event']->value)), null, 0);?><?php if (isset($_smarty_tpl->tpl_vars['_day_event']->value[$_smarty_tpl->tpl_vars['_max_day_count']->value][$_smarty_tpl->tpl_vars['_day_date_formatted']->value])){?><?php $_smarty_tpl->tpl_vars['__event'] = new Smarty_variable($_smarty_tpl->tpl_vars['_day_event']->value[$_smarty_tpl->tpl_vars['_max_day_count']->value][$_smarty_tpl->tpl_vars['_day_date_formatted']->value], null, 0);?><?php }elseif(isset($_smarty_tpl->tpl_vars['_day_event']->value[1][$_smarty_tpl->tpl_vars['_day_date_formatted']->value])){?><?php $_smarty_tpl->tpl_vars['__event'] = new Smarty_variable($_smarty_tpl->tpl_vars['_day_event']->value[1][$_smarty_tpl->tpl_vars['_day_date_formatted']->value], null, 0);?><?php }?><?php if ($_smarty_tpl->tpl_vars['__event']->value){?><?php $_smarty_tpl->tpl_vars['_calendar'] = new Smarty_variable(array("id"=>null,"bg_color"=>"#f00","font_color"=>"#fff"), null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['__event']->value['calendar_id'])&&!empty($_smarty_tpl->tpl_vars['calendar_widget']->value['calendars'][$_smarty_tpl->tpl_vars['__event']->value['calendar_id']])){?><?php $_smarty_tpl->tpl_vars['_calendar'] = new Smarty_variable($_smarty_tpl->tpl_vars['calendar_widget']->value['calendars'][$_smarty_tpl->tpl_vars['__event']->value['calendar_id']], null, 0);?><?php }?><?php $_smarty_tpl->tpl_vars['_is_birthday'] = new Smarty_variable(($_smarty_tpl->tpl_vars['__event']->value['calendar_id']=="birthday"), null, 0);?><?php $_smarty_tpl->tpl_vars['__user'] = new Smarty_variable(null, null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['calendar_widget']->value['users'][$_smarty_tpl->tpl_vars['__event']->value['contact_id']])){?><?php $_smarty_tpl->tpl_vars['__user'] = new Smarty_variable($_smarty_tpl->tpl_vars['calendar_widget']->value['users'][$_smarty_tpl->tpl_vars['__event']->value['contact_id']], null, 0);?><?php }?><?php $_smarty_tpl->tpl_vars['_badge_styles'] = new Smarty_variable('', null, 0);?><?php if ($_smarty_tpl->tpl_vars['__event']->value['is_status']){?><?php if ($_smarty_tpl->tpl_vars['_calendar']->value['status_bg_color']){?><?php $_smarty_tpl->tpl_vars['_badge_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['status_font_color'])."; background: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['status_bg_color']).";", null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_badge_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['font_color'])."; background: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['bg_color']).";", null, 0);?><?php }?><?php }else{ ?><?php if (!empty($_smarty_tpl->tpl_vars['_calendar']->value['status_bg_color'])){?><?php $_smarty_tpl->tpl_vars['_badge_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['font_color'])."; background: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['bg_color']).";", null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_badge_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['_calendar']->value['bg_color'])."; background: transparent; box-shadow: inset 0 0 0 1px currentColor;", null, 0);?><?php }?><?php }?><?php if ($_smarty_tpl->tpl_vars['__user']->value&&$_smarty_tpl->tpl_vars['_is_birthday']->value){?><?php $_smarty_tpl->tpl_vars['_badge_styles'] = new Smarty_variable("color: var(--white); background-image: linear-gradient(90deg, #FA5959 0%, #E419AB 100%);", null, 0);?><?php }?><?php $_smarty_tpl->tpl_vars['square_corner_side'] = new Smarty_variable('', null, 0);?><?php if ($_smarty_tpl->tpl_vars['__event']->value['day_count']>1){?><?php $_smarty_tpl->tpl_vars['square_corner_side'] = new Smarty_variable(' square-corner', null, 0);?><?php if (waDateTime::date('Y-m-d',$_smarty_tpl->tpl_vars['__event']->value['start'])==$_smarty_tpl->tpl_vars['__event']->value['date']){?><?php $_smarty_tpl->tpl_vars['square_corner_side'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['square_corner_side']->value)."-right", null, 0);?><?php }?><?php if (waDateTime::date('Y-m-d',$_smarty_tpl->tpl_vars['__event']->value['end'])==$_smarty_tpl->tpl_vars['__event']->value['date']){?><?php $_smarty_tpl->tpl_vars['square_corner_side'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['square_corner_side']->value)."-left", null, 0);?><?php }?><?php }?><?php $_smarty_tpl->tpl_vars['_full_count'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['__events_in_week']->value["all"][$_smarty_tpl->tpl_vars['_day_data']->index]), null, 0);?><?php }?><?php }?><li class="t-week-row-widget<?php if ($_smarty_tpl->tpl_vars['_now']->value===$_smarty_tpl->tpl_vars['_day_date_formatted']->value){?> is-active<?php }?>"><span class="t-week-row-widget-day"><?php echo _ws(waDateTime::date('D',$_smarty_tpl->tpl_vars['_day_data']->value['date']));?>
</span><span class="t-week-row-widget-number"><?php echo waDateTime::date('j',$_smarty_tpl->tpl_vars['_day_data']->value['date']);?>
</span><?php if ($_smarty_tpl->tpl_vars['__event']->value){?><span class="t-week-row-widget-badge<?php echo $_smarty_tpl->tpl_vars['square_corner_side']->value;?>
"<?php if (!$_smarty_tpl->tpl_vars['_is_birthday']->value){?> data-wa-tooltip-content="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['__event']->value['summary'], ENT_QUOTES, 'UTF-8', true);?>
"<?php }?> style="<?php echo $_smarty_tpl->tpl_vars['_badge_styles']->value;?>
"><?php if ($_smarty_tpl->tpl_vars['_is_birthday']->value&&$_smarty_tpl->tpl_vars['__user']->value){?><?php $_smarty_tpl->tpl_vars['title'] = new Smarty_variable(sprintf(_w("%s's birthday — %s"),htmlspecialchars((string)$_smarty_tpl->tpl_vars['__user']->value['name'], ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['__event']->value['birthday_str']), null, 0);?><i class="fas fa-birthday-cake" title="<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
"></i><?php }?><?php if (!$_smarty_tpl->tpl_vars['_is_birthday']->value){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['__event']->value['summary'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></span><?php if ($_smarty_tpl->tpl_vars['_full_count']->value>1){?><span class="badge small gray custom-ml-8">+ <?php echo $_smarty_tpl->tpl_vars['_full_count']->value-1;?>
</span><?php }?><?php }?></li><?php } ?></ul><script>( function($) {$("#t-calendar-widget").find('[data-wa-tooltip-content]').waTooltip({placement: 'left'});})(jQuery);</script><?php }else{ ?><p>Данные повреждены</p><?php }?></div>
<?php }} ?>