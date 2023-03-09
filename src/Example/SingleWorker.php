<?php

declare(strict_types=1);

use simpleQueue\Configuration\Configuraton;
use simpleQueue\Factory;

require_once __DIR__ . '/../../vendor/autoload.php';

$factory = new Factory(new Configuraton());

$processingStrategy = $factory->createSingleProcessingStrategy();
$processingStrategy->process(($factory->createJobReader())->retrieveAllJobs());