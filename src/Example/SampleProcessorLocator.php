<?php

declare(strict_types=1);

namespace simpleQueue\Example;

use simpleQueue\Job\JobType;
use simpleQueue\Job\Processor;
use simpleQueue\Job\ProcessorLocator;

class SampleProcessorLocator implements ProcessorLocator
{
    public function getProcessorFor(JobType $jobType): Processor
    {
        return new SampleProcessor();
    }
}
