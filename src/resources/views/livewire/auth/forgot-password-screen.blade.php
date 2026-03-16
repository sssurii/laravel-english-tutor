<section>
    <div class="mb-6">
        <p class="text-xs font-medium uppercase tracking-[0.24em] text-amber-200">Password Recovery</p>
        <h1 class="mt-2 text-2xl font-semibold text-white">Forgot password?</h1>
        <p class="mt-2 text-sm text-slate-300">Enter your email and we will send a password reset link.</p>
    </div>

    @if ($status)
        <div class="mb-4 rounded-2xl border border-emerald-300/35 bg-emerald-300/10 px-4 py-3 text-sm text-emerald-200">
            {{ $status }}
        </div>
    @endif

    <form wire:submit="sendResetLink" class="space-y-4">
        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-200">Email</label>
            <input
                wire:model.blur="email"
                id="email"
                type="email"
                autocomplete="email"
                class="h-12 w-full rounded-2xl border border-white/20 bg-slate-950/50 px-4 text-base text-slate-100 placeholder:text-slate-400 focus:border-amber-300 focus:outline-none"
                placeholder="you@example.com"
            >
            @error('email') <p class="mt-1 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="mt-2 flex h-12 w-full items-center justify-center rounded-2xl bg-amber-300 font-semibold text-slate-950 transition active:scale-[0.99]">
            <span wire:loading.remove wire:target="sendResetLink">Send reset link</span>
            <span wire:loading wire:target="sendResetLink">Sending...</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-300">
        Remembered it?
        <a href="{{ route('login') }}" wire:navigate class="font-semibold text-amber-200">Back to sign in</a>
    </p>
</section>
