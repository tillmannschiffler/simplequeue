<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

class Filename
{
    private string $filename;

    private function __construct(string $filename)
    {
        $this->ensureIsValid($filename);
        $this->filename = $filename;
    }

    private function ensureIsValid(string $filename): void
    {
        if (!self::isValid($filename))
            throw new \InvalidArgumentException(sprintf(
                'Provided value not valid: %s',
                $filename
            ));
    }

    public function toString(): string
    {
        return $this->filename;
    }

    public static function isValid(string $filename): bool
    {
        //TODO: remove or implement validation rules
        if (!is_string($filename))
            return false;

        return true;
    }

    public static function fromString(string $filename): self
    {
        return new self($filename);
    }
    
}