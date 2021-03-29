<?php

namespace EventSauce\ExampleProject;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class ExampleAggregate implements AggregateRoot
{
    use AggregateRootBehaviour;

    public function performAction(string $value): void
    {
        $this->recordThat(new ExampleEvent($value));
    }

    protected function applyExampleEvent(ExampleEvent $event)
    {
        // ignore
    }
}
