<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class Response
{
    /**
     * Success
     * 
     * @param string $message
     * @param mixed $data
     * @param int $code
     *
     * @return Response
     */
    public static function success(string $message = '', mixed $data = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'message' => empty($message) ? 'Request successful' : $message,
            'data' => $data
        ], $code);
    }

    /**
     * Error
     * 
     * @param string $message
     * @param mixed $errors
     * @param int $code
     *
     * @return Response
     */
    public static function error(string $message = '', mixed $errors = [], int $code = 400): JsonResponse
    {
        return response()->json([
            'message' => empty($message) ? 'Request failed' : $message,
            'errors' => $errors
        ], $code);
    }
}