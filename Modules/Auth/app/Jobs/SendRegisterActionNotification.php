<?php

namespace Modules\Auth\app\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Modules\Notification\app\Notifications\RgisterActionNotification;

class SendRegisterActionNotification implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $user;
    protected $action;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param string $action
     */
    public function __construct(User $user, string $action)
    {
        $this->user = $user;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send the notification to all admins
        $admins = User::role('admin')->get();

        // Send the notification using the broadcast
        Notification::send($admins, new RgisterActionNotification($this->user, $this->action));
    }
}
