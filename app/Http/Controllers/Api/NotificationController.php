<?php

namespace App\Http\Controllers\Api;

use App\Const\NotificationTypes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function getForUser(): JsonResponse
    {
        return ApiResponse::success(
            NotificationResource::collection(
                Notification::where('user_id', auth()->id())
                    ->get()
            ),
            'Notifications retrieved successfully'
        );
    }
}
