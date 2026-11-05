<?php

/**
 * Ошибка валидации с машиночитаемым списком issues (для publish и подобных).
 */
class pb2bValidationException extends waException
{
    /** @var list<array{step?:string,field?:string,message:string}> */
    private array $issues;

    /**
     * @param list<array{step?:string,field?:string,message:string}> $issues
     */
    public function __construct(string $message, array $issues = array(), int $code = 400)
    {
        parent::__construct($message, $code);
        $this->issues = array_values($issues);
    }

    /**
     * @return list<array{step?:string,field?:string,message:string}>
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

    public function getPayload(): array
    {
        return array(
            'error' => true,
            'issues' => $this->issues,
        );
    }
}
