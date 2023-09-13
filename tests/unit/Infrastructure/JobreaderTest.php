<?php

namespace unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use simpleQueue\Infrastructure\Directory;
use simpleQueue\Infrastructure\JobReader;

/**
 * @covers \simpleQueue\Infrastructure\JobReader
 */
class JobreaderTest extends TestCase
{
    public function testCanCreate(): void
    {
        $mockDir = $this->createMock(Directory::class);

        $this->assertInstanceOf(
            JobReader::class,
            new JobReader($mockDir)
        );
    }

    public function testThrowExceptionOnInvalidJobFile(): void
    {
        $mockDir = $this->createMock(Directory::class);
        $mockDir->expects($this->atLeastOnce())->method('toString')->willReturn(
            __DIR__.'/../Fixtures/'
        );

        $this->expectException(\InvalidArgumentException::class);
        (new JobReader($mockDir))->retrieveOldest();
    }
}
