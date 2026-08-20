<?php

class pb2bTenderEditAction extends pb2bWaproViewAction
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $object = new pb2bTender(waRequest::post('id', null, waRequest::TYPE_INT));
        $pack = $object->get();
        $row = $pack['object'] ?? array();
        $pack['tender_dt_local'] = array(
            'start_at' => pb2bWaproHelper::formatMysqlDatetimeForDatetimeLocal($row['start_at'] ?? ''),
            'end_at' => pb2bWaproHelper::formatMysqlDatetimeForDatetimeLocal($row['end_at'] ?? ''),
            'opening_at' => pb2bWaproHelper::formatMysqlDatetimeForDatetimeLocal($row['opening_at'] ?? ''),
        );
        $pack += pb2bWaproHelper::getConfigOption(array('tender_types', 'tender_statuses', 'tender_submission_forms'));
        $this->view->assign($pack);
    }
}
