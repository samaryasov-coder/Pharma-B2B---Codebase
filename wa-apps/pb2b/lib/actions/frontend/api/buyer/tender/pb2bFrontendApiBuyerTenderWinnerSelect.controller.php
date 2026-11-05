<?php

/**
 * POST api/buyer/tender/<id>/winner/select/
 * Тело: mode=all + application_id | mode=positional + items[].
 */
class pb2bFrontendApiBuyerTenderWinnerSelectController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            $tender_id = waRequest::post('id', 0, waRequest::TYPE_INT);
        }
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $mode = waRequest::post('mode', '', waRequest::TYPE_STRING_TRIM);
        $company_id = $this->tenderCompanyId();
        $apps = $this->applicationService();

        if ($mode === 'all') {
            $application_id = waRequest::post('application_id', 0, waRequest::TYPE_INT);
            if ($application_id <= 0) {
                throw new waException('Не указана заявка', pb2bHttpStatus::BAD_REQUEST);
            }
            $state = $apps->selectWinnerAll($tender_id, $company_id, $application_id);
        } elseif ($mode === 'positional') {
            $raw_items = waRequest::post('items', array(), waRequest::TYPE_ARRAY);
            $state = $apps->selectWinnerPositional(
                $tender_id,
                $company_id,
                $this->normalizePositionalItems(is_array($raw_items) ? $raw_items : array())
            );
        } else {
            throw new waException(
                'Укажите mode: all или positional',
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        $this->response = $this->ok($state, 'Победитель выбран');
    }

    /**
     * @param array<mixed> $raw
     * @return list<array{tender_item_id: int, application_id: int}>
     */
    private function normalizePositionalItems(array $raw): array
    {
        $items = array();
        foreach ($raw as $row) {
            if (!is_array($row)) {
                continue;
            }
            $items[] = array(
                'tender_item_id' => (int) ($row['tender_item_id'] ?? 0),
                'application_id' => (int) ($row['application_id'] ?? 0),
            );
        }

        return $items;
    }
}
