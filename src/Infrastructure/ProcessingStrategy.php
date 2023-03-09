<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\Job;
use simpleQueue\Job\JobCollection;

interface ProcessingStrategy
{
    public function process(JobCollection $jobs): void;
}