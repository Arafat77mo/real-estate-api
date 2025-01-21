<?php

namespace Modules\Notification\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Notification\app\Services\NotificationService;
use Modules\Notification\App\Helpers\ResponseData;
use Modules\Notification\App\Transformers\NotificationResource;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get unread notifications for the authenticated user.
     *
     * @return JsonResponse
     */
    public function getUnreadNotifications()
    {
        $notifications = $this->notificationService->getUnreadNotifications();

        return ResponseData::send(
            'success',
            __('notifications.notification_fetch_success'),
            NotificationResource::collection($notifications)
        );
    }

    /**
     * Get all notifications for the authenticated user with pagination.
     *
     * @param int $perPage
     * @return JsonResponse
     */
    public function getNotifications($perPage = 10)
    {
        $notifications = $this->notificationService->getNotifications($perPage);

        return ResponseData::send(
            'success',
            __('notifications.notification_fetch_success'),
            NotificationResource::collection($notifications)
        );
    }

    /**
     * Mark a notification as read.
     *
     * @param int $notificationId
     * @return JsonResponse
     */
    public function markAsRead($notificationId)
    {
        $this->notificationService->markAsRead($notificationId);

        return ResponseData::send(
            'success',
            __('notifications.notification_marked_as_read')
        );
    }

    /**
     * Mark all notifications as read for the authenticated user.
     *
     * @return JsonResponse
     */
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead();

        return ResponseData::send(
            'success',
            __('notifications.notification_all_marked_as_read')
        );
    }

    /**
     * Delete a notification.
     *
     * @param int $notificationId
     * @return JsonResponse
     */
    public function deleteNotification($notificationId)
    {
        $this->notificationService->deleteNotification($notificationId);

        return ResponseData::send(
            'success',
            __('notifications.notification_deleted')
        );
    }

    /**
     * Delete all notifications for the authenticated user.
     *
     * @return JsonResponse
     */
    public function deleteAllNotifications()
    {
        $this->notificationService->deleteAllNotifications();

        return ResponseData::send(
            'success',
            __('notifications.notification_all_deleted')
        );
    }
}
