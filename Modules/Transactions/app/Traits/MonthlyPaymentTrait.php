<?php

namespace Modules\Transactions\app\Traits;

use Illuminate\Support\Facades\DB;
use Modules\Transactions\App\Models\MonthlyPayment;
use Modules\Transactions\App\Models\PropertyTransaction;

trait MonthlyPaymentTrait
{
    public function createMonthlyPayments(PropertyTransaction $transaction, $durationMonths)
    {
        $monthlyAmount = $transaction->price;
        $today = now();

        // الحصول على الدفعة الموجودة مسبقاً لهذه المعاملة
        $existingPayment = $this->getExistingPayment($transaction)->first();

        // التحقق مما إذا كانت هناك دفعة مستحقة اليوم
        $existingPaymentForToday = $this->getExistingPayment($transaction)
            ->whereDate('due_date', $today->toDateString())
            ->whereNot('payment_status', 'paid')
            ->first();

        DB::transaction(function () use ($existingPayment, $existingPaymentForToday, $transaction, $durationMonths, $monthlyAmount, $today) {
            if ($existingPaymentForToday) {
                // تحديث الدفعة المستحقة اليوم إلى "مدفوعة" وتحديث تاريخ الاستحقاق التالي
                $existingPaymentForToday->update([
                    'due_date' => $today,
                    'payment_status' => 'paid',
                ]);
            } elseif (!$existingPayment) {
                // إنشاء جميع الدفعات لأول مرة
                // تجهيز البيانات للإدراج دفعة واحدة
                $payments = [];

                for ($i = 0; $i < $durationMonths; $i++) {
                    $dueDate = $today->copy()->addMonths($i);
                    $nextDueDate = $today->copy()->addMonths($i + 1);

                    $payments[] = [
                        'property_transaction_id' => $transaction->id,
                        'amount' => $monthlyAmount,
                        'due_date' => $dueDate,
                        'payment_status' => $i == 0 ? 'paid' : 'pending',
                        'next_due_date' => $nextDueDate,
                        'property_id' => $transaction->property_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // إدراج البيانات دفعة واحدة باستخدام insert
                MonthlyPayment::insert($payments);
            }
        });
    }

    private function getExistingPayment($transaction)
    {
        return MonthlyPayment::where('property_transaction_id', $transaction->id);
    }

}
