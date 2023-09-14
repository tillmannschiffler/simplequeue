<?php

namespace unit\Job;

use PHPUnit\Framework\TestCase;
use simpleQueue\Job\JobPayload;

/**
 * @covers \simpleQueue\Job\JobPayload
 */
class JobPayloadTest extends TestCase
{
    public function testCanCreate(): void
    {
        $this->assertInstanceOf(
            JobPayload::class,
            JobPayload::fromString('foo')
        );
    }

    public function testCanValidateString(): void
    {
        $this->assertTrue(JobPayload::isValid('foo'));
    }

    public function testToString(): void
    {
        $jobType = JobPayload::fromString('foo');
        $this->assertEquals('foo', $jobType->toString());
    }
}
