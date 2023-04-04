<?php

declare(strict_types=1);

use simpleQueue\Configuration\Configuraton;
use simpleQueue\Factory;
use simpleQueue\Infrastructure\Logger\SimpleConsoleLogger;

require_once __DIR__ . '/../../vendor/autoload.php';

$factory = new Factory(new Configuraton());

$processingStrategy = $factory->createForkingProcessingStrategy();
$processingStrategy->getLogEmmitter()->addSubscriber(new SimpleConsoleLogger());
$processingStrategy->process(($factory->createJobReader())->retrieveAllJobs());