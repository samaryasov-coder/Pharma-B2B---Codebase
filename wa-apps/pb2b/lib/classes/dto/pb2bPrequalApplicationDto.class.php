<?php

readonly class pb2bPrequalApplicationDto extends pb2bBaseDto
{
    public array $documents;
    public int $audit_consent;
    public int $samples_consent;
    public int $info_consent;
}
