<?php

namespace Modules\Notification\app\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Modules\Properties\App\Models\Property;

class PropertyActionNotification extends Notification
{
    protected $property;
    protected $action;

    public function __construct(Property $property, $action)
    {
        $this->property = $property;
        $this->action = $action;


    }

    public function via($notifiable)
    {
        return ['broadcast']; // Add 'broadcast' to trigger real-time notifications
    }


    public function broadcastOn()
    {
        return new Channel('property');
    }


    public function broadcastAs()
    {
        return 'create';
    }


    public function toBroadcast($notifiable)
    {
        $userName = $this->property->owner->name; // اسم المستخدم
        $propertyId = $this->property->id; // رقم العقار
        $action = $this->action; // العملية (مثل "مضاف" أو "تم تعديله")

        return new BroadcastMessage([
            'type' => 'property_' . $this->action,
            'message' => "تم {$action} عقار برقم {$propertyId} بواسطة المستخدم {$userName}.",
        ]);
    }
}
