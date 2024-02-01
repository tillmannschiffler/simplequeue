<?php

declare(strict_types=1);

use simpleQueue\Configuration\Configuration;
use simpleQueue\Factory;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\JobType;

require_once __DIR__.'/../../vendor/autoload.php';
$factory = new Factory(new Configuration());
$jobWriter = $factory->createJobWriter();

$count = intval($argv[1] ?? '10');

for ($i = 0; $i < $count; $i++) {
    $job = $factory->createJob(
        JobType::fromString('sample'),
        JobPayload::fromString('Hello you simple world!')
    );
    $jobWriter->store($job);
    echo $job->getJobId()->toString().PHP_EOL;
}
