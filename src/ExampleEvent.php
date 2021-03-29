<?php

namespace EventSauce\ExampleProject;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class ExampleEvent implements SerializablePayload
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toPayload(): array
    {
        return ['value' => $this->value];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new ExampleEvent($payload['value']);
    }

    public function value(): string
    {
        return $this->value;
    }
}
