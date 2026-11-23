<?php

class pb2bPrequalService extends pb2bBaseService
{
    public function listFromBuyer(pb2bCompany $company): array
    {
        return (new pb2bPrequalCollection())->getBuyerList((int) $company->id);
    }

    public function listFromSupplier(pb2bCompany $company): array
    {
        if (!(int) $company->id || !$company->isSupplier()) throw new waException('Не найдена компания-поставщик', pb2bHttpStatus::FORBIDDEN);
        $invitations = (new pb2bPrequalInvitationCollection(array(
            'col' => array('supplier_company_id' => array('value' => (int) $company->id)),
        )))->getCollection(array('key' => 'prequal_id', 'select' => array('prequal_id' => null)));
        $statuses = (array) pb2bWaproHelper::getConfigOption('prequal_statuses', 'id');
        $types = (array) pb2bWaproHelper::getConfigOption('tender_types', 'code');
        $type = $types[pb2bTenderType::PREQUALIFICATION->value] ?? array();
        $out = array();
        foreach ((new pb2bPrequalCollection())->getSupplierCandidates() as $row) {
            if (!pb2bPrequalPolicy::viewSupplierData($row, $company, isset($invitations[(int) $row['id']]))) continue;
            $out[] = array(
                'kind' => 'prequal',
                'tender' => array(
                    'id' => (int) $row['id'],
                    'number' => $row['procedure_code'],
                    'title' => $row['title'],
                    'type' => $type,
                    'status' => $statuses[(int) $row['status']] ?? array(),
                    'end_at' => $row['accept_end_datetime'],
                ),
                'application' => null,
                'card' => array('organizer' => $row['organizer_name'] ?? ''),
            );
        }
        return $out;
    }

    public function getPrequalWithAssert(int $id): pb2bPrequal
    {
        $prequal = new pb2bPrequal($id);
        if (!(int) $prequal->id) throw new waException('Процедура ПК не найдена', pb2bHttpStatus::NOT_FOUND);
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

    public function getFromSupplier(int $id, pb2bCompany $company): pb2bPrequal
    {
        $prequal = $this->getPrequalWithAssert($id);
        $has_invitation = false;
        if (!empty($prequal->data['is_private'])) {
            foreach ($prequal->getInvitations() as $invitation) {
                if ((int) $invitation['supplier_company_id'] === (int) $company->id) {
                    $has_invitation = true;
                    break;
                }
            }
        }
        if (!pb2bPrequalPolicy::viewAsSupplier($prequal, $company, $has_invitation)) throw new waException('Процедура ПК недоступна', pb2bHttpStatus::FORBIDDEN);
        return $prequal;
    }

    public function getBuyerOverview(int $id, pb2bCompany $company): array
    {
        $prequal = $this->getFromBuyer($id, $company);
        $invitations = $prequal->getInvitations();
        $applications = array();
        $participants = array();
        foreach ($prequal->getApplications() as $application) {
            $status = pb2bPrequalApplicationStatus::tryFromId((int) ($application['status'] ?? 0));
            if (!in_array($status, array(pb2bPrequalApplicationStatus::SUBMITTED, pb2bPrequalApplicationStatus::IN_REVIEW, pb2bPrequalApplicationStatus::DECIDED), true)) continue;
            $applications[] = $application;
            $company_id = (int) ($application['supplier_company_id'] ?? 0);
            if ($company_id > 0) $participants[$company_id] = true;
        }
        usort($applications, static function (array $a, array $b): int {
            $date_order = strcmp((string) ($b['submitted_at'] ?? ''), (string) ($a['submitted_at'] ?? ''));
            return $date_order ?: ((int) $b['id'] <=> (int) $a['id']);
        });

        $companies = array();
        $decisions = array();
        if ($applications) {
            $companies = (new pb2bCompanyCollection(array(
                'col' => array('id' => array('simile' => 'IN', 'value' => array_keys($participants))),
            )))->getCollection(array('key' => 'id', 'select' => array('id' => null, 'name' => null, 'inn' => null, 'phone' => null, 'jur_address' => null)));
            $application_ids = array_column($applications, 'id');
            $items = (new pb2bPrequalApplicationItemCollection(array(
                'col' => array('application_id' => array('simile' => 'IN', 'value' => $application_ids)),
            )))->getCollection(array('key' => false, 'select' => array('application_id' => null, 'decision' => null)));
            foreach ($items as $item) {
                if (!empty($item['decision'])) $decisions[(int) $item['application_id']][(int) $item['decision']] = true;
            }
        }

        return array(
            'prequal' => $prequal,
            'invitations' => $invitations,
            'applications' => $applications,
            'application_companies' => $companies,
            'application_decisions' => $decisions,
            'counts' => array(
                'invited' => count($invitations),
                'participants' => count($participants),
                'applications' => count($applications),
            ),
            'recent_applications' => array_slice($applications, 0, 2),
            'deadline' => pb2bHelper::resolveTenderDeadline((string) ($prequal->data['accept_end_datetime'] ?? ''), true),
            'can_edit' => pb2bPrequalPolicy::update($prequal, $company) && $prequal->canEdit(),
        );
    }

    public function createFromBuyer(pb2bCompany $company, pb2bPrequalDto $dto, waContact $actor): pb2bPrequal
    {
        if (!pb2bPrequalPolicy::create($company)) throw new waException('Создание предварительной квалификации недоступно', pb2bHttpStatus::FORBIDDEN);

        $prequal = new pb2bPrequal();
        $prequal->model->query('START TRANSACTION');
        try {
            $result = $prequal->save(array_merge($dto->toArray(), array(
                'title' => $dto->title ?? 'Предварительная квалификация',
                'procedure_code' => (new pb2bProcedureCodeService())->issue('prequalification'),
                'reviewer_id' => (int) $company->id,
                'contact_id' => $actor->getId(),
                'status' => pb2bPrequalStatus::DRAFT->id(),
            )));
            if (!empty($result['error'])) throw new waException((string) ($result['message'] ?? 'Не удалось создать ПК'), pb2bHttpStatus::BAD_REQUEST);

            if ($dto->has('documents')) $this->saveDocuments($prequal, $dto->documents);
            if ($dto->has('items')) $this->saveItems($prequal, $dto->items);

            if ($dto->has('invitations')) $this->saveInvitations($prequal, $dto->invitations, $actor);

            if ($dto->has('category_ids')) $this->saveCategories($prequal, $dto->category_ids);

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

        $prequal->model->query('START TRANSACTION');
        try {
            $result = $prequal->save(array_merge($prequal->data, $dto->toArray()));
            if (!empty($result['error'])) {
                throw new waException((string) ($result['message'] ?? 'Не удалось сохранить ПК'), pb2bHttpStatus::BAD_REQUEST);
            }

            if ($dto->has('invitations'))  $this->saveInvitations($prequal, $dto->invitations, $actor);
            if ($dto->has('category_ids')) $this->saveCategories($prequal, $dto->category_ids);
            if ($dto->has('documents')) $this->saveDocuments($prequal, $dto->documents);
            if ($dto->has('items')) $this->saveItems($prequal, $dto->items);

            $prequal->model->query('COMMIT');
        } catch (Throwable $e) {
            $prequal->model->query('ROLLBACK');
            throw $e;
        }

        return $prequal;
    }

    public function publishFromBuyer(int $id, pb2bCompany $company): pb2bPrequal
    {
        $prequal = $this->getPrequalWithAssert($id);
        if (!pb2bPrequalPolicy::update($prequal, $company)) throw new waException('Нет доступа к процедуре ПК', pb2bHttpStatus::FORBIDDEN);
        if (!$prequal->canEdit()) throw new waException('Опубликовать можно только черновик ПК', pb2bHttpStatus::CONFLICT);

        $data = $prequal->data;
        $items = $prequal->getItems();
        $documents = $prequal->getDocuments();
        $issues = array();
        if (trim((string) ($data['title'] ?? '')) === '') $issues[] = array('step' => 'basic', 'field' => 'title', 'message' => 'Укажите наименование процедуры');
        if ((int) ($data['validity_months'] ?? 0) < 1) $issues[] = array('step' => 'conditions', 'field' => 'validity_months', 'message' => 'Укажите срок действия квалификации');
        $end = trim((string) ($data['accept_end_datetime'] ?? ''));
        $timezone = new DateTimeZone('Europe/Moscow');
        $deadline = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $end, $timezone);
        if (!$deadline || $deadline->format('Y-m-d H:i:s') !== $end || $deadline <= new DateTimeImmutable('now', $timezone)) {
            $issues[] = array('step' => 'conditions', 'field' => 'accept_end_datetime', 'message' => 'Укажите будущий срок окончания подачи заявок');
        }
        if (!empty($data['is_private']) && !$prequal->getInvitations()) $issues[] = array('step' => 'access', 'field' => 'invitations', 'message' => 'Для закрытой процедуры добавьте хотя бы одного поставщика');
        if (!$items || array_filter($items, static fn($item) => trim((string) ($item['name'] ?? '')) === '')) {
            $issues[] = array('step' => 'items', 'field' => 'items', 'message' => 'Добавьте предметы квалификации и укажите их названия');
        }
        if ($issues) throw new pb2bValidationException('Заполните обязательные поля перед публикацией', $issues, pb2bHttpStatus::BAD_REQUEST);

        $links = array_filter(array_merge(array_column($items, 'file_link_id'), array_column($documents, 'file_link_id')));
        $this->assertDocumentLinks($prequal, array_values(array_unique($links)));
        $status_id = pb2bPrequalStatus::PUBLISHED->id();
        if ($status_id <= 0) throw new waException('Статус публикации ПК не настроен', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
        $result = $prequal->save(array_merge($prequal->data, array('status' => $status_id)));
        if (!empty($result['error'])) throw new waException((string) ($result['message'] ?? 'Не удалось опубликовать ПК'), pb2bHttpStatus::BAD_REQUEST);
        return $prequal;
    }

    private function saveItems(pb2bPrequal $prequal, array $rows): void
    {
        $links = array();
        $sort = 0;
        foreach ($rows as &$row) {
            if (!is_array($row)) throw new waException('Неверный предмет ПК', pb2bHttpStatus::BAD_REQUEST);
            $row = array_intersect_key($row, array_flip(array('id', 'name', 'qty', 'unit', 'comment', 'file_link_id')));
            foreach ($row as $value) {
                if (!is_scalar($value) && $value !== null) throw new waException('Неверное поле предмета ПК', pb2bHttpStatus::BAD_REQUEST);
            }
            $row['id'] = filter_var($row['id'] ?? 0, FILTER_VALIDATE_INT);
            $link_id = ($row['file_link_id'] ?? '') === '' ? 0 : $row['file_link_id'];
            $link_id = filter_var($link_id, FILTER_VALIDATE_INT);
            if ($row['id'] === false || $row['id'] < 0 || $link_id === false || $link_id < 0) throw new waException('Неверный ID предмета или файла', pb2bHttpStatus::BAD_REQUEST);
            $row['name'] = trim((string) ($row['name'] ?? ''));
            $row['qty'] = ($row['qty'] ?? '') === '' ? null : $row['qty'];
            $row['unit'] = ($row['unit'] ?? '') === '' ? null : trim((string) $row['unit']);
            $row['file_link_id'] = $link_id ?: null;
            $row['sort'] = $sort++;
            if ($link_id) $links[] = $link_id;
        }
        unset($row);
        $this->assertDocumentLinks($prequal, array_values(array_unique($links)));
        $current = array_column($prequal->getItems(), null, 'id');
        $seen = array();
        foreach ($rows as $row) {
            $id = $row['id'];
            if ($id > 0 && isset($seen[$id])) throw new waException('Предмет указан несколько раз', pb2bHttpStatus::BAD_REQUEST);
            $item = new pb2bPrequalItem($id ?: null);
            if ($id > 0) {
                if (!$item->id) throw new waException('Предмет ПК не найден', pb2bHttpStatus::NOT_FOUND);
                if ((int) $item->data['prequal_id'] !== (int) $prequal->id) throw new waException('Предмет принадлежит другой ПК', pb2bHttpStatus::FORBIDDEN);
                $seen[$id] = true;
            }
            unset($row['id']);
            $row['prequal_id'] = (int) $prequal->id;
            $result = $item->save($row);
            if (!empty($result['error'])) throw new waException((string) ($result['message'] ?? 'Не удалось сохранить предмет ПК'), pb2bHttpStatus::BAD_REQUEST);
            if (!$item->id) throw new waException('Не удалось сохранить предмет ПК', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
            unset($current[(int) $item->id]);
        }
        foreach ($current as $id => $row) {
            $result = (new pb2bPrequalItem((int) $id))->delete();
            if (!empty($result['error'])) throw new waException((string) ($result['message'] ?? 'Не удалось убрать предмет ПК'), pb2bHttpStatus::BAD_REQUEST);
        }
    }

    private function saveDocuments(pb2bPrequal $prequal, array $rows): void
    {
        $links = array();
        $sort = 0;
        foreach ($rows as &$row) {
            if (!is_array($row)) throw new waException('Неверный документ ПК', pb2bHttpStatus::BAD_REQUEST);
            $row = array_intersect_key($row, array_flip(array('id', 'kind', 'name', 'description', 'is_required', 'file_link_id')));
            foreach ($row as $value) {
                if (!is_scalar($value) && $value !== null) throw new waException('Неверное поле документа ПК', pb2bHttpStatus::BAD_REQUEST);
            }
            $row['id'] = filter_var($row['id'] ?? 0, FILTER_VALIDATE_INT);
            $link_id = ($row['file_link_id'] ?? '') === '' ? 0 : $row['file_link_id'];
            $link_id = filter_var($link_id, FILTER_VALIDATE_INT);
            if ($row['id'] === false || $row['id'] < 0 || $link_id === false || $link_id < 0) throw new waException('Неверный ID документа или файла', pb2bHttpStatus::BAD_REQUEST);
            $row['file_link_id'] = $link_id ?: null;
            $row['sort'] = $sort++;
            if ($link_id) $links[] = $link_id;
        }
        unset($row);
        $this->assertDocumentLinks($prequal, array_values(array_unique($links)));
        $current = array_column($prequal->getDocuments(), null, 'id');
        $seen = array();
        foreach ($rows as $row) {
            $id = $row['id'];
            if ($id > 0 && isset($seen[$id])) throw new waException('Документ указан несколько раз', pb2bHttpStatus::BAD_REQUEST);
            $document = new pb2bPrequalDocument($id ?: null);
            if ($id > 0) {
                if (!$document->id) throw new waException('Документ ПК не найден', pb2bHttpStatus::NOT_FOUND);
                if ((int) $document->data['prequal_id'] !== (int) $prequal->id) throw new waException('Документ принадлежит другой ПК', pb2bHttpStatus::FORBIDDEN);
                $seen[$id] = true;
            }
            unset($row['id']);
            $row['prequal_id'] = (int) $prequal->id;
            $result = $document->save($row);
            if (!empty($result['error'])) throw new waException((string) ($result['message'] ?? 'Не удалось сохранить документ ПК'), pb2bHttpStatus::BAD_REQUEST);
            unset($current[(int) $document->id]);
        }
        foreach ($current as $id => $row) {
            $result = (new pb2bPrequalDocument((int) $id))->delete();
            if (!empty($result['error'])) throw new waException((string) ($result['message'] ?? 'Не удалось убрать документ ПК'), pb2bHttpStatus::BAD_REQUEST);
        }
    }

    private function saveInvitations(pb2bPrequal $prequal, array $ids, waContact $actor): void
    {
        foreach ($ids as &$id) {
            if (!is_scalar($id) || is_bool($id)) throw new waException('Неверный ID приглашённой компании', pb2bHttpStatus::BAD_REQUEST);
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if ($id === false || $id <= 0) throw new waException('Неверный ID приглашённой компании', pb2bHttpStatus::BAD_REQUEST);
        }
        unset($id);
        $ids = array_values(array_unique($ids));
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
    private function saveCategories(pb2bPrequal $prequal, array $ids): void
    {
        $category_ids = array();
        foreach ($ids as $value) {
            if (!is_scalar($value) || is_bool($value)) throw new waException('Неверный ID категории ПК', pb2bHttpStatus::BAD_REQUEST);
            $id = filter_var($value, FILTER_VALIDATE_INT);
            if ($id === false || $id <= 0) throw new waException('Неверный ID категории ПК', pb2bHttpStatus::BAD_REQUEST);
            $category_ids[$id] = $id;
        }
        $category_ids = array_values($category_ids);
        if ($category_ids) {
            $categories = new pb2bCategoryCollection();
            $categories->model->setWhere(array('id' => array('simile' => 'IN', 'value' => $category_ids)));
            $found = $categories->getCollection(array('key' => 'id', 'select' => array('id' => null)));
            if (array_diff($category_ids, array_keys($found))) throw new waException('Категория не найдена', pb2bHttpStatus::NOT_FOUND);
        }

        $types = (array) pb2bWaproHelper::getConfigOption('tender_classifier_types', 'code');
        $category_type = (int) $types['category']['id'];
        $remaining = array();
        foreach ($prequal->getClassifiers() as $row) {
            if ((int) $row['row_kind'] !== pb2bPrequalClassifier::CLASSIFIER || (int) $row['classifier_type'] !== $category_type) continue;
            $remaining[(int) $row['classifier_id']][] = (int) $row['id'];
        }
        foreach ($category_ids as $sort => $category_id) {
            $id = empty($remaining[$category_id]) ? null : array_shift($remaining[$category_id]);
            $row = new pb2bPrequalClassifier($id);
            $result = $row->save(array(
                'prequal_id' => (int) $prequal->id,
                'row_kind' => pb2bPrequalClassifier::CLASSIFIER,
                'classifier_type' => $category_type,
                'classifier_id' => $category_id,
                'tag_id' => null,
                'sort' => $sort,
            ));
            if (!empty($result['error'])) throw new waException((string) ($result['message'] ?? 'Не удалось сохранить категорию ПК'), pb2bHttpStatus::BAD_REQUEST);
        }
        foreach ($remaining as $row_ids) {
            foreach ($row_ids as $id) {
                $result = (new pb2bPrequalClassifier($id))->delete();
                if (!empty($result['error'])) throw new waException((string) ($result['message'] ?? 'Не удалось убрать категорию ПК'), pb2bHttpStatus::BAD_REQUEST);
            }
        }
    }

    private function assertDocumentLinks(pb2bPrequal $prequal, array $ids): array
    {
        $links = (new pb2bFileLinkCollection())->getByIds($ids);
        foreach ($ids as $id) {
            $link = $links[$id] ?? null;
            if (!$link || empty($link['stored_file_id'])) throw new waException('Файл документации не найден', pb2bHttpStatus::NOT_FOUND);
            if ((int) $link['owner_company_id'] !== (int) $prequal->data['reviewer_id']) throw new waException('Файл не принадлежит организатору ПК', pb2bHttpStatus::FORBIDDEN);
            $extra = json_decode((string) ($link['extra'] ?? ''), true);
            if (($extra['purpose'] ?? '') !== 'prequal_documentation' || (int) ($extra['prequal_id'] ?? 0) !== (int) $prequal->id) throw new waException('Файл загружен для другой процедуры', pb2bHttpStatus::FORBIDDEN);
            if (!pb2bStorage::disk($link['storage_disk'])->exists($link['storage_path'])) throw new waException('Файл отсутствует в хранилище', pb2bHttpStatus::NOT_FOUND);
        }
        return $links;
    }

    public function uploadFileFromBuyer(int $id, pb2bCompany $company, waRequestFile $upload_file): array
    {
        $prequal = $this->getPrequalWithAssert($id);
        if (!pb2bPrequalPolicy::update($prequal, $company)) throw new waException('Нет доступа к процедуре ПК', pb2bHttpStatus::FORBIDDEN);
        if (!$prequal->canEdit()) throw new waException('Изменять можно только черновик ПК', pb2bHttpStatus::CONFLICT);
        $storage = new pb2bFileStorageService();
        $upload = $storage->validateDocumentUpload($upload_file);
        $link = $storage->saveFileAndCreateLink($upload_file, 'prequal', (int) $company->id, array(
            'purpose' => 'prequal_documentation', 'prequal_id' => (int) $prequal->id,
        ));
        return array('file_link_id' => (int) $link->id, 'filename' => (string) $link->data['filename'], 'size' => $upload['size'], 'ext' => $upload['ext']);
    }

    public function downloadDocumentsFromSupplier(int $id, pb2bCompany $company): void
    {
        $prequal = $this->getFromSupplier($id, $company);
        $documents = $prequal->getDocuments(pb2bPrequalDocument::KIND_TECH_SPEC);
        $ids = array_values(array_filter(array_map('intval', array_column($documents, 'file_link_id'))));
        if (!$ids) throw new waException('Документация ПК не найдена', pb2bHttpStatus::NOT_FOUND);

        $links = $this->assertDocumentLinks($prequal, array_values(array_unique($ids)));
        $zip_path = tempnam(sys_get_temp_dir(), 'pb2b_docs_');
        if ($zip_path === false) throw new waException('Не удалось создать архив документов', pb2bHttpStatus::INTERNAL_SERVER_ERROR);

        try {
            $zip = new ZipArchive();
            if ($zip->open($zip_path, ZipArchive::OVERWRITE) !== true) {
                throw new waException('Не удалось создать архив документов', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
            }
            $names = array();
            foreach ($ids as $file_link_id) {
                $link = $links[$file_link_id];
                $filename = basename(str_replace('\\', '/', $link['filename']));
                $name = $filename;
                $index = 2;
                while (isset($names[$name])) {
                    $name = $index++ . '_' . $filename;
                }
                $names[$name] = true;
                $path = pb2bStorage::disk($link['storage_disk'])->path($link['storage_path']);
                if (!$zip->addFile($path, $name)) {
                    $zip->close();
                    throw new waException('Не удалось добавить документ в архив', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
                }
            }
            if (!$zip->close()) throw new waException('Не удалось сохранить архив документов', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
            waFiles::readFile($zip_path, 'Документация предварительной квалификации ' . $id . '.zip', false);
        } finally {
            if (is_file($zip_path)) unlink($zip_path);
        }
    }

    public function downloadDocumentFromBuyer(int $id, int $file_link_id, pb2bCompany $company): void
    {
        $prequal = $this->getFromBuyer($id, $company);
        $this->downloadDocument($prequal, $file_link_id);
    }

    public function downloadDocumentFromSupplier(int $id, int $file_link_id, pb2bCompany $company): void
    {
        $prequal = $this->getFromSupplier($id, $company);
        $this->downloadDocument($prequal, $file_link_id);
    }

    private function downloadDocument(pb2bPrequal $prequal, int $file_link_id): void
    {
        $files = array_merge(array_column($prequal->getDocuments(), 'file_link_id'), array_column($prequal->getItems(), 'file_link_id'));
        if ($file_link_id <= 0 || !in_array($file_link_id, array_map('intval', $files), true)) throw new waException('Файл ПК не найден', pb2bHttpStatus::NOT_FOUND);
        $link = new pb2bFileLink($file_link_id);
        $file = $link->id ? $link->getFile() : null;
        if (!$file) throw new waException('Файл документации не найден', pb2bHttpStatus::NOT_FOUND);
        $disk = pb2bStorage::disk($file->data['storage_disk']);
        if (!$disk->exists($file->data['storage_path'])) throw new waException('Файл отсутствует в хранилище', pb2bHttpStatus::NOT_FOUND);
        $path = $disk->path($file->data['storage_path']);
        waFiles::readFile($path, basename(str_replace('\\', '/', $link->data['filename'])), false);
    }
}
