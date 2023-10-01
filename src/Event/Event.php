<?php

declare(strict_types=1);

namespace simpleQueue\Event;

interface Event
{
    public function getDateTimeImmutable(): \DateTimeImmutable;
}
