<?php

namespace App\Patterns\Iterator;

use Illuminate\Support\Collection;
use Iterator;

class SubmissionQueueIterator implements Iterator
{
    /**
     * @var Collection
     */
    private Collection $submissions;
    
    /**
     * @var int
     */
    private int $position = 0;

    public function __construct(Collection $submissions)
    {
        // Re-index collection so that keys are strictly 0, 1, 2...
        $this->submissions = $submissions->values();
        $this->position = 0;
    }

    public function current(): mixed
    {
        return $this->submissions[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->submissions[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
