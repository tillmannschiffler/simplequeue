<?php

declare(strict_types=1);

use simpleQueue\Configuration\Configuration;
use simpleQueue\Factory;
use simpleQueue\Infrastructure\Logger\SimpleConsoleLogger;

require_once __DIR__.'/../../vendor/autoload.php';

$factory = new Factory(new Configuration());

$processingStrategy = $factory->createForkingProcessingStrategy();

$processingStrategy->getLogEmitter()->addSubscriber(new SimpleConsoleLogger());

$processingStrategy->process(($factory->createJobReader())->retrieveAllJobs());
