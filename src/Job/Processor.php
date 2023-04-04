<?php

declare(strict_types=1);

namespace simpleQueue\Job;

use simpleQueue\Job\Job;

interface Processor
{
    public function execute(Job $job): void;
}