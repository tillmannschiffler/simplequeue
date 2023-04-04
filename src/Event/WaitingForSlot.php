<?php

declare(strict_types=1);

namespace simpleQueue\Event;

class WaitingForSlot implements Event
{
    private \DateTimeImmutable $dateTimeImmutable;


    public function __construct(\DateTimeImmutable $dateTimeImmutable)
    {
        $this->dateTimeImmutable = $dateTimeImmutable;
    }
    
    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }
}