<?php

use App\Http\Middleware\AuthBasicMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'basicAuth' => AuthBasicMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->render(function (Exception $e, Request $request) {
            Log::error($e->getMessage());

            if ($request->is('api/*')) {

                if ($e instanceof ValidationException) {
                    $messages = collect($e->errors())->flatten()->values();
                    return response()->json([
                        'message' => $messages->join(' | '),
                        'errors' => $e->errors()
                    ], 422);
                }

                return response()->json(
                    $e->getMessage(),
                    500
                );
            }
        });
    })->create();
