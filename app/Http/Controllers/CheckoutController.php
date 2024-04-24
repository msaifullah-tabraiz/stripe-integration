<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Exception;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('checkout');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->for == 'create') {
            $stripeService = new StripeService();

            // header('Content-Type: application/json');

            try {


                // retrieve JSON from POST body
                // $jsonStr = file_get_contents('php://input');
                // $jsonObj = json_decode($jsonStr);

                // Create a PaymentIntent with amount and currency
                // https://docs.stripe.com/api/payment_intents/create
                $paymentIntent = $stripeService->stripe->paymentIntents->create([
                    'amount' => $stripeService->calculateOrderAmount($request->data['items']),
                    'currency' => 'usd',
                    'description' => 'Payment for order #' . '101' . ' of amount USD ' . '200',
                    // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
                    'automatic_payment_methods' => [
                        'enabled' => true,
                    ],
                    'metadata' => [
                        'order_id' => 101,
                        'amount' => 200,
                    ]
                ]);

                $output = [
                    'clientSecret' => $paymentIntent->client_secret,
                ];

                return json_encode($output);
            } catch (Exception $e) {
                http_response_code(500);
                return json_encode(['error' => $e->getMessage()]);
            }
            return 'hi';
        }
        return 'why';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
