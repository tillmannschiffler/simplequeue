<?php

declare(strict_types=1);

namespace simpleQueue\Configuration;

use simpleQueue\Infrastructure\Directory;

class Configuraton
{
    public function getInboxDirectory() : Directory
    {
        return Directory::fromString(__DIR__ . '/../../queue/inbox');
    }

    public function getFinishedDirectory() : Directory
    {
        return Directory::fromString(__DIR__ . '/../../queue/finished');
    }

    public function getFailedDirectory() : Directory
    {
        return Directory::fromString(__DIR__ . '/../../queue/failed');
    }
}