<?php

declare(strict_types=1);


namespace simpleQueue\Job;


class JobPayload
{
    private string $jobPayload;

    private function __construct(string $jobPayload)
    {
        $this->ensureIsValid($jobPayload);
        $this->jobPayload = $jobPayload;
    }

    private function ensureIsValid(string $jobPayload): void
    {
        if (!self::isValid($jobPayload))
            throw new \InvalidArgumentException(sprintf(
                'Provided value not valid: %s',
                $jobPayload
            ));
    }

    public function toString(): string
    {
        return $this->jobPayload;
    }

    public static function isValid(string $jobPayload): bool
    {
        //TODO: remove or implement validation rules
        if (!is_string($jobPayload))
            return false;

        return true;
    }

    public static function fromString(string $jobPayload): self
    {
        return new self($jobPayload);
    }
}