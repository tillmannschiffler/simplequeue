<?php

declare(strict_types=1);

namespace simpleQueue\Configuration;

use simpleQueue\Infrastructure\Directory;

class Configuration
{
    public function getMaxForkChilds(): int
    {
        return 5;
    }

    public function getInboxDirectory(): Directory
    {
        return Directory::fromString(__DIR__.'/../../queue/inbox');
    }

    public function getFinishedDirectory(): Directory
    {
        return Directory::fromString(__DIR__.'/../../queue/finished');
    }

    public function getFailedDirectory(): Directory
    {
        return Directory::fromString(__DIR__.'/../../queue/failed');
    }

    public function getProgressDirectory(): Directory
    {
        return Directory::fromString(__DIR__.'/../../queue/progress');
    }
}
