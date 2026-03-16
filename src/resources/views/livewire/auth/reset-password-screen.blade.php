<section>
    <div class="mb-6">
        <p class="text-xs font-medium uppercase tracking-[0.24em] text-sky-200">Reset Password</p>
        <h1 class="mt-2 text-2xl font-semibold text-white">Choose new password</h1>
        <p class="mt-2 text-sm text-slate-300">Set a new password for your account.</p>
    </div>

    <form wire:submit="resetPassword" class="space-y-4">
        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-200">Email</label>
            <input
                wire:model.blur="email"
                id="email"
                type="email"
                autocomplete="email"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-sky-300 focus:outline-none"
                placeholder="you@example.com"
            >
            @error('email') <p class="mt-1 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-200">New Password</label>
            <input
                wire:model.blur="password"
                id="password"
                type="password"
                autocomplete="new-password"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-sky-300 focus:outline-none"
                placeholder="Minimum 8 characters"
            >
            @error('password') <p class="mt-1 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-200">Confirm Password</label>
            <input
                wire:model.blur="password_confirmation"
                id="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-sky-300 focus:outline-none"
                placeholder="Repeat your password"
            >
        </div>

        <button type="submit" class="mt-2 flex h-12 w-full items-center justify-center rounded-2xl bg-sky-300 font-semibold text-slate-950 transition active:scale-[0.99]">
            <span wire:loading.remove wire:target="resetPassword">Reset password</span>
            <span wire:loading wire:target="resetPassword">Resetting...</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-300">
        <a href="{{ route('login') }}" wire:navigate class="font-semibold text-sky-200">Back to sign in</a>
    </p>
</section>
