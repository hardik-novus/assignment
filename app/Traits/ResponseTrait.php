<?php

namespace App\Traits;

trait ResponseTrait
{
    protected function success($data, string $message = null, int $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json(array_filter([
            'data' => $data,
            'message' => $message,
        ]), $code);
    }

    protected function error(string $message, int $status = 422, $data = null): \Illuminate\Http\JsonResponse
    {
        return response()->json(array_filter([
            'message' => $message,
            'data' => $data
        ]), $status);
    }
}
