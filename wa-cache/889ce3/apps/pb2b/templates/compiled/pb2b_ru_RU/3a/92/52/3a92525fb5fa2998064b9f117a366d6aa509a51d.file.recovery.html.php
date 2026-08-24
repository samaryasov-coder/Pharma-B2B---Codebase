<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:05:50
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/auth/recovery.html" */ ?>
<?php /*%%SmartyHeaderCode:11752167826a89bace077d61-65180752%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3a92525fb5fa2998064b9f117a366d6aa509a51d' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/auth/recovery.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11752167826a89bace077d61-65180752',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89bace079e85_47534777',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89bace079e85_47534777')) {function content_6a89bace079e85_47534777($_smarty_tpl) {?><form class="auth-form auth-recovery" action="/recovery/" method="POST">
    <a href="/auth/login/" class="auth-form__back button text">
        <i class="fas fa-chevron-left"></i>
        <span>Назад</span>
    </a>
    <div class="auth-form__body">
        <div class="auth-form__auth-flow">
            <span class="text-h1 fw-bold">Восстановление пароля</span>
            <div class="auth-form__fields">
                <div class="toggle medium" data-toggle-id="auth">
                    <span data-type="phone" class="selected">Телефон</span>
                    <span data-type="email">E-mail</span>
                </div>
                <div class="auth-form__credentials">
                    <div class="auth-form__switcher switcher" data-toggle-id="auth">
                        <div class="auth-form__input-wrapper" data-type="phone">
                            <div class="input-wrap">
                                <label class="input-label" for="phone">Номер телефона</label>
                                <div class="input-box">
                                    <span class="input-prefix">+7 |</span>
                                    <input id="phone" name="phone" type="text" class="input mask_phone" placeholder="Напишите свой номер телефона">
                                </div>
                                <span class="input-hint"></span>
                            </div>
                        </div>
                        <div class="auth-form__input-wrapper" data-type="email">
                            <div class="input-wrap">
                                <label class="input-label" for="email">E-mail</label>
                                <div class="input-box">
                                    <input id="email" name="email" type="text" class="input" placeholder="Напишите свой e-mail" disabled>
                                </div>
                                <span class="input-hint"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-form__actions">
            <button class="button primary large" type="submit">Отправить код верификации</button>
        </div>
    </div>
</form>

<script>
    $(function() {
        $.Auth.Recovery.init();
    });
</script><?php }} ?>