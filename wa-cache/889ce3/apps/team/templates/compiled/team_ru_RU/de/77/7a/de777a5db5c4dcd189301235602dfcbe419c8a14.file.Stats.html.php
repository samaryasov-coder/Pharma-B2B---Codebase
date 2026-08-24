<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/sidebarWidgets/Stats.html" */ ?>
<?php /*%%SmartyHeaderCode:17854325866a889d14add491-21087116%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'de777a5db5c4dcd189301235602dfcbe419c8a14' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/sidebarWidgets/Stats.html',
      1 => 1637225471,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17854325866a889d14add491-21087116',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'is_drawer' => 0,
    'stats_widget_data' => 0,
    'total_events_count' => 0,
    '_is_drawer' => 0,
    'wa_url' => 0,
    'wa' => 0,
    'wa_app_static_url' => 0,
    'wa_app_url' => 0,
    'user' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d14ae4fd3_02839112',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d14ae4fd3_02839112')) {function content_6a889d14ae4fd3_02839112($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['_is_drawer'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['is_drawer']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['total_events_count'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['stats_widget_data']->value['total_events_count'])===null||$tmp==='' ? 0 : $tmp), null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['total_events_count']->value>0){?>
    <div class="t-stats-wrapper js-sidebar-profile-dialog cursor-pointer"><div class="js-stats-wrapper<?php if ($_smarty_tpl->tpl_vars['_is_drawer']->value){?>-drawer<?php }?>"><div class="t-graph-wrapper js-is-stats-widget" id="t-graph-wrapper"><div class="t-graph custom-p-0 bordered-bottom" style="height:100px"><div class="spinner custom-p-16" style="position:absolute;top:calc(50% - 2rem);left:calc(50% - 2rem);"></div></div></div></div></div>
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/d3/d3.min.js?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script>
    
    <script src="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/profile.stats.js?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"></script>
    <script>
        (function($) {
            new ProfileStatistic({
                $wrapper: $(".js-stats-wrapper<?php if ($_smarty_tpl->tpl_vars['_is_drawer']->value){?>-drawer<?php }?>"),
                graphData: <?php echo json_encode($_smarty_tpl->tpl_vars['stats_widget_data']->value['chart_data']);?>
,
                app_url: "<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
",
                app_id: false,
                group_by: false,
                timeframe: 'custom',
                start_date: "<?php echo waDateTime::date('Y-m-d',strtotime("-14 day"));?>
",
                end_date: "<?php echo waDateTime::date('Y-m-d');?>
",
                contact_id: <?php echo json_encode($_smarty_tpl->tpl_vars['user']->value['id']);?>
,
                locales: { },
                is_widget: true,
            });
        })(jQuery);
    </script>
<?php }?>
<?php }} ?>