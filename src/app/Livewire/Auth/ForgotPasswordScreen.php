<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPasswordScreen extends Component
{
    public string $email = '';
    public ?string $status = null;

    public function sendResetLink(): void
    {
        $validated = $this->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $validated['email'],
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = __($status);
            return;
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-screen')
            ->layout('layouts.mobile-auth');
    }
}
