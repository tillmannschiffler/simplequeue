<?php

declare(strict_types=1);

namespace simpleQueue\Example;

use simpleQueue\Infrastructure\Processor;
use simpleQueue\Infrastructure\ProcessorLocator;
use simpleQueue\Job\JobType;

class SampleProcessorLocator implements ProcessorLocator
{
    public function getProcessorFor(JobType $jobType): Processor
    {
        return new SampleProcessor();
    }
}