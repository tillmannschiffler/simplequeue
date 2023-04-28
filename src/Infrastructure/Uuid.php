<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

class Uuid
{
    private string $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function toString(): string
    {
        return $this->uuid;
    }

    public static function fromString(string $uuid): self
    {
        return new self($uuid);
    }

    public static function create(): self
    {
        return new self(trim(file_get_contents('/proc/sys/kernel/random/uuid')));
    }
}
