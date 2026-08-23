<?php

/**
 * Class pb2bObject
 * @property $id
 * @property $data
 * @property $params
 * @property $class_name
 * @property $model
 * @property $pagination_params
 */
abstract class pb2bObject implements arrayAccess
{
    use pb2bBaseTrait;
    /**
     * @var int|null
     */
    protected ?int $id = null;
    /**
     * @var string
     */
    protected string $class_name;
    /**
     * @var string
     */
    protected mixed $app_id;
    /**
     * @var array
     */
    protected array $data = array();
    /**
     * @var array
     */
    protected array $params = array();
    /**
     * @var ?pb2bModel
     */
    protected ?pb2bModel $model = null;
    /**
     * @var ?pb2bObject
     */
    protected ?pb2bObject $subject = null;

    /**
     * pb2bCollection constructor.
     * @param ?int $id
     * @throws waException
     */
    public function __construct(?int $id = null)
    {
        $this->baseConstruct();
        $this->setId($id);
    }

    /**
     * @param $name
     * @return mixed|int|string|false
     */
    public function __get($name)
    {
        $get = array('id', 'data', 'class_name', 'model');
        $result = false;
        if (in_array($name, $get)) {
            $result = $this->$name;
        } else {
            $method = 'get'.ucfirst($name);
            if (method_exists($this, $method)) {
                $result = $this->$method();
            }
        }
        return $result;
    }

    public function __set($name, $value)
    {
        $method = 'set'.ucfirst($name);
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    public function offsetGet($offset): mixed
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->__set($offset, null);
    }

    public function offsetExists($offset): bool
    {
        $result = false;
        if(isset($this->data[$offset])) {
            $result = true;
        }
        return $result;
    }

    /**
     * @param $value
     * @throws waException
     */
    protected function setId($value): void
    {
        if ($this->model instanceof pb2bModel && !empty($value)) {
            if (is_array($value)) {
                if ($this->data = $this->model->getByField($value)) {
                    $this->id = $this->data['id'];
                }
            } else {
                if ($data = $this->model->getById($value)) {
                    $this->data = $data;
                    $this->id = intval($value);
                }
            }
        } else {
            $this->id = null;
            $this->data = array();
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws waException
     */
    protected function preSave(array &$data): array
    {
        $result = pb2bHelper::validate($data, $this->class_name);
        if (!$result['error']) {
            if (empty($this->id)) {
                $result['new'] = true;
            }
            if (method_exists($this, 'setStream')) {
                $this->setStream($result, $data);
            }
            if (!empty($data['category_change']) && !empty($data['category_id'])) {
                if (empty($data['categories'])) {
                    $data['categories'] = array();
                }
                $data['categories'][] = $data['category_id'];
                $result['categories'] = $data['categories'];
                unset($data['categories']);
            }
        }
        return $result;
    }

    /**
     * @param array $data
     * @return array
     * @throws waException
     */
    public final function save(array $data): array
    {
        $result = $this->preSave($data);
        if (!$result['error']) {
            $object_name = $this->getNameCase(1);
            if (isset($this->id) && !empty($this->data)) {
                if (pb2bHelper::arrayChangeCheck($this->data, $data)) {
                    $this->model->updateById($this->id, $data);
                }
                $result['message'] = 'Обновление '.$object_name.' прошло успешно';
            } else {
                $this->setId($this->model->insert($data));
                $result['message'] = 'Добавление '.$object_name.' прошло успешно';
            }
            $data['id'] = $this->id;
            $result['item'] = $data;
            $this->afterSave($result);
        }
        return $result;
    }

    protected function afterSave(array &$result): void
    {
    }

    /**
     * @param array $data
     * @return array|false[]
     */
    protected function preDelete(array &$data): array
    {
        $result = array('error' => true, 'message' => 'Ошибка получения идентификатора');
        if ($this->id) {
            $result = array('error' => false);
        }
        return $result;
    }

    /**
     * @param $data
     * @return false[]
     * @throws waException
     */
    public final function delete($data): array
    {
        $result = $this->preDelete($data);
        if (!$result['error']) {
            $this->model->deleteById($this->id);
            $this->afterDelete($result);
            $this->setId(null);
        }
        return $result;
    }

    protected function afterDelete(array &$result): void
    {
    }

    /**
     * @param int $case
     * @return mixed|string
     * @throws waException
     */
    public function getNameCase(int $case = 0): mixed
    {
        $cases = pb2bHelper::getConfigOption('objects');
        $cases = $cases[$this->class_name]['cases'] ?? array(
            'объект',
            'объекта',
            'объекту',
            'объект',
            'объектом',
            'объекте',
            'объекты',
            'объектов',
            'объектам',
            'объектов',
            'объектами',
            'объектах',
        );
        if (empty($cases[$case])) {
            $result = $cases;
        } else {
            $result = $cases[$case];
        }
        if ($this->subject) {
            $subject = $this->subject->getNameCase(7);
            if (is_array($result)) {
                foreach ($result as $k => $n) {
                    $result[$k] .= ' '.$subject;
                }
            } else {
                $result .= ' '.$subject;
            }
        }
        return $result;
    }

    public function get($params = array()): array
    {
        $result['object'] = $this->data;
        $this->params = $params;
        if ($this->id && !empty($params['includes']) && is_array($params['includes'])) {
            foreach ($params['includes'] as $include) {
                $result['includes'][$include] = $this->__get($include);
            }
        }
        return $result;
    }
}