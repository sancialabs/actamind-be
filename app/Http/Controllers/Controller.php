<?php

namespace App\Http\Controllers;

use App\Support\ApiResponder;
use Illuminate\Http\JsonResponse;
use Throwable;

abstract class Controller
{
    protected function success(
        mixed $data = null,
        string $message = 'Request was successful.',
        int $status = 200
    ): JsonResponse {
        return ApiResponder::success($data, $message, $status);
    }

    protected function error(
        ?Throwable $exception = null,
        ?string $message = null,
        int $status = 500
    ): JsonResponse {
        return ApiResponder::error($exception, $message, $status);
    }
}
