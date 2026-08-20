<?php
class pb2bFrontendApiBuyerDocflowRequestCreateController extends pb2bFrontendCabinetController {

    private function getCollectionTemplateIds($company_id, $process_type): array
    {
        $collection = new pb2bDocflowTemplateCollection("company_id=$company_id&process_type=$process_type");
        $rows = $collection->getCollection();

        return array_keys($rows);
    }

    public function executeAction()
    {
        $provider_company_id = waRequest::post('provider_company_id', 0, waRequest::TYPE_INT);
        $mode = waRequest::post('mode', null, waRequest::TYPE_STRING_TRIM);
        $comment = waRequest::post('comment', null, waRequest::TYPE_STRING_TRIM);
        $template_item_ids = waRequest::post('ids', [], waRequest::TYPE_ARRAY_INT);

        $service = new pb2bDocflowRequestService();
        switch ($mode){
            case 'all':
                $request = $service->createFromReviewer($this->context->company()->id, $provider_company_id, 1, $comment);
                break;

            case 'selected':
                if (empty($template_item_ids))
                    return $this->response = ['error' => true, 'message' => 'Не выбраны документы'];
                $request = $service->createFromReviewer($this->context->company()->id, $provider_company_id, 1, $comment, $template_item_ids);
                break;

            default:
                return $this->response = ['error' => true, 'message' => 'Неизвестный тип запроса документов'];
        }

        $this->response = ['error' => false, 'message' => 'Запрос создан', 'request' => $request->id];
    }
}