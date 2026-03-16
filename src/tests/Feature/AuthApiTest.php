<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registers logs in and logs out on happy path', function (): void {
    $registerResponse = $this->postJson('/api/v1/register', [
        'name' => 'Raj User',
        'email' => 'raj.user@example.com',
        'password' => 'secret1234',
        'password_confirmation' => 'secret1234',
    ]);

    $registerResponse
        ->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'user' => ['id', 'name', 'email'],
                'token',
            ],
        ]);

    $loginResponse = $this->postJson('/api/v1/login', [
        'email' => 'raj.user@example.com',
        'password' => 'secret1234',
    ]);

    $loginResponse
        ->assertOk()
        ->assertJsonPath('data.user.email', 'raj.user@example.com');

    $token = (string) $loginResponse->json('data.token');

    $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/v1/logout')
        ->assertOk()
        ->assertJsonPath('data.logged_out', true);
});

it('validates register payload', function (array $payload): void {
    $this->postJson('/api/v1/register', $payload)
        ->assertUnprocessable();
})->with([
    'missing name' => [[
        'email' => 'test@example.com',
        'password' => 'secret1234',
        'password_confirmation' => 'secret1234',
    ]],
    'invalid email' => [[
        'name' => 'Test',
        'email' => 'not-an-email',
        'password' => 'secret1234',
        'password_confirmation' => 'secret1234',
    ]],
    'password confirmation mismatch' => [[
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'secret1234',
        'password_confirmation' => 'different1234',
    ]],
]);

it('returns validation error for invalid login credentials', function (): void {
    $this->postJson('/api/v1/register', [
        'name' => 'Raj User',
        'email' => 'raj.user@example.com',
        'password' => 'secret1234',
        'password_confirmation' => 'secret1234',
    ])->assertCreated();

    $this->postJson('/api/v1/login', [
        'email' => 'raj.user@example.com',
        'password' => 'wrong-password',
    ])->assertUnprocessable();
});

it('requires authentication for logout edge case', function (): void {
    $this->postJson('/api/v1/logout')
        ->assertUnauthorized();
});
