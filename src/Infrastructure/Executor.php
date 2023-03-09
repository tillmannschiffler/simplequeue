<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\Job;

class Executor
{
    private ProcessorLocator $processorLocator;
    private JobMover $jobMover;

    public function __construct(ProcessorLocator $processorLocator, JobMover $jobMover)
    {
        $this->processorLocator = $processorLocator;
        $this->jobMover = $jobMover;
    }

    public function process(Job $job): void
    {
        try {
            $processor = $this->processorLocator->getProcessorFor($job->getJobType());
            $processor->execute($job);
            $this->jobMover->moveToFinished($job);
        } catch (\Throwable $exception) {
            //TODO: log exception
            $this->jobMover->moveToFailed($job);
        }
    }
}