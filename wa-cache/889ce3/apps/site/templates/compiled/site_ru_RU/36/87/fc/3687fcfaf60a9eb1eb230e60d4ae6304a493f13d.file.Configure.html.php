<?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:22
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/configure/Configure.html" */ ?>
<?php /*%%SmartyHeaderCode:1694586276a889cfe763973-79413469%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3687fcfaf60a9eb1eb230e60d4ae6304a493f13d' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/configure/Configure.html',
      1 => 1750844366,
      2 => 'file',
    ),
    '6764a4e175d8ad228226f61b45b8f6d3eed24613' => 
    array (
      0 => '/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/domain_tabs.html',
      1 => 1745480410,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1694586276a889cfe763973-79413469',
  'function' => 
  array (
    '_renderCdnItem' => 
    array (
      'parameter' => 
      array (
        '_cdn' => '',
      ),
      'compiled' => '',
    ),
    '_renderRobotsControl' => 
    array (
      'parameter' => 
      array (
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'title' => 0,
    'domain' => 0,
    '_cdn' => 0,
    'robots' => 0,
    'robots_message' => 0,
    'domain_id' => 0,
    'wa' => 0,
    'domain_alias' => 0,
    'ssl_all' => 0,
    'is_https' => 0,
    'domain_apps_type' => 0,
    'domain_apps' => 0,
    'row' => 0,
    'favicon' => 0,
    'has_favicon' => 0,
    'favicon_message' => 0,
    'touchicon' => 0,
    'has_touchicon' => 0,
    'touchicon_message' => 0,
    'touchicon_title' => 0,
    'cdn_list' => 0,
    'head_js' => 0,
    'google_analytics' => 0,
    'code_hint' => 0,
    'backend_settings' => 0,
    '_' => 0,
    'custom_texts' => 0,
    'route_id' => 0,
    'route' => 0,
    'redirects' => 0,
    'route_disabled' => 0,
    'url' => 0,
    'routing_errors' => 0,
    'routes' => 0,
    'app_disabled' => 0,
    'wa_url' => 0,
    'is_misconfigured_settlement' => 0,
    'apps' => 0,
    'app' => 0,
    'scroll_to' => 0,
    'domains' => 0,
    '_domains' => 0,
    'latter_apps_names' => 0,
    '_latter_apps_names' => 0,
    '_domains_count' => 0,
    'single_site' => 0,
    '_latter_apps_names_count' => 0,
    'domain_idn' => 0,
    'wa_app_url' => 0,
    'delete_content' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_6a889cfe7cebc3_61225316',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_6a889cfe7cebc3_61225316')) {function content_6a889cfe7cebc3_61225316($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.truncate.php';
if (!is_callable('smarty_modifier_join')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty-plugins/modifier.join.php';
?>

<?php if (!isset($_smarty_tpl->tpl_vars['title']->value)){?>
    <?php $_smarty_tpl->tpl_vars['title'] = new Smarty_variable($_smarty_tpl->tpl_vars['domain']->value['title']!==$_smarty_tpl->tpl_vars['domain']->value['name'], null, 0);?>
<?php }?>

<?php $_smarty_tpl->tpl_vars['url'] = new Smarty_variable($_smarty_tpl->tpl_vars['domain']->value['name'], null, 0);?>

<?php if (!function_exists('smarty_template_function__renderCdnItem')) {
    function smarty_template_function__renderCdnItem($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['_renderCdnItem']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <div class="s-cdn-item js-cdn">
        <input class="s-cdn-input long" type="text" name="cdn[]" value="<?php echo $_smarty_tpl->tpl_vars['_cdn']->value;?>
" autocomplete="off" placeholder="Адрес CDN" />
        <span class="icon cursor-pointer js-cdn-remove" title="Удалить" style="display: none;"><i class="fas fa-times-circle" ></i></span>
    </div>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>

<?php if (!function_exists('smarty_template_function__renderRobotsControl')) {
    function smarty_template_function__renderRobotsControl($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['_renderRobotsControl']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
    <div class="field">
        <div class="name">robots.txt</div>
        <div class="value">
            <textarea class="width-100 s-small" name="robots"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['robots']->value, ENT_QUOTES, 'UTF-8', true);?>
</textarea>
            <?php if (isset($_smarty_tpl->tpl_vars['robots_message']->value)){?><div class="hint text-red"><?php echo $_smarty_tpl->tpl_vars['robots_message']->value;?>
</div><?php }else{ ?><span class="hint">Содержимое файла robots.txt этого сайта</span><?php }?>
        </div>
    </div>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<div class="article site-base">
    <div class="article-body">
        <?php /*  Call merged included template "templates/actions/backend/includes/domain_tabs.html" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate("templates/actions/backend/includes/domain_tabs.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('selected'=>'settings'), 0, '1694586276a889cfe763973-79413469');
content_6a889cfe76d426_20383578($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "templates/actions/backend/includes/domain_tabs.html" */?>
        <div class="form s-settings-page" id="s-settings-page">
            <form target="s-settings-iframe" id="s-settings-form" method="post" action="?module=configure&action=save&domain_id=<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
" enctype="multipart/form-data">
                <?php echo $_smarty_tpl->tpl_vars['wa']->value->csrf();?>

                <div class="fields custom-my-32">
                    <div class="field">
                        <div class="name bold for-input">Доменное имя</div>
                        <div class="value">
                            <strong class="custom-pr-4">http://</strong><input type="text" class="bold longer" name="url" value="<?php echo waIdna::dec(htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain']->value['name'], ENT_QUOTES, 'UTF-8', true));?>
" /><br />
                            <p class="hint">
                                <span class="custom-mr-2">Доменное имя должно быть зарегистрировано и настроено, чтобы указывать на этот аккаунт Webasyst.</span>
                                <a href="https://support.webasyst.ru/47313/get-domain-name/" target="_blank" class="nowrap">
                                    Помощь <i class="fas fa-external-link-alt icon smaller custom-ml-2"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="field">
                        <div class="name bold for-switch">Название сайта</div>
                        <div class="value">
                            <div class="s-domain-title-wrapper">
                                <div class="switch-with-text custom-pb-8" >
                                    <span class="switch smaller" id="switch-domain-title">
                                        <input id="s-domain-title" type="checkbox" name="" <?php if (!$_smarty_tpl->tpl_vars['title']->value){?>checked<?php }?>>
                                    </span>
                                    <label for="s-domain-title">Совпадает с доменным именем</label>
                                </div>
                                <div class="s-domain-title-input" <?php if (!$_smarty_tpl->tpl_vars['title']->value){?>style="display:none"<?php }?>>
                                    <input type="text" class="" name="title" value="<?php if ($_smarty_tpl->tpl_vars['title']->value){?><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"><br>
                                    <span class="hint">Может использоваться в бекенде в разделе выбора сайтов и на самом сайте в меню навигации &#123;$wa-&gt;apps()&#125;</span>
                                </div>
                                <script>
                                    ( function($) {
                                        $("#switch-domain-title").waSwitch({
                                            ready: function (wa_switch) {
                                            },
                                            change: function(active, wa_switch) {
                                                const $input_wrapper = $('.s-domain-title-input');
                                                if (!active) {
                                                    $input_wrapper.show();
                                                    $input_wrapper.children('input').removeAttr('disabled');
                                                }
                                                else {
                                                    $input_wrapper.hide();
                                                    $input_wrapper.children('input').attr('disabled', 'disabled');
                                                }
                                            }
                                        });
                                    })(jQuery);
                                </script>
                            </div>
                        </div>
                    </div>
                <?php if (!empty($_smarty_tpl->tpl_vars['domain_alias']->value)){?>
                    <div class="field">
                        <div class="name bold">Зеркало для сайта</div>
                        <div class="value no-shift">
                            <?php echo $_smarty_tpl->tpl_vars['domain_alias']->value;?>

                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="field">
                        <div class="name bold for-switch">Безопасность</div>
                        <div class="value no-shift">
                            <div class="switch-with-text ">
                                <span class="switch smaller" id="switch-ssl-all">
                                    <input type="checkbox" name="ssl_all" id="ssl_all" <?php if (ifset($_smarty_tpl->tpl_vars['ssl_all']->value)){?> checked<?php }?> <?php if (empty($_smarty_tpl->tpl_vars['is_https']->value)){?>disabled<?php }?>/>
                                </span>
                                <label for="ssl_all">Перенаправлять на HTTPS</label>

                            </div>
                            <div class="hint">
                                <span class="ssl_server_https bold" style="<?php if (!empty($_smarty_tpl->tpl_vars['is_https']->value)){?>display: none<?php }?>">Включение перенаправления недоступно, потому что ваш веб-сервер не позволяет отличать подключение через HTTP от HTTPS.<br></span>
                                <span class="ssl_all_hide bold" style="display: none">Чтобы активировать эту настройку, <a>войдите через HTTPS</a>.<br></span>
                                Перенаправлять посетителей сайта с обычного HTTP- на безопасное HTTPS-подключение.
                                <br>
                                Эта настройка будет работать, только если для вашего доменного имени установлен SSL-сертификат.
                            </div>

                            <script>
                                ( function($) {
                                    $("#switch-ssl-all").waSwitch();
                                })(jQuery);
                            </script>
                        </div>
                    </div>


                    <div class="field s-field-waapps">
                        <div class="name">
                            Ссылки в меню навигации по сайту
                            <div class="name-wa-apps">&#123;$wa-&gt;apps()&#125;</div>
                        </div>
                        <div class="value">
                            <div class="alert">
                                <div class="flexbox space-12 s-small">
                                    <span> <i class="fas fa-info-circle"></i></span>
                                    Внешний вид меню может быть дополнен или полностью изменен настройками темы дизайна.
                                </div>
                            </div>
                            <div class="flexbox space-4 custom-mb-12 waapps-auto">
                                <label for="waapps-auto">
                                    <span class="wa-radio custom-pt-4">
                                        <input type="radio" name="wa_apps_type" <?php if (!$_smarty_tpl->tpl_vars['domain_apps_type']->value){?>checked<?php }?> id="waapps-auto" value="0">
                                        <span></span>
                                    </span>
                                </label>
                                <div>
                                    <label for="waapps-auto" class="flexbox vertical custom-mb-12">
                                        Формировать автоматически из разделов и страниц верхнего уровня в карте сайта
                                    </label>
                                </div>
                            </div>
                            <div class="waapps-select">
                                <label for="waapps-select">
                                <span class="wa-radio custom-pt-4">
                                    <input type="radio" name="wa_apps_type" <?php if ($_smarty_tpl->tpl_vars['domain_apps_type']->value){?>checked<?php }?> id="waapps-select" value="1" />
                                    <span></span>
                                </span>
                                </label>
                                <label for="waapps-select"> Ручные настройки</label>
                                <div class="custom-my-16" <?php if (!$_smarty_tpl->tpl_vars['domain_apps_type']->value){?>style="display:none"<?php }?>>
                                    <table id="s-wa-apps" class="s-settings-apps custom-mt-16 custom-mb-8">
                                        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['domain_apps']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
                                        <tr>
                                           <td class="s-app"><a href="#" onclick="return false" class="custom-pr-8 text-light-gray"><span class="s-sort icon"><i class="fas fa-grip-vertical"></i></span></a>
                                              <span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['row']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                                              <input style="display:none" type="text" name="apps[name][]" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['row']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
" />
                                           </td>
                                           <td>
                                              <span><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['row']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                                              <input style="display:none" type="text" name="apps[url][]" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['row']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
" />
                                           </td>
                                           <td class="s-actions">
                                                <a href="#" class="s-apps-edit custom-pr-16 text-light-gray" title="Редактировать"><i class="icon fas fa-pen edit"></i></a>
                                                <a href="#" class="s-apps-delete text-light-gray" title="Удалить"><i class="icon fas fa-times-circle delete"></i></a>
                                           </td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                    <div style="display:none; text-align: right;" class="custom-mb-8"><span class="hint">После завершения редактирования нажмите на кнопку «Сохранить».</span></div>
                                    <a href="#" class="button rounded outlined smaller custom-mt-8" id="s-apps-add">
                                        <span class="icon add custom-pr-8"><i class="fas fa-plus"></i></span>Добавить ссылку
                                    </a>

                                </div>

                            </div>


                        </div>
                    </div>
                    <?php }?>


                    <?php if (!empty($_smarty_tpl->tpl_vars['domain_alias']->value)){?>
                    
                    <?php smarty_template_function__renderRobotsControl($_smarty_tpl,array());?>

                    <?php }?>

                    <?php if (empty($_smarty_tpl->tpl_vars['domain_alias']->value)){?>

                    
                    <div class="field s-field-favicon">
                        <?php $_smarty_tpl->tpl_vars['has_favicon'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['favicon']->value), null, 0);?>
                        <div class="name">
                            Иконка для браузера и поисковых систем (фавиконка)
                        </div>
                        <div class="value">
                            <div class="favicon-wrapper flexbox middle space-8 custom-pb-8" style="<?php if (!$_smarty_tpl->tpl_vars['has_favicon']->value){?>display: none<?php }?>">
                                <i class="icon favicon-icon" style="background-image: url('<?php if ($_smarty_tpl->tpl_vars['has_favicon']->value){?><?php echo $_smarty_tpl->tpl_vars['favicon']->value['icon'];?>
<?php }?>');"></i>
                                <span class="favicon-name s-small bold"><?php if ($_smarty_tpl->tpl_vars['has_favicon']->value){?><?php echo $_smarty_tpl->tpl_vars['favicon']->value['name'];?>
<?php }?></span>
                            </div>
                            <div class="flexbox wrap space-4 favicon-settings">
                                <a href="javascript:void(0)" class="button red rounded outlined smaller file-delete" style="<?php if (!$_smarty_tpl->tpl_vars['has_favicon']->value){?>display: none<?php }?>">
                                    <span class="icon add custom-pr-8 "><i class="fas fa-trash-alt"></i></span>Удалить
                                </a>
                                <div id="file-upload-favicon">
                                    <div class="upload flexbox">
                                        <label class="link button rounded outlined smaller">
                                            <i class="fas fa-file-upload"></i>
                                            <span>Загрузить</span>
                                            <input name="favicon" type="file" autocomplete="off">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($_smarty_tpl->tpl_vars['favicon_message']->value)){?><div class="hint text-red custom-mt-8"><?php echo $_smarty_tpl->tpl_vars['favicon_message']->value;?>
</div><?php }?>
                            <div class="hint">
                                Рекомендуются квадратная форма, размер 48x48 пикселей, формат файла PNG или ICO.
                                <div class="semibold">
                                    Фавиконка формата PNG будет дополнительно конвертирована в формат ICO.
                                </div>
                            </div>

                        </div>
                    </div>

                    
                    <div class="field s-field-touchicon">

                        <?php $_smarty_tpl->tpl_vars['has_touchicon'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['touchicon']->value), null, 0);?>
                        <div class="name">Иконка для телефонов</div>
                        <div class="value">
                            <div class="touchicon-wrapper flexbox middle space-8 custom-pb-8" style="<?php if (!$_smarty_tpl->tpl_vars['has_touchicon']->value){?>display: none<?php }?>">
                                <img src="<?php if ($_smarty_tpl->tpl_vars['has_touchicon']->value){?><?php echo $_smarty_tpl->tpl_vars['touchicon']->value['icon'];?>
<?php }?>" class="touchicon-icon">

                                <span class="touchicon-name s-small bold"><?php if ($_smarty_tpl->tpl_vars['has_touchicon']->value){?><?php echo $_smarty_tpl->tpl_vars['touchicon']->value['name'];?>
<?php }?></span>
                            </div>
                            <div class="flexbox wrap space-4 touchicon-settings">
                                <a href="javascript:void(0)" class="button red rounded outlined smaller file-delete" style="<?php if (!$_smarty_tpl->tpl_vars['has_touchicon']->value){?>display: none<?php }?>">
                                    <span class="icon add custom-pr-8 "><i class="fas fa-trash-alt"></i></span>Удалить
                                </a>
                                <div id="file-upload-touchicon">
                                    <div class="upload flexbox">
                                        <label class="link button rounded outlined smaller">
                                            <i class="fas fa-file-upload"></i>
                                            <span>Загрузить</span>
                                            <input name="touchicon" type="file" autocomplete="off">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="hint">Иконка отображается, например, при сохранении ярлыка на главном экране телефона. Рекомендуются квадратная форма, размер от 192x192 до 1024x1024 пикселей, формат файла PNG.</div>
                            <?php if (isset($_smarty_tpl->tpl_vars['touchicon_message']->value)){?><div class="hint text-red"><?php echo $_smarty_tpl->tpl_vars['touchicon_message']->value;?>
</div><?php }?>
                            <div class="custom-mt-8">
                                <input type="text" name="touchicon_title" value="<?php echo $_smarty_tpl->tpl_vars['touchicon_title']->value;?>
">
                                <div class="hint">Подпись под иконкой</div>
                            </div>
                        </div>
                    </div>

                    
                    <?php smarty_template_function__renderRobotsControl($_smarty_tpl,array());?>


                    
                    <div class="field">
                        <div class="name">CDN</div>
                        <div class="value js-cdn-wrapper">
                            <div class="js-cdn-list flexbox wrap space-12 vertical custom-pb-12">
                                <?php  $_smarty_tpl->tpl_vars['_cdn'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_cdn']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cdn_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_cdn']->key => $_smarty_tpl->tpl_vars['_cdn']->value){
$_smarty_tpl->tpl_vars['_cdn']->_loop = true;
?>
                                    <?php smarty_template_function__renderCdnItem($_smarty_tpl,array('_cdn'=>$_smarty_tpl->tpl_vars['_cdn']->value));?>

                                <?php }
if (!$_smarty_tpl->tpl_vars['_cdn']->_loop) {
?>
                                    <?php smarty_template_function__renderCdnItem($_smarty_tpl,array());?>

                                <?php } ?>
                            </div>
                            <a href="javascript:void(0)" class="s-cdn-add js-cdn-add button rounded outlined smaller">
                                <span class="icon add custom-pr-8"><i class="fas fa-plus"></i></span>Добавить
                            </a>
                            <p class="hint">
                                Введите адрес CDN (Content Delivery Network), чтобы включить его автоматическую поддержку для этого сайта.
                            </p>
                        </div>
                    </div>

                    <div class="field s-field-custom-code">
                        <div class="name">Пользовательский JavaScript-код внутри &lt;head&gt;</div>
                        <div class="value">
                            <div class="">
                                <div class="width-100 s-small custom-pb-8">Дополнительный JavaScript-код для вставки перед закрывающим тегом &lt;/head&gt;:</div>
                                <textarea class="width-100 s-small custom-code-textarea" name="head_js" placeholder="&lt;script&gt;&lt;/script&gt;"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['head_js']->value, ENT_QUOTES, 'UTF-8', true);?>
</textarea>
                            </div>

                            <div class="flexbox wrap space-8 custom-pb-16">
                                <div class="width-100 s-small">Google Analytics Property ID:</div>
                                <input class="long" type="text" name="google_analytics[code]" value="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['google_analytics']->value['code'], ENT_QUOTES, 'UTF-8', true);?>
">
                                <div class="wa-select">
                                    <select name="google_analytics[universal]" class="js-ga-select">
                                        <option value="1" data-hint="UA-123456-1" <?php if (!empty($_smarty_tpl->tpl_vars['google_analytics']->value['universal'])){?>selected<?php }?>>Universal Analytics</option>
                                        <option value="0" data-hint="G-FJBLKODBA0" <?php if (empty($_smarty_tpl->tpl_vars['google_analytics']->value['universal'])){?>selected<?php }?>>Google Analytics 4</option>
                                    </select>
                                </div>

                                <?php if (!empty($_smarty_tpl->tpl_vars['google_analytics']->value['universal'])){?>
                                    <?php $_smarty_tpl->tpl_vars['code_hint'] = new Smarty_variable('UA-123456-1', null, 0);?>
                                <?php }else{ ?>
                                    <?php $_smarty_tpl->tpl_vars['code_hint'] = new Smarty_variable('G-FJBLKODBA0', null, 0);?>
                                <?php }?>
                                <span class="hint ga-hint"><?php echo sprintf('Например, <strong>%s</strong><br />Если вы используете <a href="https://www.google.com/analytics/">Google Analytics</a> для отслеживания статистики посещений и конверсии вашего сайта, то вместо внедрения кода Google Analytics вручную достаточно просто ввести здесь ваш Google Analytics Property ID. Правильный код Google Analytics автоматически вставится во все темы оформления всех приложений перед закрывающим тегом &lt;/head&gt;.',$_smarty_tpl->tpl_vars['code_hint']->value);?>
</span>
                            </div>
                        </div>

                    </div>

                    <!-- plugin hook: 'backend_settings.section' -->
                    
                    <?php  $_smarty_tpl->tpl_vars['_'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['_']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['backend_settings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['_']->key => $_smarty_tpl->tpl_vars['_']->value){
$_smarty_tpl->tpl_vars['_']->_loop = true;
?><?php echo ifset($_smarty_tpl->tpl_vars['_']->value['section']);?>
<?php } ?>

                    
                    <div class="fields-group s-field-group s-field-custom-text custom-py-20">
                        <div class="flexbox full-width space-12">
                            <div class="flexbox middle space-4">
                                <h4 class="custom-mb-0">Свои файлы в корне сайта</h4>
                            </div>
                            <button type="button" class="button nobutton small light-gray js-custom-text-toggle">
                                <i class="fas fa-cog"></i> Настройки <span><i class="fas fa-caret-down js-toggle-icon"></i></span>
                            </button>
                        </div>

                        <p class="small custom-mb-0">
                            Помогают подтвердить владение сайтом для интеграции со сторонними сервисами и могут также использоваться в других целях.
                        </p>

                        <div class="js-custom-text-value" style="display: none;">
                            <div class="table-scrollable-x custom-py-20">
                                <table id="s-custom-text-routes" class="s-routing single-lined">
                                    <?php  $_smarty_tpl->tpl_vars['route'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['route']->_loop = false;
 $_smarty_tpl->tpl_vars['route_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['custom_texts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['route']->key => $_smarty_tpl->tpl_vars['route']->value){
$_smarty_tpl->tpl_vars['route']->_loop = true;
 $_smarty_tpl->tpl_vars['route_id']->value = $_smarty_tpl->tpl_vars['route']->key;
?>
                                        <tr data-route-id="<?php echo $_smarty_tpl->tpl_vars['route_id']->value;?>
">
                                            <td class="nowrap app-name-td <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['private'])){?>gray<?php }?>">
                                                <div class="flexbox middle space-8 app-name-wrapper">
                                                    <span class="app-icon"><i class="fas fa-file-code"></i></span>
                                                    <span class="text"><?php echo htmlspecialchars((string)smarty_modifier_truncate(ifset($_smarty_tpl->tpl_vars['route']->value,'_name',ifset($_smarty_tpl->tpl_vars['route']->value['static_content'])),32), ENT_QUOTES, 'UTF-8', true);?>
</span>
                                                </div>
                                            </td>
                                            <td class="s-url max-width <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['private'])){?>gray<?php }?>">
                                                <div><?php echo smarty_modifier_truncate(htmlspecialchars((string)$_smarty_tpl->tpl_vars['route']->value['url'], ENT_QUOTES, 'UTF-8', true),30,'...',false,true);?>
<i class="shortener"></i></div>
                                            </td>
                                            <td class="s-link">
                                                <a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value['name'];?>
/<?php echo $_smarty_tpl->tpl_vars['route']->value['url'];?>
" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                            </td>
                                            <td class="s-actions s-route-settings js-route-settings align-right">
                                                <span class="icon cursor-pointer" title="Настройки"><i class="icon fas fa-pen"></i></span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <!-- /fields -->
                                </table>
                            </div>
                            <a class="js-custom-text-add button rounded outlined smaller custom-mb-8" data-app-id=":text" href="javascript:void(0)">
                                <span class="icon add custom-pr-8"><i class="fas fa-plus"></i></span>Добавить
                            </a>
                        </div>
                    </div>

                    
                    <div class="fields-group s-field-group s-field-redirect custom-py-20">
                        <div class="flexbox full-width space-12">
                            <div class="flexbox middle space-4">
                                <h4 class="custom-mb-0">Перенаправления</h4>
                            </div>
                            <button type="button" class="button nobutton small light-gray js-redirect-toggle">
                                <i class="fas fa-cog"></i> Настройки <span><i class="fas fa-caret-down js-toggle-icon"></i></span>
                            </button>
                        </div>

                        <p class="small custom-mb-0">
                            Перенаправляйте посетителей сайта с устаревших адресов на новые.
                        </p>

                        <div class="js-redirect-value" style="display: none;">
                            <div class="table-scrollable-x custom-py-20">
                                <table id="s-routes" class="s-routing single-lined">
                                    <?php  $_smarty_tpl->tpl_vars['route'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['route']->_loop = false;
 $_smarty_tpl->tpl_vars['route_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['redirects']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['route']->key => $_smarty_tpl->tpl_vars['route']->value){
$_smarty_tpl->tpl_vars['route']->_loop = true;
 $_smarty_tpl->tpl_vars['route_id']->value = $_smarty_tpl->tpl_vars['route']->key;
?>
                                        <?php if (empty($_smarty_tpl->tpl_vars['route']->value['disabled'])&&ifempty($_smarty_tpl->tpl_vars['route']->value['disabled'])<=0){?>
                                            <?php $_smarty_tpl->tpl_vars['route_disabled'] = new Smarty_variable(1, null, 0);?>
                                        <?php }else{ ?>
                                            <?php $_smarty_tpl->tpl_vars['route_disabled'] = new Smarty_variable(null, null, 0);?>
                                        <?php }?>
                                        <tr id="route-<?php echo $_smarty_tpl->tpl_vars['route_id']->value;?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['route_id']->value;?>
" class="s-inc">
                                            <td class="s-url nowrap max-width <?php if (!$_smarty_tpl->tpl_vars['route_disabled']->value){?>text-gray<?php }?>">
                                                <div>
                                                    <span class="s-domain-url"><?php echo waIdna::dec((($tmp = @$_smarty_tpl->tpl_vars['url']->value)===null||$tmp==='' ? '' : $tmp));?>
/</span><span class="s-editable-url"><?php echo smarty_modifier_truncate(htmlspecialchars((string)$_smarty_tpl->tpl_vars['route']->value['url'], ENT_QUOTES, 'UTF-8', true),30,'...',false,true);?>
</span>
                                                    <span class="icon custom-px-4"><i class="fas fa-long-arrow-alt-right"></i></span>
                                                    <span class="redirect"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['route']->value['redirect'], ENT_QUOTES, 'UTF-8', true);?>
</span>
                                                    <i class="shortener"></i>
                                                </div>

                                            </td>
                                            
                                            <td class="s-actions s-route-settings js-route-settings align-right">
                                                <span class="icon cursor-pointer" title="Настройки"><i class="icon fas fa-pen"></i></span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <!-- /fields -->
                                </table>
                            </div>
                            <a href="javascript:void(0)" class="s-redirect-add js-redirect-add button rounded outlined smaller custom-mb-8">
                                <span class="icon add custom-pr-8"><i class="fas fa-plus"></i></span>Добавить
                            </a>
                        </div>
                    </div>

                    
                    <div class="fields-group s-field-group s-field-app custom-py-20">
                        <div class="flexbox full-width space-12">
                            <div class="flexbox middle space-8">
                                <h4 class="custom-mb-0">Приложения на сайте</h4>
                            </div>
                            <button type="button" class="button nobutton small light-gray js-app-toggle">
                                <i class="fas fa-cog"></i> Настройки <span><i class="fas fa-caret-down js-toggle-icon"></i></span>
                            </button>
                        </div>

                        <p class="small custom-mb-0">
                            Эти приложения не могут формировать целый раздел сайта, но могут показывать полезное содержимое на сайте или по отдельной ссылке.
                        </p>

                        <?php $_smarty_tpl->tpl_vars['routing_errors'] = new Smarty_variable(siteHelper::getRoutingErrorsInfo(), null, 0);?>
                        <?php if (!empty($_smarty_tpl->tpl_vars['routing_errors']->value['not_install'])){?>
                        <div id="not-install-error" class="text-orange custom-mt-12 custom-mb-4">
                            <div class="flexbox space-8">
                                <div>
                                    <span class="icon"><i class="fas fa-info-circle"></i></span>
                                </div>
                                <span class="alert-content wide"><?php echo $_smarty_tpl->tpl_vars['routing_errors']->value['not_install'];?>
</span>
                            </div>
                        </div>
                        <?php }?>
                        <div class="js-app-value" style="display: none;">
                            <div class="table-scrollable-x custom-py-20">
                                <table id="s-apps" class="s-apps s-routing single-lined">
                                    <?php  $_smarty_tpl->tpl_vars['route'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['route']->_loop = false;
 $_smarty_tpl->tpl_vars['route_id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['routes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['route']->key => $_smarty_tpl->tpl_vars['route']->value){
$_smarty_tpl->tpl_vars['route']->_loop = true;
 $_smarty_tpl->tpl_vars['route_id']->value = $_smarty_tpl->tpl_vars['route']->key;
?>
                                    <?php if ($_smarty_tpl->tpl_vars['route']->value['app']===':text'||isset($_smarty_tpl->tpl_vars['route']->value['app']['id'])){?>
                                        <?php $_smarty_tpl->tpl_vars['is_misconfigured_settlement'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['route']->value['misconfigured_settlement']), null, 0);?>
                                        <?php $_smarty_tpl->tpl_vars['app_disabled'] = new Smarty_variable(!empty($_smarty_tpl->tpl_vars['route']->value['app']['disabled']), null, 0);?>
                                        <tr data-route-id="<?php echo $_smarty_tpl->tpl_vars['route_id']->value;?>
">
                                            <td class="nowrap app-name-td <?php if ($_smarty_tpl->tpl_vars['app_disabled']->value||!empty($_smarty_tpl->tpl_vars['route']->value['private'])||!empty($_smarty_tpl->tpl_vars['route']->value['disabled'])||($_smarty_tpl->tpl_vars['route']->value['app']!==':text'&&!is_array($_smarty_tpl->tpl_vars['route']->value['app']))){?>gray<?php }?>">
                                            <div class="flexbox middle space-8 app-name-wrapper">
                                                <?php if (is_array($_smarty_tpl->tpl_vars['route']->value['app'])){?>
                                                    <span class="app-icon">
                                                        <?php if (!empty($_smarty_tpl->tpl_vars['route']->value['app']['icon'])){?>
                                                        <img src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['route']->value['app']['icon'][24];?>
" alt="">
                                                        <?php }else{ ?>
                                                        <i class="fas fa-question"></i>
                                                        <?php }?>
                                                    </span>
                                                    <?php echo (($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['route']->value['_name'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['route']->value['app']['name'] : $tmp))===null||$tmp==='' ? $_smarty_tpl->tpl_vars['route']->value['app']['id'] : $tmp);?>

                                                <?php }elseif($_smarty_tpl->tpl_vars['route']->value['app']===':text'){?>
                                                    <span class="app-icon"><i class="fas fa-file-code"></i></span>
                                                    <span class="text"><?php echo htmlspecialchars((string)smarty_modifier_truncate((($tmp = @$_smarty_tpl->tpl_vars['route']->value['static_content'])===null||$tmp==='' ? '' : $tmp),32), ENT_QUOTES, 'UTF-8', true);?>
</span>
                                                <?php }else{ ?>
                                                    <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['route']->value['app'], ENT_QUOTES, 'UTF-8', true);?>

                                                <?php }?>

                                                <?php if ($_smarty_tpl->tpl_vars['app_disabled']->value||$_smarty_tpl->tpl_vars['is_misconfigured_settlement']->value){?>
                                                    <span class="js-misconfigured-settlement icon small"><i class="fas fa-exclamation-triangle"></i></span>
                                                <?php }?>
                                            </div>
                                            </td>
                                            <td class="<?php if ($_smarty_tpl->tpl_vars['app_disabled']->value||!empty($_smarty_tpl->tpl_vars['route']->value['private'])||!empty($_smarty_tpl->tpl_vars['route']->value['disabled'])||($_smarty_tpl->tpl_vars['route']->value['app']!==':text'&&!is_array($_smarty_tpl->tpl_vars['route']->value['app']))){?>gray<?php }?><?php if ($_smarty_tpl->tpl_vars['app_disabled']->value||$_smarty_tpl->tpl_vars['is_misconfigured_settlement']->value){?> js-misconfigured-settlement strike<?php }?>"><?php if ($_smarty_tpl->tpl_vars['route']->value['app']===':text'){?>/<?php echo smarty_modifier_truncate(htmlspecialchars((string)$_smarty_tpl->tpl_vars['route']->value['url'], ENT_QUOTES, 'UTF-8', true),30,'...',false,true);?>
<?php }else{ ?>/<?php echo smarty_modifier_truncate(htmlspecialchars((string)rtrim($_smarty_tpl->tpl_vars['route']->value['url'],'/*'), ENT_QUOTES, 'UTF-8', true),30,'...',false,true);?>
/<?php }?></td>
                                            <td class="min-width s-route-settings js-app-settings cursor-pointer">
                                                <span class="icon cursor-pointer" title="Настройки"><i class="fas fa-cog"></i></span>
                                            </td>
                                        </tr>
                                    <?php }?>
                                    <?php } ?>
                                </table>
                            </div>
                            <div class="dropdown app-dropdown custom-mb-8" id="js-app-dropdown-add">
                                <a href="javascript:void(0)" class="dropdown-toggle without-arrow button rounded outlined smaller ">
                                    <span class="icon add custom-pr-8"><i class="fas fa-plus"></i></span>Добавить
                                </a>
                                <div class="dropdown-body">
                                    <ul class="menu">
                                        <?php  $_smarty_tpl->tpl_vars['app'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['app']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['apps']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['app']->key => $_smarty_tpl->tpl_vars['app']->value){
$_smarty_tpl->tpl_vars['app']->_loop = true;
?>
                                        <li>
                                            <a href="javascript:void(0);" class="js-add-app" data-app-id="<?php echo $_smarty_tpl->tpl_vars['app']->value['id'];?>
">
                                                <?php if (isset($_smarty_tpl->tpl_vars['app']->value['icon_class'])){?>
                                                <i class="<?php echo $_smarty_tpl->tpl_vars['app']->value['icon_class'];?>
"></i>
                                                <?php }else{ ?>
                                                <i class="icon"><img src="<?php echo $_smarty_tpl->tpl_vars['wa_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['app']->value['icon'][24];?>
" alt=""></i>
                                                <?php }?>
                                                <span><?php echo $_smarty_tpl->tpl_vars['app']->value['name'];?>
</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                    <script>
                        (function($) {
                            <?php $_smarty_tpl->tpl_vars['scroll_to'] = new Smarty_variable(waRequest::get('scroll_to'), null, 0);?>
                            if (<?php echo json_encode(in_array($_smarty_tpl->tpl_vars['scroll_to']->value,array('redirects','custom-texts')));?>
) {
                                localStorage.setItem('site/settings/<?php echo $_smarty_tpl->tpl_vars['scroll_to']->value;?>
-expanded', 1);
                            }

                            const sections = {
                                'site/settings/custom-texts-expanded': '.js-custom-text-value',
                                'site/settings/redirects-expanded': '.js-redirect-value',
                                'site/settings/apps-expanded': '.js-app-value',
                            };
                            for (const key in sections) {
                                if (localStorage.getItem(key) == 1) {
                                    $(sections[key])
                                        .show()
                                        .closest('.s-field-group').find('.js-toggle-icon').addClass('fa-caret-up').removeClass('fa-caret-down');
                                }
                            }
                        })(jQuery);
                    </script>
                <?php }?>

                </div>
                <div class="s-footer-field flexbox middle bottombar sticky full-width custom-mt-20">
                    <div class="value save_button">
                        <input type="submit" name="save" class="button" value="Сохранить">
                        <span id="s-settings-form-status" style="display:none">Сохранено</span>
                    </div>
                    <div>
                        <a href="javascript:void(0)" class="js-delete button nobutton red "><i class="fas fa-trash-alt custom-pr-4"></i>Удалить сайт</a>
                    </div>
                </div>
                <iframe style="display:none" name="s-settings-iframe" id="s-settings-iframe"></iframe>
                </form>
        </div>
    </div>
</div>

<?php $_smarty_tpl->tpl_vars['_domains'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['domains']->value)===null||$tmp==='' ? array() : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_domains_count'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['_domains']->value), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_latter_apps_names'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['latter_apps_names']->value)===null||$tmp==='' ? array() : $tmp), null, 0);?>
<?php $_smarty_tpl->tpl_vars['_latter_apps_names_count'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['_latter_apps_names']->value), null, 0);?>

<?php $_smarty_tpl->tpl_vars['single_site'] = new Smarty_variable(json_encode(($_smarty_tpl->tpl_vars['_domains_count']->value===1)), null, 0);?>,
<?php $_smarty_tpl->tpl_vars['delete_content'] = new Smarty_variable('Будет удален весь сайт со всеми страницами без возможности восстановления. Удалить?', null, 0);?>

<?php if (($_smarty_tpl->tpl_vars['single_site']->value)){?>
<?php $_smarty_tpl->tpl_vars['delete_content'] = new Smarty_variable('Вместе с сайтом будут удалены правила маршрутизации для установленных приложений. Это ограничит их функциональность. Удалить сайт?', null, 0);?>
<?php }?>
<?php if (!empty($_smarty_tpl->tpl_vars['latter_apps_names']->value)){?>
<?php $_smarty_tpl->tpl_vars['delete_content'] = new Smarty_variable(sprintf(_w("Deleting this site will also delete routing rules set up for <b>%s</b> app. This will limit its functionality.</p><p>Upon site deletion, set up routing rules for this app in the structure settings of remaining sites to continue using all functions.","Deleting this site will also delete routing rules set up for <b>%s</b> apps. This will limit their functionality.</p><p>Upon site deletion, set up routing rules for these apps in the structure settings of remaining sites to continue using all functions.",$_smarty_tpl->tpl_vars['_latter_apps_names_count']->value,false),smarty_modifier_join($_smarty_tpl->tpl_vars['_latter_apps_names']->value,"»</b>, <b>«")), null, 0);?>
<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['domain_alias']->value)&&empty($_smarty_tpl->tpl_vars['latter_apps_names']->value)){?>
<?php $_smarty_tpl->tpl_vars['delete_content'] = new Smarty_variable('Удалить этот сайт-зеркало? Никакие данные основного сайта <strong>не&nbsp;будут</strong> удалены.', null, 0);?>
<?php }?>

<script type="text/javascript">
    SiteSettings = (function ($) {
        return class {
            constructor(options) {
                const that = this;
                // DOM
                that.$wrapper = options["$wrapper"];
                that.form = that.$wrapper.find('#s-settings-form');

                // VARS
                that.locales = options["locales"];
                that.$wa_app_url = options["$wa_app_url"];
                that.domain_id = options["domain_id"];
                that.cdn_template = options["cdn_template"];
                that.$delete_content = options["$delete_content"];
                that.redirect_after_delete = options["redirect_after_delete"];
                that.storage = that.initStorage();
                that.icon_mime_types = options["icon_mime_types"];
                that.icon_extensions = options["icon_extensions"];

                // INIT
                that.initClass(options);
            };

            initClass(options) {
                const that = this;
                // this.oldData = this.$form.serialize();
                that.initWaApps();
                that.initFavicon();
                that.initTouchicon();
                that.initCdn();
                that.initCustomTextRoutes();
                that.initRedirectRoutes();
                that.initRouteSettings();
                that.initAppSettings();
                that.initDeleteSite();
                that.initSubmitSettings();

                // handler get-params
                if (options["dialog_add_app"] || options["scroll_to"]) {
                    const content_url = options['$wa_app_url'] + 'settings/?domain_id=' + options['domain_id'];
                    history.pushState({ content_url }, null, content_url);

                    that.showAddAppDialog(options["dialog_add_app"])
                    that.scrollToSection(options["scroll_to"]);
                }
            }

            initWaApps() {
                const that = this,
                    $wrapper = that.$wrapper,
                    $section = $wrapper.find('.s-field-waapps'),
                    $table = $section.find('#s-wa-apps'),
                    $input = $section.find('input[name=wa_apps_type]'),
                    //$waapps_domain_title = $section.find('.waapps-auto-domain-title'),
                    $waapps_select = $section.find('.waapps-select'),
                    $waapps_select_input = $section.find('#waapps-select');

                $input.on('change', function () {
                    if ($waapps_select_input.is(":checked")) {
                        $waapps_select.children('div').show();
                        //$waapps_domain_title.hide();
                    } else {
                        $waapps_select.children('div').hide();
                        //$waapps_domain_title.show();
                    }
                });

                $("a#s-apps-add").click(function () {
                    $table.append('<tr><td><a style="display:inline" href="#" onclick="return false" class="custom-pr-8 text-light-gray"><i class="icon fas fa-grip-vertical sort"></i></a>' +
                                        '<span></span><input type="text" name="apps[name][]" value="" /></td>' +
                                        '<td><span></span><input type="text" name="apps[url][]" value="" /></td>' +
                                        '<td class="s-actions">' +
                                            '<a href="#" class="s-apps-edit custom-pr-16 text-light-gray" title="Редактировать"><i class="icon fas fa-pen edit"></i></a>' +
                                            '<a href="#" class="s-apps-delete text-light-gray" title="Удалить"><i class="icon fas fa-times-circle delete"></i></a>' +
                                        '</td></tr>');
                    $table.next('div').show();
                    return false;
                });

                $table.on('click', 'a.s-apps-edit', function () {
                    var tr = $(this).parents('tr');
                    tr.find('span').hide();
                    tr.find('input').show();
                    $table.next('div').show();
                    return false;
                });

                $table.on('click', 'a.s-apps-delete', function () {
                    if (confirm('Ссылка будет удалена из меню. Продолжить?')) {
                        $(this).parents('tr').remove();
                    }
                    $table.next('div').show();
                    return false;
                });

                $.site.helper.loadSortableJS().then(() => {
                    $table.find('tbody').sortable({
                        animation: 150,
                        handle: '.s-sort',
                        draggable: 'tr',
                        opacity: 0.75,
                        tolerance: 'pointer',
                        onEnd: function () {
                            $table.next('div').show();
                        }
                    });
                });
            }

            initFavicon() {
                const that = this;
                const $field_wrapper = that.$wrapper.find(".s-field-favicon"),
                    $file_loader = $field_wrapper.find("#file-upload-favicon"),
                    $file_delete = $field_wrapper.find(".file-delete");

                $file_loader.waUpload({
                    show_file_name: false
                })
                $file_loader.on('change', 'input', function(evt){
                    const $input = $(this),
                        $file_error = $field_wrapper.find(".file-error");
                    if ($file_error.length) $file_error.remove();

                    const tgt = evt.target || window.event.srcElement,
                        files = tgt.files,
                        file_ext = files[0].type;

                    if (file_ext && that.icon_mime_types.includes(file_ext)) {
                        if (FileReader && files && files.length) {
                            const fr = new FileReader();
                            fr.onload = function () {
                                $field_wrapper.find('i.favicon-icon').css('background-image', 'url(' + fr.result + ')');
                                $field_wrapper.find('.favicon-name').text(files[0].name);
                                $field_wrapper.find('.favicon-wrapper').show();
                            }
                            fr.readAsDataURL(files[0]);
                        } else {
                            console.error('FileReader Not supported');
                        }

                        if ($input.val()) {
                            $file_delete.show();
                        } else{
                            $file_delete.hide();
                        }
                    } else {
                        const msg = 'Разрешена загрузка файлов только с расширениями %s.'.replace('%s', that.icon_extensions.map(ext => ('.'+ext)).join(', '));
                        $field_wrapper.find('.value').append($('<div class="small text-red file-error" />').text(msg));
                        $input.val('');
                        $field_wrapper.find('.favicon-wrapper').hide();
                    }
                })
                $file_delete.on('click', function(){
                    that.form.append('<input type="hidden" name="remove_favicon" value="1">');
                    $file_loader.find('input').val('');
                    $(this).hide();
                    $field_wrapper.find('.favicon-wrapper').hide();
                })
            }

            initTouchicon() {
                const that = this;
                const $field_wrapper = that.$wrapper.find(".s-field-touchicon"),
                $file_loader = $field_wrapper.find("#file-upload-touchicon"),
                $file_delete = $field_wrapper.find(".file-delete");

                $file_loader.waUpload({
                    show_file_name: false
                })
                $file_loader.on('change', 'input', function(evt){
                    const $input = $(this),
                        $file_error = $field_wrapper.find(".file-error");
                    if ($file_error.length) $file_error.remove();

                    const tgt = evt.target || window.event.srcElement,
                        files = tgt.files,
                        file_ext = files[0].type;
                    // FileReader support
                    if (file_ext.includes('png')){
                        if (FileReader && files && files.length) {
                            const fr = new FileReader();

                            fr.onload = function () {

                                $field_wrapper.find('.touchicon-icon').attr('src', fr.result);
                                $field_wrapper.find('.touchicon-name').text(files[0].name);
                                $field_wrapper.find('.touchicon-wrapper').show();
                            }
                            fr.readAsDataURL(files[0]);
                        }
                        // Not supported
                        else {
                            console.error('FileReader Not supported');
                        }

                        if ($input.val()) {
                            $file_delete.show();
                        }
                        else{
                            $file_delete.hide();
                        }
                    }
                    else {
                        $field_wrapper.find('.value').append('<div class="small text-red file-error">Разрешена загрузка только PNG-файлов.</div>')
                        $input.val('');
                        $field_wrapper.find('.touchicon-wrapper').hide();
                    }
                })
                $file_delete.on('click', function(){
                    that.form.append('<input type="hidden" name="remove_touchicon" value="1">');
                    $file_loader.find('input').val('');
                    $(this).hide();
                    $field_wrapper.find('.touchicon-wrapper').hide();
                })
            }

            initCdn() {
                const that = this;
                const $cdn_wrapper = that.$wrapper.find('.js-cdn-wrapper'),
                $cnd_list = $cdn_wrapper.find('.js-cdn-list'),
                $add_cdn = $cdn_wrapper.find('.js-cdn-add'),
                cdn_template = that.cdn_template;

                switchCdnRemoveIcon();

                $add_cdn.on('click', function (e) {
                    e.preventDefault();
                    addCdnInput();
                });

                $cdn_wrapper.on('click', '.js-cdn-remove', function (e) {
                    var $item = $(this).parent('.js-cdn');
                    removeCdnInput($item);
                });

                function switchCdnRemoveIcon() {
                    var $items = $cdn_wrapper.find('.js-cdn');

                    if ($items.length > 1) {
                        $items.each(function (i, item) {
                            var $item = $(item);
                            $item.find('.js-cdn-remove').show();
                        });
                    } else {
                        $items.each(function (i, item) {
                            var $item = $(item);
                            $item.find('.js-cdn-remove').hide();
                        });
                    }
                }

                function addCdnInput() {
                    var $item = $(cdn_template);
                    $cnd_list.append($item);
                    switchCdnRemoveIcon();
                }

                function removeCdnInput($item) {
                    $item.remove();
                    switchCdnRemoveIcon();
                }
            }

            initRedirectRoutes() {
                const that = this,
                    $wrapper = that.$wrapper,
                    $switchers = $wrapper.find('.js-s-disable-route'),
                    xhr = null,
                    url = that.$wa_app_url + '?module=configure&action=saveroutes&domain_id=' + that.domain_id;

                $switchers.each(function () {
                    const $switch_wrapper = $(this),
                        $item = $switch_wrapper.closest('tr'),
                        id = $item.data('id'),
                        $loading = $item.find('.s-loading'),
                        $switch = $switch_wrapper.find("#switch-redirect-" + id);
                        //is_disabled = $item.hasClass('c-is-disabled'),
                        $switch.waSwitch({
                            ready: function (wa_switch) {
                            },
                            change: function(active, wa_switch) {
                                //$loading.show();
                                //wa_switch.disable(true);
                                const data = {
                                    id: id,
                                    disabled: active ? 0 : 1
                                }
                            }
                        });
                });
            };

            initCustomTextRoutes() {
                const that = this,
                $section = that.$wrapper.find('.s-field-custom-text'),
                $table_wrapper =  $section.find('.js-custom-text-value'),
                $table = $section.find('#s-custom-text-routes');

                $table.on('click', '.js-route-settings', that.openAppDialog());
                $section.on('click', '.js-custom-text-add', that.openAppDialog());

                $section.on('click', '.js-custom-text-toggle', function(e, data){
                    data = data || {};
                    that.storage.setExpandCustomTexts($table_wrapper.is(':hidden'));
                    $table_wrapper.slideToggle(data.no_animation ? 0 : 350);
                    $(this).find('.js-toggle-icon').toggleClass('fa-caret-down fa-caret-up');
                });
            }

            initAppSettings() {
                const that = this,
                $section = that.$wrapper.find('.s-field-app'),
                $table_wrapper =  $section.find('.js-app-value'),
                $dropdown_add =  $section.find('#js-app-dropdown-add'),
                $table = $section.find('#s-apps');

                $table.on('click', '.js-app-settings', that.openAppDialog());

                $section.on('click', '.js-app-toggle', function(e, data){
                    data = data || {};
                    that.storage.setExpandApps($table_wrapper.is(':hidden'));
                    $table_wrapper.slideToggle(data.no_animation ? 0 : 350);
                    $(this).find('.js-toggle-icon').toggleClass('fa-caret-down fa-caret-up');
                });

                $dropdown_add.waDropdown({
                    hover: false,
                    ready: function(dropdown) {
                            const $menu = dropdown.$wrapper.find('.menu');
                            $menu.on('click', '.js-add-app', function(e){
                                let closed = false;
                                let dialog;
                                const data = {
                                    domain_id: that.domain_id,
                                    app: $(this).data('app-id'),
                                }

                                $.post(that.$wa_app_url+'?module=configure&action=sectionDialog&domain_id=' + that.domain_id, data, function(html) {
                                    if (closed) {
                                        return;
                                    }
                                    dialog = $.waDialog({
                                        html: html
                                    });
                                });
                                return false;
                            })
                        }
                });
            }

            openAppDialog() {
                const that = this;

                return function() {
                    const $item = $(this),
                    $tr = $item.closest('tr'),
                    item_id = $tr.data('route-id');

                    const href = that.$wa_app_url + "?module=configure&action=sectionDialog";
                    const data = {
                        ...(item_id !== undefined ? { route: item_id }: {}),
                        ...($item.data('app-id') !== undefined ? { app: $item.data('app-id') }: {}),
                        domain_id: that.domain_id,
                    };

                    $.post(href, data, function (html) {
                        $.waDialog({
                            html: html,
                        })
                    })
                }
            }

            initRouteSettings() {
                const that = this,
                $section = that.$wrapper.find('.s-field-redirect'),
                $table_wrapper =  $section.find('.js-redirect-value'),
                $table = $section.find('#s-routes');

                $table.on('click', '.js-route-settings', openDialog);

                $section.on('click', '.js-redirect-add', openDialog);

                $section.on('click', '.js-redirect-toggle', function(e, data){
                    data = data || {};
                    that.storage.setExpandRedirects($table_wrapper.is(':hidden'));
                    $table_wrapper.slideToggle(data.no_animation ? 0 : 350);
                    $(this).find('.js-toggle-icon').toggleClass('fa-caret-down fa-caret-up');
                });

                function openDialog() {
                    const $item = $(this),
                        $tr = $item.closest('tr'),
                        item_id = $tr.data('id');

                    const href = that.$wa_app_url + "?module=configure&action=redirectDialog";
                    const data = {
                        'route': item_id,
                        'domain_id': that.domain_id
                    };
                    $.get(href, data, function (html) {
                        $.waDialog({
                            html: html,
                        })
                    })
                }
            }

            initDeleteSite() {
                const that = this,
                $section = that.$wrapper.find('.s-footer-field'),
                delete_url = '?module=configure&action=delete';

                $section.on('click', '.js-delete', function () {

                $.waDialog.confirm({
                    title: that.locales["delete_confirm_title"],
                    text: that.locales["delete_confirm_text"],
                    success_button_title: that.locales["delete_confirm_button"],
                    success_button_class: 'danger',
                    cancel_button_title: that.locales["delete_cancel_button"],
                    cancel_button_class: 'light-gray',
                    onSuccess: deleteSite
                });

                function deleteSite() {
                    console.log('Delete site id =', that.domain_id);

                    $.post(delete_url, "domain_id=" + that.domain_id, function (response) {
                        if (response.status === 'ok') {
                            if (that.redirect_after_delete) {
                                location.href = that.redirect_after_delete;
                            }
                        }
                    }, "json");
                }

                return false;
                });
            }

            initSubmitSettings(){
                const that = this,
                $form = that.form,
                save_url = '?module=configure&action=save&domain_id=' + that.domain_id,
                $iframe = that.$wrapper.find("#s-settings-iframe"),
                $form_status = that.$wrapper.find("#s-settings-form-status");
                const wa_loading = $.waLoading();

                $form.on("submit", function (e) {
                    $form_status.html('<span class="icon size-16 loading"><i class="fas fa-spinner fa-spin"></i></span>').css('color', 'var(--text-color)').show();
                    wa_loading.show();
                    wa_loading.animate(4000, 99, false);

                    $iframe.one('load', function () {
                        var response = $.parseJSON($(this).contents().find('body').html());
                        if (response.status == 'ok') {
                            $form_status.html('<span class="icon size-16 loading nowrap"><i class="fas fa-check-circle"></i> Сохранено</span>').css('color', 'var(--green)').show();
                            $form_status.fadeOut(3000);
                            location.reload();
                        } else {
                            const alert_text = response.errors ? response.errors : response;
                            $.waDialog.alert({
                                title: '<i class="fas fa-exclamation-triangle text-red"></i>',
                                text: '<span class="text-red">' + alert_text + '</span>',
                                button_title: that.locales["alert_button"],
                                button_class: 'warning',
                            });
                            $form_status.hide();
                            //$form_status.html(response.errors ? response.errors : response).css('color', 'red');
                            wa_loading.abort();
                            //$form_status.fadeIn("slow");
                        }
                    });
                });
            }

            showAddAppDialog(app_id) {
                if (!app_id) return;
                const $app_toggler = this.$wrapper.find('.js-app-toggle').trigger('click');
                setTimeout(() => this.$wrapper.find('#js-app-dropdown-add').get(0).scrollIntoView(), 350);
                this.$wrapper.find(`.js-add-app[data-app-id="${ app_id }"]`).trigger('click');
            }

            scrollToSection(section) {
                if (!section) return;

                section = section.slice(0, -1);
                const offset_top = ($('#wa-nav').height() || 0) + ($('.s-tabs').height() || 0);
                const $section = $('.s-field-'+section);

                if ($section.length) {
                    const top = $section.offset().top - offset_top;
                    window.scroll(0, top);
                }
            }

            initStorage() {
                const custom_texts_expanded_key = 'site/settings/custom-texts-expanded';
                const redirects_expanded_key = 'site/settings/redirects-expanded';
                const apps_expanded_key = 'site/settings/apps-expanded';

                return {
                    isCustomTextsExpanded: () => localStorage.getItem(custom_texts_expanded_key) == 1,
                    isRedirectsExpanded: () => localStorage.getItem(redirects_expanded_key) == 1,
                    isAppsExpanded: () => localStorage.getItem(apps_expanded_key) == 1,
                    setExpandCustomTexts: (is_expand = false) => {
                        if (is_expand) {
                            localStorage.setItem(custom_texts_expanded_key, 1);
                        } else {
                            localStorage.removeItem(custom_texts_expanded_key);
                        }
                    },
                    setExpandRedirects: (is_expand = false) => {
                        if (is_expand) {
                            localStorage.setItem(redirects_expanded_key, 1);
                        } else {
                            localStorage.removeItem(redirects_expanded_key);
                        }
                   },
                   setExpandApps: (is_expand = false) => {
                        if (is_expand) {
                            localStorage.setItem(apps_expanded_key, 1);
                        } else {
                            localStorage.removeItem(apps_expanded_key);
                        }
                   }
                }
            }
        }
    })(jQuery);

    $(function() {
        document.title = 'Настройка сайта — ' + <?php echo json_encode($_smarty_tpl->tpl_vars['domain_idn']->value);?>
;
        new SiteSettings({
            $wrapper: $("#s-settings-page"),
            domain_id: '<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
',
            $wa_app_url: '<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
',
            cdn_template: <?php ob_start();?><?php smarty_template_function__renderCdnItem($_smarty_tpl,array());?>
<?php echo json_encode(ob_get_clean())?>,
            redirect_after_delete: <?php echo json_encode($_smarty_tpl->tpl_vars['wa_app_url']->value);?>
,
            locales: {
                delete_confirm_title: 'Удалить сайт',
                delete_confirm_text: '<?php echo $_smarty_tpl->tpl_vars['delete_content']->value;?>
',
                delete_confirm_button: "Удалить",
                delete_cancel_button: "Отмена",
                alert_title: "Сообщение об ошибке",
                alert_button: "Закрыть",
            },
            dialog_add_app: <?php echo json_encode(waRequest::get('dialog_add_app'));?>
,
            scroll_to: <?php echo json_encode(waRequest::get('scroll_to'));?>
,
            icon_extensions: ['icon', 'png'],
            icon_mime_types: ['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/jpeg', 'image/webp', 'image/gif'],
        });
    });

    (function($) {
        const storage_key  = 'site/settings/scroll-pos';
        const scroll_pos = sessionStorage.getItem(storage_key);
        if (scroll_pos) {
            window.scrollTo(0, scroll_pos);
            sessionStorage.removeItem(storage_key);
        }

        $.site.reloadWithScrollTo = function () {
            $('body').removeClass('is-locked');
            sessionStorage.setItem(storage_key, window.scrollY);
            this.reload();
        }
    })(jQuery);
</script>
<?php }} ?><?php /* Smarty version Smarty-3.1.14, created on 2026-08-21 18:46:22
         compiled from "/var/www/pharmab2b/httpdocs/wa-apps/site/templates/actions/backend/includes/domain_tabs.html" */ ?>
<?php if ($_valid && !is_callable('content_6a889cfe76d426_20383578')) {function content_6a889cfe76d426_20383578($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/pharmab2b/httpdocs/wa-system/vendors/smarty3/plugins/modifier.replace.php';
?><?php if (!isset($_smarty_tpl->tpl_vars['selected']->value)){?><?php $_smarty_tpl->tpl_vars['selected'] = new Smarty_variable('sitemap', null, 0);?><?php }?>
<?php $_smarty_tpl->tpl_vars['is_alias'] = new Smarty_variable(ifset($_smarty_tpl->tpl_vars['domain']->value['is_alias'],null), null, 0);?>
<?php $_smarty_tpl->tpl_vars['is_premium'] = new Smarty_variable(waLicensing::check('site')->isPremium(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['tabs'] = new Smarty_variable(array('sitemap'=>array('id'=>'sitemap','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."map/overview/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value),'name'=>_w('Site map'),'icon'=>'sitemap'),'settings'=>array('id'=>'settings','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."settings/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value),'name'=>_w('Settings'),'icon'=>'cog'),'design'=>array('id'=>'design','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."themes/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value)."#/themes/",'name'=>_w('Design themes'),'icon'=>'palette'),'plugins'=>array('id'=>'plugins','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."plugins/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value)."#/plugins/",'name'=>_w('Plugins'),'icon'=>'plug'),'files'=>array('id'=>'files','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."files/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value),'name'=>_w('Files'),'icon'=>'folder-open'),'variables'=>array('id'=>'variables','url'=>((string)$_smarty_tpl->tpl_vars['wa_app_url']->value)."variables/?domain_id=".((string)$_smarty_tpl->tpl_vars['domain_id']->value),'name'=>'Переменные','icon'=>'dollar-sign')), null, 0);?>

<div class="s-site-header blank<?php if (!empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?> custom-p-16 custom-pb-0<?php }?>">
    <ul class="breadcrumbs custom-pb-8 ">
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
?list">Мои сайты</a></li>
        <li class="js-site-breadcrumb hidden">
            <a href="<?php echo $_smarty_tpl->tpl_vars['wa_app_url']->value;?>
map/overview/?domain_id=<?php echo $_smarty_tpl->tpl_vars['domain_id']->value;?>
"><?php echo smarty_modifier_replace(waIdna::dec(htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain']->value['title'], ENT_QUOTES, 'UTF-8', true)),'www.','');?>
</a>
        </li>
        <?php if (isset($_smarty_tpl->tpl_vars['tabs']->value[$_smarty_tpl->tpl_vars['selected']->value])){?>
            <li class="js-site-breadcrumb hidden">
                <a href="<?php echo $_smarty_tpl->tpl_vars['tabs']->value[$_smarty_tpl->tpl_vars['selected']->value]['url'];?>
" class="js-disable-router"><?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['tabs']->value[$_smarty_tpl->tpl_vars['selected']->value]['name'], ENT_QUOTES, 'UTF-8', true);?>
</a>
            </li>
        <?php }?>
    </ul>

    <div class="js-site-tabs-with-domain s-site-tabs custom-mb-<?php if (!empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?>8<?php }else{ ?>32<?php }?>">
        <h3 class="custom-my-0 site-domain-header">
            <span class="break-word"><?php echo smarty_modifier_replace(waIdna::dec(htmlspecialchars((string)$_smarty_tpl->tpl_vars['domain']->value['title'], ENT_QUOTES, 'UTF-8', true)),'www.','');?>
</span>
            <a href="//<?php echo $_smarty_tpl->tpl_vars['domain']->value['name'];?>
" target="_blank" class="smallest button circle light-gray" title="Посмотреть">
                <i class="icon fas fa-external-link-alt"></i>
            </a>
            <a href="javascript:void(0)" class="smallest button circle light-gray js-duplicate-site-button" title="Копирование сайта">
                <i class="icon far fa-clone"></i>
            </a>
        </h3>

        <div class="flexbox middle">
            <ul class="s-tabs tabs wide nowrap overflow-dropdown blank custom-pt-8 <?php if (empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?>custom-px-16<?php }else{ ?>custom-pl-0<?php }?>"<?php if (empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?> style="margin: 0 -1.25rem;"<?php }?>>
                <?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['t']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
$_smarty_tpl->tpl_vars['t']->_loop = true;
?>
                    <?php $_smarty_tpl->tpl_vars['disabled'] = new Smarty_variable($_smarty_tpl->tpl_vars['is_alias']->value&&($_smarty_tpl->tpl_vars['t']->value['id']==='sitemap'||$_smarty_tpl->tpl_vars['t']->value['id']==='design'), null, 0);?>
                    <li class="<?php if ($_smarty_tpl->tpl_vars['selected']->value==$_smarty_tpl->tpl_vars['t']->value['id']){?>selected<?php }?> <?php if ($_smarty_tpl->tpl_vars['disabled']->value){?>disabled<?php }?>" <?php if ($_smarty_tpl->tpl_vars['disabled']->value){?>title="Раздел не доступен для зеркала сайта"<?php }?>>
                        <a href="<?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['t']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
">
                            <i class="icon small fas fa-<?php echo $_smarty_tpl->tpl_vars['t']->value['icon'];?>
"></i>
                            <?php echo htmlspecialchars((string)$_smarty_tpl->tpl_vars['t']->value['name'], ENT_QUOTES, 'UTF-8', true);?>

                        </a>
                    </li>
                <?php } ?>
            </ul>
            <?php if (!$_smarty_tpl->tpl_vars['is_premium']->value){?>
                <div class="s-premium-link-wrapper s-tabs nowrap">
                    <a href="javascript:void(0)" id="js-premium-section" class="semibold text-purple"><i class="fas fa-crown text-purple"></i> Премиум</a>
                </div>
            <?php }?>
        </div>
    </div>
    <script>
        ( function($) {
            const $wrapper = $(".s-site-header");
            const domain_id = <?php echo json_encode($_smarty_tpl->tpl_vars['domain_id']->value);?>
;

            if (navigator.platform.indexOf('Mac') > -1) {
                setTimeout(() => {
                    $(".tabs", $wrapper).waTabs();
                });
            } else {
                $(".tabs", $wrapper).waTabs();
            }

            <?php if (empty($_smarty_tpl->tpl_vars['sidebar_mode']->value)){?>
            $(function() {
                setTimeout(() => {
                    $('.tabs').resize();
                });
            });
            <?php }?>

            $.site.breadcrumbs = new class {
                constructor () {
                    this.events = {};
                }
                toggleMode(all_links) {
                    $('.js-site-tabs-with-domain', $wrapper).toggleClass('hidden', all_links);
                    $('.js-site-breadcrumb', $wrapper).toggleClass('hidden', !all_links);
                }
                callEvent(event_name) {
                    if (!event_name || !this.events[event_name]) {
                        return;
                    }
                    this.events[event_name].forEach(fn => fn.call(null));
                }
                showRoot() {
                    this.toggleMode(false);
                    this.callEvent('click_parent');
                    $(".tabs", $wrapper).trigger('resize');
                }
                showAll() {
                    this.toggleMode(true);
                    this.callEvent('click_child');
                }
                on(event_name, callback) {
                    if (callback && ['click_parent', 'click_child'].includes(event_name)) {
                        if (!this.events[event_name]) {
                            this.events[event_name] = [];
                        }
                        this.events[event_name].push(callback);
                    }
                }
            };

            $('.js-site-breadcrumb', $wrapper).on('click', function () {
                $.site.breadcrumbs.showRoot();
            });

            $('#js-premium-section').on('click', function () {
                $.site.helper.showPremiumDialog();
            });

            $wrapper.on('click', '.js-duplicate-site-button', function() {
                <?php if ($_smarty_tpl->tpl_vars['is_premium']->value){?>
                $.post('?module=domains&action=duplicateDialog', { domain_id }, function(html) {
                    if (html) {
                        $.waDialog({ html });
                    }
                });
                <?php }else{ ?>
                $.site.helper.showPremiumDialog();
                <?php }?>
                return false;
            });

        })(jQuery);
    </script>
</div>
<?php }} ?>