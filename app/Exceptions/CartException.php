<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'status'    => 'error',
            'message'   => $this->message,
            'available' => $this->getAvailable(),
        ], $this->getStatusCode());
    }
}
