<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
    }
    public function render($request, Exception|Throwable $exception)
    {
        if ($exception instanceof AuthenticationException){
            return new JsonResponse([],Response::HTTP_UNAUTHORIZED);

        }else if ($exception instanceof ValidationException){
            return new JsonResponse([
                'errors' => $exception->errors(),
            ],Response::HTTP_BAD_REQUEST);

        }else if ($exception instanceof MethodNotAllowedHttpException){
            return new JsonResponse([],Response::HTTP_METHOD_NOT_ALLOWED);

        }else if ($exception instanceof ModelNotFoundException){
            return new JsonResponse([],Response::HTTP_NOT_FOUND);

        }else if ($exception instanceof NotFoundHttpException){
            return new JsonResponse([],Response::HTTP_NOT_FOUND);

        }else{
            if (env('APP_DEBUG') === true){
                return new JsonResponse([
                    'type' => get_class($exception),
                    'error' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return new JsonResponse([
                'error' => trans('errors.internal_server'),
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
