<?php
abstract class pb2bBaseJsonResource implements JsonSerializable
{
    protected static bool $wrap = false;

    protected object|array|null $resource = null;
    protected array $casts = [];
    protected array $additional = [];

    public function __construct(object|array $resource)
    {
        $this->resource = $resource;
    }

    abstract public function toArray(): array;

    public static function make(object|array|null $resource): ?static
    {
        if (empty($resource)) {
            return null;
        }

        return new static($resource);
    }


    public static function collection(iterable $resources): array
    {
        return array_map(
            fn($r) => (new static($r))->resolve(),
            $resources
        );
    }

    public function additional(array $data): static
    {
        $this->additional = $data;
        return $this;
    }

    public static function withoutWrapping(): void
    {
        static::$wrap = false;
    }

    protected function get(string $key): mixed
    {
        if (is_array($this->resource)) {
            return $this->resource[$key] ?? null;
        }

        return $this->resource?->$key ?? null;
    }

    protected function castValue(string $key, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        foreach ($this->casts as $type => $fields) {
            if (in_array($key, $fields, true)) {
                return match ($type) {
                    'int' => (int) $value,
                    'float' => (float) $value,
                    'string' => (string) $value,
                    'bool' => (bool) $value,
                    default => $value,
                };
            }
        }

        return $value;
    }

    public function resolve(): array
    {
        $data = $this->toArray();

        foreach ($data as $key => $value) {
            $data[$key] = $this->castValue($key, $value);
        }

        if (static::$wrap) {
            $data = ['data' => $data];
        }

        return array_merge($data, $this->additional);
    }

    public function jsonSerialize(): array
    {
        return $this->resolve();
    }

    public function __get($key)
    {
        return $this->get($key);
    }
}