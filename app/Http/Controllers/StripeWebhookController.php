<?php

namespace App\Http\Controllers;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class StripeWebhookController extends CashierWebhookController
{
    /**
     * Handle a successful payment webhook event.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentSuccess($payload)
    {
        // Handle the payment success event here.
        // You can access event data from the $payload array.

        // For example, log the event:
        Log::info('Payment success webhook received:', $payload);

        return response('Webhook Handled', 200);
    }
    
    function handleChargeSucceeded($payload){
        Log::info('Change success webhook received:', $payload);

        return response('Webhook Handled', 200);
    }
    function handleSubscriptionCreated($payload){
        Log::info('Subscription created webhook received:', $payload);

        return response('Webhook Handled', 200);
    }
}
