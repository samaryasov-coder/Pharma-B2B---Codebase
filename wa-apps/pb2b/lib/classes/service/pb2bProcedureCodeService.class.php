<?php

/**
 * Реестровый код процедуры: «код типа — год — порядковый номер».
 * Пример: 01-26-000001.
 */
class pb2bProcedureCodeService
{
    private const STORAGE = [
        'price_request' => ['table' => 'pb2b_tender', 'column' => 'number'],
        'proposal_request' => ['table' => 'pb2b_tender', 'column' => 'number'],
        'prequalification' => ['table' => 'pb2b_tender', 'column' => 'number'],
        'supplier_approval' => ['table' => 'pb2b_docflow_request', 'column' => 'procedure_code'],
    ];

    /**
     * @throws waException
     */
    public function issue(string $type): string
    {
        $prefix = $this->requirePrefix($type);
        $yy = substr((string) date('Y'), -2);
        $seq = $this->maxSequence($type, $prefix, $yy);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $seq++;
            if ($seq > 999999) {
                throw new waException('Исчерпан диапазон кодов процедуры', pb2bHttpStatus::CONFLICT);
            }
            $code = sprintf('%s-%s-%06d', $prefix, $yy, $seq);
            if (!$this->codeExists($type, $code)) {
                return $code;
            }
        }

        throw new waException('Не удалось выдать код процедуры', pb2bHttpStatus::CONFLICT);
    }

    /**
     * @throws waException
     */
    public function issueForDocflowProcess(int $processType): string
    {
        $processTypes = (array) pb2bWaproHelper::getConfigOption('docflow_process_types');
        $row = $processTypes[$processType] ?? null;
        $type = is_array($row) ? (string) ($row['procedure_code_type'] ?? '') : '';
        if ($type === '') {
            throw new waException('Для процесса не задан тип кода процедуры', pb2bHttpStatus::BAD_REQUEST);
        }

        return $this->issue($type);
    }

    /**
     * @throws waException
     */
    private function requirePrefix(string $type): string
    {
        $prefix = $this->configuredPrefix($type);
        if ($prefix === null || preg_match('/^\d{2}$/', $prefix) !== 1 || !isset(self::STORAGE[$type])) {
            throw new waException('Неизвестный тип кода процедуры', pb2bHttpStatus::BAD_REQUEST);
        }

        return $prefix;
    }

    private function configuredPrefix(string $type): ?string
    {
        $codes = (array) pb2bWaproHelper::getConfigOption('procedure_codes');
        $row = $codes[$type] ?? null;
        if (!is_array($row) || !isset($row['code'])) {
            return null;
        }
        $prefix = trim((string) $row['code']);

        return $prefix === '' ? null : $prefix;
    }

    /**
     * @return array{0:string,1:string}
     */
    private function storage(string $type): array
    {
        $row = self::STORAGE[$type];

        return [$row['table'], $row['column']];
    }

    private function maxSequence(string $type, string $prefix, string $yy): int
    {
        [$table, $column] = $this->storage($type);
        $row = (new waModel())->query(
            "SELECT MAX(CAST(SUBSTRING_INDEX(`{$column}`, '-', -1) AS UNSIGNED)) AS seq
             FROM `{$table}`
             WHERE `{$column}` LIKE ?",
            $prefix.'-'.$yy.'-%'
        )->fetchAssoc();

        return (int) ($row['seq'] ?? 0);
    }

    private function codeExists(string $type, string $code): bool
    {
        [$table, $column] = $this->storage($type);
        $row = (new waModel())->query(
            "SELECT 1 AS hit FROM `{$table}` WHERE `{$column}` = ? LIMIT 1",
            $code
        )->fetchAssoc();

        return !empty($row);
    }
}
