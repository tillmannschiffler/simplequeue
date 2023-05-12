<?php

namespace unit\Job;

use PHPUnit\Framework\TestCase;
use simpleQueue\Job\JobType;

/**
 * @covers \simpleQueue\Job\JobType
 */
class JobTypeTest extends TestCase
{
    public function testCanCreate(): void
    {
        $this->assertInstanceOf(
            JobType::class,
            JobType::fromString('foo')
        );
    }

    public function testCantCreate(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        JobType::fromString('');
    }

    public function testCanValidateString(): void
    {
        $this->assertTrue(JobType::isValid('foo'));
    }

    public function testCantValidateString(): void
    {
        $this->assertFalse(JobType::isValid(''));
    }
}
