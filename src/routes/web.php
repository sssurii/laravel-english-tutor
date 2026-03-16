<?php

use App\Livewire\Auth\ForgotPasswordScreen;
use App\Livewire\Auth\LoginScreen;
use App\Livewire\Auth\ResetPasswordScreen;
use App\Livewire\Auth\RegisterScreen;
use App\Livewire\App\ChatScreen;
use App\Livewire\App\ProfileScreen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('chat')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', LoginScreen::class)->name('login');
    Route::get('/register', RegisterScreen::class)->name('register');
    Route::get('/forgot-password', ForgotPasswordScreen::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPasswordScreen::class)->name('password.reset');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/chat', ChatScreen::class)->name('chat');
    Route::get('/profile', ProfileScreen::class)->name('profile');
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');

    Route::get('/dashboard', function () {
        return redirect()->route('chat');
    })->name('dashboard');
});
