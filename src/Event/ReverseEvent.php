<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ReverseEvent extends Event
{
    public const NAME = 'app.event.reverse';

    private object $data;

    private object $originalData;

    public function __construct(object $data, object $originalData)
    {
        $this->data = $data;
        $this->originalData = $originalData;
    }

    public function getOriginalData(): object
    {
        return $this->originalData;
    }

    public function getData(): object
    {
        return $this->data;
    }
}
