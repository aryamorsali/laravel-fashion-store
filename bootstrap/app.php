<?php

use App\Exceptions\CartException;
use App\Http\Middleware\EnsureUserIsOwner;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(
            fn(Request $request) => route('auth.login-register.form')
        );

        $middleware->alias([
            'owner' => EnsureUserIsOwner::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (
            DomainException $exception,
            Request $request
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 409);
        });

        $exceptions->render(function (
            AuthenticationException $exception,
            Request $request
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401);
        });

        $exceptions->render(function (
            AuthorizationException $exception,
            Request $request
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return response()->json([
                'status' => 'error',
                'message' => 'This action is unauthorized.',
            ], 403);
        });


        $exceptions->render(function (
            ValidationException $exception,
            Request $request
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            $errors = $exception->errors();

            $message = collect($errors)
                ->flatten()
                ->first() ?? 'The given data was invalid.';

            return response()->json([
                'status' => 'error',
                'message' => $message,
                'errors' => $errors,
            ], 422);
        });

        $exceptions->render(function (NotFoundHttpException  $e, Request $request) {
            if (! $request->expectsJson()) {
                return null;
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Requested cart item does not exist or does not belong to you.',
            ], 404);
        });
    })->create();
