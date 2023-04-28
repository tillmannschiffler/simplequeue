<?php

declare(strict_types=1);

namespace simpleQueue\Job;

class JobType
{
    private string $jobType;

    private function __construct(string $jobType)
    {
        $this->ensureIsValid($jobType);
        $this->jobType = $jobType;
    }

    private function ensureIsValid(string $jobType): void
    {
        if (! self::isValid($jobType)) {
            throw new \InvalidArgumentException(sprintf(
                'Provided value not valid: %s',
                $jobType
            ));
        }
    }

    public function toString(): string
    {
        return $this->jobType;
    }

    public static function isValid(string $jobType): bool
    {
        //TODO: remove or implement validation rules
        if (! is_string($jobType)) {
            return false;
        }

        return true;
    }

    public static function fromString(string $jobType): self
    {
        return new self($jobType);
    }
}
