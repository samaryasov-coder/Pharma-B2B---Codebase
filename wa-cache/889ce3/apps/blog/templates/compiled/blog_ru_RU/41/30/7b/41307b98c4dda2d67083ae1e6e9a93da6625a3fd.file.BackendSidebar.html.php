<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:41:49
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/blog/templates/actions/backend/BackendSidebar.html" */ ?>
<?php /*%%SmartyHeaderCode:17949371686a8c825dae22b0-86757610%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '41307b98c4dda2d67083ae1e6e9a93da6625a3fd' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/blog/templates/actions/backend/BackendSidebar.html',
      1 => 1729850850,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17949371686a8c825dae22b0-86757610',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'wa_url' => 0,
    'writable_blogs' => 0,
    'view_all_posts' => 0,
    'wa_app_url' => 0,
    'new_post_count' => 0,
    'post_count' => 0,
    'module' => 0,
    'comment_new_count' => 0,
    'comment_count' => 0,
    'action' => 0,
    'count_draft_overdue' => 0,
    'backend_sidebar' => 0,
    'output' => 0,
    'plugin' => 0,
    'can_see_blog_settings' => 0,
    'blog_id_full_access' => 0,
    'blogs' => 0,
    'id' => 0,
    'blog_id' => 0,
    'blog' => 0,
    '_color' => 0,
    'drafts_count' => 0,
    'wa' => 0,
    'drafts' => 0,
    'post' => 0,
    'post_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c825db10407_29361175',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c825db10407_29361175')) {function content_6a8c825db10407_29361175($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.replace.php';
if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
if (!is_callable('smarty_modifier_wa_datetime')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.wa_datetime.php';
?><script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.core.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.widget.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.mouse.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
wa-content/js/jquery-ui/jquery.ui.sortable.min.js"></script>

<div class="sidebar flexbox width-adaptive-wider hide-scrollbar mobile-friendly b-app-sidebar">

    <nav class="sidebar-mobile-toggle">
        <div class="box align-center">
            <a href="javascript:void(0);"><i class="fas fa-bars custom-mr-4"></i> Меню</a>
        </div>
    </nav>
    <?php if ($_smarty_tpl->tpl_vars['writable_blogs']->value){?>
    <div class="sidebar-header box custom-pt-20">
        <a href="?module=post&amp;action=edit&amp;id=" class="button full-width align-center">
            <i class="fas fa-pencil-alt fa-w-20 custom-mr-4 small"></i>
            <span class="small">Новая запись</span>
        </a>
    </div>
    <?php }?>
    <div class="sidebar-body">
        <!-- core navigation -->
        <div class="box custom-py-0">
        <ul class="menu">
            <li class="rounded<?php if (isset($_smarty_tpl->tpl_vars['view_all_posts']->value)&&$_smarty_tpl->tpl_vars['view_all_posts']->value==true){?> selected<?php }?>">
                <a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
">
                    <i class="fas fa-file-invoice"></i>
                    <span>Все записи</span>
                    <span class="count">
                        <?php if (isset($_smarty_tpl->tpl_vars['new_post_count']->value)&&$_smarty_tpl->tpl_vars['new_post_count']->value>0){?>
                            <strong class="small highlighted">+<?php echo $_smarty_tpl->tpl_vars['new_post_count']->value;?>
</strong>
                        <?php }?>
                        <?php echo $_smarty_tpl->tpl_vars['post_count']->value;?>

                    </span>
                </a>
            </li>
            <li class="rounded<?php if ($_smarty_tpl->tpl_vars['module']->value=='comments'){?> selected<?php }?>">
                <a href="?module=comments">
                    <i class="fas fa-comments"></i>
                    <span>Комментарии</span>
                    <span class="count comment-count">
                        <?php if ($_smarty_tpl->tpl_vars['comment_new_count']->value>0){?>
                            <strong class="small highlighted">+<?php echo $_smarty_tpl->tpl_vars['comment_new_count']->value;?>
</strong>
                        <?php }?>
                        <?php echo $_smarty_tpl->tpl_vars['comment_count']->value;?>

                    </span>
                </a>
            </li>
            <li class="rounded<?php if ($_smarty_tpl->tpl_vars['action']->value=='calendar'){?> selected<?php }?>">
                <a href="?action=calendar">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Календарь</span>
                    <?php if ($_smarty_tpl->tpl_vars['count_draft_overdue']->value>0){?>
                        <strong class="count badge text-white"><?php echo $_smarty_tpl->tpl_vars['count_draft_overdue']->value;?>
</strong>
                    <?php }?>
                </a>
            </li>
            
            <!-- plugin hook: "backend_sidebar.menu" -->
            <?php  $_smarty_tpl->tpl_vars['output'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['output']->_loop = false;
 $_smarty_tpl->tpl_vars['plugin'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['backend_sidebar']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['output']->key => $_smarty_tpl->tpl_vars['output']->value){
$_smarty_tpl->tpl_vars['output']->_loop = true;
 $_smarty_tpl->tpl_vars['plugin']->value = $_smarty_tpl->tpl_vars['output']->key;
?>
                <?php if (is_array($_smarty_tpl->tpl_vars['output']->value)&&isset($_smarty_tpl->tpl_vars['output']->value['menu'])){?>
                    <!-- begin <?php echo $_smarty_tpl->tpl_vars['plugin']->value;?>
 --><?php echo $_smarty_tpl->tpl_vars['output']->value['menu'];?>
<!-- end <?php echo $_smarty_tpl->tpl_vars['plugin']->value;?>
 -->
                <?php }?>
            <?php } ?>

            <!-- end plugin hook: "backend_sidebar.menu" -->
        </ul>
        </div>

        <!-- blog list -->
        <details data-id="blogs">
            <summary class="heading">
                <span class="cursor-pointer">
                    <span class="caret">
                        <i class="fas fa-caret-right"></i>
                    </span>
                    Блоги
                </span>
                <?php if ($_smarty_tpl->tpl_vars['can_see_blog_settings']->value){?>
                <a href="?module=blog&amp;blog=&amp;action=settings&blog=<?php echo $_smarty_tpl->tpl_vars['blog_id_full_access']->value;?>
" class="count action">
                    <i class="fas fa-cog"></i>
                </a>
                <?php }?>
            </summary>

            <?php if ($_smarty_tpl->tpl_vars['blogs']->value){?>
                <div class="box custom-py-0">
                    <ul class="menu collapsible category-menu" id="blogs">
                        <?php  $_smarty_tpl->tpl_vars['blog'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['blog']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['blogs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['blog']->key => $_smarty_tpl->tpl_vars['blog']->value){
$_smarty_tpl->tpl_vars['blog']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['blog']->key;
?> <!-- blog list item <?php echo $_smarty_tpl->tpl_vars['id']->value;?>
  -->
                            <li class="rounded<?php if (isset($_smarty_tpl->tpl_vars['blog_id']->value)&&$_smarty_tpl->tpl_vars['blog_id']->value==$_smarty_tpl->tpl_vars['id']->value&&$_smarty_tpl->tpl_vars['action']->value!='settings'){?> selected<?php }?>" id="blog_li_item_<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
">
                                <a href="?blog=<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
">
                                    <?php if ($_smarty_tpl->tpl_vars['blog']->value['color']=='b-white'&&strpos($_smarty_tpl->tpl_vars['blog']->value['icon_html'],'background-image')!==false){?>
                                        <span class="icon">
                                            <?php echo $_smarty_tpl->tpl_vars['blog']->value['icon_html'];?>

                                        </span>
                                    <?php }?>
                                    <?php if ($_smarty_tpl->tpl_vars['blog']->value['color']!='b-white'){?>
                                        <?php $_smarty_tpl->tpl_vars['_color'] = new Smarty_variable(smarty_modifier_replace($_smarty_tpl->tpl_vars['blog']->value['color'],'b-','icon16 text-'), null, 0);?>
                                        <span class="icon">
                                            <?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['blog']->value['icon_html'],'icon16',$_smarty_tpl->tpl_vars['_color']->value);?>

                                        </span>
                                    <?php }?>
                                    <span>
                                        <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['blog']->value['name'], ENT_QUOTES, 'UTF-8', true);?>

                                    </span>
                                    <span class="count">
                                        <?php if ($_smarty_tpl->tpl_vars['blog']->value['status']==blogBLogModel::STATUS_PRIVATE){?>
                                            <span class="small custom-mr-4">
                                                <i class="fas fa-lock" title="Закрытый блог"></i>
                                            </span>
                                        <?php }?>
                                        <?php if (isset($_smarty_tpl->tpl_vars['blog']->value['new_post'])){?>
                                            <strong class="small highlighted">+<?php echo $_smarty_tpl->tpl_vars['blog']->value['new_post'];?>
</strong>
                                        <?php }?>
                                        <?php echo $_smarty_tpl->tpl_vars['blog']->value['qty'];?>

                                    </span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php }else{ ?>
                <p class="align-center hint box custom-mt-16">
                    <?php echo sprintf('Для добавления записей необходимо сначала <a href="%s">создать новый блог</a>.',"?module=blog&amp;blog=&amp;action=settings&amp;blog=");?>

                </p>
            <?php }?>
        </details>

        
        <!-- plugin hook: "backend_sidebar.section" -->
        <?php  $_smarty_tpl->tpl_vars['output'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['output']->_loop = false;
 $_smarty_tpl->tpl_vars['plugin'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['backend_sidebar']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['output']->key => $_smarty_tpl->tpl_vars['output']->value){
$_smarty_tpl->tpl_vars['output']->_loop = true;
 $_smarty_tpl->tpl_vars['plugin']->value = $_smarty_tpl->tpl_vars['output']->key;
?>
            <?php if (is_array($_smarty_tpl->tpl_vars['output']->value)&&isset($_smarty_tpl->tpl_vars['output']->value['section'])){?>
                <!-- begin <?php echo $_smarty_tpl->tpl_vars['plugin']->value;?>
 --><?php echo $_smarty_tpl->tpl_vars['output']->value['section'];?>
<!-- end <?php echo $_smarty_tpl->tpl_vars['plugin']->value;?>
 -->
            <?php }?>
        <?php } ?>

        <!-- end plugin hook: "backend_sidebar.section" -->

        <?php if ($_smarty_tpl->tpl_vars['writable_blogs']->value&&(!empty($_smarty_tpl->tpl_vars['drafts_count']->value['all'])||!empty($_smarty_tpl->tpl_vars['drafts_count']->value['my']))){?>
            <!-- drafts -->
        <details data-id="drafts">
            <summary class="heading">
                <span class="cursor-pointer">
                    <span class="caret">
                        <i class="fas fa-caret-right"></i>
                    </span>
                    <span class="title b-all-drafts" style="display:none;">Черновики</span><?php if (!empty($_smarty_tpl->tpl_vars['drafts_count']->value['my'])){?><span class="title b-my-drafts" style="display:none;">Мои черновики</span><?php }?>
                </span>
                <span id="b-all-drafts" class="count b-drafts-toggle small" style="display:none;">
                    <a href="javascript:void(0);" class="text-light-gray">все</a>
                </span>
                <?php if (!empty($_smarty_tpl->tpl_vars['drafts_count']->value['my'])){?>
                    <span id="b-my-drafts" class="count b-drafts-toggle small" style="display:none;">
                        <a href="javascript:void(0);" class="text-light-gray" data-contact-id="<?php echo $_smarty_tpl->tpl_vars['wa']->value->user('id');?>
">только мои</a>
                    </span>
                <?php }?>
                <span class="count b-all-drafts counter" style="display: none;">
                    <?php if (!empty($_smarty_tpl->tpl_vars['drafts_count']->value['all'])){?><?php echo $_smarty_tpl->tpl_vars['drafts_count']->value['all'];?>
<?php }?>
                </span>
                <?php if (!empty($_smarty_tpl->tpl_vars['drafts_count']->value['my'])){?>
                    <span class="count b-my-drafts counter" style="display: none;"><?php echo $_smarty_tpl->tpl_vars['drafts_count']->value['my'];?>
</span>
                <?php }?>
            </summary>
            <div class="box custom-py-0">
            <ul id="blog-drafts" class="menu b-drafts">
                <?php  $_smarty_tpl->tpl_vars['post'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['post']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['drafts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['post']->key => $_smarty_tpl->tpl_vars['post']->value){
$_smarty_tpl->tpl_vars['post']->_loop = true;
?>
                    <li class="rounded<?php if ($_smarty_tpl->tpl_vars['post']->value['id']==$_smarty_tpl->tpl_vars['post_id']->value){?> selected<?php }?>" data-contact-id="<?php echo $_smarty_tpl->tpl_vars['post']->value['contact_id'];?>
">
                        <a class="<?php if ($_smarty_tpl->tpl_vars['post']->value['status']==blogPostModel::STATUS_SCHEDULED){?>italic<?php }?>" href="?module=post&amp;action=edit&amp;id=<?php echo $_smarty_tpl->tpl_vars['post']->value['id'];?>
">
                            <i class="icon userpic userpic-20" style="background-image: url('<?php echo $_smarty_tpl->tpl_vars['post']->value['user']['photo_url_20'];?>
')"></i>
                            <span>
                                <span class="<?php if (isset($_smarty_tpl->tpl_vars['post']->value['overdue'])){?>bold b-draft-overdue text-red<?php }?>">
                                    <?php echo smarty_modifier_truncate(htmlspecialchars((string)$_smarty_tpl->tpl_vars['post']->value['title'], ENT_QUOTES, 'UTF-8', true),80);?>

                                </span>
                                <?php if ($_smarty_tpl->tpl_vars['post']->value['blog_status']==blogBlogModel::STATUS_PRIVATE){?>
                                    <span class="small">
                                        <i class="fas fa-lock" title="Принадлежит частному блогу"></i>
                                    </span>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['post']->value['datetime']){?>
                                    <span class="nowrap hint<?php if (isset($_smarty_tpl->tpl_vars['post']->value['overdue'])){?> b-draft-overdue<?php }?>"><?php echo smarty_modifier_wa_datetime($_smarty_tpl->tpl_vars['post']->value['datetime'],'humandate');?>
</span>
                                <?php }?>
                            </span>
                            <span class="count">
                                <?php if ($_smarty_tpl->tpl_vars['post']->value['status']==blogPostModel::STATUS_SCHEDULED){?>
                                    <i class="fas fa-clock"></i>
                                <?php }elseif($_smarty_tpl->tpl_vars['post']->value['status']==blogPostModel::STATUS_DEADLINE){?>
                                    <i class="fas fa-exclamation-triangle<?php if (isset($_smarty_tpl->tpl_vars['post']->value['overdue'])){?> text-red<?php }?>"></i>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['post']->value['color']=='b-white'&&strpos($_smarty_tpl->tpl_vars['post']->value['icon'],'background-image')!==false){?>
                                    <span class="icon">
                                        <?php echo $_smarty_tpl->tpl_vars['post']->value['icon'];?>

                                    </span>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['post']->value['color']!='b-white'){?>
                                    <?php $_smarty_tpl->tpl_vars['_color'] = new Smarty_variable(smarty_modifier_replace($_smarty_tpl->tpl_vars['post']->value['color'],'b-','icon16 text-'), null, 0);?>
                                    <span class="icon">
                                        <?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['post']->value['icon'],'icon16',$_smarty_tpl->tpl_vars['_color']->value);?>

                                    </span>
                                <?php }?>
                            </span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            </div>
        </details>
        <?php }?>
    </div>
    <div class="sidebar-footer shadowed">
        <div class="box custom-py-0">
        <ul class="menu">
            <?php if ($_smarty_tpl->tpl_vars['wa']->value->user()->getRights($_smarty_tpl->tpl_vars['wa']->value->app(),'pages')){?>
                <li class="rounded<?php if ($_smarty_tpl->tpl_vars['module']->value=='pages'){?> selected<?php }?>">
                    <a href="?module=pages">
                        <i class="fas fa-pen"></i>
                        <span>Страницы</span>
                    </a>
                </li>
            <?php }?>
            <?php if ($_smarty_tpl->tpl_vars['wa']->value->user()->getRights($_smarty_tpl->tpl_vars['wa']->value->app(),'design')){?>
                <li class="rounded<?php if ($_smarty_tpl->tpl_vars['module']->value=='design'||$_smarty_tpl->tpl_vars['module']->value=='themes'){?> selected<?php }?>">
                    <a href="?module=design#/design/themes/">
                        <i class="fas fa-palette"></i>
                        <span>Дизайн</span>
                    </a>
                </li>
            <?php }?>
            <li class="rounded<?php if ($_smarty_tpl->tpl_vars['module']->value=='settings'){?> selected<?php }?>">
                <a href="?module=settings">
                    <i class="fas fa-cog"></i>
                    <span>Настройки</span>
                </a>
            </li>

            <?php if ($_smarty_tpl->tpl_vars['wa']->value->user()->isAdmin($_smarty_tpl->tpl_vars['wa']->value->app())){?>
                <li class="rounded<?php if ($_smarty_tpl->tpl_vars['action']->value=='plugins'){?> selected<?php }?>">
                    <a href="?module=plugins">
                        <i class="fas fa-plug"></i>
                        <span>Плагины</span>
                    </a>
                </li>
            <?php }?>

            
            <!-- plugin hook: "backend_sidebar.system" -->
            <?php  $_smarty_tpl->tpl_vars['output'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['output']->_loop = false;
 $_smarty_tpl->tpl_vars['plugin'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['backend_sidebar']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['output']->key => $_smarty_tpl->tpl_vars['output']->value){
$_smarty_tpl->tpl_vars['output']->_loop = true;
 $_smarty_tpl->tpl_vars['plugin']->value = $_smarty_tpl->tpl_vars['output']->key;
?>
                <?php if (is_array($_smarty_tpl->tpl_vars['output']->value)&&isset($_smarty_tpl->tpl_vars['output']->value['system'])){?>
                        <!-- begin <?php echo $_smarty_tpl->tpl_vars['plugin']->value;?>
 --><?php echo $_smarty_tpl->tpl_vars['output']->value['system'];?>
<!-- end <?php echo $_smarty_tpl->tpl_vars['plugin']->value;?>
 -->
                <?php }?>
            <?php } ?>
            <!-- end plugin hook: "backend_sidebar.system" -->
        </ul>
        </div>
    </div>
</div>

<script>
( function($) {
    $('.b-app-sidebar').waShowSidebar();
    $.wa_blog.sidebar.lockPosition();
})(jQuery);
</script>
<?php }} ?>