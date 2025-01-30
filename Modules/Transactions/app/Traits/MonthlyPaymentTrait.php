<?php

namespace Modules\Transactions\app\Traits;

use Modules\Transactions\App\Models\MonthlyPayment;
use Modules\Transactions\App\Models\PropertyTransaction;

trait MonthlyPaymentTrait
{
    public function createMonthlyPayments(PropertyTransaction $transaction, $durationMonths)
    {
        for ($i = 1; $i <= $durationMonths; $i++) {
            MonthlyPayment::create([
                'property_transaction_id' => $transaction->id,
                'amount' => $transaction->price,
                'due_date' => now()->addMonths($i),
                'payment_status' => 'pending',
            ]);
        }
    }
}
