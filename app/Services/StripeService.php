<?php

namespace App\Services;

use Exception;
use Stripe\Exception\CardException;
use Stripe\StripeClient;

class StripeService
{
    public $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('cashier.secret'));
    }

    public function calculateOrderAmount(array $items): int
    {
        // Replace this constant with a calculation of the order's amount
        // Calculate the order total on the server to prevent
        // people from directly manipulating the amount on the client
        return 1400;
    }

    public function createToken(array $requestCardData)
    {

        $token = null;
        try {
            $token = $this->stripe->tokens->create([
                'card' => [
                    'number' => $requestCardData['card_number'],
                    'exp_month' => $requestCardData['month'],
                    'exp_year' => $requestCardData['year'],
                    'cvc' => $requestCardData['cvv']
                ]
            ]);
        } catch (CardException $e) {
            $token['error'] = $e->getError()->message;
        } catch (Exception $e) {
            $token['error'] = $e->getMessage();
        }
        return $token;
    }

    public function createCharge($tokenId, $amount)
    {
        $charge = null;
        try {
            $charge = $this->stripe->charges->create([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $tokenId,
                'description' => 'My first payment'
            ]);
        } catch (Exception $e) {
            $charge['error'] = $e->getMessage();
        }
        return $charge;
    }
}
