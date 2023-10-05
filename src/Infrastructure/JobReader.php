<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\Job;
use simpleQueue\Job\JobCollection;
use simpleQueue\Job\JobId;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\JobType;

class JobReader
{
    private Directory $directory;

    private array $ignoreFiles = [
        '.',
        '..',
        '.gitkeep',
    ];

    public function __construct(Directory $directory)
    {
        $this->directory = $directory;
    }

    public function retrieveOldest(): ?Job
    {
        $jobFile = $this->oldestFromDir();

        if (is_null($jobFile)) {
            return null;
        }

        return $this->read($jobFile);
    }

    public function retrieveAllJobs(): JobCollection
    {
        $jobCollection = new JobCollection();

        foreach (scandir($this->directory->toString()) as $file) {
            if (in_array($file, $this->ignoreFiles)) {
                continue;
            }

            $jobCollection->add(
                $this->read(Filename::fromString($this->directory->toString().'/'.$file))
            );
        }

        return $jobCollection;
    }

    private function oldestFromDir(): ?Filename
    {
        $oldest_file = null;
        $oldest_time = time();
        $file = null;

        foreach (scandir($this->directory->toString()) as $file) {
            if (in_array($file, $this->ignoreFiles)) {
                continue;
            }

            $file_time = filemtime($this->directory->toString().'/'.$file);
            if ($file_time < $oldest_time) {
                $oldest_file = $file;
                $oldest_time = $file_time;
            }
        }

        if (is_null($oldest_file)) {
            return null;
        }

        return Filename::fromString($this->directory->toString().'/'.$file);
    }

    private function read(Filename $file): Job
    {
        $content = file_get_contents($file->toString());
        if (! $content) {
            throw new \InvalidArgumentException('Job File could not be read.');
        }

        $json = Json::fromString($content);

        return new Job(
            JobId::fromString($json->getJobId()),
            JobType::fromString($json->getJobType()),
            JobPayload::fromString($json->getJobPayload())
        );
    }
}
