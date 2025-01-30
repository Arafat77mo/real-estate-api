<?php

namespace Modules\Transactions\app\Traits;

use Stripe\StripeClient;

trait StripePaymentTrait
{
    public function processStripePayment($amount, $paymentMethodId)
    {
        // Initialize Stripe client
        $stripe = new StripeClient(env('STRIPE_SECRET'));


            // Create PaymentIntent
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method' => $paymentMethodId, // Payment Method ID
                'confirm' => true, // Confirm the PaymentIntent right after creation
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);

            // Check the status of the payment
            if ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_source_action') {
                // Additional authentication is required
                return [
                    'status' => 'requires_action',
                    'payment_intent_client_secret' => $paymentIntent->client_secret,
                ];
            }

            if ($paymentIntent->status === 'succeeded') {
                // Payment succeeded
                return [
                    'status' => 'success',
                    'payment_intent' => $paymentIntent,
                ];
            }

            return [
                'status' => 'failed',
                'message' => 'Payment failed or was not completed.',
            ];


    }
}
