<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use simpleQueue\Job\Job;
use simpleQueue\Job\JobCollection;

class JobCollectionTest extends TestCase
{
    public function testIteration(): void
    {
        $job1 = $this->createMock(Job::class);
        $job2 = $this->createMock(Job::class);

        $collection = new JobCollection();
        $collection->add($job1);
        $collection->add($job2);

        $jobs = [];

        // ensure iteration works
        foreach ($collection as $job) {
            $jobs[] = $job;
        }

        // ensure iteration gives the correct values and order
        static::assertCount(2, $jobs);
        static::assertSame([$job1, $job2], $jobs);
    }
}
