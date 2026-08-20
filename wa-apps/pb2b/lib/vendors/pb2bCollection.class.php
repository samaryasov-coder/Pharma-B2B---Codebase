<?php

/**
 * Class pb2bCollection
 * @property $hash
 * @property $pagination_params
 */
abstract class pb2bCollection
{
    use pb2bBaseTrait;
    /**
     * @var array
     */
    protected array $hash;
    /**
     * @var array
     */
    protected array $pagination_params = array('page' => 0, 'limit' => 10, 'total' => 0);
    /**
     * @var string[]
     */
    protected array $hash_similes = array('~=', '>=', '<=', '!=', '=', '<>', '<', '>');

    /**
     * pb2bCollection constructor.
     * @param null $hash
     * @throws waException
     */
    public function __construct($hash = null)
    {
        $this->baseConstruct('Collection');
        $this->setHash($hash);
    }

    /**
     * @param $name
     * @return mixed|int|string|false
     */
    public function __get($name)
    {
        $get = array('hash', 'class_name', 'model', 'pagination_params');
        $result = false;
        if (in_array($name, $get)) {
            $result = $this->$name;
        }
        return $result;
    }

    /**
     * @param null $hash
     */
    public function setHash($hash = null): void
    {
        if (empty($hash)) {
            $this->hash = array();
        } else {
            if (is_array($hash)) {
                $this->hash = $hash;
            } else {
                $this->hash = $this->hashDecode($hash);
            }
        }
    }

    /**
     * @param $hash
     * @return array
     */
    public function hashDecode($hash): array
    {
        $result = array();
        $hash = rawurldecode($hash);
        $hash = explode('&', $hash);
        foreach ($hash as $value) {
            $value = explode('.', $value);
            if (count($value) == 2) {
                $this->setHashValue($result, $value[0], $value[1]);
            } else {
                $this->setHashValue($result, 'col', $value[0]);
            }
        }
        return $result;
    }

    /**
     * @param $result
     * @param $field
     * @param $value
     */
    protected function setHashValue(&$result, $field, $value): void
    {
        foreach ($this->hash_similes as $simile) {
            if (mb_strpos($value, $simile)) {
                $value = explode($simile, $value);
                if (count($value) == 2 && strlen($value[1])) {
                    $val = array('value' => $value[1], 'simile' => $simile);
                    if ($this->checkHashValue($val)) {
                        if (empty($result[$field])) {
                            $result[$field] = array();
                        }
                        pb2bModel::setElement($result[$field], ($field == 'col' ? $this->model->getTableName().'.' : '').$value[0], $val);
                    }
                }
                break;
            }
        }
    }

    /**
     * @param $value
     * @return bool
     */
    protected function checkHashValue(&$value): bool
    {
        $result = true;
        if (empty($value['simile'])) {
            $result = false;
        } else {
            switch ($value['simile']) {
                case '~=':
                    $value['simile'] = 'LIKE';
                    $value['value'] = '%'.$value['value'].'%';
                    break;
                case '!=':
                    $value['value'] = explode('|', $value['value']);
                    if (count($value['value']) > 1) {
                        $value['simile'] = 'NOT IN';
                    } else {
                        if ($value['value'][0] == 'NULL') {
                            $value['simile'] = 'IS NOT';
                            $value['value'] = null;
                        } else {
                            $value['value'] = $value['value'][0];
                        }
                    }
                    break;
                case '=':
                    $value['value'] = explode('|', $value['value']);
                    if (count($value['value']) > 1) {
                        $value['simile'] = 'IN';
                    } else {
                        if ($value['value'][0] == 'NULL') {
                            $value['simile'] = 'IS';
                            $value['value'] = null;
                        } else {
                            $value['value'] = $value['value'][0];
                        }
                    }
                    break;
                case '<>':
                    $value['value'] = explode('|', $value['value']);
                    if (count($value['value']) == 2) {
                        if (empty($value['value'][0])) {
                            if (!empty($value['value'][1])) {
                                $value['value'] = $value['value'][1];
                                $value['simile'] = '<=';
                            }
                        } else {
                            if (empty($value['value'][1])) {
                                $value['value'] = $value['value'][0];
                                $value['simile'] = '>=';
                            } else {
                                $value['simile'] = 'BETWEEN';
                                $value['value'] = array('from' => $value['value'][0], 'to' => $value['value'][1]);
                            }
                        }
                    }
                    break;
                case '<':
                case '>':
                case '<=':
                case '>=':
                    break;
                default:
                    $result = false;
            }
        }
        return $result;
    }

    /**
     * @param array $params example array('order' => array('id' => 'ASC', 'name' => 'DESC'), 'start' => 1)
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function getCollection(array $params = array()): array
    {
        $this->model->setFetch('all', $params['key'] ?? 'id');
        $this->model->setSelect(array($this->model->getTableName().'.*' => null));
        foreach ($this->hash as $type => $values) {
            $method = 'addWhere'.ucfirst($type);
            if (method_exists($this, $method)) {
                $this->$method($values);
            }
        }
        if (!empty($params['order'])) {
            $this->model->setOrderBy($params['order']);
        }
        if (empty($params['limit']) || !is_numeric($params['limit'])) {
            $params['limit'] = 50;
        }
        $this->pagination_params['limit'] = $params['limit'];
        if (!empty($params['page'])) {
            $this->pagination_params['page'] = $params['page'] - 1;
        }
        $this->pagination_params['start'] = $params['start'] ?? (!empty($this->pagination_params['page']) ? $this->pagination_params['page'] * $params['limit'] : 0);
        $this->model->setLimit($params['limit'], $this->pagination_params['start']);
        $result = $this->model->queryRun();
//        if ($this->hash != 'all') {
//            $this->pagination_params['total_count'] = count($this->hash);
//        } else {
//            $this->pagination_params['total_count'] = $this->model->countAll();
//        }
//        $this->pagination_params['total'] = ceil($this->pagination_params['total_count']/$this->pagination_params['limit']);
        $this->workup($result, $params);
        return $result;
    }

    /**
     * @param $items
     * @param array $params
     */
    protected function workup(&$items, array $params = array()): void
    {
        if (!empty($items)) {
            if (!empty($params['includes'])) {
                $ids = array();
                foreach ($items as $id => $item) {
                    $ids[$id] = $id;
                }
                foreach ($params['includes'] as $include) {
                    $method = 'include'.ucfirst($include);
                    if (method_exists($this, $method)) {
                        $this->$method($items, $ids);
                    }
                }
            }
        }
    }

    protected function addWhereCol($where): void
    {
        $this->model->addWhere($where);
    }

    /**
     * @param array $params
     * @return array
     * @throws SmartyException
     * @throws waDbException
     * @throws waException
     */
    public function getDataTable(array $params): array
    {
        $result = array('data' => array());
        $this->model->setFetch('field');
        $this->model->setSelect(array(
            array('func' => 'count', 'params' => array('id'))
        ));
        $result['recordsTotal'] = $this->model->queryRun(false);
        if (empty($params['search'])) {
            $result['recordsFiltered'] = $result['recordsTotal'];
        } else {
            $this->setDataTableSearch($params);
            $result['recordsFiltered'] = $this->model->queryRun(false);
        }
        $this->model->setFetch('all');
        $this->setDataTableSelect($params);
        $this->model->setLimit($params['length'], $params['start']);
        if (isset($params['column'])) {
            $this->model->setOrderBy(array($params['column'] + 1 => $params['direction']));
        }
        $result['base_data'] = $this->model->queryRun();
        $this->setDataTableResult($result, $params);
        return $result;
    }

    /**
     * @param array $params
     */
    protected function setDataTableSearch(array $params): void
    {
        $this->model->addWhere(array('name' => array('simile' => 'LIKE', 'value' => '%' . $params['search'] . '%')));
    }

    /**
     * @param array $params
     */
    protected function setDataTableSelect(array $params): void
    {
        $this->model->setSelect(array('name' => null, 'id' => null));
    }

    /**
     * @param array $result
     * @param array $params
     * @return void
     * @throws SmartyException
     * @throws waException
     */
    protected function setDataTableResult(array &$result, array $params): void
    {
        if (!empty($result['base_data'])) {
            $col_templates = array();
            $fields = empty($params['fields']) ? pb2bHelper::getFields($this->class_name) : $params['fields'];
            $this->setDataTableFields($fields, $params);
            foreach ($result['base_data'] as $row) {
                $current_row = array();
                foreach ($fields as $code => $field) {
                    if (isset($field['viewed'])) {
                        $this->setDataTableColTemplate($col_templates, $code);
                        if (empty($col_templates[$code])) {
                            if (isset($row[$code])) {
                                $current_row[] = htmlspecialchars($row[$code], ENT_QUOTES);
                            }
                        } else {
                            $row['params'] = $params;
                            $row['params']['config'] = empty($params['config']) ? null : $params['config'];
                            $row['params']['app_path'] = wa()->getAppPath('', $this->app_id);
                            $this->setDataTableCol($row, $col_templates[$code], $code);
                            if (isset($row[$code])) {
                                $current_row[] = $row[$code];
                            }
                        }
                    }
                }
                $result['data'][] = $current_row;
            }
            unset($result['base_data']);
        }
    }

    protected function setDataTableFields(array &$fields, array $params): void
    {
    }

    /**
     * @param array $col_templates
     * @param string $col_name
     * @throws waException
     */
    protected function setDataTableColTemplate(array &$col_templates, string $col_name): void
    {
        if (empty($col_templates[$col_name])) {
            $col_templates[$col_name] = 'Шаблон не найден';
            $path = wa()->getAppPath('templates/datatable/' . $this->class_name . '/' . $col_name . '.html', $this->app_id);
            if (!file_exists($path)) {
                $path = wa()->getAppPath('templates/datatable/' . $col_name . '.html', $this->app_id);
            }
            if (file_exists($path)) {
                $col_templates[$col_name] = file_get_contents($path);
            }
        }
    }

    /**
     * @param array $row
     * @param string $template
     * @param string $col
     * @return void
     * @throws SmartyException
     * @throws waException
     */
    protected function setDataTableCol(array &$row, string $template, string $col): void
    {
        $view = wa()->getView();
        if (empty($row['class_name'])) {
            $row['class_name'] = $this->class_name;
        }
        $view->assign($row);
        $row[$col] = $view->fetch('string:' . $template);
    }

    /**
     * @param pb2bModel $model
     * @param string $field
     * @param string $include
     * @param array $items
     * @param array $ids
     * @return void
     * @throws waDbException
     */
    protected function includeMultiple(pb2bModel $model, string $field, string $include, array &$items, array $ids): void
    {
        $model->setFetch('all', $field, 2);
        $model->setWhere(array($field => array('simile' => 'IN', 'value' => $ids)));
        $include_items = $model->queryRun();
        foreach ($items as $id => &$item) {
            $item[$include] = $include_items[$id] ?? array();
        }
    }

    /**
     * @param pb2bModel $model
     * @param string $field
     * @param string $include
     * @param array $items
     * @return void
     * @throws waDbException
     */
    protected function include(pb2bModel $model, string $field, string $include, array &$items): void
    {
        $ids = array();
        foreach ($items as $item) {
            if (!empty($item[$field])) {
                $ids[] = $item[$field];
            }
        }
        if  (!empty($ids)) {
            $model->setFetch('all', 'id');
            $model->setWhere(array('id' => array('simile' => 'IN', 'value' => $ids)));
            $include_items = $model->queryRun();
            foreach ($items as &$item) {
                $item[$include] = $include_items[$item[$field]] ?? array();
            }
        }
    }
}