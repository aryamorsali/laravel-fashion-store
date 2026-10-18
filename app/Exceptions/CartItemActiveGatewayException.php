<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartItemActiveGatewayException extends Exception
{
    public function __construct(
        string $message,
        protected int $previousValue,
        protected int $statusCode = 409
    ) {
        parent::__construct($message);
    }

    public function getPreviousValue(): int
    {
        return $this->previousValue;
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
            'previous_value' => $this->getPreviousValue(),
            'status_code' => 409
        ], $this->getStatusCode());
    }
}
