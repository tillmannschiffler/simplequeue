<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\Job;
use simpleQueue\Job\JobPayload;
use simpleQueue\Job\OrderId;

class JobFileHandler
{
    private Directory $directory;
    private ?Filename $filename = null; 

    public function __construct(Directory $directory)
    {
        $this->directory = $directory;
    }
    

    public function retrieve() : ?Job
    {
        $jobFile = $this->oldestFromDir();
        
        if (is_null($jobFile))
            return null;
        
        return $this->read(
            Filename::fromString(
                $this->directory->toString() . '/' . $jobFile->toString()
            )
        );
    }

    public function store(Job $job): void
    {
        $writeResult = $this->write($job);
        
        if ($writeResult === false)
            throw new JobInfrastructureException('could not store job file.');
    }

    public function moveToFinished(): bool
    {
        return rename(
            $this->directory->toString() . '/' . $this->filename->toString(),
            $this->directory->toString().'/../finished/'.$this->filename->toString()
            
        );
    }

    public function moveToFailed(): bool
    {
        return rename(
            $this->directory->toString() . '/' . $this->filename->toString(),
            $this->directory->toString().'/../failed/'.$this->filename->toString()

        );
    }
    
    private function oldestFromDir(): ?Filename
    {
        $oldest_file = null;
        $oldest_time = time();
        $file = null;

        foreach (scandir($this->directory->toString()) as $file) 
        {
            if ($file === '.' || $file === '..') 
                continue;
            
            $file_time = filemtime($this->directory->toString().'/'.$file);
            if ($file_time < $oldest_time) 
            {
                $oldest_file = $file;
                $oldest_time = $file_time;
            }
        }

        if (is_null($oldest_file))
            return null;

        $this->filename = Filename::fromString($file);
        return $this->filename;
    }

    private function write(Job $job) : bool|int
    {
        return file_put_contents(
            $this->directory->toString() . '/' . $job->getJobId()->toString(),
            $job->toJson()
        );
    }
    
    private function read(Filename $file): Job
    {
        $content = file_get_contents($file->toString());
        if (!$content)
            throw new \InvalidArgumentException('Job File could not be read.');

        $decodetContent = json_decode($content);
        if (is_null($decodetContent))
            throw new JobInfrastructureException(json_last_error_msg());


        if (!isset($decodetContent->jobId))
            throw new JobInfrastructureException('Missing job id in job file.');

        if (!isset($decodetContent->jobPayload))
            throw new JobInfrastructureException('Missing payload id in job file.');
        
        return new Job(
            Uuid::fromString($decodetContent->jobId),
            JobPayload::fromString($decodetContent->jobPayload)
        );
    }
}