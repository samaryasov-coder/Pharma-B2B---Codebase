<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileInfo.html" */ ?>
<?php /*%%SmartyHeaderCode:16464920776a889d14bb1127-83605904%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a6764060a4f92f78f47cb43576ba03d8bd5808f5' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/profile/ProfileInfo.html',
      1 => 1639387639,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16464920776a889d14bb1127-83605904',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'can_edit' => 0,
    'is_admin' => 0,
    'contact' => 0,
    'author' => 0,
    'contact_create_time' => 0,
    'assets' => 0,
    'asset' => 0,
    'wa' => 0,
    'wa_url' => 0,
    'wa_app_static_url' => 0,
    'is_my_profile' => 0,
    'wa_backend_url' => 0,
    'wa_app_url' => 0,
    'save_url' => 0,
    'geocoding' => 0,
    'contactFields' => 0,
    'contactFieldsOrder' => 0,
    'fieldValues' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d14bc3764_43161146',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d14bc3764_43161146')) {function content_6a889d14bc3764_43161146($_smarty_tpl) {?>
<div class="block double-padded" id="tc-contact">
    <?php if ($_smarty_tpl->tpl_vars['can_edit']->value){?>
        <a class="float-right no-underline" href="javascript:void(0);" id="edit-contact-link">
            <i class="icon16 edit"></i>Редактировать
        </a>
    <?php }?>

    <div class="fields">
        <div class="contact-info-block" id="contact-info-block">
            
        </div>

        
        <?php if ($_smarty_tpl->tpl_vars['is_admin']->value){?>
            <ul class="hint c-create-method-info">
                <li>ID: <?php echo $_smarty_tpl->tpl_vars['contact']->value['id'];?>
</li>
                <li>Добавил: <?php if (!empty($_smarty_tpl->tpl_vars['author']->value)){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['author']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
 <?php }?><?php echo $_smarty_tpl->tpl_vars['contact_create_time']->value;?>
</li>
                <li>Метод: <?php if ($_smarty_tpl->tpl_vars['contact']->value['create_method']){?><?php echo $_smarty_tpl->tpl_vars['contact']->value['create_method'];?>
 (<?php echo $_smarty_tpl->tpl_vars['contact']->value['create_app_id'];?>
)<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['contact']->value['create_app_id'];?>
<?php }?></li>
            </ul>
        <?php }?>
    </div>
    <div class="clear-left"></div>
</div>

<?php $_smarty_tpl->tpl_vars['assets'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['assets']->value)===null||$tmp==='' ? array() : $tmp), null, 0);?>

<script type="text/javascript">
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
        "or": "или",
        "cancel": "отменить"
    });

    $(function () { "use strict";

        // Load first system assets, make more possible we would't fail from first try of loading all dependencies
        var files = { };
        <?php  $_smarty_tpl->tpl_vars['asset'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['asset']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['assets']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['asset']->key => $_smarty_tpl->tpl_vars['asset']->value){
$_smarty_tpl->tpl_vars['asset']->_loop = true;
?>
            files["<?php echo $_smarty_tpl->tpl_vars['asset']->value;?>
?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"] = true;
        <?php } ?>

        files["<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-plugins/jquery.store.js?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"] = typeof $.store !== 'function';
        if($('html').hasClass('is-wa2')) {
            files["<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js/profile.info.js?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"] = typeof window.ProfileInfoTab !== 'function';
            files["<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css/profile_info.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"] = typeof window.ProfileInfoTab !== 'function';
        }else{
            files["<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
js-legacy/profile.info.js?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"] = typeof window.ProfileInfoTab !== 'function';
            files["<?php echo $_smarty_tpl->tpl_vars['wa_app_static_url']->value;?>
css-legacy/profile_info.css?<?php echo $_smarty_tpl->tpl_vars['wa']->value->version();?>
"] = typeof window.ProfileInfoTab !== 'function';
        }

        var onAllLoad = function () {
            $.storage = new $.store();

            <?php if (!empty($_smarty_tpl->tpl_vars['is_my_profile']->value)){?>
                $.wa.contactEditor.wa_app_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_backend_url']->value);?>
;
            <?php }else{ ?>
                $.wa.contactEditor.wa_app_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
;
            <?php }?>

            $.wa.contactEditor.wa_backend_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_backend_url']->value);?>
;
            $.wa.contactEditor.saveUrl = <?php echo json_encode($_smarty_tpl->tpl_vars['save_url']->value);?>
;
            $.wa.contactEditor.contact_id = <?php echo json_encode($_smarty_tpl->tpl_vars['contact']->value['id']);?>
;
            $.wa.contactEditor.current_user_id = <?php echo json_encode($_smarty_tpl->tpl_vars['wa']->value->user('id'));?>
;
            $.wa.contactEditor.contactType = '<?php if ($_smarty_tpl->tpl_vars['contact']->value['is_company']){?>company<?php }else{ ?>person<?php }?>';
            $.wa.contactEditor.justCreated = false;
            $.wa.contactEditor.geocoding = <?php echo json_encode($_smarty_tpl->tpl_vars['geocoding']->value);?>
;

            $.wa.contactEditor.initFactories(<?php echo json_encode($_smarty_tpl->tpl_vars['contactFields']->value);?>
, <?php echo json_encode($_smarty_tpl->tpl_vars['contactFieldsOrder']->value);?>
);
            $.wa.contactEditor.resetFieldEditors();
            $.wa.contactEditor.initFieldEditors(<?php echo json_encode($_smarty_tpl->tpl_vars['fieldValues']->value);?>
);
            $.wa.contactEditor.initContactInfoBlock('view');

            // Edit button onclick
            $('#edit-contact-link').click(function() {
                $.wa.contactEditor.switchMode('edit');
                return false;
            });

            $($.wa.contactEditor).on('top_fields_updated', function(evt, data) {
                window.profileTab.trigger('top_fields_updated', [data.data]);
            });
            $($.wa.contactEditor).on('contact_saved', function(evt, data) {
                window.profileTab.trigger('contact_saved', data);
                if (data && data.timezone === '') {
                    // If user has just changed their timezone setting to 'Auto',
                    // determine timezone via JS.
                    $.wa.determineTimezone(<?php echo json_encode($_smarty_tpl->tpl_vars['wa_url']->value);?>
);
                }
            });
        };

        var loadAllFiles = function () {
            return $.wa.loadFiles(files);
        };

        var load = function (tries) {
            loadAllFiles().then(function() {
                onAllLoad();
            }).fail(function () {
                if (tries > 0) {
                    console.log('Not loaded, try again...')
                    load(tries - 1);
                } else {
                    console.error("Couldn't load profile info tab");
                }
            });
        };

        load(3);

        <?php if (!empty($_smarty_tpl->tpl_vars['is_my_profile']->value)&&!$_smarty_tpl->tpl_vars['contact']->value['timezone']){?>
            // If user timezone setting is 'Auto', use JS to set timezone.
            $.wa.determineTimezone(<?php echo json_encode($_smarty_tpl->tpl_vars['wa_url']->value);?>
);
        <?php }?>
    })
</script>
<?php }} ?>