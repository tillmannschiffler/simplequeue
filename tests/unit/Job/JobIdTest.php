<?php

declare(strict_types=1);

namespace unit\Job;

use PHPUnit\Framework\TestCase;
use simpleQueue\Job\JobId;

class JobIdTest extends TestCase
{
    public function testCanCreate(): void
    {
        $this->assertInstanceOf(
            JobId::class,
            JobId::fromString('foo')
        );
    }

    public function testCanValidateString(): void
    {
        $this->assertTrue(JobId::isValid('foo'));
    }

    public function testToString(): void
    {
        $jobId = JobId::fromString('foo');
        $this->assertEquals('foo', $jobId->toString());
    }
}
