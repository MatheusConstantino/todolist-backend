<?php

namespace App\Exceptions;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\UserAlreadyExistsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    private function handleApiException(Request $request, Throwable $exception)
    {
        // Custom exceptions
        if ($exception instanceof InvalidCredentialsException || 
            $exception instanceof UserAlreadyExistsException) {
            return response()->json([
                'message' => $exception->getMessage()
            ], $exception->getCode());
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors()
            ], 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Resource not found'
            ], 404);
        }

        if (app()->environment('production')) {
            return response()->json([
                'message' => 'Server error'
            ], 500);
        }

        return response()->json([
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ], 500);
    }
}