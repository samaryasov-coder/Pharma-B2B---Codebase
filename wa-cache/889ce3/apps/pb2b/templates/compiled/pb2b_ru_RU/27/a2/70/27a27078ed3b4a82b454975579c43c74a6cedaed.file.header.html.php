<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:02
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/header.html" */ ?>
<?php /*%%SmartyHeaderCode:4783433196a889cf7a57a40-30336310%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '27a27078ed3b4a82b454975579c43c74a6cedaed' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/header.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4783433196a889cf7a57a40-30336310',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889cf7a5a881_21433075',
  'variables' => 
  array (
    'wa_theme_url' => 0,
    'wa' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889cf7a5a881_21433075')) {function content_6a889cf7a5a881_21433075($_smarty_tpl) {?><header class="header">
    <svg style="display:none;">
        <defs>
            <g id="icon-mobile-nav"><path d="M32.5 16.5C32.7761 16.5 33 16.7239 33 17C33 17.2761 32.7761 17.5 32.5 17.5H11.5C11.2239 17.5 11 17.2761 11 17C11 16.7239 11.2239 16.5 11.5 16.5H32.5Z" fill="#0B4780"/><path d="M32.5 21.5C32.7761 21.5 33 21.7239 33 22C33 22.2761 32.7761 22.5 32.5 22.5H17.5C17.2239 22.5 17 22.2761 17 22C17 21.7239 17.2239 21.5 17.5 21.5H32.5Z" fill="#0B4780"/><path d="M32.5 26.5C32.7761 26.5 33 26.7239 33 27C33 27.2761 32.7761 27.5 32.5 27.5H11.5C11.2239 27.5 11 27.2761 11 27C11 26.7239 11.2239 26.5 11.5 26.5H32.5Z" fill="#0B4780"/></g>
            <g id="icon-profile"><path d="M13 2C19.0751 2 24 6.92487 24 13C24 19.0751 19.0751 24 13 24C6.92487 24 2 19.0751 2 13C2 6.92487 6.92487 2 13 2Z" fill="#CCD9E3" stroke="#CCD9E3" stroke-width="2.5"/><circle cx="13.0001" cy="10.8333" r="4.33333" fill="#0B4780"/><path d="M13.0002 16.25C15.9392 16.25 18.4877 17.6831 19.7465 19.7785C19.8044 19.875 19.7867 19.9984 19.705 20.0758C17.9575 21.7324 15.5982 22.75 13.0002 22.75C10.4023 22.75 8.04301 21.7324 6.29549 20.0758C6.21379 19.9984 6.19607 19.875 6.25404 19.7785C7.51282 17.6831 10.0613 16.25 13.0002 16.25Z" fill="#0B4780"/></g>
        </defs>
    </svg>


    <div class="header__container container">
        <a href="/" class="header__logo">
            <img src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
img/icons/favicon.svg" alt="PharmaB2B">
        </a>

        <nav class="header__nav">
            <ul class="header__nav-pages">
                <li><a href="/start/">Начало работы</a></li>
                <li><a href="/supplier/">Поставщикам</a></li>
                <li><a href="/buyer/">Покупателям</a></li>
                <li><a href="/tariff/">Тарифы</a></li>
                <li><a href="/training/">Обучение</a></li>
                <li><a href="/info/">О площадке</a></li>
            </ul>
        </nav>

        <div class="header__actions">
            <div class="header__action-pc-nav">
                <?php if ($_smarty_tpl->tpl_vars['wa']->value->user()->isAuth()){?>
                    <a href="/cabinet/" class="btn btn--primary">
                        <span class="btn__text">Личный кабинет</span>
                    </a>
                    <a class="btn btn--secondary js-logout">
                        <span class="btn__text">Выход</span>
                    </a>
                <?php }else{ ?>
                    <a href="/auth/login/" class="btn btn--primary">
                        <span class="btn__text">Вход</span>
                    </a>
                <?php }?>

            </div>


            <div class="header_action-mobile-nav">
                <a href="/auth/login/">
                    <svg class="icon" width="36" height="36" viewBox="0 0 26 26">
                        <use xlink:href="#icon-profile"></use>
                    </svg>
                </a>

                <button class="mobile-nav-toggle" aria-label="Открыть меню">
                    <svg class="icon" width="60" height="60" viewBox="0 0 45 43">
                        <use xlink:href="#icon-mobile-nav"></use>
                    </svg>
                </button>
            </div>
        </div>

    </div>
</header>
<?php }} ?>