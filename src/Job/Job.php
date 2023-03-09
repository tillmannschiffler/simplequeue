<?php

declare(strict_types=1);

namespace simpleQueue\Job;

use simpleQueue\Infrastructure\Uuid;

class Job
{
    private Uuid $jobId;
    private JobPayload $jobPayload;
    private JobType $jobType;

    public function __construct(Uuid $jobId, JobType $jobType, JobPayload $jobPayload)
    {
        $this->jobId = $jobId;
        $this->jobPayload = $jobPayload;
        $this->jobType = $jobType;
    }

    /**
     * @return JobType
     */
    public function getJobType(): JobType
    {
        return $this->jobType;
    }

    public function getJobId(): Uuid
    {
        return $this->jobId;
    }

    public function getJobPayload(): JobPayload
    {
        return $this->jobPayload;
    }

    public function toJson(): string
    {
        return json_encode(
            [
                'jobId'         => $this->jobId->toString(),
                'jobPayload'    => $this->jobPayload->toString()    
            ]
        );
    }
}