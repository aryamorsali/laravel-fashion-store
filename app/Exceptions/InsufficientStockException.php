<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InsufficientStockException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => [
                'quantity' => $this->getMessage() ?: 'Sorry, there isn’t enough stock for this item.'
            ]
        ], 422);
    }
}
