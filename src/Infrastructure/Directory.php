<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

class Directory
{
    private string $directory;

    private function __construct(string $directory)
    {
        $this->ensureIsValid($directory);
        $this->directory = $directory;
    }

    private function ensureIsValid(string $directory): void
    {
        if (! self::isValid($directory)) {
            throw new \InvalidArgumentException(sprintf(
                'Provided value not valid: %s',
                $directory
            ));
        }
    }

    public function toString(): string
    {
        return $this->directory;
    }

    public static function isValid(string $directory): bool
    {
        //TODO: remove or implement validation rules
        if (! is_string($directory)) {
            return false;
        }
        
        if (!is_dir($directory)) {
            return false;
        }

        return true;
    }

    public static function fromString(string $directory): self
    {
        return new self($directory);
    }
}
