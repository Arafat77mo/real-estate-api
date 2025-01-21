<?php

namespace Modules\Properties\app\Observers;

use Modules\Notification\app\Jobs\SaveNotificationJob;
use Modules\Notification\app\Notifications\PropertyActionNotification;
use Modules\Properties\App\Models\Property;

class PropertyObserver
{
    public function created(Property $property)
    {
        $user = auth()->user(); // Get the authenticated user
        $user->notify(new PropertyActionNotification($property, 'انشاء'));

        // Queue the notification to be saved to the database
        SaveNotificationJob::dispatch($user, $property, 'انشاء');
    }

    public function updated(Property $property)
    {
        $user = auth()->user();
        $user->notify(new PropertyActionNotification($property, 'تعديل'));

        SaveNotificationJob::dispatch($user, $property, 'تعديل');
    }

    public function deleted(Property $property)
    {
        $user = auth()->user();
        $user->notify(new PropertyActionNotification($property, 'حذف'));


        SaveNotificationJob::dispatch($user, $property, 'حذف');
    }
}
