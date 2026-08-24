<?php /* Smarty version Smarty-3.1.14, created on 2026-08-23 15:21:47
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/cabinet/layout.html" */ ?>
<?php /*%%SmartyHeaderCode:4783465856a8b100b06f6d2-89787315%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f935de3f67151db373db7c1cb6ced14f14e66f84' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/html/cabinet/layout.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4783465856a8b100b06f6d2-89787315',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'role' => 0,
    'html_class_array' => 0,
    'wa' => 0,
    '_head_prefix' => 0,
    'wa_active_theme_path' => 0,
    'head_path' => 0,
    'wa_theme_url' => 0,
    'sidebar_menu' => 0,
    'menu_value' => 0,
    'contact' => 0,
    'company' => 0,
    'header_menu' => 0,
    'cabinet_link' => 0,
    'ROLE_BUYER' => 0,
    'ROLE_SUPPLIER' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8b100b088136_66126416',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8b100b088136_66126416')) {function content_6a8b100b088136_66126416($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars["cabinet_link"] = new Smarty_variable(("/cabinet/").($_smarty_tpl->tpl_vars['role']->value), null, 0);?>

<!DOCTYPE html>
<html<?php if (!empty($_smarty_tpl->tpl_vars['html_class_array']->value)){?> class="<?php echo join(' ',$_smarty_tpl->tpl_vars['html_class_array']->value);?>
"<?php }?> lang="<?php if ($_smarty_tpl->tpl_vars['wa']->value->locale()=='ru_RU'){?>ru<?php }else{ ?>en<?php }?>">

    <?php $_smarty_tpl->tpl_vars['_head_prefix'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->globals("headPrefix"), null, 0);?>

    <head <?php if (!empty($_smarty_tpl->tpl_vars['_head_prefix']->value)){?>prefix="<?php echo $_smarty_tpl->tpl_vars['_head_prefix']->value;?>
"<?php }?>>
        <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['wa_active_theme_path']->value)."/head.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    </head>

    <body hx-boost="true" hx-target=".js-main-content" hx-swap="innerHTML" hx-push-url="true">
        <?php echo $_smarty_tpl->getSubTemplate ("img/icons/cabinet/sprite.svg", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


        <?php if (!empty($_smarty_tpl->tpl_vars['head_path']->value)){?>
            <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['wa_active_theme_path']->value)."/".((string)$_smarty_tpl->tpl_vars['head_path']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

        <?php }?>
        <main class="maincontent">
            <div class="flexbox wrap-mobile" id="wa-app">
                <div class="pharmab2b-app-sidebar is-pinned " id="js-app-sidebar">
                    <div class="sidebar flexbox hide-scrollbar overflow-visible hover-is-disabled">
                        <div class="sidebar-body">
                            <div class="head">
                                <a href="/" class="logo">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
img/icons/favicon.svg" alt="PharmaB2B">
                                </a>
                                <span class="js-edit-sidebar button secondary small s-edit-wrapper">
                                    <svg><use href="#icon-edit"></use></svg>
                                </span>

                                <div class="s-toggle-wrapper js-toggle-sidebar">
                                    <span class="pharmab2b-icon-main icon">
                                        <i class="fas fa-angle-left left"></i>
                                        <i class="fas fa-angle-right right"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="menu-wrapper">
                                <div class="main-menu">
                                    <ul class="menu">
                                        <?php  $_smarty_tpl->tpl_vars['menu_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu_value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sidebar_menu']->value[0]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu_value']->key => $_smarty_tpl->tpl_vars['menu_value']->value){
$_smarty_tpl->tpl_vars['menu_value']->_loop = true;
?>
                                            <li>
                                                <a class="item" data-tooltip="<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['name'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['link'];?>
" hx-get="<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['link'];?>
">
                                                    <span class="dot success <?php if (empty($_smarty_tpl->tpl_vars['menu_value']->value['notify'])){?>is-hidden<?php }?>"></span>
                                                    <div class="item-content">
                                                        <span class="pharmab2b-icon-main icon">
                                                            <svg><use href="#<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['icon'];?>
"></use></svg>
                                                        </span>
                                                        <div class="item-meta">
                                                            <span class="pharmab2b-name"><?php echo $_smarty_tpl->tpl_vars['menu_value']->value['name'];?>
</span>
                                                            <?php if (!empty($_smarty_tpl->tpl_vars['menu_value']->value['notify'])){?>
                                                                <span class="counter primary"><?php echo $_smarty_tpl->tpl_vars['menu_value']->value['notify'];?>
</span>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>

                                <div class="main-menu">
                                    <span class="state">Управление</span>
                                    <ul class="menu">
                                        <?php  $_smarty_tpl->tpl_vars['menu_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu_value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sidebar_menu']->value[1]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu_value']->key => $_smarty_tpl->tpl_vars['menu_value']->value){
$_smarty_tpl->tpl_vars['menu_value']->_loop = true;
?>
                                            <li>
                                                <a class="item" data-tooltip="<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['name'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['link'];?>
" hx-get="<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['link'];?>
">
                                                    <span class="dot success <?php if (empty($_smarty_tpl->tpl_vars['menu_value']->value['notify'])){?>is-hidden<?php }?>"></span>
                                                    <div class="item-content">
                                                            <span class="pharmab2b-icon-main icon">
                                                                <svg><use href="#<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['icon'];?>
"></use></svg>
                                                            </span>
                                                        <div class="item-meta">
                                                            <span class="pharmab2b-name"><?php echo $_smarty_tpl->tpl_vars['menu_value']->value['name'];?>
</span>
                                                            <?php if (!empty($_smarty_tpl->tpl_vars['menu_value']->value['notify'])){?>
                                                                <span class="counter primary"><?php echo $_smarty_tpl->tpl_vars['menu_value']->value['notify'];?>
</span>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>


                        <div class="sidebar-footer hidden js-overflowing-gradient">
                            <ul class="menu">
                                <li>
                                    <a class="item" href="#">
                                        <span class="pharmab2b-icon-main icon">
                                            <img src="">
                                        </span>
                                        <span class="pharmab2b-name">Настройки</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="page">
                    <div class="cabinet-head head">
                        <div class="input-wrap small">
                            <div class="input-box">
                            <span class="input-prefix">
                                <svg><use href="#icon-search"></use></svg>
                            </span>
                                <input id="search" name="search" type="text" class="input" placeholder="Поиск">
                            </div>
                        </div>

                        <div class="actions">
                            <div class="action-buttons">
                                <button class="head-button button outline small">
                                    <svg><use href="#icon-message"></use></svg>
                                </button>

                                <button class="head-button button outline small">
                                    <svg><use href="#icon-notify"></use></svg>
                                </button>

                                <div class="menu">
                                    <button class="button outline small js-popover">
                                        <svg><use href="#icon-help"></use></svg>Помощь
                                    </button>

                                    <div class="help-menu-list popover-menu">
                                        <div class="input-wrap small">
                                            <div class="input-box">
                                                <span class="input-prefix">
                                                    <svg><use href="#icon-search"></use></svg>
                                                </span>
                                                <input id="s" name="s" type="text" class="input" placeholder="Поиск">
                                            </div>
                                        </div>
                                        <div class="articles">
                                            <span class="headline">Статьи по разделу</span>
                                            <div class="articles-list">
                                                <span class="article-item">Создание задачи</span>
                                                <span class="article-item">Как использовать сообщения, файлы, задачи?</span>
                                                <span class="article-item">Список задач</span>
                                                <span class="article-item">Контроль исполнения</span>
                                            </div>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="items">
                                            <span class="item">
                                                <svg><use href="#icon-thunder"></use></svg>С чего начать?
                                            </span>
                                            <a class="item">
                                                <svg><use href="#icon-play"></use></svg>Обучающие видео
                                            </a>
                                            <a class="item">
                                                <svg><use href="#icon-headset"></use></svg>Задать вопрос
                                            </a>
                                        </div>
                                        <button class="button secondary small">Попробовать еще раз</button>
                                    </div>
                                </div>
                            </div>

                            <div class="menu">
                                <div class="user-menu js-popover">
                                    <div class="user-container">
                                        <div class="user-avatar">
                                            <svg><use href="#icon-office"></use></svg>
                                        </div>
                                        <div class="user-info">
                                            <span class="name"><?php echo $_smarty_tpl->tpl_vars['contact']->value['name'];?>
</span>
                                            <span class="des"><?php echo $_smarty_tpl->tpl_vars['company']->value->getFullName();?>
</span>
                                        </div>
                                    </div>
                                    <svg class="arrow"><use href="#icon-arrow-down"></use></svg>
                                </div>
                                <div class="user-menu-list popover-menu">
                                    <?php  $_smarty_tpl->tpl_vars['menu_value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu_value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['header_menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu_value']->key => $_smarty_tpl->tpl_vars['menu_value']->value){
$_smarty_tpl->tpl_vars['menu_value']->_loop = true;
?>
                                        <a class="item" hx-get="<?php echo $_smarty_tpl->tpl_vars['cabinet_link']->value;?>
<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['link'];?>
">
                                            <svg><use href="#<?php echo $_smarty_tpl->tpl_vars['menu_value']->value['icon'];?>
"></use></svg><?php echo $_smarty_tpl->tpl_vars['menu_value']->value['name'];?>

                                        </a>
                                    <?php } ?>
                                    <a class="item" href="/">
                                        <svg><use href="#icon-link"></use></svg>Перейти на сайт
                                    </a>
                                    <div class="divider"></div>
                                    <div class="item js-change-role">
                                        <?php if (($_smarty_tpl->tpl_vars['role']->value==$_smarty_tpl->tpl_vars['ROLE_BUYER']->value)){?>
                                            <svg><use href="#icon-cart"></use></svg>Покупатель
                                        <?php }elseif(($_smarty_tpl->tpl_vars['role']->value==$_smarty_tpl->tpl_vars['ROLE_SUPPLIER']->value)){?>
                                            <svg><use href="#icon-truck"></use></svg>Поставщик
                                        <?php }?>
                                        <svg class="chevron"><use href="#icon-chevron-up-down"></use></svg>

                                        <div class="user-role-submenu popover-menu">
                                            <div class="user-role-item item <?php if (($_smarty_tpl->tpl_vars['role']->value==$_smarty_tpl->tpl_vars['ROLE_BUYER']->value)){?>active<?php }?>" data-role="<?php echo $_smarty_tpl->tpl_vars['ROLE_BUYER']->value;?>
">
                                                <svg><use href="#icon-cart"></use></svg>Покупатель
                                            </div>
                                            <div class="user-role-item item <?php if (($_smarty_tpl->tpl_vars['role']->value==$_smarty_tpl->tpl_vars['ROLE_SUPPLIER']->value)){?>active<?php }?>" data-role="<?php echo $_smarty_tpl->tpl_vars['ROLE_SUPPLIER']->value;?>
">
                                                <svg><use href="#icon-truck"></use></svg>Поставщик
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                    <a class="item js-logout">
                                        <svg><use href="#icon-exit"></use></svg>Выйти
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <main class="cabinet-content content blank sidebar-pinned">
                        <div id="htmx-loader" class="htmx-loader">
                            <svg><use href="#icon-spin"></use></svg>
                        </div>
                        <div class="content blank">
                            <div class="cabinet-article">
                                <div class="js-main-content" hx-get="" hx-trigger="refresh" hx-push-url="true">
                                    <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </main>

        <script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/cabinet/cabinet.js"></script>
        <?php if (($_smarty_tpl->tpl_vars['role']->value===$_smarty_tpl->tpl_vars['ROLE_BUYER']->value)){?>
            <script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/cabinet/buyer.js"></script>
        <?php }elseif(($_smarty_tpl->tpl_vars['role']->value===$_smarty_tpl->tpl_vars['ROLE_SUPPLIER']->value)){?>
            <script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/cabinet/supplier.js"></script>
        <?php }?>

        <script>
            $(document).ready(function() {
                $.App.init();
                $.Cabinet.init();
            });
        </script>
    </body>
</html>


<?php }} ?>