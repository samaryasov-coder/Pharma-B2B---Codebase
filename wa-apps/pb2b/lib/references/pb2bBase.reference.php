<?php
abstract class pb2bBaseReference
{
    abstract protected static function configField(): string;

    public static function getConfig(): array
    {
        return pb2bWaproHelper::getConfigOption(static::configField());
    }
}