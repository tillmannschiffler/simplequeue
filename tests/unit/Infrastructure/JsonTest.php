<?php

namespace unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use simpleQueue\Infrastructure\JobInfrastructureException;
use simpleQueue\Infrastructure\Json;

/**
 * @covers \simpleQueue\Infrastructure\Json
 */
class JsonTest extends TestCase
{
    public function testCantCreateWithInvalidJsonString(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString('sdf');
    }

    public function testCanEncode(): void
    {
        $this->assertEquals(
            '{"foo":"bah"}',
            Json::encode(['foo' => 'bah'])
        );
    }

    public function testCanCreateWithValidJobData(): void
    {
        $this->assertInstanceOf(
            Json::class,
            Json::fromString(json_encode([
                'jobId' => '1',
                'jobType' => 'bar',
                'jobPayload' => 'foo',
            ]))
        );
    }

    public function testCanRetrieveValueObjects(): void
    {
        $job = Json::fromString(json_encode([
            'jobId' => '1',
            'jobType' => 'bar',
            'jobPayload' => 'foo',
        ]));

        $this->assertEquals('1', $job->getJobId());
        $this->assertEquals('bar', $job->getJobType());
        $this->assertEquals('foo', $job->getJobPayload());
    }

    public function testCanThrowWithInvalidJobData(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString('foo');
    }

    public function testCanThrowWithInvalidJobId(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString(json_encode([
            'jobType' => 'bar',
            'jobPayload' => 'foo',
        ]));
    }

    public function testCanThrowWithInvalidJobType(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString(json_encode([
            'jobId' => '1',
            'jobPayload' => 'foo',
        ]));
    }

    public function testCanThrowWithInvalidJobPayload(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString(json_encode([
            'jobId' => '1',
            'jobType' => 'bar',
        ]));
    }

    public function testCanThrowWithJobIdNotBeeingAString(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString(json_encode([
            'jobId' => 1,
            'jobType' => 'bar',
            'jobPayload' => 'foo',
        ]));
    }

    public function testCanThrowWithJobTypeNotBeeingAString(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString(json_encode([
            'jobId' => '1',
            'jobType' => 2,
            'jobPayload' => 'foo',
        ]));
    }

    public function testCanThrowWithJobPayloadNotBeeingAString(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString(json_encode([
            'jobId' => '1',
            'jobType' => 'bar',
            'jobPayload' => 0,
        ]));
    }
}
