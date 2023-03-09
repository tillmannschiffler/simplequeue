<?php

declare(strict_types=1);

use simpleQueue\Configuration\Configuraton;
use simpleQueue\Factory;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\JobType;

require_once __DIR__ . '/../../vendor/autoload.php';
$factory = new Factory(new Configuraton());
$jobWriter = $factory->createJobWriter();

$jobCollection = $factory->createJobReader()->retrieveAllJobs();

for ($i=0;$i<=1;$i++)
{
    $jobWriter->store(
        $factory->createJob(
            JobType::fromString('sample'),
            JobPayload::fromString('Hello you simple world!')
        )
    );
}