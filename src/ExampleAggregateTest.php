<?php

namespace EventSauce\ExampleProject;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;

/**
 * @method ExampleId aggregateRootId()
 */
class ExampleAggregateTest extends AggregateRootTestCase
{
    protected function newAggregateRootId(): AggregateRootId
    {
        return ExampleId::fromString('abcde');
    }

    protected function aggregateRootClassName(): string
    {
        return ExampleAggregate::class;
    }

    /**
     * @test
     */
    public function emitting_some_event(): void
    {
        $this->when(new ExampleCommand($this->aggregateRootId(), 'random-value'))
            ->then(
                new ExampleEvent('random-value'),
            );
    }

    public function handle(ExampleCommand $command): void
    {
        (new ExampleService($this->repository))->handle($command);
    }
}
