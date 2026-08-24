<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 15:03:30
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/auth/login.html" */ ?>
<?php /*%%SmartyHeaderCode:14936993976a89ba42308f88-35360770%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2f4ca8415f6ce08fd3991d84a83111c1123aa11a' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/auth/login.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14936993976a89ba42308f88-35360770',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89ba4230a326_75895688',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89ba4230a326_75895688')) {function content_6a89ba4230a326_75895688($_smarty_tpl) {?><form class="auth-form auth-login" action="/login/" method="POST" autocomplete="off">
    <a href="/" class="auth-form__back button text">
        <i class="fas fa-chevron-left"></i>
        <span>Назад</span>
    </a>
    <div class="auth-form__body">
        <div class="auth-form__auth-flow">
            <span class="text-h1 fw-bold">Вход в систему</span>
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
                    <div class="input-wrap">
                        <label class="input-label" for="password">Пароль</label>
                        <div class="input-box">
                            <input id="password" name="password" type="password" class="input" placeholder="Напишите свой пароль">
                            <div class="input-postfix">
                                <button type="button" class="input-icon js-password-toggle">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <span class="input-hint"></span>
                        <a href="/auth/recovery/" class="button text small ml-auto content-width">Забыли пароль?</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-form__actions">
            <button class="button primary large" type="submit">Войти</button>
            <div class="auth-form__action">
                <span class="text-p2 fw-regular">Ещё не зарегистрированы?</span>
                <a href="/auth/registration/" class="button secondary large">Зарегистрироваться</a>
            </div>
        </div>
    </div>
    <div class="auth-form__footer">
        <span class="text-p2 fw-regular help">
            Если у вас возникли сложности со входом - обратитесь в
            <a href="#" class="link">службу поддержки</a>.
        </span>
    </div>
</form>

<script>
    $(function() {
        $.Auth.Login.init();
    });
</script><?php }} ?>