<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    protected function successResponse(string $message = '', $data = [], int $code = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse(string $message = '', int $code = 400): JsonResponse
    {
        return response()->json(['message' => $message], $code);
    }
}
