<?php

class pb2bClient extends pb2bWaproObject
{
    protected array $contacts_to_save = array();
    public function __construct(?int $id = null)
    {
        parent::__construct($id);
    }

    protected function preSave(array &$data): array
    {
        $contacts = ifempty($data['contacts'], array());
        unset($data['contacts']);

        $result = parent::preSave($data);
        if(!empty($result['error'])) return $result;

        $normalized = $this->normalizeContacts($contacts);
        if (!empty($normalized['error'])) return $normalized;

        $this->contacts_to_save = $normalized['contacts'];

        return $result;
    }

    protected function afterSave(array &$result): void
    {
        parent::afterSave($result);

        if(empty($result['error'])) $this->saveContacts($this->contacts_to_save);

        if (empty($result['error']) && isset($result['new'])) {
            $result['dispatch_url'] = '#/client/edit/id='.$this->id;
        }
    }

    protected function afterDelete(array &$result): void
    {
        parent::afterDelete($result);
    }

    protected function getTabs()
    {
        return array(
            'items' => array(
                'company_data' => array('tab' => 'company_data', 'name' => 'Информация о компании'),
                'contact_data' => array('tab' => 'contact_data', 'name' => 'Контакты'),
            ),
            'options' => array(
                'default_tab' => 'company_data',
            ),
        );
    }

    protected function saveContacts(array $contacts): void
    {
        $model = new pb2bClientContactModel();
        $model->deleteByField('client_id', (int)$this->id);

        $sort = 0;
        foreach ($contacts as $c) {
            $sort++;

            $model->insert(array(
                'client_id' => (int)$this->id,
                'name' => (string)$c['name'],
                'post' => (string)$c['post'],
                'phone' => (string)$c['phone'],
                'email' => (string)$c['email'],
                'sort' => $sort,
            ));
        }
    }

    protected function normalizeContacts(array $contacts): array
    {
        $out = array();

        foreach ($contacts as $row) {
            $c = array(
                'name' => trim((string)ifempty($row['name'], '')),
                'post' => trim((string)ifempty($row['post'], '')),
                'phone' => trim((string)ifempty($row['phone'], '')),
                'email' => trim((string)ifempty($row['email'], '')),
            );

            if ($c['name'] === '' && $c['post'] === '' && $c['phone'] === '' && $c['email'] === '') {
                continue;
            }

            if ($c['phone'] === '' && $c['email'] === '') {
                return array('error' => true, 'message' => 'У контакта должен быть телефон или e-mail');
            }
            
            foreach (array('name','post','phone','email') as $field) {
                $value = $c[$field];

                $vr = pb2bWaproHelper::validateField($field, $value, 'client_contact'); 
                if (!empty($vr['error'])) {
                    return $vr; 
                }
                $c[$field] = $value;
            }

            $out[] = $c;
        }

        return array('error' => false, 'contacts' => $out);
    }

    protected function getContacts(): array
    {
        $model = new pb2bClientContactModel();
        $model->setFetch('all');
        $model->setWhere(array(
            'client_id' => array('simile' => '=', 'value' => $this->id),
        ));
        return $model->queryRun();
    }
}