<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:42:36
         compiled from "/var/www/pharmab2b/httpdocs/wa-system/design/templates/ThemeUpdateDialog.html" */ ?>
<?php /*%%SmartyHeaderCode:7467214946a8c828c366bc3-79166867%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e3e66b455651cc007dbf12df1167f6f6a661932b' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-system/design/templates/ThemeUpdateDialog.html',
      1 => 1768467926,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7467214946a8c828c366bc3-79166867',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'parent_only' => 0,
    'theme' => 0,
    'theme_original_version' => 0,
    'theme_files' => 0,
    'f' => 0,
    '_non_m_files_count' => 0,
    '_non_m_group_shown_flag' => 0,
    'f_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c828c3805b2_79772910',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c828c3805b2_79772910')) {function content_6a8c828c3805b2_79772910($_smarty_tpl) {?><div class="dialog-background"></div>
<form class="dialog-body">
    <h3 class="dialog-header">
        Обновить тему
        <span class="hint"><?php if (empty($_smarty_tpl->tpl_vars['parent_only']->value)){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme']->value['parent_theme']['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?> <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['theme_original_version']->value, ENT_QUOTES, 'UTF-8', true);?>
</span>
    </h3>
    <div class="dialog-content">
        <p><?php echo sprintf('Подтвердите обновление всех выбранных шаблонов дизайна их новыми версиями из %s.',$_smarty_tpl->tpl_vars['theme_original_version']->value);?>
</p>
        <p class="state-caution-hint"><i class="fas fa-exclamation-triangle fa-sm"></i> <em>Файлы, которые вы ранее редактировали, выделены <strong>жирным</strong>. Выберите только те файлы, которые хотите полностью сбросить до их оригинальных версий из обновленной темы дизайна, тем самым удалив все внесенные вами ранее изменения. (Если вы не уверены, нужно ли обновлять тот или иной файл, не выбирайте его. Позже вы сможете обновить все эти файлы по одному.)</em></p>
        <?php if (empty($_smarty_tpl->tpl_vars['parent_only']->value)){?>
        <ul class="menu">
            <li>
                <label title="Настройки темы дизайна будут обновлены автоматически" class="gray item">
                    <input type="checkbox" disabled checked="checked" class="custom-mt-0 custom-mr-4">
                    Настройки оформления (theme.xml)
                </label>
            </li>
            <?php $_smarty_tpl->tpl_vars['_non_m_files_count'] = new Smarty_variable(0, null, 0);?>
            <?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_smarty_tpl->tpl_vars['f_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['theme_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
 $_smarty_tpl->tpl_vars['f_id']->value = $_smarty_tpl->tpl_vars['f']->key;
?>
                <?php if (empty($_smarty_tpl->tpl_vars['f']->value['modified'])){?><?php $_smarty_tpl->tpl_vars['_non_m_files_count'] = new Smarty_variable($_smarty_tpl->tpl_vars['_non_m_files_count']->value+1, null, 0);?><?php }?>
            <?php } ?>
            
            <?php $_smarty_tpl->tpl_vars['_non_m_group_shown_flag'] = new Smarty_variable(false, null, 0);?>
            <?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_smarty_tpl->tpl_vars['f_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['theme_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
 $_smarty_tpl->tpl_vars['f_id']->value = $_smarty_tpl->tpl_vars['f']->key;
?>
                <?php if (empty($_smarty_tpl->tpl_vars['f']->value['modified'])){?>
                    <?php if (!$_smarty_tpl->tpl_vars['_non_m_group_shown_flag']->value){?>
                        <li>
                            <label title="Вы не вносили изменения в этот файл" class="gray item">
                                <input type="checkbox" disabled checked="checked" class="custom-mt-0 custom-mr-4">
                                <?php if ($_smarty_tpl->tpl_vars['_non_m_files_count']->value>1){?>
                                    <?php echo sprintf_wp('%s and %s',htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true),_ws('%d more non-modified file','%d more non-modified files',$_smarty_tpl->tpl_vars['_non_m_files_count']->value-1));?>

                                <?php }else{ ?>
                                    <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true);?>

                                <?php }?>
                            </label>
                        </li>
                        <?php $_smarty_tpl->tpl_vars['_non_m_group_shown_flag'] = new Smarty_variable(true, null, 0);?>
                    <?php }?>
                <?php }else{ ?>
                    <?php if (empty($_smarty_tpl->tpl_vars['f']->value['custom'])){?>
                        <li>
                            <label class="bold item" title="Вы вносили изменения в этот файл">
                                <input name="reset[]" type="checkbox" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="custom-mt-0 custom-mr-4">
                                <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true);?>

                            </label>
                        </li>
                    <?php }else{ ?>
                        <li>
                            <label title="Это пользовательский файл, и он не будет затронут обновлением." class="gray item">
                                <input name="reset[]" type="checkbox" disabled value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="custom-mt-0 custom-mr-4"> <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true);?>

                            </label>
                        </li>
                    <?php }?>
                <?php }?>
            <?php } ?>

        </ul>
        <?php }else{ ?>
        <input type="hidden" name="parent_only" value="1">
        <?php }?>

        <?php if ($_smarty_tpl->tpl_vars['theme']->value['parent_theme']&&$_smarty_tpl->tpl_vars['theme']->value['parent_theme']['type']==waTheme::OVERRIDDEN){?>
        <br><br>
        <h3 class="heading">Родительская тема дизайна: <?php echo $_smarty_tpl->tpl_vars['theme']->value['parent_theme']['name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['theme']->value['parent_theme']['app'];?>
)</h3>
        <ul class="menu">
            <li>
                <label title="Настройки темы дизайна будут обновлены автоматически" class="gray item">
                    <input type="checkbox" disabled checked="checked" class="custom-mt-0 custom-mr-4">
                    Настройки оформления (theme.xml)
                </label>
            </li>
            <?php $_smarty_tpl->tpl_vars['_non_m_files_count'] = new Smarty_variable(0, null, 0);?>
            <?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_smarty_tpl->tpl_vars['f_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['theme']->value['parent_theme']['files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
 $_smarty_tpl->tpl_vars['f_id']->value = $_smarty_tpl->tpl_vars['f']->key;
?>
                <?php if (empty($_smarty_tpl->tpl_vars['f']->value['modified'])){?><?php $_smarty_tpl->tpl_vars['_non_m_files_count'] = new Smarty_variable($_smarty_tpl->tpl_vars['_non_m_files_count']->value+1, null, 0);?><?php }?>
            <?php } ?>
            
            <?php $_smarty_tpl->tpl_vars['_non_m_group_shown_flag'] = new Smarty_variable(false, null, 0);?>
            <?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_smarty_tpl->tpl_vars['f_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['theme']->value['parent_theme']['files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
 $_smarty_tpl->tpl_vars['f_id']->value = $_smarty_tpl->tpl_vars['f']->key;
?>
                <?php if (empty($_smarty_tpl->tpl_vars['f']->value['modified'])){?>
                    <?php if (!$_smarty_tpl->tpl_vars['_non_m_group_shown_flag']->value){?>
                        <li>
                            <label title="Вы не вносили изменения в этот файл" class="gray item">
                                <input type="checkbox" disabled checked="checked" class="custom-mt-0 custom-mr-4">
                                <?php if ($_smarty_tpl->tpl_vars['_non_m_files_count']->value>1){?>
                                    <?php echo sprintf(_ws('%s and %d more unmodified file','%s and %d more unmodified files',$_smarty_tpl->tpl_vars['_non_m_files_count']->value-1,false),htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['_non_m_files_count']->value-1);?>

                                <?php }else{ ?>
                                    <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true);?>

                                <?php }?>
                            </label>
                        </li>
                        <?php $_smarty_tpl->tpl_vars['_non_m_group_shown_flag'] = new Smarty_variable(true, null, 0);?>
                    <?php }?>
                <?php }else{ ?>
                    <li>
                        <label class="bold item" title="Вы вносили изменения в этот файл">
                            <input name="parent_reset[]" type="checkbox" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="custom-mt-0 custom-mr-4">
                            <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['f_id']->value, ENT_QUOTES, 'UTF-8', true);?>

                        </label>
                    </li>
                <?php }?>
            <?php } ?>
        </ul>
        <?php }?>
    </div>
    <div class="dialog-footer">
        <input type="hidden" name="theme" value="<?php echo $_smarty_tpl->tpl_vars['theme']->value['id'];?>
">
        <input type="submit" class="button blue" value="<?php echo sprintf('Обновить до %s',$_smarty_tpl->tpl_vars['theme_original_version']->value);?>
">
        <a href="#/design/themes/" class="js-close-dialog button light-gray">Отмена</a>
    </div>
</form>
<?php }} ?>