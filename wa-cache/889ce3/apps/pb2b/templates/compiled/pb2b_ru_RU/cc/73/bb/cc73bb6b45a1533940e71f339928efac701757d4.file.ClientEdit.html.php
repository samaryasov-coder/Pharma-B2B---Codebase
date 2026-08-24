<?php /* Smarty version Smarty-3.1.14, created on 2026-08-22 17:13:13
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/client/ClientEdit.html" */ ?>
<?php /*%%SmartyHeaderCode:17329507486a89d8a98caa72-56828233%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cc73bb6b45a1533940e71f339928efac701757d4' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/pb2b/templates/actions/client/ClientEdit.html',
      1 => 1787338794,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17329507486a89d8a98caa72-56828233',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'object' => 0,
    'includes' => 0,
    'default_tab' => 0,
    'key' => 0,
    'tab' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a89d8a98e0d03_06304238',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a89d8a98e0d03_06304238')) {function content_6a89d8a98e0d03_06304238($_smarty_tpl) {?><?php if (!empty($_smarty_tpl->tpl_vars['object']->value['id'])){?>      
    <span class="hint">id: <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['object']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
</span>
<?php }?>

<h1 class="mt0">
    <?php if (empty($_smarty_tpl->tpl_vars['object']->value['id'])){?>
        Новый клиент
    <?php }else{ ?>

        Клиент &laquo;<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['object']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
&raquo; 

        <?php if ($_smarty_tpl->tpl_vars['object']->value['archive']==(($tmp = @0)===null||$tmp==='' ? 0 : $tmp)){?>
            <span class="badge small user green">В работе</span>
        <?php }else{ ?>
            <span class="badge small user yellow">Архив</span>
        <?php }?>
        
    <?php }?> 
</h1>

<?php if (!empty($_smarty_tpl->tpl_vars['object']->value['id'])){?>
    <?php $_smarty_tpl->tpl_vars['default_tab'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['includes']->value['options']['default_tab'])===null||$tmp==='' ? 'company_data' : $tmp), null, 0);?>
    <form class="js-form form fields" action="?module=client&action=save">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['object']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
">

        <div class="js-tabs" data-default-tab="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['default_tab']->value, ENT_QUOTES, 'UTF-8', true);?>
" data-param="tab">

            <ul class="tabs js-tabs-controls">
                <?php  $_smarty_tpl->tpl_vars['tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tab']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['includes']->value['tabs']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tab']->key => $_smarty_tpl->tpl_vars['tab']->value){
$_smarty_tpl->tpl_vars['tab']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['tab']->key;
?>
                    <li data-tab="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="<?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['default_tab']->value){?>selected<?php }?>">
                        <a href="#"><span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['tab']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span></a>
                    </li>
                <?php } ?>
            </ul>

            <div class="js-tabs-panels mt10">
                <?php  $_smarty_tpl->tpl_vars['tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tab']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['includes']->value['tabs']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tab']->key => $_smarty_tpl->tpl_vars['tab']->value){
$_smarty_tpl->tpl_vars['tab']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['tab']->key;
?>
                    <div data-tab="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8', true);?>
" <?php if ($_smarty_tpl->tpl_vars['key']->value!=$_smarty_tpl->tpl_vars['default_tab']->value){?>style="display:none"<?php }?>>
                        <?php echo $_smarty_tpl->getSubTemplate ("./tabs/".((string)$_smarty_tpl->tpl_vars['key']->value).".html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

                    </div>
                <?php } ?>
            </div>

        </div>

        <section class="bottombar pb2b-bottombar">
            <div class="article width-100">
                <div class="article-body custom-py-8 flexbox">
                    <input type="submit" class="button" value="Сохранить">
                    <span class="js-form-message form-message red" style="display: none;">
                        <span class="js-form-message-icons">
                            <i class="fas fa-check-circle" style="display:none;"></i>
                        </span>
                        <span class="js-form-message-text"></span>
                    </span>
                </div>
            </div>
        </section>
    </form>
<?php }else{ ?>
    <?php echo $_smarty_tpl->getSubTemplate ("./tabs/create.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }?><?php }} ?>