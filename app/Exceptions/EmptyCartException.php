<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmptyCartException extends Exception
{
    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Your shopping cart is empty.',
            ], 422);
        }

        return redirect()->back()->with('toast-error', 'Your shopping cart is empty.');
    }
}
