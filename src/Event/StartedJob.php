<?php

declare(strict_types=1);

namespace simpleQueue\Event;

use simpleQueue\Job\Job;

class StartedJob implements Event
{
    private Job $job;

    private \DateTimeImmutable $dateTimeImmutable;

    public function __construct(Job $job, \DateTimeImmutable $dateTimeImmutable)
    {
        $this->job = $job;
        $this->dateTimeImmutable = $dateTimeImmutable;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }
}
