<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\URL;

class MailTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_navigate_to_verification_link()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/verify')
                ->assertSee('認証はこちらから')
                ->click('@verify-email-button')
                ->pause(1000)
                ->visit('http://mailhog:8025')
                ->assertSee('MailHog');
        });
    }
}
