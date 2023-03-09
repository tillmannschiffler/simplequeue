<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\Job;
use simpleQueue\Job\OrderId;

class JobWriter
{
    private Directory $inboxDirectory;

    public function __construct(Directory $inboxDirectory)
    {
        $this->inboxDirectory = $inboxDirectory;
    }

    public function store(Job $job): void
    {
        $writeResult = file_put_contents(
            $this->inboxDirectory->toString() . '/' . $job->getJobId()->toString(),
            $job->toJson());
         
        if ($writeResult === false)
            throw new JobInfrastructureException('could not store job file.');
    }
}