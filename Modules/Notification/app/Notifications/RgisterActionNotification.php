<?php

namespace Modules\Notification\app\Notifications;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class RgisterActionNotification extends Notification
{
    protected $user;
    protected $action;

    // نمرر المستخدم و الفعل الذي قام به (مثل التسجيل)
    public function __construct(User $user, $action)
    {
        $this->user = $user;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['broadcast']; // نستخدم broadcast لإرسال الإشعار مباشرة
    }

    // القناة التي سيتم البث من خلالها
    public function broadcastOn()
    {
        // تحديد القناة الخاصة بالإدمن
        return new Channel('admin.notifications');  // قناة مخصصة للإدمن
    }

    public function broadcastAs()
    {
        return 'user-registered';  // اسم الحدث
    }

    // تحديد الرسالة التي سيتم بثها
    public function toBroadcast($notifiable)
    {
        $userType = $this->user->type;  // اسم المستخدم الذي قام بالتسجيل
        $userName = $this->user->name;  // اسم المستخدم الذي قام بالتسجيل
        $action = $this->action;        // الفعل الذي تم (مثل "التسجيل")

        return new BroadcastMessage([
            'typee' => $userType,
            'message' => "تم تسجيل مستخدم جديد: {$userName} بعملية {$action}.",  // الرسالة المرسلة
            'user_id' => $this->user->id,  // إضافة تفاصيل المستخدم
        ]);
    }
}
