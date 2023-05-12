<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        $this->renderable(function (\Exception $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof NotFoundHttpException) {
                    // Handle NotFoundHttpException
                    return response()->json(['error' => 'Invalid route.'], Response::HTTP_NOT_FOUND);
                }
                if ($e instanceof ModelNotFoundException) {
                    // Handle ModelNotFoundException
                    return response()->json(['error' => 'Resource Not Found.'], Response::HTTP_NOT_FOUND);
                }
                throw $e;
            }
        });
    }
}
