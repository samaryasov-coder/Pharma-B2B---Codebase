<?php

/**
 * Class pb2bModel
 * @property $fields
 * @property $select
 * @property $join
 * @property $where
 * @property $logic
 * @property $group_by
 * @property $having
 * @property $having_logic
 * @property $order_by
 * @property $limit
 */
abstract class pb2bModel extends waModel
{
    protected array $data = array();
    protected string $fetch_type = 'all';
    protected ?string $fetch_field = null;
    protected bool|int $fetch_del = false;
    /**
     * @var string
     */
    protected string $insert_table = '', $insert_row = '', $update_row = '', $delete_row = '';
    /**
     * @var ?array
     */
    protected ?array $select = array(), $join = array(), $where = array(), $group_by = array(), $having = array(), $having_logic = array(), $order_by = array(), $join_tables = array();
    protected null|string|array $logic = 'AND';
    protected array $limit = array();
    protected ?string $check_as;
    protected int $counter = 1;
    protected int $max_allowed_packet = 1000000;
    protected array $sub_queries = array();
    protected array $expected_similes = array('in', 'not in', 'like', '=', '!=', '<', '>', '>=', '<=', '<>', 'is', 'is not', 'between', 'exists', 'not exists');
    protected array $expected_functions = array('if' => 3, 'match' => 2, 'lover' => 1, 'upper' => 1, 'left' => 2, 'right' => 2, 'space' => 1, 'locate' => 2, 'repeat' => 2, 'timestamp' => 0, 'adddate' => 2, 'curdate' => 0, 'curtime' => 0, 'date' => 1, 'day' => 1, 'hour' => 1, 'week' => 1, 'year' => 1, 'time' => 1, 'round' => 2, 'min' => 1, 'max' => 1, 'avg' => 1, 'count' => 1, 'floor' => 1, 'mod' => 2, 'ceil' => 1, 'abs' => 1, 'exp' => 1, 'pi' => 0, 'ln' => 1);
    protected array $expected_math = array('+', '-', '*', '/');
    
    protected ?string $current_directive = null;

    /**
     * pb2bModel constructor.
     * @param waModel|null $model
     * @throws waDbException
     * @throws waException
     */
    public function __construct(waModel $model = null)
    {
        parent::__construct();
        if (isset($model)) {
            $this->id = $model->getTableId();
            $this->table = $model->getTableName();
            //TODO получение актуальных полей или отказ от такого режима использования
        }
        $this->max_allowed_packet = $this->getVariables('max_allowed_packet');
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        $properties = array('fields', 'select', 'join', 'where', 'group_by', 'having', 'order_by', 'limit');
        $result = null;
        if (in_array($name, $properties)) {
            $result = $this->$name;
        }
        return $result;
    }

    /**
     * @return array database connect data
     * @throws waException
     */
    protected function getDbSettings(): array
    {
        return include wa()->getConfig()->getRootPath().'/wa-config/db.php';
    }

    /**
     * @param $query
     * @throws waException
     */
    public final function superQuery($query): void
    {
        $data_base = $this->getDbSettings();
        $mysqli = new mysqli($data_base['default']['host'], $data_base['default']['user'], $data_base['default']['password'], $data_base['default']['database']);
        $mysqli->set_charset("utf8");
        $mysqli->multi_query($query);
        do {
            continue;
        } while ($mysqli->next_result());
        $mysqli->close();
    }

    /**
     * @throws waException
     */
    public final function multiUpdate(): void
    {
        if (strlen($this->update_row) > 0) {
            $this->superQuery($this->update_row);
            $this->update_row = "";
        }
    }

    /**
     * @throws waException
     */
    public final function multiDelete(): void
    {
        if (strlen($this->delete_row) > 0) {
            $this->superQuery($this->delete_row);
            $this->delete_row = "";
        }
    }

    /**
     * @throws waDbException
     */
    public final function bulkInsert(): void
    {
        if (strlen($this->insert_row) > 0) {
            $this->query("INSERT IGNORE INTO {$this->table}{$this->insert_table} VALUES {$this->insert_row};");
        }
        $this->insert_row = '';
    }

    /**
     * @param string|null $name - variable name
     * @return array|string array is_null($name), string isset($name)
     * @throws waDbException
     */
    protected final function getVariables(string $name = null): array|string
    {
        if (empty($name)) {
            $result = $this->query("SHOW VARIABLES")->fetchAll('Variable_name', true);
        } else {
            $result = $this->query("SHOW VARIABLES LIKE s:var_name", array('var_name' => $name))->fetchAssoc();
            $result = $result['Value'];
        }
        return $result;
    }

    /**
     * @param array $insert_table = array('column',)
     */
    public final function setInsertTable(array $insert_table): void
    {
        $insert_fields = array();
        foreach ($insert_table as $col) {
            if (strlen($this->insert_table) > 0) {
                $this->insert_table .= ", ";
            }
            if ($this->fieldExists($col)) {
                $insert_fields[] = $col;
            } else {
                $this->log('Поле '.$col.' не найдено в таблице '.$this->table.', ');//TODO doc link
            }
        }
        if (!empty($insert_fields)) {
            $this->insert_table = "({".implode(', ', $insert_fields)."})";
        }
    }

    /**
     * @param array $insert_data = array('value',)
     * @throws waDbException
     */
    public final function setInsertRow(array $insert_data): void
    {
        if (strlen($this->insert_row) > $this->max_allowed_packet/4) {
            $this->bulkInsert();
        }
        $insert_row = "";
        foreach ($insert_data as $col) {
            if (strlen($insert_row) > 0) {
                $insert_row .= ", ";
            }
            if (is_null($col)) {
                $insert_row .= "NULL";
            } else {
                $insert_row .= "'{$this->escape($col)}'";
            }
        }
        if (strlen($this->insert_row) > 0) {
            $this->insert_row .= ", ";
        }
        $this->insert_row .= "({$insert_row})";
    }

    /**
     * @param array $delete = array('column' => array('simile' => '=!=<=>=', 'value' => 'val'),)
     * @throws waException
     */

    public final function setDeleteRow(array $delete): void
    {
        if (strlen($this->delete_row) > $this->max_allowed_packet/4) {
            $this->multiDelete();
        }
        $where = $this->getCondition($delete);
        if (strlen($where) > 0) {
            $this->delete_row .= "DELETE FROM {$this->getTableName()} WHERE {$where};";
        }
    }

    /**
     * @param array $update = array('data' => array('column' => 'value',), 'where' => array('column' => 'value',))
     * @throws waException
     */
    public final function setUpdateRow(array $update): void
    {
        if (strlen($this->update_row) > $this->max_allowed_packet/4) {
            $this->multiUpdate();
        }
        $update_row = $this->updateRowCreate($update);
        if (!empty($update_row)) {
            $this->update_row .= "UPDATE {$this->getTableName()} SET {$update_row};";
        }
    }

    /**
     * @param array $update = array('data' => array('column' => 'value',), 'where' => array('column' => 'value',))
     * @return string update row
     */
    public final function getUpdateRow(array $update): string
    {
        $result = $this->updateRowCreate($update);
        if (!empty($result)) {
            $result = "UPDATE {$this->getTableName()} SET {$result};";
        }
        return $result;
    }

    /**
     * @param array $update
     * @return string
     */
    protected final function updateRowCreate(array $update): string
    {
        $result = "";
        if (!empty($update['data']) && !empty($update['where'])) {
            $update_set = "";
            foreach ($update['data'] as $col => $val) {
                if (strlen($update_set) > 0) {
                    $update_set .= ", ";
                }
                $update_set .= "{$this->escape($col)} = '{$this->escape($val)}'";
            }
            $update_where = $this->getCondition($update['where']);
            if (!empty($update_where)) {
                $result = $update_set." WHERE {$update_where}";
            }
        }
        return $result;
    }

    /**
     * @param string $fetch_type - all/assoc/field
     * @param null $fetch_field - column name
     * @param bool|int $fetch_del
     */
    public final function setFetch(string $fetch_type, $fetch_field = null, bool|int $fetch_del = false): void
    {
        $this->fetch_type = $fetch_type;
        $this->fetch_field = $fetch_field;
        $this->fetch_del = $fetch_del;
    }

    /**
     * @param array $select = array('field' => 'as', 'field2' => array('as1', 'as2')),
     */
    public final function setSelect(array $select): void
    {
        $this->select = $select;
    }

    /**
     * @return string
     */
    protected final function getSelect(): string
    {
        $this->current_directive = 'select';
        $select = array();
        if (!empty($this->select)) {
            foreach ($this->select as $col => $data) {
                $as = '';
                $col = trim($col);
                if ($col === '*') {
                    if (empty($data)) {
                        $select[] = $col;
                    } else {
                        $select[] = $data.'.'.$col;
                    }
                } else {
                    if (is_numeric($col)) {
                        $col = $this->getValue($data);
                    } else {
                        $col = $this->getValue(array('field' => $col, 'table' => (is_array($data) && isset($data['table'])) ? $data['table'] : null));
                    }
                    if (!empty($data)) {
                        if (is_array($data)) {
                            if (isset($data['as'])) {
                                $as = $data['as'];
                            } else {
                                if (is_numeric(array_key_first($data))) {
                                    $as = $data;
                                }
                            }
                        } else {
                            $as = $data;
                        }
                    }
                    if (!empty($col)) {
                        if (empty($as)) {
                            $select[] = $col;
                        } else {
                            if (is_array($as)) {
                                foreach ($as as $a) {
                                    $select[] = $col.' AS '.$this->escape($a);
                                }
                            } else {
                                $select[] = $col.' AS '.$this->escape($as);
                            }
                        }
                    }
                }
            }
        }
        if (empty($select)) {
            $select = '*';
        } else {
            $select = implode(', ', $select);
        }
        $this->current_directive = null;
        return "SELECT {$select}";
    }

    /**
     * @param array $join = array(array('type' => 'LEFT','left' => 'left_table','right' => 'right_table','as' => 'AS', 'on' => array('left_field' => 'right_field',)),)
     */
    public final function setJoin(array $join): void
    {
        $this->join = $join;
    }

    protected final function getJoin(): string
    {
        $this->current_directive = 'join';
        $join = $this->table;
        if (!empty($this->join)) {
            $join_types = array('inner', 'left', 'right');
            foreach ($this->join as $table) {
                if (empty($table['type'])) {
                    $table['type'] = 'INNER';
                }
                if (!in_array(strtolower($table['type']), $join_types)) {
                    $this->log('Передан неверный тип джойна, ');//TODO doc link
                    continue;
                }
                if (isset($table['left'])) {
                    if (is_string($table['left'])) {
                        if (empty($this->join_tables[$table['left']])) {
                            $this->log('Для алиаса '.$table['left'].' не найдена модель. Вероятно, для ожидаемой таблицы ещё не выполнен джойн, проверь порядок передаваемых джойнов, и алиас (чувствителен к регистру), ');//TODO doc link
                            continue;
                        }
                    } else {
                        $this->log('Алиас для левой таблицы не в том формате, требуется строка, ');//TODO doc link
                        continue;
                    }
                }
                if (empty($table['right']) || !($table['right'] instanceof waModel)) {
                    $this->log('Дай мне эксземпляр модели, ');//TODO doc link
                    continue;
                } else {
                    $right_table = $table['right']->getTableName();
                    if ($table['query'] ?? false) {
                        if (empty($table['as'])) {
                            $this->log('При попытке джойна на подзапрос из таблицы '.$right_table.' не был указан алиас, при использовании подзапроса в директиве JOIN алиас обязателен, ');//TODO doc link
                            continue;
                        } else {
                            $table['as'] = $this->escape($table['as']);
                            $this->join_tables[$table['as']] = $table['right'];
                            if ($this->join_tables[$table['as']] instanceof pb2bModel) {
                                $this->sub_queries[$table['as']] = 1;
                                $data = $this->join_tables[$table['as']]->getSubQuery($this->counter);
                                if (empty($data['query'])) {
                                    $this->log('Что-то пошло не так в формировании подзапроса к таблице '.$right_table.', ');//TODO doc link
                                    continue;
                                } else {
                                    $this->counter = $data['counter'];
                                    $this->data += $data['data'];
                                    $right = '('.$data['query'].') AS '.$table['as'];
                                }
                            } else {
                                $this->log('Дай мне эксземпляр МОЕЙ модели, раз хочешь использовать подзапрос, ');//TODO doc link
                                continue;
                            }
                        }
                    } else {
                        if (empty($table['as'])) {
                            $table['as'] = $right_table;
                            $as = '';
                        } else {
                            $as = ' AS '.$this->escape($table['as']);
                        }
                        $this->join_tables[$table['as']] = $table['right'];
                        $right = $this->join_tables[$table['as']]->getTableName().$as;
                    }
                }
                $this->joinConditionsPrepare($table);
                $condition = $this->getCondition($table['on'], $table['logic'] ?? 'AND');
                if (!empty($condition)) {
                    $condition = " ON {$condition}";
                }
                $join .= " {$this->escape($table['type'])} JOIN {$right}{$condition}";
            }
        }
        $this->current_directive = null;
        return "FROM {$join}";
    }

    protected final function joinConditionsPrepare(array&$table): void
    {
        $as = $table['as'] ?? $table['right']->getTableName();
        foreach ($table['on'] as &$right) {
            if (is_array($right)) {
                if (empty($right['table'])) {
                    $right['table'] = $as;
                }
            } else {
                $right = array('value' => array('table' => $as, 'field' => $right));
            }
            if (isset($table['left'])) {
                $right['table'] = $table['left'];
            }
        }
    }

    /**
     * @param array $join = array(array('type' => 'LEFT','left' => 'left_table','right' => 'right_table','on' => array('left_field' => 'right_field',)),)
     */
    public final function addJoin(array $join): void
    {
        if (empty($this->join)) {
            $this->join = array();
        }
        foreach ($join as $j) {
            $this->join[] = $j;
        }
    }

    /**
     * @param array $where = array('column' => array('simile' => '!=/=/>/</>=/<=','value' => string/int/array),)
     * @param array|string $def_logic = AND/OR/array(
     *    'table.field' => 1,
     *    array(
     *      'logic' => 'AND|OR',
     *      'fields' => array(
     *          0 => array(
     *            'logic' => 'AND|OR',
     *            'fields' => array(
     *                'table.field' => 1,
     *             ),
     *          ),
     *          'table.field' => 1,
     *       ),
     *    )
     * )
     */
    public final function setWhere(array $where, array|string $def_logic = 'AND'): void
    {
        $this->where = $where;
        if (!empty($def_logic)) {
            $this->logic = $def_logic;
        }
    }


    /**
     * @param array $where = array('column' => array('simile' => '!=/=/>/</>=/<=','value' => string/int/array),)
     * @param array|string|null $logic = AND/OR/array(
     *    'table.field' => 1,
     *    array(
     *      'logic' => 'AND|OR',
     *      'fields' => array(
     *          0 => array(
     *            'logic' => 'AND|OR',
     *            'fields' => array(
     *                'table.field' => 1,
     *             ),
     *          ),
     *          'table.field' => 1,
     *       ),
     *    )
     * )
     * @param boolean $rewrite
     */
    public final function addWhere(array $where, array|string $logic = null, bool $rewrite = true): void
    {
        if ($rewrite) {
            $this->where = $where + $this->where;
            if (!empty($logic)) {
                $this->logic = $logic;
            }
        } else {
            foreach ($where as $field => $value) {
                self::setElement($this->where, $field, $value);
            }
            if (is_array($logic) && is_array($this->logic)) {
                self::mergeLogic($this->logic, $logic);
            }
        }
    }

    /**
     * @return string
     */
    protected final function getWhere(): string
    {
        $this->current_directive = 'where';
        $where = '';
        if (!empty($this->where)) {
            $where = $this->getCondition($this->where, $this->logic);
            if (strlen($where) > 0) {
                $where = 'WHERE '.$where;
            }
        }
        $this->current_directive = null;
        return $where;
    }

    protected final function getCondition(array $conditions, string|array $logic = 'AND'): string
    {
        if (is_array($logic)) {
            $where = $this->setLogic($conditions, $logic);
            if (!empty($where)) {
                $where = preg_replace(array('/^\(/', '/\)$/'), '', $where);
            }
        } else {
            $where = array();
            foreach ($conditions as $col => $val) {
                $w = $this->getWhereValue($col, $val);
                if (!empty($w)) {
                    $where[] = $w;
                }
            }
            $where = implode(' '.$logic.' ', $where);
        }
        return $where;
    }

    /**
     * @param array $conditions,
     * @param array $logic
     * @param string $def_logic
     * @return string
     */
    protected final function setLogic(array $conditions, array $logic, string $def_logic = 'AND'): string
    {
        $result = array();
        foreach ($logic as $field => $data) {
            if (isset($conditions[$field])) {
                $result[] = "{$this->getWhereValue($field, $conditions[$field])}";
            } else {
                if (is_array($data) && isset($data['fields']) && isset($data['logic'])) {
                    $result[] = $this->setLogic($conditions, $data['fields'], $data['logic']);
                }
            }
        }
        if (empty($result)) {
            $result = '';
        } else {
            $result = '('.implode(' '.$this->escape($def_logic).' ', $result).')';
        }
        return $result;
    }

    /**
     * @param mixed $value
     * @return string $type
     */
    protected final function getValType(mixed $value): string
    {
        if (is_numeric($value)) {
            if (strpos($value, '.') || strpos($value, ',')) {
                $type = 'f';
            } else {
                $type = 'i';
            }
        } else {
            $type = 's';
        }
        return $type;
    }

    /**
     * @param string $col
     * @param array $val
     * @return string
     */
    protected final function getWhereValue(string $col, array $val): string
    {
        $where = '';
        $col = trim($col);
        if (empty($val['func']) || empty($val['math']) || empty($val['sub'])) {
            $val['field'] = $col;
        }
        if (empty($val['value'])) {
            $val['value'] = $val;
        }
        if (empty($val['simile'])) {
            if (is_array($val['value']) && empty($val['value']['field']) && empty($val['value']['func']) && empty($val['value']['math'])) {
                $val['simile'] = 'IN';
            } else {
                $val['simile'] = '=';
            }
        }
        if (in_array(strtolower($val['simile']), $this->expected_similes)) {
            $simile = strtoupper($val['simile']);
            $left = $this->getValue($val);
            if (!empty($left)) {
                if (is_null($val['value'])) {
                    $right = 'NULL';
                } else {
                    if ($simile == 'BETWEEN') {
                        $value[] = $this->getValue($val['value']['from']);
                        $value[] = $this->getValue($val['value']['to']);
                        $right = implode(' AND ', $value);
                    } else {
                        $right = $this->getValue($val['value']);
                    }
                }
                if (!empty($right)) {
                    $where = "{$left} {$simile} {$right}";
                }
            }
        } else {
            $this->log('Сравнение '.$val['simile'].' не предусмотрено, либо тут ошибка, либо требуется доработка модели, ');//TODO doc link
        }
        return $where;
    }

    /**
     * @param $group_by = array('column',)
     */
    public final function setGroupBy(array $group_by): void
    {
        $this->group_by = $group_by;
    }

    /**
     * @return string
     */
    protected final function getGroupBy(): string
    {
        $group_by = array();
        $this->current_directive = 'group';
        if (!empty($this->group_by)) {
            foreach ($this->group_by as $group) {
                if (is_array($group)) {
                    if (isset($group['field']) || isset($group['func']) || isset($group['math'])) {
                        $group = $this->getValue($group);
                        if (!empty($group)) {
                            $group_by[] = $group;
                        }
                    }
                } else {
                    if ($this->checkField($group)) {
                        $group_by[] = $group;
                    } else {
                        $this->log('Поле '.$group.' не найдено при формировании группировки смотри лог выше, ');//TODO doc link
                    }
                }
            }
        }
        if (empty($group_by)) {
            $group_by = '';
        } else {
            $group_by = 'GROUP BY '.implode(', ', $group_by);
        }
        $this->current_directive = null;
        return $group_by;
    }

    /**
     * @param array $having = array('column' => array('simile' => '!=/=/>/</>=/<=','value' => string/int/array),)
     * @param string|array $def_logic = AND/OR/array(
     *    array(
     *      'logic' => 'AND|OR',
     *      'fields' => array(
     *          0 => array(
     *            'logic' => 'AND|OR',
     *            'fields' => array(
     *                'table.field' => null,
     *             ),
     *          ),
     *          'table.field' => null,
     *       ),
     *    )
     * )
     * @return void
     */
    public final function setHaving(array $having, string|array $def_logic = 'AND'): void
    {
        $this->having = $having;
        if (!empty($def_logic)) {
            $this->having_logic = $def_logic;
        }
    }

    /**
     * @return string
     */
    protected final function getHaving(): string
    {
        $having = '';
        $this->current_directive = 'having';
        if (!empty($this->having)) {
            $having = $this->getCondition($this->having, $this->having_logic);
            if (strlen($having) > 0) {
                $having = 'HAVING '.$having;
            }
        }
        $this->current_directive = null;
        return $having;
    }

    /**
     * @param array $order_by
     */
    public final function setOrderBy(array $order_by): void
    {
        $this->order_by = $order_by;
    }

    /**
     * @return string
     */
    protected final function getOrderBy(): string
    {
        $this->current_directive = 'order';
        $order_by = array();
        if (!empty($this->order_by)) {
            foreach ($this->order_by as $order => $data) {
                $order = trim($order);
                if (!is_numeric($order)) {
                    $order = $this->getValue(array('field' => trim($order), 'table' => (is_array($data) && isset($data['table'])) ? $data['table'] : null));
                }
                if (is_array($data)) {
                    $dir = strtolower($data['dir'] ?? 'asc') == 'asc' ? 'ASC' : 'DESC';
                } else {
                    $dir = strtolower($data ?? 'asc') == 'asc' ? 'ASC' : 'DESC';
                }
                if (!empty($order)) {
                    $order_by[] = $order.' '.$dir;
                }
            }
        }
        $order_by = implode(', ', $order_by);
        if (empty($order_by)) {
            $order_by = '';
        } else {
            $order_by = 'ORDER BY '.$order_by;
        }
        $this->current_directive = null;
        return $order_by;
    }

    /**
     * @param int $length
     * @param ?int $offset
     */
    public final function setLimit(int $length, ?int $offset = null): void
    {
        $this->limit['length'] = $length;
        $this->limit['offset'] = intval($offset);
    }

    /**
     * @return string
     */
    protected final function getLimit(): string
    {
        $limit = '';
        if (!empty($this->limit['length'])) {
            $limit = $this->limit['length'];
            if (!empty($this->limit['offset'])) {
                $limit = $this->limit['offset'].', '.$this->limit['length'];
            }
            $limit = 'LIMIT '.$limit;
        }
        return $limit;
    }

    protected final function getValue($value): string
    {
        $result = '';
        if (is_array($value)) {
            if (empty($value['math']) && empty($value['func']) && empty($value['field']) && empty($value['sub'])) {
                $temp = $value;
                $type = $this->getValType(array_shift($temp));
            } else {
                $methods = array('math', 'func', 'field', 'sub');
                foreach ($methods as $method) {
                    if (isset($value[$method])) {
                        $method = 'get'.ucfirst($method);
                        if (method_exists($this, $method)) {
                            $result = $this->$method($value);
                            break;
                        }
                    }
                }
            }
        } else {
            $type = $this->getValType($value);
        }
        if (isset($type)) {
            $this->data["value_{$this->counter}"] = $value;
            if (is_array($value)) {
                $result = "({$type}:value_{$this->counter})";
            } else {
                $result = "{$type}:value_{$this->counter}";
            }
            $this->counter++;
        }
        return $result;
    }

    protected final function getFunc(array $data): string
    {
        $result = '';
        if (empty($data['func'])) {
            $this->log('Не указано название функции, ');//TODO doc link
        } else {
            $data['func'] = strtolower($data['func']);
            if (isset($this->expected_functions[$data['func']])) {
                if (count($data['params'] ?? array()) == $this->expected_functions[$data['func']]) {
                    $params = $this->getFuncParams($data['params'] ?? array(), $this->expected_functions[$data['func']]);
                    if (empty($this->expected_functions[$data['func']]) === empty($params)) {
                        $result = strtoupper($data['func']).'('.$params.')';
                    } else {
                        $this->log('При попытке использовать функцию '.$data['func'].' не все входные параметры прошли обработку, смотри лог выше, ');//TODO doc link
                    }
                } else {
                    $this->log('У функции '.$data['func'].' кол-во входных параметров - '.$this->expected_functions[$data['func']].', а передано '.count($data['params'] ?? array()).', ');//TODO doc link
                }
            } else {
                $this->log('Функция '.$data['func'].' не предусмотрена, либо тут ошибка, либо требуется доработка модели, ');//TODO doc link
            }
        }
        return $result;
    }

    protected final function getFuncParams(array $params, int $check): string
    {
        $result = array();
        foreach ($params as $key => $param) {
            if (empty($param['value'])) {
                $param = $this->getvalue($param);
            } else {
                $param = $this->getWhereValue($key, $param);
            }
            if (!empty($param)) {
                $result[] = $param;
            }
        }
        if (count($result) == $check) {
            $result = implode(', ', $result);
        } else {
            $result = '';
        }
        return $result;
    }

    protected final function getMath(array $data): string
    {
        $result = array();
        if (in_array($data['math'], $this->expected_math)) {
            if (empty($data['params'])) {
                $this->log('При попытке сгенерировать запрос по операции '.$data['math'].' в модели таблицы '.$this->table.' не были переданы аргументы, ');//TODO doc link
            } else {
                foreach ($data['params'] as $param) {
                    $param = $this->getValue($param);
                    $result[] = $param;
                }
            }
        } else {
            $this->log('Оператор '.$data['math'].' не поддерживается, либо тут ошибка, либо требуется доработка модели, ');//TODO doc link
        }
        if (empty($result)) {
            $result = '';
        } else {
            $result = implode(' '.$data['math'].' ', $result);
        }
        return $result;
    }

    protected final function getField(array $value): string
    {
        $result = '';
        if ($this->checkField($value['field'], $value['table'] ?? null)) {
            if (!empty($this->check_as)) {
                $value['table'] = $this->check_as;
            }
            $result = $value['field'];
            if (empty($value['table'])) {
                if (!empty($this->join)) {
                    $result = $this->table.'.'.$result;
                }
            } else {
                $result = $value['table'].'.'.$result;
            }
            if (!empty($value['distinct'])) {
                $result = 'DISTINCT '.$result;
            }
        }
        return $result;
    }

    protected final function getSub(array $value): string
    {
        $result = '';
        if ($value['sub'] instanceof pb2bModel) {
            $sub_query = $value['sub']->getSubQuery($this->counter);
            if (!empty($sub_query['query'])) {
                $this->counter = $sub_query['counter'];
                $this->data += $sub_query['data'];
                $result = '('.$sub_query['query'].')';
            }
        } else {
            $this->log('При передаче в условие подзапроса, в ключе sub необходимо передать экземпляр pb2bModel, ');//TODO doc link
        }
        return $result;
    }

    public final function checkField(string $col, ?string $table = null, $check_select = false): bool
    {
        if ($this->current_directive == 'order' || $check_select) {
            if (empty($this->select)) {
                $field = $col;
            } else {
                foreach ($this->select as $f => $d) {
                    if (empty($table) || isset($d['table']) && $d['table'] = $table) {
                        if (is_array($d)) {
                            if (isset($d['as'])) {
                                if (is_array($d['as'])) {
                                    if (in_array($col, $d['as'])) {
                                        $field = $f;
                                        break;
                                    }
                                } else {
                                    if ($col == $d['as']) {
                                        $field = $f;
                                        break;
                                    }
                                }
                            }
                        } else {
                            if ($col == $d) {
                                $field = $f;
                                break;
                            }
                        }
                        if ($col == ($d['as'] ?? $f)) {
                            $field = $f;
                            break;
                        }
                    }
                }
            }
        } else {
            $field = $col;
        }
        if (isset($field)) {
            if (empty($table)) {
                $check = $this->fieldExists($col);
                if (!$check) {
                    if (empty($this->join_tables)) {
                        $this->log('У текущей таблицы '.$this->table.' нет поля '.$col.', ');//TODO doc link
                    } else {
                        $tables = array();
                        foreach ($this->join_tables as $t => $model) {
                            if (empty($this->sub_queries[$t])) {
                                $sub_check = $this->join_tables[$t]->fieldExists($col);
                            } else {
                                $sub_check = $this->join_tables[$t]->checkField($col, null, true);
                            }
                            if ($sub_check) {
                                $tables[] = $this->join_tables[$t]->getTableName();
                                $this->check_as = $t;
                            }
                        }
                        if (empty($tables)) {
                            $this->log('Ни у одной таблицы запроса для модели таблицы '.$this->table.' нет поля '.$col.', ');//TODO doc link
                        } else {
                            if (count($tables) > 1) {
                                $this->log('Поле '.$col.' было найдено в нескольких таблицах запроса, а именно: '.implode(', ', $tables).', ');//TODO doc link
                            } else {
                                $check = true;
                            }
                        }
                    }
                }
            } else {
                if (empty($this->join_tables[$table])) {
                    $this->log('Для алиаса '.$table.' не найдена модель, ');//TODO doc link
                    $check = false;
                } else {
                    if (empty($this->sub_queries[$table])) {
                        $check = $this->join_tables[$table]->fieldExists($col);
                        if (!$check) {
                            $this->log('У таблицы '.$this->join_tables[$table]->getTableName().' нет поля '.$col.', ');//TODO doc link
                        }
                    } else {
                        $check = $this->join_tables[$table]->checkField($col, null, true);
                    }
                }
            }
        } else {
            if ($check_select) {
                $this->log('При подзапрпосе в таблицу '.$this->table.' не найдено поле '.$col.' в директиве SELECT, ');//TODO doc link
            } else {
                $this->log('При запрпосе в таблицу '.$this->table.' не найдено поле '.$col.' в директиве SELECT, ');//TODO doc link
            }
            $check = false;
        }
        return $check;
    }

    public final function queryReset(): void
    {
        $this->select = null;
        $this->join = null;
        $this->where = null;
        $this->logic = 'AND';
        $this->group_by = null;
        $this->having = null;
        $this->having_logic = null;
        $this->order_by = null;
        $this->limit = array();
        $this->fetch_field = null;
        $this->fetch_del = false;
        $this->data = array();
        $this->join_tables = array();
        $this->sub_queries = array();
    }

    /**
     * @param array|null $exclude
     * @return string
     */
    protected final function getQuery(array $exclude = null): string
    {
        $directions = array('join' => '', 'where' => '', 'groupBy' => '', 'having' => '', 'select' => '', 'orderBy' => '', 'limit' => '');
        foreach ($directions as $direction => &$q) {
            if (!is_array($exclude) || !in_array($direction, $exclude)) {
                $method = 'get'.ucfirst($direction);
                if (method_exists($this, $method)) {
                    $q = $this->$method();
                    if (empty($q)) {
                        unset($directions[$direction]);
                    }
                } else {
                    unset($directions[$direction]);
                }
            } else {
                unset($directions[$direction]);
            }
        }
        $select = $directions['select'];
        unset($directions['select']);
        return $select.' '.implode(' ', $directions);
    }

    /**
     * @param boolean $reset
     * @return array|string|int|mixed all/assoc; string field; default false
     * @throws waDbException
     */
    public final function queryRun(bool $reset = true): mixed
    {
        $query = $this->getQuery();
        $result = false;
        if (!empty($query)) {
            switch ($this->fetch_type) {
                case 'all':
                    $result = $this->query($query, $this->data)->fetchAll($this->fetch_field, $this->fetch_del);
                    if (empty($result)) {
                        $result = array();
                    }
                    break;
                case 'assoc':
                    $result = $this->query($query, $this->data)->fetchAssoc();
                    if (empty($result)) {
                        $result = array();
                    }
                    break;
                case 'field':
                    $result = $this->query($query, $this->data)->fetchField();
                    break;
            }
            if ($reset) {
                $this->queryReset();
            }
        }
        return $result;
    }

    public final function getSubQuery($counter = 1): array
    {
        $this->counter = $counter;
        return array(
            'query' => $this->getQuery(),
            'data' => $this->data,
            'counter' => $this->counter,
        );
    }

    /**
     * @param string $type select/insert/update
     */
    public final function showQuery(string $type = 'select'): void
    {
        $types = array(
            'insert' => "INSERT IGNORE INTO {$this->table}{$this->insert_table} VALUES {$this->insert_row};",
            'update' => $this->update_row,
            'select' => $this->getQuery(),
        );
        $this->setValues($types['select']);
        waLog::log($types[$type], 'pb2bModel.log');
    }

    /**
     * @param $query
     */
    protected final function setValues(&$query): void
    {
        $query = str_replace('i:value', 'value', $query);
        $query = str_replace('s:value', 'value', $query);
        $query = str_replace('f:value', 'value', $query);
        foreach ($this->data as $placeholder => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            } else {
                if (!is_numeric($value)) {
                    $value = "'".$value."'";
                }
            }
            $query = str_replace($placeholder, $value, $query);
        }
    }

    /**
     * @param $set
     * @throws waDbException
     */
    public final function updateBy($set): void
    {
        $set_row = array();
        foreach ($set as $field => $value) {
            if ($this->fieldExists($field)) {
                if (is_null($value)) {
                    $set_row .= " {$field} = NULL";
                } else {
                    $set_row .= " {$field} = {$this->getValue($value)}";
                }
            } else {
                $this->log('Поле '.$field.' не было найдено таблице '.$this->table.', смотри запись в логе выше данной для получения больших данных, ');//TODO doc link
            }
        }
        $set_row = implode(', ', $set_row);
        if (strlen($set_row) > 0) {
            $this->query("UPDATE {$this->table} SET {$set_row}{$this->where}", $this->data);
        }
    }

    /**
     * @throws waDbException
     */
    public final function clear(): void
    {
        $this->query("DELETE FROM {$this->table}");
        if ($this->id == 'id') {
            $this->query("ALTER TABLE {$this->table} AUTO_INCREMENT = 1;");
        }
    }

    /**
     * @throws waDbException
     */
    public final function deleteBy(): void
    {
        $this->query("DELETE FROM {$this->table}{$this->getWhere()}", $this->data);
    }
    
    protected final function log(string $record): void
    {
        if (class_exists('pb2bHelper')) {
            pb2bHelper::logRecord('pb2bModel', array($record.pb2bHelper::getAddressPerson().'!'));
        } else {
            waLog::log($record.' товарищ!', 'pb2bModel.log');
        }
    }

    /**
     * @param $app
     * @return array|bool
     * @throws waDbException
     */
    static final public function getTables($app): bool|array
    {
        $result = false;
        $model = new waModel();
        if (!empty($app)) {
            $result = $model->query("SHOW TABLES LIKE s:pattern", array('pattern' => $app.'_%'))->fetchAll();
        }
        return $result;
    }

    /**
     * @param array $data
     * @param string $key
     * @param $value
     */
    static public final function setElement(array &$data, string $key, $value): void
    {
        if (isset($data[$key])) {
            self::setElement($data, $key.' ', $value);
        } else {
            $data[$key] = $value;
        }
    }

    /**
     * @param array $logic
     * @param array $add
     */
    static public final function mergeLogic(array &$logic, array $add): void
    {
        foreach ($add as $key => $value) {
            if (isset($logic[$key])) {
                if (is_array($logic[$key]) && is_array($value)) {
                    self::mergeLogic($logic[$key], $value);
                }
            } else {
                $logic[$key] = $value;
            }
        }
    }
}