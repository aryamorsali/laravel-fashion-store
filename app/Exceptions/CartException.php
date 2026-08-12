<?php

namespace App\Exceptions;

use Exception;

class CartException extends Exception
{
    public function __construct(
        string $message,
        protected int $available,
        protected int $statusCode = 409
    ) {
        parent::__construct($message);
    }

    public function getAvailable(): int
    {
        return $this->available;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
