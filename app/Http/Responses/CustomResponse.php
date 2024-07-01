<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class CustomResponse
{
    /**
     * Send a JSON response with success message.
     *
     * @param string|null $message
     * @param array $data
     * @param int $status
     * @return JsonResponse
     */
    public static function success($message = null, $data = [], $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $status);
    }

    /**
     * Send a JSON response with error message.
     *
     * @param string|null $message
     * @param int $statusCode
     * @param array $errors
     * @return JsonResponse
     */
    public static function error($message = null, $statusCode = 400, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
            'error' => $message, // Not needed. We are just trying make sure nothing breaks
            'errors' => $errors,
            'data' => $errors, // Not needed. We are just trying make sure nothing breaks
        ];

        return response()->json($response, $statusCode);
    }
}
