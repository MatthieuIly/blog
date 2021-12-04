<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class TransferEvent extends Event
{
    const NAME = 'app.event.transfer';

    private object $originalData;

    private object $data;

    /**
     * @param object $originalData
     * @param object $data
     */
    public function __construct(object $originalData, object $data)
    {
        $this->originalData = $originalData;
        $this->data = $data;
    }

    public function getOriginalData(): object
    {
        return $this->originalData;
    }

    /**
     * @return object
     */
    public function getData(): object
    {
        return $this->data;
    }

}
