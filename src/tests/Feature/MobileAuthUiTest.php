<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders mobile auth guest screens', function (): void {
    $this->get('/login')->assertOk();
    $this->get('/register')->assertOk();
    $this->get('/forgot-password')->assertOk();
    $this->get('/reset-password/test-token?email=test@example.com')->assertOk();
    $this->get('/chat')->assertRedirect('/login');
    $this->get('/profile')->assertRedirect('/login');
});

it('registers through mobile ui and reaches dashboard', function (): void {
    Livewire::test(\App\Livewire\Auth\RegisterScreen::class)
        ->set('name', 'Maya Student')
        ->set('email', 'maya@example.com')
        ->set('password', 'secret1234')
        ->set('password_confirmation', 'secret1234')
        ->call('register')
        ->assertRedirect('/chat');

    $this->assertAuthenticated();
    $this->get('/chat')->assertOk()->assertSee('Text Chat');
});

it('allows existing user to login through mobile ui', function (): void {
    User::factory()->create([
        'name' => 'Aman User',
        'email' => 'aman@example.com',
        'password' => 'secret1234',
    ]);

    Livewire::test(\App\Livewire\Auth\LoginScreen::class)
        ->set('email', 'aman@example.com')
        ->set('password', 'secret1234')
        ->call('login')
        ->assertRedirect('/chat');

    $this->assertAuthenticated();
});

it('sends a reset password link from mobile ui', function (): void {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'reset@example.com',
    ]);

    Livewire::test(\App\Livewire\Auth\ForgotPasswordScreen::class)
        ->set('email', 'reset@example.com')
        ->call('sendResetLink')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

it('resets password from mobile ui with valid token', function (): void {
    $user = User::factory()->create([
        'email' => 'recover@example.com',
        'password' => 'old-secret1234',
    ]);

    $token = Password::broker()->createToken($user);

    Livewire::withQueryParams(['email' => 'recover@example.com'])
        ->test(\App\Livewire\Auth\ResetPasswordScreen::class, ['token' => $token])
        ->set('password', 'new-secret1234')
        ->set('password_confirmation', 'new-secret1234')
        ->call('resetPassword')
        ->assertRedirect('/login');

    expect(Hash::check('new-secret1234', $user->fresh()->password))->toBeTrue();
});

it('allows a logged in user to send chat messages', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(\App\Livewire\App\ChatScreen::class)
        ->set('message', 'Hello, I want to practice English')
        ->call('sendMessage')
        ->assertHasNoErrors()
        ->assertSee('Hello, I want to practice English');
});

it('allows a logged in user to update basic profile', function (): void {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\App\ProfileScreen::class)
        ->set('name', 'New Name')
        ->set('email', 'new@example.com')
        ->set('password', 'secret9999')
        ->set('password_confirmation', 'secret9999')
        ->call('saveProfile')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toBe('New Name');
    expect($user->email)->toBe('new@example.com');
    expect(Hash::check('secret9999', $user->password))->toBeTrue();
});
