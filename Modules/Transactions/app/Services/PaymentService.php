<?php

namespace Modules\Transactions\app\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Transactions\App\Models\PropertyTransaction;
use Modules\Transactions\app\Traits\InstallmentTrait;
use Modules\Transactions\app\Traits\MonthlyPaymentTrait;
use Modules\Transactions\app\Traits\StripePaymentTrait;
use Modules\Transactions\app\Traits\TamaraTabbyPaymentTrait;

class PaymentService
{
    use StripePaymentTrait, TamaraTabbyPaymentTrait, InstallmentTrait, MonthlyPaymentTrait;

    private function createTransaction($request, $type, $isPaid = false)
    {
        return PropertyTransaction::create([
            'property_id' => $request->property_id,
            'user_id' => $request->user_id,
            'transaction_type' => $type,
            'price' => $request->price,
            'is_paid' => $isPaid,
        ]);
    }

    /**
     * Validate payment method.
     */
    private function validatePaymentMethod($paymentMethod, $validMethods)
    {
        if (!in_array($paymentMethod, $validMethods)) {
            throw new Exception(__('messages.invalid_payment_method'));
        }
    }

    /**
     * Handle sale transaction.
     */
    public function handleSale($request)
    {
        $this->validatePaymentMethod($request->payment_method, ['stripe']);

        $existingTransaction = PropertyTransaction::where('property_id', $request->property_id)
            ->where('transaction_type', 'sale')
            ->where('is_paid', true)
            ->first();

        if ($existingTransaction) {
            throw new Exception('تم بيع هذا العقار بالفعل');
        }

        DB::beginTransaction();
        try {

            $this->processStripePayment($request->price, $request->payment_method_id);
            $transaction = $this->createTransaction($request, 'sale', true);
            DB::commit();
            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Handle installment payment transaction.
     */
    public function handleInstallments($request)
    {
        $this->validatePaymentMethod($request->payment_method, ['tamara', 'tabby']);

        DB::beginTransaction();
        try {
            // Process installment payment
// الحصول على بيانات العميل من الجلسة
            $user = auth()->user();

            // استخدام بيانات العميل المسجل
            $customerData = [
                'name' => $user->name,
                'email' => $user->email,
            ];

            $response = $this->processTamaraOrTabbyPayment($request->price, $request->payment_method, $customerData);
            if (isset($response['error'])) {
                return ['error' => $response['error']];
            }

            $transaction = $this->createTransaction($request, 'installment');
            $this->createInstallments($transaction, $request->duration_months);

            DB::commit();
            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Handle rent transaction.
     */
    public function handleRent($request)
    {
        $this->validatePaymentMethod($request->payment_method, ['stripe', 'tamara', 'tabby']);

        DB::beginTransaction();
        try {
            // Process rent payment
            if ($request->payment_method === 'stripe') {
                $this->processStripePayment($request->price, $request->payment_method_id);
            } else {
// الحصول على بيانات العميل من الجلسة
                $user = auth()->user();

                // استخدام بيانات العميل المسجل
                $customerData = [
                    'name' => $user->name,
                    'email' => $user->email,
                ];

                $response = $this->processTamaraOrTabbyPayment($request->price, $request->payment_method, $customerData);
                if (isset($response['error'])) {
                    return ['error' => $response['error']];
                }
            }

            $transaction = $this->createTransaction($request, 'rent');
            $this->createMonthlyPayments($transaction, $request->duration_months);

            DB::commit();
            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

