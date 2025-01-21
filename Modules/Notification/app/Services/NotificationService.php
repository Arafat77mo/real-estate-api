<?php

namespace Modules\Notification\app\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Notification\app\Models\Notification;

class NotificationService
{
    /**
     * Get all unread notifications for the authenticated user.
     *
     * @return Collection
     */
    public function getUnreadNotifications(): Collection
    {
        return Notification::where('is_read', false)->get();
    }

    /**
     * Get all notifications for the authenticated user with pagination.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getNotifications(int $perPage = 10)
    {
        return Notification::orderBy('created_at', 'desc')->fastPaginate($perPage);
    }

    /**
     * Mark a notification as read.
     *
     * @param int $notificationId
     * @return bool
     */
    public function markAsRead(int $notificationId): bool
    {
        $notification = Notification::findOrFail($notificationId);

        if ($notification) {
            $notification->update(['is_read' => true]);
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications for the authenticated user as read.
     *
     * @return int
     */
    public function markAllAsRead(): int
    {
        return Notification::where('is_read', false)->update(['is_read' => true]);
    }

    /**
     * Delete a notification.
     *
     * @param int $notificationId
     * @return bool
     */
    public function deleteNotification(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);

        if ($notification) {
            return $notification->delete();
        }

        return false;
    }

    /**
     * Delete all notifications for the authenticated user.
     *
     * @return int
     */
    public function deleteAllNotifications(): int
    {
        return Notification::delete();
    }
}
