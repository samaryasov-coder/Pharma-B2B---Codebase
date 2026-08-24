<?php

class pb2bSettingsSaveController extends waJsonController
{
    public function execute()
    {
        $type = waRequest::post('type', null);
        $posted = waRequest::post('settings', [], waRequest::TYPE_ARRAY);
        unset($posted['type']);
        
        if (!$type) {
            $this->response = ['error' => 1, 'message' => 'Не передан type'];
            return;
        }

        $service = new pb2bSettings();
        $this->response = $service->save($type, $posted);
    }
}