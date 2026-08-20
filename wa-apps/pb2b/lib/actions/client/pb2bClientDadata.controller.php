<?php

class pb2bClientDadataController extends waJsonController
{
    public function execute(): void
    {
        $type = waRequest::post('type', 'party', waRequest::TYPE_STRING_TRIM);
        $query = waRequest::post('query', '', waRequest::TYPE_STRING_TRIM);
        
        $service = new pb2bDadataService();
        if ($type === 'bank') {
            $result = $service->findBankByBik($query);
        } else {
            $result = $service->findPartyByInn($query);
        }
        
        if (!is_array($result)) {
            $this->response = array('error' => 1, 'message' => 'Некорректный ответ сервиса');
            return;
        }

        if (!empty($result['error'])) {
            $this->response = array(
                'error' => 1,
                'message' => (string)ifempty($result['message'], 'Ошибка DaData'),
            );
            return;
        }

        $this->response = array(
            'error' => 0,
            'data' => (array)ifempty($result['data'], array()),
        );
    }
}