<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function checkout(CheckoutRequest $request)
    {
        $attrs = $request->validated();

        $max_price = 99999999;

        Stripe::setApiKey(config('cashier.secret'));

        if($attrs['amount'] > $max_price) {
            $attrs['amount'] = $max_price;
        }

        try {
            $payment = PaymentIntent::create([
                'amount' => $attrs['amount'],
                'currency' => 'usd',
                'receipt_email' => auth()->user()->email
            ]);

            return response()->json(['clientSecret' => $payment->client_secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
