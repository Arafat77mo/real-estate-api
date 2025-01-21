<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        return [
            'api' => [
                \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Required for SPAs
                'throttle:api',
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ],
        ];   })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('errors.model_not_found'),
            ], 404);
        });
        $exceptions->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('errors.method_not_allowed'),
            ], 405);
        });
        $exceptions->renderable(function (UnauthorizedHttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('errors.unauthorized'),
            ], 401);
        });
        $exceptions->renderable(function (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('errors.query_exception'),
            ], 500);
        });


        $exceptions->renderable(function (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('errors.validation_failed'),
                'errors' => $e->errors(),
            ], 400);
        });


    })->create();
