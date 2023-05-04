<?php

declare(strict_types=1);

namespace unit\Event;

use PHPUnit\Framework\TestCase;
use simpleQueue\Event\Clock;

/**
 * @covers \simpleQueue\Event\Clock
 */
class ClockTest extends TestCase
{
    public function testCanCreate(): void
    {
        $this->assertInstanceOf(Clock::class, new Clock());
    }

    public function testCanRetrieveDateTimeImmutable(): void
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, (new Clock())->now());
    }
}
