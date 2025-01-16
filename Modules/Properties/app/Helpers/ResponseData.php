<?php

namespace Modules\Properties\app\Helpers;


use Illuminate\Http\JsonResponse;

class ResponseData
{
    /**
     * Format the response data for API.
     *
     * @param string $status
     * @param string $message
     * @param mixed $data
     * @return JsonResponse
     */
    public static function send($status, $message, $data = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
