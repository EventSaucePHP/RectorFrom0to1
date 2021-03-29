<?php

namespace EventSauce\ExampleProject;

use DateTimeImmutable;
use EventSauce\EventSourcing\Consumer;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Time\Clock;
use EventSauce\EventSourcing\Time\SystemClock;
use EventSauce\EventSourcing\Time\TestClock;

use function var_dump;

class ExampleConsumer implements Consumer
{
    /**
     * @var Clock
     */
    private $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
        $clock->dateTime();
    }

    public function systemClockTime(SystemClock $clock): DateTimeImmutable
    {
        return $clock->dateTime();
    }

    public function testClockTime(TestClock $clock): DateTimeImmutable
    {
        return $clock->dateTime();
    }

    public function handle(Message $message)
    {
        var_dump($message->event());
    }
}
