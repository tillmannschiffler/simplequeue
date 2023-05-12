<?php

declare(strict_types=1);

namespace simpleQueue\Event;

use simpleQueue\Job\Job;

class FailedJob implements Event
{
    private Job $job;

    private \Throwable $exception;

    private \DateTimeImmutable $dateTimeImmutable;

    public function __construct(Job $job, \Throwable $exception, \DateTimeImmutable $dateTimeImmutable)
    {
        $this->job = $job;
        $this->exception = $exception;
        $this->dateTimeImmutable = $dateTimeImmutable;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function getException(): \Throwable
    {
        return $this->exception;
    }

    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }
}
