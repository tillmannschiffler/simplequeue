<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Event\LogEmmitter;
use simpleQueue\Job\JobCollection;
use simpleQueue\Job\ProcessingStrategy;

class ForkingProcessingStrategy implements ProcessingStrategy
{
    private Executor $executor;
    private int $maxForks;
    private LogEmmitter $logEmmitter;

    public function __construct(Executor $executor, int $maxForks, LogEmmitter $logEmmitter)
    {
        $this->executor = $executor;
        $this->maxForks = $maxForks;
        $this->logEmmitter = $logEmmitter;
    }

    public function process(JobCollection $jobs): void
    {
        $pidList = [];
        $joblist = [];
        foreach ($jobs->all() as $job) {
            while (count($pidList) === $this->maxForks) {
                foreach ($pidList as $pos => $pId) {
                    $code = pcntl_waitpid($pId, $status, WNOHANG);

                    if ($code === 0) continue;
                    unset($pidList[$pos]);
                    unset($joblist[$pId]);
                    break;

                }
                $this->logEmmitter->emmitWaitingForSlot();
                sleep(1);
            }
            $pid = pcntl_fork();
            if ($pid == -1) {
                $this->logEmmitter->emmitCouldNotFork();
                die('Could not fork');
            } else if ($pid) {
                $pidList[] = $pid;
                $joblist[$pid] = $job;
            } else {
                $this->logEmmitter->emmitStartedJob($job);
                $this->executor->process($job);
                $this->logEmmitter->emmitFinishedJob($job);
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

    public function getLogEmmitter(): LogEmmitter
    {
        return $this->logEmmitter;
    }
}