<?php

class pb2bEsklp extends pb2bWaproObject
{
    /**
     * @var pb2bEsklpGroupModel
     */
    protected pb2bEsklpGroupModel $groupModel;
    /**
     * @var pb2bEsklpGroupsModel
     */
    protected pb2bEsklpGroupsModel $groupsModel;

    public function __construct(?int $id = null)
    {
        $this->groupModel = new pb2bEsklpGroupModel();
        $this->groupsModel = new pb2bEsklpGroupsModel();
        parent::__construct($id);
    }

    /**
     * @return void
     * @throws waException
     */
    public function parse(): void
    {
        $sheet = new pb2bXlsxSheet(2, wa()->getAppPath('esklp/xl/'), array(2 => array('start_row' => 5)));
        $current = '';
        $sheet = $sheet->sheetParser();
        $groups = array();
        $esklp = array();
        if (!empty($sheet)) {
            foreach ($sheet['data'] as $i) {
                if ($current != $i['A']) {
                    $current = $i['A'];
                    $groups[$i['A']] = $this->groupModel->insert(array('name' => $i['A'], 'unit' => $i['B']));
                }
                if (!empty($groups[$i['A']]) && !empty($i['C'])) {
                    if (empty($esklp[$i['C']])) {
                        $id = $this->model->insert(array(
                            'name' => $i['D'],
                            'code' => $i['C'],
                            'form' => $i['E'],
                            'dose' => $i['F'],
                            'unit' => $i['G'],
                            'coef' => $i['H'],
                        ));
                        $esklp[$i['C']] = $id;
                    }
                    $this->groupsModel->insert(array(
                        'esklp_id' => $esklp[$i['C']],
                        'group_id' => $groups[$i['A']],
                    ), 2);
                }
            }
        }
    }

    /**
     * @return bool|int
     * @throws waException
     */
    public function prepareExel(): bool|int
    {
        $zip = new ZipArchive();
        $result = $zip->open(wa()->getAppPath('/esklp.xlsx'));
        if ($result === TRUE) {
            $result = $zip->extractTo(wa()->getAppPath('/esklp/'));
            $zip->close();
        }
        return $result;
    }

    protected function getTabs(): array
    {
        return array(
            'items' => array(
                'common' => array('tab' => 'common', 'name' => 'Общее'),
                'groups' => array('tab' => 'groups', 'name' => 'Группы'),
            ),
            'options' => array(
                'default_tab' => 'common',
            ),
        );
    }

    /**
     * @return array
     * @throws waDbException
     * @throws waException
     */
    protected function getGroups(): array
    {
        $groups = new pb2bEsklpGroupCollection('esklp.esklp_id='.$this->id);
        return $groups->getCollection(array('order' => array('name' => array('dir' => 'ASC', 'table' => $this->groupModel->getTableName()))));
    }
}