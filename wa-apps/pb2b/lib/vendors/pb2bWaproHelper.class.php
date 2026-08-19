<?php

class pb2bWaproHelper
{
    /**
     * @param ?string $log_name
     * @param array $records
     * @return void
     */
    static public function logRecord(?string $log_name = null, array $records = array()): void
    {
        if (waSystemConfig::isDebug()) {
            if (empty($log_name)) {
                $log_name = 'error';
            }
            $stack = debug_backtrace();
            foreach ($stack as $d) {
                $records[] = $d['file'].' '.$d['class'].$d['type'].$d['function'].'() '.$d['line'];
            }
            waLog::log(implode(PHP_EOL, $records), $log_name.'.log');
        }
    }

    /**
     * @param $name
     * @return string
     */
    static public function getMethodByName($name): string
    {
        $result = '';
        $name = explode('_', $name);
        foreach ($name as $v) {
            if (empty($result)) {
                $result = $v;
            } else {
                $result .= ucfirst($v);
            }
        }
        return $result;
    }

    static public function getAddressPerson(): string
    {
        $addresses = array('котик', 'зайка', 'лапонька', 'солнышко');
        return $addresses[rand(0, count($addresses) - 1)];
    }

    /**
     * @param string $mode
     * @return array
     * @throws waException
     */
    static public function setSidebarMode(string $mode): array
    {
        $contact_id = wa(self::getAppId())->getUser()->getId();
        if ($contact_id) {
            $user_id = wa(self::getAppId())->getUser()->getId();
            $settings_model = new waContactSettingsModel();
            $settings_model->set($user_id, self::getAppId(), 'backend_sidebar_mode', $mode);
        }
        return array('error' => false, 'message' => 'Режим установлен');
    }

    /**
     * @return mixed|string
     * @throws waException
     */
    static public function getSidebarMode(): mixed
    {
        $contact_id = wa(self::getAppId())->getUser()->getId();
        $settings_model = new waContactSettingsModel();
        $settings = $settings_model->get($contact_id, self::getAppId());
        if (empty($settings['backend_sidebar_mode'])) {
            $result = 'dashboard';
        } else {
            $result = $settings['backend_sidebar_mode'];
        }
        return $result;
    }

    /**
     * @param object $class
     * @param string $replace
     * @return string
     * @throws waException
     */
    static public function getClassName(object $class, string $replace = ''): string
    {
        $class_name = get_class($class);
        if (!empty($replace)) {
            $class_name = str_replace($replace, '', $class_name);
        }
        return lcfirst(str_replace(self::getAppId(), '', $class_name));
    }

    /**
     * @return mixed|string
     * @throws waException
     */
    static public function getAppId(): mixed
    {
        $app_info = waSystem::getInstance()->getAppInfo();
        return $app_info['id'] ?? '';
    }

    /**
     * Suggests a URL part generated from specified string.
     *
     * @param string $str Specified string
     * @param boolean $strict Whether a default value must be generated if provided string results in an empty URL
     * @return string
     * @throws waException
     */
    public static function transliterate(string $str, bool $strict = true): string
    {
        $str = preg_replace('/\s+/u', '-', $str);
        if ($str) {
            foreach (waLocale::getAll() as $lang) {
                $str = waLocale::transliterate($str, $lang);
            }
        }
        $str = trim(preg_replace('/[^a-zA-Z0-9_]+/', '-', $str), '-');
        if ($strict && !strlen($str)) {
            $str = date('Ymd');
        }

        return strtolower($str);
    }

    /**
     * @param array|string|null $name
     * @param string|null $field
     * @return array|null
     * @throws waException
     */
    static public function getConfigOption(array|string $name = null, string $field = null): mixed
    {
        $result = null;
        if (empty($name)) {
            $result = wa()->getConfig()->getOption();
        } else {
            if (is_array($name)) {
                $config = wa()->getConfig()->getOption();
                foreach ($name as $code) {
                    if (isset($config[$code])) {
                        $result[$code] = $config[$code];
                        self::keyReplace($result[$code], $field);
                    }
                }
            } else {
                $result = wa()->getConfig()->getOption($name);
                self::keyReplace($result, $field);
            }
        }
        return $result;
    }

    /**
     * @param $result
     * @param $field
     */
    static private function keyReplace(&$result, $field): void
    {
        if (isset($field)) {
            $temp = array();
            foreach ($result as $r) {
                if (isset($r[$field])) {
                    $temp[$r[$field]] = $r;
                } else {
                    break;
                }
            }
            $result = $temp;
        }
    }

    /**
     * @param $old
     * @param $new
     * @return bool
     */
    static public function arrayChangeCheck($old, $new): bool
    {
        $changed = false;
        foreach ($new as $field => $value) {
            if (!isset($old[$field]) || $old[$field] != $value) {
                $changed = true;
                break;
            }
        }
        return $changed;
    }

    /**
     * @param string $result
     * @param $check_fields
     * @param $data
     * @param $rows
     * @param string $row_start
     */
    static public function repeatCheck(string &$result, $check_fields, $data, $rows, string $row_start = ''): void
    {
        foreach ($rows as $row) {
            $row_string = '';
            foreach ($check_fields as $field => $field_data) {
                if (isset($field_data['unique']) && $row[$field] == $data[$field]) {
                    if (empty($row_string)) {
                        $row_string .= $row_start;
                        if (isset($row['name'])) {
                            $row_string .= '"'.htmlspecialchars($row['name'], ENT_QUOTES).'" ';
                        }
                    } else {
                        $row_string .= ', ';
                    }
                    $row_string .= $field_data['unique'];
                }
            }
            $result .= $row_string.'.</br>';
        }
    }

    /**
     * @param null $type
     * @return false|mixed
     * @throws waException
     */
    static public function getObjectConfig($type = null): mixed
    {
        $result = self::getConfigOption('objects');
        if (isset($type)) {
            $result = $result[$type] ?? false;
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * @param null $type
     * @return mixed|null
     * @throws waException
     */
    static public function getFields($type = null): mixed
    {
        $fields = self::getObjectConfig($type);
        return $fields ? (empty($fields['fields']) ? false : $fields['fields']) : false;
    }

    /**
     * @param array $data
     * @param array|string $fields
     * @return array|false[]
     * @throws waException
     */
    static public function validate(array &$data, array|string $fields): array
    {
        $result = array('error' => false);
        if (!is_array($fields)) {
            $fields = self::getFields($fields);
        }
        $errors = array();
        if (empty($data)) {
            $result = array('error' => true, 'message' => 'Ошибка получения данных');
        } else {
            if ($fields) {
                foreach ($fields as $code => $field) {
                    if (!empty($field)) {
                        if (empty($data[$code]) && !empty($field['required'])) {
                            $errors[] = 'поле "'.$field['name'].'" должно быть заполнено';
                        } else {
                            if (isset($data[$code]) || $field['type'] == 'checkbox') {
                                self::fieldValidation($field, $errors, $data[$code]);
                            }
                        }
                    }
                }
            }
        }
        if (!empty($errors)) {
            $result = array('error' => true, 'message' => self::messageMerge($errors));
        }
        return $result;
    }

    /**
     * @param $model
     * @return pb2bWaproModel|null
     * @throws waException
     */
    static public function getModel($model): ?pb2bWaproModel
    {
        $result = null;
        $model = waSystem::getInstance()->getAppInfo()['id'].ucfirst($model).'Model';
        if (class_exists($model)) {
            $model = new $model();
            if ($model instanceof pb2bWaproModel) {
                $result = $model;
            }
        }
        return $result;
    }

    /**
     * @param $field
     * @param $value
     * @param $type
     * @return array|false[]
     * @throws waException
     */
    static public function validateField($field, $value, $type): array
    {
        $result = array('error' => false);
        $fields = self::getFields($type);
        $errors = array();
        if (isset($fields[$field])) {
            $field = $fields[$field];
            if (empty($value) && strlen($value) === 0 && empty($field['nullable'])) {
                $errors[] = 'поле "'.$field['name'].'" должно быть заполнено';
            } else {
                self::fieldValidation($field, $errors, $value);
            }
        }
        if (!empty($errors)) {
            $result = array('error' => true, 'message' => self::messageMerge($errors));
        }
        return $result;
    }

    /**
     * @param mixed $raw
     * @return array{0: bool, 1: ?string} [ok, mysql Y-m-d H:i:s|null]
     */
    public static function normalizeDatetimeValueForMysql($raw): array
    {
        if ($raw === null) {
            return array(true, null);
        }
        if (!is_scalar($raw)) {
            return array(false, null);
        }
        $v = trim((string) $raw);
        if ($v === '') {
            return array(false, null);
        }
        foreach (array('!Y-m-d\TH:i:s', '!Y-m-d\TH:i', '!Y-m-d H:i:s', '!Y-m-d H:i', '!Y-m-d') as $fmt) {
            $dt = DateTimeImmutable::createFromFormat($fmt, $v);
            if ($dt instanceof DateTimeImmutable) {
                $le = DateTimeImmutable::getLastErrors();
                if (empty($le['warning_count']) && empty($le['error_count'])) {
                    return array(true, $dt->format('Y-m-d H:i:s'));
                }
            }
        }

        return array(false, null);
    }

    /**
     * @param mixed $mysql
     */
    public static function formatMysqlDatetimeForDatetimeLocal($mysql): string
    {
        if ($mysql === null || $mysql === '' || (is_string($mysql) && trim($mysql) === '')) {
            return '';
        }
        if (!is_scalar($mysql)) {
            return '';
        }
        $v = trim((string) $mysql);
        foreach (array('!Y-m-d H:i:s', '!Y-m-d H:i', '!Y-m-d') as $fmt) {
            $dt = DateTimeImmutable::createFromFormat($fmt, $v);
            if ($dt instanceof DateTimeImmutable) {
                $le = DateTimeImmutable::getLastErrors();
                if (empty($le['warning_count']) && empty($le['error_count'])) {
                    return $dt->format('Y-m-d\TH:i:s');
                }
            }
        }

        return '';
    }

    /**
     * @param $field
     * @param $errors
     * @param $value
     * @throws waException
     */
    static public function fieldValidation($field, &$errors, &$value): void
    {
        if(isset($value) && is_string($value) && $value === '' && !empty($field['nullable'])) {
            $value = null;
            return;
        }
        if (isset($value)) {
            if (isset($field['max']) && $value > $field['max']) {
                $errors[] = 'значения поля "'.$field['name'].'" не должно быть больше '.$field['max'];
            }
            if (isset($field['min']) && $value < $field['min']) {
                $errors[] = 'значения поля "'.$field['name'].'" не должно быть меньше '.$field['min'];
            }
            switch ($field['type']) {
                case 'string':
                case 'varchar':
                case 'text':
                    if (isset($field['regexp'])) {
                        if (preg_match($field['regexp'], $value)) {
                            $errors[] = 'в поле "'.$field['name'].'" присутствуют недопустимые символы';
                        }
                    }
                    if (isset($field['max_length']) && mb_strlen($value) > $field['max_length']) {
                        $errors[] = 'поле "'.$field['name'].'" не должно быть длиннее '.$field['max_length'];
                    }
                    if (isset($field['min_length']) && mb_strlen($value) < $field['min_length']) {
                        $errors[] = 'поле "'.$field['name'].'" не должно быть короче '.$field['min_length'];
                    }
                    break;
                case 'decimal':
                    if (!is_numeric($value)) {
                        $errors[] = 'поле "'.$field['name'].'" должно быть числом';
                    }
                    break;
                case 'int':
                    if (!(is_numeric($value)&&(int)$value==$value)) {
                        $errors[] = 'поле "'.$field['name'].'" должно быть целочисленным';
                    }
                    break;
                case 'config':
                    if (empty($field['code'])) {
                        $errors[] = 'ошибка получения конфигурационных данных для поля "'.$field['name'].'"';
                    } else {
                        if (isset($field['config'])) {
                            $config = self::getConfigOption($field['config']);
                        } else {
                            $config = self::getConfigOption($field['code']);
                        }
                        if (empty($config[$value])) {
                            if (empty($field['nullable']) || $value != -1) {
                                $check = true;
                                foreach ($config as $config_val) {
                                    if (isset($config_val[$value])) {
                                        $check = false;
                                    }
                                }
                                if ($check) {
                                    $errors[] = 'ошибка получения значения конфигурационных данных для поля "'.$field['name'].'"';
                                }
                            }
                        }
                    }
                    break;
                case 'checkbox':
                    if (!in_array($value, array(0,1))) {
                        $value = 1;
                    }
                    break;
                case 'email':
                    $v = new waEmailValidator();
                    if (!$v->isValid($value)) {
                        $errors[] = 'неверный формат поля "'.$field['name'].'"';
                    }
                    break;
                case 'phone':
                    $v = new waPhoneNumberValidator();
                    if (!$v->isValid($value)) {
                        $errors[] = 'неверный формат поля "'.$field['name'].'"';
                    }
                    break;
                case 'date':
                    $v = new waDateValidator();
                    if (!$v->isValid($value)) {
                        $errors[] = 'неверный формат поля "' . $field['name'] . '"';
                    }
                    break;
                case 'datetime':
                    if (is_string($value)) {
                        $value = trim($value);
                    }
                    if ($value === '' && !empty($field['nullable'])) {
                        $value = null;
                        break;
                    }
                    if ($value === '' || $value === null) {
                        $errors[] = 'неверный формат поля "' . $field['name'] . '"';
                        break;
                    }
                    $pair = self::normalizeDatetimeValueForMysql($value);
                    if (!$pair[0]) {
                        $errors[] = 'неверный формат поля "' . $field['name'] . '"';
                        break;
                    }
                    $value = $pair[1];
                    break;
                case 'time':
                    $v = new waTimeValidator();
                    if (!$v->isValid($value)) {
                        $errors[] = 'неверный формат поля "' . $field['name'] . '"';
                    }
                    break;
                default:
                    $errors[] = 'ошибка получения типа данных для поля "'.$field['name'].'"';
            }
        } else {
            if ($field['type'] == 'checkbox') {
                $value = 0;
            }
        }
    }

    /**
     * @param $svg
     * @return string
     * @throws SmartyException
     * @throws waException
     */
    static public function getSvgTemplate($svg): string
    {
        $check = wa()->getView();
        return $check->fetch(wa()->getAppPath('/img/svg/'.$svg.'.svg', self::getAppId()));
    }

    /**
     * @param array $messages
     * @return string
     */
    static public function messageMerge(array $messages): string
    {
        $result = '';
        foreach ($messages as $key => $value) {
            if (!is_string($value)) {
                unset($messages[$key]);
            }
        }
        if (!empty($messages)) {
            $result = 'Ошибка: '.trim(implode(', ', $messages));
        }
        return $result;
    }

    /**
     * @param $app
     * @throws waException
     */
    static public function dbPhpGenerate($app = null): void
    {
        $command = '';
        if (empty($app)) {
            $app = self::getAppId();
        }
        foreach (pb2bWaproModel::getTables($app) as $t) {
            $t = array_values($t)[0];
            if (!empty($t)) {
                $command .= ' '.$t;
            }
        }
        if (!empty($command)) {
            $command = $app.$command;
            if (file_exists(wa()->getAppPath('lib/config/db.php', $app))) {
                $command .= ' -update';
            }
            waLog::dump("php wa.php generateDb ".$command, 'pb2bWaproModel.log');
//            `php wa.php generateDb $command`;
        }
    }
}