<?php

namespace Modules\Auth\app\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Modules\Auth\app\Jobs\SendRegisterActionNotification;
use Modules\Notification\app\Notifications\RgisterActionNotification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (auth()->user() && auth()->user()->hasRole('admin')) {
            // Get all admins
            $admins = User::role('admin')->get();

            // Send notification to admins about the new registration
            SendRegisterActionNotification::dispatch($user, 'تسجيل');
        }

    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
