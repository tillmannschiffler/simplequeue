<?php

declare(strict_types=1);

namespace simpleQueue\Job;

class JobCollection
{
    private array $items = [];

    public function add(Job $job)
    {
        $this->items[] = $job;
    }

    /**
     * @return Job[]
     */
    public function all() : array
    {
        return $this->items;
    }
}