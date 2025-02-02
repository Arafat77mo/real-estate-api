<?php
namespace Modules\Properties\app\Services\Owner;
use Modules\Transactions\App\Models\MonthlyPayment;

use Modules\Properties\app\Transformers\Owner\InstallmentResource;
use Modules\Properties\app\Transformers\Owner\PaymentResource;
use Modules\Properties\app\Transformers\Owner\RenterResource;
use Modules\Transactions\App\Models\PropertyTransaction;

class UserReportService
{
    /**
     * Get renters report including paid amount and user details.
     */
    public function getRenters()
    {
      $payments = MonthlyPayment::whereHas('transaction', function($query)  {
        $query
            ->where('transaction_type', 'rent');
    })
        ->orderBy('due_date')
        ->get();

        $paidPayments = $payments->where('payment_status', 'paid');
        $pendingPayments = $payments->where('payment_status', 'pending');

        $paidAmount = $paidPayments->sum('amount');
        $pendingAmount = $pendingPayments->sum('amount');
        $paidMonths = $paidPayments->count();
        $nextDueDate = $pendingPayments->first()->due_date ?? null;

        return [
            'user_id' => $payments->first()->transaction->user->id ?? '1',
            'user_name' => $payments->first()->transaction->user->name ?? 'N/A',  // استخدام الكائن وليس المصفوفة
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
            'paid_months' => $paidMonths,
            'next_due_date' => $nextDueDate,
        ];
    }

    /**
     * Get installments report including paid amount and user details.
     */
    public function getInstallments()
    {
        $payments = MonthlyPayment::whereHas('transaction', function($query)  {
            $query
                ->where('transaction_type', 'installment');
        })
            ->orderBy('due_date')
            ->get();

        $paidPayments = $payments->where('payment_status', 'paid');
        $pendingPayments = $payments->where('payment_status', 'pending');

        $paidAmount = $paidPayments->sum('amount');
        $pendingAmount = $pendingPayments->sum('amount');
        $paidMonths = $paidPayments->count();
        $remainingMonths = $pendingPayments->count();

        $nextDueDate = $pendingPayments->first()->due_date ?? null;

        return [
            'user_id' => $payments->first()->transaction->user->id ?? '1',
            'user_name' => $payments->first()->transaction->user->name ?? 'N/A',  // استخدام الكائن وليس المصفوفة
            'paid_amount' => $paidAmount,
            'remaining_amount' => $pendingAmount,
            'paid_months' => $paidMonths,
            'remaining_months' => $remainingMonths,
            'next_due_date' => $nextDueDate,

        ];
    }


    /**
     * Get buyers report including paid amount and user details.
     */
    public function getBuyers()
    {
        return PropertyTransaction::where('transaction_type', 'sale')
            ->with('user')  // إضافة تفاصيل المستخدم
            ->get(['user_id', 'property_id', 'price','is_paid', 'created_at']);




    }

    /**
     * Get renter's payment history and remaining dues.
     */
    public function getRenterDetails($userId)
    {
        $payments = MonthlyPayment::whereHas('transaction', function($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('transaction_type', 'rent');
        })
            ->orderBy('due_date')
            ->get();

        $paidPayments = $payments->where('payment_status', 'paid');
        $pendingPayments = $payments->where('payment_status', 'pending');

        $paidAmount = $paidPayments->sum('amount');
        $pendingAmount = $pendingPayments->sum('amount');
        $paidMonths = $paidPayments->count();
        $nextDueDate = $pendingPayments->first()->due_date ?? null;

        return [
            'user_id' => $payments->first()->transaction->user->id ?? '1',
            'user_name' => $payments->first()->transaction->user->name ?? 'N/A',  // استخدام الكائن وليس المصفوفة
            'paid_amount' => $paidAmount,
            'pending_amount' => $pendingAmount,
            'paid_months' => $paidMonths,
            'next_due_date' => $nextDueDate,
            'payments' => PaymentResource::collection($payments),
        ];
    }

    /**
     * Get installment details for a user, including paid months, remaining months, and next due date.
     */
    public function getInstallmentDetails($userId)
    {
        $payments = MonthlyPayment::whereHas('transaction', function($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('transaction_type', 'installment');
        })
            ->orderBy('due_date')
            ->get();

        $paidPayments = $payments->where('payment_status', 'paid');
        $pendingPayments = $payments->where('payment_status', 'pending');

        $paidAmount = $paidPayments->sum('amount');
        $pendingAmount = $pendingPayments->sum('amount');
        $paidMonths = $paidPayments->count();
        $remainingMonths = $pendingPayments->count();

        $nextDueDate = $pendingPayments->first()->due_date ?? null;

        return [
            'user_id' => $payments->first()->transaction->user->id ?? '1',
            'user_name' => $payments->first()->transaction->user->name ?? 'N/A',  // استخدام الكائن وليس المصفوفة
            'paid_amount' => $paidAmount,
            'remaining_amount' => $pendingAmount,
            'paid_months' => $paidMonths,
            'remaining_months' => $remainingMonths,
            'next_due_date' => $nextDueDate,
            'payments' => PaymentResource::collection($payments),

        ];
    }
}
