<?php

class pb2bXlsxSheet
{
    protected $id;
    protected $path;
    protected $settings;
    protected $dictionary_path;

    public function __construct($id, $path, $settings)
    {
        if (!empty($id)) {
            $this->id = intval($id);
            $this->path = $path.'worksheets/sheet'.$this->id.'.xml';
            $this->settings = isset($settings[$this->id]) ? $settings[$this->id] : null;
            $this->dictionary_path = $path.'sharedStrings.xml';
        }
    }

    /**
     * Функция для парсинга конкретной страницы
     */
    public function sheetParser()
    {
        $res = array('error' => false);
        if (isset($this->id) && isset($this->settings)) {
            try {
                $dictionary = simplexml_load_file($this->dictionary_path);
                if (isset($this->path) && file_exists($this->path)) {
                    $sheet = simplexml_load_file($this->path);
                    foreach ($sheet->sheetData->row as $row) {
                        if ($row['r'] >= $this->settings['start_row']) {
                            $row_r = (int)$row['r'];
                            foreach ($row->c as $col) {
                                $col_name = substr((string)$col['r'], 0, 1);
                                if (isset($col->v)) {
                                    if (empty($col['t'])) {
                                        $value = (string)$col->v;
                                    } else {
                                        if (strlen((int)$col->v) == strlen((string)$col->v) && isset($dictionary->si[(int)$col->v])) {
                                            if (isset($dictionary->si[(int)$col->v]->r[1]->t) && $dictionary->si[(int)$col->v]->r[1]->t == 2) {
                                                $value = 'm2';
                                            } else {
                                                $value = (string)$dictionary->si[(int)$col->v]->t;
                                            }
                                        } else {
                                            if (strpos((string)$col->v, 'E')) {
                                                $value = explode('E', (string)$col->v);
                                                $value = preg_replace('/\.0$/', '', $value[0]*pow(10, $value[1]));
                                            } else {
                                                $value = preg_replace('/\.0$/', '', (string)$col->v);
//                                                if ($col['r'] == 'B186') {
//                                                    var_export($value);
//                                                }
                                            }
                                        }
                                    }
                                    $res['data'][$row_r][$col_name] = $value;
                                }
                            }
                        }
                    }
                }
            } catch (waException $wa) {
                $res = array('error' => true, 'message' => $wa->getMessage());
            }
        } else {
            $res = array('error' => true, 'message' => 'Неверные настройки прайс листа');
        }
        return $res;
    }

    public function sheetStructure()
    {
        $res = array();
        if (isset($this->settings)) {
            $res['settings'] = $this->settings;
        }
        if (isset($this->id)) {
            try {
                if (isset($this->path) && file_exists($this->path)) {
                    $sheet = simplexml_load_file($this->path);
                    foreach ($sheet->sheetData->row as $row) {
                        foreach ($row->c as $col) {
                            $col_name = substr((string)$col['r'], 0, 1);
                            $res['cols'][$col_name] = $col_name;
                        }
                    }
                }
            } catch (waException $wa) {
                $res = array('error' => true, 'message' => $wa->getMessage());
            }
        } else {
            $res = array('error' => true, 'message' => 'Ошибка получения идентификатора');
        }
        return $res;
    }

    protected function dateConvert($date)
    {
        $date = new DateTime(date('Y-m-d', $date*24*3600));
        $date->modify('-1 day');
        $date->modify('-70 year');
        return $date->format('Y-m-d');
    }
}