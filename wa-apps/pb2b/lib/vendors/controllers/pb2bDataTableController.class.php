<?php

abstract class pb2bDataTableController extends waJsonController
{
    protected array $data_tables_params = array(
        'direction' => 'ASC',
    );

    public function __construct()
    {
        $this->data_tables_params['page'] = waRequest::get('page', 0, waRequest::TYPE_INT);
        $this->data_tables_params['draw'] = waRequest::get('draw', 0, waRequest::TYPE_INT);
        $this->data_tables_params['start'] = waRequest::get('start', 0, waRequest::TYPE_INT);
        $this->data_tables_params['length'] = waRequest::get('length', 10, waRequest::TYPE_INT);
        if ($this->data_tables_params['length'] > 100 || $this->data_tables_params['length'] < 0) {
            $this->data_tables_params['length'] = 100;
        }
        $order = waRequest::get('order', null, waRequest::TYPE_ARRAY);
        if (!empty($order)) {
            $this->data_tables_params['order'] = 1;
            if (isset($order[0]['column'])) {
                $this->data_tables_params['column'] = intval($order[0]['column']);
            }
            if (isset($order[0]['dir'])) {
                $this->data_tables_params['direction'] = strtoupper($order[0]['dir']);
            }
        }
        if (!empty($_GET['search']['value'])) {
            $this->data_tables_params['search'] = $_GET['search']['value'];
        }
    }

    /**
     * @return void
     * @throws SmartyException
     * @throws waDbException
     * @throws waException
     */
    public function execute(): void
    {
        if (!empty($this->type)) {
            $class = pb2bHelper::getAppId().ucfirst($this->type).'Collection';
            if (class_exists($class)) {
                $hash = waRequest::get('hash', null, waRequest::TYPE_STRING_TRIM);
                if (!empty($hash)) {
                    $hash = urldecode($hash);
                }
                $class = new $class($hash);
                if ($class instanceof pb2bCollection && method_exists($class, 'getDataTable')) {
                    $this->response = $class->getDataTable($this->data_tables_params);
                }
            }
        }
    }

    public function display(): void
    {
        $this->getResponse()->sendHeaders();
        if (!$this->errors) {
            echo json_encode($this->response);
        } else {
            echo json_encode(array('status' => 'fail', 'errors' => $this->errors));
        }
    }
}