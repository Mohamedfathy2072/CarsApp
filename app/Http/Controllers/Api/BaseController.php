<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function successResponse($items, $message = "Data fetched successfully.")
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => [
                'items' => $items->items(),
                'pagination' => [
                    'current_page' => $items->currentPage(),
                    'per_page' => $items->perPage(),
                    'total' => $items->total(),
                    'last_page' => $items->lastPage(),
                ]
            ]
        ]);
    }

    protected function singleItemResponse($item, $message = "Item fetched successfully.")
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $item
        ]);
    }
}
