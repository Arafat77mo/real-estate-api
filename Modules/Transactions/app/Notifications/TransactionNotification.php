<?php

namespace Modules\Transactions\app\Notifications;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionNotification extends Notification
{
    protected $message;
    protected $transactionType;

    public function __construct($message, $transactionType)
    {
        $this->message = $message;
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
        return [ 'broadcast']; // Adding broadcast for real-time notifications
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
            'user_id' => auth()->id(),
            'message' => $this->message,
            'type' => $this->transactionType,
        ];


    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $pdf = PDF::loadView('invoices.transaction_invoice', [
            'message' => $this->message,
            'transactionType' => $this->transactionType
        ]);
        return (new MailMessage)
            ->subject('Transaction Invoice')
            ->line($this->message)
            ->line('Thank you for your transaction.')
            ->attachData($pdf->output(), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);    }



    public function broadcastOn()
    {
        return new Channel('tran');
    }


    public function broadcastAs()
    {
        return 'createe';
    }
    /**
     * Send real-time (broadcast) notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'transaction_type' => $this->transactionType,

        ]);
    }
}
