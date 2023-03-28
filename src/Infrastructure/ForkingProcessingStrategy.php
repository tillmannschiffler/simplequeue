<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Job\JobCollection;

class ForkingProcessingStrategy implements ProcessingStrategy
{
    private const MAX_FORKS = 5;
    private Executor $executor;

    public function __construct(Executor $executor, int $maxForks)
    {
        $this->executor = $executor;
    }

    public function process(JobCollection $jobs): void
    {
        $pidList = [];
        $joblist = [];
        foreach ($jobs->all() as $job) {
            while (count($pidList) === self::MAX_FORKS) {
                foreach ($pidList as $pos => $pId) {
                    $code = pcntl_waitpid($pId, $status, WNOHANG);

                    if ($code === 0) continue;
                    unset($pidList[$pos]);
                    unset($joblist[$pId]);
                    break;

                }
                sleep(1);
            }
            $pid = pcntl_fork();
            if ($pid == -1) {
                die('Konnte nicht verzweigen');
            } else if ($pid) {
                $pidList[] = $pid;
                $joblist[$pid] = $job;
            } else {
                $this->executor->process($job);
                exit;
            }
        }

        while (!empty($pidList)) {
            foreach ($pidList as $pos => $pId) {
                $code = pcntl_waitpid($pId, $status, WNOHANG);

                if ($code === 0) continue;
                unset($pidList[$pos]);
                unset($joblist[$pId]);
            }

            sleep(1);
        }
    }
}