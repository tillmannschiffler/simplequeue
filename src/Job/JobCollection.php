<?php

declare(strict_types=1);

namespace simpleQueue\Job;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @template-implements IteratorAggregate<Job>
 */
class JobCollection implements IteratorAggregate
{
    private array $items = [];

    public function add(Job $job): void
    {
        $this->items[] = $job;
    }

    /**
     * @return Job[]
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return Traversable<Job>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
