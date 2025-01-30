<?php

namespace Modules\Transactions\app\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait TamaraTabbyPaymentTrait
{
    protected function getPaymentClient()
    {
        return new Client(); // إنشاء كائن Guzzle
    }

    /**
     * تنفيذ الدفع عبر Tamara أو Tabby
     */
    public function processTamaraOrTabbyPayment($amount, $paymentMethod, $customerData)
    {
        if ($paymentMethod === 'tamara') {
            return $this->processTamaraPayment($amount, $customerData);
        } elseif ($paymentMethod === 'tabby') {
            return $this->processTabbyPayment($amount, $customerData);
        }

        return ['error' => __('messages.invalid_payment_method')];
    }

    /**
     * تنفيذ الدفع عبر Tamara
     */
    private function processTamaraPayment($amount, $customerData)
    {
        try {
            $client = $this->getPaymentClient();
            $response = $client->post(env('TAMARA_API_URL') . '/checkout', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer " . env('TAMARA_API_KEY'),
                ],
                'json' => [
                    'order_reference_id' => uniqid(),
                    'order_amount' => [
                        'amount' => $amount,
                        'currency' => 'SAR'
                    ],
                    'consumer' => $customerData,
                    'callback_url' => route('payment.success'),
                    'cancel_url' => route('payment.cancel'),
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * تنفيذ الدفع عبر Tabby
     */
    private function processTabbyPayment($amount, $customerData)
    {
        try {
            $client = $this->getPaymentClient();
            $response = $client->post(env('TABBY_API_URL') . '/checkout', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer " . env('TABBY_API_KEY'),
                ],
                'json' => [
                    'amount' => $amount,
                    'currency' => 'SAR',
                    'buyer' => $customerData,
                    'success_url' => route('payment.success'),
                    'failure_url' => route('payment.cancel'),
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
