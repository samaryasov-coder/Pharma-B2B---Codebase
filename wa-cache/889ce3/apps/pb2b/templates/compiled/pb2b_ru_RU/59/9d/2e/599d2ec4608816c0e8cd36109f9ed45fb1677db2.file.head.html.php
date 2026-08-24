<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 19:01:02
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/head.html" */ ?>
<?php /*%%SmartyHeaderCode:12595402116a889cf79cdd27-82884467%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '599d2ec4608816c0e8cd36109f9ed45fb1677db2' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/themes/default/head.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12595402116a889cf79cdd27-82884467',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889cf79e0604_39616920',
  'variables' => 
  array (
    'wa' => 0,
    'canonical' => 0,
    'wa_theme_url' => 0,
    'wa_url' => 0,
    'wa_theme_version' => 0,
    'wa_static_url' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889cf79e0604_39616920')) {function content_6a889cf79e0604_39616920($_smarty_tpl) {?><meta name="viewport" content="width=device-width, initial-scale=1<?php if ($_smarty_tpl->tpl_vars['wa']->value->isMobile()){?>, maximum-scale=1, user-scalable=0<?php }?>">
<meta name="keywords" content="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['wa']->value->meta('keywords'), ENT_QUOTES, 'UTF-8', true);?>
">
<meta name="description" content="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['wa']->value->meta('description'), ENT_QUOTES, 'UTF-8', true);?>
">
<meta name="robots" content="index, follow">

<title><?php echo $_smarty_tpl->tpl_vars['wa']->value->title();?>
</title>

<?php if (!empty($_smarty_tpl->tpl_vars['canonical']->value)){?> <link rel="canonical" href="<?php echo $_smarty_tpl->tpl_vars['canonical']->value;?>
"> <?php }?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
img/favicon.png">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/css/wa/wa-2.0.css" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
css/default.css?v<?php echo $_smarty_tpl->tpl_vars['wa_theme_version']->value;?>
" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
css/pharmab2b.css" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
css/auth.css" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
css/cabinet.css" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
css/swiper.css" rel="stylesheet">
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
css/navSidebar.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.min.css">
<link href="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
css/datatable.css" rel="stylesheet">
<?php echo $_smarty_tpl->tpl_vars['wa']->value->css();?>


<script src="<?php echo $_smarty_tpl->tpl_vars['wa_static_url']->value;?>
wa-content/js/jquery/jquery-3.6.0.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/waTheme.js?v=<?php echo $_smarty_tpl->tpl_vars['wa_theme_version']->value;?>
"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-wa/wa.js?v=<?php echo $_smarty_tpl->tpl_vars['wa']->value->version(true);?>
"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
<script src="https://unpkg.com/htmx.org@2.0.3"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/i18n/ru.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luxon@3/build/global/luxon.min.js"></script>

<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/swiper.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/anime.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/alert-manager.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/file-utils.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/tab-manager.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/file-uploader.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/dialog-manager.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/fsend.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/frequest.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/lib/flatpickr-rus.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/navSidebar.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/registration-company.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/default.js?v<?php echo $_smarty_tpl->tpl_vars['wa_theme_version']->value;?>
"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/auth.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_theme_url']->value;?>
js/pharmab2b.js"></script>
<?php echo $_smarty_tpl->tpl_vars['wa']->value->head();?>
<?php }} ?>