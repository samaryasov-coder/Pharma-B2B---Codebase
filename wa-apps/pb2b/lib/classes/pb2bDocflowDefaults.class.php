<?php

/**
 * Глобальный набор образцов аккредитации (один file_set в настройках приложения).
 * В extra файла: pb2b_docflow_default, doc_name, doc_comment, company_type_id, sort.
 */
class pb2bDocflowDefaults
{
    public const SETTING_KEY = 'docflow_defaults.file_set_id';
    public const EXTRA_MARKER = 'pb2b_docflow_default';

    public static function getOrCreateFileSetId(): int
    {
        $asm = new waAppSettingsModel();
        $id = (int) $asm->get('pb2b', self::SETTING_KEY, '');
        if ($id > 0) {
            $set_model = new waproFileSetModel();
            if ($set_model->getById($id)) {
                return $id;
            }
        }
        $set = new waproFileSet();
        $id = (int) $set->getId();
        $asm->set('pb2b', self::SETTING_KEY, (string) $id);
        return $id;
    }

    public static function listDocuments(): array
    {
        $set_id = self::getOrCreateFileSetId();
        $set = new waproFileSet($set_id);
        $items = $set->getItems(false);
        $company_types = pb2bWaproHelper::getConfigOption('company_type');
        if (!is_array($company_types)) {
            $company_types = array();
        }
        $out = array();
        foreach ($items as $item) {
            $extra = isset($item['extra']) && is_array($item['extra']) ? $item['extra'] : array();
            if (empty($extra[self::EXTRA_MARKER])) {
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

    public static function nextSortValue(): int
    {
        $list = self::listDocuments();
        $max = 0;
        foreach ($list as $row) {
            if ($row['sort'] > $max) {
                $max = $row['sort'];
            }
        }
        return $max + 10;
    }

    /**
     * @return array{error:bool,message?:string,result?:int,file_id?:int}
     */
    public static function addFromUpload(string $input_name = 'file'): array
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

        $set_id = self::getOrCreateFileSetId();
        $set = new waproFileSet($set_id);
        $extra = array(
            self::EXTRA_MARKER => 1,
            'doc_name' => $name,
            'doc_comment' => $comment,
            'company_type_id' => $company_type_id,
            'sort' => self::nextSortValue(),
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

    /**
     * @return array{error:bool,message?:string}
     */
    public static function deleteFile(int $file_id): array
    {
        if ($file_id <= 0) {
            return array('error' => true, 'message' => 'Не указан файл');
        }
        $set_id = self::getOrCreateFileSetId();
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
        if (empty($extra[self::EXTRA_MARKER])) {
            return array('error' => true, 'message' => 'Некорректный тип файла');
        }
        $set = new waproFileSet($set_id);
        $set->deleteItem($file_id);
        return array('error' => false, 'message' => 'Удалено');
    }

    /**
     * @param string $scope all|organization|entrepreneur
     * @return array{error:bool,message?:string,added?:int}
     */
    public static function applyToBuyerTemplate(pb2bCompany $buyer, int $process_type, string $scope = 'all'): array
    {
        if (empty($buyer->id) || empty($buyer->data['buyer'])) {
            return array('error' => true, 'message' => 'Доступно только для компании-покупателя');
        }
        if ($process_type <= 0) {
            return array('error' => true, 'message' => 'Некорректный тип процесса');
        }
        $scope = strtolower(trim($scope));
        if (!in_array($scope, array('all', 'organization', 'entrepreneur'), true)) {
            $scope = 'all';
        }

        $defaults = self::listDocuments();
        if (empty($defaults)) {
            return array('error' => true, 'message' => 'Стандартный перечень в настройках пуст');
        }

        $filtered = array();
        foreach ($defaults as $row) {
            if (self::defaultRowMatchesScope($row, $scope)) {
                $filtered[] = $row;
            }
        }
        if (empty($filtered)) {
            return array('error' => true, 'message' => 'Нет документов для выбранного варианта перечня');
        }

        $template_model = new pb2bDocflowTemplateModel();
        $template_data = $template_model->getByField(array(
            'company_id' => (int) $buyer->id,
            'process_type' => $process_type,
        ));
        if (empty($template_data)) {
            $docflow_template = new pb2bDocflowTemplate();
            $save_result = $docflow_template->save(array(
                'company_id' => (int) $buyer->id,
                'process_type' => $process_type,
            ));
            if (!empty($save_result['error'])) {
                return array(
                    'error' => true,
                    'message' => $save_result['message'] ?? 'Не удалось создать шаблон',
                );
            }
        } else {
            $docflow_template = new pb2bDocflowTemplate((int) $template_data['id']);
        }

        if (!$docflow_template->id) {
            return array('error' => true, 'message' => 'Шаблон не найден');
        }

        $template_file_set_id = (int) ($docflow_template->data['file_set_id'] ?? 0);
        if (!$template_file_set_id) {
            return array('error' => true, 'message' => 'У шаблона нет набора файлов');
        }

        $source_set_id = self::getOrCreateFileSetId();
        $items_model = new pb2bDocflowTemplateItemsModel();
        $max_sort = self::getMaxTemplateItemSort((int) $docflow_template->id);

        $added = 0;
        $position = 0;
        foreach ($filtered as $def) {
            $source_file_id = (int) ($def['file_id'] ?? 0);
            if (!$source_file_id) {
                continue;
            }
            $copy = self::copyFileBetweenSets($source_file_id, $source_set_id, $template_file_set_id);
            if (!empty($copy['error'])) {
                return $copy;
            }
            $new_file_id = (int) ($copy['file_id'] ?? 0);
            if (!$new_file_id) {
                return array('error' => true, 'message' => 'Не удалось скопировать файл в шаблон');
            }
            $position++;
            $sort = $max_sort + $position * 10 + (int) ($def['sort'] ?? 0);

            $item_row = array(
                'template_id' => (int) $docflow_template->id,
                'name' => $def['doc_name'] !== '' ? $def['doc_name'] : $def['original_filename'],
                'comment' => $def['doc_comment'] !== '' ? $def['doc_comment'] : null,
                'company_type_id' => isset($def['company_type_id']) && $def['company_type_id'] !== null
                    ? (int) $def['company_type_id'] : null,
                'file_id' => $new_file_id,
                'sort' => $sort,
            );
            $validate = pb2bWaproHelper::validate($item_row, 'docflow_template_item');
            if (!empty($validate['error'])) {
                return $validate;
            }
            $items_model->insert($item_row);
            $added++;
        }

        return array(
            'error' => false,
            'message' => 'Добавлено позиций: '.$added,
            'added' => $added,
        );
    }

    protected static function defaultRowMatchesScope(array $row, string $scope): bool
    {
        if ($scope === 'all') {
            return true;
        }
        $ct = isset($row['company_type_id']) && $row['company_type_id'] !== null
            ? (int) $row['company_type_id'] : 0;
        if ($scope === 'organization') {
            return $ct <= 0 || $ct === 1;
        }
        if ($scope === 'entrepreneur') {
            return $ct <= 0 || $ct === 2;
        }
        return true;
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

    /**
     * @return array{error?:bool,message?:string,file_id?:int}
     */
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
        unset($extra[self::EXTRA_MARKER], $extra['doc_name'], $extra['doc_comment'], $extra['sort']);
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
