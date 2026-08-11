<?php

pest()->group('auth');

test('exchange a valid code for a token', function () {
    $token = 'fake-sanctum-token-value';
    $code = 'valid-one-time-code';

    cache()->put("oauth_code:{$code}", $token, now()->addMinutes(2));

    $response = $this->post(route('auth.exchange'), ['code' => $code]);

    $response->assertOk()
        ->assertJson(['token' => $token]);
});

test('reject an invalid code', function () {
    $response = $this->post(route('auth.exchange'), ['code' => 'does-not-exist']);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Invalid or expired code']);
});

test('reject a code that has already been used', function () {
    $token = 'fake-sanctum-token-value';
    $code = 'one-time-code';

    cache()->put("oauth_code:{$code}", $token, now()->addMinutes(2));

    // first exchange succeeds
    $this->post(route('auth.exchange'), ['code' => $code])->assertOk();

    // second attempt with the same code should fail
    $response = $this->post(route('auth.exchange'), ['code' => $code]);

    $response->assertStatus(401);
});

test('validate that code is required', function () {
    $response = $this->post(route('auth.exchange'), []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['code']);
});
