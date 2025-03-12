<?php

declare(strict_types=1);

namespace simpleQueue\Configuration;

use simpleQueue\Infrastructure\Directory;

class Configuration
{
    protected string $basePath;

    public function __construct(?string $basePath = null)
    {
        if ($basePath === null) {
            $basePath = dirname(__DIR__, 2);
        }

        $this->basePath = $basePath;
    }

    public function getMaxForkChilds(): int
    {
        return 5;
    }

    public function getInboxDirectory(): Directory
    {
        return Directory::fromString($this->basePath.'/queue/inbox');
    }

    public function getFinishedDirectory(): Directory
    {
        return Directory::fromString($this->basePath.'/queue/finished');
    }

    public function getFailedDirectory(): Directory
    {
        return Directory::fromString($this->basePath.'/queue/failed');
    }

    public function getProgressDirectory(): Directory
    {
        return Directory::fromString($this->basePath.'/queue/progress');
    }
}
