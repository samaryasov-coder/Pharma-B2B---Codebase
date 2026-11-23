<?php

class pb2bPrequalApplicationService
{
    public function submit(int $id, pb2bCompany $company, pb2bPrequalApplicationDto $dto, waContact $actor): pb2bPrequalApplication
    {
        $service = new pb2bPrequalService();
        $prequal = $service->getFromSupplier($id, $company);
        $model = new pb2bPrequalModel();
        $model->query('START TRANSACTION');

        try {
            $model->lockForUpdate($id);
            $prequal = $service->getFromSupplier($id, $company);
            if (!$prequal->canAcceptApplications()) throw new waException('Приём заявок закрыт', pb2bHttpStatus::CONFLICT);
            if ($prequal->getApplicationBySupplier((int) $company->id)) throw new waException('У компании уже есть заявка на эту процедуру', pb2bHttpStatus::CONFLICT);

            $items = $prequal->getItems();
            if (!$items) throw new waException('В процедуре нет предметов квалификации', pb2bHttpStatus::CONFLICT);

            $this->validateConsents($prequal, $dto);
            $storage = new pb2bFileStorageService();
            $uploads = $this->validateDocuments($prequal, $dto, $storage);

            $submitted_at = (new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');
            $status = pb2bPrequalApplicationStatus::SUBMITTED->id();
            $application = new pb2bPrequalApplication();
            $result = $application->save(array(
                'prequal_id' => $id,
                'supplier_company_id' => (int) $company->id,
                'contact_id' => $actor->getId(),
                'status' => $status,
                'submitted_at' => $submitted_at,
            ));
            if (!empty($result['error']) || !$application->id) throw new waException('Не удалось сохранить заявку', pb2bHttpStatus::INTERNAL_SERVER_ERROR);

            $this->saveItems($application, $items);
            $this->saveDocuments($application, $uploads, $storage, $company, $id, $submitted_at);

            $result = (new pb2bPrequalApplicationHistory())->save(array(
                'application_id' => (int) $application->id,
                'action' => pb2bPrequalApplicationStatus::SUBMITTED->value,
                'status_to' => $status,
                'actor_contact_id' => $actor->getId(),
                'actor_company_id' => (int) $company->id,
                'data_json' => json_encode(array(
                    'audit_consent' => $dto->audit_consent === 1,
                    'samples_consent' => $dto->samples_consent === 1,
                    'info_consent' => $dto->info_consent === 1,
                ), JSON_THROW_ON_ERROR),
            ));
            if (!empty($result['error'])) throw new waException('Не удалось сохранить историю заявки', pb2bHttpStatus::INTERNAL_SERVER_ERROR);

            $model->query('COMMIT');
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }

        return $application;
    }

    private function validateConsents(pb2bPrequal $prequal, pb2bPrequalApplicationDto $dto): void
    {
        if (!empty($prequal->data['requires_audit']) && $dto->audit_consent !== 1) {
            throw new waException('Подтвердите согласие на аудит', pb2bHttpStatus::BAD_REQUEST);
        }
        if (!empty($prequal->data['requires_samples']) && $dto->samples_consent !== 1) {
            throw new waException('Подтвердите согласие на отправку образцов', pb2bHttpStatus::BAD_REQUEST);
        }
        if ($dto->info_consent !== 1) {
            throw new waException('Подтвердите согласие на получение информационных сообщений', pb2bHttpStatus::BAD_REQUEST);
        }
    }

    private function validateDocuments(pb2bPrequal $prequal, pb2bPrequalApplicationDto $dto, pb2bFileStorageService $storage): array
    {
        $requirements = array_column($prequal->getDocuments(pb2bPrequalDocument::KIND_REQUIREMENT), null, 'id');
        $uploads = array();

        foreach ($dto->documents as $document_id => $file) {
            if (!isset($requirements[$document_id])) throw new waException('Документ не относится к требованиям этой процедуры', pb2bHttpStatus::BAD_REQUEST);
            if ($file->error_code === UPLOAD_ERR_NO_FILE) continue;
            $storage->validateDocumentUpload($file);
            $uploads[$document_id] = $file;
        }

        foreach ($requirements as $document_id => $document) {
            if (!empty($document['is_required']) && !isset($uploads[$document_id])) {
                throw new waException('Загрузите обязательный документ «' . $document['name'] . '»', pb2bHttpStatus::BAD_REQUEST);
            }
        }

        return $uploads;
    }

    private function saveItems(pb2bPrequalApplication $application, array $items): void
    {
        foreach ($items as $item) {
            $result = (new pb2bPrequalApplicationItem())->save(array(
                'application_id' => (int) $application->id,
                'prequal_item_id' => (int) $item['id'],
            ));
            if (!empty($result['error'])) throw new waException('Не удалось сохранить предмет заявки', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    private function saveDocuments(pb2bPrequalApplication $application, array $uploads, pb2bFileStorageService $storage, pb2bCompany $company, int $id, string $submitted_at): void
    {
        foreach ($uploads as $document_id => $file) {
            $link = $storage->saveFileAndCreateLink($file, 'prequal', (int) $company->id, array(
                'purpose' => 'prequal_application_document',
                'prequal_id' => $id,
                'application_id' => (int) $application->id,
            ));
            $result = (new pb2bPrequalApplicationDocument())->save(array(
                'application_id' => (int) $application->id,
                'prequal_document_id' => $document_id,
                'file_link_id' => (int) $link->id,
                'status' => pb2bPrequalApplicationDocumentStatus::UPLOADED->id(),
                'uploaded_at' => $submitted_at,
            ));
            if (!empty($result['error'])) throw new waException('Не удалось сохранить документ заявки', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
        }
    }
}
