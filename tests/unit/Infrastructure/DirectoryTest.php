<?php

namespace unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use simpleQueue\Infrastructure\Directory;

/**
 * @covers \simpleQueue\Infrastructure\Directory
 */
class DirectoryTest extends TestCase
{
    public function testCanCreateWithValidDirectory(): void
    {
        $this->assertInstanceOf(
            Directory::class,
            Directory::fromString(__DIR__)
        );
    }

    public function testCantCreateWithValidDirectory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Directory::fromString('foo');
    }

    public function testCanValidateString(): void
    {
        $this->assertTrue(Directory::isValid(__DIR__));
        $this->assertFalse(Directory::isValid('foo'));
    }
}
