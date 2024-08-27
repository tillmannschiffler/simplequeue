<?php

declare(strict_types=1);

namespace simpleQueue\Job;

use Traversable;

interface ProcessingStrategy
{
    /**
     * @param  Traversable<Job>  $jobs
     */
    public function process(Traversable $jobs): void;
}
