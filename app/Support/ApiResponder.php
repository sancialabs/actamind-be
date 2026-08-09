<?php

namespace App\Support;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiResponder
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function success(
        mixed $data = null,
        string $message = 'Request was successful.',
        int $status = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'data' => $data,
            'status' => $status,
            'message' => $message,
        ], $status);
    }

    public static function error(
        ?Throwable $exception = null,
        ?string $message = null,
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        $errors = null;

        switch (true) {
            case $exception instanceof ValidationException:
                $status = Response::HTTP_UNPROCESSABLE_ENTITY;
                $message ??= 'The submitted information contains errors. Please review the highlighted fields and try again.';
                $errors = $exception->errors();
                break;

            case $exception instanceof AuthenticationException:
                $status = Response::HTTP_UNAUTHORIZED;
                $message ??= 'You are not authenticated.';
                break;

            case $exception instanceof AuthorizationException:
                $status = Response::HTTP_FORBIDDEN;
                $message ??= 'You do not have permission to perform this action.';
                break;

            case $exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException:
                $status = Response::HTTP_NOT_FOUND;
                $message ??= 'The requested resource could not be found.';
                break;

            case $exception instanceof QueryException:
                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
                $message ??= 'An unexpected database error occurred. Please try again later.';
                break;

            case $exception instanceof HttpException:
                $status = $exception->getStatusCode();
                $message ??= 'The request could not be completed.';
                break;

            default:
                if ($exception) {
                    $status = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $message ??= $exception->getMessage() ?: 'An unexpected error occurred. Please try again later.';
                }
        }

        if ($status === Response::HTTP_INTERNAL_SERVER_ERROR && $exception) {
            Log::error($exception->getMessage(), [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
        }

        $payload = [
            'data' => null,
            'status' => $status,
            'message' => $message ?? 'Something went wrong.',
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        if ($exception && config('app.debug') && ! App::environment('production')) {
            $payload['debug'] = [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => collect($exception->getTrace())->take(10)->toArray(),
            ];
        }

        return response()->json($payload, $status);
    }
}
