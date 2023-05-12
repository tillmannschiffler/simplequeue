<?php

namespace simpleQueue\Event;

use simpleQueue\Job\Job;

class JobOutput implements Event
{
    private Job $job;

    private string $joboutput;

    private \DateTimeImmutable $dateTimeImmutable;

    public function __construct(Job $job, string $joboutput, \DateTimeImmutable $dateTimeImmutable)
    {
        $this->job = $job;
        $this->joboutput = $joboutput;
        $this->dateTimeImmutable = $dateTimeImmutable;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function getJoboutput(): string
    {
        return $this->joboutput;
    }
}
