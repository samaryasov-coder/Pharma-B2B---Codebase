<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:43
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/users/Users.inc.html" */ ?>
<?php /*%%SmartyHeaderCode:6662870896a889d134dec77-42187667%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '260434d1c1dc50d7ec593c4dce2db0a200d135c9' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/team/templates/actions/users/Users.inc.html',
      1 => 1648029634,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6662870896a889d134dec77-42187667',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa' => 0,
    '_is_admin' => 0,
    '_has_rights' => 0,
    'sort' => 0,
    'list_context' => 0,
    'contacts' => 0,
    'user' => 0,
    'user_data' => 0,
    'wa_app_url' => 0,
    '_title' => 0,
    '_l' => 0,
    '_user_uri' => 0,
    '_user_id' => 0,
    '_badge_status_styles' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d13507747_57369731',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d13507747_57369731')) {function content_6a889d13507747_57369731($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_wa_date')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.wa_date.php';
?><?php $_smarty_tpl->tpl_vars['_is_admin'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin($_smarty_tpl->tpl_vars['wa']->value->app()), null, 0);?><?php $_smarty_tpl->tpl_vars['_has_rights'] = new Smarty_variable(teamHelper::hasRights(), null, 0);?><?php $_smarty_tpl->tpl_vars['_can_drag'] = new Smarty_variable(($_smarty_tpl->tpl_vars['_is_admin']->value||$_smarty_tpl->tpl_vars['_has_rights']->value), null, 0);?><?php $_smarty_tpl->tpl_vars['_user_id'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->user()->getId(), null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['sort']->value)&&$_smarty_tpl->tpl_vars['sort']->value==='last_seen'){?><h3>Онлайн</h3><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['list_context']->value)&&$_smarty_tpl->tpl_vars['list_context']->value==='invited'){?><ul class="unstyled t-invited-list"><?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['contacts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['user']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['user']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value){
$_smarty_tpl->tpl_vars['user']->_loop = true;
 $_smarty_tpl->tpl_vars['user']->iteration++;
 $_smarty_tpl->tpl_vars['user']->last = $_smarty_tpl->tpl_vars['user']->iteration === $_smarty_tpl->tpl_vars['user']->total;
?><?php $_smarty_tpl->tpl_vars['user_data'] = new Smarty_variable(waUser::getNameAndStatus($_smarty_tpl->tpl_vars['user']->value,true), null, 0);?><?php $_smarty_tpl->tpl_vars['_title'] = new Smarty_variable(htmlspecialchars((string)$_smarty_tpl->tpl_vars['user_data']->value['formatted_user_name'], ENT_QUOTES, 'UTF-8', true), null, 0);?><?php $_smarty_tpl->tpl_vars['_user_uri'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."id/".((string)$_smarty_tpl->tpl_vars['user']->value['id'])."/", null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['login'])&&$_smarty_tpl->tpl_vars['user']->value['login']!=$_smarty_tpl->tpl_vars['_title']->value){?><?php $_smarty_tpl->tpl_vars['_l'] = new Smarty_variable(htmlspecialchars((string)urlencode($_smarty_tpl->tpl_vars['user']->value['login']), ENT_QUOTES, 'UTF-8', true), null, 0);?><?php $_smarty_tpl->tpl_vars['_user_uri'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."u/".((string)$_smarty_tpl->tpl_vars['_l']->value)."/", null, 0);?><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['list_context']->value)){?><?php $_smarty_tpl->tpl_vars['_user_uri'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_user_uri']->value)."?list=".((string)$_smarty_tpl->tpl_vars['list_context']->value), null, 0);?><?php }?><li class="t-user-wrapper flexbox custom-py-12 text-gray<?php if (!$_smarty_tpl->tpl_vars['user']->last){?> bordered-bottom<?php }?>" data-contact-id="<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['_user_uri']->value;?>
" class="userpic userpic32 custom-mr-12 custom-mt-4" style="background-image: url('<?php echo $_smarty_tpl->tpl_vars['user']->value['photo_url_32'];?>
');"></a><div class="user-info wide flexbox wrap"><div class="user-info--details custom-mr-auto width-100-mobile custom-mb-8"><a href="<?php echo $_smarty_tpl->tpl_vars['_user_uri']->value;?>
" class="bold" title="<?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
</a><p class="smaller custom-m-0">Истекает через <?php echo $_smarty_tpl->tpl_vars['user']->value['expires_in'];?>
</p></div><div class="user-info--actions small width-100-mobile flexbox wrap"><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['email'])){?><a href="javascript:void(0)" class="js-invite custom-mr-16 custom-mb-4" data-type="email" data-email="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['email'][0], ENT_QUOTES, 'UTF-8', true);?>
"><i class="fas fa-envelope"></i> Отправить еще раз</a><a href="javascript:void(0)" class="js-invite" data-type="link"><i class="fas fa-link text-gray"></i> Ссылка на приглашение</a><?php }else{ ?><a href="javascript:void(0)" class="js-invite" data-type="link"><i class="fas fa-link text-gray"></i> Ссылка на приглашение</a><?php }?></div></div><?php if (teamUser::canDelete($_smarty_tpl->tpl_vars['_user_id']->value)){?><a href="javascript:void(0)" class="user-delete js-delete-user custom-ml-24 small gray"><i class="fas fa-trash-alt text-red"></i><i class="fas fa-spinner fa-spin hidden wa-animation-spin speed-1000"></i></a><?php }?></li><?php } ?></ul><script>(function ($) {$('.js-invite').on('click', function(e) {e.preventDefault();if(!!this.dataset.isLocked) {return;}this.dataset.isLocked = 'true';const $link = this,$parent = $link.closest('[data-contact-id]'),type = $link.dataset.type;let href, post_data;if(type === 'email'){href = '?module=users&action=invite';post_data = { email: this.dataset.email ?? '' };$link.innerHTML = '<i class="fas fa-spinner fa-spin wa-animation-spin speed-1000"></i> Отправляется';}else if(type === 'link') {href = '?module=users&action=createInvitation';post_data = { contact_id: $parent.dataset.contactId ?? 0 };}$.post(href, post_data, function(response) {if(response.status === 'ok') {if(type === 'email'){$link.classList.add('text-green');$link.innerHTML = '<i class="fas fa-check"></i> Отправлено';}else if(type === 'link'){$link.insertAdjacentHTML('afterend', `<input type="text" style="position:absolute;opacity:0" class="js-invite-link" value="${ response.data.invitation_link ?? '' }">`);let $invite_link = document.querySelector('.js-invite-link');$invite_link.select();$invite_link.setSelectionRange(0, 99999);document.execCommand("copy");$invite_link.remove();$link.classList.add('text-green');$link.innerHTML = '<i class="fas fa-check"></i> Скопировано';}}}).always( function() {setTimeout(()=>{$link.classList.remove('text-green');if(type === 'email'){$link.innerHTML = '<i class="fas fa-envelope"></i> Отправить еще раз';}else if(type === 'link'){$link.innerHTML = '<i class="fas fa-link"></i> Ссылка на приглашение';}$link.dataset.isLocked = '';}, 1500);});});<?php if (teamUser::canDelete($_smarty_tpl->tpl_vars['_user_id']->value)){?>$('.js-delete-user').on('click', function(e) {e.preventDefault();const $delete_btn = $(this);$delete_btn.find('.fa-trash-alt').addClass('hidden');$delete_btn.find('.fa-spinner').removeClass('hidden');new MutationObserver(mutations => {for(const mutation of mutations) {if(mutation.addedNodes.length) {for(let value of mutation.addedNodes.values()) {if(value.classList.contains('t-confirm-deletion-dialog'))$delete_btn.find('.fa-spinner').addClass('hidden');$delete_btn.find('.fa-trash-alt').removeClass('hidden');}}}}).observe(document.body, { childList: true});$.team.confirmContactDelete([this.closest('[data-contact-id]').dataset.contactId ?? 0], true);});<?php }?>})(jQuery);</script><?php }else{ ?><ul class="thumbs li150px t-users-list custom-mt-32 js-users-list" id="t-users-list"><?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['contacts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['user']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['user']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value){
$_smarty_tpl->tpl_vars['user']->_loop = true;
 $_smarty_tpl->tpl_vars['user']->iteration++;
 $_smarty_tpl->tpl_vars['user']->last = $_smarty_tpl->tpl_vars['user']->iteration === $_smarty_tpl->tpl_vars['user']->total;
?><?php $_smarty_tpl->tpl_vars['user_data'] = new Smarty_variable(waUser::getNameAndStatus($_smarty_tpl->tpl_vars['user']->value,true), null, 0);?><?php $_smarty_tpl->tpl_vars['_title'] = new Smarty_variable(htmlspecialchars((string)$_smarty_tpl->tpl_vars['user_data']->value['formatted_user_name'], ENT_QUOTES, 'UTF-8', true), null, 0);?><?php $_smarty_tpl->tpl_vars['_user_uri'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."id/".((string)$_smarty_tpl->tpl_vars['user']->value['id'])."/", null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['login'])&&$_smarty_tpl->tpl_vars['user']->value['login']!=$_smarty_tpl->tpl_vars['_title']->value){?><?php $_smarty_tpl->tpl_vars['_l'] = new Smarty_variable(htmlspecialchars((string)urlencode($_smarty_tpl->tpl_vars['user']->value['login']), ENT_QUOTES, 'UTF-8', true), null, 0);?><?php $_smarty_tpl->tpl_vars['_user_uri'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."u/".((string)$_smarty_tpl->tpl_vars['_l']->value)."/", null, 0);?><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['list_context']->value)){?><?php $_smarty_tpl->tpl_vars['_user_uri'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['_user_uri']->value)."?list=".((string)$_smarty_tpl->tpl_vars['list_context']->value), null, 0);?><?php }?><li class="t-user-wrapper <?php if ($_smarty_tpl->tpl_vars['user']->value['is_user']>=1&&!$_smarty_tpl->tpl_vars['wa']->value->isMobile()){?>js-move-user<?php }?>" data-user-id="<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
" data-update-datetime="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['user']->value['update_datetime'])===null||$tmp==='' ? '' : $tmp);?>
"><a class="userpic userpic144" href="<?php echo $_smarty_tpl->tpl_vars['_user_uri']->value;?>
" title="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" style="background-image: url('<?php echo $_smarty_tpl->tpl_vars['user']->value['photo_url_144'];?>
'); background-size: cover;"><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['birth_day'])&&$_smarty_tpl->tpl_vars['user']->value['birth_day']==waDateTime::format('j')&&$_smarty_tpl->tpl_vars['user']->value['birth_month']==waDateTime::format('n')){?><span class="userstatus birthday" title="<?php echo sprintf(_ws('%s\'s birthday — %s'),$_smarty_tpl->tpl_vars['user_data']->value['formatted_user_name'],$_smarty_tpl->tpl_vars['user_data']->value['user_birthday_str']);?>
"><i class="fas fa-birthday-cake"></i></span><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['_online_status'])&&($_smarty_tpl->tpl_vars['user']->value['_online_status']==='online'||$_smarty_tpl->tpl_vars['user']->value['_online_status']==='idle')){?><span class="userstatus<?php if ($_smarty_tpl->tpl_vars['user']->value['_online_status']==='idle'){?> idle<?php }?>"></span><?php }?></a><div class="details"><h6 class="t-name custom-my-4"><a class="text-blue" href="<?php echo $_smarty_tpl->tpl_vars['_user_uri']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
</a><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['_event'])){?><div class="align-center"><?php $_smarty_tpl->tpl_vars['_badge_status_styles'] = new Smarty_variable('', null, 0);?><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['_event']['status_bg_color'])){?><?php $_smarty_tpl->tpl_vars['_badge_status_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['user']->value['_event']['status_font_color'])."; background: ".((string)$_smarty_tpl->tpl_vars['user']->value['_event']['status_bg_color']).";", null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_badge_status_styles'] = new Smarty_variable("color: ".((string)$_smarty_tpl->tpl_vars['user']->value['_event']['font_color'])."; background: ".((string)$_smarty_tpl->tpl_vars['user']->value['_event']['bg_color']).";", null, 0);?><?php }?><span class="badge user small custom-mt-8" style="<?php echo $_smarty_tpl->tpl_vars['_badge_status_styles']->value;?>
"><i class="<?php if (!empty($_smarty_tpl->tpl_vars['user']->value['_event']['icon'])){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['_event']['icon'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?>fas fa-calendar-alt<?php }?>"></i><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['_event']['summary'], ENT_QUOTES, 'UTF-8', true);?>
</span></div><?php }?></h6><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['jobtitle'])){?><div class="t-job small"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['jobtitle'], ENT_QUOTES, 'UTF-8', true);?>
</div><?php }?><div class="t-login hint"><?php if (!empty($_smarty_tpl->tpl_vars['user']->value['login'])&&$_smarty_tpl->tpl_vars['user']->value['login']!=waUser::formatName($_smarty_tpl->tpl_vars['user']->value)){?>@<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['login'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(!empty($_smarty_tpl->tpl_vars['user']->value['name'])&&$_smarty_tpl->tpl_vars['user']->value['name']!=waUser::formatName($_smarty_tpl->tpl_vars['user']->value)){?>@<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['user']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></div><?php if (!empty($_smarty_tpl->tpl_vars['sort']->value)&&$_smarty_tpl->tpl_vars['sort']->value==='signed_up'&&$_smarty_tpl->tpl_vars['user']->value['create_datetime']){?><em class="hint"><?php echo smarty_modifier_wa_date($_smarty_tpl->tpl_vars['user']->value['create_datetime'],'humandate');?>
</em><?php }?></div></li><?php } ?></ul><?php }?>
<?php }} ?>