<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure\Logger;

use simpleQueue\Event\Event;

class SimpleConsoleLogger implements Subscriber
{
    public function notify(Event $event): void
    {
        var_dump($event);
    }
}
