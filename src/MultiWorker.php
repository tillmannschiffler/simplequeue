<?php declare(strict_types=1);

use simpleQueue\Infrastructure\Directory;
use simpleQueue\Infrastructure\JobFileHandler;
use simpleQueue\Job\Processor;

require_once __DIR__ . '/../vendor/autoload.php';

$directory = Directory::fromString(__DIR__ . '/../queue/inbox');
$jobFileHandler = new JobFileHandler($directory);


$pidList = [];
$jobCollection =[
'72532079-d85e-4ec3-bf92-9c00d6f6ca68',  
'93e4b61b-76b8-4de4-acef-648cac02d2cf',
'81721e69-8e7b-498d-9686-03542ed8a732',    
]; 


foreach ($jobCollection as $jobId) {
    $pid = pcntl_fork();
    if ($pid == -1) {
        die('Konnte nicht verzweigen');
    } else if ($pid) {
        $pidList[] = $pid;
    } else {
        try {
            $job = $jobFileHandler->retrieve($jobId);

            if (is_null($job))
                die();

            $processor = new Processor();
            $processor->execute($job);
            $jobFileHandler->moveToFinished();
        } catch (Exception $exception) {
            $jobFileHandler->moveToFailed();
        }
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