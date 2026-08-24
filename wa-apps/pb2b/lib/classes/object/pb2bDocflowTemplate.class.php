<?php

class pb2bDocflowTemplate extends pb2bWaproObject
{
    public const DEFAULTS_SETTING_KEY = 'docflow_defaults.file_set_id';
    public const DEFAULTS_EXTRA_MARKER = 'pb2b_docflow_default';

    protected pb2bDocflowTemplateModel $docflowTemplateModel;
    protected pb2bDocflowTemplateItemsModel $docflowTemplateItemsModel;

    public function __construct(?int $id = null)
    {
        parent::__construct($id);
        $this->docflowTemplateModel = new pb2bDocflowTemplateModel();
        $this->docflowTemplateItemsModel = new pb2bDocflowTemplateItemsModel();
    }

    /**
     * Возвращает привязанную компанию
     */
    public function getCompany(): ?pb2bCompany
    {
        $company = new pb2bCompany($this->data['company_id'] ?? 0);
        return $company->id ? $company : null;
    }

    /**
     * Возвращает элементы шаблона по типу компании
     *
     * @return pb2bDocflowTemplateItem[]
     */
    public function getItemsByCompanyType(pb2bCompanyType $type): array
    {
        if (!$this->id) return [];

        $rows = $this->docflowTemplateItemsModel::getIdsByTemplateAndCompanyType($this->id, $type->value);

        return array_map(
            fn(array $row) => new pb2bDocflowTemplateItem((int)$row['id']),
            $rows
        );
    }





    protected function afterDelete(array &$result): void
    {
        parent::afterDelete($result);
        // if (empty($result['error'])) {
        //     $this->companyCategoryModel->deleteByField('company_id', $this->id);
        // }
    }

    protected function getConfigFields()
    {
        return pb2bWaproHelper::getFields($this->class_name);
    }

    public function addItemFromUpload(array $data, string $input_name = 'file'): array
    {
        unset($data['id']);
        return $this->saveItemFromUpload(0, $data, $input_name);
    }

    public function updateItemFromUpload(int $item_id, array $data, string $input_name = 'file'): array
    {
        if ($item_id <= 0) {
            return array('error' => true, 'result' => 0, 'message' => 'Не передан id элемента шаблона', 'data' => array());
        }
        unset($data['id']);
        return $this->saveItemFromUpload($item_id, $data, $input_name);
    }

    protected function saveItemFromUpload(int $item_id, array $data, string $input_name = 'file'): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'result' => 0, 'message' => 'Шаблон не сохранён', 'data' => array());
        }

        $exists = array();
        $old_file_id = 0;
        if ($item_id > 0) {
            $exists = $this->docflowTemplateItemsModel->getById($item_id);
            if (empty($exists) || (int) ($exists['template_id'] ?? 0) !== (int) $this->id) {
                return array('error' => true, 'result' => 0, 'message' => 'Документ не найден или не принадлежит шаблону', 'data' => array());
            }
            $old_file_id = (int) ($exists['file_id'] ?? 0);
        }

        $item_data = array(
            'template_id' => (int) $this->id,
            'name' => (string) ($exists['name'] ?? ''),
            'comment' => $exists['comment'] ?? null,
            'company_type_id' => isset($exists['company_type_id']) && $exists['company_type_id'] !== '' && $exists['company_type_id'] !== null
                ? (int) $exists['company_type_id'] : null,
        );
        if (isset($exists['file_id'])) {
            $item_data['file_id'] = (int) $exists['file_id'];
        }
        if (isset($exists['sort'])) {
            $item_data['sort'] = (int) $exists['sort'];
        }

        if (array_key_exists('name', $data)) {
            $item_data['name'] = trim((string) $data['name']);
        }
        if (array_key_exists('comment', $data)) {
            $item_data['comment'] = $data['comment'];
        }
        if (array_key_exists('company_type_id', $data)) {
            $company_type_id = $data['company_type_id'];
            if ($company_type_id === '' || $company_type_id === null || (int) $company_type_id <= 0) {
                $item_data['company_type_id'] = null;
            } else {
                $item_data['company_type_id'] = (int) $company_type_id;
            }
        }
        if (array_key_exists('sort', $data)) {
            $item_data['sort'] = (int) $data['sort'];
        }

        $upload_result = $this->uploadItemFileFromPost($input_name);
        if (!empty($upload_result['error'])) {
            return $upload_result;
        }
        $new_file_id = (int) ($upload_result['file_id'] ?? 0);
        if ($new_file_id > 0) {
            $item_data['file_id'] = $new_file_id;
        }

        $result_validate = pb2bWaproHelper::validate($item_data, 'docflow_template_item');
        if (!empty($result_validate['error'])) {
            return $result_validate;
        }

        if ($item_id > 0) {
            $update = array();
            foreach (array('name', 'comment', 'company_type_id', 'sort') as $field) {
                if (array_key_exists($field, $data)) {
                    $update[$field] = $item_data[$field] ?? null;
                }
            }
            if ($new_file_id > 0) {
                $update['file_id'] = $new_file_id;
            }
            if (empty($update)) {
                return array('error' => true, 'result' => 0, 'message' => 'Нет данных для обновления', 'data' => array('template_item_id' => $item_id));
            }

            $this->docflowTemplateItemsModel->updateById($item_id, $update);
            if ($new_file_id > 0 && $old_file_id > 0 && $old_file_id !== $new_file_id) {
                $set = new waproFileSet((int) $this->data['file_set_id']);
                $set->deleteItem($old_file_id);
            }

            return array(
                'error' => false,
                'result' => 1,
                'message' => 'Обновлено',
                'data' => array(
                    'template_item_id' => $item_id,
                    'file_id' => (int) ($item_data['file_id'] ?? 0),
                ),
            );
        }

        $insert = array(
            'template_id' => (int) $this->id,
            'name' => (string) ($item_data['name'] ?? ''),
            'comment' => $item_data['comment'] ?? null,
            'company_type_id' => $item_data['company_type_id'] ?? null,
        );
        if (isset($item_data['file_id'])) {
            $insert['file_id'] = (int) $item_data['file_id'];
        }
        if (isset($item_data['sort'])) {
            $insert['sort'] = (int) $item_data['sort'];
        }

        $new_id = (int) $this->docflowTemplateItemsModel->insert($insert);
        return array(
            'error' => false,
            'result' => 1,
            'message' => 'Создано',
            'data' => array(
                'template_item_id' => $new_id,
                'file_id' => (int) ($insert['file_id'] ?? 0),
            ),
        );
    }

    protected function uploadItemFileFromPost(string $input_name): array
    {
        $file = waRequest::file($input_name);
        if (!$file || !$file->uploaded()) {
            return array('error' => false, 'file_id' => 0);
        }

        $file_set_id = (int) ($this->data['file_set_id'] ?? 0);
        if (!$file_set_id) {
            return array('error' => true, 'result' => 0, 'message' => 'У шаблона нет file_set_id', 'data' => array());
        }

        $set = new waproFileSet($file_set_id);
        $upload = $set->uploadFromPost($input_name, array('is_public' => 0));
        $upload_row = is_array($upload) && !empty($upload[0]) && is_array($upload[0]) ? $upload[0] : array();
        if ((int) ($upload_row['result'] ?? 0) !== 1) {
            return array(
                'error' => true,
                'result' => 0,
                'message' => (string) ($upload_row['message'] ?? 'Не удалось загрузить файл'),
                'data' => array(),
            );
        }

        $new_file_id = (int) ($upload_row['file_id'] ?? 0);
        if ($new_file_id <= 0) {
            return array('error' => true, 'result' => 0, 'message' => 'Не удалось получить id файла', 'data' => array());
        }

        return array('error' => false, 'file_id' => $new_file_id);
    }

    public function getItemById(int $item_id): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Шаблон не найден');
        }
        if ($item_id <= 0) {
            return array('error' => true, 'message' => 'Не передан id элемента шаблона');
        }

        $item = $this->docflowTemplateItemsModel->getById($item_id);
        if (empty($item) || (int) ($item['template_id'] ?? 0) !== (int) $this->id) {
            return array('error' => true, 'message' => 'Элемент шаблона не найден');
        }

        $file_data = array();
        $file_id = (int) ($item['file_id'] ?? 0);
        if ($file_id > 0) {
            $file_set_id = (int) ($this->data['file_set_id'] ?? 0);
            if ($file_set_id > 0) {
                $set = new waproFileSet($file_set_id);
                $set_file = $set->getItem($file_id);
                if (is_array($set_file)) {
                    $download_data = waproFileSet::getDownloadData(pb2bWaproHelper::getAppId(), $file_id);
                    $set_file['download_data'] = $download_data;
                    $set_file['download_url'] = !empty($download_data['backend_url'])
                        ? (string) $download_data['backend_url']
                        : '?module=files&action=download&file_id='.$file_id;
                    $file_data = $set_file;
                }
            }
        }

        return array(
            'error' => false,
            'item' => array(
                'id' => (int) ($item['id'] ?? 0),
                'template_id' => (int) ($item['template_id'] ?? 0),
                'name' => (string) ($item['name'] ?? ''),
                'comment' => isset($item['comment']) ? (string) $item['comment'] : null,
                'company_type_id' => isset($item['company_type_id']) && $item['company_type_id'] !== '' && $item['company_type_id'] !== null
                    ? (int) $item['company_type_id'] : null,
                'file_id' => $file_id,
                'sort' => isset($item['sort']) ? (int) $item['sort'] : null,
                'file' => $file_data,
            ),
        );
    }

    public function deleteItemById(int $item_id): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Шаблон не найден');
        }
        if ($item_id <= 0) {
            return array('error' => true, 'message' => 'Не передан id элемента шаблона');
        }

        $item = $this->docflowTemplateItemsModel->getById($item_id);
        if (empty($item) || (int) ($item['template_id'] ?? 0) !== (int) $this->id) {
            return array('error' => true, 'message' => 'Элемент шаблона не найден');
        }

        $file_id = (int) ($item['file_id'] ?? 0);
        if ($file_id > 0) {
            $file_set_id = (int) ($this->data['file_set_id'] ?? 0);
            if ($file_set_id > 0) {
                $set = new waproFileSet($file_set_id);
                $set->deleteItem($file_id);
            }
        }

        $this->docflowTemplateItemsModel->deleteById($item_id);

        return array(
            'error' => false,
            'message' => 'Элемент шаблона удалён',
            'data' => array(
                'template_item_id' => $item_id,
            ),
        );
    }

    public function getItems(array $params = array())
    {
        if (empty($this->id)) return array();

        $sort_by = strtolower((string) ($params['sort_by'] ?? 'sort'));
        $sort_dir = strtolower((string) ($params['sort_dir'] ?? 'asc'));
        $sort_dir = $sort_dir === 'desc' ? 'DESC' : 'ASC';
        $sort_by_file_name = in_array($sort_by, array('file_name', 'template_file_name', 'template_file'), true);

        $sort_map = array(
            'id' => array('field' => 'id', 'table' => 'DTI'),
            'name' => array('field' => 'name', 'table' => 'DTI'),
            'comment' => array('field' => 'comment', 'table' => 'DTI'),
            'file_id' => array('field' => 'file_id', 'table' => 'DTI'),
            'company_type_id' => array('field' => 'company_type_id', 'table' => 'DTI'),
            'sort' => array('field' => 'sort', 'table' => 'DTI'),
        );
        if (!$sort_by_file_name && empty($sort_map[$sort_by])) $sort_by = 'sort';

        $order = array();
        if ($sort_by_file_name) {
            $order['sort'] = array('table' => 'DTI', 'dir' => 'ASC');
            $order['id'] = array('table' => 'DTI', 'dir' => 'ASC');
        } else {
            $order[$sort_map[$sort_by]['field']] = array(
                'table' => $sort_map[$sort_by]['table'],
                'dir' => $sort_dir,
            );
            if ($sort_by !== 'id') $order['id'] = array('table' => 'DTI', 'dir' => 'ASC');
        }

        $collection = new pb2bDocflowTemplateCollection('items.id='.$this->id);
        $rows = $collection->getCollection(array(
            'key' => false,
            'order' => $order,
        ));
        if (!is_array($rows) || empty($rows)) return array();

        $file_ids = array();
        foreach ($rows as $row) {
            $file_id = $row['item_file_id'] ?? 0;
            if ($file_id > 0) $file_ids[$file_id] = $file_id;
        }

        $files = array();
        $app_id = pb2bWaproHelper::getAppId();
        $template_file_set_id = (int) ($this->data['file_set_id'] ?? ($rows[0]['file_set_id'] ?? 0));
        if (!empty($file_ids) && $template_file_set_id > 0) {
            $file_set = new waproFileSet($template_file_set_id);
            $file_rows = $file_set->getItems();
            if (!is_array($file_rows)) $file_rows = array();

            foreach ($file_rows as $file_row) {
                if (!is_array($file_row)) continue;
                $file_id = (int) ($file_row['id'] ?? 0);
                if ($file_id <= 0) {
                    continue;
                }
                if (empty($file_ids[$file_id])) {
                    continue;
                }

                $download_data = waproFileSet::getDownloadData($app_id, $file_id);
                if (!empty($download_data['result'])) {
                    $file_row['download_data'] = $download_data;
                    $file_row['download_url'] = (string) ($download_data['backend_url'] ?? '');
                } else {
                    $file_row['download_data'] = $download_data;
                    $file_row['download_url'] = '?module=files&action=download&file_id='.$file_id;
                }
                $files[$file_id] = $file_row;
            }
        }

        foreach ($rows as $key => $row) {
            $file_id = (int) ($row['item_file_id'] ?? 0);
            $rows[$key]['item_file'] = $file_id > 0 ? ($files[$file_id] ?? array()) : array();
            $rows[$key]['item_file_download_url'] = $file_id > 0
                ? '?module=files&action=download&file_id='.$file_id
                : null;
        }

        if ($sort_by_file_name) {
            usort($rows, function ($a, $b) use ($sort_dir) {
                $a_file = is_array($a['item_file'] ?? null) ? $a['item_file'] : array();
                $b_file = is_array($b['item_file'] ?? null) ? $b['item_file'] : array();

                $a_name = (string) ($a_file['name'] ?? '');
                if ($a_name === '') $a_name = (string) ($a_file['original_filename'] ?? '');
                if ($a_name === '') $a_name = (string) ($a_file['filename'] ?? '');

                $b_name = (string) ($b_file['name'] ?? '');
                if ($b_name === '') $b_name = (string) ($b_file['original_filename'] ?? '');
                if ($b_name === '') $b_name = (string) ($b_file['filename'] ?? '');

                $a_sort_name = function_exists('mb_strtolower') ? mb_strtolower($a_name, 'UTF-8') : strtolower($a_name);
                $b_sort_name = function_exists('mb_strtolower') ? mb_strtolower($b_name, 'UTF-8') : strtolower($b_name);

                if ($a_sort_name === $b_sort_name) {
                    $a_id = (int) ($a['item_id'] ?? 0);
                    $b_id = (int) ($b['item_id'] ?? 0);
                    return $a_id <=> $b_id;
                }

                $result = strcmp($a_sort_name, $b_sort_name);
                if ($sort_dir === 'DESC') $result *= -1;
                return $result;
            });
        }

        return $rows;
    }

    public function getItemsWithFiles(array $params = array())
    {
        if (empty($this->id)) return array();

        $sort_by = strtolower((string) ($params['sort_by'] ?? 'sort'));
        $sort_dir = strtolower((string) ($params['sort_dir'] ?? 'asc'));
        $sort_dir = $sort_dir === 'desc' ? 'DESC' : 'ASC';

        $sort_map = array(
            'id' => array('field' => 'id', 'table' => 'DTI'),
            'name' => array('field' => 'name', 'table' => 'DTI'),
            'comment' => array('field' => 'comment', 'table' => 'DTI'),
            'file_id' => array('field' => 'file_id', 'table' => 'DTI'),
            'company_type_id' => array('field' => 'company_type_id', 'table' => 'DTI'),
            'sort' => array('field' => 'sort', 'table' => 'DTI'),
            'file_name' => array('field' => 'name', 'table' => 'FSI'),
            'template_file' => array('field' => 'name', 'table' => 'FSI'),
            'template_file_name' => array('field' => 'name', 'table' => 'FSI'),
        );
        if (empty($sort_map[$sort_by])) $sort_by = 'sort';

        $order = array();
        $order[$sort_map[$sort_by]['field']] = array(
            'table' => $sort_map[$sort_by]['table'],
            'dir' => $sort_dir,
        );
        if ($sort_by !== 'id') {
            $order['id'] = array('table' => 'DTI', 'dir' => 'ASC');
        }

        $collection = new pb2bDocflowTemplateCollection('itemsWithFiles.id='.$this->id);
        $rows = $collection->getCollection(array(
            'key' => false,
            'order' => $order,
            'select' => array(
                'id' => 'template_id',
                'company_id' => null,
                'file_set_id' => null,
                'process_type' => null,
                'auto_request_enabled' => null,
                'refresh_period_days' => null,
                array('field' => 'id', 'table' => 'DTI', 'as' => 'item_id'),
                array('field' => 'name', 'table' => 'DTI', 'as' => 'item_name'),
                array('field' => 'comment', 'table' => 'DTI', 'as' => 'item_comment'),
                array('field' => 'file_id', 'table' => 'DTI', 'as' => 'item_file_id'),
                array('field' => 'sort', 'table' => 'DTI', 'as' => 'item_sort'),
                array('field' => 'company_type_id', 'table' => 'DTI', 'as' => 'item_company_type_id'),
                array('field' => 'id', 'table' => 'FSI', 'as' => 'file_id'),
                array('field' => 'set_id', 'table' => 'FSI', 'as' => 'file_set_id_join'),
                array('field' => 'name', 'table' => 'FSI', 'as' => 'file_name'),
                array('field' => 'description', 'table' => 'FSI', 'as' => 'file_description'),
                array('field' => 'filename', 'table' => 'FSI', 'as' => 'file_filename'),
                array('field' => 'original_filename', 'table' => 'FSI', 'as' => 'file_original_filename'),
                array('field' => 'ext', 'table' => 'FSI', 'as' => 'file_ext'),
                array('field' => 'is_public', 'table' => 'FSI', 'as' => 'file_is_public'),
                array('field' => 'sort', 'table' => 'FSI', 'as' => 'file_sort'),
                array('field' => 'create_datetime', 'table' => 'FSI', 'as' => 'file_create_datetime'),
                array('field' => 'update_datetime', 'table' => 'FSI', 'as' => 'file_update_datetime'),
                array('field' => 'extra', 'table' => 'FSI', 'as' => 'file_extra'),
            )
        ));

        if (!is_array($rows) || empty($rows)) return array();

        $app_id = pb2bWaproHelper::getAppId();
        foreach ($rows as $key => $row) {
            $file_id = (int) ($row['item_file_id'] ?? 0);

            $file = array(
                'id' => (int) ($row['file_id'] ?? 0),
                'set_id' => (int) ($row['file_set_id_join'] ?? 0),
                'name' => (string) ($row['file_name'] ?? ''),
                'description' => isset($row['file_description']) ? (string) $row['file_description'] : null,
                'filename' => (string) ($row['file_filename'] ?? ''),
                'original_filename' => (string) ($row['file_original_filename'] ?? ''),
                'ext' => (string) ($row['file_ext'] ?? ''),
                'is_public' => isset($row['file_is_public']) ? (int) $row['file_is_public'] : 0,
                'sort' => isset($row['file_sort']) ? (int) $row['file_sort'] : 0,
                'create_datetime' => $row['file_create_datetime'] ?? null,
                'update_datetime' => $row['file_update_datetime'] ?? null,
                'extra' => array(),
            );

            if (!empty($row['file_extra'])) {
                $decoded_extra = json_decode((string) $row['file_extra'], true);
                if (is_array($decoded_extra)) {
                    $file['extra'] = $decoded_extra;
                }
            }

            $download_data = array();
            if ($file_id > 0) $download_data = waproFileSet::getDownloadData($app_id, $file_id);
            $file['download_data'] = $download_data;
            $file['download_url'] = !empty($download_data['backend_url'])
                ? (string) $download_data['backend_url']
                : ($file_id > 0 ? '?module=files&action=download&file_id='.$file_id : '');

            $rows[$key]['item_file'] = $file_id > 0 ? $file : array();
            $rows[$key]['item_file_download_url'] = $file_id > 0 ? $file['download_url'] : null;
        }

        return $rows;
    }

    /**
     * Добавляет в шаблон выбранные стандартные документы по id файлов из набора defaults.
     *
     * @param array|string $default_file_ids
     * @return array{error:bool,message?:string,added?:int}
     */
    public function addDefaultItemsByFileIds($default_file_ids): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Шаблон не найден');
        }

        $ids = array();
        if (is_string($default_file_ids)) {
            $default_file_ids = explode(',', $default_file_ids);
        }
        if (is_array($default_file_ids)) {
            foreach ($default_file_ids as $file_id) {
                $file_id = (int) $file_id;
                if ($file_id > 0) {
                    $ids[$file_id] = $file_id;
                }
            }
        }
        $ids = array_values($ids);
        if (empty($ids)) {
            return array('error' => true, 'message' => 'Не переданы стандартные документы');
        }

        $template_file_set_id = (int) ($this->data['file_set_id'] ?? 0);
        if (!$template_file_set_id) {
            return array('error' => true, 'message' => 'У шаблона отсутствует file_set_id');
        }

        $defaults = self::getDefaultDocuments();
        $defaults_by_file_id = array();
        foreach ($defaults as $row) {
            $file_id = (int) ($row['file_id'] ?? 0);
            if ($file_id > 0) {
                $defaults_by_file_id[$file_id] = $row;
            }
        }

        $missing = array();
        $selected_defaults = array();
        foreach ($ids as $file_id) {
            if (isset($defaults_by_file_id[$file_id])) {
                $selected_defaults[$file_id] = $defaults_by_file_id[$file_id];
            } else {
                $missing[] = $file_id;
            }
        }
        if (!empty($missing)) {
            return array(
                'error' => true,
                'message' => 'Стандартные документы не найдены: '.implode(', ', $missing),
            );
        }

        $source_set_id = self::getOrCreateDefaultsFileSetId();
        $items_model = new pb2bDocflowTemplateItemsModel();
        $max_sort = self::getMaxTemplateItemSort((int) $this->id);
        $added = 0;
        $position = 0;

        foreach ($selected_defaults as $source_file_id => $def) {
            $copy = self::copyFileBetweenSets((int) $source_file_id, $source_set_id, $template_file_set_id);
            if (!empty($copy['error'])) {
                return $copy;
            }

            $new_file_id = (int) ($copy['file_id'] ?? 0);
            if (!$new_file_id) {
                return array('error' => true, 'message' => 'Не удалось скопировать стандартный файл');
            }

            $position++;
            $sort = $max_sort + $position * 10 + (int) ($def['sort'] ?? 0);
            $item_row = array(
                'template_id' => (int) $this->id,
                'name' => $def['doc_name'] !== '' ? $def['doc_name'] : $def['original_filename'],
                'comment' => $def['doc_comment'] !== '' ? $def['doc_comment'] : null,
                'company_type_id' => isset($def['company_type_id']) && $def['company_type_id'] !== null
                    ? (int) $def['company_type_id'] : null,
                'file_id' => $new_file_id,
                'sort' => $sort,
            );
            $validate = pb2bWaproHelper::validate($item_row, 'docflow_template_item');
            if (!empty($validate['error'])) return $validate;

            $items_model->insert($item_row);
            $added++;
        }

        return array(
            'error' => false,
            'message' => 'Добавлено позиций: '.$added,
            'added' => $added,
        );
    }

    /**
     * Позиции шаблона для формирования заявки.
     *
     * @param array $item_ids Пусто — все подходящие по типу; иначе пересечение с выбранными id.
     * @param int|null $for_provider_company_type_id id из справочника company_type; null — без фильтра по типу (обратная совместимость).
     */
    public function getRequestSourceItems(array $item_ids = array(), ?int $for_provider_company_type_id = null): array
    {
        if (empty($this->id)) return array();

        $where = array(
            'template_id' => array('simile' => '=', 'value' => (int) $this->id),
        );
        if (!empty($item_ids)) {
            $where['id'] = array('simile' => 'IN', 'value' => $item_ids);
        }

        $this->docflowTemplateItemsModel->setFetch('all');
        $this->docflowTemplateItemsModel->setSelect(array(
            'id' => null,
            'template_id' => null,
            'name' => null,
            'comment' => null,
            'file_id' => null,
            'sort' => null,
            'company_type_id' => null,
        ));
        $this->docflowTemplateItemsModel->setWhere($where);
        $this->docflowTemplateItemsModel->setOrderBy(array(
            'sort' => 'ASC',
            'id' => 'ASC',
        ));

        $rows = $this->docflowTemplateItemsModel->queryRun();
        if ($for_provider_company_type_id !== null && $for_provider_company_type_id > 0) {
            $rows = array_values(array_filter($rows, function (array $row) use ($for_provider_company_type_id): bool {
                $ct = isset($row['company_type_id']) && $row['company_type_id'] !== '' && $row['company_type_id'] !== null
                    ? (int) $row['company_type_id'] : 0;
                return $ct <= 0 || $ct === $for_provider_company_type_id;
            }));
        }

        return $rows;
    }

    public function getRefreshPolicyPreview(array $params = array()): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Шаблон не найден');
        $old_refresh_period_days = isset($this->data['refresh_period_days']) ? (int) $this->data['refresh_period_days'] : null;
        if ($old_refresh_period_days !== null && $old_refresh_period_days <= 0) {
            $old_refresh_period_days = null;
        }

        $refresh_param_exists = array_key_exists('refresh_period_days', $params);
        $new_refresh_period_days = $old_refresh_period_days;
        if ($refresh_param_exists) {
            $raw_refresh_period = $params['refresh_period_days'];
            if (is_string($raw_refresh_period)) {
                $raw_refresh_period = trim($raw_refresh_period);
            }
            if ($raw_refresh_period === '' || $raw_refresh_period === null) {
                $new_refresh_period_days = null;
            } elseif (!is_numeric($raw_refresh_period) || (int) $raw_refresh_period <= 0) {
                return array('error' => true, 'message' => 'refresh_period_days должен быть больше 0 или null');
            } else {
                $new_refresh_period_days = (int) $raw_refresh_period;
            }
        }

        $is_decrease = $old_refresh_period_days !== null && $new_refresh_period_days !== null && $new_refresh_period_days < $old_refresh_period_days;
        $has_refresh_change = $refresh_param_exists && $old_refresh_period_days !== $new_refresh_period_days;
        $requires_decision_on_existing = $has_refresh_change && ($old_refresh_period_days === null || $is_decrease);

        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $approved_status = (int) ($request_statuses['approved']['id'] ?? 3);

        $request_model = new pb2bDocflowRequestModel();
        $requests = $request_model->getByField(array(
            'template_id' => (int) $this->id,
            'status' => $approved_status,
        ), true);
        if (!is_array($requests)) $requests = array();

        $now_ts = time();
        $provider_ids = array();
        $requests_preview = array();

        foreach ($requests as $request) {
            $request_id = (int) ($request['id'] ?? 0);
            $provider_id = (int) ($request['provider_id'] ?? 0);
            $approved_datetime = $request['approved_datetime'] ?? null;
            if (!is_string($approved_datetime) || trim($approved_datetime) === '') {
                $approved_datetime = null;
            }

            $current_expires_datetime = $request['expires_datetime'] ?? null;
            if (!is_string($current_expires_datetime) || trim($current_expires_datetime) === '') {
                $current_expires_datetime = null;
            }

            $new_expires_datetime = null;
            if ($new_refresh_period_days !== null && $approved_datetime !== null) {
                $approved_ts = strtotime($approved_datetime);
                if ($approved_ts !== false) {
                    $new_expires_datetime = date('Y-m-d H:i:s', $approved_ts + $new_refresh_period_days * 86400);
                }
            }

            $is_expired_by_new_period = false;
            if ($new_expires_datetime !== null) {
                $new_expires_ts = strtotime($new_expires_datetime);
                if ($new_expires_ts !== false && $new_expires_ts <= $now_ts) {
                    $is_expired_by_new_period = true;
                }
            }

            if ($provider_id > 0) {
                $provider_ids[$provider_id] = $provider_id;
            }

            $requests_preview[] = array(
                'request_id' => $request_id,
                'provider_id' => $provider_id,
                'provider_name' => '',
                'procedure_code' => (string) ($request['procedure_code'] ?? ''),
                'approved_datetime' => $approved_datetime,
                'current_expires_datetime' => $current_expires_datetime,
                'new_expires_datetime' => $new_expires_datetime,
                'is_expired_by_new_period' => $is_expired_by_new_period ? 1 : 0,
            );
        }

        $provider_names = array();
        if (!empty($provider_ids)) {
            $company_model = new pb2bCompanyModel();
            $company_rows = $company_model->getByField('id', array_values($provider_ids), true);
            if (is_array($company_rows)) {
                foreach ($company_rows as $company_row) {
                    $provider_names[(int) ($company_row['id'] ?? 0)] = (string) ($company_row['name'] ?? '');
                }
            }
        }

        $reaccreditation_companies_map = array();
        foreach ($requests_preview as $k => $row) {
            $provider_id = (int) ($row['provider_id'] ?? 0);
            $provider_name = $provider_names[$provider_id] ?? '';
            $requests_preview[$k]['provider_name'] = $provider_name;

            if (!$requires_decision_on_existing || empty($row['is_expired_by_new_period']) || $provider_id <= 0) {
                continue;
            }
            if (isset($reaccreditation_companies_map[$provider_id])) {
                continue;
            }

            $reaccreditation_companies_map[$provider_id] = array(
                'provider_id' => $provider_id,
                'provider_name' => $provider_name,
                'request_id' => (int) ($row['request_id'] ?? 0),
                'procedure_code' => (string) ($row['procedure_code'] ?? ''),
                'approved_datetime' => $row['approved_datetime'] ?? null,
                'current_expires_datetime' => $row['current_expires_datetime'] ?? null,
                'new_expires_datetime' => $row['new_expires_datetime'] ?? null,
            );
        }

        return array(
            'error' => false,
            'mode' => 'preview',
            'template_id' => (int) $this->id,
            'old_refresh_period_days' => $old_refresh_period_days,
            'new_refresh_period_days' => $new_refresh_period_days,
            'reaccreditation_companies' => array_values($reaccreditation_companies_map),
            'requests_preview' => $requests_preview,
        );
    }

    public function applyRefreshPolicy(array $params = array()): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Шаблон не найден');

        $preview = $this->getRefreshPolicyPreview($params);
        if (!empty($preview['error']))  return $preview;

        $has_refresh_change = !empty($preview['has_refresh_change']);
        $refresh_param_exists = !empty($preview['refresh_param_exists']);
        $requires_decision_on_existing = !empty($preview['requires_decision_on_existing']);

        $raw_decrease_mode = $params['decrease_mode'] ?? ($params['refresh_decrease_mode'] ?? null);
        $decrease_mode_code = trim((string) $raw_decrease_mode);
        if (is_numeric($decrease_mode_code)) {
            $decrease_mode_code = (int) $decrease_mode_code === 1
                ? 'expire_outdated'
                : ((int) $decrease_mode_code === 2 ? 'keep_existing' : '');
        }

        if ($requires_decision_on_existing) {
            if ($decrease_mode_code === '') {
                return array(
                    'error' => true,
                    'message' => 'Для уменьшения срока выберите режим применения',
                    'data' => $preview,
                );
            }
            if (!in_array($decrease_mode_code, array('expire_outdated', 'keep_existing'), true)) {
                return array(
                    'error' => true,
                    'message' => 'Некорректный decrease_mode',
                    'data' => $preview,
                );
            }
        }

        $template_update = array();
        if ($refresh_param_exists && isset($this->model->fields['refresh_period_days'])) {
            $template_update['refresh_period_days'] = $preview['new_refresh_period_days'];
        }
        if (array_key_exists('auto_request_enabled', $params) && isset($this->model->fields['auto_request_enabled'])) {
            $auto_request_enabled = (int) $params['auto_request_enabled'];
            $template_update['auto_request_enabled'] = $auto_request_enabled === 1 ? 1 : 0;
        }
        if (!empty($template_update)) {
            $this->model->updateById($this->id, $template_update);
            foreach ($template_update as $field => $value) {
                $this->data[$field] = $value;
            }
        }

        $updated_requests = 0;
        $expired_requests = 0;
        if ($has_refresh_change) {
            $request_model = new pb2bDocflowRequestModel();
            if (isset($request_model->fields['expires_datetime'])) {
                $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
                $approved_status = (int) ($request_statuses['approved']['id'] ?? 3);
                $expired_status = (int) ($request_statuses['expired']['id'] ?? 0);
                $now = date('Y-m-d H:i:s');

                foreach ((array) ($preview['requests_preview'] ?? array()) as $row) {
                    $request_id = (int) ($row['request_id'] ?? 0);
                    if ($request_id <= 0) {
                        continue;
                    }

                    if ($requires_decision_on_existing && $decrease_mode_code === 'keep_existing') {
                        continue;
                    }

                    $request_update = array(
                        'expires_datetime' => $row['new_expires_datetime'] ?? null,
                    );
                    if (isset($request_model->fields['expired_datetime'])) {
                        $request_update['expired_datetime'] = null;
                    }

                    $expire_now = $requires_decision_on_existing
                        && $decrease_mode_code === 'expire_outdated'
                        && !empty($row['is_expired_by_new_period']);
                    if ($expire_now && $expired_status > 0 && isset($request_model->fields['status'])) {
                        $request_update['status'] = $expired_status;
                        if (isset($request_model->fields['expired_datetime'])) {
                            $request_update['expired_datetime'] = $now;
                        }
                        if (isset($request_model->fields['update_datetime'])) {
                            $request_update['update_datetime'] = $now;
                        }
                    }

                    $request_model->updateById($request_id, $request_update);
                    $updated_requests++;

                    if ($expire_now && isset($request_update['status'])) {
                        $expired_requests++;
                        $request_object = new pb2bDocflowRequest($request_id);
                        if (!empty($request_object->id)) {
                            $request_object->addHistory(
                                'request_expired',
                                $approved_status,
                                $expired_status,
                                false,
                                (int) ($this->data['company_id'] ?? 0)
                            );
                        }
                    }
                }
            }
        }

        $result = $preview;
        $result['mode'] = 'apply';
        $result['decrease_mode'] = $decrease_mode_code;
        $result['updated_requests'] = $updated_requests;
        $result['expired_requests'] = $expired_requests;
        if ($requires_decision_on_existing && $decrease_mode_code === 'keep_existing') {
            $result['message'] = 'Параметры шаблона применены. Текущие заявки не изменены';
        } elseif ($requires_decision_on_existing && $decrease_mode_code === 'expire_outdated') {
            $result['message'] = 'Параметры шаблона применены. Просроченные заявки переведены в статус "Истёк срок"';
        } else {
            $result['message'] = 'Параметры срока применены';
        }

        return $result;
    }

    //default

    public static function getOrCreateDefaultsFileSetId(): int
    {
        $asm = new waAppSettingsModel();
        $id = (int) $asm->get('pb2b', self::DEFAULTS_SETTING_KEY, '');
        if ($id > 0) {
            $set_model = new waproFileSetModel();
            if ($set_model->getById($id)) return $id;
        }

        $set = new waproFileSet();
        $id = (int) $set->getId();
        $asm->set('pb2b', self::DEFAULTS_SETTING_KEY, (string) $id);

        return $id;
    }

    public static function getDefaultDocuments(): array
    {
        $set_id = self::getOrCreateDefaultsFileSetId();
        $set = new waproFileSet($set_id);
        $items = $set->getItems(false);

        $company_types = pb2bWaproHelper::getConfigOption('company_type');
        if (!is_array($company_types)) {
            $company_types = array();
        }

        $out = array();
        foreach ($items as $item) {
            $extra = isset($item['extra']) && is_array($item['extra']) ? $item['extra'] : array();
            if (empty($extra[self::DEFAULTS_EXTRA_MARKER])) {
                continue;
            }

            $ct = isset($extra['company_type_id']) ? (int) $extra['company_type_id'] : 0;
            $out[] = array(
                'file_id' => (int) ($item['id'] ?? 0),
                'doc_name' => (string) ($extra['doc_name'] ?? ''),
                'doc_comment' => isset($extra['doc_comment']) ? (string) $extra['doc_comment'] : '',
                'company_type_id' => $ct > 0 ? $ct : null,
                'company_type_name' => $ct > 0 ? ($company_types[$ct]['name'] ?? '') : 'Все типы',
                'sort' => isset($extra['sort']) ? (int) $extra['sort'] : 0,
                'original_filename' => (string) ($item['original_filename'] ?? ''),
            );
        }

        usort($out, function ($a, $b) {
            if ($a['sort'] !== $b['sort']) {
                return $a['sort'] <=> $b['sort'];
            }
            return $a['file_id'] <=> $b['file_id'];
        });

        return $out;
    }

    protected static function getNextDefaultSortValue(): int
    {
        $list = self::getDefaultDocuments();
        $max = 0;
        foreach ($list as $row) {
            if ($row['sort'] > $max) {
                $max = $row['sort'];
            }
        }

        return $max + 10;
    }
   
    public static function addDefaultFromUpload(string $input_name = 'file'): array
    {
        $name = waRequest::post('doc_name', '', waRequest::TYPE_STRING_TRIM);
        if ($name === '') {
            return array('error' => true, 'message' => 'Укажите название документа');
        }

        $comment = waRequest::post('doc_comment', '', waRequest::TYPE_STRING_TRIM);
        if ($comment === '') {
            $comment = null;
        }

        $company_type_id = waRequest::post('company_type_id', '', waRequest::TYPE_STRING_TRIM);
        if ($company_type_id === '' || $company_type_id === null) {
            $company_type_id = null;
        } else {
            $company_type_id = (int) $company_type_id;
            if ($company_type_id <= 0) {
                $company_type_id = null;
            }
        }

        $file = waRequest::file($input_name);
        if (!$file || !$file->uploaded()) {
            return array('error' => true, 'message' => 'Выберите файл');
        }

        $set_id = self::getOrCreateDefaultsFileSetId();
        $set = new waproFileSet($set_id);
        $extra = array(
            self::DEFAULTS_EXTRA_MARKER => 1,
            'doc_name' => $name,
            'doc_comment' => $comment,
            'company_type_id' => $company_type_id,
            'sort' => self::getNextDefaultSortValue(),
        );

        $upload = $set->uploadFromPost($input_name, array('is_public' => 0));
        $row = is_array($upload) && $upload !== array() ? reset($upload) : array();
        if (empty($row) || (int) ($row['result'] ?? 0) !== 1) {
            return array(
                'error' => true,
                'message' => $row['message'] ?? 'Не удалось загрузить файл',
            );
        }

        $file_id = (int) ($row['file_id'] ?? 0);
        if ($file_id) {
            $items_model = new waproFileSetItemsModel();
            $items_model->updateById($file_id, array('extra' => json_encode($extra, JSON_UNESCAPED_UNICODE)));
        }

        return array(
            'error' => false,
            'message' => 'Документ добавлен',
            'result' => 1,
            'file_id' => $file_id,
        );
    }

    public static function deleteDefaultFile(int $file_id): array
    {
        if ($file_id <= 0) {
            return array('error' => true, 'message' => 'Не указан файл');
        }

        $set_id = self::getOrCreateDefaultsFileSetId();
        $items_model = new waproFileSetItemsModel();
        $row = $items_model->getById($file_id);
        if (empty($row) || (int) ($row['set_id'] ?? 0) !== $set_id) {
            return array('error' => true, 'message' => 'Файл не найден');
        }

        $extra = array();
        if (!empty($row['extra'])) {
            $decoded = json_decode((string) $row['extra'], true);
            if (is_array($decoded)) {
                $extra = $decoded;
            }
        }

        if (empty($extra[self::DEFAULTS_EXTRA_MARKER])) {
            return array('error' => true, 'message' => 'Некорректный тип файла');
        }

        $set = new waproFileSet($set_id);
        $set->deleteItem($file_id);

        return array('error' => false, 'message' => 'Удалено');
    }
    protected static function getMaxTemplateItemSort(int $template_id): int
    {
        $model = new pb2bDocflowTemplateItemsModel();
        $model->setFetch('all');
        $model->setSelect(array('sort' => null));
        $model->setWhere(array(
            'template_id' => array('simile' => '=', 'value' => $template_id),
        ));
        $rows = $model->queryRun();
        if (!is_array($rows)) {
            return 0;
        }

        $max = 0;
        foreach ($rows as $r) {
            $s = (int) ($r['sort'] ?? 0);
            if ($s > $max) {
                $max = $s;
            }
        }

        return $max;
    }
    public static function copyFileBetweenSets(int $source_file_id, int $source_set_id, int $target_set_id): array
    {
        $file_items_model = new waproFileSetItemsModel();
        $source_file = $file_items_model->getById($source_file_id);
        if (empty($source_file)) {
            return array('error' => true, 'message' => 'Исходный файл не найден');
        }
        if ((int) ($source_file['set_id'] ?? 0) !== $source_set_id) {
            return array('error' => true, 'message' => 'Файл не из стандартного набора');
        }

        $insert = $source_file;
        unset($insert['id']);
        $insert['set_id'] = $target_set_id;

        $extra = array();
        if (!empty($source_file['extra'])) {
            $decoded = json_decode((string) $source_file['extra'], true);
            if (is_array($decoded)) {
                $extra = $decoded;
            }
        }
        unset($extra[self::DEFAULTS_EXTRA_MARKER], $extra['doc_name'], $extra['doc_comment'], $extra['sort']);
        $extra['copied_from_default_file_id'] = $source_file_id;
        $insert['extra'] = json_encode($extra, JSON_UNESCAPED_UNICODE);

        $new_file_id = (int) $file_items_model->insert($insert);
        if (!$new_file_id) {
            return array('error' => true, 'message' => 'Не удалось создать запись файла');
        }

        $app_id = pb2bWaproHelper::getAppId();
        $source_path = waproFileSet::getPath(
            $app_id,
            (int) $source_file['set_id'],
            (int) $source_file['is_public'],
            (int) $source_file['id'],
            (string) $source_file['ext']
        );
        if (!file_exists($source_path)) {
            $file_items_model->deleteById($new_file_id);
            return array('error' => true, 'message' => 'Исходный файл отсутствует на диске');
        }

        $target_dir = waproFileSet::getPath(
            $app_id,
            $target_set_id,
            (int) $source_file['is_public']
        );
        if (!file_exists($target_dir) && !waFiles::create($target_dir)) {
            $file_items_model->deleteById($new_file_id);
            return array('error' => true, 'message' => 'Не удалось создать каталог для файла');
        }

        $target_path = waproFileSet::getPath(
            $app_id,
            $target_set_id,
            (int) $source_file['is_public'],
            $new_file_id,
            (string) $source_file['ext']
        );
        try {
            waFiles::copy($source_path, $target_path);
        } catch (Exception $e) {
            $file_items_model->deleteById($new_file_id);
            return array('error' => true, 'message' => 'Не удалось скопировать файл');
        }

        return array('error' => false, 'file_id' => $new_file_id);
    }
}
