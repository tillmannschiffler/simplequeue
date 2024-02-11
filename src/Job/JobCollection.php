<?php

declare(strict_types=1);

namespace simpleQueue\Job;

use ArrayIterator;

class JobCollection implements JobIterator
{
    private array $items = [];

    private int $position = 0;

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

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
