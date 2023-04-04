<?php

declare(strict_types=1);

namespace simpleQueue;

use simpleQueue\Configuration\Configuraton;
use simpleQueue\Event\Clock;
use simpleQueue\Event\LogEmmitter;
use simpleQueue\Example\SampleProcessorLocator;
use simpleQueue\Infrastructure\Executor;
use simpleQueue\Infrastructure\ForkingProcessingStrategy;
use simpleQueue\Infrastructure\JobMover;
use simpleQueue\Infrastructure\JobReader;
use simpleQueue\Infrastructure\JobWriter;
use simpleQueue\Infrastructure\SingleProcessingStrategy;
use simpleQueue\Infrastructure\Uuid;
use simpleQueue\Job\Job;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\JobType;
use simpleQueue\Job\ProcessorLocator;

class Factory
{
    private Configuraton $configuraton;

    public function __construct(Configuraton $configuraton)
    {
        $this->configuraton = $configuraton;
    }
    
    public function createSingleProcessingStrategy(): SingleProcessingStrategy
    {
        return new SingleProcessingStrategy($this->createExecutor());
    }
    
    public function createForkingProcessingStrategy(): ForkingProcessingStrategy
    {
        return new ForkingProcessingStrategy(
            $this->createExecutor(), 
            $this->configuraton->getMaxForkChilds(),
            $this->createEmitter()
        );
    }

    public function createExecutor():Executor
    {
        return new Executor(
            $this->createProcessorLocator(),
            $this->createJobMover()
        );
    }

    public function createJobReader() : JobReader
    {
        return new JobReader($this->configuraton->getInboxDirectory());
    }
    
    public function createJobMover() : JobMover
    {
        return new JobMover(
            $this->configuraton->getInboxDirectory(),
            $this->configuraton->getFinishedDirectory(),
            $this->configuraton->getFailedDirectory()
        );
    }

    public function createJobWriter() : JobWriter
    {
        return new JobWriter($this->configuraton->getInboxDirectory());
    }

    public function createProcessorLocator(): ProcessorLocator
    {
        return new SampleProcessorLocator();
    }

    public function createJob(JobType $jobType, JobPayload $jobPayload) : Job
    {
        return new Job(
            Uuid::create(),
            $jobType,
            $jobPayload
        );
    }

    public function createEmitter() : LogEmmitter
    {
        return new LogEmmitter(
            new Clock()
        );
    }
}