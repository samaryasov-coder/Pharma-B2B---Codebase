<?php

/**
 * Общий bootstrap Smarty для create/edit формы тендера (пошаговый мастер).
 */
trait pb2bTenderFormViewTrait
{
    /**
     * @param list<array{key:string,label:string}> $steps
     * @param array|null $detail payload getDetailFromBuyer (только edit)
     */
    protected function assignTenderFormView(
        string $type_code,
        int $type_id,
        int $tender_id,
        bool $edit_mode,
        array $steps,
        string $title,
        ?array $detail = null
    ): void {
        $status_code = $detail !== null
            ? pb2bTenderStatus::tryFromId((int) $detail['tender']->data['status'])?->value ?? ''
            : '';
        $editing_published = $edit_mode
            && $status_code !== ''
            && $status_code !== pb2bTenderStatus::DRAFT->value;
        if ($editing_published) {
            $steps = array_values(array_filter($steps, static function (array $step): bool {
                return ($step['key'] ?? '') !== 'invitation';
            }));
        }
        $step_keys = array();
        foreach ($steps as $step) {
            if (!empty($step['key'])) {
                $step_keys[] = (string) $step['key'];
            }
        }

        if ($detail !== null) {
            $detail['tender'] = pb2bTenderResource::make($detail['tender'])->resolve();
            $detail['items'] = pb2bTenderItemResource::collection($detail['items']);
            $detail['documents'] = pb2bTenderDocumentResource::collection($detail['documents']);
            $detail['classifiers'] = pb2bTenderClassifierResource::collection($detail['classifiers']);
            $detail['invitations'] = pb2bTenderInvitationResource::collection($detail['invitations']);
        }

        $this->view->assign(array(
            'form_mode' => $edit_mode ? 'edit' : 'create',
            'form_type_code' => $type_code,
            'form_type_id' => $type_id,
            'form_tender_id' => $tender_id,
            'form_edit_mode' => $edit_mode ? 1 : 0,
            'form_editing_published' => $editing_published,
            'form_steps' => $steps,
            'form_step_keys_json' => json_encode($step_keys, JSON_UNESCAPED_UNICODE),
            'form_title' => $title,
            'form_detail_json' => $detail !== null
                ? json_encode($detail, JSON_UNESCAPED_UNICODE)
                : '',
            'form_status_code' => $status_code,
        ));
    }

    protected function renderTenderFormTemplate(): void
    {
        $this->setThemeTemplate('html/cabinet/buyer/tenders/tender-form.html');
    }

    protected function redirectToBuyerTendersList(): void
    {
        $this->redirect('/cabinet/buyer/tenders/');
    }
}
