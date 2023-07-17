<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\Permission\Exceptions\UnauthorizedException as PermissionUnauthorizedException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Exception $exception, $request) {
            if ($request->is('api/*')) {
                if ($exception instanceof ValidationException) {
                    return response()->json(
                        ['error' =>  $exception->errors()],
                        $exception->status
                    );
                }
                if ($exception instanceof InvalidArgumentException) {
                    return response()->json(
                        ['error' => $exception->getMessage()],
                        500
                    );
                }
                if ($exception instanceof UnauthorizedException) {
                    return response()->json(
                        [
                            'error' =>  'Acesso Bloqueado! Você não tem acesso para fazer isso.',
                        ],
                        $exception->status
                    );
                }

                if ($exception instanceof AuthorizationException) {
                    return response()->json(
                        ['error' => 'Você não tem acesso para fazer isso.'],
                        $exception->status
                    );
                }

                if ($exception instanceof PermissionUnauthorizedException) {
                    return response()->json(
                        ['error' =>  'Você não tem permissão para essa ação'],
                        403
                    );
                }
            }
        });
    }
}
