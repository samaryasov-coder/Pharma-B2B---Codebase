<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:05:57
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/auth/registration.html" */ ?>
<?php /*%%SmartyHeaderCode:10308347256a89bad53064a8-51373070%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6689d3973662be66daf911cecdd3c6332e0ed207' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/auth/registration.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10308347256a89bad53064a8-51373070',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89bad5307f68_45656900',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89bad5307f68_45656900')) {function content_6a89bad5307f68_45656900($_smarty_tpl) {?><form class="auth-form auth-register" action="/registration/" method="POST">
    <a href="/auth/login/" class="auth-form__back button text">
        <i class="fas fa-chevron-left"></i>
        <span>Назад</span>
    </a>
    <div class="auth-form__body">
        <div class="auth-form__auth-flow">
            <span class="text-h1 fw-bold">Регистрация в системе</span>
            <div class="auth-form__fields">
                <div class="input-wrap">
                    <label class="input-label" for="phone">Контактный номер телефона</label>
                    <div class="input-box">
                        <span class="input-prefix">+7 |</span>
                        <input id="phone" name="phone" type="text" class="input mask_phone" placeholder="Напишите свой номер телефона">
                    </div>
                    <span class="input-hint"></span>
                </div>
            </div>
        </div>
        <div class="auth-form__actions">
            <button class="button primary large" type="submit">Отправить код верификации</button>
            <div class="auth-form__action">
                <span class="text-p2 fw-regular">Уже есть аккаунт?</span>
                <a href="/auth/login/" class="button secondary large">Войти</a>
            </div>
        </div>
    </div>
    <div class="auth-form__footer">
        <span class="text-p3 fw-regular">
            Продолжая Вы соглашаетесь с
            <a href="#" class="link">Условиями пользования</a> и <a href="#" class="link">Политикой конфиденциальности</a>.
        </span>
    </div>
</form>

<script>
    $(function() {
        $.Auth.Register.init();
    });
</script><?php }} ?>