<?php

declare(strict_types=1);

namespace simpleQueue\Example;

use simpleQueue\Infrastructure\Processor;
use simpleQueue\Job\Job;

class SampleProcessor implements Processor
{
    public function execute(Job $job): void
    {
        var_dump($job);
    }
}