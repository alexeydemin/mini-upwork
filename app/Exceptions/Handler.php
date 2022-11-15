<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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

    public function render($request, $e)
    {
        if($e instanceof RateLimitException){
            return response()->json([
                'status' => 'ERROR',
                'message' => "You can post new vacancy in {$e->getMessage()}"
            ], 429);
        }

        if($e instanceof NotEnoughCoinsException){
            return response()->json([
                'status' => 'ERROR',
                'message' => 'You do not have enough coins for this operation'
            ], 400);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'The given entity does not exist'
            ], 404);
        }
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'You are not authenticated'
            ], 401);
        }

        if ($e instanceof AuthorizationException) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'You are not allowed to do this action'
            ], 403);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'The specified method for the request is invalid'
            ], 405);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json(['status' => 'ERROR', 'message' => 'The specified URL cannot be found'], 404);
        }

        if ($e instanceof ValidationException) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 422);
        }

        if ($e instanceof HttpException) {
            return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()],
                $e->getStatusCode());
        }

        if ($e instanceof \TypeError) {
            return response()->json(['status' => 'ERROR', 'message' => 'Passed an argument of a wrong type'], 400);
        }

        return response()->json(['status' => 'ERROR', 'message' => 'Internal error. Try again later'], 500);
    }
}
