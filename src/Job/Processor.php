<?php

declare(strict_types=1);

namespace simpleQueue\Job;

class Processor
{
    public function execute(Job $job): void
    {
        var_dump($job);
    }
}