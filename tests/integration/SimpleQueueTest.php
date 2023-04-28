<?php

declare(strict_types=1);

namespace integration;

use PHPUnit\Framework\TestCase;
use simpleQueue\Configuration\Configuration;
use simpleQueue\Factory;
use simpleQueue\Infrastructure\Directory;
use simpleQueue\Infrastructure\Logger\SimpleConsoleLogger;
use simpleQueue\Infrastructure\Uuid;
use simpleQueue\Job\Job;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\JobType;

/**
 * @covers \simpleQueue\Factory
 * @covers \simpleQueue\Infrastructure\ForkingProcessingStrategy
 * @covers \simpleQueue\Infrastructure\Executor
 * @covers \simpleQueue\Event\LogEmmitter
 * @covers \simpleQueue\Job\JobCollection
 * @covers \simpleQueue\Infrastructure\JobMover
 * @covers \simpleQueue\Infrastructure\JobReader
 * @covers \simpleQueue\Infrastructure\Directory
 * @covers \simpleQueue\Infrastructure\Filename
 * @covers \simpleQueue\Infrastructure\JobWriter
 * @covers \simpleQueue\Job\Job
 * @covers \simpleQueue\Job\JobPayload
 * @covers \simpleQueue\Job\JobType
 * @covers \simpleQueue\Infrastructure\Uuid
 */
class SimpleQueueTest extends TestCase
{
    private Factory $factory;

    private string $jobId = '123abc';

    public function setUp(): void
    {
        parent::setUp();

        $configurationMock = $this->createMock(Configuration::class);
        $configurationMock->method('getInboxDirectory')->willReturn(Directory::fromString(__DIR__.'/../queue/inbox'));
        $configurationMock->method('getFinishedDirectory')->willReturn(Directory::fromString(__DIR__.'/../queue/finished'));
        $configurationMock->method('getFailedDirectory')->willReturn(Directory::fromString(__DIR__.'/../queue/failed'));
        $configurationMock->method('getMaxForkChilds')->willReturn(1);

        $this->factory = new Factory($configurationMock);
        $jobWriter = $this->factory->createJobWriter();

        $jobWriter->store(
            new Job(
                Uuid::fromString($this->jobId),
                JobType::fromString('sample'),
                JobPayload::fromString('Hello you simple world!')
            )
        );
    }

    public function testBiggerScope(): void
    {

        $consoleLoggerMock = $this->createMock(SimpleConsoleLogger::class);
        $processingStrategy = $this->factory->createForkingProcessingStrategy();
        $processingStrategy->getLogEmmitter()->addSubscriber($consoleLoggerMock);
        $processingStrategy->process(($this->factory->createJobReader())->retrieveAllJobs());

        $this->assertTrue(file_exists(__DIR__.'/../queue/finished/'.$this->jobId));
    }

    public function tearDown(): void
    {
        if (file_exists(__DIR__.'/../queue/inbox/'.$this->jobId)) unlink(__DIR__.'/../queue/inbox/'.$this->jobId);
        if (file_exists(__DIR__.'/../queue/finished/'.$this->jobId)) unlink(__DIR__.'/../queue/finished/'.$this->jobId);
        if (file_exists(__DIR__.'/../queue/failed/'.$this->jobId)) unlink(__DIR__.'/../queue/failed/'.$this->jobId);

        parent::tearDown();
    }
}
