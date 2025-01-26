<?php

namespace Modules\Notification\app\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Modules\Notification\app\Models\Notification;
use Modules\Properties\App\Models\Property;

class SaveNotificationJob implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $property;
    protected $action;

    public function __construct(User $user, Property $property, $action)
    {
        $this->user = $user;
        $this->property = $property;
        $this->action = $action;
    }

    public function handle()
    {
        $userName = $this->user->name; // اسم المستخدم
        $propertyId = $this->property->id; // رقم العقار
        $action = $this->action; // العملية (مثل "مضاف" أو "تم تعديله")

        Notification::create([
            'user_id' => $this->user->id,
            'type' => 'property_' . $action,
            'message' => "تم {$action} عقار برقم {$propertyId} بواسطة المستخدم {$userName}.",
        ]);
    }

}
