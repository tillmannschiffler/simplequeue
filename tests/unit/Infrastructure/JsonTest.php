<?php

namespace unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use simpleQueue\Infrastructure\JobInfrastructureException;
use simpleQueue\Infrastructure\Json;
use simpleQueue\Job\JobId;
use simpleQueue\Job\JobPayload;

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
                'jobPayload' => 'foo',
            ]))
        );
    }

    public function testCanRetrieveValueObjects(): void
    {
        $job = Json::fromString(json_encode([
                'jobId' => '1',
                'jobPayload' => 'foo',
            ]));

        $this->assertEquals('1', $job->getJobId());
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
            'jobPayload' => 'foo',
        ]));
    }

    public function testCanThrowWithInvalidJobpayload(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString(json_encode([
            'jobId' => '1',
        ]));
    }

    public function testCanThrowWithJobIdNotBeeingAString(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString(json_encode([
            'jobId' => 1,
            'jobPayload' => 'foo',
        ]));
    }

    public function testCanThrowWithJobPayloadNotBeeingAString(): void
    {
        $this->expectException(JobInfrastructureException::class);
        Json::fromString(json_encode([
            'jobId' => '1',
            'jobPayload' => 0,
        ]));
    }
}
