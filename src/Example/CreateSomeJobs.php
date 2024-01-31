<?php

declare(strict_types=1);

use simpleQueue\Configuration\Configuration;
use simpleQueue\Factory;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\JobType;

require_once __DIR__.'/../../vendor/autoload.php';
$factory = new Factory(new Configuration());
$jobWriter = $factory->createJobWriter();

for ($i = 0; $i < 10; $i++) {
    $jobWriter->store(
        $factory->createJob(
            JobType::fromString('sample'),
            JobPayload::fromString('Hello you simple world!')
        )
    );
}
