<?php

abstract readonly class pb2bBaseDto
{
    protected function castValue(mixed $value, ?ReflectionType $type): mixed
    {
        if (!$type)
            return $value;

        if ($value === null)
            return null;

        $typeName = $type instanceof ReflectionNamedType ? $type->getName() : null;

        return match ($typeName) {
            'int' => (int)$value,
            'float' => (float)$value,
            'bool' => (bool)$value,
            'string' => (string)$value,
            default => $this->castObject($value, $typeName),
        };
    }

    protected function castObject(mixed $value, string $class): mixed {
        if ($value instanceof $class)
            return $value;
        return $value;
    }

    protected function normalizeValue(mixed $value): mixed
    {
        if (is_object($value)) {
            if (method_exists($value, 'toArray'))
                return $value->toArray();
            return null;
        }

        if (is_array($value))
            return array_map(fn($item) => $this->normalizeValue($item), $value);

        return $value;
    }




    public function __construct(array $data = [])
    {
        $reflection = new ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            if (!array_key_exists($name, $data))
                continue;
            $value = $this->castValue($data[$name], $property->getType());
            $property->setValue($this, $value);
        }
    }

    public function merge(array $data): array
    {
        return array_merge($this->toArray(), $data);
    }

    public function toArray(): array
    {
        $result = [];
        $reflection = new ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {
            if (!$property->isInitialized($this))
                continue;

            $value = $property->getValue($this);
            $normalized = $this->normalizeValue($value);
            if ($normalized === null && is_object($value))
                continue;

            $result[$property->getName()] = $normalized;
        }
        return $result;
    }
}
