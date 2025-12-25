<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function createSession(PurchaseRequest $request, $itemId, StripeService $stripe)
    {
        $session = $stripe->createCheckoutSession($request, $itemId);

        return response()->json([
            'id' => $session->id,
        ]);
    }

    public function order(Request $request, StripeService $stripe)
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect('/');
        }

        $session = $stripe->retrieveSession($sessionId);

        if ($session->payment_status === 'paid') {
            Order::create([
                'user_id' => $session->metadata->user_id,
                'item_id' => $session->metadata->item_id,
                'paymethod' => $session->metadata->paymethod,
                'post_code' => $session->metadata->post_code,
                'address' => $session->metadata->address,
                'building' => $session->metadata->building
            ]);
        }
        return redirect('/');
    }
}
