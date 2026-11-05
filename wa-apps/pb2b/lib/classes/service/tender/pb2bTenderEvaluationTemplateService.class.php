<?php

class pb2bTenderEvaluationTemplateService extends pb2bBaseService
{
    public function get(waContact $actor, pb2bCompany $company): array
    {
        if ($actor->getId() <= 0 || (int) $company->id <= 0) {
            throw new waException('Настройки недоступны', pb2bHttpStatus::FORBIDDEN);
        }
        $raw = $actor->getSettings('pb2b', 'evaluation_export_templates_' . (int) $company->id);
        if ($raw !== null) {
            $state = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($state) || empty($state['templates']) || !isset($state['selected_id'])) {
                throw new waException('Не удалось прочитать шаблоны сравнения', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
            }
            return $state;
        }
        $settings = array(
            'includeSpecification' => true,
            'includePositions' => true,
            'includeDocuments' => true,
            'includeRanks' => true,
            'includeAnalogs' => true,
        );

        return array(
            'selected_id' => 'full',
            'templates' => array(
                array('id' => 'full', 'name' => 'Полный отчёт сравнения', 'isDefault' => true, 'settings' => $settings),
                array('id' => 'prices', 'name' => 'Только стоимость и позиции', 'isDefault' => false,
                    'settings' => array_replace($settings, array('includeDocuments' => false))),
                array('id' => 'summary', 'name' => 'Сводка по спецификации', 'isDefault' => false,
                    'settings' => array_replace($settings, array('includePositions' => false, 'includeDocuments' => false, 'includeAnalogs' => false))),
            ),
        );
    }

    public function save(waContact $actor, pb2bCompany $company, string $id, string $name, array $settings): array
    {
        $state = $this->get($actor, $company);
        $name = trim($name);
        if ($name === '' || mb_strlen($name) > 120) {
            throw new waException('Укажите название шаблона длиной до 120 символов', pb2bHttpStatus::BAD_REQUEST);
        }
        $normalized = array();
        foreach (array('includeSpecification', 'includePositions', 'includeDocuments', 'includeRanks', 'includeAnalogs') as $key) {
            if (!array_key_exists($key, $settings)) {
                throw new waException('Укажите все параметры шаблона', pb2bHttpStatus::BAD_REQUEST);
            }
            $value = $settings[$key];
            if (!in_array($value, array(true, false, 0, 1, '0', '1'), true)) {
                throw new waException('Неверный параметр шаблона', pb2bHttpStatus::BAD_REQUEST);
            }
            $normalized[$key] = (bool) $value;
        }
        if (!in_array(true, $normalized, true)) {
            throw new waException('Выберите хотя бы один раздел отчёта', pb2bHttpStatus::BAD_REQUEST);
        }
        foreach ($state['templates'] as &$template) {
            if ($template['id'] === $id) {
                $template['name'] = $name;
                $template['settings'] = $normalized;
                unset($template);
                return $this->store($actor, $company, $state);
            }
        }
        unset($template);
        throw new waException('Шаблон не найден', pb2bHttpStatus::NOT_FOUND);
    }

    public function select(waContact $actor, pb2bCompany $company, string $id): array
    {
        $state = $this->get($actor, $company);
        foreach ($state['templates'] as $template) {
            if ($template['id'] === $id) {
                $state['selected_id'] = $id;
                return $this->store($actor, $company, $state);
            }
        }
        throw new waException('Шаблон не найден', pb2bHttpStatus::NOT_FOUND);
    }

    public function delete(waContact $actor, pb2bCompany $company, string $id): array
    {
        $state = $this->get($actor, $company);
        $index = array_search($id, array_column($state['templates'], 'id'), true);
        if ($index === false) {
            throw new waException('Шаблон не найден', pb2bHttpStatus::NOT_FOUND);
        }
        if (count($state['templates']) <= 1) {
            throw new waException('Нельзя удалить единственный шаблон', pb2bHttpStatus::CONFLICT);
        }
        $was_default = $state['templates'][$index]['isDefault'];
        array_splice($state['templates'], $index, 1);
        if ($was_default) $state['templates'][0]['isDefault'] = true;
        if ($state['selected_id'] === $id) {
            foreach ($state['templates'] as $template) {
                if ($template['isDefault']) $state['selected_id'] = $template['id'];
            }
        }
        return $this->store($actor, $company, $state);
    }

    private function store(waContact $actor, pb2bCompany $company, array $state): array
    {
        $saved = $actor->setSettings('pb2b', 'evaluation_export_templates_' . (int) $company->id,
            json_encode($state, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        if (!$saved) {
            throw new waException('Не удалось сохранить шаблоны', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
        }
        return $state;
    }
}
