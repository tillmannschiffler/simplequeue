<?php

declare(strict_types=1);

namespace simpleQueue\Job;

class JobId
{
    private string $jobId;

    private function __construct(string $jobId)
    {
        $this->ensureIsValid($jobId);
        $this->jobId = $jobId;
    }

    private function ensureIsValid(string $jobId): void
    {
        if (! self::isValid($jobId)) {
            throw new \InvalidArgumentException(sprintf(
                'Provided value not valid: %s',
                $jobId
            ));
        }
    }

    public function toString(): string
    {
        return $this->jobId;
    }

    public static function isValid(string $jobId): bool
    {
        //TODO: remove or implement validation rules
        return true;
    }

    public static function fromString(string $jobId): self
    {
        return new self($jobId);
    }
}
