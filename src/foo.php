<?php

use simpleQueue\Infrastructure\JobFileHandler;
use simpleQueue\Infrastructure\Uuid;
use simpleQueue\Job\Job;
use simpleQueue\Job\JobId;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\OrderId;

require_once __DIR__ . '/../vendor/autoload.php';
$directory = \simpleQueue\Infrastructure\Directory::fromString(__DIR__ . '/../queue/inbox');
$jobFileHandler = new JobFileHandler($directory);



for ($i=0;$i<=2;$i++)
{
    $job = new Job(
        Uuid::create(),
        JobPayload::fromString('Hello World!')
    );

    $jobFileHandler->store($job);
    
}