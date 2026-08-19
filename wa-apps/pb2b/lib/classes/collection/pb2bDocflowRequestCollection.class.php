<?php

class pb2bDocflowRequestCollection extends pb2bWaproCollection
{
    protected pb2bDocflowRequestItemsModel $docflowRequestItemsModel;
    protected pb2bFileLinksModel $fileLinksModel;
    protected pb2bFileModel $fileModel;
    protected pb2bCompanyModel $companyModel;

    public function __construct($hash = null)
    {
        $this->docflowRequestItemsModel = new pb2bDocflowRequestItemsModel();
        $this->fileLinksModel = new pb2bFileLinksModel();
        $this->fileModel = new pb2bFileModel();
        $this->companyModel = new pb2bCompanyModel();
        parent::__construct($hash);
    }

    protected function addWhereItems($where): void
    {
        $this->model->addWhere($where);
    }

    protected function addWhereItemsWithItems($where): void
    {
        $this->model->setJoin(array(
            array(
                'right' => $this->docflowRequestItemsModel,
                'on'    => array('id' => 'request_id'),
                'type'  => 'LEFT',
                'as'    => 'DRI',
            ),
        ));

        $this->model->addWhere($where);
    }

//    protected function addWhereItemsWithFiles($where): void
//    {
//        $this->model->setJoin(array(
//            array(
//                'right' => $this->docflowRequestItemsModel,
//                'on'    => array('id' => 'request_id'),
//                'type'  => 'LEFT',
//                'as'    => 'DRI',
//            ),
//            array(
//                'right' => $this->fileLinksModel,
//                'on' => array(
//                    'reviewer_file_link_id' => array(
//                        'table' => 'DRI',
//                        'simile' => '=',
//                        'value' => array('table' => 'RFL', 'field' => 'id'),
//                    ),
//                ),
//                'type' => 'LEFT',
//                'as' => 'RFL',
//            ),
//            array(
//                'right' => $this->fileLinksModel,
//                'on' => array(
//                    'provider_file_link_id' => array(
//                        'table' => 'DRI',
//                        'simile' => '=',
//                        'value' => array('table' => 'PFL', 'field' => 'id'),
//                    ),
//                ),
//                'type' => 'LEFT',
//                'as' => 'PFL',
//            ),
//        ));
//        $this->model->addWhere($where);
//    }

    public function processAutoRefresh(): array
    {
        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $approved_status = (int) ($request_statuses['approved']['id'] ?? 3);
        $expired_status = (int) ($request_statuses['expired']['id'] ?? 0);
        $waiting_provider_status = (int) ($request_statuses['waiting_provider']['id'] ?? 1);
        $waiting_review_status = (int) ($request_statuses['waiting_review']['id'] ?? 2);

        if ($approved_status <= 0 || $expired_status <= 0) {
            return array('error' => true, 'message' => 'Не настроены статусы docflow_request_statuses');
        }

        $request_model = new pb2bDocflowRequestModel();
        $template_model = new pb2bDocflowTemplateModel();
        $approved_requests = $request_model->getByField('status', $approved_status, true);
        if (!is_array($approved_requests)) $approved_requests = array();
        $now = date('Y-m-d H:i:s');
        $now_ts = strtotime($now);

        $checked_requests = 0;
        $expired_requests = 0;
        $auto_requests_created = 0;
        $auto_requests_skipped_duplicates = 0;

        foreach ($approved_requests as $request_row) {
            $checked_requests++;

            $request_id = (int) ($request_row['id'] ?? 0);
            if ($request_id <= 0) continue;

            $expires_datetime = $request_row['expires_datetime'] ?? null;
            if (!is_string($expires_datetime) || trim($expires_datetime) === '') continue;
            $expires_ts = strtotime($expires_datetime);
            if ($expires_ts === false || $expires_ts > $now_ts) continue;

            $request_update = array(
                'status' => $expired_status,
            );
            if (isset($request_model->fields['expired_datetime'])) {
                $request_update['expired_datetime'] = $now;
            }
            if (isset($request_model->fields['update_datetime'])) {
                $request_update['update_datetime'] = $now;
            }
            $request_model->updateById($request_id, $request_update);
            $expired_requests++;

            $reviewer_id = (int) ($request_row['reviewer_id'] ?? 0);
            $history_author = $reviewer_id > 0 ? $reviewer_id : null;
            $request_object = new pb2bDocflowRequest($request_id);
            if (!empty($request_object->id)) {
                $request_object->addHistory(
                    'request_expired',
                    $approved_status,
                    $expired_status,
                    false,
                    $history_author
                );
            }

            $template_id = (int) ($request_row['template_id'] ?? 0);
            $provider_id = (int) ($request_row['provider_id'] ?? 0);
            if ($template_id <= 0 || $provider_id <= 0 || $reviewer_id <= 0) continue;

            $template_row = $template_model->getById($template_id);
            $auto_request_enabled = isset($template_row['auto_request_enabled']) ? (int) $template_row['auto_request_enabled'] : 0;
            $refresh_period_days = isset($template_row['refresh_period_days']) ? (int) $template_row['refresh_period_days'] : 0;
            if ($auto_request_enabled !== 1 || $refresh_period_days <= 0) continue;

            $pair_rows = $request_model->getByField(array(
                'template_id' => $template_id,
                'reviewer_id' => $reviewer_id,
                'provider_id' => $provider_id,
            ), true);
            $has_active_pair = 0;
            if (is_array($pair_rows)) {
                foreach ($pair_rows as $pair_row) {
                    $pair_status = (int) ($pair_row['status'] ?? 0);
                    if ($pair_status === $waiting_provider_status || $pair_status === $waiting_review_status) {
                        $has_active_pair = 1;
                        break;
                    }
                }
            }
            if ($has_active_pair) {
                $auto_requests_skipped_duplicates++;
                continue;
            }

            $reviewer_company = new pb2bCompany($reviewer_id);
            if (empty($reviewer_company->id)) continue;

            $create_result = $reviewer_company->docflowRequestCreateFromReviewer(array(
                'template_id' => $template_id,
                'provider_id' => $provider_id,
            ));
            if (!empty($create_result['error'])) continue;

            $new_request_id = (int) ($create_result['request_id'] ?? 0);
            if ($new_request_id > 0 && isset($request_model->fields['source_request_id'])) {
                $request_model->updateById($new_request_id, array(
                    'source_request_id' => $request_id,
                ));
            }

            $auto_requests_created++;
        }

        return array(
            'error' => false,
            'message' => 'Фоновая обработка сроков завершена',
            'checked_requests' => $checked_requests,
            'expired_requests' => $expired_requests,
            'auto_requests_created' => $auto_requests_created,
            'auto_requests_skipped_duplicates' => $auto_requests_skipped_duplicates,
        );
    }
}
