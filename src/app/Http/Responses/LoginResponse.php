<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;


class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (is_null($user->last_login_at)) {
            $user->sendEmailVerificationNotification();
            $user->update([
                'last_login_at' => now()
            ]);
            return redirect('/verify');
        }

        $user->update([
            'last_login_at' => now()
        ]);

        return redirect('/');
    }
}
