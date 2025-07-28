<?php

namespace App\Helpers;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiResponse
{
    public static function success($data = null, $message = 'Success', $status = 200): JsonResponse
    {
        // Handle paginated resource collections
        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof Paginator) {
            $paginator = $data->resource;

            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ], $status);
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error($message = 'Error', $status = 400, $data = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function logIn($data = null, string $accessToken = null, $message = 'Login successful', $status = 200):JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'access_token' => $accessToken,
        ], $status);
    }
}
