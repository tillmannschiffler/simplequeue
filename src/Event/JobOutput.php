<?php

namespace simpleQueue\Event;

use simpleQueue\Job\Job;

class JobOutput implements Event
{
    private Job $job;

    private string $jobOutput;

    private \DateTimeImmutable $dateTimeImmutable;

    public function __construct(Job $job, string $jobOutput, \DateTimeImmutable $dateTimeImmutable)
    {
        $this->job = $job;
        $this->jobOutput = $jobOutput;
        $this->dateTimeImmutable = $dateTimeImmutable;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function getJobOutput(): string
    {
        return $this->jobOutput;
    }

    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }
}
