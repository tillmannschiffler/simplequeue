<?php

namespace unit\Job;

use PHPUnit\Framework\TestCase;
use simpleQueue\Job\Job;
use simpleQueue\Job\JobId;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\JobType;

/**
 * @covers \simpleQueue\Job\Job
 */
class JobTest extends TestCase
{
    public function testCanCreate(): void
    {
        $jobIdMock = $this->createMock(JobId::class);
        $jobTypeMock = $this->createMock(JobType::class);
        $jobTypePayloadMock = $this->createMock(JobPayload::class);

        $this->assertInstanceOf(
            Job::class,
            new Job(
                $jobIdMock,
                $jobTypeMock,
                $jobTypePayloadMock
            )
        );
    }

    public function testCanRetrieveValueObjects(): void
    {
        $jobIdMock = $this->createMock(JobId::class);
        $jobTypeMock = $this->createMock(JobType::class);
        $jobTypePayloadMock = $this->createMock(JobPayload::class);

        $job = new Job(
            $jobIdMock,
            $jobTypeMock,
            $jobTypePayloadMock
        );

        $this->assertInstanceOf(
            JobId::class,
            $job->getJobId()
        );

        $this->assertInstanceOf(
            JobType::class,
            $job->getJobType()

        );

        $this->assertInstanceOf(
            JobPayload::class,
            $job->getJobPayload()
        );
    }
}
