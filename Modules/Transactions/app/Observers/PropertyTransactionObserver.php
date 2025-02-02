<?php

namespace Modules\Transactions\app\Observers;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Modules\Transactions\App\Models\PropertyTransaction;
use Modules\Transactions\app\Notifications\TransactionForAgentNotification;
use Modules\Transactions\app\Notifications\TransactionNotification;

class PropertyTransactionObserver
{
    /**
     * Handle the PropertyTransaction "created" event.
     */
    public function created(PropertyTransaction $propertyTransaction): void
    {
        // Send notification based on transaction type (buy, rent, installment)
        $user = $propertyTransaction->user;

        $transactionType = $propertyTransaction->transaction_type;
        $message = '';

        switch ($transactionType) {
            case 'sale':
                $message = 'You have successfully purchased the property!';
                break;

            case 'rent':
                $message = 'You have successfully rented the property!';
                break;

            case 'installment':
                $message = 'You have successfully opted for an installment plan!';
                break;

            default:
                $message = 'Transaction completed successfully.';
        }

        \Modules\Notification\app\Models\Notification::create([
            'user_id' => auth()->id(),
            'message' => $message,
            'type' => $transactionType,
        ]);
        // Send the notification
        $user->notify(new TransactionNotification($message, $transactionType));
        $property = $propertyTransaction->property;




    }

    /**
     * Handle the PropertyTransaction "updated" event.
     */
    public function updated(PropertyTransaction $propertyTransaction): void
    {
        $user = $propertyTransaction->user;

        $transactionType = $propertyTransaction->transaction_type;
        $message = '';

        switch ($transactionType) {
            case 'sale':
                $message = 'You have successfully purchased the property!';
                break;

            case 'rent':
                $message = 'You have successfully rented the property!';
                break;

            case 'installment':
                $message = 'You have successfully opted for an installment plan!';
                break;

            default:
                $message = 'Transaction completed successfully.';
        }

        \Modules\Notification\app\Models\Notification::create([
            'user_id' => auth()->id(),
            'message' => $message,
            'type' => $transactionType,
        ]);
        // Send the notification
        $user->notify(new TransactionNotification($message, $transactionType));
        $property = $propertyTransaction->property;

        // إذا كان الـ Agent موجودًا، إرسال الإشعار له


    }

    /**
     * Handle the PropertyTransaction "deleted" event.
     */
    public function deleted(PropertyTransaction $propertyTransaction): void
    {
        //
    }

    /**
     * Handle the PropertyTransaction "restored" event.
     */
    public function restored(PropertyTransaction $propertyTransaction): void
    {
        //
    }

    /**
     * Handle the PropertyTransaction "force deleted" event.
     */
    public function forceDeleted(PropertyTransaction $propertyTransaction): void
    {
        //
    }


}
