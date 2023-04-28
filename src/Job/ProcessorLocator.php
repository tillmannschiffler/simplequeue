<?php

declare(strict_types=1);

namespace simpleQueue\Job;

interface ProcessorLocator
{
    public function getProcessorFor(JobType $jobType): Processor;
}
