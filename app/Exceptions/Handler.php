<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException && $request->expectsJson() && $request->is('api/*')) {
            return response()->json([
                'message' => 'The resource was not found.',
                'status' => false,
            ], 404);
        }

        if ($exception instanceof NotFoundHttpException && $request->expectsJson() && $request->is('api/*')) {
            return response()->json([
                'message' => $exception->getMessage() ?: "The specified URL can't be found",
                'status' => false,
            ], 404);
        }

        if ($exception instanceof ValidationException && $request->expectsJson() && $request->is('api/*')) {
            $error = ['message' => $exception->getMessage(), 'errors' => $exception->errors(), 'status' => false];
            return response()->json($error, 422);
        }

        if ($exception instanceof AuthenticationException && $request->expectsJson() && $request->is('api/*')) {
            $error = ['message' => $exception->getMessage(), 'status' => false];
            return response()->json($error, 401);
        }

        if ($exception instanceof AuthorizationException  && $request->expectsJson() && $request->is('api/*')) {
            return response()->json([
                "message" => $exception->getMessage() ?: "You are not authorized to access this resource",
                'status' => false,
            ], 403);
        }

        if ($exception instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {
            // dd($exception);
            $error = ['message' => 'File too large', 'status' => false];
            return response()->json($error, $exception->getStatusCode());
        }

        if ($exception instanceof HttpException && $request->expectsJson() && $request->is('api/*')) {
            $error = ['message' => $exception->getMessage(), 'status' => false];
            return response()->json($error, $exception->getStatusCode());
        }

        if ($exception instanceof MethodNotAllowedException) {
            return response()->json([
                'message' => $exception->getMessage() ?: "The specified method for this request is invalid",
                'status' => false,
            ], 405);
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }
        if($request->expectsJson()) {
            return response()->json([
                "message" => 'Something went wrong, please try again'
            ], 500);
        }

    }
}
