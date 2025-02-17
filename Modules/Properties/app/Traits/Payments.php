<?php
namespace Modules\Properties\app\Traits;

use App\Models\UserSearch;
use Modules\Properties\app\Transformers\Owner\PaymentResource;
use Modules\Transactions\App\Models\MonthlyPayment;

trait Payments
{
    public function getPayments(string $transactionType)
    {
        $isInstallment = $transactionType === 'installment';
        $remainingMonthsSql = $isInstallment
            ? 'COUNT(CASE WHEN monthly_payments.payment_status = "pending" THEN 1 END)'
            : 'NULL';

        return MonthlyPayment::join('property_transactions as t', 'monthly_payments.property_transaction_id', '=', 't.id')
            ->join('properties as p', 't.property_id', '=', 'p.id')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->where('p.user_id', auth()->id())
            ->where('t.transaction_type', $transactionType)
            ->selectRaw("
        t.user_id,
        t.property_id,
        MAX(u.name) as user_name,
        MAX(JSON_UNQUOTE(JSON_EXTRACT(p.name, '$.\"".app()->getLocale()."\"'))) as property_name,
        SUM(CASE WHEN monthly_payments.payment_status = 'paid' THEN monthly_payments.amount ELSE 0 END) as paid_amount,
        SUM(CASE WHEN monthly_payments.payment_status = 'pending' THEN monthly_payments.amount ELSE 0 END) as pending_amount,
        COUNT(CASE WHEN monthly_payments.payment_status = 'paid' THEN 1 END) as paid_months,
        {$remainingMonthsSql} as remaining_months,
        MIN(CASE WHEN monthly_payments.payment_status = 'pending' THEN monthly_payments.due_date END) as next_due_date
    ")
            ->groupBy('t.user_id', 't.property_id')
            ->fastPaginate(6); // or ->paginate() for full pagination
    }

    /**
     * Get renter's payment history and remaining dues.
     */
    public function getPaymentDetails($userId, $propertyId, $transactionType)
    {
        $payments =MonthlyPayment::with(['transaction.user', 'transaction.property'])->whereHas('transaction', function($query) use ($userId, $propertyId, $transactionType) {
            $query->where('user_id', $userId)
                ->where('property_id', $propertyId)
                ->forOwner($this->owner->id)
                ->where('transaction_type', $transactionType);
        })
            ->orderBy('due_date')
            ->fastPaginate();

        $paidPayments = $payments->where('payment_status', 'paid');
        $pendingPayments = $payments->where('payment_status', 'pending');

        $paidAmount = $paidPayments->sum('amount');
        $pendingAmount = $pendingPayments->sum('amount');
        $paidMonths = $paidPayments->count();
        $remainingMonths =  $pendingPayments->count() ;
        $nextDueDate = $pendingPayments->first()->due_date ?? null;

        return [
            'user_id' => $payments->first()->transaction->user->id ?? '1',
            'user_name' => $payments->first()->transaction->user->name ?? 'N/A',
            'property_id' => $payments->first()->transaction->property->id ?? 'N/A',
            'property_name' => $payments->first()->transaction->property->name ?? 'N/A',
            'duration_months' => $payments->first()->transaction->duration_months ?? 'N/A',
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
            'paid_months' => $paidMonths,
            'remaining_months' => $remainingMonths,
            'next_due_date' => $nextDueDate,
            'payments' => PaymentResource::collection($payments),
        ];
    }

}
