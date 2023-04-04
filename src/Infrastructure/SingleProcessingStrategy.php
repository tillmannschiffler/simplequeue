<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\JobCollection;
use simpleQueue\Job\ProcessingStrategy;

class SingleProcessingStrategy implements ProcessingStrategy
{
    private Executor $executor;

    public function __construct(Executor $executor)
    {
        $this->executor = $executor;
    }
    
    public function process(JobCollection $jobs): void
    {
        foreach ($jobs->all() as $job) 
        {
            $this->executor->process($job);
        }
    }
}