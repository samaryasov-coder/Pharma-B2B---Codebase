<?php

class pb2bCompany extends pb2bWaproObject
{
    protected pb2bCompanyCategoryModel $companyCategoryModel;
    protected pb2bClientModel $clientModel;
    protected pb2bDocflowTemplateModel $docflowTemplateModel;
    protected pb2bDocflowTemplateItemsModel $docflowTemplateItemsModel;
    public function __construct(?int $id = null)
    {
        $this->companyCategoryModel = new pb2bCompanyCategoryModel();
        $this->clientModel = new pb2bClientModel();
        $this->docflowTemplateModel = new pb2bDocflowTemplateModel();
        $this->docflowTemplateItemsModel = new pb2bDocflowTemplateItemsModel();
        parent::__construct($id);
    }

    /**
     * Является ли компания поставщиком
     */
    public function isSupplier(): bool
    {
        return (bool)$this->data['supplier'];
    }

    /**
     * Является ли компания покупателем
     */
    public function isBuyer(): bool
    {
        return (bool)$this->data['buyer'];
    }

    /**
     * Возвращает пользователя
     */
    public function getContact(): ?waContact
    {
        $contact = new waContact($this->data['contact_id'] ?? 0);
        return $contact->exists() ? $contact : null;
    }

    /**
     * Возвращает тип компании
     */
    public function getType(): pb2bCompanyType
    {
        return pb2bCompanyType::from($this->data['company_type']);
    }

    /**
     * Возвращает тип организации компании
     */
    public function getOrganizationType(): ?pb2bOrganizationType
    {
        return pb2bOrganizationType::tryFrom($this->data['type_organization'] ?? '');
    }

    /**
     * Возвращает полное название компании (тип + имя)
     */
    public function getFullName(): string
    {
        $cmp_type = $this->getType();
        $name = $this->data['name'];

        switch ($cmp_type){
            case pb2bCompanyType::ENTREPRENEUR:
                return "{$cmp_type->shortName()} \"$name\"";
            case pb2bCompanyType::ORGANIZATION:
                $org_type = $this->getOrganizationType();
                return "{$org_type->name()} \"$name\"";
        }
        return $name;
    }





    protected function validateCompanyTyping(array $data): array
    {
        $buyer = (int) ($data['buyer'] ?? 0);
        $supplier = (int) ($data['supplier'] ?? 0);
        if ($buyer !== 1 && $supplier !== 1) {
            return array('error' => true, 'message' => 'Ошибка: выберите минимум один тип компании (Покупатель или Поставщик)');
        }

        $company_type_config = pb2bWaproHelper::getConfigOption('company_type', 'code');
        if (empty($company_type_config) || !is_array($company_type_config)) {
            return array('error' => true, 'message' => 'Ошибка: не настроен справочник типов компании');
        }

        $company_type_id = (int) ($data['company_type'] ?? 0);
        if ($company_type_id <= 0) {
            return array('error' => true, 'message' => 'Ошибка: не указан тип компании');
        }

        $organization_company_type_id = (int) ($company_type_config['organization']['id'] ?? 0);
        $entrepreneur_company_type_id = (int) ($company_type_config['entrepreneur']['id'] ?? 0);
        if (
            !$organization_company_type_id ||
            !$entrepreneur_company_type_id ||
            ($company_type_id !== $organization_company_type_id && $company_type_id !== $entrepreneur_company_type_id)
        ) {
            return array('error' => true, 'message' => 'Ошибка: неверный тип компании');
        }

        $type_organization_config = pb2bWaproHelper::getConfigOption('type_organization');
        if (empty($type_organization_config) || !is_array($type_organization_config)) {
            $type_organization_config = array();
        }

        $inn = (string) ($data['inn'] ?? '');
        $kpp = (string) ($data['kpp'] ?? '');
        $ogrn = (string) ($data['ogrn'] ?? '');
        $type_organization_id = (int) ($data['type_organization'] ?? 0);

        if ($company_type_id === $organization_company_type_id) {
            if ($type_organization_id <= 0) {
                return array('error' => true, 'message' => 'Ошибка: для организации нужно указать тип организации');
            }
            if (empty($type_organization_config[$type_organization_id])) {
                return array('error' => true, 'message' => 'Ошибка: неверный тип организации');
            }

            if (strlen($inn) !== 10) {
                return array('error' => true, 'message' => 'Ошибка: для организации ИНН должен содержать 10 цифр');
            }
            if (strlen($kpp) !== 9) {
                return array('error' => true, 'message' => 'Ошибка: для организации КПП должен содержать 9 цифр');
            }
            if (strlen($ogrn) !== 13) {
                return array('error' => true, 'message' => 'Ошибка: для организации ОГРН должен содержать 13 цифр');
            }
        }

        if ($company_type_id === $entrepreneur_company_type_id) {
            if ($type_organization_id > 0) {
                return array('error' => true, 'message' => 'Ошибка: для предпринимателя тип организации должен быть пустым');
            }
            if (strlen($inn) !== 12) {
                return array('error' => true, 'message' => 'Ошибка: для предпринимателя ИНН должен содержать 12 цифр');
            }
            if (strlen($ogrn) !== 15) {
                return array('error' => true, 'message' => 'Ошибка: для предпринимателя ОГРН должен содержать 15 цифр');
            }
            if ($kpp !== '') {
                return array('error' => true, 'message' => 'Ошибка: для предпринимателя КПП не должен быть заполнен');
            }
        }

        return array('error' => false);
    }

    protected function preSave(array &$data): array
    {
        if (!array_key_exists('buyer', $data) && !array_key_exists('supplier', $data)) {
            if (isset($this->data['buyer'])) {
                $data['buyer'] = $this->data['buyer'];
            }
            if (isset($this->data['supplier'])) {
                $data['supplier'] = $this->data['supplier'];
            }
        }
        
        if (isset($this->data['contact_id'])) {
            $data['contact_id'] = (int) $this->data['contact_id'];
        } else {
            $contact_id = (int) ($data['contact_id'] ?? 0);
            $data['contact_id'] = $contact_id > 0 ? $contact_id : wa()->getUser()->getId();

            $exists = $this->model->getByField('contact_id', $data['contact_id']);
            if (!empty($exists['id'])) {
                return array('error' => true, 'message' => 'Ошибка: компания с таким контактом уже существует');
            }
        }

        
        if (!array_key_exists('status', $data) && isset($this->data['status'])) {
            $data['status'] = (int) $this->data['status'];
        }
        
        foreach (array('inn', 'kpp', 'ogrn') as $field) {
            if (isset($data[$field])) {
                $data[$field] = preg_replace('~\D+~', '', (string) $data[$field]);
            }
        }
       
        $company_type_config = pb2bWaproHelper::getConfigOption('company_type', 'code');
        if ((int) ($data['company_type'] ?? 0) === $company_type_config['entrepreneur']['id']) {
            $data['type_organization'] = null;
            $data['kpp'] = null;
        }

        $result = parent::preSave($data);
        if (!empty($result['error'])) return $result;

        $typing_validation = $this->validateCompanyTyping($data);
        if (!empty($typing_validation['error'])) return $typing_validation;
       
        if (isset($data['reserve_phones']) && is_array($data['reserve_phones'])) {
            $phones = array();

            foreach ($data['reserve_phones'] as $v) {
                $v = trim((string)$v);
                if ($v === '') continue;

                $check = pb2bWaproHelper::validateField('phone', $v, 'company');
                if(!empty($check['error'])) {
                    return array('error' => true, 'message' => 'Ошибка: неверный формат дополнительного телефона "'.$v.'"');
                }

                $phones[] = $v;
            }

            $phones = array_values(array_unique($phones));
            $data['reserve_phones'] = !empty($phones) ? json_encode($phones, JSON_UNESCAPED_UNICODE) : null;
        }
        
        if (isset($data['reserve_emails']) && is_array($data['reserve_emails'])) {
            $emails = array();

            foreach ($data['reserve_emails'] as $v) {
                $v = trim((string)$v);
                if ($v === '') {
                    continue;
                }
                
                $check = pb2bWaproHelper::validateField('registry_email', $v, 'company');
                if (!empty($check['error'])) {
                    return array('error' => true, 'message' => 'Ошибка: неверный формат дополнительного E-mail "'.$v.'"');
                }

                $emails[] = $v;
            }

            $emails = array_values(array_unique($emails));
            $data['reserve_emails'] = !empty($emails) ? json_encode($emails, JSON_UNESCAPED_UNICODE) : null;
        }

        return $result;
    }

    protected function afterSave(array &$result): void
    {
        parent::afterSave($result);

        if(empty($result['error'])) {
            if (isset($result['new'])) {
                $result['dispatch_url'] = '#/company/edit/id='.$this->id;
            }
            $inn = ifempty($this->data['inn'], null);
            $this->clientModel->updateByField(array('inn' => $inn), array('archive' => 1));
        }
    }

    protected function afterDelete(array &$result): void
    {
        parent::afterDelete($result);
        if (empty($result['error'])) {
            $this->companyCategoryModel->deleteByField('company_id', $this->id);
        }
    }

    public function get($params = array()): array
    {
        $result = parent::get($params);
        if(isset($result['object']['reserve_emails'])) 
        {
            $result['object']['reserve_emails'] = json_decode($result['object']['reserve_emails'], true);
        }
       
        if(isset($result['object']['reserve_phones'])) 
        {
            $result['object']['reserve_phones'] = json_decode($result['object']['reserve_phones'], true);
        }

        $buyer = !empty($result['object']['buyer']);
        $supplier = !empty($result['object']['supplier']);
        $role_options = (array) pb2bWaproHelper::getConfigOption('company_roles', 'code');
        $buyer_name = (string) ($role_options['buyer']['name'] ?? 'Покупатель');
        $supplier_name = (string) ($role_options['supplier']['name'] ?? 'Продавец');

        $roles = array();
        if ($buyer && $supplier) {
            $roles[] = array('code' => 'buyer_supplier', 'name' => $buyer_name.' и '.$supplier_name);
        } elseif ($buyer) {
            $roles[] = array('code' => 'buyer', 'name' => $buyer_name);
        } elseif ($supplier) {
            $roles[] = array('code' => 'supplier', 'name' => $supplier_name);
        }
        $result['object']['roles'] = $roles;

        return $result;
    }

    protected function getTabs(): array
    {
        return array(
            'items' => array(
                'common' => array('tab' => 'common', 'name' => 'Данные'),
                'category' => array('tab' => 'category', 'name' => 'Классификаторы'),
                'accreditation' => array('tab' => 'accreditation', 'name' => 'Одобрение поставщиков'),
                'tenders' => array('tab' => 'tenders', 'name' => 'Тендеры'),
            ),
            'options' => array(
                'default_tab' => 'common',
            ),
        );
    }

    protected function getCategories() 
    {
        $collection = new pb2bCategoryCollection($this->class_name . '.company_id=' . $this->id);
        return $collection->getCollection();
    }

    protected function getConfigFields()
    {
        return pb2bWaproHelper::getFields($this->class_name);
    }

    public function setStatus(int $status): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Компания не найдена');
        }

        $status = $status > 0 ? 1 : 0;
        $this->model->updateById($this->id, array('status' => $status));
        $this->setId($this->id);

        return array(
            'error' => false,
            'message' => $status ? 'Компания активирована' : 'Компания деактивирована',
            'item' => array(
                'id' => (int) $this->id,
                'status' => $status,
            ),
        );
    }

    //docflow
    //docflowTemplate
    protected function getDocflowTemplateAccreditation(): array
    {
        $collection = new pb2bDocflowTemplateCollection('items.company_id='.$this->id.'&items.process_type=1');
        $cfg = pb2bWaproHelper::getConfigOption('docflow_process_types');
        $company_types_cfg = pb2bWaproHelper::getConfigOption('company_type');
        if(empty($cfg) || !is_array($cfg)) $cfg = array();
        if(empty($company_types_cfg) || !is_array($company_types_cfg)) $company_types_cfg = array();

        $rows = $collection->getCollection(array('key' => false));
        if(empty($rows)) return array();
        $first = reset($rows);

        $template = array(
            'id' => (int) ($first['template_id'] ?? 0),
            'company_id' => $first['company_id'] ?? 0,
            'file_set_id' => $first['file_set_id'] ?? 0,
            'process_type' => $first['process_type'] ?? 0,
            'process_type_name' => $cfg[$first['process_type']]['name'] ?? '',
            'auto_request_enabled' => isset($first['auto_request_enabled']) ? (int) $first['auto_request_enabled'] : 0,
            'refresh_period_days' => isset($first['refresh_period_days']) ? (int) $first['refresh_period_days'] : null,
            'items' => array(),
        );

        foreach($rows as $row) 
        {
            $item_id = (int) ($row['item_id'] ?? 0);
            if (!$item_id) continue;

            $item_ct = isset($row['item_company_type_id']) && $row['item_company_type_id'] !== '' && $row['item_company_type_id'] !== null
                ? (int) $row['item_company_type_id'] : 0;
    
            $template['items'][] = array(
                'id' => $item_id,
                'name' => $row['item_name'] ?? '',
                'comment' => isset($row['item_comment']) ? (string) $row['item_comment'] : null,
                'file_id' => $row['item_file_id'] ?? 0,
                'sort' => isset($row['item_sort']) ? (int) $row['item_sort'] : null,
                'company_type_id' => $item_ct > 0 ? $item_ct : null,
                'company_type_name' => $item_ct > 0 ? ($company_types_cfg[$item_ct]['name'] ?? '') : 'Все типы',
            );
        }
        return $template;
    }

    public function docflowTemplateItemAddFromUpload($template_item_data): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Компания не найдена');
        }

        $result = array('error' => false, 'message' => 'Создано');
        $process_type = $template_item_data['process_type'] ?? null;
        $refresh_period_days = $template_item_data['refresh_period_days'] ?? null;
        $auto_request_enabled = isset($template_item_data['auto_request_enabled']) ? (int) $template_item_data['auto_request_enabled'] : 0;
        if ($auto_request_enabled !== 1) {
            $auto_request_enabled = 0;
        }
        
        if(!$process_type) {
            $result = array('error' => true, 'message' => 'Тип процесса не получен');
            return $result;
        }
        $template_data = $this->docflowTemplateModel->getByField(array('company_id' => $this->id, 'process_type' => $process_type));
        if (empty($template_data)) {
            $docflowTemplate = new pb2bDocflowTemplate();
            $save_result = $docflowTemplate->save(array(
                'company_id' => $this->id, 
                'process_type' => $process_type,
                'refresh_period_days'=> $refresh_period_days,
                'auto_request_enabled' => $auto_request_enabled,
            ));

            if (!empty($save_result['error'])) return $save_result;
        } else {
            $docflowTemplate = new pb2bDocflowTemplate($template_data['id']);
        }

        $default_file_ids_raw = $template_item_data['default_file_ids'] ?? array();
        $default_file_ids = array();
        if (is_array($default_file_ids_raw)) {
            foreach ($default_file_ids_raw as $default_file_id) {
                $default_file_id = (int) $default_file_id;
                if ($default_file_id > 0) {
                    $default_file_ids[$default_file_id] = $default_file_id;
                }
            }
        }
        $default_file_ids = array_values($default_file_ids);

        $added_default_items = 0;
        if (!empty($default_file_ids)) {
            $add_defaults_result = $docflowTemplate->addDefaultItemsByFileIds($default_file_ids);
            if (!empty($add_defaults_result['error'])) {
                return $add_defaults_result;
            }
            $added_default_items = (int) ($add_defaults_result['added'] ?? 0);
        }

        $has_manual_item_data = false;
        if (!empty($template_item_data['name'])) {
            $has_manual_item_data = true;
        } else {
            $file = waRequest::file('file');
            if ($file && $file->uploaded()) {
                $has_manual_item_data = true;
            }
        }

        if ($has_manual_item_data) {
            $add_result = $docflowTemplate->addItemFromUpload($template_item_data);
            if (!empty($add_result['error'])) {
                return $add_result;
            }
        }

        if (!$has_manual_item_data && $added_default_items <= 0) {
            return array('error' => true, 'message' => 'Нет данных для добавления');
        }

        if ($added_default_items > 0 && !$has_manual_item_data) {
            $result['message'] = 'Добавлено стандартных документов: '.$added_default_items;
        } elseif ($added_default_items > 0 && $has_manual_item_data) {
            $result['message'] = 'Создано. Добавлено стандартных документов: '.$added_default_items;
        }
        $result['data'] = array(
            'added_default_items' => $added_default_items,
        );

        return $result;
    }

    public function docflowTemplateItemUpdateFromUpload(
        int $template_id,
        int $template_item_id,
        array $template_item_data,
        string $input_name = 'file'
    ): array {
        if ($template_item_id <= 0) {
            return array('error' => true, 'message' => 'Не передан template_item_id');
        }

        $template_result = $this->getDocflowTemplateById($template_id);
        if (!empty($template_result['error'])) {
            return $template_result;
        }
        
        $docflow_template = $template_result['template'];
        $update_result = $docflow_template->updateItemFromUpload($template_item_id, $template_item_data, $input_name);
        if (!empty($update_result['error'])) {
            return $update_result;
        }

        $item_result = $docflow_template->getItemById($template_item_id);
        return array(
            'error' => false,
            'message' => (string) ($update_result['message'] ?? 'Обновлено'),
            'data' => $update_result['data'] ?? array(),
            'item' => !empty($item_result['error']) ? array() : ($item_result['item'] ?? array()),
        );
    }

    protected function getDocflowTemplateById(int $template_id): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Компания не найдена');
        }
        if ($template_id <= 0) {
            return array('error' => true, 'message' => 'Не передан template_id');
        }

        $docflow_template = new pb2bDocflowTemplate($template_id);
        if (!$docflow_template->id) {
            return array('error' => true, 'message' => 'Шаблон не найден');
        }
        if ((int) ($docflow_template->data['company_id'] ?? 0) !== (int) $this->id) {
            return array('error' => true, 'message' => 'Шаблон не принадлежит компании');
        }

        return array(
            'error' => false,
            'template' => $docflow_template,
        );
    }

    public function docflowTemplateItemGetById(int $template_id, int $template_item_id): array
    {
        if ($template_item_id <= 0) {
            return array('error' => true, 'message' => 'Не передан template_item_id');
        }

        $template_result = $this->getDocflowTemplateById($template_id);
        if (!empty($template_result['error'])) {
            return $template_result;
        }

        /** @var pb2bDocflowTemplate $docflow_template */
        $docflow_template = $template_result['template'];
        $item_result = $docflow_template->getItemById($template_item_id);
        if (!empty($item_result['error'])) {
            return $item_result;
        }

        return array(
            'error' => false,
            'template_id' => (int) $docflow_template->id,
            'company_id' => (int) $this->id,
            'item' => $item_result['item'] ?? array(),
        );
    }

    public function docflowTemplateItemDeleteById(int $template_id, int $template_item_id): array
    {
        if ($template_item_id <= 0) {
            return array('error' => true, 'message' => 'Не передан template_item_id');
        }

        $template_result = $this->getDocflowTemplateById($template_id);
        if (!empty($template_result['error'])) {
            return $template_result;
        }
       
        $docflow_template = $template_result['template'];
        return $docflow_template->deleteItemById($template_item_id);
    }

    public function docflowTemplateRefreshPolicyPreview(int $template_id, array $policy_data = array()): array
    {
        $template_result = $this->getDocflowTemplateById($template_id);
        if (!empty($template_result['error'])) {
            return $template_result;
        }
        $docflow_template = $template_result['template'];
        return $docflow_template->getRefreshPolicyPreview($policy_data);
    }

    public function docflowTemplateRefreshPolicyApply(int $template_id, array $policy_data = array()): array
    {
        $template_result = $this->getDocflowTemplateById($template_id);
        if (!empty($template_result['error'])) {
            return $template_result;
        }

        /** @var pb2bDocflowTemplate $docflow_template */
        $docflow_template = $template_result['template'];
        return $docflow_template->applyRefreshPolicy($policy_data);
    }

    //docflowRequest

    public function docflowRequestCreateFromReviewer($request_item_data): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Компания покупателя не найдена');
        if (empty($this->data['buyer'])) return array('error' => true, 'message' => 'Инициатором процесса может быть только компания-покупатель');

        // TODO: проверить, что текущий пользователь прикреплён к этой компании.
        // if ((int) ($this->data['contact_id'] ?? 0) !== (int) wa()->getUser()->getId()) {
        //     return array('error' => true, 'message' => 'Пользователь не прикреплён к компании');
        // }

        $template_id = (int) ($request_item_data['template_id'] ?? 0);
        if (!$template_id) return array('error' => true, 'message' => 'Не передан template_id');

        $provider_id = (int) ($request_item_data['provider_id'] ?? 0);
        if (!$provider_id) return array('error' => true, 'message' => 'Не передан поставщик');
        if ($provider_id === (int) $this->id) {
            return array('error' => true, 'message' => 'Нельзя создать запрос для своей компании');
        }

        $docflowTemplate = new pb2bDocflowTemplate($template_id);
        if (!$docflowTemplate->id) return array('error' => true, 'message' => 'Шаблон процесса не найден');
        $template_data = $docflowTemplate->data;

        if ((int) ($template_data['company_id'] ?? 0) !== (int) $this->id) {
            return array('error' => true, 'message' => 'Шаблон не принадлежит компании покупателя');
        }

        $process_type = (int) ($template_data['process_type'] ?? 0);
        if (!$process_type) return array('error' => true, 'message' => 'Не определён тип процесса');

        $template_item_ids = array();
        if (!empty($request_item_data['template_item_ids']) && is_array($request_item_data['template_item_ids'])) {
            foreach ($request_item_data['template_item_ids'] as $template_item_id) {
                $template_item_id = (int) $template_item_id;
                if ($template_item_id > 0) {
                    $template_item_ids[$template_item_id] = $template_item_id;
                }
            }
            $template_item_ids = array_values($template_item_ids);
        }

        $company_model = new pb2bCompanyModel();
        $provider = $company_model->getById($provider_id);
        if (empty($provider)) {
            return array('error' => true, 'message' => 'Компания поставщика не найдена');
        }
        if (empty($provider['supplier'])) {
            return array('error' => true, 'message' => 'Компания не является поставщиком');
        }

        $provider_company_type_id = (int) ($provider['company_type'] ?? 0);
        $for_provider_type = $provider_company_type_id > 0 ? $provider_company_type_id : null;
        $template_items = $docflowTemplate->getRequestSourceItems($template_item_ids, $for_provider_type);
        if (empty($template_items)) {
            return array('error' => true, 'message' => 'В шаблоне нет элементов для этого типа компании поставщика');
        }
        if (!empty($template_item_ids)) {
            $exists = array();
            foreach ($template_items as $template_item) {
                $exists[(int) ($template_item['id'] ?? 0)] = 1;
            }
            $missing = array();
            foreach ($template_item_ids as $template_item_id) {
                if (empty($exists[$template_item_id])) {
                    $missing[] = $template_item_id;
                }
            }
            if (!empty($missing)) {
                return array(
                    'error' => true,
                    'message' => 'Не найдены элементы шаблона для этого поставщика: '.implode(', ', $missing),
                );
            }
        }

        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $request_status = (int) ($request_statuses['waiting_provider']['id'] ?? 1);
        $comment = $request_item_data['comment'] ?? null;
        if (is_string($comment)) {
            $comment = trim($comment);
            if ($comment === '') {
                $comment = null;
            }
        }
        $contact_id = (int) ($this->data['contact_id'] ?? 0);
        if (!$contact_id) {
            $contact_id = null;
        }

        $template_file_set_id = (int) ($template_data['file_set_id'] ?? 0);

        $save_data = array(
            'process_type' => $process_type,
            'reviewer_id' => (int) $this->id,
            'provider_id' => $provider_id,
            'template_id' => $template_id,
            'status' => $request_status,
            'approved_datetime' => null,
            'comment' => $comment,
            'contact_id' => $contact_id,
        );

        $docflowRequest = new pb2bDocflowRequest();
        $save_result = $docflowRequest->save($save_data);
        if (!empty($save_result['error'])) {
            return array('error' => true, 'message' => $save_result['message'] ?? 'Не удалось создать процесс');
        }

        $create_items_result = $docflowRequest->createItemsFromTemplate($template_items, $template_file_set_id);
        if (!empty($create_items_result['error'])) {
            $docflowRequest->delete();
            return array('error' => true, 'message' => $create_items_result['message'] ?? 'Не удалось создать элементы процесса');
        }


        /*-------------------- Исправить сохранение истории, ошибка с id --------------------*/
//        $docflowRequest->addHistory(
//            'request_created',
//            null,
//            (int) ($docflowRequest->data['status'] ?? 0),
//            false,
//            (int) $this->id
//        );

        return array(
            'error' => false,
            'message' => 'Запрос создан',
            'request_id' => (int) $docflowRequest->id,
            'provider_id' => $provider_id,
            'procedure_code' => $docflowRequest->data['procedure_code'] ?? null,
            'items_count' => (int) ($create_items_result['count'] ?? 0),
        );
    }

    public function docflowRequestCreateFromProvider($request_item_data): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Компания поставщика не найдена');
        if (empty($this->data['supplier'])) return array('error' => true, 'message' => 'Инициатором процесса может быть только компания-поставщик');

        // TODO: проверить, что текущий пользователь прикреплён к этой компании.
        // if ((int) ($this->data['contact_id'] ?? 0) !== (int) wa()->getUser()->getId()) {
        //     return array('error' => true, 'message' => 'Пользователь не прикреплён к компании');
        // }

        if (!empty($request_item_data['template_item_ids'])) {
            return array('error' => true, 'message' => 'Поставщик не может выбирать отдельные элементы шаблона');
        }

        $reviewer_id = (int) ($request_item_data['reviewer_id'] ?? 0);
        if (!$reviewer_id) return array('error' => true, 'message' => 'Не передан reviewer_id компании покупателя');
        if ($reviewer_id === (int) $this->id) return array('error' => true, 'message' => 'Нельзя создать запрос для своей компании');

        $reviewer_company = new pb2bCompany($reviewer_id);
        if (!$reviewer_company->id) return array('error' => true, 'message' => 'Компания покупателя не найдена');
        if (empty($reviewer_company->data['buyer'])) return array('error' => true, 'message' => 'Компания проверяющего должна быть покупателем');

        $template_id = (int) ($request_item_data['template_id'] ?? 0);
        $docflowTemplate = new pb2bDocflowTemplate($template_id);
        if (!$docflowTemplate->id) return array('error' => true, 'message' => 'Шаблон процесса не найден');
        $template_data = $docflowTemplate->data;

        if ((int) ($template_data['company_id'] ?? 0) !== $reviewer_id) {
            return array('error' => true, 'message' => 'Шаблон не принадлежит компании покупателя');
        }

        $template_process_type = (int) ($template_data['process_type'] ?? 0);
        if (!$template_process_type) return array('error' => true, 'message' => 'У шаблона не определён process_type');

        $provider_company_type_id = (int) ($this->data['company_type'] ?? 0);
        $for_provider_type = $provider_company_type_id > 0 ? $provider_company_type_id : null;
        $template_items = $docflowTemplate->getRequestSourceItems(array(), $for_provider_type);
        if (empty($template_items)) {
            return array('error' => true, 'message' => 'В шаблоне нет элементов для вашего типа компании');
        }

        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $request_status = (int) ($request_statuses['waiting_provider']['id'] ?? 1);
        $comment = $request_item_data['comment'] ?? null;
        if (is_string($comment)) {
            $comment = trim($comment);
            if ($comment === '') {
                $comment = null;
            }
        }
        $contact_id = (int) ($reviewer_company->data['contact_id'] ?? 0);
        if (!$contact_id) {
            $contact_id = null;
        }

        $docflowRequest = new pb2bDocflowRequest();
        $save_result = $docflowRequest->save(array(
            'process_type' => $template_process_type,
            'reviewer_id' => $reviewer_id,
            'provider_id' => (int) $this->id,
            'template_id' => (int) $docflowTemplate->id,
            'status' => $request_status,
            'approved_datetime' => null,
            'comment' => $comment,
            'contact_id' => $contact_id,
        ));
        if (!empty($save_result['error'])) {
            return array('error' => true, 'message' => $save_result['message'] ?? 'Не удалось создать процесс');
        }

        $template_file_set_id = (int) ($template_data['file_set_id'] ?? 0);
        $create_items_result = $docflowRequest->createItemsFromTemplate($template_items, $template_file_set_id);
        if (!empty($create_items_result['error'])) {
            $docflowRequest->delete();
            return array('error' => true, 'message' => $create_items_result['message'] ?? 'Не удалось создать элементы процесса');
        }
        $docflowRequest->addHistory(
            'request_created',
            null,
            (int) ($docflowRequest->data['status'] ?? 0),
            false,
            (int) $this->id
        );

        return array(
            'error' => false,
            'message' => 'Запрос создан',
            'request_id' => (int) $docflowRequest->id,
            'reviewer_id' => $reviewer_id,
            'provider_id' => (int) $this->id,
            'procedure_code' => $docflowRequest->data['procedure_code'] ?? null,
        );
    }

    public function docflowRequestApproveFromReviewer(int $request_id): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Компания покупателя не найдена');
        if (empty($this->data['buyer'])) return array('error' => true, 'message' => 'Подтверждать процесс может только компания-покупатель');

        // TODO: проверить, что текущий пользователь прикреплён к этой компании.
        // if ((int) ($this->data['contact_id'] ?? 0) !== (int) wa()->getUser()->getId()) {
        //     return array('error' => true, 'message' => 'Пользователь не прикреплён к компании');
        // }

        $docflow_request = new pb2bDocflowRequest($request_id);
        if (!$docflow_request->id) {
            return array('error' => true, 'message' => 'Процесс не найден');
        }
        return $docflow_request->approveFromReviewer((int) $this->id);
    }

    public function docflowRequestSubmitFromProvider(int $request_id): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Компания поставщика не найдена');
        if (empty($this->data['supplier'])) return array('error' => true, 'message' => 'Отправлять процесс может только компания-поставщик');

        // TODO: проверить, что текущий пользователь прикреплён к этой компании.
        // if ((int) ($this->data['contact_id'] ?? 0) !== (int) wa()->getUser()->getId()) {
        //     return array('error' => true, 'message' => 'Пользователь не прикреплён к компании');
        // }

        $docflow_request = new pb2bDocflowRequest($request_id);
        if (!$docflow_request->id) {
            return array('error' => true, 'message' => 'Процесс не найден');
        }
        return $docflow_request->submitFromProvider((int) $this->id);
    }

    public function docflowRequestCancelFromReviewer(int $request_id, ?string $comment = null): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Компания покупателя не найдена');
        if (empty($this->data['buyer'])) return array('error' => true, 'message' => 'Отменять процесс может только компания-покупатель');

        // TODO: проверить, что текущий пользователь прикреплён к этой компании.
        // if ((int) ($this->data['contact_id'] ?? 0) !== (int) wa()->getUser()->getId()) {
        //     return array('error' => true, 'message' => 'Пользователь не прикреплён к компании');
        // }

        $docflow_request = new pb2bDocflowRequest($request_id);
        if (!$docflow_request->id) {
            return array('error' => true, 'message' => 'Процесс не найден');
        }
        return $docflow_request->cancelFromReviewer((int) $this->id, $comment);
    }

    public function docflowRequestCancelFromProvider(int $request_id, ?string $comment = null): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Компания поставщика не найдена');
        if (empty($this->data['supplier'])) return array('error' => true, 'message' => 'Отменять процесс может только компания-поставщик');

        // TODO: проверить, что текущий пользователь прикреплён к этой компании.
        // if ((int) ($this->data['contact_id'] ?? 0) !== (int) wa()->getUser()->getId()) {
        //     return array('error' => true, 'message' => 'Пользователь не прикреплён к компании');
        // }

        $docflow_request = new pb2bDocflowRequest($request_id);
        if (!$docflow_request->id) {
            return array('error' => true, 'message' => 'Процесс не найден');
        }
        return $docflow_request->cancelFromProvider((int) $this->id, $comment);
    }

    public function tenderAssertBuyer(): ?array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Компания не выбрана');
        }
        if (!$this->isBuyer()) {
            return array('error' => true, 'message' => 'Создавать тендер может только компания-покупатель');
        }
        return null;
    }

    public function tenderLoadOrganizer(int $tender_id): array
    {
        $buyer_check = $this->tenderAssertBuyer();
        if ($buyer_check !== null) {
            return $buyer_check;
        }

        $tender = new pb2bTender($tender_id);
        if ((int) ($tender['id'] ?? 0) <= 0) {
            return array('error' => true, 'message' => 'Тендер не найден');
        }
        if ((int) ($tender->data['organizer_company_id'] ?? 0) !== (int) $this->id) {
            return array('error' => true, 'message' => 'Нет доступа к этому тендеру');
        }
        if (!empty($tender->data['is_deleted'])) {
            return array('error' => true, 'message' => 'Тендер удалён');
        }

        return array('error' => false, 'tender' => $tender);
    }

    public function tenderGetWithClassifiers(int $tender_id): array
    {
        $loaded = $this->tenderLoadOrganizer($tender_id);
        if (!empty($loaded['error'])) {
            return $loaded;
        }

        $payload = (new pb2bTenderCollection())->getWithClassifiers($tender_id);
        if (!empty($payload['error'])) {
            return $payload;
        }

        return array(
            'error' => false,
            'tender' => $payload['tender'],
            'classifiers' => $payload['classifiers'],
            'invitations' => $payload['invitations'] ?? array(),
            'criteria' => $payload['criteria'] ?? array(),
        );
    }

    public function tenderSaveWizardFromBuyer(string $step, array $data, int $tender_id = 0): array
    {
        $buyer_check = $this->tenderAssertBuyer();
        if ($buyer_check !== null) {
            return $buyer_check;
        }

        if ($tender_id > 0) {
            $loaded = $this->tenderLoadOrganizer($tender_id);
            if (!empty($loaded['error'])) {
                return $loaded;
            }
        }

        $tender = new pb2bTender();
        return $tender->saveWizardStep($step, array_merge($data, array('id' => $tender_id)), (int) $this->id);
    }

    public function tenderValidateStepFromBuyer(string $step, array $data, int $tender_id = 0): array
    {
        $buyer_check = $this->tenderAssertBuyer();
        if ($buyer_check !== null) {
            return $buyer_check;
        }

        if ($tender_id > 0) {
            $loaded = $this->tenderLoadOrganizer($tender_id);
            if (!empty($loaded['error'])) {
                return $loaded;
            }
        }

        $tender = new pb2bTender($tender_id ?: null);
        return $tender->validateStep($step, array_merge($data, array(
            'organizer_company_id' => (int) $this->id,
        )));
    }

    public function tenderPublishFromBuyer(int $tender_id, ?string $reason = null, ?waContact $actor = null): array
    {
        $loaded = $this->tenderLoadOrganizer($tender_id);
        if (!empty($loaded['error'])) {
            return $loaded;
        }

        $result = $loaded['tender']->publish($reason, $actor);
        if (empty($result['error'])) {
            $result['tender_id'] = $tender_id;
            $result['status'] = (int) ($result['to_status'] ?? $loaded['tender']->data['status'] ?? 0);
        }
        return $result;
    }

    public function tenderReplaceClassifiersFromBuyer(int $tender_id, array $rows): array
    {
        $loaded = $this->tenderLoadOrganizer($tender_id);
        if (!empty($loaded['error'])) {
            return $loaded;
        }

        $result = $loaded['tender']->replaceClassifiers($rows, (int) $this->id);
        if (empty($result['error'])) {
            $result['tender_id'] = $tender_id;
        }
        return $result;
    }

    public function tenderReplaceInvitationsFromBuyer(int $tender_id, array $supplier_company_ids): array
    {
        $loaded = $this->tenderLoadOrganizer($tender_id);
        if (!empty($loaded['error'])) {
            return $loaded;
        }

        $result = $loaded['tender']->replaceInvitations($supplier_company_ids, (int) $this->id);
        if (empty($result['error'])) {
            $result['tender_id'] = $tender_id;
        }
        return $result;
    }

    public function tenderReplaceCriteriaFromBuyer(int $tender_id, array $rows): array
    {
        $loaded = $this->tenderLoadOrganizer($tender_id);
        if (!empty($loaded['error'])) {
            return $loaded;
        }

        $result = $loaded['tender']->replaceCriteria($rows, (int) $this->id);
        if (empty($result['error'])) {
            $result['tender_id'] = $tender_id;
        }
        return $result;
    }
}
