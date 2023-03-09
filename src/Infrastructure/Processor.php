<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\Job;

interface Processor
{
    public function execute(Job $job): void;
}