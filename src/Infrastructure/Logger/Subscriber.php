<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure\Logger;

use simpleQueue\Event\Event;

interface Subscriber
{
    public function notify(Event $event): void;
}