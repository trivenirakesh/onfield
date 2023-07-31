<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use ErrorException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $th)
    {
        if (request()->expectsJson()) {
            Log::error($th->getMessage());
            if ($th instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Access denied. Please login and try again.",
                ], 401);
            } elseif ($th instanceof ErrorException) {
                return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
                ], 404);
            } elseif ($th instanceof ValidationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Errors found",
                    'errors' => $th->errors(),
                ], 404);
            } elseif ($th instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Route'.__('messages.validation.not_found'),
                ], 404);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage(),
                ], 500);
            }
        }
        return parent::render($request, $th);
    }
}
