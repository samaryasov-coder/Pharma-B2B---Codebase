<?php

/**
 * Trait pb2bWaproBaseTrait
 * @property $class_name
 * @property $model
 */
trait pb2bWaproBaseTrait
{
    /**
     * @var string
     */
    protected string $class_name;
    /**
     * @var string
     */
    protected mixed $app_id;
    /**
     * @var ?pb2bWaproModel
     */
    protected ?pb2bWaproModel $model = null;

    /**
     * @param string $replace
     * @return void
     * @throws waException
     */
    protected final function baseConstruct(string $replace = ''): void
    {
        $this->app_id = pb2bWaproHelper::getAppId();
        if (empty($this->class_name)) {
            $this->class_name = pb2bWaproHelper::getClassName($this, $replace);
        }
        if (!($this->model instanceof pb2bWaproModel)) {
            $model_name = $this->app_id . ucfirst($this->class_name) . 'Model';
            if (class_exists($model_name)) {
                $this->model = new $model_name();
            }
        }
    }

    protected final function getModuleObject($module, $id = null)
    {
        return $this->getModuleClass($module, $id);
    }

    protected final function getModuleCollection($module, $hash = null)
    {
        return $this->getModuleClass($module, $hash, 'collection');
    }

    protected final function getModuleClass($module, $construct = null, $sub_class = ''): ?object
    {
        $result = null;
        $parent = 'pb2b'.ucfirst($module).ucfirst($sub_class);
        if (class_exists($parent)) {
            $class = $this->app_id.ucfirst($this->class_name).ucfirst($module).ucfirst($sub_class);
            if (class_exists($class)) {
                $class = new $class($construct);
                if ($class instanceof $parent) {
                    $result = $class;
                }
            }
        }
        return $result;
    }
}