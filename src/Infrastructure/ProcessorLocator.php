<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\JobType;


interface ProcessorLocator
{
    public function getProcessorFor(JobType $jobType): Processor;
}