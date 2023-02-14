<?php

use simpleQueue\Infrastructure\JobFileHandler;
use simpleQueue\Infrastructure\Directory;
use simpleQueue\Job\Processor;

require_once __DIR__ . '/../vendor/autoload.php';

$directory = Directory::fromString(__DIR__ . '/../queue/inbox');
$jobFileHandler = new JobFileHandler($directory);

try {
    $job = $jobFileHandler->retrieve();
    if (is_null($job))
    {
        sleep(1);
        die();
    }
    
    $processor = new Processor();
    $processor->execute($job);
    $jobFileHandler->moveToFinished();
}
catch (Exception $exception)
{
    $jobFileHandler->moveToFailed();
}

