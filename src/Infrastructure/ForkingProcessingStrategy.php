<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\Job;
use simpleQueue\Job\JobCollection;

class ForkingProcessingStrategy implements ProcessingStrategy
{
    private Executor $executor;

    public function __construct(Executor $executor)
    {
        $this->executor = $executor;
    }
    
    public function process(JobCollection $jobs): void
    {
        foreach ($jobs->all() as $job) {
            $pid = pcntl_fork();
            if ($pid == -1) {
                die('Konnte nicht verzweigen');
            } else if ($pid) {
                $pidList[] = $pid;
            } else {
                $this->executor->process($job);
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
    }
}