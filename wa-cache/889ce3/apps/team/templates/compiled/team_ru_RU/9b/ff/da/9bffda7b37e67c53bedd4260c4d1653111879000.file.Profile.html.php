<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/Profile.html" */ ?>
<?php /*%%SmartyHeaderCode:16873062536a889d14a32af7-18172126%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9bffda7b37e67c53bedd4260c4d1653111879000' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/Profile.html',
      1 => 1738158327,
      2 => 'file',
    ),
    '810e5c92101062b93287c9d834addd15f7a83aee' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/sidebarWidgets/Access.html',
      1 => 1679048654,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16873062536a889d14a32af7-18172126',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'is_system_profile' => 0,
    'wa' => 0,
    'user' => 0,
    '_user_id' => 0,
    'wa_app_static_url' => 0,
    '_is_my_profile' => 0,
    'context' => 0,
    '_group_id' => 0,
    'cover_thumbnails' => 0,
    'contacts' => 0,
    'c' => 0,
    'wa_app_url' => 0,
    '_login' => 0,
    '_url' => 0,
    'cover_thumbnails_limit' => 0,
    'cover_thumbnail' => 0,
    'can_edit' => 0,
    'unique_id' => 0,
    'backend_profile' => 0,
    '_' => 0,
    'fieldValues' => 0,
    'user_name_formatted' => 0,
    'user_events' => 0,
    'event' => 0,
    '_badge_status_styles' => 0,
    '_event_icon' => 0,
    'groups' => 0,
    'group' => 0,
    'invite' => 0,
    '_is_system_admin' => 0,
    'email' => 0,
    'contactFields' => 0,
    'phone' => 0,
    'key' => 0,
    'profile_editor' => 0,
    'im' => 0,
    '_href' => 0,
    'geocoding' => 0,
    '_is_admin' => 0,
    'author' => 0,
    'contact_create_time' => 0,
    'stats_html' => 0,
    'wa_backend_url' => 0,
    'is_own_profile' => 0,
    'webasyst_id_auth_url' => 0,
    'wa_url' => 0,
    '_profile_object_options' => 0,
    '_context_type' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d14ab6390_64377444',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d14ab6390_64377444')) {function content_6a889d14ab6390_64377444($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.replace.php';
if (!is_callable('smarty_modifier_wa_datetime')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.wa_datetime.php';
?><?php $_smarty_tpl->tpl_vars['is_system_profile'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['is_system_profile']->value)===null||$tmp==='' ? false : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_user_id'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user("id"), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_is_admin'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin($_smarty_tpl->tpl_vars['wa']->value->app()), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_is_system_admin'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_is_my_profile'] = new Smarty_variable((!empty($_smarty_tpl->tpl_vars['user']->value['id'])&&$_smarty_tpl->tpl_vars['user']->value['id']==$_smarty_tpl->tpl_vars['_user_id']->value), null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['is_system_profile']->value){?>
    <?php $_smarty_tpl->tpl_vars['_context_type'] = new Smarty_variable(false, null, 0);?>
    <?php $_smarty_tpl->tpl_vars['invite'] = new Smarty_variable(false, null, 0);?>
    <?php $_smarty_tpl->tpl_vars['backend_profile'] = new Smarty_variable(array(), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['groups'] = new Smarty_variable(array(), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['user_name_formatted'] = new Smarty_variable('', null, 0);?>
    <?php $_smarty_tpl->tpl_vars['wa_app_static_url'] = new Smarty_variable(smarty_modifier_replace($_smarty_tpl->tpl_vars['wa_app_static_url']->value,'webasyst','team'), null, 0);?>
    <?php $_smarty_tpl->tpl_vars['is_own_profile'] = new Smarty_variable($_smarty_tpl->tpl_vars['_is_my_profile']->value, null, 0);?>
    <?php $_smarty_tpl->tpl_vars['contacts'] = new Smarty_variable(array(), null, 0);?>
<?php }else{ ?>
    <?php $_smarty_tpl->tpl_vars['_group_id'] = new Smarty_variable(preg_match('!^group\/\d+$!i',$_smarty_tpl->tpl_vars['context']->value) ? substr($_smarty_tpl->tpl_vars['context']->value,6) : '', null, 0);?>
    <?php $_smarty_tpl->tpl_vars['_context_type'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['_group_id']->value) ? 'group' : (substr($_smarty_tpl->tpl_vars['context']->value,0,7)==='search/' ? 'search' : $_smarty_tpl->tpl_vars['context']->value), null, 0);?>
<?php }?>
<?php $_smarty_tpl->tpl_vars['cover_thumbnails'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['cover_thumbnails']->value)===null||$tmp==='' ? array() : $tmp), null, 0);?>

<?php $_smarty_tpl->tpl_vars['cover_thumbnails_limit'] = new Smarty_variable(8, null, 0);?>

<div class="content">
<div class="t-profile-page" id="t_profile_page">
    <?php echo $_smarty_tpl->getSubTemplate ("./ProfileSidebar.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    <div class="t-profile flexbox vertical<?php if (!$_smarty_tpl->tpl_vars['wa']->value->isMobile()){?> bordered-right bordered-left<?php }?>">

        <?php if (!empty($_smarty_tpl->tpl_vars['contacts']->value)){?>
            <div class="t-profile-users<?php if ($_smarty_tpl->tpl_vars['wa']->value->isMobile()){?> bordered-top<?php }?> custom-px-48">
                <div class="t-profile-users-nav left">
                    <i class="fas fa-angle-left"></i>
                </div>
                <ul class="custom-m-0 custom-p-0 swiper-wrapper">
                    <?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['contacts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
$_smarty_tpl->tpl_vars['c']->_loop = true;
?>
                        <li class="swiper-slide t-profile-users-item<?php if ($_smarty_tpl->tpl_vars['user']->value['id']==$_smarty_tpl->tpl_vars['c']->value['id']){?> selected<?php }?>">
                            <?php if ($_smarty_tpl->tpl_vars['c']->value['login']&&$_smarty_tpl->tpl_vars['c']->value['is_user']>0){?>
                                <?php $_smarty_tpl->tpl_vars['_login'] = new Smarty_variable(urlencode(htmlspecialchars((string)$_smarty_tpl->tpl_vars['c']->value['login'], ENT_QUOTES, 'UTF-8', true)), null, 0);?>
                                <?php $_smarty_tpl->tpl_vars['_url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."u/".((string)$_smarty_tpl->tpl_vars['_login']->value)."/", null, 0);?>
                            <?php }else{ ?>
                                <?php $_smarty_tpl->tpl_vars['_url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."id/".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['c']->value['id'], ENT_QUOTES, 'UTF-8', true))."/", null, 0);?>
                            <?php }?>
                            <?php if (!empty($_smarty_tpl->tpl_vars['context']->value)){?>
                                <?php $_smarty_tpl->tpl_vars['_url'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_url']->value)."?list=".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['context']->value, ENT_QUOTES, 'UTF-8', true)), null, 0);?>
                            <?php }?>
                            <a class="userpic userpic32 wa-tooltip<?php if ($_smarty_tpl->tpl_vars['user']->value['id']==$_smarty_tpl->tpl_vars['c']->value['id']){?> js-userpic-team-head<?php }?>" data-wa-tooltip-content="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['c']->value['firstname'], ENT_QUOTES, 'UTF-8', true);?>
<?php if ($_smarty_tpl->tpl_vars['c']->value['lastname']){?> <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['c']->value['lastname'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" data-wa-tooltip-placement="bottom" style="background-image: url('<?php echo $_smarty_tpl->tpl_vars['c']->value['photo_url_32'];?>
');" href="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
">
                                <?php if ($_smarty_tpl->tpl_vars['c']->value['_online_status']==='online'){?><span class="userstatus"></span><?php }?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="t-profile-users-nav right">
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        <?php }?>

        <div class="t-profile-user-slider js-profile-user-slider bg-light-gray cursor-pointer">
            <div class="swiper-wrapper">
                <?php if ($_smarty_tpl->tpl_vars['cover_thumbnails']->value){?>
                    <?php  $_smarty_tpl->tpl_vars['cover_thumbnail'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cover_thumbnail']->_loop = false;
 $_from = array_slice(array_reverse($_smarty_tpl->tpl_vars['cover_thumbnails']->value),0,$_smarty_tpl->tpl_vars['cover_thumbnails_limit']->value); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cover_thumbnail']->key => $_smarty_tpl->tpl_vars['cover_thumbnail']->value){
$_smarty_tpl->tpl_vars['cover_thumbnail']->_loop = true;
?>
                        <div class="t-profile-user-slide" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['cover_thumbnail']->value['urls']['1408x440'];?>
">
                            <?php if ($_smarty_tpl->tpl_vars['can_edit']->value){?>
                                <span class="camera-overlay">
                                    <a class="button circle black opacity-50 smallest js-change-slider-photo">
                                        <i class="fas fa-camera"></i>
                                    </a>
                                </span>
                            <?php }?>
                        </div>
                    <?php } ?>
                <?php }else{ ?>
                <?php $_smarty_tpl->tpl_vars['unique_id'] = new Smarty_variable(mt_rand(1,10), null, 0);?>
                <div class="t-profile-user-slide width-100" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
img/covers/team-cover-<?php echo $_smarty_tpl->tpl_vars['unique_id']->value;?>
.jpg);">
                    <?php if ($_smarty_tpl->tpl_vars['can_edit']->value){?>
                        <span class="camera-overlay">
                            <a class="button circle black opacity-50 smallest js-change-slider-photo">
                                <i class="fas fa-camera"></i>
                            </a>
                        </span>
                    <?php }?>
                </div>
                <?php }?>
            </div>
            <?php if (count($_smarty_tpl->tpl_vars['cover_thumbnails']->value)>1){?>
            <div class="nav-arrows left">
                <i class="fas fa-angle-left large"></i>
            </div>
            <div class="nav-arrows right">
                <i class="fas fa-angle-right large"></i>
            </div>
            <div class="nav-bullets"></div>
            <?php }?>
        </div>

        <div class="t-profile-user-info article width-100 custom-pt-12 custom-px-0">
            <div class="article-body custom-pt-0">
                <div class="t-profile-user-info-bar flexbox">
                    <div class="t-profile-userpic">
                        <span class="userpic userpic144 blank js-userpic">
                            <img src="<?php echo $_smarty_tpl->tpl_vars['user']->value->getPhoto2x(144);?>
" alt="">
                            <?php if (!empty($_smarty_tpl->tpl_vars['user']->value['_online_status'])&&($_smarty_tpl->tpl_vars['user']->value['_online_status']==='online'||$_smarty_tpl->tpl_vars['user']->value['_online_status']==='idle')){?>
                            <span class="userstatus<?php if ($_smarty_tpl->tpl_vars['user']->value['_online_status']==='idle'){?> idle<?php }?>"></span>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['can_edit']->value){?>
                                <span class="camera-overlay js-change-photo">
                                    <i class="fas fa-camera"></i>
                                </span>
                            <?php }?>
                            <!-- plugin hook: 'backend_profile.photo' -->
                            
                            <?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_profile']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo ifset($_smarty_tpl->tpl_vars['_']->value['photo']);?>
<?php } ?>
                        </span>
                    </div>

                    <div class="t-profile-user-data break-word">
                        <!-- plugin hook: 'backend_profile.before_header' -->
                        
                        <?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_profile']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo ifset($_smarty_tpl->tpl_vars['_']->value['before_header']);?>
<?php } ?>
                        <h3 class="js-username custom-mb-8">
                            <?php if (!empty($_smarty_tpl->tpl_vars['fieldValues']->value['title'])){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['fieldValues']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>&#32;
                            <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user_name_formatted']->value, ENT_QUOTES, 'UTF-8', true);?>


                            <?php if (!empty($_smarty_tpl->tpl_vars['user']->value['login'])){?>
                            &#32;<span class="h5 text-gray break-all t-profile-username login custom-my-0">@<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['login'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                            <?php }?>
                        </h3>
                        <?php if (!empty($_smarty_tpl->tpl_vars['user_events']->value)){?>
                        <?php  $_smarty_tpl->tpl_vars['event'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['event']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['user_events']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['event']->key => $_smarty_tpl->tpl_vars['event']->value){
$_smarty_tpl->tpl_vars['event']->_loop = true;
?>
                            <?php $_smarty_tpl->tpl_vars['_badge_status_styles'] = new Smarty_variable('', null, 0);?>
                            <?php if (!empty($_smarty_tpl->tpl_vars['event']->value['status_bg_color'])){?>
                            <?php $_smarty_tpl->tpl_vars['_badge_status_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['event']->value['status_font_color'])."; background: ".((string)$_smarty_tpl->tpl_vars['event']->value['status_bg_color']).";", null, 0);?>
                            <?php }else{ ?>
                            <?php $_smarty_tpl->tpl_vars['_badge_status_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['event']->value['font_color'])."; background: ".((string)$_smarty_tpl->tpl_vars['event']->value['bg_color']).";", null, 0);?>
                            <?php }?>
                            <span class="badge user small custom-mr-8<?php if ($_smarty_tpl->tpl_vars['event']->value['calendar_id']==='birthday'){?> birthday<?php }?>"<?php if ($_smarty_tpl->tpl_vars['event']->value['calendar_id']!=='birthday'){?> style="<?php echo $_smarty_tpl->tpl_vars['_badge_status_styles']->value;?>
"<?php }?>>
                                <?php $_smarty_tpl->tpl_vars['_event_icon'] = new Smarty_variable('', null, 0);?>
                                <?php if ($_smarty_tpl->tpl_vars['event']->value['calendar_id']==='birthday'){?>
                                    <?php $_smarty_tpl->tpl_vars['_event_icon'] = new Smarty_variable("fas fa-birthday-cake", null, 0);?>
                                <?php }else{ ?>
                                    <?php if (!empty($_smarty_tpl->tpl_vars['event']->value['icon'])){?>
                                    <?php $_smarty_tpl->tpl_vars['_event_icon'] = new Smarty_variable(((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['event']->value['icon'], ENT_QUOTES, 'UTF-8', true)), null, 0);?>
                                    <?php }else{ ?>
                                    <?php $_smarty_tpl->tpl_vars['_event_icon'] = new Smarty_variable("fas fa-calendar-alt", null, 0);?>
                                    <?php }?>
                                <?php }?>
                                <i class="<?php echo $_smarty_tpl->tpl_vars['_event_icon']->value;?>
"></i>
                                <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['event']->value['summary'], ENT_QUOTES, 'UTF-8', true);?>

                            </span>
                        <?php } ?>
                        <?php }?>

                        <?php if (!$_smarty_tpl->tpl_vars['user']->value['is_company']&&!empty($_smarty_tpl->tpl_vars['user']->value['jobtitle'])){?>
                            <p class="custom-mt-8 custom-mb-0">
                                
                                <span class="custom-mr-0 small"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['jobtitle'], ENT_QUOTES, 'UTF-8', true);?>
</span>

                                <?php if (!empty($_smarty_tpl->tpl_vars['user']->value['company'])){?>
                                    <span class="at custom-mr-0 small"> в </span>
                                    <span class="company custom-mr-0 small"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['company'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                                <?php }?>
                            </p>
                        <?php }?>

                        <?php if ($_smarty_tpl->tpl_vars['user']->value['is_user']>0){?>
                            <?php if ($_smarty_tpl->tpl_vars['groups']->value){?>
                            <div class="custom-mt-8 small">
                                <?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['group']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['group']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value){
$_smarty_tpl->tpl_vars['group']->_loop = true;
 $_smarty_tpl->tpl_vars['group']->iteration++;
 $_smarty_tpl->tpl_vars['group']->last = $_smarty_tpl->tpl_vars['group']->iteration === $_smarty_tpl->tpl_vars['group']->total;
?>
                                <span>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
group/<?php echo $_smarty_tpl->tpl_vars['group']->key;?>
/"<?php if (!$_smarty_tpl->tpl_vars['group']->last){?> class="custom-mr-8"<?php }?>><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['group']->value, ENT_QUOTES, 'UTF-8', true);?>
</a><?php if (!$_smarty_tpl->tpl_vars['group']->last){?><span class="t-profile-group-item-bullet custom-mr-8">&bull;</span><?php }?>
                                </span>
                                <?php } ?>
                            </div>
                            <?php }else{ ?>
                            <div class="small custom-my-8">
                                <span class="text-gray custom-mr-8">Этот сотрудник не включен ни в одну из групп.</span>

                                <?php if ($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin()){?>
                                <a href="javascript:void(0)" class="semibold nowrap js-edit-groups"><i class="fas fa-pen"></i>&nbsp;Редактировать группы</a>
                                <?php }?>
                            </div>
                            <?php }?>
                        <?php }?>

                        <?php if ($_smarty_tpl->tpl_vars['user']->value['is_user']<0){?>
                            <p class="grey custom-mt-4">Доступ выключен</p>
                        <?php }elseif($_smarty_tpl->tpl_vars['invite']->value){?>
                            <p class="italic small custom-my-0"><?php echo sprintf('Ссылка на приглашение действует до %s',smarty_modifier_wa_datetime($_smarty_tpl->tpl_vars['invite']->value['expire_datetime'],'humandatetime'));?>
</p>
                            <?php if (teamHelper::hasRights('add_users')){?>
                                <?php if (!empty($_smarty_tpl->tpl_vars['user']->value['email'])){?>
                                    <a href="javascript:void(0)" class="js-invite small nowrap custom-mr-16" data-type="email" data-email="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['email'][0]['value'], ENT_QUOTES, 'UTF-8', true);?>
">
                                        <i class="fas fa-envelope"></i> Отправить еще раз
                                    </a>
                                    <a href="javascript:void(0)" class="js-invite small nowrap" data-type="link">
                                        <i class="fas fa-link text-gray"></i> Копировать ссылку
                                    </a>
                                <?php }else{ ?>
                                    <a href="javascript:void(0)" class="js-invite small nowrap" data-type="link">
                                        <i class="fas fa-link text-gray"></i> Копировать ссылку
                                    </a>
                                <?php }?>
                            <?php }?>
                        <?php }?>
                    </div>

                    <div class="t-profile-actions-btn flexbox wrap-mobile custom-pl-16" style="justify-content: flex-end;">
                        <?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_profile']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo ifset($_smarty_tpl->tpl_vars['_']->value['header_links_li']);?>
<?php } ?>
                        <?php if ($_smarty_tpl->tpl_vars['can_edit']->value){?>
                            <button type="button"
                                    class="button light-gray circle edit-link custom-mr-0 mobile-only"
                                    title="Редактировать сотрудника">
                                <i class="fas fa-pen text-blue"></i>
                            </button>
                            <button type="button"
                                    class="button light-gray rounded edit-link custom-mr-0 nowrap desktop-and-tablet-only"
                                    title="Редактировать">
                                <i class="fas fa-pen text-blue"></i> Редактировать
                            </button>
                            <?php if ($_smarty_tpl->tpl_vars['_is_my_profile']->value||$_smarty_tpl->tpl_vars['_is_system_admin']->value){?>
                                <button type="button"
                                        class="button light-gray circle access-link custom-mr-0 custom-ml-8 mobile-only"
                                        title="Редактировать сотрудника"
                                        data-url="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?module=profile&action=access&id=<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
"
                                        data-dialog-header="Доступ"
                                        data-dialog-width="1000px"
                                        data-section-id="access">
                                    <i class="fas fa-key text-green"></i>
                                </button>
                                <button type="button"
                                        class="button light-gray rounded access-link custom-mr-0 custom-ml-8 nowrap desktop-and-tablet-only"
                                        title="Доступ"
                                        data-url="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?module=profile&action=access&id=<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
"
                                        data-dialog-header="Доступ"
                                        data-dialog-width="1000px"
                                        data-section-id="access">
                                    <i class="fas fa-key text-green"></i> Доступ
                                </button>
                            <?php }?>
                        <?php }?>

                        <?php if (teamUser::canDelete($_smarty_tpl->tpl_vars['user']->value)){?>
                            <button type="button"
                                    class="button light-gray circle custom-mr-0 custom-ml-8 delete-link"
                                    title="Удалить">
                                <i class="fas fa-trash-alt text-red"></i>
                                <i class="fas fa-spinner fa-spin wa-animation-spin speed-1000 hidden"></i>
                            </button>
                        <?php }?>
                        <button type="button"
                                class="button circle blue custom-mr-0 custom-ml-8 mobile-only tablet-only js-show-drawer">
                            <i class="fas fa-list-ul text-white"></i>
                        </button>
                    </div>
                </div>

                <div class="t-profile-blocks">
                    <?php if (!empty($_smarty_tpl->tpl_vars['user']->value['email'])){?>
                    <?php  $_smarty_tpl->tpl_vars['email'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['email']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['user']->value['email']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['email']->key => $_smarty_tpl->tpl_vars['email']->value){
$_smarty_tpl->tpl_vars['email']->_loop = true;
?>
                    <div class="t-profile-block">
                        <a href="mailto:<?php echo $_smarty_tpl->tpl_vars['email']->value['value'];?>
" class="t-profile-block__icon">
                            <i class="fas fa-envelope text-blue"></i>
                        </a>

                        <div class="t-profile-block__value">
                            <a href="mailto:<?php echo $_smarty_tpl->tpl_vars['email']->value['value'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['email']->value['value'];?>
" class="t-profile-block__value-link"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['email']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
</a>
                        </div>

                        <div class="t-profile-block__type">
                            <?php if (isset($_smarty_tpl->tpl_vars['contactFields']->value['email']['ext'][$_smarty_tpl->tpl_vars['email']->value['ext']])){?>
                                <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['contactFields']->value['email']['ext'][$_smarty_tpl->tpl_vars['email']->value['ext']], ENT_QUOTES, 'UTF-8', true);?>

                            <?php }elseif($_smarty_tpl->tpl_vars['email']->value['ext']){?>
                                <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['email']->value['ext'], ENT_QUOTES, 'UTF-8', true);?>

                            <?php }?>email
                        </div>
                    </div>
                    <?php } ?>
                    <?php }else{ ?>
                    <div class="t-profile-block">
                        <div class="t-profile-block__icon">
                            <i class="fas fa-envelope text-light-gray"></i>
                        </div>

                        <div class="t-profile-block__value text-gray">
                            нет информации
                        </div>

                        <div class="t-profile-block__type">
                            email
                        </div>
                    </div>
                    <?php }?>

                    <?php if (!empty($_smarty_tpl->tpl_vars['user']->value['phone'])){?>
                    <?php  $_smarty_tpl->tpl_vars['phone'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['phone']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['user']->value['phone']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['phone']->key => $_smarty_tpl->tpl_vars['phone']->value){
$_smarty_tpl->tpl_vars['phone']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['phone']->key;
?>
                    <div class="t-profile-block">
                        <a href="tel:<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['phone']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
" class="t-profile-block__icon">
                            <i class="fas fa-phone-alt text-green"></i>
                        </a>

                        <div class="t-profile-block__value">
                            <a href="tel:<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['phone']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['phone']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
" class="t-profile-block__value-link">
                                <?php if (isset($_smarty_tpl->tpl_vars['fieldValues']->value['phone'][$_smarty_tpl->tpl_vars['key']->value]['value'])){?>
                                    <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['fieldValues']->value['phone'][$_smarty_tpl->tpl_vars['key']->value]['value'], ENT_QUOTES, 'UTF-8', true);?>

                                <?php }else{ ?>
                                    <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['phone']->value['value'], ENT_QUOTES, 'UTF-8', true);?>

                                <?php }?>
                            </a>
                        </div>

                        <div class="t-profile-block__type">
                            <?php if (isset($_smarty_tpl->tpl_vars['contactFields']->value['phone']['ext'][$_smarty_tpl->tpl_vars['phone']->value['ext']])){?>
                                <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['contactFields']->value['phone']['ext'][$_smarty_tpl->tpl_vars['phone']->value['ext']], ENT_QUOTES, 'UTF-8', true);?>

                            <?php }elseif($_smarty_tpl->tpl_vars['phone']->value['ext']){?>
                                <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['phone']->value['ext'], ENT_QUOTES, 'UTF-8', true);?>

                            <?php }?>телефон
                        </div>
                    </div>
                    <?php } ?>
                    <?php }else{ ?>
                    <div class="t-profile-block">
                        <div class="t-profile-block__icon">
                            <i class="fas fa-phone-alt text-light-gray"></i>
                        </div>

                        <div class="t-profile-block__value text-gray">
                            нет информации
                        </div>

                        <div class="t-profile-block__type">
                            телефон
                        </div>
                    </div>
                    <?php }?>

                    <?php if (!empty($_smarty_tpl->tpl_vars['profile_editor']->value['data']['fieldValues']['im'])){?>
                    <?php  $_smarty_tpl->tpl_vars['im'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['im']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['profile_editor']->value['data']['fieldValues']['im']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['im']->key => $_smarty_tpl->tpl_vars['im']->value){
$_smarty_tpl->tpl_vars['im']->_loop = true;
?>
                    <div class="t-profile-block">
                        <?php $_smarty_tpl->tpl_vars['_href'] = new Smarty_variable('', null, 0);?>
                        <?php if ($_smarty_tpl->tpl_vars['im']->value['ext']=='whatsapp'){?>
                        <?php $_smarty_tpl->tpl_vars['_href'] = new Smarty_variable("https://wa.me/".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['im']->value['data'], ENT_QUOTES, 'UTF-8', true)), null, 0);?>
                        <?php }elseif($_smarty_tpl->tpl_vars['im']->value['ext']=='telegram'){?>
                        <?php $_smarty_tpl->tpl_vars['_href'] = new Smarty_variable("https://t.me/".((string)smarty_modifier_replace(smarty_modifier_replace(htmlspecialchars((string)$_smarty_tpl->tpl_vars['im']->value['data'], ENT_QUOTES, 'UTF-8', true),'https://t.me/',''),'https://telegram.im/@','')), null, 0);?>
                        <?php }elseif($_smarty_tpl->tpl_vars['im']->value['ext']=='skype'){?>
                        <?php $_smarty_tpl->tpl_vars['_href'] = new Smarty_variable("skype:".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['im']->value['data'], ENT_QUOTES, 'UTF-8', true))."?chat", null, 0);?>
                        <?php }elseif($_smarty_tpl->tpl_vars['im']->value['ext']=='viber'){?>
                        <?php $_smarty_tpl->tpl_vars['_href'] = new Smarty_variable("viber://chat?number=".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['im']->value['data'], ENT_QUOTES, 'UTF-8', true)), null, 0);?>
                        <?php }elseif($_smarty_tpl->tpl_vars['im']->value['ext']=='facebook'){?>
                        <?php $_smarty_tpl->tpl_vars['_href'] = new Smarty_variable("https://m.me/".((string)htmlspecialchars((string)$_smarty_tpl->tpl_vars['im']->value['data'], ENT_QUOTES, 'UTF-8', true)), null, 0);?>
                        <?php }?>

                        <?php if (!empty($_smarty_tpl->tpl_vars['_href']->value)){?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['_href']->value;?>
" class="t-profile-block__icon">
                            <?php echo $_smarty_tpl->tpl_vars['im']->value['icon'];?>

                        </a>
                        <?php }else{ ?>
                        <div class="t-profile-block__icon">
                            <?php echo $_smarty_tpl->tpl_vars['im']->value['icon'];?>

                        </div>
                        <?php }?>

                        <div class="t-profile-block__value">
                            <?php if (!empty($_smarty_tpl->tpl_vars['_href']->value)){?>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['_href']->value;?>
" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['im']->value['data'], ENT_QUOTES, 'UTF-8', true);?>
" class="t-profile-block__value-link"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['im']->value['data'], ENT_QUOTES, 'UTF-8', true);?>
</a>
                            <?php }else{ ?>
                            <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['im']->value['data'], ENT_QUOTES, 'UTF-8', true);?>

                            <?php }?>
                        </div>

                        <div class="t-profile-block__type">
                            <?php if (isset($_smarty_tpl->tpl_vars['contactFields']->value['im']['ext'][$_smarty_tpl->tpl_vars['im']->value['ext']])){?>
                                <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['contactFields']->value['im']['ext'][$_smarty_tpl->tpl_vars['im']->value['ext']], ENT_QUOTES, 'UTF-8', true);?>

                            <?php }else{ ?>
                                <?php echo (($tmp = @htmlspecialchars((string)$_smarty_tpl->tpl_vars['im']->value['ext'], ENT_QUOTES, 'UTF-8', true))===null||$tmp==='' ? "другой" : $tmp);?>

                            <?php }?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php }?>

                    <!-- plugin hook: 'backend_profile.header' -->
                    
                    <?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_profile']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo ifset($_smarty_tpl->tpl_vars['_']->value['header']);?>
<?php } ?>

                    <?php if (!empty($_smarty_tpl->tpl_vars['profile_editor']->value['data']['fieldValues']['address'][0]['data']['city'])&&!empty($_smarty_tpl->tpl_vars['geocoding']->value['key'])){?>
                    <div class="t-profile-block t-profile-block_map">
                        <div class="t-profile-block__map">
                            <style>
                                .ymaps-2-1-79-gototaxi__container,
                                .ymaps-2-1-79-gototech {
                                    display: none !important;
                                }
                            </style>

                            <div id="map"></div>

                            <script>
                                <?php if ($_smarty_tpl->tpl_vars['geocoding']->value['type']=='yandex'){?>
                                ymaps.ready(init);
                                <?php }else{ ?>
                                init();
                                <?php }?>

                                function init() {
                                    const $map = $('#map');
                                    const map = new TeamMap($map, '<?php echo $_smarty_tpl->tpl_vars['geocoding']->value['type'];?>
');

                                    map.geocode('<?php echo $_smarty_tpl->tpl_vars['profile_editor']->value['data']['fieldValues']['address'][0]['data']['city'];?>
<?php if (isset($_smarty_tpl->tpl_vars['profile_editor']->value['data']['fieldValues']['address'][0]['data']['street'])){?>, <?php echo $_smarty_tpl->tpl_vars['profile_editor']->value['data']['fieldValues']['address'][0]['data']['street'];?>
<?php }?>', renderMap, errorMsg);

                                    function renderMap(lat, lng) {
                                        map.render(lat, lng);
                                    }

                                    function errorMsg(error) {
                                        $map.closest('.t-profile-block_map').remove();
                                    }
                                }
                            </script>
                        </div>
                    </div>
                    <?php }?>
                </div>

                <div class="t-profile-user-info-block fields custom-mt-20 custom-pb-40" id="tc-contact">
                    <div class="align-center">
                        <a class="js-toggle-user-info small" href="javascript:void(0);">Подробная информация <i class="fas fa-caret-down"></i></a>
                    </div>

                    <div class="fields-group js-user-info custom-mt-16 hidden">
                        <div id="contact-info-block">
                            
                        </div>
                        <?php if ($_smarty_tpl->tpl_vars['_is_admin']->value){?>
                            <ul class="unstyled hint t-create-method-info custom-mt-16">
                                <li>ID: <?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
</li>
                                <li>Добавил: <?php if (!empty($_smarty_tpl->tpl_vars['author']->value)){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['author']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
 <?php }?><?php echo $_smarty_tpl->tpl_vars['contact_create_time']->value;?>
</li>
                                <li>Метод: <?php if ($_smarty_tpl->tpl_vars['user']->value['create_method']){?><?php echo $_smarty_tpl->tpl_vars['user']->value['create_method'];?>
 (<?php echo $_smarty_tpl->tpl_vars['user']->value['create_app_id'];?>
)<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['user']->value['create_app_id'];?>
<?php }?></li>
                            </ul>
                        <?php }?>
                    </div>
                </div>
                <div class="t-profile-user-info-activity bordered-top custom-pt-40">
                    <div class="activity-header flexbox wrap-mobile middle">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php if (!empty($_smarty_tpl->tpl_vars['user']->value)){?>
    <div class="drawer js-profile-sidebar-drawer" style="display:block;z-index:-1;opacity:0">
        <div class="drawer-background"></div>
        <div class="drawer-body">
            <a href="javascript:void(0);" class="drawer-close js-close-drawer"><i class="fas fa-times"></i></a>
            <div class="drawer-block t-profile-drawer" style="background-color:var(--background-color)">
                <div class="drawer-content">
                    <?php /*  Call merged included template "./sidebarWidgets/Access.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./sidebarWidgets/Access.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '16873062536a889d14a32af7-18172126');
content_6a889d14a8c081_82446994($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./sidebarWidgets/Access.html" */?>
                    <?php if (!$_smarty_tpl->tpl_vars['is_system_profile']->value){?>
                        <?php $_smarty_tpl->tpl_vars['stats_html'] = new Smarty_variable($_smarty_tpl->getSubTemplate ("./sidebarWidgets/Stats.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('is_drawer'=>true), 0));?>

                        <?php echo $_smarty_tpl->tpl_vars['wa']->value->contactProfileSidebar($_smarty_tpl->tpl_vars['user']->value['id'],array('is_profile_sidebar'=>true,'is_profile_drawer'=>true,'html'=>array('stats'=>$_smarty_tpl->tpl_vars['stats_html']->value)));?>


                        <?php echo $_smarty_tpl->tpl_vars['wa']->value->contactProfileTabs($_smarty_tpl->tpl_vars['user']->value['id'],array('is_profile_sidebar'=>true));?>

                    <?php }?>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<script>

    <?php $_smarty_tpl->tpl_vars['_profile_object_options'] = new Smarty_variable(array('photo_dialog_url'=>((string)$_smarty_tpl->tpl_vars['wa_backend_url']->value)."?module=profile&action=photo&id=".((string)$_smarty_tpl->tpl_vars['user']->value['id'])."&ui=2.0",'upload_cover_dialog_url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."?module=profile&action=coverUploadDialog&id=".((string)$_smarty_tpl->tpl_vars['user']->value['id']),'wa_backend_url'=>$_smarty_tpl->tpl_vars['wa_backend_url']->value,'is_own_profile'=>$_smarty_tpl->tpl_vars['is_own_profile']->value,'wa_app_url'=>$_smarty_tpl->tpl_vars['wa_app_url']->value,'webasyst_id_auth_url'=>$_smarty_tpl->tpl_vars['webasyst_id_auth_url']->value,'user'=>array('id'=>$_smarty_tpl->tpl_vars['user']->value['id']),'wa_url'=>$_smarty_tpl->tpl_vars['wa_url']->value,'wa_version'=>$_smarty_tpl->tpl_vars['wa']->value->version(),'can_edit'=>$_smarty_tpl->tpl_vars['can_edit']->value,'editor'=>(($tmp = @$_smarty_tpl->tpl_vars['profile_editor']->value)===null||$tmp==='' ? array() : $tmp)), null, 0);?>

    (function ($) {
        $.team.setTitle(<?php echo json_encode($_smarty_tpl->tpl_vars['user_name_formatted']->value);?>
);

        $.wa.locale = $.extend($.wa.locale, {
            "map": "карта",
            "other": "другой",
            "which?": "какой?",
            "delete": "удалить",
            "Add another": "Добавить еще",
            "required": "обязательное",
            "year": "год",
            "Incorrect email address format.": "Неправильный формат email-адреса.",
            "Incorrect URL format.": "Неправильный формат URL.",
            "Must be a number.": "Должно быть числом.",
            "&lt;no name&gt;": "&lt;без названия&gt;",  // empty name of a checklist option
            "&lt;none&gt;": "&lt;нет&gt;",   // no checklist options, e.g. no categories or groups
            "no name": "без имени",        // contact name
            "Yes": "Да",
            "No": "Нет",

            "January": "Январь",
            "February": "Февраль",
            "March": "Март",
            "April": "Апрель",
            "May": "Май",
            "June": "Июнь",
            "July": "Июль",
            "August": "Август",
            "September": "Сентябрь",
            "October": "Октябрь",
            "November": "Ноябрь",
            "December": "Декабрь",
            "This field is required.": "Это обязательное поле.",
            "At least one of these fields must be filled": "Хотя бы одно из этих полей должно быть заполнено.",
            "Save": "Сохранить",
            "Cancel": "Отмена",
            "Close": "Закрыть",
            "Auto": "Авто",
            "Contact info": "Контактная информация",

            "code_dialog_text": <?php echo json_encode(sprintf_wp("Share the code below with <strong>%s</strong>. Entering the code in a Webasyst mobile app in the ‘Add account’ screen will automatically connect the app to <code>%s</code>.",htmlspecialchars((string)$_smarty_tpl->tpl_vars['user_name_formatted']->value, ENT_QUOTES, 'UTF-8', true),str_replace(array('http://','https://'),'',$_smarty_tpl->tpl_vars['wa']->value->domainUrl())));?>

        });

        $.team.profile = new Profile($.extend(<?php echo json_encode($_smarty_tpl->tpl_vars['_profile_object_options']->value);?>
, {
            $wrapper: $('#t_profile_page'),
        }));

        


        $('.js-change-slider-photo').on('click', function(e) {
            e.preventDefault();
            $.get('<?php echo $_smarty_tpl->tpl_vars['_profile_object_options']->value['upload_cover_dialog_url'];?>
', function(html) {
                $.waDialog({
                  html,
                  onOpen($dialog) {
                    const limitCoverStyle = $('<style>.upload-cover__item:nth-child(1n+<?php echo $_smarty_tpl->tpl_vars['cover_thumbnails_limit']->value+1;?>
) img {opacity: .3;}</style>');
                    $dialog.prepend(limitCoverStyle);
                  }
                })
            })
        });

        $('.js-invite').on('click', function(e) {
            e.preventDefault();

            if(!!this.dataset.isLocked) {
                return;
            }

            this.dataset.isLocked = 'true';

            const $link = this,
                type = $link.dataset.type;
            let href, post_data;

            if(type === 'email'){
                href = '?module=users&action=invite';
                post_data = { email: this.dataset.email ?? '' };
                $link.innerHTML = '<i class="fas fa-spinner fa-spin wa-animation-spin speed-1000"></i> Отправляется';
            }else if(type === 'link') {
                href = '?module=users&action=createInvitation';
                post_data = { contact_id: $.team.profile.user.id ?? 0 };
            }

            $.post(href, post_data, function(response) {
                if(response.status === 'ok') {
                    if(type === 'email'){
                        $link.classList.add('text-green')
                        $link.innerHTML = '<i class="fas fa-check"></i> Отправлено';
                    }else if(type === 'link'){
                        $link.insertAdjacentHTML('afterend', `<input type="text" style="position:absolute;opacity:0" class="js-invite-link" value="${ response.data.invitation_link }">`);
                        let $invite_link = document.querySelector('.js-invite-link')
                        $invite_link.select();
                        $invite_link.setSelectionRange(0, 99999);
                        document.execCommand("copy");
                        $invite_link.remove();
                        $link.classList.add('text-green')
                        $link.innerHTML = '<i class="fas fa-check"></i> Скопировано';
                    }
                }
            }).always( function() {
                setTimeout(()=>{
                    $link.classList.remove('text-green');
                    if(type === 'email'){
                        $link.innerHTML = '<i class="fas fa-envelope"></i> Отправить еще раз';
                    }else if(type === 'link'){
                        $link.innerHTML = '<i class="fas fa-link"></i> Ссылка на приглашение';
                    }
                    $link.dataset.isLocked = '';
                }, 1500);
            });
        });
        <?php if (!$_smarty_tpl->tpl_vars['is_system_profile']->value){?>
            $.get('<?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
?module=profile&action=activity&user_id=<?php echo json_encode($_smarty_tpl->tpl_vars['user']->value['id']);?>
', function(response) {
                $('.t-profile-user-info-activity').append(response)
            });
        <?php }?>
        const $tooltip_wrapper = document.querySelector('.t-profile');
        $(".wa-tooltip").waTooltip({
            appendTo: $tooltip_wrapper,
            touch: 'hold',
        });

    })(jQuery);

    <?php if (!empty($_smarty_tpl->tpl_vars['contacts']->value)){?>
    new SwiperSlider({
        selector: '.t-profile-users',
        calculateGroupSize: false,
        watchNav: true,
        params: {
            slidesPerView: 'auto',
            freeMode: true,
            roundLengths: true,
            centeredSlides: true,
            centeredSlidesBounds: true,
            centerInsufficientSlides: true,
            watchOverflow: true,
            resizeObserver: true,
            navigation: {
                nextEl: ".t-profile-users-nav.right",
                prevEl: ".t-profile-users-nav.left",
            },
            on: {
                afterInit(swiper) {
                    if (swiper.virtualSize === swiper.width) {
                        swiper.navigation.nextEl.style.display = 'none';
                        swiper.navigation.prevEl.style.display = 'none';
                        swiper.enabled = false;
                    }
                }
            }
        },
        events: {
            click(swiper, event) {
                const is_next_click = event.target === swiper.navigation.nextEl,
                    is_prev_click = event.target === swiper.navigation.prevEl,
                    slide_width = swiper.wrapperEl.querySelector(`.${ swiper.params.slideClass }`).clientWidth;

                if (is_next_click) {
                    swiper.slideTo((Math.round(swiper.width / slide_width) - 2) + (swiper.activeIndex + 1))
                }

                if (is_prev_click) {
                    swiper.slideTo((swiper.activeIndex + 1) - (Math.round(swiper.width / slide_width) - 2))
                }
            },
            resize(swiper) {
                if (swiper.virtualSize === swiper.width) {
                    swiper.navigation.nextEl.style.display = 'none';
                    swiper.navigation.prevEl.style.display = 'none';
                    swiper.enabled = false;
                }else{
                    swiper.navigation.nextEl.style.display = 'grid';
                    swiper.navigation.prevEl.style.display = 'grid';
                    swiper.enabled = true;
                }
            }
        }
    });
    <?php }?>

    <?php if ($_smarty_tpl->tpl_vars['cover_thumbnails']->value){?>
    new SwiperSlider({
        selector: '.t-profile-user-slider ',
        setContainerWidth: [760],
        params: {
            roundLengths: true,
            slidesPerView: 1,
            slideClass: 't-profile-user-slide',
            initialSlide: parseInt(localStorage.getItem( 'team/profile_slider' )) || 0,
            navigation: {
                nextEl: ".nav-arrows.right",
                prevEl: ".nav-arrows.left",
                disabledClass: 'is-disabled'
            },
            pagination: {
                el: ".nav-bullets",
                bulletClass: 'nav-bullet',
                bulletActiveClass: 'is-active',
                clickable: false,
                renderBullet: function (index, className) {
                    return `<span class="${ className }"></span>`;
                },
            }
        },
        events: {
            slideChange(swiper) {
                localStorage.setItem( 'team/profile_slider', swiper.realIndex );
            }
        }
    });
    <?php }?>

</script>

<?php if ($_smarty_tpl->tpl_vars['_context_type']->value){?>
<script>
    (function ($) {
        $.team.sidebar.setSelected({
            type: '<?php echo $_smarty_tpl->tpl_vars['_context_type']->value;?>
',
            groupId: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['_group_id']->value)===null||$tmp==='' ? 'null' : $tmp);?>
,
        });
    })(jQuery);
</script>
<?php }?>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/sidebarWidgets/Access.html" */ ?>
<?php if ($_valid && !is_callable('content_6a889d14a8c081_82446994')) {function content_6a889d14a8c081_82446994($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_wa_date')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.wa_date.php';
?>

<?php $_smarty_tpl->createLocalArrayVariable('user', null, 0);
$_smarty_tpl->tpl_vars['user']->value['waid_invite_datetime'] = (($tmp = @$_smarty_tpl->tpl_vars['user_settings']->value['waid_invite_datetime'])===null||$tmp==='' ? '' : $tmp);?>

<?php $_smarty_tpl->tpl_vars['wrapper_id'] = new Smarty_variable(uniqid('t-sidebar-waid-'), null, 0);?>

<section class="t-profile-sidebar-section custom-mb-16" id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['wrapper_id']->value, ENT_QUOTES, 'UTF-8', true);?>
"><?php if ($_smarty_tpl->tpl_vars['is_connected_to_webasyst_id']->value){?><?php if ($_smarty_tpl->tpl_vars['is_bound_with_webasyst_contact']->value){?><p class="custom-mb-4"><span class="icon custom-mr-4"><svg width="97" height="96" viewBox="0 0 97 96" fill="none" xmlns="http://www.w3.org/2000/svg"><defs><style>.webasyst-magic-wand-handle { fill: black; }[data-theme="dark"] .webasyst-magic-wand-handle { fill: #ffffff; }</style></defs><path d="M20.7348 14.5035C18.57 12.1327 14.8662 12.0487 12.5963 14.3185C10.3239 16.5908 10.4084 20.3016 12.7813 22.468L28.8528 37.141C30.6852 38.8139 33.5082 38.75 35.263 36.9953C37.0199 35.2385 37.0843 32.4089 35.4088 30.5739L20.7348 14.5035Z" fill="#0088EE"/><path d="M47.1515 0C43.9448 0 41.3794 2.67789 41.5255 5.89162L42.5137 27.6305C42.6266 30.1155 44.6731 32.0657 47.1515 32.0657C49.6299 32.0657 51.6763 30.1155 51.7893 27.6305L52.7775 5.89161C52.9236 2.67788 50.3582 0 47.1515 0Z" fill="#22CC22"/><path d="M81.6725 14.3185C79.4026 12.0487 75.6988 12.1327 73.534 14.5035L58.86 30.5739C57.1845 32.4089 57.2489 35.2385 59.0058 36.9952C60.7606 38.75 63.5836 38.8139 65.416 37.141L81.4875 22.468C83.8604 20.3016 83.9449 16.5908 81.6725 14.3185Z" fill="#EE6622"/><path d="M96.0089 48.8621C96.0089 45.6452 93.3248 43.0868 90.1215 43.2324L68.3812 44.2205C65.9052 44.3331 63.9492 46.3744 63.9492 48.8621C63.9492 51.3499 65.9052 53.3912 68.3812 53.5037L90.1215 54.4918C93.3248 54.6374 96.0089 52.079 96.0089 48.8621Z" fill="#FFCC00"/><path d="M65.416 60.5964C63.5836 58.9235 60.7606 58.9874 59.0058 60.7421C57.2489 62.4989 57.1845 65.3285 58.86 67.1635L73.534 83.234C75.6988 85.6048 79.4026 85.6887 81.6725 83.419C83.9449 81.1467 83.8604 77.4359 81.4875 75.2694L65.416 60.5964Z" fill="#55DDDD"/><path d="M51.2853 44.7066C49.5341 42.9555 46.7086 42.9175 44.911 44.6204L1.92878 85.3376C-0.595136 87.7285 -0.649357 91.7324 1.80924 94.1908C4.26542 96.6469 8.26241 96.593 10.6515 94.0713L51.3715 51.092C53.0769 49.292 53.0386 46.4598 51.2853 44.7066Z" class="webasyst-magic-wand-handle" /></svg></span><?php if ($_smarty_tpl->tpl_vars['is_own_profile']->value||$_smarty_tpl->tpl_vars['is_app_admin']->value){?><span class="flexbox flexbox-inline vertical"><span class="small">Webasyst ID подключен</span><?php if ((!empty($_smarty_tpl->tpl_vars['webasyst_id_data']->value['email']))){?><span class="small bold"><?php echo $_smarty_tpl->tpl_vars['webasyst_id_data']->value['email'];?>
</span><?php }?><?php if ((!empty($_smarty_tpl->tpl_vars['webasyst_id_data']->value['phone']))){?><span class="small bold"><?php echo $_smarty_tpl->tpl_vars['webasyst_id_data']->value['phone'];?>
</span><?php }?></span><?php }else{ ?><span class="small bold">Webasyst ID подключен</span><?php }?></p><p class="custom-my-4 hint small"><span class="custom-mr-8">Последний вход <?php echo mb_strtolower(smarty_modifier_wa_date($_smarty_tpl->tpl_vars['user']->value['last_datetime'],"humandatetime"), 'UTF-8');?>
</span></p><?php if ($_smarty_tpl->tpl_vars['is_own_profile']->value&&!empty($_smarty_tpl->tpl_vars['webasyst_id_qrcode_url']->value)){?><div class="small align-center custom-my-12 t-access-code-invite custom-p-4"><a href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['webasyst_id_qrcode_url']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="black" target="_blank"><i class="fas fa-qrcode custom-mr-4"></i>Войти на телефоне по QR</a></div><?php }?><?php }elseif($_smarty_tpl->tpl_vars['contact']->value['is_user']>0){?><?php if ($_smarty_tpl->tpl_vars['is_own_profile']->value){?><a class="full-width js-webasyst-id-auth bold" href="javascript:void(0)"><span class="icon custom-mr-4"><svg width="97" height="96" viewBox="0 0 97 96" fill="none" xmlns="http://www.w3.org/2000/svg"><defs><style>.webasyst-magic-wand-handle { fill: black; }[data-theme="dark"] .webasyst-magic-wand-handle { fill: #ffffff; }</style></defs><path d="M20.7348 14.5035C18.57 12.1327 14.8662 12.0487 12.5963 14.3185C10.3239 16.5908 10.4084 20.3016 12.7813 22.468L28.8528 37.141C30.6852 38.8139 33.5082 38.75 35.263 36.9953C37.0199 35.2385 37.0843 32.4089 35.4088 30.5739L20.7348 14.5035Z" fill="#0088EE"/><path d="M47.1515 0C43.9448 0 41.3794 2.67789 41.5255 5.89162L42.5137 27.6305C42.6266 30.1155 44.6731 32.0657 47.1515 32.0657C49.6299 32.0657 51.6763 30.1155 51.7893 27.6305L52.7775 5.89161C52.9236 2.67788 50.3582 0 47.1515 0Z" fill="#22CC22"/><path d="M81.6725 14.3185C79.4026 12.0487 75.6988 12.1327 73.534 14.5035L58.86 30.5739C57.1845 32.4089 57.2489 35.2385 59.0058 36.9952C60.7606 38.75 63.5836 38.8139 65.416 37.141L81.4875 22.468C83.8604 20.3016 83.9449 16.5908 81.6725 14.3185Z" fill="#EE6622"/><path d="M96.0089 48.8621C96.0089 45.6452 93.3248 43.0868 90.1215 43.2324L68.3812 44.2205C65.9052 44.3331 63.9492 46.3744 63.9492 48.8621C63.9492 51.3499 65.9052 53.3912 68.3812 53.5037L90.1215 54.4918C93.3248 54.6374 96.0089 52.079 96.0089 48.8621Z" fill="#FFCC00"/><path d="M65.416 60.5964C63.5836 58.9235 60.7606 58.9874 59.0058 60.7421C57.2489 62.4989 57.1845 65.3285 58.86 67.1635L73.534 83.234C75.6988 85.6048 79.4026 85.6887 81.6725 83.419C83.9449 81.1467 83.8604 77.4359 81.4875 75.2694L65.416 60.5964Z" fill="#55DDDD"/><path d="M51.2853 44.7066C49.5341 42.9555 46.7086 42.9175 44.911 44.6204L1.92878 85.3376C-0.595136 87.7285 -0.649357 91.7324 1.80924 94.1908C4.26542 96.6469 8.26241 96.593 10.6515 94.0713L51.3715 51.092C53.0769 49.292 53.0386 46.4598 51.2853 44.7066Z" class="webasyst-magic-wand-handle" /></svg></span>&nbsp;<span>Подключить Webasyst ID</span></a><p class="smaller custom-my-4">Входите в свой Вебасист безопасно с использованием двухфакторной авторизации (2FA) и получите удобный доступ ко всем сервисам и мобильным приложениям Webasyst.</p><div class="small align-center custom-my-12 t-access-code-invite custom-p-4"><a href="javascript:void(0)" class="black js-connect-by-qr-code"><i class="fas fa-qrcode custom-mr-4 js-code-icon"></i><i class="text-gray fas fa-spinner fa-spin wa-animation-spin speed-1000 js-loading custom-mr-4" style="display: none"></i>Войти на телефоне по QR</a></div><?php }else{ ?><p class="custom-mb-4"><i class="fas fa-unlink text-red"></i> <span class="text-orange bold custom-ml-4">Webasyst ID не подключен</span></p><p class="smaller custom-my-4"><?php echo sprintf('%s еще не подключил вход c Webasyst ID. Возможность включения безопасного входа с двухфакторной авторизацией (2FA) и доступом ко всем сервисам и мобильным приложениям Webasyst появится только после подключения Webasyst ID.',htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['login'], ENT_QUOTES, 'UTF-8', true));?>
</p><?php $_smarty_tpl->tpl_vars['_is_team_admin'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin($_smarty_tpl->tpl_vars['wa']->value->app()), null, 0);?><?php if ($_smarty_tpl->tpl_vars['_is_team_admin']->value&&$_smarty_tpl->tpl_vars['contact']->value['is_user']=='1'){?><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['email'])){?><?php if (!$_smarty_tpl->tpl_vars['user']->value['waid_invite_datetime']){?><?php $_smarty_tpl->tpl_vars['_link_text'] = new Smarty_variable('Отправить инструкцию', null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_link_text'] = new Smarty_variable('Отправить снова', null, 0);?><?php }?><p class="smaller custom-my-0 js-waid"><a href="javascript:void(0)" class="js-send-email-invitation"><i class="fas fa-envelope custom-mr-4"></i><span class="js-text"><?php echo $_smarty_tpl->tpl_vars['_link_text']->value;?>
</span> <i class="text-gray fas fa-spinner fa-spin wa-animation-spin speed-1000 js-loading custom-ml-4" style="display: none"></i><span class="js-last-send-datetime custom-ml-4 text-gray"><?php if ($_smarty_tpl->tpl_vars['user']->value['waid_invite_datetime']){?><?php echo mb_strtolower(smarty_modifier_wa_date($_smarty_tpl->tpl_vars['user']->value['waid_invite_datetime'],'humandatetime'), 'UTF-8');?>
<?php }?></span></a><span class="js-sent-email-ok hidden"><i class="fas fa-envelope custom-mr-4"></i>Приглашение отправлено<i class="fas fa-check-circle custom-ml-4"></i></span></p><?php }?><?php $_smarty_tpl->tpl_vars['_is_global_admin'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin(), null, 0);?><?php if ($_smarty_tpl->tpl_vars['_is_global_admin']->value){?><div class="small align-center custom-mt-12 t-access-code-invite"><a href="javascript:void(0)" class="js-connect-by-numeric-code bold"><i class="fas fa-mobile-alt custom-mr-4"></i>Код для мобильного приложения<i class="text-gray fas fa-spinner fa-spin wa-animation-spin speed-1000 js-loading custom-ml-4" style="display: none"></i></a><p class="custom-mt-4 small text-gray"><?php echo sprintf_wp("The invitation code for <strong>%s</strong> to connect a Webasyst mobile app to <strong>%s</strong>.",htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['name'], ENT_QUOTES, 'UTF-8', true),str_replace(array('http://','https://'),'',$_smarty_tpl->tpl_vars['wa']->value->domainUrl()));?>
</p></div><?php }?><?php }?></p><?php }?><?php }?><?php }else{ ?><?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']>0){?><p class="custom-mb-4 text-red"><i class="fas fa-exclamation-triangle text-red"></i> <span class="bold custom-ml-4">Webasyst ID не подключен</span></p><p class="small custom-my-4 semibold"><?php echo sprintf('%sВключите вход с Webasyst ID%s, чтобы ваши сотрудники могли работать со всеми сервисами и мобильными приложениями Webasyst, а также могли включить безопасный вход с использованием двухфакторной авторизации (2FA).',"<a href=\"".((string)$_smarty_tpl->tpl_vars['wa_backend_url']->value)."webasyst/settings/waid/\">",'</a>');?>
</p><p class="button small light-gray custom-my-4"><a class="js-webasyst-id-help-link bold" href="javascript:void(0)">Что такое Webasyst ID?</a></p><?php }?><?php }?></section>
<script>(function($) { "use strict";
    Profile.initSidebarWidgetAccess({
        $wrapper: $('#'+<?php echo json_encode($_smarty_tpl->tpl_vars['wrapper_id']->value);?>
),
        wa_backend_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_backend_url']->value);?>
,
        wa_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_url']->value);?>
,
        user_id: <?php echo json_encode($_smarty_tpl->tpl_vars['user']->value['id']);?>
,
        loc: {
            email_again: <?php echo json_encode(_w('Email again'));?>
,
            error: <?php echo json_encode(_w('Error'));?>
,
            ok: <?php echo json_encode(_w('OK'));?>

        }
    });
})(jQuery);</script>
<?php }} ?>