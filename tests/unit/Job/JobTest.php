<?php

namespace unit\Job;

use PHPUnit\Framework\TestCase;
use simpleQueue\Infrastructure\Uuid;
use simpleQueue\Job\Job;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\JobType;

/**
 * @covers \simpleQueue\Job\Job
 */
class JobTest extends TestCase
{
    public function testCanCreate(): void
    {
        $uuidMock = $this->createMock(Uuid::class);
        $jobTypeMock = $this->createMock(JobType::class);
        $jobTypePayloadMock = $this->createMock(JobPayload::class);

        $this->assertInstanceOf(
            Job::class,
            new Job(
                $uuidMock,
                $jobTypeMock,
                $jobTypePayloadMock
            )
        );
    }

    public function testCanRetrieveValueObjects(): void
    {
        $uuidMock = $this->createMock(Uuid::class);
        $jobTypeMock = $this->createMock(JobType::class);
        $jobTypePayloadMock = $this->createMock(JobPayload::class);

        $job = new Job(
            $uuidMock,
            $jobTypeMock,
            $jobTypePayloadMock
        );

        $this->assertInstanceOf(
            Uuid::class,
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
