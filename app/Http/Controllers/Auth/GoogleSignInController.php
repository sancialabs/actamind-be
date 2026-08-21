<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleSignInController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::query()->updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]
        );

        $token = $user->createToken('auth-token')->plainTextToken;

        // Redirect back to frontend with a one-time code instead of the real token,
        // to avoid a long-lived token sitting in browser history/logs.
        $tempCode = Str::random(40);
        cache()->put("oauth_code:{$tempCode}", $token, now()->addMinutes(2));

        return redirect(config('app.frontend_url')."/callback?code={$tempCode}");
    }
}
