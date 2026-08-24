<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileSidebar.inc.html" */ ?>
<?php /*%%SmartyHeaderCode:10190324916a889d14abe993-90722193%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd0bf5f6e337e388be4e4eebde39abd5c487496e8' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileSidebar.inc.html',
      1 => 1651070886,
      2 => 'file',
    ),
    '810e5c92101062b93287c9d834addd15f7a83aee' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/sidebarWidgets/Access.html',
      1 => 1679048654,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10190324916a889d14abe993-90722193',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'user' => 0,
    'is_system_profile' => 0,
    'stats_html' => 0,
    'wa' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d14ad9591_54133618',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d14ad9591_54133618')) {function content_6a889d14ad9591_54133618($_smarty_tpl) {?><div class="sidebar right flexbox width-adaptive-wider t-profile-sidebar desktop-only custom-ml-auto"><div class="sidebar-body box js-profile-sidebar-body"><?php /*  Call merged included template "./sidebarWidgets/Access.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("./sidebarWidgets/Access.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '10190324916a889d14abe993-90722193');
content_6a889d14ac1174_49472195($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "./sidebarWidgets/Access.html" */?><?php if ($_smarty_tpl->tpl_vars['user']->value&&!$_smarty_tpl->tpl_vars['is_system_profile']->value){?><?php $_smarty_tpl->tpl_vars['stats_html'] = new Smarty_variable($_smarty_tpl->getSubTemplate ("./sidebarWidgets/Stats.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0));?>
<?php echo $_smarty_tpl->tpl_vars['wa']->value->contactProfileSidebar($_smarty_tpl->tpl_vars['user']->value['id'],array('is_profile_sidebar'=>true,'html'=>array('stats'=>$_smarty_tpl->tpl_vars['stats_html']->value)));?>
<?php echo $_smarty_tpl->tpl_vars['wa']->value->contactProfileTabs($_smarty_tpl->tpl_vars['user']->value['id'],array('is_profile_sidebar'=>true));?>
<?php }?></div></div>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/sidebarWidgets/Access.html" */ ?>
<?php if ($_valid && !is_callable('content_6a889d14ac1174_49472195')) {function content_6a889d14ac1174_49472195($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_wa_date')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.wa_date.php';
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