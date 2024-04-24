<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $stripeService = new StripeService();

        // Log::info($_SERVER['HTTP_STRIPE_SIGNATURE']);
        $signatureHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                @file_get_contents('php://input'),
                $signatureHeader,
                config('cashier.webhook.secret'),
            );
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                break;
                // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
                Log::info('Received unknown event type ' . $event->type);
                break;
        }

        http_response_code(200);
    }
}
