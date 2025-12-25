<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipping_address_stored_after_item_purchased()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $shippingAddress = [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ];

        $mockSession = (object)[
            'payment_status' => 'paid',
            'metadata' => (object)[
                'user_id' => $user->id,
                'item_id' => $item->id,
                'paymethod' => '2',
                'post_code' => $shippingAddress['post_code'],
                'address' => $shippingAddress['address'],
                'building' => $shippingAddress['building'],
            ],
        ];

        $mock = Mockery::mock(StripeService::class);
        $mock->shouldReceive('createCheckoutSession')
            ->once()
            ->andReturn((object)[
                'id' => 'cs_test_dummy',
            ]);
        $mock->shouldReceive('retrieveSession')
            ->once()
            ->andReturn($mockSession);

        $this->app->instance(StripeService::class, $mock);

        $response = $this->post("/purchase/address/{$item->id}", [
            'post_code' => $shippingAddress['post_code'],
            'address' => $shippingAddress['address'],
            'building' => $shippingAddress['building']
        ]);

        $response->assertRedirect("/purchase/{$item->id}");

        $response = $this->postJson(
            "/create-session/{$item->id}",
            [
                'paymethod' => '2',
                'post_code' => $shippingAddress['post_code'],
                'address' => $shippingAddress['address'],
                'building' => $shippingAddress['building']
            ]
        )->assertJson(['id' => 'cs_test_dummy']);

        $response = $this->get('/stripe/order?session_id=cs_test_dummy');

        $response->assertRedirect('/');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'paymethod' => '2',
            'post_code' => $shippingAddress['post_code'],
            'address' => $shippingAddress['address'],
            'building' => $shippingAddress['building']
        ]);
    }
}
