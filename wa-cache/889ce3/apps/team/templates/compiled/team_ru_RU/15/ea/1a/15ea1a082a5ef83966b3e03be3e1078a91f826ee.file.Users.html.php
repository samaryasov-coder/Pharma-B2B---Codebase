<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:43
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/users/Users.html" */ ?>
<?php /*%%SmartyHeaderCode:4687385966a889d134bcbf3-85266043%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '15ea1a082a5ef83966b3e03be3e1078a91f826ee' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/users/Users.html',
      1 => 1679048654,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4687385966a889d134bcbf3-85266043',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'contacts' => 0,
    'wa_app_url' => 0,
    'sort' => 0,
    '_sort_list' => 0,
    'wa' => 0,
    '_id' => 0,
    '_name' => 0,
    'online' => 0,
    'offline' => 0,
    'user' => 0,
    'user_data' => 0,
    '_badge_status_styles' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d134dafd0_17425136',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d134dafd0_17425136')) {function content_6a889d134dafd0_17425136($_smarty_tpl) {?><div class="t-users-page content article break-word" id="t-users-page"><div class="t-content-body article-body"><header class="t-content-header flexbox middle wrap custom-mb-24"><h1 class="wide custom-pr-16">Все сотрудники</h1><?php if (!empty($_smarty_tpl->tpl_vars['contacts']->value)){?><div class="dropdown custom-mt-8 custom-mb-16 small js-sort-by"><?php $_smarty_tpl->tpl_vars['_sort_list'] = new Smarty_variable(array('last_seen'=>'Онлайн','name'=>'По алфавиту','signed_up'=>'По дате регистрации'), null, 0);?><a class="dropdown-toggle button light-gray nowrap" href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?sort=<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['sort']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-disable-routing><?php if (isset($_smarty_tpl->tpl_vars['_sort_list']->value[$_smarty_tpl->tpl_vars['sort']->value])){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_sort_list']->value[$_smarty_tpl->tpl_vars['sort']->value], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?>&mdash;<?php }?></a><div class="dropdown-body<?php if (!$_smarty_tpl->tpl_vars['wa']->value->isMobile()){?> right<?php }?>"><ul class="menu"><?php  $_smarty_tpl->tpl_vars['_name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_name']->_loop = false;
 $_smarty_tpl->tpl_vars['_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_sort_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_name']->key => $_smarty_tpl->tpl_vars['_name']->value){
$_smarty_tpl->tpl_vars['_name']->_loop = true;
 $_smarty_tpl->tpl_vars['_id']->value = $_smarty_tpl->tpl_vars['_name']->key;
?><li class="t-menu-item <?php if ($_smarty_tpl->tpl_vars['sort']->value==$_smarty_tpl->tpl_vars['_id']->value){?>selected<?php }?>"><a href="<?php echo teamHelper::getUrl('sort',$_smarty_tpl->tpl_vars['_id']->value);?>
"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_name']->value, ENT_QUOTES, 'UTF-8', true);?>
</a></li><?php } ?></ul></div></div><?php }?></header><?php if (!empty($_smarty_tpl->tpl_vars['contacts']->value)){?><?php if (!empty($_smarty_tpl->tpl_vars['online']->value)&&$_smarty_tpl->tpl_vars['sort']->value==='last_seen'){?><?php echo $_smarty_tpl->getSubTemplate ("./Users.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('contacts'=>$_smarty_tpl->tpl_vars['online']->value), 0);?>
<?php }elseif($_smarty_tpl->tpl_vars['sort']->value!=='last_seen'){?><?php echo $_smarty_tpl->getSubTemplate ("./Users.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('contacts'=>$_smarty_tpl->tpl_vars['contacts']->value), 0);?>
<?php }else{ ?><p class="t-description">Нет сотрудников онлайн.</p><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['offline']->value)&&$_smarty_tpl->tpl_vars['sort']->value==='last_seen'){?><h3 class="t-header">Офлайн</h3><ul class="list t-offline-list js-users-list-offline"><?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['offline']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value){
$_smarty_tpl->tpl_vars['user']->_loop = true;
?><?php $_smarty_tpl->tpl_vars['user_data'] = new Smarty_variable(waUser::getNameAndStatus($_smarty_tpl->tpl_vars['user']->value,true), null, 0);?><li class="t-user-wrapper flexbox middle space-16 custom-py-12<?php if ($_smarty_tpl->tpl_vars['user']->value['is_user']>=1&&!$_smarty_tpl->tpl_vars['wa']->value->isMobile()){?> js-move-user<?php }?>" data-user-id="<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
" data-update-datetime="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['user']->value['update_datetime'])===null||$tmp==='' ? '' : $tmp);?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
u/<?php echo urlencode(htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['login'], ENT_QUOTES, 'UTF-8', true));?>
/" class="image custom-mb-auto" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user_data']->value['formatted_user_name'], ENT_QUOTES, 'UTF-8', true);?>
"><span class="userpic icon size-72 valign-middle" style="background-image: url('<?php echo $_smarty_tpl->tpl_vars['user']->value['photo_url_96'];?>
'); background-size: cover;"><?php if (!empty($_smarty_tpl->tpl_vars['user_data']->value['user']['birth_day'])&&$_smarty_tpl->tpl_vars['user_data']->value['user']['birth_day']==waDateTime::format('j')&&$_smarty_tpl->tpl_vars['user_data']->value['user']['birth_month']==waDateTime::format('n')){?><span class="userstatus birthday t-user-birthday-icon" title="<?php echo sprintf(_ws('%s\'s birthday — %s'),$_smarty_tpl->tpl_vars['user_data']->value['formatted_user_name'],$_smarty_tpl->tpl_vars['user_data']->value['user_birthday_str']);?>
"><i class="fas fa-birthday-cake"></i></span><?php }?></span></a><div class="details"><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
u/<?php echo urlencode(htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['login'], ENT_QUOTES, 'UTF-8', true));?>
/" class="t-name bold custom-my-0" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user_data']->value['formatted_user_name'], ENT_QUOTES, 'UTF-8', true);?>
&#32;<span class="small semibold gray">@<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['login'], ENT_QUOTES, 'UTF-8', true);?>
</span></a><div><?php if (!empty($_smarty_tpl->tpl_vars['user_data']->value['user']['_event'])){?><?php $_smarty_tpl->tpl_vars['_badge_status_styles'] = new Smarty_variable('', null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['user_data']->value['user']['_event']['status_bg_color'])){?><?php $_smarty_tpl->tpl_vars['_badge_status_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['user_data']->value['user']['_event']['status_font_color'])."; background: ".((string)$_smarty_tpl->tpl_vars['user_data']->value['user']['_event']['status_bg_color']).";", null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_badge_status_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['user_data']->value['user']['_event']['font_color'])."; background: ".((string)$_smarty_tpl->tpl_vars['user_data']->value['user']['_event']['bg_color']).";", null, 0);?><?php }?><span class="badge user small custom-mr-4" style="<?php echo $_smarty_tpl->tpl_vars['_badge_status_styles']->value;?>
"><?php if ($_smarty_tpl->tpl_vars['user_data']->value['user']['_event']['icon']){?><i class="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user_data']->value['user']['_event']['icon'], ENT_QUOTES, 'UTF-8', true);?>
"></i><?php }else{ ?><i class="fas fa-calendar-alt"></i><?php }?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user_data']->value['user']['_event']['summary'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['jobtitle'])){?><span class="t-job small custom-my-0"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['jobtitle'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php }?><p class="hint custom-my-0"><?php if (empty($_smarty_tpl->tpl_vars['user']->value['last_datetime'])){?><?php echo sprintf_wp('Joined %s',waDateTime::format('humandate',$_smarty_tpl->tpl_vars['user']->value['create_datetime']));?>
<?php }else{ ?>Последний визит&nbsp<?php echo $_smarty_tpl->tpl_vars['user']->value['last_datetime_formatted'];?>
<?php }?></p></div></div></li><?php } ?></ul><?php }?><?php }else{ ?><p class="t-description">Нет сотрудников.</p><?php }?></div><script>( function($) {$.team.setTitle("Сотрудники");$.team.sidebar.updateCount("<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
", <?php echo count($_smarty_tpl->tpl_vars['contacts']->value);?>
);new UserList({$wrapper: $(".js-users-list")});new UserList({$wrapper: $(".js-users-list-offline")});<?php if (!empty($_smarty_tpl->tpl_vars['contacts']->value)){?>$(".js-sort-by").waDropdown({items: ".menu > li > a"});<?php }?>})(jQuery);</script></div>
<?php }} ?>