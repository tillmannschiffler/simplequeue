<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\Job;
use simpleQueue\Job\ProcessingStrategy;
use Traversable;

class SingleProcessingStrategy implements ProcessingStrategy
{
    private Executor $executor;

    public function __construct(Executor $executor)
    {
        $this->executor = $executor;
    }

    /**
     * @param  Traversable<Job>  $jobs
     */
    public function process(Traversable $jobs): void
    {
        foreach ($jobs as $job) {
            $this->executor->process($job);
        }
    }
}
