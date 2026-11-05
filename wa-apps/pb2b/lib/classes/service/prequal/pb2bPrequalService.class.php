<?php

class pb2bPrequalService extends pb2bBaseService
{
    public function getPrequalWithAssert(int $id): pb2bPrequal
    {
        $prequal = new pb2bPrequal($id);
        if (!(int) $prequal->id) {
            throw new waException('Процедура ПК не найдена', pb2bHttpStatus::NOT_FOUND);
        }

        return $prequal;
    }

    public function getFromBuyer(int $id, pb2bCompany $company): pb2bPrequal
    {
        $prequal = $this->getPrequalWithAssert($id);
        if (!pb2bPrequalPolicy::view($prequal, $company)) {
            throw new waException('Нет доступа к процедуре ПК', pb2bHttpStatus::FORBIDDEN);
        }

        return $prequal;
    }

    public function createFromBuyer(pb2bCompany $company, pb2bPrequalDto $dto, waContact $actor): pb2bPrequal
    {
        if (!pb2bPrequalPolicy::create($company)) {
            throw new waException('Создание ПК недоступно', pb2bHttpStatus::FORBIDDEN);
        }
        if ((int) $actor->getId() <= 0) {
            throw new waException('Не указан автор процедуры', pb2bHttpStatus::BAD_REQUEST);
        }

        $prequal = new pb2bPrequal();
        $data = array_merge(array(
            'title' => '',
            'is_private' => 0,
            'requires_audit' => 0,
            'requires_samples' => 0,
            'auto_extend' => 0,
            'provider_id' => null,
            'file_set_id' => null,
            'results_planned_datatime' => null,
        ), $dto->toSaveArray());
        $data['reviewer_id'] = (int) $company->id;
        $data['contact_id'] = $actor->getId();
        $data['status'] = pb2bPrequalStatus::DRAFT->id();
        $prequal->model->query('START TRANSACTION');
        try {
            $data['procedure_code'] = (new pb2bProcedureCodeService())->issue('prequalification');
            $result = $prequal->save($data);
            if (!empty($result['error'])) {
                throw new waException((string) ($result['message'] ?? 'Не удалось создать ПК'), pb2bHttpStatus::BAD_REQUEST);
            }
            if ($dto->has('invitations')) {
                $this->saveInvitations($prequal, $dto->invitations, $actor);
            }
            $prequal->model->query('COMMIT');
        } catch (Throwable $e) {
            $prequal->model->query('ROLLBACK');
            throw $e;
        }

        return $prequal;
    }

    public function updateFromBuyer(int $id, pb2bCompany $company, pb2bPrequalDto $dto, waContact $actor): pb2bPrequal
    {
        $prequal = $this->getPrequalWithAssert($id);
        if (!pb2bPrequalPolicy::update($prequal, $company)) {
            throw new waException('Нет доступа к процедуре ПК', pb2bHttpStatus::FORBIDDEN);
        }
        if (!$prequal->canEdit()) {
            throw new waException('Изменять можно только черновик ПК', pb2bHttpStatus::CONFLICT);
        }

        if ((int) $actor->getId() <= 0) {
            throw new waException('Не указан автор изменения', pb2bHttpStatus::BAD_REQUEST);
        }
        $prequal->model->query('START TRANSACTION');
        try {
            $result = $prequal->save(array_merge($prequal->data, $dto->toSaveArray()));
            if (!empty($result['error'])) {
                throw new waException((string) ($result['message'] ?? 'Не удалось сохранить ПК'), pb2bHttpStatus::BAD_REQUEST);
            }
            if ($dto->has('invitations')) {
                $this->saveInvitations($prequal, $dto->invitations, $actor);
            }
            $prequal->model->query('COMMIT');
        } catch (Throwable $e) {
            $prequal->model->query('ROLLBACK');
            throw $e;
        }

        return $prequal;
    }

    private function saveInvitations(pb2bPrequal $prequal, array $ids, waContact $actor): void
    {
        if ($ids) {
            $companies = (new pb2bCompanyCollection(array(
                'col' => array('id' => array('simile' => 'IN', 'value' => $ids)),
            )))->getCollection(array('key' => 'id', 'select' => array('id' => null, 'supplier' => null)));
            foreach ($ids as $id) {
                if (!isset($companies[$id])) {
                    throw new waException('Приглашённая компания не найдена', pb2bHttpStatus::NOT_FOUND);
                }
                if (empty($companies[$id]['supplier']) || $id === (int) $prequal->data['reviewer_id']) {
                    throw new waException('Выберите другую компанию-поставщика', pb2bHttpStatus::BAD_REQUEST);
                }
            }
        }
        $remaining = array();
        foreach ($prequal->getInvitations() as $row) {
            $remaining[(int) $row['supplier_company_id']] = (int) $row['id'];
        }
        foreach ($ids as $id) {
            if (isset($remaining[$id])) {
                unset($remaining[$id]);
                continue;
            }
            $result = (new pb2bPrequalInvitation())->save(array(
                'prequal_id' => (int) $prequal->id,
                'supplier_company_id' => $id,
                'invited_by_contact_id' => $actor->getId(),
            ));
            if (!empty($result['error'])) {
                throw new waException((string) ($result['message'] ?? 'Не удалось сохранить приглашение'), pb2bHttpStatus::BAD_REQUEST);
            }
        }
        foreach ($remaining as $id) {
            $result = (new pb2bPrequalInvitation($id))->delete();
            if (!empty($result['error'])) {
                throw new waException((string) ($result['message'] ?? 'Не удалось убрать приглашение'), pb2bHttpStatus::BAD_REQUEST);
            }
        }
    }
}
