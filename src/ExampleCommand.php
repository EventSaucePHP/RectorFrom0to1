<?php

namespace EventSauce\ExampleProject;

class ExampleCommand
{
    /**
     * @var ExampleId
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    public function __construct(ExampleId $id, string $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    public function id(): ExampleId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}
