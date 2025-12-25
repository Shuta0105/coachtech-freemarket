<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShippingAddressTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_changed_address_reflects_on_purchase_page()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        $user_profile = UserProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $this->browse(function (Browser $browser) use ($user, $item) {
            $newAddress = [
                'post_code' => '123-4567',
                'address'   => '東京都渋谷区1',
                'building'  => 'ビル101',
            ];

            $browser->loginAs($user)
                ->visit("/purchase/{$item->id}")
                ->pause(500)
                ->click('.purchase__address-change')
                ->assertPathIs("/purchase/address/{$item->id}")
                ->type('post_code', $newAddress['post_code'])
                ->type('address', $newAddress['address'])
                ->type('building', $newAddress['building'])
                ->press('更新する')
                ->assertPathIs("/purchase/{$item->id}")
                ->assertSee($newAddress['post_code'])
                ->assertSee($newAddress['address'])
                ->assertSee($newAddress['building']);
        });
    }
}
