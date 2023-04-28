<?php

declare(strict_types=1);

namespace simpleQueue\Event;

class Clock
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }
}
