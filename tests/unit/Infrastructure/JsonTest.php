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
                'jobPayload' => 'foo',
            ]))
        );
    }
}
