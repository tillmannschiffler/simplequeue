<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\Job;

class JobMover
{
    private Directory $inboxDirectory;

    private Directory $finishedDirectory;

    private Directory $failedDirectory;

    private Directory $progressDirectory;

    public function __construct(
        Directory $inboxDirectory,
        Directory $finishedDirectory,
        Directory $failedDirectory,
        Directory $progressDirectory,
    ) {
        $this->inboxDirectory = $inboxDirectory;
        $this->finishedDirectory = $finishedDirectory;
        $this->failedDirectory = $failedDirectory;
        $this->progressDirectory = $progressDirectory;
    }

    public function moveToProgress(Job $job): bool
    {
        return rename(
            $this->inboxDirectory->toString().'/'.$job->getJobId()->toString(),
            $this->progressDirectory->toString().'/'.$job->getJobId()->toString()
        );
    }

    public function moveToFinished(Job $job): bool
    {
        return rename(
            $this->progressDirectory->toString().'/'.$job->getJobId()->toString(),
            $this->finishedDirectory->toString().'/'.$job->getJobId()->toString()
        );
    }

    public function moveToFailed(Job $job): bool
    {
        return rename(
            $this->progressDirectory->toString().'/'.$job->getJobId()->toString(),
            $this->failedDirectory->toString().'/'.$job->getJobId()->toString()

        );
    }
}
