<?php

namespace App\Http\Controllers;

use App\Services\HeaderNotificationService;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function markRead(HeaderNotificationService $notifications): JsonResponse
    {
        $notifications->markRead(auth()->user());

        return response()->json(['count' => 0]);
    }
}
