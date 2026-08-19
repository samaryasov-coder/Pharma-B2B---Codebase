<?php

class pb2bSettingsPageAction extends pb2bWaproViewAction
{
    public function execute(): void
    {
        $type = waRequest::request('type', 'general', waRequest::TYPE_STRING_TRIM);

        $settings = new pb2bSettings();
        $data = $settings->getPageData($type);

        if ($type === 'docflow_defaults') {
            $data['defaults_documents'] = pb2bDocflowTemplate::getDefaultDocuments();
            $data['company_types'] = pb2bWaproHelper::getConfigOption('company_type');
        }

        $this->view->assign($data);
    }
}
