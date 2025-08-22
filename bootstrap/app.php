<?php

use App\Http\Middleware\AuthBasicMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            if (!$e instanceof AuthenticationException) {
                Log::error($e->getMessage(), $e->getTrace());
            }

            if ($request->is('api/*')) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                if ($e instanceof ValidationException) {
                    $messages = collect($e->errors())->flatten()->values();
                    return response()->json([
                        'message' => $messages->join(' | '),
                        'errors' => $e->errors()
                    ], 422);
                }

                if ($e instanceof NotFoundHttpException) {
                    if ($e->getPrevious() instanceof ModelNotFoundException) {
                        $modelException = $e->getPrevious();
                        $model = class_basename($modelException->getModel());
                        $ids = implode(', ', $modelException->getIds() ?? []);
                        return response()->json([
                            'message' => __('exceptions.model_not_found', ['model' => $model, 'ids' => $ids])
                        ], 404);
                    }
                    return response()->json([
                        'message' => __('exceptions.route_not_found', ['route' => $request->path()])
                    ], $status);
                }

                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'message' => $e->getMessage()
                    ], 403);
                }

                return response()->json([
                    'message' => __($e->getMessage())
                ], $status);
            }
        });
    })
    ->create();
