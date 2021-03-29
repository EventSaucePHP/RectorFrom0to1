<?php

namespace EventSauce\ExampleProject;

use EventSauce\EventSourcing\AggregateRootRepository;

class ExampleService
{
    /**
     * @var AggregateRootRepository
     */
    private $repository;

    public function __construct(AggregateRootRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ExampleCommand $command)
    {
        /** @var ExampleAggregate $aggregate */
        $aggregate = $this->repository->retrieve($command->id());

        try {
            $aggregate->performAction($command->value());
        } finally {
            $this->repository->persist($aggregate);
        }
    }
}
