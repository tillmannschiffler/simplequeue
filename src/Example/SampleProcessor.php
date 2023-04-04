<?php

declare(strict_types=1);

namespace simpleQueue\Example;

use simpleQueue\Job\Processor;
use simpleQueue\Job\Job;

class SampleProcessor implements Processor
{
    public function execute(Job $job): void
    {
        sleep(rand(1,5));
    }
}