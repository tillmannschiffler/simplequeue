<?php

declare(strict_types=1);

namespace simpleQueue\Job;

interface ProcessingStrategy
{
    public function process(JobCollection $jobs): void;
}
