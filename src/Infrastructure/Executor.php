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

            $this->jobMover->moveToProgress($job);

            ob_start();
            $processor->execute($job);
            $jobEchos = ob_get_clean();
            if ((is_string($jobEchos)) and ($jobEchos != '')) {
                $this->logEmitter->emitJobHasOutput($job, $jobEchos);
            }

            $this->jobMover->moveToFinished($job);
        } catch (\Throwable $exception) {
            $this->logEmitter->emitFailedJober($job, $exception);
            $this->jobMover->moveToFailed($job);
        }
    }
}
