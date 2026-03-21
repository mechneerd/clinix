<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notifications",
     *     summary="Get all notifications for current user",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="unread_only",
     *         in="query",
     *         description="Show only unread notifications",
     *         @OA\Schema(type="boolean", default=false)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notifications retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Notification::where('user_id', $user->id);

        if ($request->boolean('unread_only')) {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($notifications, 'Notifications retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/notifications/unread-count",
     *     summary="Get unread notification count",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Unread count retrieved successfully"
     *     )
     * )
     */
    public function unreadCount(): JsonResponse
    {
        $user = auth()->user();
        
        $count = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return $this->successResponse(['count' => $count], 'Unread count retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/notifications/{id}/read",
     *     summary="Mark a notification as read",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification marked as read"
     *     )
     * )
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        $user = auth()->user();
        
        if ($notification->user_id !== $user->id) {
            return $this->forbiddenResponse('You can only mark your own notifications as read');
        }

        $notification->update(['read_at' => now()]);

        return $this->successResponse($notification, 'Notification marked as read');
    }

    /**
     * @OA\Post(
     *     path="/api/notifications/mark-all-read",
     *     summary="Mark all notifications as read",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="All notifications marked as read"
     *     )
     * )
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = auth()->user();
        
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $this->successResponse(null, 'All notifications marked as read');
    }

    /**
     * @OA\Delete(
     *     path="/api/notifications/{id}",
     *     summary="Delete a notification",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification deleted successfully"
     *     )
     * )
     */
    public function destroy(Notification $notification): JsonResponse
    {
        $user = auth()->user();
        
        if ($notification->user_id !== $user->id) {
            return $this->forbiddenResponse('You can only delete your own notifications');
        }

        $notification->delete();

        return $this->successResponse(null, 'Notification deleted successfully');
    }
}
