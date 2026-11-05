<?php

enum pb2bTenderPageTab: string
{
    case TENDER_INFO = 'tenderInfo';
    case REQUESTED_DOCUMENTS = 'requestedDocuments';
    case OVERVIEW = 'overview';
    case QUESTIONS = 'questions';
    case PARTICIPANTS = 'participants';
    case EVALUATION = 'evaluation';
    case RESULTS = 'results';
    case NOTICE = 'notice';
    case LOTS = 'lots';
    case DOCUMENTS = 'documents';
    case NON_PRICE = 'nonPrice';
    case PROCUREMENT_DOCS = 'procurementDocs';
    case POSITIONS = 'positions';

    public function label(): string
    {
        return match ($this) {
            self::TENDER_INFO => 'Информация о тендере',
            self::REQUESTED_DOCUMENTS => 'Запрашиваемые документы',
            self::OVERVIEW => 'Обзор',
            self::QUESTIONS => 'Вопросы',
            self::PARTICIPANTS => 'Участники',
            self::EVALUATION => 'Оценка',
            self::RESULTS => 'Результаты',
            self::NOTICE => 'Извещение',
            self::LOTS => 'Лоты',
            self::DOCUMENTS => 'Документы',
            self::NON_PRICE => 'Неценовые критерии',
            self::PROCUREMENT_DOCS => 'Закупочная документация',
            self::POSITIONS => 'Позиции',
        };
    }

    public function toArray(
        ?bool $active = null,
        ?bool $disabled = null,
        ?string $title = null,
        ?int $count = null
    ): array
    {
        $tab = array(
            'id' => $this->value,
            'label' => $this->label(),
        );
        if ($active !== null) {
            $tab['active'] = $active;
        }
        if ($disabled !== null) {
            $tab['disabled'] = $disabled;
        }
        if ($title !== null) {
            $tab['title'] = $title;
        }
        if ($count !== null) {
            $tab['count'] = $count;
        }

        return $tab;
    }

    public static function buyerTender(int $participants_count, bool $results_enabled): array
    {
        return array(
            self::OVERVIEW->toArray(true),
            self::QUESTIONS->toArray(true),
            self::PARTICIPANTS->toArray(count: $participants_count),
            self::EVALUATION->toArray(null, false),
            self::RESULTS->toArray(
                disabled: !$results_enabled,
                title: $results_enabled ? '' : 'Доступно после начала рассмотрения заявок'
            ),
        );
    }

    public static function buyerApplication(): array
    {
        return array(
            self::NOTICE->toArray(),
            self::LOTS->toArray(true),
            self::DOCUMENTS->toArray(),
            self::NON_PRICE->toArray(),
        );
    }

    public static function supplierApplication(): array
    {
        return array(
            self::TENDER_INFO->supplierApplicationTab(true),
            self::POSITIONS->supplierApplicationTab(),
            self::REQUESTED_DOCUMENTS->supplierApplicationTab(),
            self::NON_PRICE->supplierApplicationTab(),
        );
    }

    private function supplierApplicationTab(bool $active = false): array
    {
        $template = match ($this) {
            self::TENDER_INFO => 'html/cabinet/supplier/partials/tender-application-notice.html',
            self::POSITIONS => 'html/cabinet/supplier/partials/tender-application-positions.html',
            self::REQUESTED_DOCUMENTS => 'html/cabinet/supplier/partials/tender-application-documents.html',
            self::NON_PRICE => 'html/cabinet/supplier/partials/tender-application-nonprice.html',
            default => null,
        };

        return array_merge(
            $this->toArray(active: $active, disabled: $template === null),
            array('template' => $template)
        );
    }

    public static function supplierTender(int $items_count, int $documents_count): array
    {
        return array(
            self::NOTICE->toArray(true),
            self::PROCUREMENT_DOCS->toArray(),
            self::POSITIONS->toArray(count: $items_count),
            self::DOCUMENTS->toArray(count: $documents_count),
            self::QUESTIONS->toArray(),
            self::NON_PRICE->toArray(),
        );
    }
}
