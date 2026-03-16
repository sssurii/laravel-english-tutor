<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginScreen extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void
    {
        $validated = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validated, $this->remember)) {
            $this->addError('email', 'The provided credentials are incorrect.');
            return;
        }

        session()->regenerate();

        $this->redirectIntended(route('chat', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login-screen')
            ->layout('layouts.mobile-auth');
    }
}
