<?php

declare(strict_types=1);

namespace simpleQueue\Job;

use simpleQueue\Infrastructure\Json;

class Job
{
    private JobId $jobId;

    private JobPayload $jobPayload;

    private JobType $jobType;

    public function __construct(JobId $jobId, JobType $jobType, JobPayload $jobPayload)
    {
        $this->jobId = $jobId;
        $this->jobPayload = $jobPayload;
        $this->jobType = $jobType;
    }

    public function getJobType(): JobType
    {
        return $this->jobType;
    }

    public function getJobId(): JobId
    {
        return $this->jobId;
    }

    public function getJobPayload(): JobPayload
    {
        return $this->jobPayload;
    }

    public function toJson(): string
    {
        return Json::encode([
            'jobId' => $this->jobId->toString(),
            'jobType' => $this->jobType->toString(),
            'jobPayload' => $this->jobPayload->toString(),
        ]);
    }
}
