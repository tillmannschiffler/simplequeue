<?php

declare(strict_types=1);

namespace simpleQueue\Event;

use simpleQueue\Infrastructure\Logger\Subscriber;
use simpleQueue\Job\Job;

class LogEmmitter
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

    public function emmitWaitingForSlot(): void
    {
        $this->publish(new WaitingForSlot($this->clock->now()));
    }

    public function emmitFinishedJob(Job $job): void
    {
        $this->publish(new FinishedJob($job, $this->clock->now()));
    }

    public function emmitStartedJob(Job $job): void
    {
        $this->publish(new StartedJob($job, $this->clock->now()));
    }

    public function emmitStartedExecutor(Job $job): void
    {
        $this->publish(new StartedExecutor($job, $this->clock->now()));
    }

    public function emmitCouldNotFork()
    {
        $this->publish(new CouldNotFork($this->clock->now()));
    }

    private function publish(Event $event): void
    {
        /** @var Subscriber $subscriber */
        foreach ($this->subscriber as $subscriber) {
            $subscriber->notify($event);
        }
    }
}
