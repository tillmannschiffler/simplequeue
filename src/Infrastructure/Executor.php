<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Event\LogEmmitter;
use simpleQueue\Job\Job;
use simpleQueue\Job\ProcessorLocator;

class Executor
{
    private ProcessorLocator $processorLocator;

    private JobMover $jobMover;

    private LogEmmitter $logEmmitter;

    public function __construct(ProcessorLocator $processorLocator, JobMover $jobMover, LogEmmitter $logEmmitter)
    {
        $this->processorLocator = $processorLocator;
        $this->jobMover = $jobMover;
        $this->logEmmitter = $logEmmitter;
    }

    public function process(Job $job): void
    {
        try {
            $this->logEmmitter->emmitStartedExecutor($job);
            $processor = $this->processorLocator->getProcessorFor($job->getJobType());
            $processor->execute($job);
            $this->jobMover->moveToFinished($job);
        } catch (\Throwable $exception) {
            //TODO: log exception
            $this->jobMover->moveToFailed($job);
        }
    }
}
