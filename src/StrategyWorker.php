<?php

declare(strict_types=1);

use simpleQueue\Configuration\Configuraton;
use simpleQueue\Factory;
use simpleQueue\Infrastructure\ForkingProcessingStrategy;

use simpleQueue\Job\SampleProcessor;

require_once __DIR__ . '/../vendor/autoload.php';


$factory = new Factory(new Configuraton());

$jobFileHandler = $factory->createJobReader();

$strategy = new ForkingProcessingStrategy($factory->createExecutor());

$jobCollection = $jobFileHandler->retrieveAllJobs();

$strategy->process($jobCollection);
