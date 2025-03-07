<?php

use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\SetLocale;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        return [
            'api' => [
                EnsureFrontendRequestsAreStateful::class, // Required for SPAs
                'throttle:api',
                SubstituteBindings::class,
                \Illuminate\Http\Middleware\HandleCors::class,
                \App\Http\Middleware\CorsMiddleware::class,


            ],
            $middleware->alias([
                'role' => RoleMiddleware::class,
                'permission' => PermissionMiddleware::class,
                'role_or_permission' => RoleOrPermissionMiddleware::class,
                'setLocale' => \App\Http\Middleware\SetLocale::class,


            ]),



        ];
    })
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
