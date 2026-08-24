<?php

class pb2bDadataService
{
    protected pb2bApiService $api;
    protected pb2bSettings $settings;
    protected string $token = '';

    public function __construct()
    {
        $this->api = new pb2bApiService('https://suggestions.dadata.ru/suggestions/api/4_1/rs');
        $this->settings = new pb2bSettings();
        $this->token = $this->settings->getValue('api', 'dadata_token', '');
    }

    protected function requireToken(): array
    {
        if ($this->token === '') return array('error' => 1, 'message' => 'Не настроен токен DaData');
        return array('error' => 0);
    }

    protected function request(string $path, array $payload): array
    {
        $tok = $this->requireToken();
        if (!empty($tok['error'])) return $tok;

        $r = $this->api->post(
            $path,
            $payload,
            array('headers' => array('Authorization' => 'Token '.$this->token))
        );

        if (!is_array($r)) return array('error' => 1, 'message' => 'Некорректный ответ API');
        if (!empty($r['error'])) return $r;

        $json = ifempty($r['data'], array());
        return array('error' => 0, 'data' => $json);
    }

    protected function firstSuggestionData(array $json): array
    {
        $suggestions = ifempty($json['suggestions'], array());
        if (!$suggestions) return array();

        $s = $suggestions[0];
        return array(
            'suggestion' => $s,
            'data' => (array)ifempty($s['data'], array()),
        );
    }

    public function findPartyByInn(string $inn): array
    {
        $inn = preg_replace('~\D+~', '', $inn);
        if ($inn === '') return array('error' => 1, 'message' => 'Введите ИНН');

        $r = $this->request('findById/party', array('query' => $inn));
        if (!empty($r['error'])) return $r;

        $first = $this->firstSuggestionData((array)$r['data']);
        if (!$first) return array('error' => 0, 'data' => array());

        $s = (array)ifempty($first['suggestion'], array());
        $d = (array)ifempty($first['data'], array());

        $result = array(
            'name' => (string)ifempty($d['name']['full_with_opf'], ifempty($s['value'], '')),
            'inn' => (string)ifempty($d['inn'], ''),
            'kpp' => (string)ifempty($d['kpp'], ''),
            'ogrn' => (string)ifempty($d['ogrn'], ''),
            'jur_address' => (string)ifempty($d['address']['unrestricted_value'], ifempty($d['address']['value'], '')),
            'okved' => (string)ifempty($d['okved'], ''),
            'manager_name' => (string)ifempty($d['management']['name'], ''),
            'manager_post' => (string)ifempty($d['management']['post'], ''),
            'branch_count' => (int)ifempty($d['branch_count'], 0),
            'dadata_state' => (string)ifempty($d['state']['status'], ''),
        );

        $phones = ifempty($d['phones'], array());
        if (!empty($phones[0]['value'])) {
            $result['phone'] = (string)$phones[0]['value'];
        }

        $emails = ifempty($d['emails'], array());
        if (!empty($emails[0]['value'])) {
            $result['registry_email'] = (string)$emails[0]['value'];
        }

        return array('error' => 0, 'data' => $result);
    }

    public function findBankByBik(string $bic): array
    {
        $bic = preg_replace('~\D+~', '', $bic);
        if ($bic === '') return array('error' => 1, 'message' => 'Введите БИК');

        $r = $this->request('findById/bank', array('query' => $bic));
        if (!empty($r['error'])) return $r;

        $first = $this->firstSuggestionData((array)$r['data']);
        if (!$first) return array('error' => 0, 'data' => array());

        $d = (array)ifempty($first['data'], array());
        $name = (array)ifempty($d['name'], array());

        $result = array(
            'bic' => (string)ifempty($d['bic'], ''),
            'swift' => (string)ifempty($d['swift'], ''),
            'correspondent_account' => (string)ifempty($d['correspondent_account'], ''),
            'bank_name' => (string)ifempty($name['payment'], ifempty($name['short'], '')),
            'bank_address' => (string)ifempty($d['address']['unrestricted_value'], ifempty($d['address']['value'], '')),
            'bank_inn' => (string)ifempty($d['inn'], ''),
            'bank_kpp' => (string)ifempty($d['kpp'], ''),
        );

        return array('error' => 0, 'data' => $result);
    }
}
