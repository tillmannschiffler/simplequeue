<?php

declare(strict_types=1);

namespace simpleQueue\Event;

use simpleQueue\Infrastructure\Logger\Subscriber;
use simpleQueue\Job\Job;
use Throwable;

class LogEmitter
{
    private array $subscriber = [];

    private Clock $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function addSubscriber(Subscriber $subscriber): void
    {
        $this->subscriber[] = $subscriber;
    }

    public function emitWaitingForSlot(): void
    {
        $this->publish(new WaitingForSlot($this->clock->now()));
    }

    public function emitFinishedJob(Job $job): void
    {
        $this->publish(new FinishedJob($job, $this->clock->now()));
    }

    public function emitStartedJob(Job $job): void
    {
        $this->publish(new StartedJob($job, $this->clock->now()));
    }

    public function emitStartedExecutor(Job $job): void
    {
        $this->publish(new StartedExecutor($job, $this->clock->now()));
    }

    public function emitCouldNotFork(): void
    {
        $this->publish(new CouldNotFork($this->clock->now()));
    }

    public function emitFailedJober(Job $job, Throwable $exception): void
    {
        $this->publish(new FailedJob($job, $exception, $this->clock->now()));
    }

    public function emitJobHasOutput(Job $job, string $jobOutput): void
    {
        $this->publish(new JobOutput($job, $jobOutput, $this->clock->now()));
    }

    private function publish(Event $event): void
    {
        /** @var Subscriber $subscriber */
        foreach ($this->subscriber as $subscriber) {
            $subscriber->notify($event);
        }
    }
}
