<?php

namespace Modules\Transactions\app\Traits;

use Modules\Transactions\App\Models\Installment;
use Modules\Transactions\App\Models\PropertyTransaction;

trait InstallmentTrait
{


    public function createInstallments(PropertyTransaction $transaction, $durationMonths)
    {
        for ($i = 1; $i <= $durationMonths; $i++) {
            Installment::create([
                'property_transaction_id' => $transaction->id,
                'amount' => $transaction->price / $durationMonths,
                'is_paid' => false,
                'due_date' => now()->addMonths($i),
            ]);
        }
    }
}
