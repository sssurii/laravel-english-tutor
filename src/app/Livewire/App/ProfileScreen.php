<?php

namespace App\Livewire\App;

use Illuminate\Validation\Rule;
use Livewire\Component;

class ProfileScreen extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = (string) $user?->name;
        $this->email = (string) $user?->email;
    }

    public function saveProfile(): void
    {
        $user = auth()->user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        $this->reset(['password', 'password_confirmation']);
        session()->flash('status', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.app.profile-screen')
            ->layout('layouts.mobile-app');
    }
}
