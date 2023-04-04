<?php

declare(strict_types=1);

namespace simpleQueue\Event;

use simpleQueue\Job\Job;

class FinishedJob implements Event
{
    private Job $job;
    private \DateTimeImmutable $dateTimeImmutable;


    public function __construct(Job $job, \DateTimeImmutable $dateTimeImmutable)
    {
        $this->job = $job;
        $this->dateTimeImmutable = $dateTimeImmutable;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }
    
    
}