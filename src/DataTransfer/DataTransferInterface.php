<?php

namespace App\DataTransfer;

interface DataTransferInterface
{
    /**
     * @param $originalData
     * @return mixed
     */
    public function transfer($originalData): mixed;

    public function reverseTransfer($data, $originalData): mixed;

}