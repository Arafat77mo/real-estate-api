<?php

namespace Modules\Notification\app\Notifications;

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


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => 'property_' . $this->action,
            'message' => "A property with ID {$this->property->id} has been {$this->action}.",
        ]);
    }
}
