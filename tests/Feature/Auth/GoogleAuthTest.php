<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

pest()->group('auth');

it('redirects to google', function () {
    $response = $this->get(route('google.redirect'));

    $response->assertRedirect();
    expect($response->headers->get('Location'))->toContain('accounts.google.com');
});

it('creates a new user on first google sign-in', function () {
    $socialiteUser = new SocialiteUser;
    $socialiteUser->id = '1234567890';
    $socialiteUser->email = 'newuser@gmail.com';
    $socialiteUser->name = 'New User';
    $socialiteUser->avatar = 'https://example.com/avatar.jpg';

    Socialite::shouldReceive('driver->stateless->user')
        ->andReturn($socialiteUser);

    $response = $this->get(route('google.callback'));

    $this->assertDatabaseHas('users', [
        'email' => 'newuser@gmail.com',
        'google_id' => '1234567890',
    ]);

    $response->assertRedirect();
    expect($response->headers->get('Location'))->toContain(config('app.frontend_url').'/callback');
});

it('logs in an existing google user and updates their google_id', function () {
    $existingUser = User::factory()->create([
        'email' => 'existing@gmail.com',
        'google_id' => null,
    ]);

    $socialiteUser = new SocialiteUser;
    $socialiteUser->id = '999888777';
    $socialiteUser->email = 'existing@gmail.com';
    $socialiteUser->name = $existingUser->name;
    $socialiteUser->avatar = null;

    Socialite::shouldReceive('driver->stateless->user')
        ->andReturn($socialiteUser);

    $this->get(route('google.callback'));

    expect($existingUser->fresh()->google_id)->toBe('999888777');
});

it('redirects with a one-time code, not a raw token, after google callback', function () {
    $socialiteUser = new SocialiteUser;
    $socialiteUser->id = '111222333';
    $socialiteUser->email = 'secure@gmail.com';
    $socialiteUser->name = 'Secure User';
    $socialiteUser->avatar = null;

    Socialite::shouldReceive('driver->stateless->user')
        ->andReturn($socialiteUser);

    $response = $this->get(route('google.callback'));

    $location = $response->headers->get('Location');

    parse_str(parse_url($location, PHP_URL_QUERY), $params);

    expect($params)->toHaveKey('code')
        ->and(strlen($params['code']))->toBe(40);
});
