<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:52
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileAccess.html" */ ?>
<?php /*%%SmartyHeaderCode:15676631246a889d1c3c5ed3-69867246%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e56fad22962bb0d44172a3fcb090ec4ca591e896' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileAccess.html',
      1 => 1731320827,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15676631246a889d1c3c5ed3-69867246',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'url_change_password' => 0,
    'is_connected_to_webasyst_id' => 0,
    'contact' => 0,
    'is_bound_with_webasyst_contact' => 0,
    'webasyst_id_email' => 0,
    'customer_center_auth_url' => 0,
    'is_webasyst_id_forced' => 0,
    'is_own_profile' => 0,
    'is_superadmin' => 0,
    'password_block' => 0,
    'waid_block' => 0,
    'gFullAccess' => 0,
    'fullAccess' => 0,
    'own_profile' => 0,
    '_has_credentials' => 0,
    '_selector_avaiable' => 0,
    'frontend_user_backend_access' => 0,
    'message' => 0,
    'event_message' => 0,
    'single_app_id' => 0,
    'noAccess' => 0,
    'access_rights_select_options' => 0,
    'value' => 0,
    'access_rights_selected_value' => 0,
    'label' => 0,
    'gNoAccess' => 0,
    'invite_tokens' => 0,
    'wa' => 0,
    '_token' => 0,
    'g_id' => 0,
    'all_groups' => 0,
    'wa_app_url' => 0,
    'groups' => 0,
    'id' => 0,
    'name' => 0,
    'apps' => 0,
    'app_id' => 0,
    'is_selected' => 0,
    'wa_url' => 0,
    'app' => 0,
    '_rights' => 0,
    'access_types' => 0,
    '_access' => 0,
    'at' => 0,
    'status' => 0,
    'access_disable_msg' => 0,
    'api_tokens' => 0,
    '_app' => 0,
    'personal_portal_available' => 0,
    'wa_backend_url' => 0,
    'email_change_log' => 0,
    'log_item' => 0,
    'wa_app_static_url' => 0,
    'url_change_api_token' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d1c41fb34_16293390',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d1c41fb34_16293390')) {function content_6a889d1c41fb34_16293390($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_wa_date')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.wa_date.php';
if (!is_callable('smarty_modifier_join')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.join.php';
?><style>
    .t-app-icon {
        width: 32px;
        line-height: 0;
        padding: 0 8px 0 0;
    }
    .t-app-icon img {
        width: 100%;
        height: auto;
    }
    .t-app-name {
        padding: 0 8px 0 0;
    }
    .c-access-rights > table {
        margin: 12px 0 0;
    }
    .c-access-rights table .t-access-status {
        width: auto;
    }
    .t-app-name-mobile {
        display: none;
    }
    .c-access-single-app tr:not(.t-single-app-selected) > td > .t-access-status {
        visibility: hidden;
    }
    @media (max-width: 768px) {
        .t-app-icon {
            width: auto;
            vertical-align: top;
            text-align: center;
        }
        .t-app-icon img {
            display: block;
            width: 32px;
            margin: 7px auto 10px;
        }
        .t-app-name {
            display: none;
        }
        .t-app-name-mobile {
            display: inline;
        }
    }
</style>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', "password_block", null); ob_start(); ?>
    <div id="c-password-block" class="custom-mt-24" style="display:none;">
        <form id="c-password-form" method="post" action="<?php echo $_smarty_tpl->tpl_vars['url_change_password']->value;?>
">
            <div class="c-one-tab">
                <div class="field">
                    <div class="name custom-pt-0">Пароль</div>
                    <div class="value">
                        <a href="javascript:void(0)" class="small c-tab-toggle semibold">
                            <i class="fas fa-pen custom-mr-4"></i>Сменить пароль
                        </a>
                        <p class="hint custom-mt-4">Для доступа в личный кабинет и в бекенд используется единый пароль.</p>
                    </div>
                </div>
            </div>
            <div class="c-two-tab" style="display:none;">
                <div class="field">
                    <div class="name">Новый пароль</div>
                    <div class="value">
                        <input name="password" type="password" class="c-password-input small" autocomplete="off" />
                    </div>
                </div>
                <div class="field">
                    <div class="name">Подтверждение пароля</div>
                    <div class="value">
                        <input name="confirm_password" type="password" class="c-confirm-password-input small" autocomplete="off" />
                    </div>
                </div>
                <div class="field custom-mt-12">
                    <div class="value submit">
                        <input type="submit" class="button small" value="Сохранить">
                        <a class="cancel c-tab-toggle button light-gray small" href="javascript:void(0)">Отмена</a>
                        <i class="spinner loading custom-ml-8" style="display: none;"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', "waid_block", null); ob_start(); ?>
    <?php if ($_smarty_tpl->tpl_vars['is_connected_to_webasyst_id']->value&&($_smarty_tpl->tpl_vars['contact']->value['is_user']=='1'||$_smarty_tpl->tpl_vars['is_bound_with_webasyst_contact']->value)){?>
        <div class="c-waid custom-mt-12">
            <div class="field">
                <div class="name">Вход с Webasyst ID</div>
                <div class="value">

                    <?php if ($_smarty_tpl->tpl_vars['is_bound_with_webasyst_contact']->value){?>

                        
                        <span class="icon custom-mr-8">
                            <svg width="97" height="96" viewBox="0 0 97 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <style>
                                        .webasyst-magic-wand-handle { fill: black; }
                                        [data-theme="dark"] .webasyst-magic-wand-handle { fill: #ffffff; }
                                    </style>
                                </defs>
                                <path d="M20.7348 14.5035C18.57 12.1327 14.8662 12.0487 12.5963 14.3185C10.3239 16.5908 10.4084 20.3016 12.7813 22.468L28.8528 37.141C30.6852 38.8139 33.5082 38.75 35.263 36.9953C37.0199 35.2385 37.0843 32.4089 35.4088 30.5739L20.7348 14.5035Z" fill="#0088EE"/>
                                <path d="M47.1515 0C43.9448 0 41.3794 2.67789 41.5255 5.89162L42.5137 27.6305C42.6266 30.1155 44.6731 32.0657 47.1515 32.0657C49.6299 32.0657 51.6763 30.1155 51.7893 27.6305L52.7775 5.89161C52.9236 2.67788 50.3582 0 47.1515 0Z" fill="#22CC22"/>
                                <path d="M81.6725 14.3185C79.4026 12.0487 75.6988 12.1327 73.534 14.5035L58.86 30.5739C57.1845 32.4089 57.2489 35.2385 59.0058 36.9952C60.7606 38.75 63.5836 38.8139 65.416 37.141L81.4875 22.468C83.8604 20.3016 83.9449 16.5908 81.6725 14.3185Z" fill="#EE6622"/>
                                <path d="M96.0089 48.8621C96.0089 45.6452 93.3248 43.0868 90.1215 43.2324L68.3812 44.2205C65.9052 44.3331 63.9492 46.3744 63.9492 48.8621C63.9492 51.3499 65.9052 53.3912 68.3812 53.5037L90.1215 54.4918C93.3248 54.6374 96.0089 52.079 96.0089 48.8621Z" fill="#FFCC00"/>
                                <path d="M65.416 60.5964C63.5836 58.9235 60.7606 58.9874 59.0058 60.7421C57.2489 62.4989 57.1845 65.3285 58.86 67.1635L73.534 83.234C75.6988 85.6048 79.4026 85.6887 81.6725 83.419C83.9449 81.1467 83.8604 77.4359 81.4875 75.2694L65.416 60.5964Z" fill="#55DDDD"/>
                                <path d="M51.2853 44.7066C49.5341 42.9555 46.7086 42.9175 44.911 44.6204L1.92878 85.3376C-0.595136 87.7285 -0.649357 91.7324 1.80924 94.1908C4.26542 96.6469 8.26241 96.593 10.6515 94.0713L51.3715 51.092C53.0769 49.292 53.0386 46.4598 51.2853 44.7066Z" class="webasyst-magic-wand-handle" />
                            </svg>
                        </span>
                        <span class="small">
                            <?php if ($_smarty_tpl->tpl_vars['webasyst_id_email']->value){?>
                                <?php echo sprintf(_w('Webasyst ID account <span class="bold">%s</span> is connected.'),$_smarty_tpl->tpl_vars['webasyst_id_email']->value);?>

                            <?php }else{ ?>
                                <?php echo sprintf(_w('%sSign-in with Webasyst ID%s is connected.'),'<span class="bold">','</span>');?>

                            <?php }?>
                        </span>

                        
                        <?php if ($_smarty_tpl->tpl_vars['customer_center_auth_url']->value){?>
                            <a class="bold underline js-customer-center-auth small" href="<?php echo $_smarty_tpl->tpl_vars['customer_center_auth_url']->value;?>
"><i class="icon10" style="background-image: url('/wa-content/img/wa-settings/ws-waid-green.svg'); background-size: 14px 14px; background-position: 0 0;width: 14px;height: 14px; margin: 0 5px;"></i>Перейти в Центр заказчика Webasyst</a>
                        <?php }?>

                        <?php if (!$_smarty_tpl->tpl_vars['is_webasyst_id_forced']->value){?>
                            <a href="#" class="js-webasyst-id-unbind-auth text-gray custom-ml-8 small"><i class="fas fa-times-circle"></i> Отключить</a>
                        <?php }?>
                        <p class="hint custom-mt-4 custom-mb-0"><span class="custom-mr-8">Последний вход <?php echo mb_strtolower(smarty_modifier_wa_date($_smarty_tpl->tpl_vars['contact']->value['last_datetime'],"humandatetime"), 'UTF-8');?>
</span></p>
                    <?php }elseif($_smarty_tpl->tpl_vars['is_own_profile']->value){?>

                        
                        <div class="waid-login custom-m-0 ">
                            <div>
                                <p class="custom-m-0 small">
                                    <a class="button webasyst-magic-wand js-webasyst-id-auth" href="javascript:void(0)">
                                        <svg class="icon" style="background-image: none;" width="97" height="96" viewBox="0 0 97 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <style>
                                            .webasyst-magic-wand-handle { fill: black; }
                                            [data-theme="dark"] .webasyst-magic-wand-handle { fill: #ffffff; }
                                        </style>
                                    </defs>
                                    <path d="M20.7348 14.5035C18.57 12.1327 14.8662 12.0487 12.5963 14.3185C10.3239 16.5908 10.4084 20.3016 12.7813 22.468L28.8528 37.141C30.6852 38.8139 33.5082 38.75 35.263 36.9953C37.0199 35.2385 37.0843 32.4089 35.4088 30.5739L20.7348 14.5035Z" fill="#0088EE"/>
                                    <path d="M47.1515 0C43.9448 0 41.3794 2.67789 41.5255 5.89162L42.5137 27.6305C42.6266 30.1155 44.6731 32.0657 47.1515 32.0657C49.6299 32.0657 51.6763 30.1155 51.7893 27.6305L52.7775 5.89161C52.9236 2.67788 50.3582 0 47.1515 0Z" fill="#22CC22"/>
                                    <path d="M81.6725 14.3185C79.4026 12.0487 75.6988 12.1327 73.534 14.5035L58.86 30.5739C57.1845 32.4089 57.2489 35.2385 59.0058 36.9952C60.7606 38.75 63.5836 38.8139 65.416 37.141L81.4875 22.468C83.8604 20.3016 83.9449 16.5908 81.6725 14.3185Z" fill="#EE6622"/>
                                    <path d="M96.0089 48.8621C96.0089 45.6452 93.3248 43.0868 90.1215 43.2324L68.3812 44.2205C65.9052 44.3331 63.9492 46.3744 63.9492 48.8621C63.9492 51.3499 65.9052 53.3912 68.3812 53.5037L90.1215 54.4918C93.3248 54.6374 96.0089 52.079 96.0089 48.8621Z" fill="#FFCC00"/>
                                    <path d="M65.416 60.5964C63.5836 58.9235 60.7606 58.9874 59.0058 60.7421C57.2489 62.4989 57.1845 65.3285 58.86 67.1635L73.534 83.234C75.6988 85.6048 79.4026 85.6887 81.6725 83.419C83.9449 81.1467 83.8604 77.4359 81.4875 75.2694L65.416 60.5964Z" fill="#55DDDD"/>
                                    <path d="M51.2853 44.7066C49.5341 42.9555 46.7086 42.9175 44.911 44.6204L1.92878 85.3376C-0.595136 87.7285 -0.649357 91.7324 1.80924 94.1908C4.26542 96.6469 8.26241 96.593 10.6515 94.0713L51.3715 51.092C53.0769 49.292 53.0386 46.4598 51.2853 44.7066Z" class="webasyst-magic-wand-handle" />
                                </svg>
                                        &nbsp;<span>Подключить Webasyst ID</span>
                                    </a>
                                </p>
                                <p class="hint custom-mt-8">Входите в свой Вебасист безопасно с использованием двухфакторной авторизации (2FA) и получите удобный доступ ко всем сервисам и мобильным приложениям Webasyst. <a href="javascript:void(0);" class="js-webasyst-id-help-link">Как это работает?</a></p>
                            </div>
                        </div>
                    <?php }else{ ?>

                        

                        <span class="custom-mb-4 small"><i class="fas fa-unlink text-light-gray"></i> <span class="text-gray bold custom-ml-4">Webasyst ID не подключен</span></span>
                        <p class="hint custom-my-4"><?php echo sprintf('%s еще не подключил вход c Webasyst ID. Возможность включения безопасного входа с двухфакторной авторизацией (2FA) и доступом ко всем сервисам и мобильным приложениям Webasyst появится только после подключения Webasyst ID.',htmlspecialchars((string)$_smarty_tpl->tpl_vars['contact']->value['login'], ENT_QUOTES, 'UTF-8', true));?>
</p>
                    <?php }?>
                </div>
            </div>
        </div>
        <script>
            ( function($) {
                $('.js-webasyst-id-help-link').on('click', function (e) {
                    e.preventDefault();
                    $(this).attr('data-trigger', 'wa_waid_help_link')
                });
                $('.js-webasyst-id-unbind-auth').on('click', function (e) {
                    e.preventDefault();
                    $(this).attr('data-trigger', 'wa_waid_unbind_auth');

                    $(this).attr('data-wa_waid_unbind_auth', JSON.stringify({
                        id: <?php echo $_smarty_tpl->tpl_vars['contact']->value['id'];?>

                    }))
                });
            })(jQuery);
        </script>
    <?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<div class="fields" id="profile-access-wrapper">

<?php if (!$_smarty_tpl->tpl_vars['is_superadmin']->value){?>

    <div class="basic-user-fields width-100">
        <?php if (!$_smarty_tpl->tpl_vars['is_webasyst_id_forced']->value){?>
            <div class="field">
                <div class="name">Логин</div>
                <div class="value">
                    <span class="c-login"><?php if ($_smarty_tpl->tpl_vars['contact']->value['login']){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['contact']->value['login'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></span>
                </div>
            </div>
            <?php echo $_smarty_tpl->tpl_vars['password_block']->value;?>

        <?php }?>
        <?php echo $_smarty_tpl->tpl_vars['waid_block']->value;?>

    </div>

<?php }elseif($_smarty_tpl->tpl_vars['is_superadmin']->value){?>

<div class="c-shown-on-enabled width-100 custom-mb-24"<?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']==='-1'){?> style="display:none;"<?php }?> id="c-available-resourses">

    <div class="field">
        <div class="name for-input">Бекенд</div>
        <div class="value<?php if ($_smarty_tpl->tpl_vars['gFullAccess']->value){?> custom-pt-8<?php }?>">
            <?php if ($_smarty_tpl->tpl_vars['gFullAccess']->value){?>
                <strong>Администратор</strong>
                <div class="hint">Этот уровень доступа унаследован от групп. Чтобы изменить его, отредактируйте настройки этих групп или измените набор групп для данного сотрудника.</div>
            <?php }elseif($_smarty_tpl->tpl_vars['fullAccess']->value&&!empty($_smarty_tpl->tpl_vars['own_profile']->value)){?>
                <strong>Администратор</strong>
                <div class="hint">Вы не можете аннулировать административный уровень доступа для самого себя. Только другой администратор может это сделать.</div>
            <?php }else{ ?>

                <?php $_smarty_tpl->tpl_vars['_has_credentials'] = new Smarty_variable($_smarty_tpl->tpl_vars['contact']->value['login']&&$_smarty_tpl->tpl_vars['contact']->value['password'], null, 0);?>

                
                <?php $_smarty_tpl->tpl_vars['_selector_avaiable'] = new Smarty_variable(!$_smarty_tpl->tpl_vars['is_webasyst_id_forced']->value||$_smarty_tpl->tpl_vars['_has_credentials']->value, null, 0);?>

                <?php if ($_smarty_tpl->tpl_vars['_selector_avaiable']->value){?>
                    <?php $_smarty_tpl->tpl_vars['event_message'] = new Smarty_variable('', null, 0);?>
                    <!-- plugin hook: 'frontend_user_backend_access' -->
                    
                    <?php  $_smarty_tpl->tpl_vars['message'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['message']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['frontend_user_backend_access']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['message']->key => $_smarty_tpl->tpl_vars['message']->value){
$_smarty_tpl->tpl_vars['message']->_loop = true;
?>
                        <?php if ($_smarty_tpl->tpl_vars['message']->value){?>
                            <?php $_smarty_tpl->tpl_vars['event_message'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['event_message']->value)." ".((string)$_smarty_tpl->tpl_vars['message']->value), null, 0);?>
                        <?php }?>
                    <?php } ?>

                    <?php if ($_smarty_tpl->tpl_vars['single_app_id']->value){?>
                        <?php $_smarty_tpl->tpl_vars['access_rights_selected_value'] = new Smarty_variable('single_app_mode', null, 0);?>
                    <?php }elseif($_smarty_tpl->tpl_vars['fullAccess']->value){?>
                        <?php $_smarty_tpl->tpl_vars['access_rights_selected_value'] = new Smarty_variable('1', null, 0);?>
                    <?php }elseif($_smarty_tpl->tpl_vars['noAccess']->value){?>
                        <?php $_smarty_tpl->tpl_vars['access_rights_selected_value'] = new Smarty_variable('remove', null, 0);?>
                    <?php }else{ ?>
                        <?php $_smarty_tpl->tpl_vars['access_rights_selected_value'] = new Smarty_variable('0', null, 0);?>
                    <?php }?>

                    <?php $_smarty_tpl->tpl_vars['access_rights_select_options'] = new Smarty_variable(array('remove'=>_w('No access'),'0'=>_w('Limited access'),'1'=>_w('Administrator'),'single_app_mode'=>_w('Single app')), null, 0);?>

                    <div class="custom-mb-8">
                        <div class="wa-select small">
                            <select id="c-access-rights-toggle" <?php if ($_smarty_tpl->tpl_vars['event_message']->value){?>disabled<?php }?>>
                                <?php  $_smarty_tpl->tpl_vars['label'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['label']->_loop = false;
 $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['access_rights_select_options']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['label']->key => $_smarty_tpl->tpl_vars['label']->value){
$_smarty_tpl->tpl_vars['label']->_loop = true;
 $_smarty_tpl->tpl_vars['value']->value = $_smarty_tpl->tpl_vars['label']->key;
?>
                                    <option value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['value']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php if ($_smarty_tpl->tpl_vars['value']->value==$_smarty_tpl->tpl_vars['access_rights_selected_value']->value){?> selected="selected"<?php }?>><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['label']->value, ENT_QUOTES, 'UTF-8', true);?>
</option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php if ($_smarty_tpl->tpl_vars['event_message']->value){?>
                            <div class="hint custom-mt-4">
                                <?php echo preg_replace('!\s+!u', ' ',$_smarty_tpl->tpl_vars['event_message']->value);?>

                            </div>
                        <?php }?>
                    </div>
                    <div>
                        <span id="access-rights-toggle-confirm" style="display:none;">
                            <a href="javascript:void(0)" class="button small custom-mb-8">Подтвердите изменение доступа</a>
                            <a href="javascript:void(0)" class="cancel button light-gray small">Отмена</a>
                        </span>

                        <?php if (!$_smarty_tpl->tpl_vars['gNoAccess']->value){?>
                            <p id="c-access-rights-hint-warning" class="state-error-hint custom-mt-4 custom-mb-0" style="display:none;"><span>Нельзя выбрать «Нет доступа», потому что некоторые права доступа унаследованы от групп. Для лишения прав доступа отредактируйте настройки групп или набор групп для данного сотрудника.</span></p>
                        <?php }?>

                        <i class="spinner loading custom-ml-8" style="display:none;"></i>
                        <span class="c-access-rights-hint c-access-saved-hint" style="display:none"><i class="fas fa-check-circle text-green"></i> Сохранено</span>
                    </div>
                <?php }else{ ?>
                    <span>Нет доступа</span>
                <?php }?>
            <?php }?>
        </div>
    </div>

    <?php if ($_smarty_tpl->tpl_vars['invite_tokens']->value){?>
    <div class="field">
        <div class="value submit">
            <ul>
            <?php  $_smarty_tpl->tpl_vars['_token'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_token']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['invite_tokens']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_token']->key => $_smarty_tpl->tpl_vars['_token']->value){
$_smarty_tpl->tpl_vars['_token']->_loop = true;
?>
                <li>
                    <div class="custom-mb-16">
                        <code><?php echo $_smarty_tpl->tpl_vars['wa']->value->url(true);?>
link.php/<?php echo urlencode($_smarty_tpl->tpl_vars['_token']->value['token']);?>
/</code>
                        <p class="small custom-mt-8 custom-mb-4">Истекает через <?php echo $_smarty_tpl->tpl_vars['_token']->value['expires_in'];?>
</p>
                        <p class="hint custom-mt-4">Сотрудник должен перейти по ссылке-приглашению, чтобы получить доступ.</p>
                    </div>
                    <?php if ($_smarty_tpl->tpl_vars['_token']->value['data']){?>
                        <?php $_smarty_tpl->tpl_vars['a'] = new Smarty_variable(json_decode($_smarty_tpl->tpl_vars['_token']->value['data'],true), null, 0);?>
                        <?php if (!empty($_smarty_tpl->tpl_vars['_token']->value['groups'])){?>
                            <p class="custom-m-0">
                            <?php  $_smarty_tpl->tpl_vars['g_id'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['g_id']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_token']->value['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['g_id']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['g_id']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['g_id']->key => $_smarty_tpl->tpl_vars['g_id']->value){
$_smarty_tpl->tpl_vars['g_id']->_loop = true;
 $_smarty_tpl->tpl_vars['g_id']->iteration++;
 $_smarty_tpl->tpl_vars['g_id']->last = $_smarty_tpl->tpl_vars['g_id']->iteration === $_smarty_tpl->tpl_vars['g_id']->total;
?>
                                <?php if (isset($_smarty_tpl->tpl_vars['all_groups']->value[$_smarty_tpl->tpl_vars['g_id']->value])){?>
                                    <span<?php if (!$_smarty_tpl->tpl_vars['g_id']->last){?> class="custom-mr-4"<?php }?>>
                                        <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['all_groups']->value[$_smarty_tpl->tpl_vars['g_id']->value], ENT_QUOTES, 'UTF-8', true);?>
<?php if (!$_smarty_tpl->tpl_vars['g_id']->last){?>,<?php }?>
                                    </span>
                                <?php }?>
                            <?php } ?>
                            </p>
                        <?php }?>
                    <?php }?>
                </li>
            <?php } ?>
            </ul>
        </div>
        <script>
            const $code = document.querySelector('code');
            if ($code) {
                $code.onclick = () => {
                    const range = new Range();
                    range.setStart($code.firstChild, 0);
                    range.setEnd($code.firstChild, $code.firstChild.length);

                    document.getSelection().removeAllRanges();
                    document.getSelection().addRange(range);
                }
            }
        </script>
    </div>
    <?php }?>

    <?php if (!$_smarty_tpl->tpl_vars['is_webasyst_id_forced']->value){?>
        <div id="c-credentials-block" class="custom-mt-24" style="display:none;">
            <form id="c-credentials-form" method="post" action="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?module=accessSave&action=grant&id=<?php echo $_smarty_tpl->tpl_vars['contact']->value['id'];?>
">
                <div class="field">
                    <div class="name">Логин</div>
                    <div class="value">
                        <input type="text" name="login" class="c-login-input small" value="<?php if ($_smarty_tpl->tpl_vars['contact']->value['login']){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['contact']->value['login'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" autocomplete="off" /><br>
                        <span class="hint">
                            Логин обязателен для доступа в бекенд.<br>
                            Пример: john, agent07 и т. д.
                        </span>
                    </div>
                </div>
                <div class="field">
                    <div class="name">Новый пароль</div>
                    <div class="value">
                        <input name="password" type="password" class="c-password-input small" autocomplete="off" />
                    </div>
                </div>
                <div class="field">
                    <div class="name">Подтверждение пароля</div>
                    <div class="value">
                        <input name="confirm_password" type="password" class="c-confirm-password-input small" autocomplete="off" /><br>
                        <span class="hint">Для доступа в личный кабинет и в бекенд используется единый пароль.</span>
                    </div>
                </div>
                <div class="field">
                    <div class="name"></div>
                    <div class="value">
                        <input type="submit" class="button small" value="Сохранить">
                        <a class="button light-gray cancel small" href="javascirt:void(0);">Отмена</a>
                    </div>
                </div>
            </form>
        </div>

        <div id="c-login-block" class="custom-mt-16" style="display:none;">
            <form id="c-login-form" method="post" action="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?module=accessSave&action=login&id=<?php echo $_smarty_tpl->tpl_vars['contact']->value['id'];?>
">
                <div class="field">
                    <div class="name custom-pt-0">Логин</div>
                    <div class="value">
                        <div class="c-one-tab">
                            <span class="c-login"><?php if ($_smarty_tpl->tpl_vars['contact']->value['login']){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['contact']->value['login'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></span>
                            &nbsp;
                            <a href="javascript:void(0)" class="small c-tab-toggle semibold nowrap">
                                <i class="fas fa-pen custom-mr-4"></i>Сменить логин сотрудника
                            </a>
                        </div>
                        <div class="c-two-tab" style="display:none;">
                            <input type="text" name="login" class="c-login-input small" value="<?php if ($_smarty_tpl->tpl_vars['contact']->value['login']){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['contact']->value['login'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" autocomplete="off" />
                            <p class="hint custom-mt-4 custom-mb-12">
                                Логин обязателен для доступа в бекенд.<br>
                                Пример: john, agent07 и т. д.
                            </p>
                            <input type="submit" class="button small" value="Сохранить">
                            <a class="cancel button light-gray c-tab-toggle small" href="javascript:void(0);">Отмена</a>
                            <i class="spinner loading custom-ml-8" style="display: none;"></i>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php echo $_smarty_tpl->tpl_vars['password_block']->value;?>

    <?php }?>

    <?php echo $_smarty_tpl->tpl_vars['waid_block']->value;?>


    <div class="c-shown-on-access custom-mt-24">
        <div class="field">
            <div class="name custom-pt-0">Принадлежит к группам</div>
            <div class="value custom-pb-16">
                <?php if ($_smarty_tpl->tpl_vars['groups']->value){?>
                    <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['name']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['name']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['name']->key;
 $_smarty_tpl->tpl_vars['name']->iteration++;
 $_smarty_tpl->tpl_vars['name']->last = $_smarty_tpl->tpl_vars['name']->iteration === $_smarty_tpl->tpl_vars['name']->total;
?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
group/<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
/"<?php if ($_smarty_tpl->tpl_vars['name']->last){?> class="custom-mr-16"<?php }?>><?php if (strlen($_smarty_tpl->tpl_vars['name']->value)>0){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?>&lt;без названия&gt;<?php }?></a><?php if (!$_smarty_tpl->tpl_vars['name']->last){?>,<?php }?>
                    <?php } ?>
                <?php }else{ ?>
                    &lt;нет&gt;
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['all_groups']->value){?>
                    <a href="javascript:void(0)" class="small semibold nowrap" id="open-customize-groups">
                        <i class="fas fa-pen custom-mr-4"></i>Настроить группы
                    </a>
                <?php }?>
            </div>
        </div>
        <?php if ($_smarty_tpl->tpl_vars['all_groups']->value){?>
        <div class="field custom-mt-0">
            <div class="value submit">
                <form id="form-customize-groups" style="display:none;" method="post" action="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?module=accessSave&action=savegroups&id=<?php echo $_smarty_tpl->tpl_vars['contact']->value['id'];?>
">
                    <ul class="unstyled">
                        <li class="c-checkbox-menu-container width-100-mobile wide">
                            <div>
                                <ul class="zebra c-checkbox-menu small">
                                    <?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['all_groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['name']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['name']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value){
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['name']->key;
 $_smarty_tpl->tpl_vars['name']->iteration++;
 $_smarty_tpl->tpl_vars['name']->last = $_smarty_tpl->tpl_vars['name']->iteration === $_smarty_tpl->tpl_vars['name']->total;
?>
                                        <li>
                                            <label>
                                                <span class="wa-checkbox">
                                                    <input type="checkbox" name="groups[]" value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"<?php if (isset($_smarty_tpl->tpl_vars['groups']->value[$_smarty_tpl->tpl_vars['id']->value])){?> checked="checked"<?php }?> />
                                                    <span>
                                                        <span class="icon">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    </span>
                                                </span>
                                                <?php if (strlen($_smarty_tpl->tpl_vars['name']->value)>0){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?>&lt;без названия&gt;<?php }?>
                                            </label>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </li>
                        <li class="custom-mt-12">
                            <input type="hidden" name="set" value="1">
                            <input type="submit" class="button small" value="Сохранить">
                            <a class="button light-gray small" href="javascript:void(0)" id="cancel-customize-groups">Отмена</a>
                            <i class="spinner loading custom-ml-8" style="display: none;"></i>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <?php }?>
        <div class="field c-access-single-app" id="c-access-single-app-by-app" style="display: none">
            <div class="name">Одно приложение</div>
            <div class="value c-access-rights c-access-rights-wrapper small custom-pt-4">
                <span>Сотрудник не увидит никаких других приложений, кроме выбранного.</span>
                <table>
                    <tbody>
                    <?php  $_smarty_tpl->tpl_vars['app'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['app']->_loop = false;
 $_smarty_tpl->tpl_vars['app_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['apps']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['app']->key => $_smarty_tpl->tpl_vars['app']->value){
$_smarty_tpl->tpl_vars['app']->_loop = true;
 $_smarty_tpl->tpl_vars['app_id']->value = $_smarty_tpl->tpl_vars['app']->key;
?>
                    <?php $_smarty_tpl->tpl_vars['is_selected'] = new Smarty_variable($_smarty_tpl->tpl_vars['single_app_id']->value&&$_smarty_tpl->tpl_vars['single_app_id']->value==$_smarty_tpl->tpl_vars['app_id']->value, null, 0);?>
                        <tr <?php if ($_smarty_tpl->tpl_vars['is_selected']->value){?>class="t-single-app-selected"<?php }?>>
                            <td class="min-width" style="min-width:1.35rem;">
                                <label>
                                    <span class="wa-radio large">
                                        <input type="radio" id="t-radio-<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
" class="c-access-single-app-checkbox" name="single_app_id" value="<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['is_selected']->value){?> checked<?php }?>>
                                        <span></span>
                                    </span>
                                </label>
                            </td>
                            <td class="t-app-icon">
                                <label for="t-radio-<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['app']->value['img'];?>
">
                                    <span class="t-app-name-mobile"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['app']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                                </label>
                            </td>
                            <td class="t-app-name">
                                <label for="t-radio-<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
">
                                    <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['app']->value['name'], ENT_QUOTES, 'UTF-8', true);?>

                                </label>
                            </td>
                            <td class="t-app-access width-40">
                                <?php $_smarty_tpl->tpl_vars['_rights'] = new Smarty_variable(max($_smarty_tpl->tpl_vars['app']->value['gaccess'],$_smarty_tpl->tpl_vars['app']->value['access']), null, 0);?>
                                <?php if ($_smarty_tpl->tpl_vars['_rights']->value>1){?>
                                    <?php $_smarty_tpl->tpl_vars['_access'] = new Smarty_variable($_smarty_tpl->tpl_vars['access_types']->value['full'], null, 0);?>
                                <?php }elseif($_smarty_tpl->tpl_vars['_rights']->value){?>
                                    <?php $_smarty_tpl->tpl_vars['_access'] = new Smarty_variable($_smarty_tpl->tpl_vars['access_types']->value['limited'], null, 0);?>
                                <?php }else{ ?>
                                    <?php $_smarty_tpl->tpl_vars['_access'] = new Smarty_variable($_smarty_tpl->tpl_vars['access_types']->value['no'], null, 0);?>
                                <?php }?>
                                <div class="t-access-status type-<?php echo $_smarty_tpl->tpl_vars['_access']->value['id'];?>
"
                                    data-app-id="<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
"
                                    title="Редактировать доступ"
                                >
                                    
                                    <i class="fas fa-edit"></i>
                                    <?php  $_smarty_tpl->tpl_vars['at'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['at']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['access_types']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['at']->key => $_smarty_tpl->tpl_vars['at']->value){
$_smarty_tpl->tpl_vars['at']->_loop = true;
?>
                                        <span class="t-access-name type-<?php echo $_smarty_tpl->tpl_vars['at']->value['id'];?>
"><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['at']->value['name'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</span>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="field c-access-rights" id="c-access-rights-by-app" style="display: none">
            <div class="name">Права доступа</div>
            <div class="value c-access-rights c-access-rights-wrapper small custom-pt-4">
                Права доступа в бекенде настраиваются для каждого приложения отдельно и складываются из прав персональных и унаследованных от групп.
                <table
                    <tbody>
                    <?php  $_smarty_tpl->tpl_vars['app'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['app']->_loop = false;
 $_smarty_tpl->tpl_vars['app_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['apps']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['app']->key => $_smarty_tpl->tpl_vars['app']->value){
$_smarty_tpl->tpl_vars['app']->_loop = true;
 $_smarty_tpl->tpl_vars['app_id']->value = $_smarty_tpl->tpl_vars['app']->key;
?>
                        <tr>
                            <td class="t-app-icon">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['app']->value['img'];?>
">
                                <span class="t-app-name-mobile"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['app']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                            </td>
                            <td class="t-app-name">
                                <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['app']->value['name'], ENT_QUOTES, 'UTF-8', true);?>

                            </td>
                            <td class="t-app-access">
                                <?php $_smarty_tpl->tpl_vars['_rights'] = new Smarty_variable(max($_smarty_tpl->tpl_vars['app']->value['gaccess'],$_smarty_tpl->tpl_vars['app']->value['access']), null, 0);?>
                                <?php if ($_smarty_tpl->tpl_vars['_rights']->value>1){?>
                                    <?php $_smarty_tpl->tpl_vars['_access'] = new Smarty_variable($_smarty_tpl->tpl_vars['access_types']->value['full'], null, 0);?>
                                <?php }elseif($_smarty_tpl->tpl_vars['_rights']->value){?>
                                    <?php $_smarty_tpl->tpl_vars['_access'] = new Smarty_variable($_smarty_tpl->tpl_vars['access_types']->value['limited'], null, 0);?>
                                <?php }else{ ?>
                                    <?php $_smarty_tpl->tpl_vars['_access'] = new Smarty_variable($_smarty_tpl->tpl_vars['access_types']->value['no'], null, 0);?>
                                <?php }?>
                                <div class="t-access-status type-<?php echo $_smarty_tpl->tpl_vars['_access']->value['id'];?>
"
                                    data-app-id="<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
"
                                    title="Редактировать доступ"
                                >
                                    
                                    <i class="fas fa-edit"></i>
                                    <?php  $_smarty_tpl->tpl_vars['at'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['at']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['access_types']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['at']->key => $_smarty_tpl->tpl_vars['at']->value){
$_smarty_tpl->tpl_vars['at']->_loop = true;
?>
                                        <span class="t-access-name type-<?php echo $_smarty_tpl->tpl_vars['at']->value['id'];?>
"><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['at']->value['name'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</span>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php }?>

    <div class="basic-user-fields width-100 custom-mt-0">
        
        <div class="field">
            <div class="name">Доступ</div>
            <div class="value">
                <ul>
                    <li>
                        
                        <?php if (empty($_smarty_tpl->tpl_vars['own_profile']->value)){?><a href="#"id="c-access-link-block"class="c-shown-on-enabled semibold"<?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']=='-1'){?> style="display:none"<?php }?>data-alert="Заблокировать этот контакт?"><i class="fas fa-times-circle text-red"></i> Заблокировать контакт</a><?php $_smarty_tpl->tpl_vars['event_message'] = new Smarty_variable('', null, 0);?><?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']=='-1'){?><!-- plugin hook: 'frontend_user_backend_access' --><?php  $_smarty_tpl->tpl_vars['message'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['message']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['frontend_user_backend_access']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['message']->key => $_smarty_tpl->tpl_vars['message']->value){
$_smarty_tpl->tpl_vars['message']->_loop = true;
?><?php if ($_smarty_tpl->tpl_vars['message']->value){?><?php $_smarty_tpl->tpl_vars['event_message'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['event_message']->value)." ".((string)$_smarty_tpl->tpl_vars['message']->value), null, 0);?><?php }?><?php } ?><?php }?><a href="javascript:void(0)"<?php if (empty($_smarty_tpl->tpl_vars['event_message']->value)){?>id="c-access-link-unblock"data-alert="Разблокировать этот контакт?"<?php }?><?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']!='-1'){?> style="display:none"<?php }?>class="c-shown-on-disabled semibold<?php if (!empty($_smarty_tpl->tpl_vars['event_message']->value)){?> gray<?php }?>">Разблокировать</a><?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']=='-1'&&!empty($_smarty_tpl->tpl_vars['event_message']->value)){?><div class="hint"><?php echo preg_replace('!\s+!u', ' ',$_smarty_tpl->tpl_vars['event_message']->value);?>
</div><?php }?><i class="fas fa-spinner fa-spin loading wa-animation-spin speed-1000" style="display:none;"></i><form class="js-block-user-reason-form custom-mt-16" style="display: none"><textarea class="js-block-user-reason small" placeholder="Необязательный комментарий о причине блокировки"></textarea><p class="hint">Контакт будет оставаться авторизованным около 2 минут после блокировки доступа.</p><div class="custom-mt-8 custom-mb-16"><input class="button red small" type="submit" value="Заблокировать"><a href="javascript:void(0)" class="js-block-user-cancel button light-gray small">Отмена</a></div></form><?php }else{ ?>
                            <?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']=='-1'){?>Выключен<?php }else{ ?>Включен<?php }?>
                        <?php }?>
                    </li>
                    <li>
                        <div class="c-shown-on-enabled hint" id="tc-user-status-field"<?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']==='-1'){?> style="display:none;"<?php }?>>
                            Статус:
                                <?php $_smarty_tpl->tpl_vars['status'] = new Smarty_variable($_smarty_tpl->tpl_vars['contact']->value->getStatus(), null, 0);?>
                                <?php if ($_smarty_tpl->tpl_vars['status']->value=='online'){?>
                                    <span class="bold">
                                        <i class="fa fa-circle text-green fa-xs"></i> <span class="c-user-status-online">Онлайн</span>
                                    </span>
                                <?php }else{ ?>
                                    <span class="bold">
                                        <i class="fa fa-circle text-yellow fa-xs"></i>
                                        <span class="c-user-status-offline">
                                            Офлайн
                                            <?php if ($_smarty_tpl->tpl_vars['contact']->value['last_datetime']){?>
                                                <span class="small no-bold">(последний вход: <?php echo smarty_modifier_wa_date($_smarty_tpl->tpl_vars['contact']->value['last_datetime'],"datetime");?>
)</span>
                                            <?php }?>
                                        </span>
                                    </span>
                                <?php }?>
                        </div>
                        <div id="tc-user-access-disabled" class="small c-shown-on-disabled"<?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']!='-1'){?> style="display:none;"<?php }?>>
                            <?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']=='-1'){?><?php echo $_smarty_tpl->tpl_vars['access_disable_msg']->value;?>
<?php }?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        
        <?php if ($_smarty_tpl->tpl_vars['is_superadmin']->value&&!empty($_smarty_tpl->tpl_vars['api_tokens']->value)){?>
            <div class="field c-shown-on-enabled" id="tc-api-tokens-filed">
                <div class="name">Токены API</div>
                <div class="value">
                    <table class="zebra small js-api-tokens-list">
                        <thead>
                            <tr>
                                <th class="tc-column"></th>
                                <th class="tc-column-date">Дата создания</th>
                                <th class="tc-column-client-id">Идентификатор клиента</th>
                                <th class="tc-column-scope">Доступ</th>
                                <th class="tc-column-last-use-datetime">Дата последнего использования</th>
                                <th class="tc-column-expires">Истекает</th>
                                <th class="tc-column-remove"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  $_smarty_tpl->tpl_vars['_token'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_token']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['api_tokens']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_token']->key => $_smarty_tpl->tpl_vars['_token']->value){
$_smarty_tpl->tpl_vars['_token']->_loop = true;
?>
                                <tr class="js-token-item" data-token="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_token']->value['token'], ENT_QUOTES, 'UTF-8', true);?>
">
                                    <td class="tc-column"></td>
                                    <td class="tc-column-date"><?php echo smarty_modifier_wa_date($_smarty_tpl->tpl_vars['_token']->value['create_datetime'],'humandatetime');?>
</td>
                                    <td class="tc-column-client-id"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_token']->value['client_id'], ENT_QUOTES, 'UTF-8', true);?>
</td>
                                    <td class="tc-column-scope">
                                        <?php  $_smarty_tpl->tpl_vars['_app'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_app']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_token']->value['installed_apps']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['_app']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['_app']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['_app']->key => $_smarty_tpl->tpl_vars['_app']->value){
$_smarty_tpl->tpl_vars['_app']->_loop = true;
 $_smarty_tpl->tpl_vars['_app']->iteration++;
 $_smarty_tpl->tpl_vars['_app']->last = $_smarty_tpl->tpl_vars['_app']->iteration === $_smarty_tpl->tpl_vars['_app']->total;
?>
                                            <img src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['_app']->value['img'];?>
" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_app']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" style="width: 16px; height: 16px;" />
                                        <?php } ?>
                                        <?php  $_smarty_tpl->tpl_vars['_app'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_app']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_token']->value['not_installed_apps']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['_app']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['_app']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['_app']->key => $_smarty_tpl->tpl_vars['_app']->value){
$_smarty_tpl->tpl_vars['_app']->_loop = true;
 $_smarty_tpl->tpl_vars['_app']->iteration++;
 $_smarty_tpl->tpl_vars['_app']->last = $_smarty_tpl->tpl_vars['_app']->iteration === $_smarty_tpl->tpl_vars['_app']->total;
?>
                                            <span class="text-light-gray"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['_app']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php if (!$_smarty_tpl->tpl_vars['_app']->last){?>,<?php }?></span>
                                        <?php } ?>
                                    </td>
                                    <td class="tc-column-last-use-datetime">
                                        <?php if (!empty($_smarty_tpl->tpl_vars['_token']->value['last_use_datetime'])){?>
                                            <?php echo smarty_modifier_wa_date($_smarty_tpl->tpl_vars['_token']->value['last_use_datetime'],'humandatetime');?>

                                        <?php }else{ ?>
                                            <span class="text-light-gray">—</span>
                                        <?php }?>
                                    </td>
                                    <td class="tc-column-expires">
                                        <?php if (!empty($_smarty_tpl->tpl_vars['_token']->value['expires'])){?>
                                            <?php echo smarty_modifier_wa_date($_smarty_tpl->tpl_vars['_token']->value['expires'],'humandatetime');?>

                                        <?php }else{ ?>
                                            <span class="text-light-gray">—</span>
                                        <?php }?>
                                    </td>
                                    <td class="tc-column-remove">
                                        <a href="#" title="Удалить токен" class="js-remove-api-token"><i class="fas fa-trash-alt text-red"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php }?>

        
        <div class="field c-shown-on-enabled" id="tc-customer-portal-filed"<?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']==='-1'){?> style="display:none;"<?php }?>>
            <div class="name custom-pt-0">Личный кабинет</div>
            <div class="value">
                <?php if ($_smarty_tpl->tpl_vars['personal_portal_available']->value){?>
                    Доступен
                <?php }else{ ?>
                    Недоступен
                <?php }?>
                <p class="hint custom-mt-4">
                    Личный кабинет — это часть вашего сайта, доступная каждому зарегистрированному клиенту и содержащая персональные данные клиента. Для доступа в личный кабинет клиентам потребуется авторизация с помощью email-адреса и пароля.
                    <?php if ($_smarty_tpl->tpl_vars['is_superadmin']->value){?>
                        <?php echo sprintf('Управляйте <a href="%s" class="no-underline">настройками личного кабинета</a> в приложении «Сайт».',((string)$_smarty_tpl->tpl_vars['wa_backend_url']->value)."site/#/personal/settings");?>

                    <?php }?>
                </p>
            </div>
        </div>

        <div class="field c-shown-on-enabled" id="tc-email-change-log"<?php if ($_smarty_tpl->tpl_vars['contact']->value['is_user']==='-1'||!$_smarty_tpl->tpl_vars['email_change_log']->value){?> style="display:none;"<?php }?>>
            <div class="name">История изменений email-адреса</div>
            <div class="value">
                <ul class="menu">
                    <?php  $_smarty_tpl->tpl_vars['log_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['log_item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['email_change_log']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['log_item']->key => $_smarty_tpl->tpl_vars['log_item']->value){
$_smarty_tpl->tpl_vars['log_item']->_loop = true;
?>
                        <li data-id="<?php echo $_smarty_tpl->tpl_vars['log_item']->value['id'];?>
">
                            <span>
                                <?php echo smarty_modifier_wa_date($_smarty_tpl->tpl_vars['log_item']->value['datetime'],'humandatetime');?>

                            </span>
                            <span>
                                <?php echo htmlspecialchars((string)smarty_modifier_join($_smarty_tpl->tpl_vars['log_item']->value['emails'],', '), ENT_QUOTES, 'UTF-8', true);?>

                            </span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

</div>

<div id="tc-api-dialog"></div>

<link href="<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/team.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
" rel="stylesheet"/>
<script>
    $.wa.loadFiles({
        "<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/team.js?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
": typeof window.TeamDialog !== 'function',
        "<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/access.js?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
": typeof window.AccessDialog !== 'function'
    }).then(function() {
        $.team.app_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
;
        $(function() {
            new ProfileAccessTab({
                contact_id: <?php echo json_encode($_smarty_tpl->tpl_vars['contact']->value['id']);?>
,
                login: <?php if ($_smarty_tpl->tpl_vars['contact']->value['login']){?><?php echo json_encode($_smarty_tpl->tpl_vars['contact']->value['login']);?>
<?php }else{ ?>null<?php }?>,
                password: <?php echo json_encode((bool) $_smarty_tpl->tpl_vars['contact']->value['password']);?>
,

                wa_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_url']->value);?>
,
                wa_app_url: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
,
                is_own_profile: <?php echo (int)ifempty($_smarty_tpl->tpl_vars['own_profile']->value);?>
,
                wa_framework_version: <?php echo json_encode($_smarty_tpl->tpl_vars['wa']->value->version(true));?>
,

                // true if user / all groups do not have any rights set up
                contact_no_access: <?php echo (int)(boolean)$_smarty_tpl->tpl_vars['noAccess']->value;?>
,
                contact_groups_no_access: <?php echo (int)(boolean)$_smarty_tpl->tpl_vars['gNoAccess']->value;?>
,

                url_change_api_token: <?php echo json_encode($_smarty_tpl->tpl_vars['url_change_api_token']->value);?>
,

                loc: {
                    "Passwords do not match.": "Пароли не совпадают.",
                    "Login is required.": "Логин обязателен.",
                    "cancel": "отменить",
                    "Cancel": "Отмена",
                    "blockUser": "Заблокировать",
                    "unblockUser": "Разблокировать",
                    "Save": "Сохранить",
                    "remove_ask": "Пользователь не сможет отправлять запросы API, используя этот токен."
                }
            });
        });
    });
</script>
<?php }} ?>