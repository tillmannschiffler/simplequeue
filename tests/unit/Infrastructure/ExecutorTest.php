<?php

declare(strict_types=1);

namespace unit\Infrastructure;

use PHPUnit\Framework\TestCase;
use simpleQueue\Event\LogEmitter;
use simpleQueue\Infrastructure\Executor;
use simpleQueue\Infrastructure\JobMover;
use simpleQueue\Job\Job;
use simpleQueue\Job\Processor;
use simpleQueue\Job\ProcessorLocator;

/**
 * @covers \simpleQueue\Infrastructure\Executor
 */
class ExecutorTest extends TestCase
{
    public function testCanEmmitProcessorOutput(): void
    {
        $processor = $this->createMock(Processor::class);
        $processor->method('execute')->willReturnCallback(
            static function () {
                echo 'some Output while processing a job';
            }
        );

        $processorLocator = $this->createMock(ProcessorLocator::class);
        $processorLocator->method('getProcessorFor')->willReturn($processor);

        $jobMover = $this->createMock(JobMover::class);
        $jobMover->expects($this->once())->method('moveToProgress');
        $jobMover->expects($this->once())->method('moveToFinished');

        $logEmitter = $this->createMock(LogEmitter::class);
        $logEmitter->expects($this->once())->method('emitJobHasOutput');
        $logEmitter->expects($this->once())->method('emitStartedExecutor');

        $job = $this->createMock(Job::class);

        $executor = new Executor(
            $processorLocator,
            $jobMover,
            $logEmitter
        );

        $executor->process($job);
    }

    public function testDontEmmitOnEmptyOutput(): void
    {
        $processor = $this->createMock(Processor::class);
        $processor->method('execute')->willReturnCallback(
            static function () {
                echo '';
            }
        );

        $processorLocator = $this->createMock(ProcessorLocator::class);
        $processorLocator->method('getProcessorFor')->willReturn($processor);

        $jobMover = $this->createMock(JobMover::class);
        $jobMover->expects($this->once())->method('moveToProgress');
        $jobMover->expects($this->once())->method('moveToFinished');

        $logEmitter = $this->createMock(LogEmitter::class);
        $logEmitter->expects($this->never())->method('emitJobHasOutput');
        $logEmitter->expects($this->once())->method('emitStartedExecutor');

        $job = $this->createMock(Job::class);

        $executor = new Executor(
            $processorLocator,
            $jobMover,
            $logEmitter
        );

        $executor->process($job);
    }

    public function testCanCatch(): void
    {
        $processorLocator = $this->createMock(ProcessorLocator::class);
        $processorLocator->method('getProcessorFor')->willReturnCallback(
            static function () {
                throw new \Exception('Foo');
            }
        );

        $jobMover = $this->createMock(JobMover::class);
        $jobMover->expects($this->once())->method('moveToFailed');
        $jobMover->expects($this->never())->method('moveToFinished');

        $logEmitter = $this->createMock(LogEmitter::class);
        $logEmitter->expects($this->once())->method('emitFailedJober');

        $job = $this->createMock(Job::class);

        $executor = new Executor(
            $processorLocator,
            $jobMover,
            $logEmitter
        );

        $executor->process($job);
        $this->assertInstanceOf(Executor::class, $executor);
    }
}
