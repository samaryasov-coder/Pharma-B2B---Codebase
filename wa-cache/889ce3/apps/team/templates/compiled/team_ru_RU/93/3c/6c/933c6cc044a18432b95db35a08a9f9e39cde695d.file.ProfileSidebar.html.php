<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:44
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/profile/ProfileSidebar.html" */ ?>
<?php /*%%SmartyHeaderCode:19807109156a889d14bc8467-66880135%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '933c6cc044a18432b95db35a08a9f9e39cde695d' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/webasyst/templates/actions/profile/ProfileSidebar.html',
      1 => 1697709364,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19807109156a889d14bc8467-66880135',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'is_profile_sidebar' => 0,
    'app_path' => 0,
    'widget_path' => 0,
    'schedule_path' => 0,
    'is_profile_drawer' => 0,
    'sections' => 0,
    'section_id' => 0,
    'section' => 0,
    'options' => 0,
    'uniqid' => 0,
    'profile_content_layout_template' => 0,
    'layout_html' => 0,
    'section_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889d14be01b1_09138982',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889d14be01b1_09138982')) {function content_6a889d14be01b1_09138982($_smarty_tpl) {?>
<?php if ($_smarty_tpl->tpl_vars['is_profile_sidebar']->value){?>
    <?php $_smarty_tpl->tpl_vars['widget_path'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['app_path']->value)."/templates/actions/profile/sidebarWidgets/Calendar.html", null, 0);?>
    <?php $_smarty_tpl->tpl_vars['schedule_path'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['app_path']->value)."/templates/actions/schedule/Schedule.inc.html", null, 0);?>
    <section class="t-profile-sidebar-section js-sidebar-calendar custom-mb-16" data-section="calendar">
        <div class="t-profile-sidebar-title flexbox middle">
            <a href="javascript:void(0);"
               class="large bold js-sidebar-profile-dialog"
               data-dialog-header="Календарь"
               data-dialog-width="1000px"
               data-dialog-team-calendar="Календарь команды"
               data-section-id="calendar">
                Календарь
            </a>
            <a href="javascript:void(0);" class="text-light-gray custom-ml-auto js-show-outer-calendar-manager"><i class="fas fa-cog"></i></a>
        </div>
        <?php if (file_exists($_smarty_tpl->tpl_vars['widget_path']->value)){?>
            <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['widget_path']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

        <?php }?>
        <?php if (file_exists($_smarty_tpl->tpl_vars['schedule_path']->value)&&!$_smarty_tpl->tpl_vars['is_profile_drawer']->value){?>
            <div class="js-calendar-html" style="display: none">
                <?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['schedule_path']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('context'=>'profile'), 0);?>

            </div>
        <?php }?>
    </section>
    <?php  $_smarty_tpl->tpl_vars['section'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['section']->_loop = false;
 $_smarty_tpl->tpl_vars['section_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['sections']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['section']->key => $_smarty_tpl->tpl_vars['section']->value){
$_smarty_tpl->tpl_vars['section']->_loop = true;
 $_smarty_tpl->tpl_vars['section_id']->value = $_smarty_tpl->tpl_vars['section']->key;
?>
        <?php if ($_smarty_tpl->tpl_vars['section_id']->value!=='stats'){?><?php continue 1?><?php }?>
        <section class="t-profile-sidebar-section js-sidebar-<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['section_id']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php if (!empty($_smarty_tpl->tpl_vars['section']->value['html'])){?> custom-mb-16<?php }?>" data-section="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['section_id']->value, ENT_QUOTES, 'UTF-8', true);?>
">
            <a href="javascript:void(0);"
               class="t-profile-sidebar-title large bold js-sidebar-profile-dialog"
               data-url="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['section']->value['url'])===null||$tmp==='' ? '' : $tmp);?>
"
               data-dialog-header="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['section']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
"
               data-dialog-width="1000px"
               data-section-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['section_id']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                <?php echo $_smarty_tpl->tpl_vars['section']->value['title'];?>

                <?php if (!empty($_smarty_tpl->tpl_vars['section']->value['count'])){?>
                    <span class="count small text-light-gray"><?php echo $_smarty_tpl->tpl_vars['section']->value['count'];?>
</span>
                <?php }?>
            </a>
            <?php if (!empty($_smarty_tpl->tpl_vars['section']->value['html'])){?>
                <?php echo $_smarty_tpl->tpl_vars['section']->value['html'];?>

            <?php }?>
            </section>
    <?php } ?>
    <?php  $_smarty_tpl->tpl_vars['section'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['section']->_loop = false;
 $_smarty_tpl->tpl_vars['section_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['sections']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['section']->key => $_smarty_tpl->tpl_vars['section']->value){
$_smarty_tpl->tpl_vars['section']->_loop = true;
 $_smarty_tpl->tpl_vars['section_id']->value = $_smarty_tpl->tpl_vars['section']->key;
?>
        <?php if ($_smarty_tpl->tpl_vars['section_id']->value==='stats'||$_smarty_tpl->tpl_vars['section_id']->value==='access'){?><?php continue 1?><?php }?>
        <section class="t-profile-sidebar-section js-sidebar-<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['section_id']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php if (!empty($_smarty_tpl->tpl_vars['section']->value['html'])){?> custom-mb-16<?php }?>" data-section="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['section_id']->value, ENT_QUOTES, 'UTF-8', true);?>
">
            <a href="javascript:void(0);"
               class="t-profile-sidebar-title large bold js-sidebar-profile-dialog"
               data-url="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['section']->value['url'])===null||$tmp==='' ? '' : $tmp);?>
"
               data-dialog-header="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['section']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
"
               data-dialog-width="1000px"
               data-section-id="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['section_id']->value, ENT_QUOTES, 'UTF-8', true);?>
">
                <?php echo $_smarty_tpl->tpl_vars['section']->value['title'];?>

                <?php if (!empty($_smarty_tpl->tpl_vars['section']->value['count'])){?>
                    <span class="count small text-light-gray"><?php echo $_smarty_tpl->tpl_vars['section']->value['count'];?>
</span>
                <?php }?>
            </a>
            <?php if (!empty($_smarty_tpl->tpl_vars['section']->value['html'])){?>
                <?php echo $_smarty_tpl->tpl_vars['section']->value['html'];?>

            <?php }?>
        </section>
    <?php } ?>
<?php }else{ ?>
    <?php $_smarty_tpl->tpl_vars['section_id'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['options']->value['sectionId'])===null||$tmp==='' ? '' : $tmp), null, 0);?>
    <?php if ($_smarty_tpl->tpl_vars['section_id']->value&&isset($_smarty_tpl->tpl_vars['sections']->value[$_smarty_tpl->tpl_vars['section_id']->value])){?>
        <?php $_smarty_tpl->tpl_vars['section'] = new Smarty_variable($_smarty_tpl->tpl_vars['sections']->value[$_smarty_tpl->tpl_vars['section_id']->value], null, 0);?>
        <?php $_smarty_tpl->tpl_vars['section_url'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['section']->value['url'])===null||$tmp==='' ? '' : $tmp), null, 0);?>

        <div id="t-profile-section-dialog-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
" class="t-profile-section-iframe height-100"></div>
        <div class="hidden" id="t-profile-section-layout-html-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
">
            <?php $_smarty_tpl->tpl_vars['layout_html'] = new Smarty_variable($_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['profile_content_layout_template']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0));?>

            <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['layout_html']->value, ENT_QUOTES, 'UTF-8', true);?>

        </div>
         <div class="hidden" id="t-profile-section-html-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
"><?php if (!empty($_smarty_tpl->tpl_vars['sections']->value[$_smarty_tpl->tpl_vars['section_id']->value]['html'])&&empty($_smarty_tpl->tpl_vars['section']->value['url'])){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['section']->value['html'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></div>

        <script>
            new class ProfileSidebarSection {
                constructor() {
                    const that = this;

                    that.$content_wrapper = $('#t-profile-section-dialog-<?php echo $_smarty_tpl->tpl_vars['uniqid']->value;?>
') || null;

                    that.dialog = that.$content_wrapper.closest('.dialog-opened').data('dialog');
                    that.section_url = '<?php echo $_smarty_tpl->tpl_vars['section_url']->value;?>
';
                    that.error_msg = "Ошибка получения содержимого.";
                    that.init();
                }

                init() {
                    const that = this;

                    if (!that.section_url) {
                        that.$content_wrapper.html(`<p><i class="fas fa-times-circle text-red"></i> ${ that.error_msg }</p>`);
                        throw new Error(that.error_msg);
                    }

                    $.ajax({
                        url: that.section_url,
                        success(result) {
                            that.$content_wrapper.html(result);

                            // we need timeout because html method have no callback
                            // and we need time to repaint content
                            setTimeout(() => {
                              that.dialog.resize();
                            }, 100)
                        },
                        error() {
                            console.warn(that.error_msg);
                            console.error.apply(console, arguments);
                            that.$content_wrapper.html(`<p><i class="fas fa-times-circle text-red"></i> ${ that.error_msg }</p>`);
                            return false;
                        },
                        dataType: 'html'
                    });
                }

                reload() {
                    const that = this;
                    that.$content_wrapper.html.empty();
                    that.init();
                }
            }

            window.isProfileSidebarSection = true;
        </script>
    <?php }?>
<?php }?>
<?php }} ?>