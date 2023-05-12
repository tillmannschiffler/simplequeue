<?php

declare(strict_types=1);

namespace simpleQueue\Infrastructure;

use simpleQueue\Event\LogEmitter;
use simpleQueue\Job\Job;
use simpleQueue\Job\ProcessingStrategy;
use Traversable;

class ForkingProcessingStrategy implements ProcessingStrategy
{
    private Executor $executor;

    private int $maxForks;

    private LogEmitter $logEmitter;

    public function __construct(Executor $executor, int $maxForks, LogEmitter $logEmitter)
    {
        $this->executor = $executor;
        $this->maxForks = $maxForks;
        $this->logEmitter = $logEmitter;
    }

    /**
     * @param  Traversable<Job>  $jobs
     */
    public function process(Traversable $jobs): void
    {
        $pidList = [];
        $joblist = [];
        foreach ($jobs as $job) {
            while (count($pidList) === $this->maxForks) {
                foreach ($pidList as $pos => $pId) {
                    $code = pcntl_waitpid($pId, $status, WNOHANG);

                    if ($code === 0) {
                        continue;
                    }
                    unset($pidList[$pos]);
                    unset($joblist[$pId]);
                    break;
                }
                $this->logEmitter->emitWaitingForSlot();
                sleep(1);
            }
            $pid = pcntl_fork();
            if ($pid == -1) {
                $this->logEmitter->emitCouldNotFork();
                exit('Could not fork');
            } elseif ($pid) {
                $pidList[] = $pid;
                $joblist[$pid] = $job;
            } else {
                $this->logEmitter->emitStartedJob($job);
                $this->executor->process($job);
                $this->logEmitter->emitFinishedJob($job);
                exit;
            }
        }

        while (! empty($pidList)) {
            foreach ($pidList as $pos => $pId) {
                $code = pcntl_waitpid($pId, $status, WNOHANG);

                if ($code === 0) {
                    continue;
                }
                unset($pidList[$pos]);
                unset($joblist[$pId]);
            }
            sleep(1);
        }
    }

    public function getLogEmitter(): LogEmitter
    {
        return $this->logEmitter;
    }
}
