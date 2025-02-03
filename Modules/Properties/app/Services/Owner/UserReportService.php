<?php
namespace Modules\Properties\app\Services\Owner;
use Illuminate\Support\Facades\Auth;
use Modules\Properties\app\Traits\Payments;
use Modules\Transactions\App\Models\MonthlyPayment;

use Modules\Properties\app\Transformers\Owner\InstallmentResource;
use Modules\Properties\app\Transformers\Owner\PaymentResource;
use Modules\Properties\app\Transformers\Owner\RenterResource;
use Modules\Transactions\App\Models\PropertyTransaction;

class UserReportService
{
    use Payments;
    protected  $owner;
    public function __construct(
        protected PropertyTransaction $propertyTransaction,
    ) {

        $this->owner=\auth()->user();
    }
    public function getBuyers()
    {
        $transactions = $this->propertyTransaction::with(['user', 'property'])
            ->whereHas('property', fn($q) => $q->forOwner($this->owner->id))
            ->where('transaction_type', 'sale')
            ->fastPaginate(100); // أو simplePaginate(10)

        $transactions->getCollection()->transform(function ($transaction) {
            return [
                'user_id' => $transaction->user->id,
                'user_name' => $transaction->user->name,
                'property_id' => $transaction->property->id,
                'property_name' => $transaction->property->name,
                'is_paid' => $transaction->is_paid,
            ];
        });

        return $transactions;

    }

    public function getRenters()
    {
        return $this->getPayments('rent');
    }

    public function getInstallments()
    {
        return $this->getPayments('installment');
    }


// استدعاء الدوال
    public function getRenterDetails($userId, $propertyId)
    {
        return $this->getPaymentDetails($userId, $propertyId, 'rent');
    }

    public function getInstallmentDetails($userId, $propertyId)
    {
        return $this->getPaymentDetails($userId, $propertyId, 'installment');
    }
}
