<?php declare(strict_types=1);

use simpleQueue\Infrastructure\Directory;
use simpleQueue\Infrastructure\JobFileHandler;
use simpleQueue\Job\Processor;

require_once __DIR__ . '/../vendor/autoload.php';

$jobFileHandler = new JobFileHandler(
    Directory::fromString(__DIR__ . '/../queue/inbox')
);

try {
    $job = $jobFileHandler->retrieve();

    if (is_null($job))
        die();

    $processor = new Processor();
    $processor->execute($job);
    $jobFileHandler->moveToFinished();
} catch (Exception $exception) {
    $jobFileHandler->moveToFailed();
}