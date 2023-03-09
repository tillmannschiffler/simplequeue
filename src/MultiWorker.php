<?php 

declare(strict_types=1);

use simpleQueue\Configuration\Configuraton;
use simpleQueue\Factory;
use simpleQueue\Infrastructure\Directory;
use simpleQueue\Infrastructure\JobWriter;
use simpleQueue\Job\SampleProcessor;

require_once __DIR__ . '/../vendor/autoload.php';

$directory = Directory::fromString(__DIR__ . '/../queue/inbox');
$jobFileHandler = new JobWriter($directory);
$factory = new Factory(new Configuraton());

$jobFileHandler = $factory->createJobReader();
$jobFileMover = $factory->createJobMover();
$processorLocator = $factory->createProcessorLocator();


$pidList = [];

$jobCollection = $jobFileHandler->retrieveAllJobs();


/** @var simpleQueue\Job\Job $job */
foreach ($jobCollection->all() as $job) {
    $pid = pcntl_fork();
    if ($pid == -1) {
        die('Konnte nicht verzweigen');
    } else if ($pid) {
        $pidList[] = $pid;
    } else {
        try {

            $processor = $processorLocator->getProcessorFor($job->getJobType());
            $processor->execute($job);
            $jobFileMover->moveToFinished($job);
        } catch (Exception $exception) {
            $jobFileMover->moveToFailed($job);
        }
        exit;
    }
}

while (!empty($pidList))
{
    foreach ($pidList as $pos => $pId) 
    {
        $code = pcntl_waitpid($pId, $status, WNOHANG);
        
        if ($code === 0) continue;
        unset($pidList[$pos]);
        
    }
    sleep(1);
}