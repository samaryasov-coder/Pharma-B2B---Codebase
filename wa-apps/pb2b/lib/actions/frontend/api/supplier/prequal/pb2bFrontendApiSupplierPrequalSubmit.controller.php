<?php

class pb2bFrontendApiSupplierPrequalSubmitController extends pb2bFrontendCabinetController
{
    protected function executeSupplier(): void
    {
        if (waRequest::method() !== 'post') throw new waException('Отправка заявки требует POST', pb2bHttpStatus::METHOD_NOT_ALLOWED);

        $dto = new pb2bPrequalApplicationDto(array(
            'documents' => iterator_to_array(waRequest::file('documents')),
            'audit_consent' => waRequest::post('audit_consent', 0, waRequest::TYPE_INT),
            'samples_consent' => waRequest::post('samples_consent', 0, waRequest::TYPE_INT),
            'info_consent' => waRequest::post('info_consent', 0, waRequest::TYPE_INT),
        ));
        $application = (new pb2bPrequalApplicationService())->submit(
            waRequest::param('id', 0, waRequest::TYPE_INT),
            $this->context->company(),
            $dto,
            $this->context->contact()
        );
        $this->success('Заявка подана', array('application_id' => (int) $application->id));
    }
}
