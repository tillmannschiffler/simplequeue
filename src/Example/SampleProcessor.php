<?php

declare(strict_types=1);

namespace simpleQueue\Example;

use simpleQueue\Job\Job;
use simpleQueue\Job\Processor;

class SampleProcessor implements Processor
{
    public function execute(Job $job): void
    {
        echo 'sdf'.time();
    }
}
