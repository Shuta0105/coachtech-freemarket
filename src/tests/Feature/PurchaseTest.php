<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $mockSession = (object)[
            'payment_status' => 'paid',
            'metadata' => (object)[
                'user_id' => $user->id,
                'item_id' => $item->id,
                'paymethod' => '2',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル',
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

        $response = $this->postJson(
            "/create-session/{$item->id}",
            [
                'paymethod' => '2',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル',
            ]
        )->assertJson(['id' => 'cs_test_dummy']);

        $response = $this->get('/stripe/order?session_id=cs_test_dummy');

        $response->assertRedirect('/');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'paymethod' => '2',
        ]);
    }

    public function test_purchased_item_is_displayed_with_sold_label()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $mockSession = (object)[
            'payment_status' => 'paid',
            'metadata' => (object)[
                'user_id' => $user->id,
                'item_id' => $item->id,
                'paymethod' => '2',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル',
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

        $response = $this->postJson(
            "/create-session/{$item->id}",
            [
                'paymethod' => '2',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル',
            ]
        )->assertJson(['id' => 'cs_test_dummy']);

        $response = $this->get('/stripe/order?session_id=cs_test_dummy');

        $response->assertRedirect('/');

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    public function test_purchased_item_is_displayed_in_profile_page()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();

        $mockSession = (object)[
            'payment_status' => 'paid',
            'metadata' => (object)[
                'user_id' => $user->id,
                'item_id' => $item->id,
                'paymethod' => '2',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル',
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

        $response = $this->postJson(
            "/create-session/{$item->id}",
            [
                'paymethod' => '2',
                'post_code' => '123-4567',
                'address' => '東京都渋谷区',
                'building' => 'テストビル',
            ]
        )->assertJson(['id' => 'cs_test_dummy']);

        $response = $this->get('/stripe/order?session_id=cs_test_dummy');

        $response->assertRedirect('/');

        $response = $this->get('/mypage?page=buy');

        $response->assertSee($item->name);
    }
}
