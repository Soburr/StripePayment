<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Stripe;

class StripePaymentController extends Controller
{
    public function stripePost(Request $request) {
      try {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET '));
        
        $res = $stripe->tokens->create([
            'card' => [
                'number' => $request->number,
                'exp_month' => $request->exp_number,
                'exp_year' => $request->exp_year,
                'cvv' => $request->cvv
            ],    
        ]);

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET '));

        $response = $stripe->charges->create([
                'amount' => $request->amount,
                'currency' => 'usd',
                'source' => $res->id,
                'description' => $request->description
        ]);

        return response()->json([$response->status], 201);

      } catch(Exception $ex) {
        return response()->json(['response'=> 'Error'], 500);
      }
    }
}
