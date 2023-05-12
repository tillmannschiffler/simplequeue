<?php

declare(strict_types=1);

namespace simpleQueue;

use simpleQueue\Configuration\Configuration;
use simpleQueue\Event\Clock;
use simpleQueue\Event\LogEmitter;
use simpleQueue\Example\SampleProcessorLocator;
use simpleQueue\Infrastructure\Executor;
use simpleQueue\Infrastructure\ForkingProcessingStrategy;
use simpleQueue\Infrastructure\JobMover;
use simpleQueue\Infrastructure\JobReader;
use simpleQueue\Infrastructure\JobWriter;
use simpleQueue\Infrastructure\Uuid;
use simpleQueue\Job\Job;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\JobType;
use simpleQueue\Job\ProcessorLocator;

class Factory
{
    private Configuration $configuraton;

    public function __construct(Configuration $configuraton)
    {
        $this->configuraton = $configuraton;
    }

    public function createForkingProcessingStrategy(): ForkingProcessingStrategy
    {
        $logEmitter = $this->createLogEmitter();

        return new ForkingProcessingStrategy(
            new Executor(
                $this->createProcessorLocator(),
                $this->createJobMover(),
                $logEmitter
            ),
            $this->configuraton->getMaxForkChilds(),
            $logEmitter
        );
    }

    public function createJobReader(): JobReader
    {
        return new JobReader($this->configuraton->getInboxDirectory());
    }

    public function createJobMover(): JobMover
    {
        return new JobMover(
            $this->configuraton->getInboxDirectory(),
            $this->configuraton->getFinishedDirectory(),
            $this->configuraton->getFailedDirectory(),
            $this->configuraton->getProgressDirectory(),
        );
    }

    public function createJobWriter(): JobWriter
    {
        return new JobWriter($this->configuraton->getInboxDirectory());
    }

    public function createProcessorLocator(): ProcessorLocator
    {
        return new SampleProcessorLocator();
    }

    public function createJob(JobType $jobType, JobPayload $jobPayload): Job
    {
        return new Job(
            Uuid::create(),
            $jobType,
            $jobPayload
        );
    }

    public function createLogEmitter(): LogEmitter
    {
        return new LogEmitter(
            new Clock()
        );
    }
}
