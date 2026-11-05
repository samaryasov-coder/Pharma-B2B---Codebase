<?php

class pb2bTenderService extends pb2bBaseService
{
    protected pb2bTenderStatusService $statusService;
    protected pb2bProcedureCodeService $procedureCodeService;

    public function __construct(
        ?pb2bTenderStatusService $statusService = null,
        ?pb2bProcedureCodeService $procedureCodeService = null
    ) {
        $this->statusService = $statusService ?? new pb2bTenderStatusService();
        $this->procedureCodeService = $procedureCodeService ?? new pb2bProcedureCodeService();
    }

    private function getBuyerCompanyWithAssert(int $company_id): pb2bCompany
    {
        $company = new pb2bCompany($company_id);
        if (!$company->id || !$company->isBuyer()) throw new waException('Не найдена компания-покупатель', pb2bHttpStatus::NOT_FOUND);
        return $company;
    }

    private function getTenderWithAssert(int $tender_id): pb2bTender
    {
        if ($tender_id <= 0) throw new waException('Тендер не найден', pb2bHttpStatus::NOT_FOUND);
        $tender = new pb2bTender($tender_id);
        if (!$tender->id) throw new waException('Тендер не найден', pb2bHttpStatus::NOT_FOUND);
        return $tender;
    }

    public function listFromBuyer(pb2bCompany $company, array $filters = array()): array
    {
        $rows = (new pb2bTenderCollection())->getBuyerList((int) $company->id, $filters);
        if (!$rows) return array();

        $ids = array_column($rows, 'id');
        $invitation_counts = (new pb2bTenderInvitationCollection())->countsByTenderIds($ids);
        $application_counts = (new pb2bTenderApplicationCollection())->applicationCountsByTenderIds($ids);

        foreach ($rows as &$row) {
            $id = (int) $row['id'];
            $counts = $application_counts[$id] ?? array();
            $row['invited_count'] = $invitation_counts[$id] ?? 0;
            $row['participants_count'] = array_sum($counts);
            $row['proposals_count'] = $counts[pb2bTenderApplication::STATUS_SUBMITTED] ?? 0;
            $row['withdrawn_count'] = $counts[pb2bTenderApplication::STATUS_WITHDRAWN] ?? 0;
        }
        unset($row);

        return $rows;
    }

    public function getTenderFromBuyer(int $tender_id, pb2bCompany $company): pb2bTender
    {
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::view($tender, $company)) throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);

        // меняет состояние тендера. Для ЗЦ в статусе «Опубликован», если время начала уже наступило или не задано, переводит его в «Приём заявок»
        // нужно перенести на cli
        $this->statusService->ensureReceptionOpen($tender);

        return $tender;
    }

    public function getBuyerOverview(int $tender_id, pb2bCompany $company): array
    {
        $tender = $this->getTenderFromBuyer($tender_id, $company);
        $counts_by_tender = (new pb2bTenderApplicationCollection())->applicationCountsByTenderIds(array((int) $tender->id));
        $application_counts = $counts_by_tender[(int) $tender->id];
        $recent_applications = (new pb2bTenderApplicationCollection(
            'tender_id=' . (int) $tender->id . '&status=' . pb2bTenderApplication::STATUS_SUBMITTED
        ))->getCollection(array(
            'key' => false,
            'select' => array('id' => null, 'submitted_at' => null),
            'order' => array('submitted_at' => 'DESC', 'id' => 'DESC'),
            'limit' => 2,
        ));

        return array(
            'tender' => $tender,
            'organizer' => $company,
            'tender_status' => pb2bWaproHelper::getConfigOption('tender_statuses', 'id')[(int) $tender->data['status']] ?? array(),
            'tender_type' => pb2bWaproHelper::getConfigOption('tender_types', 'id')[(int) $tender->data['type']] ?? array(),
            'review_started' => $tender->isReviewStarted(),
            'deadline' => pb2bHelper::resolveTenderDeadline((string) ($tender->data['end_at'] ?? ''), true),
            'recent_applications' => $recent_applications,
            'counts' => array(
                'invited' => count($tender->getInvitations()),
                'participants' => array_sum($application_counts),
                'proposals' => $application_counts[pb2bTenderApplication::STATUS_SUBMITTED],
            ),
            'permissions' => array(
                'can_edit' => pb2bTenderPolicy::update($tender, $company) && $tender->canEdit(),
                'can_start_review' => pb2bTenderPolicy::startReview($tender, $company) && $tender->canStartReview(),
                'can_select_winner' => pb2bTenderPolicy::selectWinner($tender, $company) && $tender->canSelectWinner(),
            ),
        );
    }

    public function getSupplierOverview(int $tender_id, pb2bCompany $company): array
    {
        $tender = $this->getTenderWithAssert($tender_id);
        $has_invitation = !empty($tender->data['is_private'])
            && (new pb2bTenderInvitationCollection())->existsForTenderAndCompany((int) $tender->id, (int) $company->id);
        if (!pb2bTenderPolicy::viewAsSupplier($tender, $company, $has_invitation)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->statusService->ensureReceptionOpen($tender);

        $organizer = new pb2bCompany((int) $tender->data['organizer_company_id']);
        $applications = (new pb2bTenderApplicationCollection(
            'tender_id=' . (int) $tender->id . '&supplier_company_id=' . (int) $company->id
        ))->getCollection(array('key' => false, 'limit' => 1));
        $application = $applications[0] ?? null;
        $admission_code = (string) ($application['admission_status'] ?? 'pending');
        $items = $tender->getItems();
        $classifiers = $tender->getClassifiers();
        $documents = $tender->getDocuments();
        $procurement_documents = array_values(array_filter($documents, static function (array $document): bool {
            return $document['kind'] === 'tech_spec' && (int) $document['file_link_id'] > 0;
        }));
        if (!empty($tender->data['hide_initial_price'])) {
            $tender = clone $tender;
            $tender->setField('budget', null);
            foreach ($items as &$item) {
                unset($item['max_price_no_vat']);
            }
            unset($item);
        }

        return array(
            'tender' => $tender,
            'organizer' => $organizer->id ? $organizer : null,
            'tender_status' => pb2bWaproHelper::getConfigOption('tender_statuses', 'id')[(int) $tender->data['status']] ?? array(),
            'tender_type' => pb2bWaproHelper::getConfigOption('tender_types', 'id')[(int) $tender->data['type']] ?? array(),
            'items' => $items,
            'documents' => $documents,
            'procurement_documents' => $procurement_documents,
            'classifiers' => $classifiers,
            'criteria' => $tender->getCriteria(),
            'application' => $application,
            'summary' => pb2bTenderSummaryHelper::supplierNotice($items, $classifiers),
            'deadline' => pb2bHelper::resolveTenderDeadline((string) ($tender->data['end_at'] ?? ''), false),
            'counts' => array(
                'items' => count($items),
                'documents' => count(array_filter($documents, static function (array $document): bool {
                    return $document['kind'] === 'requirement';
                })),
            ),
            'participation' => array(
                'url' => '/cabinet/supplier/tender/' . (int) $tender->id . '/participation/',
                'closed' => $this->isSupplierParticipationClosed($tender),
                'requires_prequalification' => (int) ($tender->data['past_prequal_tender_id'] ?? 0) > 0,
                'tender_status_code' => pb2bTenderStatus::tryFromId((int) $tender->data['status'])?->value ?? '',
                'admission_status' => pb2bWaproHelper::getConfigOption('tender_application_admission_statuses', 'code')[$admission_code] ?? array(),
            ),
        );
    }

    public function downloadFilesForSupplier(int $tender_id, pb2bCompany $company, int $record_id = 0, string $scope = 'procurement'): void
    {
        if ($record_id < 0 || !in_array($scope, array('procurement', 'template', 'item'), true)
            || ($scope !== 'procurement' && $record_id === 0)) {
            throw new waException('Некорректный документ', pb2bHttpStatus::BAD_REQUEST);
        }
        $overview = $this->getSupplierOverview($tender_id, $company);
        $documents = $overview['procurement_documents'];
        if ($scope === 'template') {
            $documents = array_filter($overview['documents'], static function (array $document): bool {
                return $document['kind'] === 'requirement' && (int) $document['file_link_id'] > 0;
            });
        } elseif ($scope === 'item') {
            $documents = $overview['items'];
        }
        if ($record_id > 0) {
            $documents = array_values(array_filter($documents, static function (array $document) use ($record_id): bool {
                return (int) $document['id'] === $record_id && (int) $document['file_link_id'] > 0;
            }));
        }
        if (!$documents) {
            throw new waException('Закупочная документация не найдена', pb2bHttpStatus::NOT_FOUND);
        }
        $links = (new pb2bFileLinkCollection())->getByIds(array_column($documents, 'file_link_id'));
        $files = array();
        foreach ($documents as $document) {
            $link = $links[(int) $document['file_link_id']] ?? null;
            if (!$link || empty($link['stored_file_id'])
                || (int) $link['owner_company_id'] !== (int) $overview['tender']->data['organizer_company_id']) {
                throw new waException('Файл документа не найден', pb2bHttpStatus::NOT_FOUND);
            }
            $path = pb2bStorage::disk($link['storage_disk'])->path($link['storage_path']);
            if (!is_file($path)) {
                throw new waException('Файл документа отсутствует в хранилище', pb2bHttpStatus::NOT_FOUND);
            }
            $files[] = array('path' => $path, 'name' => basename(str_replace('\\', '/', $link['filename'])));
        }
        if ($record_id > 0) {
            waFiles::readFile($files[0]['path'], $files[0]['name'], false);
            return;
        }
        $zip_path = tempnam(sys_get_temp_dir(), 'pb2b_docs_');
        if ($zip_path === false) {
            throw new waException('Не удалось создать архив документов', 500);
        }
        try {
            $zip = new ZipArchive();
            if ($zip->open($zip_path, ZipArchive::OVERWRITE) !== true) {
                throw new waException('Не удалось создать архив документов', 500);
            }
            $names = array();
            foreach ($files as $file) {
                $name = $file['name'];
                $index = 2;
                while (isset($names[$name])) {
                    $name = $index++ . '_' . $file['name'];
                }
                $names[$name] = true;
                if (!$zip->addFile($file['path'], $name)) {
                    $zip->close();
                    throw new waException('Не удалось добавить документ в архив', 500);
                }
            }
            if (!$zip->close()) {
                throw new waException('Не удалось сохранить архив документов', 500);
            }
            waFiles::readFile($zip_path, 'Закупочная документация ' . $tender_id . '.zip', false);
        } finally {
            if (is_file($zip_path)) {
                unlink($zip_path);
            }
        }
    }

    // выводит тендер с его доп данными
    public function getDetailFromBuyer(int $tender_id, pb2bCompany $company): array
    {
        $tender = $this->getTenderFromBuyer($tender_id, $company);
        $counts = (new pb2bTenderApplicationCollection())->applicationCountsByTenderIds(array((int) $tender->id));

        return array(
            'tender' => $tender,
            'application_counts' => $counts[(int) $tender->id],
            'classifiers' => $tender->getClassifiers(),
            'items' => $tender->getItems(),
            'documents' => $tender->getDocuments(),
            'criteria' => $tender->getCriteria(),
            'invitations' => $tender->getInvitations(),
        );
    }

    // создание тендера 
    public function createFromBuyer(int $organizer_company_id, pb2bTenderDto $dto, waContact $actor): pb2bTender
    {
        $company = $this->getBuyerCompanyWithAssert($organizer_company_id);
        if (!pb2bTenderPolicy::create($company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }

        if (!$dto->has('type') || (int) $dto->type <= 0) {
            throw new waException('Не указан тип процедуры', pb2bHttpStatus::BAD_REQUEST);
        }

        $type = pb2bTenderType::tryFromId($dto->type);
        if ($type !== pb2bTenderType::PRICE_REQUEST) {
            throw new waException(
                'В этом срезе доступен только запрос цен',
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        $type_code = $type->value;
        $title = $dto->has('title') ? trim((string) $dto->title) : '';

        $number = $this->procedureCodeService->issue($type_code);

        $tender = new pb2bTender();
        if ($tender->findDuplicateNumber($number, (int) $company->id)) {
            throw new waException('Реестровый номер уже используется', pb2bHttpStatus::BAD_REQUEST);
        }

        $save_result = $tender->save([
            'type' => (int) $dto->type,
            'title' => $title,
            'number' => $number,
            'organizer_company_id' => (int) $company->id,
            'responsible_contact_id' => $actor->getId(),
            'status' => pb2bTenderStatus::DRAFT->id(),
        ]);
        if (!empty($save_result['error'])) {
            throw new waException(
                (string) ($save_result['message'] ?? 'Не удалось создать тендер'),
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        if ($dto->hasClassifiers()) $this->saveClassifiers($tender, $dto->classifiers);

        return $tender;
    }
    
    // обновление тендера 
    public function updateFromBuyer(int $tender_id, int $company_id, pb2bTenderDto $dto, waContact $actor): pb2bTender
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);

        if (!pb2bTenderPolicy::update($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }

        if (!$tender->canEdit()) {
            throw new waException(
                'Редактирование недоступно: срок приёма заявок истёк, уже подана заявка или статус не позволяет правку',
                pb2bHttpStatus::CONFLICT
            );
        }

        if ($dto->hasItems()) {
            $this->assertTenderFileLinks($tender, array_column($dto->items, 'file_link_id'));
        }
        if ($dto->hasDocuments()) {
            $this->assertTenderFileLinks($tender, array_column($dto->documents, 'file_link_id'));
        }

        $patch = $dto->toSaveArray();
        unset($patch['number']);
        if (
            array_key_exists('end_at', $patch)
            && pb2bTender::isReceptionDatetimePassed(
                $patch['end_at'] !== null ? (string) $patch['end_at'] : null
            )
        ) {
            throw new pb2bValidationException(
                'Конечный срок подачи заявок должен быть позже текущего времени',
                array(array(
                    'step' => 'purchase_params',
                    'field' => 'end_at',
                    'message' => 'Выберите время позже текущего',
                )),
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        if (array_key_exists('title', $patch)) {
            $patch['title'] = trim((string) $patch['title']);
            if ($patch['title'] === ''
                && pb2bTenderStatus::tryFromId((int) $tender->data['status']) !== pb2bTenderStatus::DRAFT) {
                throw new pb2bValidationException(
                    'Укажите наименование извещения',
                    array(array(
                        'step' => 'basic',
                        'field' => 'title',
                        'message' => 'Укажите наименование извещения',
                    )),
                    pb2bHttpStatus::BAD_REQUEST
                );
            }
        }

        if ($patch) {
            $save_data = $tender->data;
            unset($save_data['create_datetime'], $save_data['update_datetime']);
            foreach ($patch as $key => $value) {
                $save_data[$key] = $value;
            }
            $save_data['type'] = (int) ($tender->data['type'] ?? 0);
            $save_data['organizer_company_id'] = (int) ($tender->data['organizer_company_id'] ?? 0);
            $save_data['status'] = (int) ($tender->data['status'] ?? 0);
            $save_data['responsible_contact_id'] = (int) ($tender->data['responsible_contact_id'] ?? 0);

            $save_result = $tender->save($save_data);
            if (!empty($save_result['error'])) {
                throw new waException(
                    (string) ($save_result['message'] ?? 'Не удалось сохранить тендер'),
                    pb2bHttpStatus::BAD_REQUEST
                );
            }
        }

        if ($dto->hasItems()) $this->saveItems($tender, $dto->items);
        if ($dto->hasDocuments()) $this->saveDocuments($tender, $dto->documents);
        if ($dto->hasClassifiers()) $this->saveClassifiers($tender, $dto->classifiers);

        if ($dto->hasInvitations()) $this->saveInvitations($tender, $dto->invitations, $actor);
        if ($dto->hasCriteria()) $this->saveCriteria($tender, $dto->criteria);

        

        return $tender;
    }

    // функции для сохранения тендера
    private function saveInvitations(pb2bTender $tender, array $supplier_company_ids, waContact $actor): void
    {
        if ($supplier_company_ids) {
            $collection = new pb2bCompanyCollection(array(
                'col' => array('id' => array('simile' => 'IN', 'value' => $supplier_company_ids)),
            ));
            $companies = $collection->getCollection(array(
                'key' => 'id',
                'select' => array('id' => null, 'supplier' => null),
            ));
            foreach ($supplier_company_ids as $supplier_id) {
                if (!isset($companies[$supplier_id])) {
                    throw new waException('Компания-поставщик не найдена', pb2bHttpStatus::NOT_FOUND);
                }
                if (empty($companies[$supplier_id]['supplier'])) {
                    throw new waException('В приглашения можно добавлять только компании-поставщиков', pb2bHttpStatus::BAD_REQUEST);
                }
            }
        }
        $model = new pb2bTenderInvitationModel();
        $model->query('START TRANSACTION');
        try {
            $remaining = array();
            foreach ($tender->getInvitations() as $row) {
                $remaining[(int) $row['supplier_company_id']][] = (int) $row['id'];
            }
            foreach ($supplier_company_ids as $supplier_id) {
                if (!empty($remaining[$supplier_id])) {
                    array_shift($remaining[$supplier_id]);
                    continue;
                }
                $invitation = new pb2bTenderInvitation();
                $result = $invitation->save(array(
                    'tender_id' => (int) $tender->id,
                    'supplier_company_id' => $supplier_id,
                    'invited_by_contact_id' => $actor->getId(),
                    'status' => pb2bTenderInvitation::STATUS_INVITED,
                ));
                if (!empty($result['error'])) {
                    throw new waException(
                        (string) ($result['message'] ?? 'Не удалось сохранить приглашение'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
                if (!$invitation->id) {
                    throw new waException('Не удалось сохранить приглашение', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
                }
            }
            foreach ($remaining as $invitation_ids) {
                foreach ($invitation_ids as $id) {
                    $result = (new pb2bTenderInvitation($id))->delete();
                    if (!empty($result['error'])) {
                        throw new waException(
                            (string) ($result['message'] ?? 'Не удалось удалить приглашение'),
                            pb2bHttpStatus::BAD_REQUEST
                        );
                    }
                }
            }
            $model->query('COMMIT');
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }

    }

    private function saveClassifiers(pb2bTender $tender, array $rows): void
    {
        $model = new pb2bTenderClassifierModel();
        $model->query('START TRANSACTION');
        try {
            $remaining = array();
            foreach ($tender->getClassifiers() as $row) {
                $key = $row['classifier_type'] . ':' . $row['classifier_id'];
                $remaining[$key][] = (int) $row['id'];
            }
            $seen = array();
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    throw new waException('Неверный формат классификатора', pb2bHttpStatus::BAD_REQUEST);
                }
                $type_id = filter_var($row['classifier_type'] ?? 0, FILTER_VALIDATE_INT);
                $node_id = filter_var($row['classifier_id'] ?? 0, FILTER_VALIDATE_INT);
                if ($type_id === false || $type_id <= 0 || $node_id === false || $node_id <= 0) {
                    throw new waException('Неверный тип или узел классификатора', pb2bHttpStatus::BAD_REQUEST);
                }
                $key = $type_id . ':' . $node_id;
                if (isset($seen[$key])) {
                    throw new waException('Классификатор указан несколько раз', pb2bHttpStatus::BAD_REQUEST);
                }
                $seen[$key] = true;
                $id = empty($remaining[$key]) ? null : array_shift($remaining[$key]);
                $classifier = new pb2bTenderClassifier($id);
                $result = $classifier->save(array(
                    'tender_id' => (int) $tender->id,
                    'classifier_type' => $type_id,
                    'classifier_id' => $node_id,
                ));
                if (!empty($result['error'])) {
                    throw new waException(
                        (string) ($result['message'] ?? 'Не удалось сохранить классификатор'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
                if (!$classifier->id) {
                    throw new waException('Не удалось сохранить классификатор', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
                }
            }
            foreach ($remaining as $ids) {
                foreach ($ids as $id) {
                    $result = (new pb2bTenderClassifier($id))->delete();
                    if (!empty($result['error'])) {
                        throw new waException(
                            (string) ($result['message'] ?? 'Не удалось удалить классификатор'),
                            pb2bHttpStatus::BAD_REQUEST
                        );
                    }
                }
            }
            $model->query('COMMIT');
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }

    private function saveItems(pb2bTender $tender, array $rows): void
    {
        $model = new pb2bTenderItemModel();
        $model->query('START TRANSACTION');
        try {
            $remaining = array_fill_keys(array_column($tender->getItems(), 'id'), true);
            $seen = array();
            foreach ($rows as $row) {
                $id = (int) $row['id'];
                if ($id > 0 && isset($seen[$id])) {
                    throw new waException('Позиция указана несколько раз', pb2bHttpStatus::BAD_REQUEST);
                }
                $item = new pb2bTenderItem($id > 0 ? $id : null);
                if ($id > 0) {
                    if (!$item->id) {
                        throw new waException('Позиция тендера не найдена', pb2bHttpStatus::NOT_FOUND);
                    }
                    if ((int) $item->data['tender_id'] !== (int) $tender->id) {
                        throw new waException('Позиция не принадлежит тендеру', pb2bHttpStatus::FORBIDDEN);
                    }
                    $seen[$id] = true;
                }

                unset($row['id']);
                $row['tender_id'] = (int) $tender->id;
                $result = $item->save($row);
                if (!empty($result['error'])) {
                    throw new waException(
                        (string) ($result['message'] ?? 'Не удалось сохранить позицию'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
                if (!$item->id) {
                    throw new waException('Не удалось сохранить позицию', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
                }
                unset($remaining[(int) $item->id]);
            }

            foreach (array_keys($remaining) as $id) {
                $item = new pb2bTenderItem((int) $id);
                $result = $item->delete();
                if (!empty($result['error'])) {
                    throw new waException(
                        (string) ($result['message'] ?? 'Не удалось удалить позицию'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
            }
            $model->query('COMMIT');
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }

    private function saveCriteria(pb2bTender $tender, array $rows): void
    {
        $model = new pb2bTenderCriterionModel();
        $model->query('START TRANSACTION');
        try {
            $remaining = array_fill_keys(array_column($tender->getCriteria(), 'id'), true);
            $seen = array();
            foreach ($rows as $row) {
                $id = (int) $row['id'];
                if ($id > 0 && isset($seen[$id])) {
                    throw new waException('Критерий указан несколько раз', pb2bHttpStatus::BAD_REQUEST);
                }
                $criterion = new pb2bTenderCriterion($id > 0 ? $id : null);
                if ($id > 0) {
                    if (!$criterion->id) {
                        throw new waException('Критерий тендера не найден', pb2bHttpStatus::NOT_FOUND);
                    }
                    if ((int) $criterion->data['tender_id'] !== (int) $tender->id) {
                        throw new waException('Критерий не принадлежит тендеру', pb2bHttpStatus::FORBIDDEN);
                    }
                    $seen[$id] = true;
                }

                unset($row['id']);
                $row['tender_id'] = (int) $tender->id;
                $result = $criterion->save($row);
                if (!empty($result['error'])) {
                    throw new waException(
                        (string) ($result['message'] ?? 'Не удалось сохранить критерий'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
                if (!$criterion->id) {
                    throw new waException('Не удалось сохранить критерий', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
                }
                unset($remaining[(int) $criterion->id]);
            }

            foreach (array_keys($remaining) as $id) {
                $criterion = new pb2bTenderCriterion((int) $id);
                $result = $criterion->delete();
                if (!empty($result['error'])) {
                    throw new waException(
                        (string) ($result['message'] ?? 'Не удалось удалить критерий'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
            }
            $model->query('COMMIT');
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }

    private function saveDocuments(pb2bTender $tender, array $rows): void
    {
        $model = new pb2bTenderDocumentModel();
        $model->query('START TRANSACTION');
        try {
            $remaining = array_fill_keys(array_column($tender->getDocuments(), 'id'), true);
            $seen = array();
            foreach ($rows as $row) {
                $id = (int) $row['id'];
                if ($id > 0 && isset($seen[$id])) {
                    throw new waException('Документ указан несколько раз', pb2bHttpStatus::BAD_REQUEST);
                }
                $document = new pb2bTenderDocument($id > 0 ? $id : null);
                if ($id > 0) {
                    if (!$document->id) {
                        throw new waException('Документ тендера не найден', pb2bHttpStatus::NOT_FOUND);
                    }
                    if ((int) $document->data['tender_id'] !== (int) $tender->id) {
                        throw new waException('Документ не принадлежит тендеру', pb2bHttpStatus::FORBIDDEN);
                    }
                    $seen[$id] = true;
                }

                unset($row['id']);
                $row['tender_id'] = (int) $tender->id;
                $result = $document->save($row);
                if (!empty($result['error'])) {
                    throw new waException(
                        (string) ($result['message'] ?? 'Не удалось сохранить документ'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
                if (!$document->id) {
                    throw new waException('Не удалось сохранить документ', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
                }
                unset($remaining[(int) $document->id]);
            }

            foreach (array_keys($remaining) as $id) {
                $document = new pb2bTenderDocument((int) $id);
                $result = $document->delete();
                if (!empty($result['error'])) {
                    throw new waException(
                        (string) ($result['message'] ?? 'Не удалось удалить документ'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
            }
            $model->query('COMMIT');
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }
    
    public function uploadFileFromBuyer(int $tender_id, int $company_id, waRequestFile $upload_file): array
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::uploadFile($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        if (!$tender->canEdit()) {
            throw new waException(
                'Редактирование недоступно: срок приёма заявок истёк, уже подана заявка или статус не позволяет правку',
                pb2bHttpStatus::CONFLICT
            );
        }

        $storage = new pb2bFileStorageService();
        $upload = $storage->validateDocumentUpload($upload_file);
        $ext = $upload['ext'];
        $size = $upload['size'];
        $file_link = $storage->saveFileAndCreateLink(
            $upload_file,
            'tenders',
            (int) $company->id,
            array('purpose' => 'tender_upload', 'tender_id' => (int) $tender->id)
        );

        $file = $file_link->getFile();

        return array(
            'file_link_id' => (int) $file_link->id,
            'filename' => (string) ($file_link->data['filename'] ?? $upload_file->name),
            'size' => (int) ($file->data['size'] ?? $upload_file->size),
            'ext' => (string) ($file->data['ext'] ?? $ext),
        );
    }
    // -----




    public function isSupplierParticipationClosed(pb2bTender $tender): bool
    {
        $status = pb2bTenderStatus::tryFromId((int) $tender->data['status']);

        return pb2bTenderStatus::isSupplierParticipationClosed($status?->value ?? '');
    }

   
    /**
     * @return array<string, mixed>
     */
    private function startReviewPayload(pb2bTender $tender): array
    {
        $counts_by_tender = (new pb2bTenderApplicationCollection())->applicationCountsByTenderIds(array((int) $tender->id));
        $counts = $counts_by_tender[(int) $tender->id];
        $tender_payload = pb2bTenderResource::make($tender)->additional($counts)->resolve();
        $tender_payload['participants_count'] = array_sum($counts);
        $tender_payload['proposals_count'] = $counts[pb2bTenderApplication::STATUS_SUBMITTED];

        return array(
            'tender_id' => (int) $tender->id,
            'tender' => $tender_payload,
            // Ranking подмешивает startReviewForBuyer после успешного перехода / идемпотентного ответа.
            'ranking' => null,
        );
    }

    // -----
    /**
     * @param array<string, mixed> $state
     * @throws waException
     */
    private function assertWinnerDraftReadyToComplete(array $state): void
    {
        if (!empty($state['can_complete'])) {
            return;
        }
        $mode = (string) ($state['winner_selection_mode'] ?? 'none');
        if ($mode === 'positional') {
            throw new waException(
                'Укажите победителя по всем позициям',
                pb2bHttpStatus::CONFLICT
            );
        }
        throw new waException('Выберите победителя', pb2bHttpStatus::CONFLICT);
    }
    // -----
    /**
     * review_and_admission → evaluation → summarizing_results (или догон с evaluation).
     *
     * @throws waException
     */
    private function transitionToSummarizingResults(
        pb2bTender $tender,
        string $reason,
        ?waContact $actor
    ): void {
        $status = pb2bTenderStatus::tryFromId((int) $tender->data['status']);
        $evaluation_id = pb2bTenderStatus::EVALUATION->id();
        $summarizing_results_id = pb2bTenderStatus::SUMMARIZING_RESULTS->id();
        if ($evaluation_id <= 0 || $summarizing_results_id <= 0) {
            throw new waException('Статусы подведения итогов не настроены', pb2bHttpStatus::BAD_REQUEST);
        }

        if ($status === pb2bTenderStatus::REVIEW_AND_ADMISSION) {
            $to_evaluation = $this->statusService->transition(
                $tender,
                $evaluation_id,
                $reason,
                $actor
            );
            if (!empty($to_evaluation['error'])) {
                throw new waException(
                    (string) ($to_evaluation['message'] ?? 'Ошибка смены статуса'),
                    (int) ($to_evaluation['http_status'] ?? pb2bHttpStatus::BAD_REQUEST)
                );
            }
            $status = pb2bTenderStatus::tryFromId((int) $tender->data['status']);
        }

        if ($status === pb2bTenderStatus::EVALUATION) {
            $to_results = $this->statusService->transition(
                $tender,
                $summarizing_results_id,
                $reason,
                $actor
            );
            if (!empty($to_results['error'])) {
                throw new waException(
                    (string) ($to_results['message'] ?? 'Ошибка смены статуса'),
                    (int) ($to_results['http_status'] ?? pb2bHttpStatus::BAD_REQUEST)
                );
            }

            return;
        }

        if ($status === pb2bTenderStatus::SUMMARIZING_RESULTS) {
            return;
        }

        throw new waException(
            'Подведение итогов недоступно из текущего статуса',
            pb2bHttpStatus::CONFLICT
        );
    }

    // -----
    /**
     * @return array<string, mixed>
     */
    private function winnerCompletePayload(
        pb2bTender $tender,
        int $company_id,
        pb2bTenderApplicationService $apps
    ): array {
        $counts_by_tender = (new pb2bTenderApplicationCollection())->applicationCountsByTenderIds(array((int) $tender->id));
        $counts = $counts_by_tender[(int) $tender->id];
        $tender_payload = pb2bTenderResource::make($tender)->additional($counts)->resolve();
        $tender_payload['participants_count'] = array_sum($counts);
        $tender_payload['proposals_count'] = $counts[pb2bTenderApplication::STATUS_SUBMITTED];

        return array(
            'tender_id' => (int) $tender->id,
            'tender' => $tender_payload,
            'winner' => $apps->getWinnerStateForBuyer((int) $tender->id, $company_id),
        );
    }
    


    // ------------
    /**
     * @throws waException
     */
    public function publishFromBuyer(
        int $tender_id,
        int $company_id,
        ?string $reason = null,
        ?waContact $actor = null
    ): pb2bTender {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::publish($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }

        $this->assertTenderFileLinks($tender, array_column($tender->getItems(), 'file_link_id'));
        $this->assertTenderFileLinks($tender, array_column($tender->getDocuments(), 'file_link_id'));

        $result = $this->statusService->publish($tender, $reason, $actor);
        if (!empty($result['error'])) {
            if (!empty($result['issues'])) {
                throw new pb2bValidationException(
                    (string) ($result['message'] ?? 'Заполните обязательные поля перед публикацией'),
                    array_values($result['issues']),
                    pb2bHttpStatus::BAD_REQUEST
                );
            }
            throw new waException(
                (string) ($result['message'] ?? 'Ошибка смены статуса'),
                (int) ($result['http_status'] ?? pb2bHttpStatus::BAD_REQUEST)
            );
        }

        return $tender;
    }

    /**
     * После end_at: accepting_applications → opening_applications → review_and_admission.
     * Идемпотентно, если рассмотрение уже начато. В evaluation не переводит.
     *
     * @return array{
     *   tender_id:int,
     *   tender:array,
     *   ranking:mixed
     * }
     * @throws waException
     */
    public function startReviewForBuyer(
        int $tender_id,
        int $company_id,
        ?string $reason = null,
        ?waContact $actor = null
    ): array {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::startReview($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }

        if (pb2bTenderType::tryFromId((int) $tender->data['type']) !== pb2bTenderType::PRICE_REQUEST) {
            throw new waException(
                'Начать рассмотрение можно только для запроса цен',
                pb2bHttpStatus::CONFLICT
            );
        }

        $status = pb2bTenderStatus::tryFromId((int) $tender->data['status']);
        $reason_text = ($reason !== null && trim($reason) !== '')
            ? trim($reason)
            : 'Начало рассмотрения заявок';

        // После открытия заявок (кроме догоняющего перехода) — 200 без смены статуса.
        if ($status !== pb2bTenderStatus::ACCEPTING_APPLICATIONS
            && $status !== pb2bTenderStatus::OPENING_APPLICATIONS) {
            if ($tender->isReviewStarted()) {
                $payload = $this->startReviewPayload($tender);
                $payload['ranking'] = (new pb2bTenderApplicationService())->rankingForBuyer(
                    (int) $tender->id,
                    $company_id
                );

                return $payload;
            }
            throw new waException(
                'Рассмотрение недоступно из текущего статуса',
                pb2bHttpStatus::CONFLICT
            );
        }

        $opening_applications_id = pb2bTenderStatus::OPENING_APPLICATIONS->id();
        $review_and_admission_id = pb2bTenderStatus::REVIEW_AND_ADMISSION->id();
        if ($opening_applications_id <= 0 || $review_and_admission_id <= 0) {
            throw new waException('Статусы рассмотрения не настроены', pb2bHttpStatus::BAD_REQUEST);
        }

        if ($status === pb2bTenderStatus::ACCEPTING_APPLICATIONS) {
            if (!$tender->canStartReview()) {
                throw new waException(
                    'Приём заявок ещё не закончен',
                    pb2bHttpStatus::CONFLICT
                );
            }
            $opened = $this->statusService->transition(
                $tender,
                $opening_applications_id,
                $reason_text,
                $actor
            );
            if (!empty($opened['error'])) {
                throw new waException(
                    (string) ($opened['message'] ?? 'Ошибка смены статуса'),
                    (int) ($opened['http_status'] ?? pb2bHttpStatus::BAD_REQUEST)
                );
            }
            $status = pb2bTenderStatus::tryFromId((int) $tender->data['status']);
        }

        if ($status === pb2bTenderStatus::OPENING_APPLICATIONS) {
            $review = $this->statusService->transition(
                $tender,
                $review_and_admission_id,
                $reason_text,
                $actor
            );
            if (!empty($review['error'])) {
                throw new waException(
                    (string) ($review['message'] ?? 'Ошибка смены статуса'),
                    (int) ($review['http_status'] ?? pb2bHttpStatus::BAD_REQUEST)
                );
            }
        }

        $payload = $this->startReviewPayload($tender);
        $payload['ranking'] = (new pb2bTenderApplicationService())->rankingForBuyer(
            (int) $tender->id,
            $company_id
        );

        return $payload;
    }

    /**
     * Завершить выбор победителя: evaluation → summarizing_results + winner_selection_completed.
     *
     * @return array<string, mixed>
     * @throws waException
     */
    public function completeWinnerSelectionForBuyer(
        int $tender_id,
        int $company_id,
        ?string $reason = null,
        ?waContact $actor = null
    ): array {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::completeWinner($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        if (pb2bTenderType::tryFromId((int) $tender->data['type']) !== pb2bTenderType::PRICE_REQUEST) {
            throw new waException(
                'Выбор победителя доступен только для запроса цен',
                pb2bHttpStatus::CONFLICT
            );
        }

        $apps = new pb2bTenderApplicationService();
        $row = is_array($tender->data) ? $tender->data : array();
        $completed = !empty($row['winner_selection_completed']);
        $finished_without = !empty($row['finished_without_winner']);
        $status = pb2bTenderStatus::tryFromId((int) $tender->data['status']);

        if ($completed) {
            if ($status === pb2bTenderStatus::SUMMARIZING_RESULTS && !$finished_without) {
                return $this->winnerCompletePayload($tender, $company_id, $apps);
            }
            throw new waException(
                'Выбор победителя уже завершён',
                pb2bHttpStatus::CONFLICT
            );
        }

        $state = $apps->getWinnerStateForBuyer($tender_id, $company_id);
        $this->assertWinnerDraftReadyToComplete($state);

        $reason_text = ($reason !== null && trim($reason) !== '')
            ? trim($reason)
            : 'Завершение выбора победителя';
        $model = new pb2bTenderModel();
        $model->query('START TRANSACTION');
        try {
            $this->transitionToSummarizingResults($tender, $reason_text, $actor);
            $apps->markWinnerSelectionCompleted($tender_id, $company_id, false);

            $tender = new pb2bTender((int) $tender->id);

            $payload = $this->winnerCompletePayload($tender, $company_id, $apps);
            $model->query('COMMIT');

            return $payload;
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }

    /**
     * Завершить процедуру без победителя.
     *
     * @return array<string, mixed>
     * @throws waException
     */
    public function finishWithoutWinnerForBuyer(
        int $tender_id,
        int $company_id,
        string $reason,
        ?waContact $actor = null
    ): array {
        $reason = trim($reason);
        if ($reason === '') {
            throw new waException(
                'Укажите причину завершения без победителя',
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::completeWinner($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        if (pb2bTenderType::tryFromId((int) $tender->data['type']) !== pb2bTenderType::PRICE_REQUEST) {
            throw new waException(
                'Завершение без победителя доступно только для запроса цен',
                pb2bHttpStatus::CONFLICT
            );
        }

        $apps = new pb2bTenderApplicationService();
        $row = is_array($tender->data) ? $tender->data : array();
        $completed = !empty($row['winner_selection_completed']);
        $finished_without = !empty($row['finished_without_winner']);
        $status = pb2bTenderStatus::tryFromId((int) $tender->data['status']);

        if ($completed) {
            if ($status === pb2bTenderStatus::SUMMARIZING_RESULTS && $finished_without) {
                return $this->winnerCompletePayload($tender, $company_id, $apps);
            }
            throw new waException(
                'Выбор победителя уже завершён',
                pb2bHttpStatus::CONFLICT
            );
        }

        $model = new pb2bTenderModel();
        $model->query('START TRANSACTION');
        try {
            $apps->applyFinishWithoutWinnerDraft($tender_id, $company_id, $reason);
            $tender = new pb2bTender((int) $tender->id);
            $this->transitionToSummarizingResults(
                $tender,
                'Завершение без победителя: ' . $reason,
                $actor
            );
            $apps->markWinnerSelectionCompleted($tender_id, $company_id, true);
            $tender = new pb2bTender((int) $tender->id);

            $payload = $this->winnerCompletePayload($tender, $company_id, $apps);
            $model->query('COMMIT');

            return $payload;
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }



    private function assertTenderFileLinks(pb2bTender $tender, array $file_link_ids): void
    {
        $ids = array_values(array_unique(array_filter($file_link_ids)));
        if (!$ids) {
            return;
        }
        $links = (new pb2bFileLinkCollection())->getByIds($ids);
        $attached_ids = null;
        foreach ($ids as $id) {
            $link = $links[$id] ?? null;
            if (!$link || empty($link['stored_file_id'])) {
                throw new waException('Вложение не найдено. Загрузите файл повторно', pb2bHttpStatus::NOT_FOUND);
            }
            if ((int) $link['owner_company_id'] !== (int) $tender->data['organizer_company_id']) {
                throw new waException('Файл не принадлежит организатору тендера', pb2bHttpStatus::FORBIDDEN);
            }
            $extra = json_decode((string) ($link['extra'] ?? ''), true);
            if (($extra['purpose'] ?? '') === 'tender_upload') {
                if ((int) ($extra['tender_id'] ?? 0) !== (int) $tender->id) {
                    throw new waException('Файл загружен для другого тендера', pb2bHttpStatus::FORBIDDEN);
                }
            } else {
                if ($attached_ids === null) {
                    $attached_ids = array();
                    foreach ($tender->getItems() as $item) {
                        $attached_ids[(int) ($item['file_link_id'] ?? 0)] = true;
                    }
                    foreach ($tender->getDocuments() as $document) {
                        $attached_ids[(int) ($document['file_link_id'] ?? 0)] = true;
                    }
                }
                if (!isset($attached_ids[$id])) {
                    throw new waException('Файл не связан с этим тендером. Загрузите его повторно', pb2bHttpStatus::FORBIDDEN);
                }
            }
            if (!pb2bStorage::disk($link['storage_disk'])->exists($link['storage_path'])) {
                throw new waException('Файл отсутствует в хранилище. Загрузите его повторно', pb2bHttpStatus::NOT_FOUND);
            }
        }
    }
}
