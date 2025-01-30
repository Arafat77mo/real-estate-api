<?php

namespace Modules\Transactions\app\Traits;

use Modules\Transactions\App\Models\MonthlyPayment;
use Modules\Transactions\App\Models\PropertyTransaction;

trait MonthlyPaymentTrait
{
    public function createMonthlyPayments(PropertyTransaction $transaction, $durationMonths)
    {
        // التحقق إذا كان هناك سجل مع نفس property_id في جدول monthly_payments
        $existingPayment = MonthlyPayment::where('property_id', $transaction->property_id)
            ->whereDate('next_due_date', '2025-03-02') // التحقق إذا كانت next_due_date هي تاريخ اليوم
            ->first();

        // إذا كان موجود، نقوم بتحديثه
        if ($existingPayment) {
            // إذا كان next_due_date يساوي تاريخ اليوم، نحدث due_date إلى تاريخ اليوم ونجعل الحالة "تم الدفع"
            $existingPayment->update([
                'due_date' => now(),  // تحديث due_date إلى تاريخ اليوم
                'payment_status' => 'paid',  // تحديث حالة الدفع إلى مدفوع
            ]);
        } else {
            // نبدأ من 1 لأخر دفعة
            for ($i = 1; $i <= $durationMonths; $i++) {
                // إذا كانت هذه هي الدفعة الأولى
                $paymentStatus = ($i == 1) ? 'paid' : 'pending'; // تعيين حالة الدفع للدفعة الأولى "مدفوعه"

                MonthlyPayment::create([
                    'property_transaction_id' => $transaction->id,
                    'amount' => $transaction->price,
                    'due_date' => now()->addMonths($i),  // استخدام تاريخ الاستحقاق المتغير
                    'payment_status' => $paymentStatus,  // تعيين الحالة كـ "مدفوع" للدفعة الأولى و"معلق" للباقي
                    'next_due_date' => now()->addMonths($i),
                    'property_id' => $transaction->property_id,  // إضافة property_id
                ]);
            }
        }
    }
}
