<?php

namespace Modules\Transactions\app\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Transactions\App\Models\MonthlyPayment;
use Modules\Transactions\App\Models\PropertyTransaction;
use Modules\Transactions\app\Traits\InstallmentTrait;
use Modules\Transactions\app\Traits\MonthlyPaymentTrait;
use Modules\Transactions\app\Traits\StripePaymentTrait;
use Modules\Transactions\app\Traits\TamaraTabbyPaymentTrait;

class PaymentService
{
    use StripePaymentTrait, TamaraTabbyPaymentTrait, InstallmentTrait, MonthlyPaymentTrait;

    protected $propertyTransaction;
    protected $monthlyPayment;
    protected $authUser;

    public function __construct(PropertyTransaction $propertyTransaction, MonthlyPayment $monthlyPayment)
    {
        $this->propertyTransaction = $propertyTransaction;
        $this->monthlyPayment = $monthlyPayment;
        $this->authUser = Auth::user(); // حفظ المستخدم المسجل
    }

    private function createTransaction($request, $type, $isPaid = false)
    {
        return $this->propertyTransaction->firstOrCreate(
            ['property_id' => $request->property_id],
            [
                'user_id' => $this->authUser->id,
                'transaction_type' => $type,
                'price' => $request->price,
                'is_paid' => $isPaid,
            ]
        );
    }

    private function validatePaymentMethod($paymentMethod, $validMethods)
    {
        if (!in_array($paymentMethod, $validMethods)) {
            throw new Exception(__('messages.invalid_payment_method'));
        }
    }

    public function handleSale($request)
    {
        $this->validatePaymentMethod($request->payment_method, ['stripe']);

        $existingTransaction = $this->propertyTransaction
            ->where('property_id', $request->property_id)
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

    public function handleInstallments($request)
    {
        $this->validatePaymentMethod($request->payment_method, ['tamara', 'tabby']);

        DB::beginTransaction();
        try {
            $customerData = [
                'name' => $this->authUser->name,
                'email' => $this->authUser->email,
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

    public function handleRent($request)
    {
        $this->validatePaymentMethod($request->payment_method, ['stripe', 'tamara', 'tabby']);

        DB::beginTransaction();
        try {
            if ($request->payment_method === 'stripe') {
                $this->processStripePayment($request->price, $request->payment_method_id);

            } else {
                $customerData = [
                    'name' => $this->authUser->name,
                    'email' => $this->authUser->email,
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
