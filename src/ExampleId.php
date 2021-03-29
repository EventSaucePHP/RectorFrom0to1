<?php

namespace EventSauce\ExampleProject;

use EventSauce\EventSourcing\AggregateRootId;

class ExampleId implements AggregateRootId
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static($aggregateRootId);
    }
}
