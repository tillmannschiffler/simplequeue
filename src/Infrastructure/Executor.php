<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Event\LogEmitter;
use simpleQueue\Job\Job;
use simpleQueue\Job\ProcessorLocator;

class Executor
{
    private ProcessorLocator $processorLocator;

    private JobMover $jobMover;

    private LogEmitter $logEmitter;

    public function __construct(ProcessorLocator $processorLocator, JobMover $jobMover, LogEmitter $logEmitter)
    {
        $this->processorLocator = $processorLocator;
        $this->jobMover = $jobMover;
        $this->logEmitter = $logEmitter;
    }

    public function process(Job $job): void
    {
        try {
            $this->logEmitter->emitStartedExecutor($job);
            $processor = $this->processorLocator->getProcessorFor($job->getJobType());
            $processor->execute($job);
            $this->jobMover->moveToFinished($job);
        } catch (\Throwable $exception) {
            //TODO: log exception
            $this->jobMover->moveToFailed($job);
        }
    }
}
