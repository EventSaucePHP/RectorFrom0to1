<?php

use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\ConstructingAggregateRootRepository;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use EventSauce\ExampleProject\ExampleAggregate;
use EventSauce\ExampleProject\ExampleConsumer;
use EventSauce\ExampleProject\ExampleId;
use EventSauce\ExampleProject\JsonMessageRepository;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

include __DIR__ . '/vendor/autoload.php';

/** @var AggregateRootRepository<ExampleAggregate> $repository */
$repository = new ConstructingAggregateRootRepository(
    ExampleAggregate::class,
    new JsonMessageRepository(new Filesystem(new LocalFilesystemAdapter(__DIR__.'/messages/'))),
    new SynchronousMessageDispatcher(
        new ExampleConsumer(create_clock())
    )
);

/** @var ExampleAggregate $exampleAggregate */
$exampleAggregate = $repository->retrieve(new ExampleId('this-is-the-id'));

$exampleAggregate->performAction(bin2hex(random_bytes(10)));
//
$repository->persist($exampleAggregate);
