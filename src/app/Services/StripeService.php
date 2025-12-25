<?php

namespace App\Services;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    public function createCheckoutSession(PurchaseRequest $request, $itemId)
    {
        $item = Item::findOrFail($itemId);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card', 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            'metadata' => [
                'user_id' => auth()->id(),
                'item_id' => $item->id,
                'paymethod' => $request->paymethod,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building
            ],

            'success_url' => route('stripe.order') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/purchase/' . $item->id),
        ]);

        return $session;
    }

    public function retrieveSession(string $sessionId)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        return Session::retrieve($sessionId);
    }
}