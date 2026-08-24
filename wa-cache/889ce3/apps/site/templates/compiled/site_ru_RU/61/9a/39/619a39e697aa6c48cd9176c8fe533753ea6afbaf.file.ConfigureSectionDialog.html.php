<?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:44:00
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/configure/ConfigureSectionDialog.html" */ ?>
<?php /*%%SmartyHeaderCode:19746387076a8c82e0cb7527-18955106%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '619a39e697aa6c48cd9176c8fe533753ea6afbaf' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/configure/ConfigureSectionDialog.html',
      1 => 1783409806,
      2 => 'file',
    ),
    'a21f44a73930eb43496a8840b4e1fa04d29e9e28' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/layouts/includes/alert_misconfigured_settlement.html',
      1 => 1740579100,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19746387076a8c82e0cb7527-18955106',
  'function' => 
  array (
    '_after_url' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
    '_render_field_url' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'app_id' => 0,
    'url_exact' => 0,
    'is_custom_text' => 0,
    'domain_decoded' => 0,
    'route_id' => 0,
    'route' => 0,
    'app_url' => 0,
    'app' => 0,
    'wa_url' => 0,
    'misconfigured_settlement' => 0,
    'route_name' => 0,
    'themes' => 0,
    'wa' => 0,
    'locales' => 0,
    '_l' => 0,
    'is_https' => 0,
    'params' => 0,
    'p' => 0,
    'key' => 0,
    'value' => 0,
    'wa_app_url' => 0,
    'domain_id' => 0,
    'last_app_route' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a8c82e0cf5a28_84403264',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a8c82e0cf5a28_84403264')) {function content_6a8c82e0cf5a28_84403264($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/function.html_options.php';
?><?php $_smarty_tpl->tpl_vars['is_custom_text'] = new Smarty_variable($_smarty_tpl->tpl_vars['app_id']->value===':text', null, 0);?>

<?php if (!function_exists('smarty_template_function__after_url')) {
    function smarty_template_function__after_url($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['_after_url']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
<?php if ($_smarty_tpl->tpl_vars['url_exact']->value){?>
    <span data-wa-tooltip-placement="left" data-wa-tooltip-template="#template-url-exact">
        <i class="fas fa-question-circle text-light-gray"></i>
    </span>
    <div class="wa-tooltip-template" id="template-url-exact">
        <?php echo sprintf_wp('The URL is defined as a routing rule and may include special characters, URL patterns, and regular expressions. In most cases, a rule should end with <em>/*</em>, e.g., <em>shop/*</em>, so that the app can handle all URLs within this address range. <a %s>Learn more about routing %s</a>',sprintf('href="%s" target="_blank"',_w('https://www.webasyst.com/developers/docs/routing/site-app-routing/')),'<i class="fas fa-external-link-alt fa-xs"></i>');?>

    </div>
    <input type="hidden" name="url_exact" value="1">
<?php }elseif(!$_smarty_tpl->tpl_vars['is_custom_text']->value){?>
    <span class="custom-mr-4">/</span>
<?php }?>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php if (!function_exists('smarty_template_function__render_field_url')) {
    function smarty_template_function__render_field_url($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['_render_field_url']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
<div class="field field-url">
    <div class="name for-input">URL</div>
    <div class="value">
        <div class="s-route-block flexbox wrap middle space-4 full-width" id="s-route-where">
            <span class="break-word custom-py-6">http://<?php echo $_smarty_tpl->tpl_vars['domain_decoded']->value;?>
/</span>
            <?php if (strlen($_smarty_tpl->tpl_vars['route_id']->value)){?>
                <!-- existing route -->
                <input type="text" name="params[url]" value="<?php if ($_smarty_tpl->tpl_vars['url_exact']->value||$_smarty_tpl->tpl_vars['is_custom_text']->value){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['route']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?><?php echo htmlspecialchars((string)rtrim($_smarty_tpl->tpl_vars['route']->value['url'],'/*'), ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" class="js-url small full-width">
            <?php }else{ ?>
                <!-- new route -->
                <input type="text" name="params[url]" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['app_url']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="js-url small full-width">
            <?php }?>
            <?php smarty_template_function__after_url($_smarty_tpl,array());?>

            <input type="hidden" name="params[app]" value="<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
">
        </div>
    </div>
</div>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<div class="dialog s-section-settings-dialog" id="js-section-settings-dialog">
    <div class="dialog-background"></div>
    <div class="dialog-body">
        <header class="dialog-header flexbox middle full-width">
            <?php if (strlen($_smarty_tpl->tpl_vars['route_id']->value)){?>
            <h1 class="custom-mb-0"><?php if ($_smarty_tpl->tpl_vars['is_custom_text']->value){?>Настройки правила<?php }else{ ?>Настройки приложения<?php }?></h1>
            <?php }else{ ?>
            <h1 class="custom-mb-0"><?php if ($_smarty_tpl->tpl_vars['is_custom_text']->value){?>Добавить Произвольный текст<?php }else{ ?><?php if (!empty($_smarty_tpl->tpl_vars['app']->value['name'])){?>Добавить приложение <?php echo $_smarty_tpl->tpl_vars['app']->value['name'];?>
<?php }else{ ?>Добавить приложение <?php echo (($tmp = @htmlspecialchars((string)$_smarty_tpl->tpl_vars['route']->value['app'], ENT_QUOTES, 'UTF-8', true))===null||$tmp==='' ? '' : $tmp);?>
<?php }?></span><?php }?></h1>

                <?php if ($_smarty_tpl->tpl_vars['is_custom_text']->value){?>
                <i class="fas fa-file-code largest text-gray"></i>
                <?php }else{ ?>
                <img class="icon size-32" src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['app']->value['icon'][24];?>
" />
                <?php }?>
            <?php }?>
        </header>
        <div class="dialog-content">
            <form>
            <?php if (!$_smarty_tpl->tpl_vars['url_exact']->value){?>
                <?php /*  Call merged included template "templates/layouts/includes/alert_misconfigured_settlement.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/layouts/includes/alert_misconfigured_settlement.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('misconfigured_settlement'=>$_smarty_tpl->tpl_vars['misconfigured_settlement']->value), 0, '19746387076a8c82e0cb7527-18955106');
content_6a8c82e0ccd224_23440835($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/layouts/includes/alert_misconfigured_settlement.html" */?>
            <?php }?>
            <div class="fields">
                <?php if ($_smarty_tpl->tpl_vars['app']->value){?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['priority_settlement'])){?>
                        <input type="hidden" name="params[priority_settlement]" value="1">
                    <?php }?>
                    <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['old_url'])){?>
                        <input type="hidden" name="params[old_url]" value="<?php echo $_smarty_tpl->tpl_vars['route']->value['old_url'];?>
">
                    <?php }?>
                    <div class="custom-mb-16">
                        <div class="field">
                            <div class="name custom-pt-4">Название</div>
                            <div class="value">
                                <input type="text" name="params[_name]" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['route_name']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="bold small width-100" /><br />
                                <span class="hint">Если опубликовано,  может быть использовано в меню навигации сайта <em>&#123;$wa-apps()&#125;</em>.</span>
                            </div>
                        </div>

                        <div class="field field-redirect-disabled">
                            <div class="name custom-pt-0">
                                Публикация
                            </div>
                            <div class="value">
                                <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['private'])){?>
                                    <?php $_smarty_tpl->tpl_vars['route_disabled'] = new Smarty_variable(null, null, 0);?>
                                <?php }else{ ?>
                                    <?php $_smarty_tpl->tpl_vars['route_disabled'] = new Smarty_variable(1, null, 0);?>
                                <?php }?>

                                <div class="switch-with-text">
                                    <span class="switch smaller" id="switch-redirect-dialog-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['route_id']->value)===null||$tmp==='' ? 'new' : $tmp);?>
">
                                        <input type="checkbox" id="switch-redirect" name="private" value="1" <?php if (empty($_smarty_tpl->tpl_vars['route']->value['private'])){?>checked<?php }?>>
                                        <input type="hidden" id="switch-redirect-hidden" name="params[private]" value="1" <?php if (empty($_smarty_tpl->tpl_vars['route']->value['private'])){?>disabled<?php }?>>
                                    </span>
                                    <label class="bold s-small" for="switch-redirect" data-active-text="Опубликовано" data-inactive-text="Не опубликовано"><?php if (empty($_smarty_tpl->tpl_vars['route']->value['private'])){?>Опубликовано<?php }else{ ?>Не опубликовано<?php }?></label>
                                </div>
                                <script>
                                    ( function($) {
                                        $("#tooltip").waTooltip({
                                            placement: 'right'
                                        });
                                        $switch = $("#switch-redirect-dialog-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['route_id']->value)===null||$tmp==='' ? 'new' : $tmp);?>
");
                                        $switch.waSwitch({
                                            ready: function (wa_switch) {
                                                let $label = wa_switch.$wrapper.siblings('label');
                                                let $input = wa_switch.$wrapper.find('#switch-redirect-hidden');
                                                wa_switch.$label = $label;
                                                wa_switch.$input = $input;
                                                wa_switch.active_text = $label.data('active-text');
                                                wa_switch.inactive_text = $label.data('inactive-text');
                                            },
                                            change: function(active, wa_switch) {
                                                if (active) {
                                                    wa_switch.$input.attr('disabled', true)
                                                    wa_switch.$label.text(wa_switch.active_text);
                                                }
                                                else {
                                                    wa_switch.$input.attr('disabled', false)
                                                    wa_switch.$label.text(wa_switch.inactive_text);
                                                }
                                            }
                                        });
                                    })(jQuery);
                                </script>
                                <div class="hint">Когда выключено, приложение доступно по своему прямому адресу, но не индексируется поисковыми системами и не включается в главное меню <em>&#123;$wa-apps()&#125;</em>.</div>
                            </div>
                        </div>

                        <?php smarty_template_function__render_field_url($_smarty_tpl,array());?>


                        <?php if ($_smarty_tpl->tpl_vars['themes']->value){?>
                            <div class="field">
                                <div class="name">Тема оформления</div>
                                <div class="value">
                                    <div class="wa-select small">
                                        <?php echo smarty_function_html_options(array('name'=>"params[theme]",'options'=>$_smarty_tpl->tpl_vars['themes']->value,'selected'=>ifempty($_smarty_tpl->tpl_vars['route']->value['theme'],'default')),$_smarty_tpl);?>

                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <div class="name">Мобильная тема оформления</div>
                                <div class="value">
                                    <div class="wa-select small">
                                        <?php echo smarty_function_html_options(array('name'=>"params[theme_mobile]",'options'=>$_smarty_tpl->tpl_vars['themes']->value,'selected'=>ifempty($_smarty_tpl->tpl_vars['route']->value['theme_mobile'],'default')),$_smarty_tpl);?>

                                    </div>
                                    <br />
                                    <span class="hint">Тема оформления для мобильных multi-touch устройств (iPhone, Android и пр.)</span>
                                </div>
                            </div>
                            <div class="field">
                                <div class="name">Язык</div>
                                <div class="value">
                                    <?php if (!strlen($_smarty_tpl->tpl_vars['route_id']->value)){?><?php $_smarty_tpl->tpl_vars['_l'] = new Smarty_variable($_smarty_tpl->tpl_vars['wa']->value->locale(), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['_l'] = new Smarty_variable(ifset($_smarty_tpl->tpl_vars['route']->value['locale'],''), null, 0);?><?php }?>
                                    <div class="wa-select small">
                                        <?php echo smarty_function_html_options(array('name'=>"params[locale]",'options'=>$_smarty_tpl->tpl_vars['locales']->value,'selected'=>$_smarty_tpl->tpl_vars['_l']->value),$_smarty_tpl);?>
</div><br>
                                    <span class="hint">Выберите язык для перевода текстовых строк на страницах сайта.<br>
                        Если выбран вариант «Авто», язык сайта будет определен на основании настроек браузера пользователя.</span>
                                </div>
                            </div>
                        <?php }?>

                        <div class="field">
                            <div class="name for-switch">Безопасность</div>
                            <div class="value">
                            <label>
                                <span class="wa-checkbox">
                                    <input type="checkbox" value="1" id="ssl_all" name="params[ssl_all]" <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['ssl_all'])){?> checked<?php }?> <?php if (empty($_smarty_tpl->tpl_vars['is_https']->value)){?>disabled<?php }?>>
                                    <span>
                                        <span class="icon">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </span>
                                </span>
                                <span class="s-small">Перенаправлять на HTTPS</span><br>
                                <span class="hint ssl_server_https bold" style="<?php if (!empty($_smarty_tpl->tpl_vars['is_https']->value)){?>display: none<?php }?>">Включение перенаправления недоступно, потому что ваш веб-сервер не позволяет отличать подключение через HTTP от HTTPS.<br></span>
                                <span class="hint ssl_all_hide bold" style="display: none">Чтобы активировать эту настройку, <a>войдите через HTTPS</a>.<br></span>
                                <span class="hint">
                                    Перенаправлять посетителей сайта с обычного HTTP- на безопасное HTTPS-подключение в пределах этого приложения.
                                    <br>
                                    Эта настройка будет работать, только если для вашего доменного имени установлен SSL-сертификат.
                                    <br>
                                    Чтобы включить перенаправление на HTTPS для <em>всех</em> поселений сайта, измените этот параметр в общих настройках сайта.
                                </span>
                            </label>
                            </div>
                        </div>
                    </div>
                    <div class="custom-mb-16">
                        <?php if (!empty($_smarty_tpl->tpl_vars['params']->value)){?>
                            <?php  $_smarty_tpl->tpl_vars['p'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['p']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['params']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['p']->key => $_smarty_tpl->tpl_vars['p']->value){
$_smarty_tpl->tpl_vars['p']->_loop = true;
?>
                                <?php if (is_array($_smarty_tpl->tpl_vars['p']->value)){?>
                                    <?php if ($_smarty_tpl->tpl_vars['p']->value['type']=='hidden'){?>
                                        <?php echo $_smarty_tpl->tpl_vars['p']->value['value'];?>

                                    <?php }else{ ?>
                                        <div class="field">
                                            <div class="name"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['p']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</div>
                                            <div class="value"><?php echo $_smarty_tpl->tpl_vars['p']->value['value'];?>
</div>
                                        </div>
                                    <?php }?>
                                <?php }else{ ?>
                                    <h5 class="heading clear-both"><br><?php echo $_smarty_tpl->tpl_vars['p']->value;?>
<br><br></h5>
                                <?php }?>
                            <?php } ?>
                        <?php }?>
                    </div>
                    <div class="custom-mb-16">
                        <div class="field">
                            <div class="name">Дополнительные параметры</div>
                            <div class="value">
                                <textarea class="small width-100" name="other_params"><?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['route']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?><?php if (!in_array($_smarty_tpl->tpl_vars['key']->value,array('app','url','theme','theme_mobile','locale','private','ssl','ssl_all','disabled','is_broken_route_url','priority_settlement','old_url'))&&substr($_smarty_tpl->tpl_vars['key']->value,0,1)!='_'&&!isset($_smarty_tpl->tpl_vars['params']->value[$_smarty_tpl->tpl_vars['key']->value])&&is_scalar($_smarty_tpl->tpl_vars['value']->value)){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8', true);?>
=<?php if ($_smarty_tpl->tpl_vars['value']->value===false){?>0<?php }else{ ?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['value']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?><?php echo "\n";?>
<?php }?><?php } ?></textarea>
                                <p class="hint">Необязательный набор параметров вида <em>key=value</em>, к значениям которых можно обращаться шаблонах дизайна и страницах этого приложения как <em>&#123;$wa->param("key")&#125;</em>. Каждая пара key=value должна быть указана на отдельной строке. <a href="https://developers.webasyst.ru/docs/templates/design-themes/" target="_blank">Помощь</a> <i class="icon10 new-window"></i></p>
                            </div>
                        </div>
                    </div>
                <?php }elseif($_smarty_tpl->tpl_vars['app_id']->value===':text'){?>
                    <div class="custom-text-fields">
                        <div class="field">
                            <div class="name custom-pt-4">Название</div>
                            <div class="value">
                                <input type="text" name="params[_name]" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['route_name']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="bold small width-100" />
                            </div>
                        </div>

                        <?php smarty_template_function__render_field_url($_smarty_tpl,array());?>


                        <div class="field">
                            <div class="name">Произвольный текст</div>
                            <div class="value">
                                <textarea class="small width-100" name="params[static_content]" placeholder="Текст, который должен быть доступен по указанному URL"><?php echo htmlspecialchars((string)(($tmp = @$_smarty_tpl->tpl_vars['route']->value['static_content'])===null||$tmp==='' ? '' : $tmp), ENT_QUOTES, 'UTF-8', true);?>
</textarea>
                            </div>
                        </div>
                        <div class="field">
                            <div class="name">Тип содержимого</div>
                            <div class="value">
                                <div class="wa-select small">
                                    <select name="params[static_content_type]">
                                        <option value=""<?php if (empty($_smarty_tpl->tpl_vars['route']->value['static_content_type'])){?> selected<?php }?>>файл</option>
                                        <option value="text/plain" <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['static_content_type'])&&($_smarty_tpl->tpl_vars['route']->value['static_content_type']==='text/plain')){?> selected<?php }?>>текст (text/plain)</option>
                                        <option value="text/html" <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['static_content_type'])&&($_smarty_tpl->tpl_vars['route']->value['static_content_type']==='text/html')){?> selected<?php }?>>HTML-код (text/html)</option>
                                    </select>
                                </div>
                                <div class="hint">Если выбран тип содержимого «файл» и в конце URL не указано расширение имени, то автоматически начнется скачивание файла при запросе этого URL.</div>
                            </div>
                        </div>
                    </div>

                <?php }elseif($_smarty_tpl->tpl_vars['app']->value===false){?>
                    <div class="">
                        <div class="field">
                            <div class="alert danger">
                                <div class="flexbox space-8">
                                    <div><i class="icon fas fa-exclamation-triangle exclamation"></i></div>
                                    <div class="wide"><?php echo sprintf("Приложение [%s] удалено или отключено.",htmlspecialchars((string)$_smarty_tpl->tpl_vars['app_id']->value, ENT_QUOTES, 'UTF-8', true));?>
</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>

            </div>
            </form>
        </div>
        <footer class="dialog-footer">
            <div class="flexbox space-24">
                <div class="wide flexbox middle wrap space-8">
                    <?php if ($_smarty_tpl->tpl_vars['app']->value||$_smarty_tpl->tpl_vars['app_id']->value===':text'){?>
                    <button class="js-save button">Сохранить</button>
                    <button class="js-close-dialog button light-gray">Отмена</button>
                    <?php }else{ ?>
                    <button class="js-close-dialog button light-gray">Закрыть</button>
                    <?php }?>
                    <span class="js-place-for-errors state-caution-hint"></span>
                </div>
                <?php if (strlen($_smarty_tpl->tpl_vars['route_id']->value)){?>
                    <div class="nowrap">
                        <button class="js-delete red nobutton"><i class="fas fa-trash-alt"></i> Удалить</button>
                    </div>
                <?php }?>
            </div>
        </footer>
    </div>
</div>

<script>(function() { "use strict";

    const site_app_url = <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
;
    const domain_id = <?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
;
    const $route_id = '<?php echo $_smarty_tpl->tpl_vars['route_id']->value;?>
';
    const save_url = site_app_url + '?module=configure&action=redirectSave' + '&domain_id=' + domain_id + '&route=' + $route_id;
    const delete_url = site_app_url + '?module=configure&action=redirectDelete' + '&domain_id=' + domain_id;

    const $wrapper = $('#js-section-settings-dialog');
    const $form = $wrapper.find('form');
    const $save_button = $wrapper.find('.js-save');
    const wa_loading = $.waLoading();
    const $place_for_errors = $wrapper.find('.js-place-for-errors');
    var dialog;

    initToggle();

    $wrapper.find('[data-wa-tooltip-template],[data-wa-tooltip-content]').waTooltip({
        interactive: true
    });

    function initToggle() {
        setTimeout(() => {
            $form.find("#toggle-response-code").waToggle({
                change: function(event, target, toggle) {
                    const input = toggle.$wrapper.find('input');
                    input.attr('checked') ? input.attr('checked', false) : input.attr('checked', true);
                    input.val($(target).data('id'));
                }
            });
        }, 1);
    }

    // Save to server when user clicks Save button
    $save_button.on('click', function() {
        saveHandler();
        return false;
    });
    $form.submit(function() {
        saveHandler();
        return false;
    });

    // Delete page when user clicks on Delete button
    $wrapper.on('click', '.js-delete', function() {
        const is_custom_text = '<?php echo $_smarty_tpl->tpl_vars['app_id']->value;?>
' === ':text';
        let last_app_route = <?php echo json_encode($_smarty_tpl->tpl_vars['last_app_route']->value);?>
,
            content = is_custom_text ? 'Правило будет удалено из настроек сайта. Продолжить?' : 'Приложение будет удалено из настроек сайта. Продолжить?';

        if (last_app_route) {
            content = '<?php echo sprintf('Вы удаляете единственное настроенное правило маршрутизации для приложения «<b>%s</b>».',ifempty($_smarty_tpl->tpl_vars['app']->value['name'],$_smarty_tpl->tpl_vars['app_id']->value));?>
</p><p>Это ограничит его функциональность. Удалить правило?';
        }

        $.waDialog.confirm({
            title: is_custom_text ? 'Удалить правило?' : 'Удалить приложение?',
            text: content,
            success_button_title: $_('Delete'),
            success_button_class: 'danger',
            cancel_button_title: $_('Cancel'),
            cancel_button_class: 'light-gray',
            onSuccess: deleteHandler
        });
    });

    function deleteHandler(d) {
        if (!$route_id) {
            return;
        }
        const $loading = $('<span><i class="fas fa-spinner fa-spin"></i></span>');
        d.$block.find('.dialog-footer').append($loading);
        $.post(delete_url, { route: $route_id }).then(function(r) {
            handleResponse(r, () => {
                $wrapper.data('dialog').close();
                if (typeof $.site.reloadWithScrollTo === 'function') {
                    $.site.reloadWithScrollTo();
                } else {
                    $(document).trigger('wa_delete_route', [$route_id]);
                }
            });
        }, function(r) {
            console.log('Error saving page settings', arguments);
            updateRoutingErrors(r.errors);
        }).always(function() {
            $loading.remove();
        });
    }

    // Rule address contains unsupported character, regexp for define it
    const invalid_url_regexp = /(\&|\$|\+|\,|\;|\=|\?|\@|\#|\[|\]|\}|\||\^|\%)/;

    let errors = [];

    function updateRoutingErrors(errors) {
        if ($.isArray(errors)) {
            errors.forEach(function(e) {
                var $field = null;
                if (e.field) {
                    $field = $form.find('[name="'+e.field+'"]');
                }
                const $msg = $('<div class="state-error-hint"></div>').html(e.description);

                if($field && $field.length) {
                    $field.addClass('state-error').after($msg);
                }
            });
            return
        }
        $place_for_errors.append(errors);
    }

    function validateUrls() {
        const $url_inputs = $form.find('.js-url');

        $url_inputs.each(function(index, url_input) {

            const $url_input = $(url_input);
            if ($url_input.length) {
                var url = $url_input.val(),
                    res = url.match(invalid_url_regexp);
                if (res) {
                    //$settings_form_status.html('');
                    var title = 'Невозможно сохранить правило',
                        content = 'В адресе правила содержится недопустимый символ <strong class="highlighted">%s</strong>.';
                    content = content.replace(/\%s/, res[0]);
                    errors[index] = {
                        field: $url_input.attr("name"),
                        description: content
                    };
                }
            }
        })
        if (errors.length) {
            console.log(errors)
            updateRoutingErrors(errors);
            return false
        }

        return true;
    }

    function saveHandler() {
        //clear errors
        errors = [];
        $form.find('.state-error').removeClass('state-error');
        $form.find('.state-error-hint').remove();
        $place_for_errors.empty();

        // Validating unsupported characters in url
        if (!validateUrls()) return

        wa_loading.show();
        wa_loading.animate(4000, 99, false);

        $.post(save_url, $form.serialize(), 'json').then(function(r) {
            wa_loading.done();
            handleResponse(r, () => {
                $wrapper.data('dialog').close();
                if (r?.status === 'ok') {
                    if (typeof $.site.reloadWithScrollTo === 'function') {
                        $.site.reloadWithScrollTo();
                    } else {
                        $(document).trigger('wa_update_route', [$route_id, r.data]);
                    }
                }
            });
        }, function() {
            console.log('Error saving page settings', arguments);
            updateRoutingErrors(r.errors);
            wa_loading.abort();
        });
    }

    function handleResponse(res, cbSuccess) {
        if (!res) return;

        $place_for_errors.empty();

        if (Array.isArray(res.errors)) {
            updateErrors(res.errors, true);
            $place_for_errors.append(res.errors.map(err => err.description).join('<br>'));
            return;
        } else if (res.errors) {
            $place_for_errors.append(res.errors);
            return;
        } else if (res.data && res.data.confirm) {
            $place_for_errors.append(res.data.confirm);
            return;
        }

        if (res?.data?.routing_errors?.incorrect) {
            $(document).trigger('wa_update_routing_errors', [res.data.routing_errors]);
        }

        if (res?.status === 'ok' && typeof cbSuccess === 'function') {
            cbSuccess(res);
        }
    }
})();</script>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-24 17:44:00
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/layouts/includes/alert_misconfigured_settlement.html" */ ?>
<?php if ($_valid && !is_callable('content_6a8c82e0ccd224_23440835')) {function content_6a8c82e0ccd224_23440835($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['misconfigured_settlement']->value){?>
<div class="incorrect-rule-error alert warning small">
    <div class="flexbox space-12">
        <div>
            <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
        </div>
        <div class="wide">
            В старом интерфейсе этот раздел или приложение были заданы с ошибкой и поэтому недоступны на сайте. Исправить ошибку можно в старом интерфейсе или прямо здесь.
        </div>
        <div>
            <a href="javascript:void(0)" class="js-fix-incorrect-rule button orange nowrap">Исправить</a>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.js-fix-incorrect-rule').one('click', function () {
            $('#js-section-settings-dialog form:first').append('<input type="hidden" name="fix_incorrect_rule" value="1">');
            $('.incorrect-rule-error').remove();
        });
    })
</script>
<?php }?>
<?php }} ?>