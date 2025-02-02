<?php

namespace Modules\Transactions\app\Notifications;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionForAgentNotification extends Notification
{
    protected $transactionType;
    protected $userName;
    protected $agent ;

    public function __construct($userName, $transactionType,$agent)
    {
        $this->agent = $agent;
        $this->userName = $userName;
        $this->transactionType = $transactionType;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ 'broadcast']; // يمكن إضافة قنوات أخرى إذا أردت (مثل البريد الإلكتروني أو الرسائل النصية)
    }

    /**
     * Get the database notification representation.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id'=>$this->agent->id,
            'message' => 'تم ' . $this->transactionType . ' من قبل ' . $this->userName,
            'type' => $this->transactionType,
        ];
    }
    public function broadcastOn()
    {
        return new Channel('agent');
    }


    public function broadcastAs()
    {
        return 'createe';
    }
    /**
     * Get the real-time broadcast notification representation.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'تم ' . $this->transactionType . ' من قبل ' . $this->userName,
            'transaction_type' => $this->transactionType,
        ]);
    }
}
